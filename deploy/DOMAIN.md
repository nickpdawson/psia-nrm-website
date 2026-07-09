# Domain setup — northernrocky.org

The production root domain is **northernrocky.org** (board decision 2026-07-09; more inclusive
than psia-nrm.org across disciplines). psia-nrm.org will 301-redirect to it at go-live.

## Current state (live)

- `northernrocky.org` and `www.northernrocky.org` serve the Azure App Service, **HTTPS, Cloudflare-proxied**.
- Browser → Cloudflare edge (Universal SSL) → **Full** → Azure origin (Azure-managed cert, SNI-bound).
- WordPress is **domain-agnostic** (see below), so it serves identically on the new domain and the
  `*.azurewebsites.net` origin without canonical-redirect loops.

## Cloudflare (zone `9dc25908da21b9e6b7d134b739aa5c76`)

Token (DNS/Worker/Pages scope) at `~/Development/Notes/private/northernrocky_api.txt`.

DNS records:
| Type | Name | Content | Proxied |
|---|---|---|---|
| CNAME | northernrocky.org | psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net | ✅ (flattened at apex) |
| CNAME | www | (same) | ✅ |
| TXT | asuid.northernrocky.org | `0151DB58…` (Azure custom-domain verify id) | n/a |
| TXT | asuid.www.northernrocky.org | (same) | n/a |

Zone settings: SSL mode **Full**, Always-Use-HTTPS on. **TODO:** upgrade SSL mode to **Full (strict)**
in the CF dashboard — the API token lacks Zone-Settings edit. Azure's origin cert is publicly valid,
so strict will pass.

## Azure

- Custom hostnames `northernrocky.org` + `www.northernrocky.org` added to `PSIA-NRM-Website`.
- **Azure managed certs** issued + SNI-bound (expire 2027-01-09, auto-renew).
- ⚠️ **Managed-cert renewal under a permanent CF proxy can fail** domain validation (~45d before
  expiry, so ~Nov 2026). Mitigate by briefly grey-clouding the apex/www during renewal, OR switch to
  a **Cloudflare Origin CA cert** (needs an Origin-CA-capable token/key — current token can't) uploaded
  to Azure as a 15-yr private cert (the pattern used for demo.ownchart.me).

## WordPress domain-agnostic config

Set as the `WORDPRESS_CONFIG_EXTRA` app setting (and in `deploy.sh configure`):

```php
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') { $_SERVER['HTTPS'] = 'on'; }
define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
if (!empty($_SERVER['HTTP_HOST']) && preg_match('/^[a-z0-9.-]+$/i', $_SERVER['HTTP_HOST'])) {
    define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
    define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
}
```

The `HTTP_HOST` regex guard blocks host-header injection from defining a hostile site URL.

## Go-live checklist (not done yet)

- [ ] psia-nrm.org / www.psia-nrm.org → `https://northernrocky.org/$1` 301 (National's registrar — Sean).
- [ ] Path redirects from the old URL structure on northernrocky.org (see `redirect-map.md`).
- [ ] Pick canonical apex vs www; redirect the other.
- [ ] CF SSL mode → Full (strict).
- [ ] OAuth client redirect URIs include northernrocky.org (already in the request doc).
- [ ] Flip `blog_public` on (allow search indexing) once ready.
- [ ] (Hardening) Restrict the Azure origin to Cloudflare IP ranges via App Service Access Restrictions,
      so the `*.azurewebsites.net` origin can't be hit directly bypassing the proxy. Do carefully at
      go-live (misconfig can lock out deploys/Kudu — keep an SCM allow rule).
