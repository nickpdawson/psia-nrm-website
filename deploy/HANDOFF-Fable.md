# Deployment Handoff — for Fable (site coder)

**Prepared:** June 29, 2026 · by Nick + Claude (PM)
**Goal:** Deploy the existing NRM WordPress build to the Azure environment National provisioned, connect it to the managed MySQL, and stand it up on the staging URL. This is the **staging deploy** (N3) — DNS cutover comes later.

---

## 1. Azure environment (verified live in the portal, Jun 29)

| Thing | Value |
|-------|-------|
| Tenant | **PSIA-AASI** (thesnowpros.org), tenant id `5255010f-5fc6-4e7b-8d8a-93481e0e0062` |
| Subscription | **PSIA-NRM**, id `f39024c5-e896-49f0-98ea-92266e0f0aa2` |
| Resource group | **PSIA-NRM** |
| Region | **Canada Central** |
| Access | Nick has guest access (nd@nickdawson.net). Fable needs its own path in — see §5. |

### App Service (web host)
- **Name:** `PSIA-NRM-Website` · **Status:** Running
- **Plan:** `ASP-PSIANRM-8941` — **B2, Linux** (2 vCPU / 3.5 GB) ✅ right-sized
- **Default/staging hostname:** `psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net`
- **⚠️ Publishing model: CONTAINER.** Currently running Microsoft's placeholder image `mcr.microsoft.com/appsvc/staticsite:latest` (runtime status "Issues Detected" — expected; it's an empty shell). **There is no PHP/WordPress runtime yet.**

### Database (MySQL)
- **Name:** `psia-nrm-website-server` — Azure Database for MySQL **Flexible Server**
- **Endpoint (FQDN):** `psia-nrm-website-server.mysql.database.azure.com`
- **Admin login:** `nqnssouudx` (password NOT captured — get from Sean/Nick out-of-band, see §5)
- **⚠️ SKU: General Purpose, D2ds_v4, 2 vCores, 8 GiB** — over-provisioned vs. plan (see §6 cost flag)
- **Version:** older (Azure flags support ending Dec 31 2026 — likely 5.7). Recommend upgrading to **8.0** before load.
- **Networking:** reachable only via **private endpoint** on the VNet + private DNS zone `privatelink.mysql.database.azure.com`. **Not publicly accessible** — see §4.

### Networking
- VNet `vnet-wldeqcjg`; private endpoint `endpoint-c4xufjnjhwziy` + NIC; private DNS zone for MySQL.
- The DB FQDN resolves to a **private IP from inside the VNet only.** The App Service must be VNet-integrated (a VNet exists — confirm integration is on) so the container can reach the DB.

---

## 2. What to deploy (the app)

The finished build lives in this repo under `wordpress/` (synced from the `psia-nrm.dzsec.net` prototype):

- **Custom theme `psia-nrm`** — `wordpress/themes/` (front-page, archives, single templates, `functions.php` defining CPTs/taxonomies/meta, `style.css` with the PSIA brand system).
- **Plugin** `wordpress/plugins/nrm-import.php` — one-time importer (79 people, 12 events, 29 schools).
- **mu-plugin** `wordpress/mu-plugins/nrm-hardening.php`.
- **Seed data** `wordpress/people.json`, `wordpress/events.json`.
- Reference: `PSIA-NRM_Implementation_Log.md` (full architecture), `PSIA-NRM_Website_Redesign_PRD*.md`.

Custom post types: `nrm_member`, `nrm_event`. Taxonomies: `nrm_role`, `nrm_discipline`, `nrm_specialty`, `nrm_school`, `nrm_event_type`. Dynamic org pages read `nrm_query_*` meta.

---

## 3. Recommended deployment approach

Because the App Service is **container** publishing model, two clean paths:

