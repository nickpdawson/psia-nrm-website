# Old psia-nrm.org Source Audit

Fill in by SSHing to the Plesk Obsidian 18 host + logging into the WP admin. The output of this audit drives Phase 4 (content reconciliation).

## DNS

```
$ dig psia-nrm.org +noall +answer
$ dig www.psia-nrm.org +noall +answer
$ dig MX psia-nrm.org +noall +answer
$ dig TXT psia-nrm.org +noall +answer    # SPF, DMARC, verifications
```

| Record | Value | TTL | Notes |
|---|---|---|---|
| A `psia-nrm.org` | | | |
| A `www` | | | |
| MX | | | If at Plesk box, leave alone at cutover |
| SPF (TXT) | | | |
| DMARC (TXT @_dmarc) | | | |
| DKIM | | | |

## Plesk / Hosting

- Plesk version:
- PHP version:
- MySQL/MariaDB version:
- Disk usage of webroot:
- Disk usage of `wp-content/uploads/`:
- SMTP: Plesk-hosted, external (which provider?), or PHP `mail()`?
- Cron jobs (`wp-cron` real cron vs WP fake cron):

## WordPress

```
$ wp core version
$ wp theme list
$ wp plugin list --status=active
$ wp post list --post_type=page --format=count
$ wp post list --post_type=post --format=count
$ wp option get permalink_structure
```

- WP version:
- Active theme name + slug:
- Theme is custom / parent-child / off-the-shelf?
- Page builder in use (Elementor, Divi, Beaver, native Gutenberg, classic editor, none):
- Permalink structure:
- Page count:
- Post count:
- Custom post types (besides default):
- Custom taxonomies:
- Multisite? (`wp core is-installed --network`):

## Active plugins (full list)

| Plugin | Version | Purpose | Migrate / drop / replace |
|---|---|---|---|
| | | | |

## Page inventory (every published page)

| Slug | Title | Page-builder content? | Action (import / rebuild / discard) |
|---|---|---|---|
| `about-us` | About Us | | |
| `contact` | Contact | | |
| `rules-regulations` | Rules & Regulations | | |
| `scholarships` | Scholarships | | |
| `sponsors` | Sponsors | | |
| `member-schools` | Member Schools | discarded — replaced by `nrm_school` taxonomy loop | |
| `board-of-directors` | Board of Directors | discarded — replaced by `nrm_role=Board Member` loop | |
| | | | |

## Sample raw `post_content` for 5 representative pages

(Paste from `wp post get <id> --field=post_content` — drives the page-builder decision.)

```

```

## Media

- Total uploads size:
- File count:
- Years covered:
- Anything outside `wp-content/uploads/`?

## Forms / contact endpoints

- Contact form plugin and recipient address:
- Newsletter signup → MailChimp embed or plugin?

## ⚠️ Ed-staff invoice system (LAUNCH DEPENDENCY — fill this in first)

This system lives on the current site. Decommissioning the old host breaks it, so we must understand it before cutover and decouple it (likely onto a subdomain on the old host).

- What is it? (WordPress plugin / WooCommerce / custom code / third-party embed):
- Plugin name + version, or file locations if custom:
- URL/path where ed staff access it (e.g. `psia-nrm.org/invoices`):
- Who uses it and how (ed staff submit? office approves? Jessica administers?):
- Data it holds — custom DB tables outside `wp_*`? Where are invoices/records stored?:
- Payment processor / integration (Stripe, PayPal, Snowpros API, manual)?:
- Email it sends (from address, SMTP) and to whom:
- Dependencies on other plugins or the theme:
- Can it run standalone on a subdomain (`invoices.psia-nrm.org`) on the old host? Any hardcoded URLs to fix?:
- Decision: keep-as-is on subdomain through launch → revamp as fast-follow? OR migrate now?:

## Redirect / SEO

- Existing redirect rules (in `.htaccess`, plugin like Redirection, or absent):
- Sitemap URL and which plugin generates it:
- robots.txt content:

## Risk flags

- [ ] Page builder shortcodes in any page → schedule rebuild time
- [ ] Plugins that hold critical data (e.g. event manager) → custom export needed
- [ ] MX entangled with web hosting → leave alone at DNS cutover
- [ ] SMTP entangled with Plesk → switch to Postmark before cutover
- [ ] Custom database tables outside the `wp_*` schema (some plugins create their own)
