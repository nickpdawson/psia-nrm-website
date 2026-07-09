# PSIA-NRM Redirect Map — old psia-nrm.org → new site (northernrocky.org)

**Purpose:** DNS-cutover redirect map. Every URL from the old www.psia-nrm.org site (86 mirrored
pages + the `/thestoke/` newsletter archive) mapped to its new-site path, verified against the
staging site on 2026-07-03. All non-410 targets returned HTTP 200 at verification time.

**⚠️ DOMAIN CHANGE (2026-07-09):** the new production root is **`northernrocky.org`** (not psia-nrm.org).
Two redirect layers apply at go-live:
1. **Legacy-domain redirect (front psia-nrm.org, at National's registrar/host):**
   `psia-nrm.org/*` and `www.psia-nrm.org/*` → `https://northernrocky.org/*` (301, path-preserving).
   psia-nrm.org DNS is NOT in the northernrocky.org Cloudflare account — coordinate with Sean.
2. **Path redirects (table below), applied ON northernrocky.org** — map the *old site's* URL structure
   to the new pages. In the nginx/htaccess blocks below, the new-site host is now `northernrocky.org`.

**Generated:** 2026-07-03 · **Domain updated:** 2026-07-09

## Mapping table

| # | Old URL path | New path | Notes |
|---|---|---|---|
| 1 | `/` (a.k.a. `/HOME/`) | `/` | Homepage |
| 2 | `/ada-accommodation/` | `/ada-accommodation/` | 1:1 |
| 3 | `/be-strong-stay-safe-and-keep-sharing-the-stoke/` | **410 gone** | COVID-era post, pruned |
| 4 | `/current-board-of-directors-candidates/` | **410 gone** | 2016 BOD candidates, pruned |
| 5 | `/disciplines/` | `/disciplines/` | 1:1 |
| 6 | `/disciplines/adaptive/` | `/adaptive-discipline/` | Discipline pages flattened to top level |
| 7 | `/disciplines/alpine/` | `/alpine/` | |
| 8 | `/disciplines/alpine/alpine-certification/` | `/alpine/` | Cert hub merged into discipline page |
| 9 | `/disciplines/alpine/alpine-certification/alpine-level-i/` | `/alpine/` | Per-level page merged |
| 10 | `/disciplines/alpine/alpine-certification/alpine-level-ii/` | `/alpine/` | Per-level page merged |
| 11 | `/disciplines/alpine/alpine-certification/alpine-level-iii/` | `/alpine/` | Per-level page merged |
| 12 | `/disciplines/alpine/alpine-ed-staff-program/` | `/alpine-ed-staff-program/` | 1:1 (flattened) |
| 13 | `/disciplines/cross-country/` | `/cross-country-discipline/` | |
| 14 | `/disciplines/cross-country/cross-country-certification/` | `/cross-country-discipline/` | Cert merged |
| 15 | `/disciplines/cross-country/cross-country-telemark-education-team-hiring-process/` | `/ed-team-hiring/` | Unified hiring page |
| 16 | `/disciplines/snowboard/` | `/snowboard/` | |
| 17 | `/disciplines/snowboard/snowboard-certification/` | `/snowboard/` | Cert merged |
| 18 | `/disciplines/snowboard/snowboard-certification/snowboard-level-i/` | `/snowboard/` | Per-level merged |
| 19 | `/disciplines/snowboard/snowboard-certification/snowboard-level-ii/` | `/snowboard/` | Per-level merged |
| 20 | `/disciplines/snowboard/snowboard-certification/snowboard-level-iii/` | `/snowboard/` | Per-level merged |
| 21 | `/disciplines/snowboard/nrm-snowboard-education-team-program/` | `/snowboard-ed-team-program/` | 1:1 (flattened) |
| 22 | `/disciplines/snowboard/snowboard-dce-program/` | `/snowboard-dce-program/` | 1:1 (flattened) |
| 23 | `/disciplines/telemark/` | `/telemark-discipline/` | |
| 24 | `/disciplines/telemark/telemark-certification/` | `/telemark-discipline/` | Cert merged |
| 25 | `/disciplines/telemark/level-i-assessment-activities/` | `/telemark-discipline/` | Per-level merged |
| 26 | `/disciplines/telemark/level-ii-assessment-activities/` | `/telemark-discipline/` | Per-level merged |
| 27 | `/disciplines/telemark/level-iii-assessment-activities/` | `/telemark-discipline/` | Per-level merged |
| 28 | `/disciplines/telemark/cross-country-telemark-education-team-hiring-process/` | `/ed-team-hiring/` | Unified hiring page |
| 29 | `/disciplines/telemark/telemark-education-team-hiring-process/` | `/ed-team-hiring/` | Unified hiring page |
| 30 | `/education-teams-paysheet/` | `/staff-resources/` | Timesheet lives in staff resources; the new site's staff page still deep-links the old URL — update that link at cutover |
| 31 | `/event-scheduling-tool/` | **410 gone** | psia-calendar utility, pruned |
| 32 | `/events/` | `/events/` | 1:1 |
| 33 | `/events/calendar/` | `/events/` | Old calendar → events archive |
| 34 | `/events/event-registration-faqs/` | `/event-registration-faqs/` | 1:1 (flattened) |
| 35 | `/events/fall-fest-2025/` | `/events/fall-fest-2025/` | 1:1 |
| 36 | `/events/unified-telemark-exams-2025/` | `/events/unified-telemark-exams/` | Slug lost the year |
| 37 | `/info/` | `/contact/` | Info hub dissolved; children mapped individually |
| 38 | `/info/board-matters/` | `/archive/` | Board matters → archive |
| 39 | `/info/board-of-directors-elections/` | `/board-elections/` | 1:1 (flattened) |
| 40 | `/info/fall-conference-recap/` | **410 gone** | 2019 recap, pruned |
| 41 | `/info/make-contact-with-us/` | `/contact/` | |
| 42 | `/info/region-awards/` | `/region-awards/` | 1:1 (flattened) |
| 43 | `/info/region-newsletter/` | `/archive/` | Newsletter archive → archive |
| 44 | `/info/region-sponsors/` → **410 gone** (NRM no longer has region sponsors — removed 2026-07-04) 
| 45 | `/membership/` | `/membership/` | Hub with anchor sections |
| 46 | `/membership/about-psia-aasi/` | `/about-psia-aasi/` | 1:1 (flattened) |
| 47 | `/membership/become-a-member/` | `/become-a-member/` | 1:1 (flattened) |
| 48 | `/membership/certification/` | `/membership/#maintaining-certification` | Merged into hub section |
| 49 | `/membership/certification-faqs-2/` | `/membership/#maintaining-certification` | Merged into hub section |
| 50 | `/membership/dues-information/` | `/membership/#dues-rates` | Merged into hub section |
| 51 | `/membership/faqs/` | `/membership/#faq` | Merged into hub section |
| 52 | `/membership/member-school/` | `/member-schools-info/` | |
| 53 | `/membership/member-school/event-request-form/` | `/member-schools-info/` | Event request form lives there now |
| 54 | `/membership/membership-benefits/` | `/membership-benefits/` | 1:1 (flattened) |
| 55 | `/membership/membership-transfer/` | `/membership/#faq` | Transfer info covered on hub |
| 56 | `/membership/new-member-guide-2/` | `/new-member-guide/` | 1:1 (flattened, cleaned slug) |
| 57 | `/membership/new-policies-on-ceus-and-inactive-status/` | `/membership/#maintaining-certification` | CEU policy merged |
| 58 | `/membership/non-psia-aasi-event-education-credit/` | `/membership/#maintaining-certification` | Credit-request form linked from hub |
| 59 | `/membership/reinstatement/` | `/membership/#reinstatement` | Merged into hub section |
| 60 | `/membership/rules-regulations/` | `/membership/#rules-documents` | Merged into hub section |
| 61 | `/membership/scholarships/` | `/scholarships/` | Top-level page now |
| 62 | `/plannedevents/` | **410 gone** | psia-calendar "Discipline Manager Event Portal" utility, pruned |
| 63 | `/psia-calendar-password-reset-page/` | **410 gone** | psia-calendar utility, pruned |
| 64 | `/specialties/` | `/disciplines/` | Specialties hub merged into disciplines overview |
| 65 | `/specialties/children/` | `/childrens-specialist/` | |
| 66 | `/specialties/children/childrens-specialist-certificates/` | `/childrens-specialist/` | Cert page merged |
| 67 | `/specialties/freestyle/` | `/freestyle-specialist/` | |
| 68 | `/specialties/freestyle/freestyle-specialist-certificates/` | `/freestyle-specialist/` | Cert page merged |
| 69 | `/specialties/freestyle/iron-team-program/` | `/whos-who/iron-team/` | Iron Team program → team page |
| 70 | `/specialties/senior-teaching/` | `/senior-teaching-specialty/` | |
| 71 | `/specialties/senior-teaching/senior-teaching-certificate/` | `/senior-teaching-specialty/` | Cert page merged |
| 72 | `/staff/` | `/staff-resources/` | |
| 73 | `/who-is-who/` | `/whos-who/` | |
| 74 | `/who-is-who/adaptive-education-team/` | `/whos-who/adaptive-education-team/` | |
| 75 | `/who-is-who/alpine-education-team/` | `/whos-who/alpine-education-team/` | |
| 76 | `/who-is-who/board-of-directors/` | `/whos-who/board-of-directors/` | |
| 77 | `/who-is-who/childrens-education-team/` | `/whos-who/childrens-education-team/` | |
| 78 | `/who-is-who/cross-country-education-team/` | `/whos-who/cross-country-education-team/` | |
| 79 | `/who-is-who/discipline-chairs/` | `/whos-who/discipline-chairs/` | |
| 80 | `/who-is-who/iron-team-freestyle-team/` | `/whos-who/iron-team/` | Slug simplified |
| 81 | `/who-is-who/member-schools/` | `/member-schools-info/` | School directory also on `/whos-who/` |
| 82 | `/who-is-who/office-staff/` | `/whos-who/office-staff/` | |
| 83 | `/who-is-who/senior-teaching-education-team/` | `/whos-who/senior-teaching-education-team/` | |
| 84 | `/who-is-who/snowboard-education-team/` | `/whos-who/snowboard-education-team/` | |
| 85 | `/who-is-who/specialty-chairs/` | `/whos-who/specialty-chairs/` | |
| 86 | `/who-is-who/telemark-education-team/` | `/whos-who/telemark-education-team/` | |
| 87 | `/thestoke/*` | `/wp-content/uploads/thestoke/*` | The Stoke HTML newsletter archive served as static uploads (e.g. `/thestoke/latest.html`, `/thestoke/2022_mar.html`) |

## Mapping notes

- **Discipline flattening:** old `/disciplines/<x>/` children collapse into single top-level
  discipline pages: `/alpine/`, `/snowboard/`, `/adaptive-discipline/`,
  `/cross-country-discipline/`, `/telemark-discipline/`. All per-level cert pages (Level I/II/III,
  assessment-activities) 301 to their discipline page.
- **Membership hub anchors:** dues, FAQs, rules, reinstatement, certification-maintenance pages
  merged into `/membership/` with anchors `#dues-rates`, `#faq`, `#rules-documents`,
  `#reinstatement`, `#maintaining-certification`, `#scholarships-grants` (all verified present as
  element ids on the hub). Fragments survive a 301 in every modern browser; use `[NE]` in Apache.
- **`/membership/scholarships/` → `/scholarships/`**, **`/info/make-contact-with-us/` → `/contact/`**.
- **who-is-who → whos-who:** near-1:1 rename; exceptions: `iron-team-freestyle-team` →
  `iron-team`, `member-schools` → `/member-schools-info/`.
- **`/staff/` → `/staff-resources/`**; `/education-teams-paysheet/` also lands there.
- **`/info/region-newsletter/` + `/info/board-matters/` → `/archive/`**.
- **410s (pruned, no replacement):** 2016 BOD candidates page, COVID "Be Strong" post,
  2019 Fall Conference recap, and the three psia-calendar utility pages
  (`/plannedevents/`, `/event-scheduling-tool/`, `/psia-calendar-password-reset-page/`).
- WordPress's own canonical-guess redirects on the new site already catch several of these
  (e.g. `/disciplines/alpine/` → `/alpine/`), but explicit rules below make the behavior
  deterministic and preserve the 410s.

## nginx rules

```nginx
# --- PSIA-NRM legacy redirects (place inside server {} for psia-nrm.org) ---

# The Stoke newsletter archive (static files now under uploads)
location ^~ /thestoke/ {
    rewrite ^/thestoke/(.*)$ /wp-content/uploads/thestoke/$1 permanent;
}

# Pruned pages -> 410
location = /be-strong-stay-safe-and-keep-sharing-the-stoke/ { return 410; }
location = /current-board-of-directors-candidates/          { return 410; }
location = /info/fall-conference-recap/                     { return 410; }
location = /event-scheduling-tool/                          { return 410; }
location = /plannedevents/                                  { return 410; }
location = /psia-calendar-password-reset-page/              { return 410; }

# Exact-match 301s
location = /HOME/                                                 { return 301 /; }
location = /ada-accommodation/                                    { return 301 /ada-accommodation/; }
location = /disciplines/adaptive/                                 { return 301 /adaptive-discipline/; }
location = /disciplines/alpine/                                   { return 301 /alpine/; }
location = /disciplines/alpine/alpine-certification/              { return 301 /alpine/; }
location = /disciplines/alpine/alpine-certification/alpine-level-i/   { return 301 /alpine/; }
location = /disciplines/alpine/alpine-certification/alpine-level-ii/  { return 301 /alpine/; }
location = /disciplines/alpine/alpine-certification/alpine-level-iii/ { return 301 /alpine/; }
location = /disciplines/alpine/alpine-ed-staff-program/           { return 301 /alpine-ed-staff-program/; }
location = /disciplines/cross-country/                            { return 301 /cross-country-discipline/; }
location = /disciplines/cross-country/cross-country-certification/ { return 301 /cross-country-discipline/; }
location = /disciplines/cross-country/cross-country-telemark-education-team-hiring-process/ { return 301 /ed-team-hiring/; }
location = /disciplines/snowboard/                                { return 301 /snowboard/; }
location = /disciplines/snowboard/snowboard-certification/        { return 301 /snowboard/; }
location = /disciplines/snowboard/snowboard-certification/snowboard-level-i/   { return 301 /snowboard/; }
location = /disciplines/snowboard/snowboard-certification/snowboard-level-ii/  { return 301 /snowboard/; }
location = /disciplines/snowboard/snowboard-certification/snowboard-level-iii/ { return 301 /snowboard/; }
location = /disciplines/snowboard/nrm-snowboard-education-team-program/ { return 301 /snowboard-ed-team-program/; }
location = /disciplines/snowboard/snowboard-dce-program/          { return 301 /snowboard-dce-program/; }
location = /disciplines/telemark/                                 { return 301 /telemark-discipline/; }
location = /disciplines/telemark/telemark-certification/          { return 301 /telemark-discipline/; }
location = /disciplines/telemark/level-i-assessment-activities/   { return 301 /telemark-discipline/; }
location = /disciplines/telemark/level-ii-assessment-activities/  { return 301 /telemark-discipline/; }
location = /disciplines/telemark/level-iii-assessment-activities/ { return 301 /telemark-discipline/; }
location = /disciplines/telemark/cross-country-telemark-education-team-hiring-process/ { return 301 /ed-team-hiring/; }
location = /disciplines/telemark/telemark-education-team-hiring-process/ { return 301 /ed-team-hiring/; }
location = /education-teams-paysheet/                             { return 301 /staff-resources/; }
location = /events/calendar/                                      { return 301 /events/; }
location = /events/event-registration-faqs/                       { return 301 /event-registration-faqs/; }
location = /events/unified-telemark-exams-2025/                   { return 301 /events/unified-telemark-exams/; }
location = /info/                                                 { return 301 /contact/; }
location = /info/board-matters/                                   { return 301 /archive/; }
location = /info/board-of-directors-elections/                    { return 301 /board-elections/; }
location = /info/make-contact-with-us/                            { return 301 /contact/; }
location = /info/region-awards/                                   { return 301 /region-awards/; }
location = /info/region-newsletter/                               { return 301 /archive/; }
location = /info/region-sponsors/ { return 410; }
location = /membership/about-psia-aasi/                           { return 301 /about-psia-aasi/; }
location = /membership/become-a-member/                           { return 301 /become-a-member/; }
location = /membership/certification/                             { return 301 "/membership/#maintaining-certification"; }
location = /membership/certification-faqs-2/                      { return 301 "/membership/#maintaining-certification"; }
location = /membership/dues-information/                          { return 301 "/membership/#dues-rates"; }
location = /membership/faqs/                                      { return 301 "/membership/#faq"; }
location = /membership/member-school/                             { return 301 /member-schools-info/; }
location = /membership/member-school/event-request-form/          { return 301 /member-schools-info/; }
location = /membership/membership-benefits/                       { return 301 /membership-benefits/; }
location = /membership/membership-transfer/                       { return 301 "/membership/#faq"; }
location = /membership/new-member-guide-2/                        { return 301 /new-member-guide/; }
location = /membership/new-policies-on-ceus-and-inactive-status/  { return 301 "/membership/#maintaining-certification"; }
location = /membership/non-psia-aasi-event-education-credit/      { return 301 "/membership/#maintaining-certification"; }
location = /membership/reinstatement/                             { return 301 "/membership/#reinstatement"; }
location = /membership/rules-regulations/                         { return 301 "/membership/#rules-documents"; }
location = /membership/scholarships/                              { return 301 /scholarships/; }
location = /specialties/                                          { return 301 /disciplines/; }
location = /specialties/children/                                 { return 301 /childrens-specialist/; }
location = /specialties/children/childrens-specialist-certificates/ { return 301 /childrens-specialist/; }
location = /specialties/freestyle/                                { return 301 /freestyle-specialist/; }
location = /specialties/freestyle/freestyle-specialist-certificates/ { return 301 /freestyle-specialist/; }
location = /specialties/freestyle/iron-team-program/              { return 301 /whos-who/iron-team/; }
location = /specialties/senior-teaching/                          { return 301 /senior-teaching-specialty/; }
location = /specialties/senior-teaching/senior-teaching-certificate/ { return 301 /senior-teaching-specialty/; }
location = /staff/                                                { return 301 /staff-resources/; }
location = /who-is-who/iron-team-freestyle-team/                  { return 301 /whos-who/iron-team/; }
location = /who-is-who/member-schools/                            { return 301 /member-schools-info/; }

# who-is-who -> whos-who (covers hub + all remaining 1:1 team pages)
location ^~ /who-is-who/ {
    rewrite ^/who-is-who/(.*)$ /whos-who/$1 permanent;
}
```

## Apache .htaccess rules

```apache
# --- PSIA-NRM legacy redirects ---
RewriteEngine On

# The Stoke newsletter archive
RewriteRule ^thestoke/(.*)$ /wp-content/uploads/thestoke/$1 [R=301,L]

# Pruned pages -> 410
RewriteRule ^be-strong-stay-safe-and-keep-sharing-the-stoke/?$ - [G,L]
RewriteRule ^current-board-of-directors-candidates/?$ - [G,L]
RewriteRule ^info/fall-conference-recap/?$ - [G,L]
RewriteRule ^event-scheduling-tool/?$ - [G,L]
RewriteRule ^plannedevents/?$ - [G,L]
RewriteRule ^psia-calendar-password-reset-page/?$ - [G,L]

# Exact 301s (NE preserves the #anchor fragments)
RewriteRule ^HOME/?$ / [R=301,L]
Redirect 301 /disciplines/adaptive/ /adaptive-discipline/
RewriteRule ^disciplines/alpine/alpine-certification(/alpine-level-(i|ii|iii))?/?$ /alpine/ [R=301,L]
Redirect 301 /disciplines/alpine/alpine-ed-staff-program/ /alpine-ed-staff-program/
Redirect 301 /disciplines/alpine/ /alpine/
RewriteRule ^disciplines/cross-country/cross-country-telemark-education-team-hiring-process/?$ /ed-team-hiring/ [R=301,L]
RewriteRule ^disciplines/cross-country(/cross-country-certification)?/?$ /cross-country-discipline/ [R=301,L]
RewriteRule ^disciplines/snowboard/nrm-snowboard-education-team-program/?$ /snowboard-ed-team-program/ [R=301,L]
Redirect 301 /disciplines/snowboard/snowboard-dce-program/ /snowboard-dce-program/
RewriteRule ^disciplines/snowboard(/snowboard-certification(/snowboard-level-(i|ii|iii))?)?/?$ /snowboard/ [R=301,L]
RewriteRule ^disciplines/telemark/(cross-country-telemark|telemark)-education-team-hiring-process/?$ /ed-team-hiring/ [R=301,L]
RewriteRule ^disciplines/telemark(/telemark-certification|/level-(i|ii|iii)-assessment-activities)?/?$ /telemark-discipline/ [R=301,L]
Redirect 301 /education-teams-paysheet/ /staff-resources/
Redirect 301 /events/calendar/ /events/
Redirect 301 /events/event-registration-faqs/ /event-registration-faqs/
Redirect 301 /events/unified-telemark-exams-2025/ /events/unified-telemark-exams/
Redirect 301 /info/board-matters/ /archive/
Redirect 301 /info/board-of-directors-elections/ /board-elections/
Redirect 301 /info/make-contact-with-us/ /contact/
Redirect 301 /info/region-awards/ /region-awards/
Redirect 301 /info/region-newsletter/ /archive/
Redirect gone /info/region-sponsors/
RewriteRule ^info/?$ /contact/ [R=301,L]
Redirect 301 /membership/about-psia-aasi/ /about-psia-aasi/
Redirect 301 /membership/become-a-member/ /become-a-member/
RewriteRule ^membership/certification(-faqs-2)?/?$ /membership/#maintaining-certification [R=301,NE,L]
RewriteRule ^membership/dues-information/?$ /membership/#dues-rates [R=301,NE,L]
RewriteRule ^membership/faqs/?$ /membership/#faq [R=301,NE,L]
Redirect 301 /membership/member-school/event-request-form/ /member-schools-info/
Redirect 301 /membership/member-school/ /member-schools-info/
Redirect 301 /membership/membership-benefits/ /membership-benefits/
RewriteRule ^membership/membership-transfer/?$ /membership/#faq [R=301,NE,L]
Redirect 301 /membership/new-member-guide-2/ /new-member-guide/
RewriteRule ^membership/(new-policies-on-ceus-and-inactive-status|non-psia-aasi-event-education-credit)/?$ /membership/#maintaining-certification [R=301,NE,L]
RewriteRule ^membership/reinstatement/?$ /membership/#reinstatement [R=301,NE,L]
RewriteRule ^membership/rules-regulations/?$ /membership/#rules-documents [R=301,NE,L]
Redirect 301 /membership/scholarships/ /scholarships/
RewriteRule ^specialties/children(/childrens-specialist-certificates)?/?$ /childrens-specialist/ [R=301,L]
RewriteRule ^specialties/freestyle/iron-team-program/?$ /whos-who/iron-team/ [R=301,L]
RewriteRule ^specialties/freestyle(/freestyle-specialist-certificates)?/?$ /freestyle-specialist/ [R=301,L]
RewriteRule ^specialties/senior-teaching(/senior-teaching-certificate)?/?$ /senior-teaching-specialty/ [R=301,L]
RewriteRule ^specialties/?$ /disciplines/ [R=301,L]
Redirect 301 /staff/ /staff-resources/
Redirect 301 /who-is-who/iron-team-freestyle-team/ /whos-who/iron-team/
Redirect 301 /who-is-who/member-schools/ /member-schools-info/
# who-is-who -> whos-who (hub + remaining team pages, 1:1)
RewriteRule ^who-is-who/(.*)$ /whos-who/$1 [R=301,L]
```

Ordering note: in Apache, more-specific rules must precede prefix rules (as written above —
`Redirect 301` prefix-matches, so child paths are listed before their parents). In nginx,
`location =` exact matches always win over the `^~` prefix blocks, so order is not significant.