- **Option A (matches the prototype — recommended):** build a **WordPress container image** (the prototype ran WP 6.7 / PHP 8.3 via Docker) with the `psia-nrm` theme + plugins baked in, push to a registry (Azure Container Registry or Docker Hub), and point the Web App at it. Set DB connection via App Service **application settings** (env vars): `WORDPRESS_DB_HOST`, `WORDPRESS_DB_NAME`, `WORDPRESS_DB_USER`, `WORDPRESS_DB_PASSWORD`, plus `WORDPRESS_CONFIG_EXTRA` for SSL.
- **Option B:** reconfigure the Web App to the **built-in PHP 8.x** runtime (code deploy via ZIP/Git/GitHub Actions) instead of a container. Cleaner OS updates, but changes the publishing model Sean set.

Either way: MySQL **requires SSL** — include the Azure MySQL CA / set `MYSQL_SSL` appropriately in wp-config. Create the WordPress schema/database on the flexible server first.

---

## 4. Important gotcha — the DB is private

The MySQL server has **no public access** (private endpoint only). Practical implications:
- You **cannot** connect to the DB from a local machine or a plain CI runner. Options: (a) run schema setup + the `nrm-import.php` import **from inside the App Service** (SSH/Kudu console, see §5); (b) temporarily enable a public access firewall rule on the flexible server for migration, then disable it; (c) use a jump path on the VNet.
- Confirm the App Service **VNet integration** is enabled so the running container resolves and reaches `psia-nrm-website-server.mysql.database.azure.com` privately.

---

## 5. How Fable drives Azure (automation model — no portal clicking)

The portal is just a GUI over the ARM API. Recommended way for Fable to operate:

- **Azure CLI (`az`)** for everything: `az webapp config container set` (point the app at the WordPress image), `az webapp config appsettings set` (DB env vars), `az webapp deploy`, and `az mysql flexible-server update --sku-name ...` (the downsize in §6).
- **Authenticate with a service principal**, not Nick's login: `az login --service-principal -u <appId> -p <secret> --tenant thesnowpros.org`. Scoped to the **PSIA-NRM resource group** with **Contributor**. Non-interactive, auditable, revocable.
- **SSH into the App Service** for in-container work: `az webapp ssh -g PSIA-NRM -n PSIA-NRM-Website` (or the Kudu SCM SSH console). **This is the required path to the DB** — the MySQL server is private (§4), so wp-cli, schema creation, and the `nrm-import.php` import run from inside the container over the VNet.

### Access Fable needs from Nick / Sean (secrets — not in this doc)

- [ ] **Service principal** scoped to RG `PSIA-NRM` (Contributor) — **Sean must create it** (National's tenant); Nick can't self-grant. Deliver appId + secret out-of-band. *(Alternative: Sean adds Fable's identity as Contributor on the RG.)*
- [ ] **MySQL admin password** for `nqnssouudx` (from Sean, or Nick resets it via portal "Reset password" / `az mysql flexible-server update --admin-password`).
- [ ] Decision on **registry** (spin up an Azure Container Registry in the RG — `az acr create` — or use Docker Hub).
- [ ] Confirm the **import approach**: in-container SSH (preferred) vs. temporary public DB firewall rule.

---

## 6. ⚠️ Cost flag to resolve with Sean (independent of the deploy)

- The **MySQL is General Purpose D2ds_v4** (~$130–190/mo) — the single thing breaking the ~$40/mo target. Ask Sean to **downsize to a Burstable tier (B1ms or B2s, ~$15–35/mo)**; plenty for this workload. Best to do **before** data is loaded. (`az mysql flexible-server update --sku-name Standard_B1ms --tier Burstable`)
- App Service **B2 (~$25/mo)** is correctly sized — leave it.
- Net: right-sized, this environment lands near the promised ~$40/mo. As-is, it's ~$150–215/mo.

---

## 7. Definition of done (staging)

- [ ] WordPress running on the App Service at the `*.azurewebsites.net` staging URL
- [ ] Connected to the flexible-server MySQL over SSL (private)
- [ ] `psia-nrm` theme active; CPTs/taxonomies registered
- [ ] `nrm-import.php` run → 79 members, 12 events, 29 schools present
- [ ] Admin reachable; basic smoke test of front page, member directory, event archive, a member profile
- [ ] (Then, separately: OAuth login wiring once National issues client_id/secret — tracked as N5/S5)
