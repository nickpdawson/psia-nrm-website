# Live Demo — Monday July 6, 2026 (Herb + NRM staff)

**Site:** https://psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net
**Admin:** same URL + `/wp-admin/` · user `nrm-admin` · password in `deploy/.secrets/mysql.env` (put in 1Password before Monday)

## Pre-flight (Sunday night or Monday morning — 5 min)

- [ ] Front page loads fast (hit it once beforehand — first request after idle can take ~10s while the container warms; B2 plan has Always-On available: consider enabling before the demo: `az webapp config set -g PSIA-NRM -n PSIA-NRM-Website --always-on true`)
- [ ] Log into wp-admin in a second tab, ready to switch
- [ ] Delete any test form entries (Form Entries → "Deploy Smoke Test" / QA rows)
- [ ] Have the old site open in a third tab for before/after comparison

## Suggested demo flow (~15 min)

1. **Front page** — hero photo, live stats, photo gallery of real NRM instructors, recent events. Compare against the old site's 2010s-era design in the other tab.
2. **Member Directory / Who's Who** (`/whos-who/`) — all 79 people, board, education teams by discipline, 29 member schools. *Point out: this is a database, not hand-edited pages — one edit updates every listing.*
3. **A discipline page** (`/alpine/`) — certification levels with the **current Oct 2025 national assessment forms**, prep requirements, docs. Mention every discipline got a content-accuracy pass against national standards during migration.
4. **Membership** (`/membership/`) — current 2025-26 dues, scholarships, reinstatement, FAQ — all one page, all sections independently editable.
5. **Scholarships** (`/scholarships/`) — native application forms replacing Google Forms; submissions land in the admin dashboard with CSV export for the Foundation.
6. **The wow moment — live edit:** in wp-admin → **Site Content** → change the hero heading (e.g. add "— Welcome Herb"), publish, refresh the public tab. *"Any of your office staff can do this. No developer, no page builder, no waiting."* Change it back.
7. **Form Entries** — show a submission arriving (submit the contact form from the public tab live).
8. **The Stoke archive** (`/archive/`) — newsletters preserved and hosted on the new infrastructure.

## Honest-answer prep (questions they may ask)

- **"Can members log in?"** — The plumbing is built and mocked (`/pathway` shows the design with live-data placeholders). It turns on when National issues our OAuth credentials — that request is ready to send and is the single biggest outstanding dependency.
- **"What about event registration?"** — Register buttons currently point at the existing calendar. Real events + registration come from the national API with the same credentials as login.
- **"What does it cost?"** — App Service ~$25/mo. The database National provisioned is oversized (~$130–190/mo) and should be downsized to ~$15–35/mo — that's a Sean conversation, and the total then lands near the promised ~$40/mo.
- **"When can we launch?"** — Site is content-complete now. Launch needs: OAuth from National, the ed-staff paysheet decoupled from the old host, and the DNS cutover — realistic within weeks of National delivering credentials.
- **"Who maintains it?"** — Office staff, via the admin. `EDITING-GUIDE.md` maps every seasonal edit; an illustrated version is planned.

## Do NOT do before Monday

- No image/deploy changes after Saturday (current: `staging-9`)
- Don't rename the `psia-nrm` theme dir, don't deactivate plugins
- Don't run the importer options reset or touch cert-updates.json
