# PSIA-NRM Website Revamp — Project Plan

**Owner / PM:** Nick Dawson (NRM Board)
**Status:** Board-approved; in execution
**Plan date:** June 8, 2026
**Target launch:** Week of **August 3, 2026** (summer offseason — Herb's "best time of our year")
**Hosting decision:** **Host on National's Azure tenant** (Sean Steele / National IT), targeting a ~$40/mo footprint (B2 App Service + MySQL Flexible Server)

---

## 1. Where this stands

The redesign is **built and approved**. Nick built a full WordPress production prototype (custom `psia-nrm` theme, custom post types for members and events, 79 real NRM people imported, 12 events, PSIA national API integration mapped) currently live at `psia-nrm.dzsec.net`. The board reviewed and approved it.

The project is **not blocked on design or build** — it's blocked on **infrastructure and access from National**, and on **two slow-moving counterparties**:

- **Sean Steele (National IT, thesnowpros.org)** owes the Azure subscription, access credentials, OAuth client, and a hosting/payment agreement. His last update (May 22) was that the request "got buried" and he'd get to it "next week," pending a payment agreement he needs to work out with Jeff.
- **Herb Davis (NRM CEO)** has given the green light ("GTG to get rolling," May 15) and offered to negotiate cost with Jeff, but after the winter server debacle he floated hosting *independent* of National. **Decision for this plan: proceed with National's Azure**, with independent hosting kept as a documented fallback (see §6).

Nick is driving because both counterparties are moving slowly. This plan exists to make the critical path, owners, and dates explicit so nothing else gets "buried."

---

## 2. Goal & success criteria

Launch a modern, mobile-first `psia-nrm.org` that replaces the dated 2015 Modernize-theme brochure site, on reliable hosting, with PSIA single-sign-on for members and staff.

**Done means:**

1. New site live on `psia-nrm.org` (DNS cut over, HTTPS valid).
2. Members and staff can log in with their existing PSIA-AASI credentials (OAuth/OIDC).
3. All legacy content reconciled — nothing important lost, redirects in place.
4. Staff can manage content (members, events, pages) without a developer.
5. Hosting is in the **org's name**, billed at a predictable rate (~$40/mo target), with a signed agreement.

---

## 3. Phases, milestones & target dates

The critical path runs through National granting access. Phases 1–2 are the unblock; 3–5 are the build-out Nick controls; 6–7 are cutover.

| # | Phase | Key deliverables | Owner | Target |
|---|-------|------------------|-------|--------|
| 0 | **Decision & alignment** | Confirm National-hosted path with Herb; lock cost target (~$40/mo, B2+MySQL); Herb closes $ with Jeff | Nick + Herb | **Jun 12** |
| 1 | **Hosting agreement** | Sean + Jeff produce the hosting/payment agreement; NRM signs | Sean / Jeff → Nick/Herb | **Jun 19** |
| 2 | **Azure access** | New PSIA-NRM subscription on National tenant; guest access for nick@nickdawson.net; App Service (B2 Linux) + MySQL Flexible Server provisioned; old site cloned to staging | Sean | **Jun 26** |
| 3 | **OAuth client** | `client_id`/`client_secret` issued; redirect URIs confirmed; `authorization_code`+PKCE; scopes `openid PSIA` | Sean → Nick | **Jun 26** |
| 4 | **Deploy to Azure staging** | Push custom theme + import; stand up site on Azure staging URL; smoke test; wire OAuth login plugin | Nick | **Jul 10** |
| 5 | **Content reconciliation** | Complete source audit of old site; migrate/rebuild remaining legacy pages; finalize homepage & "Our Mountain" copy; logos in SVG | Nick (+ Jessica/Jill for copy) | **Jul 24** |
| 6 | **Board/staff UAT** | Staff test content editing + login on staging; board final sign-off; fix punch list | Nick + Board/Staff | **Jul 31** |
| 7 | **DNS cutover & launch** | DNS switch (domain hosting decision: National GoDaddy vs. Network Solutions); HTTPS; redirects; monitor. **Do NOT decommission the old Plesk site until the ed-staff invoice system is decoupled** (moved to a subdomain on the old host) — invoicing lives there. | Nick + Sean | **Week of Aug 3** |

> Dates assume Sean delivers Phases 1–3 on schedule. **Every slip by Sean pushes launch one-for-one.** That's why the nudge cadence (§5, §7) matters.

---

## 4. Critical path & dependencies

```
Herb closes $ with Jeff ──► Sean produces hosting agreement ──► NRM signs
                                                                   │
                                          ┌────────────────────────┴───────────┐
                                          ▼                                     ▼
                              Sean provisions Azure                  Sean issues OAuth client
                              (subscription, access,                 (client_id/secret,
                               B2 App Service + MySQL,                redirect URIs)
                               clone old site)                                  │
                                          │                                     │
                                          └──────────────┬──────────────────────┘
                                                         ▼
                                          Nick deploys theme + import to Azure staging
                                                         ▼
                                          Content reconciliation + OAuth login wired
                                                         ▼
                                          Board/staff UAT ──► DNS cutover ──► LAUNCH
```

**Hard external dependencies (all owned by National):** hosting agreement, Azure access, OAuth client registration. Everything Nick controls (theme, data, templates, migration tooling) is effectively ready and waiting on these.

---

## 5. PM cadence & how I (Claude) am helping

- **Action-item tracker:** `PROJECT-TRACKER.md` in this folder — the living owner/owes list with status and due dates. Source of truth for "who owes what."
- **Nudge emails:** Drafts to Sean and Herb in `nudges/` to unstick the current blockers. Nick reviews and sends.
- **Weekly status check:** A recurring review (Mondays) that flags anything overdue from Sean or Herb and refreshes the tracker, so slips surface immediately instead of going quiet for two weeks.

---

## 6. Fallback: independent hosting (Herb's preference, kept warm)

If National stalls again, drags the agreement, or the cost can't be held near $40/mo, the documented Plan B is **independent hosting in the org's name** (managed WP host — Cloudways/SiteGround/WP Engine, $20–40/mo). Trade-off: full control and speed, but the OAuth/SSO integration with National still requires Sean to register the client either way, so independence removes the *hosting* dependency but **not** the *OAuth* dependency. Decision trigger: if Phase 1 (agreement) or Phase 2 (access) slips past **June 30**, escalate the fallback with Herb.

---

## 7. Risks & mitigations

| Risk | Likelihood | Impact | Mitigation |
|------|-----------|--------|------------|
| Sean stays slow / items "get buried" again | High | High | Weekly nudge cadence; specific asks with deadlines; Herb escalates to Jeff |
| Hosting cost creeps back up (DB sized to General Purpose, P-tier App Service) | Medium | Medium | Hold to B2 App Service + burstable MySQL in writing; pure passthrough, no admin fees |
| Hosting agreement negotiation drags | Medium | High | Herb owns the $ conversation with Jeff in parallel, not after, technical setup |
| OAuth client registration is the long pole (security review on National's side) | Medium | High | Send the full OAuth registration request (already drafted in `migration/`) now, in parallel with hosting |
| Legacy content/data lost at cutover | Low | Medium | Complete `migration/source-audit.md` before cutover; redirects; keep old Plesk site live through a grace period |
| MX/email entangled with old Plesk host | Medium | Medium | Audit MX/SPF/DMARC before DNS cutover; leave mail records alone unless deliberately moving |

---

## 8. Open decisions

> **Nick has board carte blanche (as of ~Jun 29) on all web + content decisions** — these are his calls to make and move on; none require going back to the board.

1. **Domain/DNS host at cutover** — move to National's GoDaddy, or keep on Network Solutions? (Sean offered either.) Recommendation: keep DNS where NRM controls it.
2. **Event system ownership** — NRM-managed events vs. continue linking to the national calendar (`thesnowpros.org/calendar?divisions=7`). Affects content scope.
3. **ACF Pro** ($49/yr) for richer staff editing UX — yes/no.
4. **Final launch domain** — straight to `psia-nrm.org`, or stage on `new.psia-nrm.org` for a board-review window first.
5. **Ed-staff invoice system** (raised by Herb, Jun 10) — **it lives on the current site, so it's a launch dependency, not a free fast-follow.** Decommissioning the old host breaks invoicing. **Recommendation: decouple before cutover** (move it to a subdomain like `invoices.psia-nrm.org` that keeps running on the old host), launch the new site on schedule, then revamp invoicing as a scoped fast-follow. First step is the source audit (what is it, exactly?). Needs Sean (it's on National's host) and overlaps Jessica (N6).

---

*Maintained by Nick + Claude. See `PROJECT-TRACKER.md` for live action items and `nudges/` for outbound email drafts.*
