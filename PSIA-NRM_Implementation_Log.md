# PSIA-NRM Website Redesign: Implementation Log

**Date:** April 2–15, 2026
**Author:** Nick Dawson
**Status:** WordPress production prototype live at psia-nrm.dzsec.net

---

## 1. What We Built

### Phase 1: Interactive Prototype (Next.js)

A fully interactive prototype demonstrating the redesigned UX across all major page types, deployed as a Docker container on `maverick.dzsec.net:3081`.

**Stack:** Next.js 14, Tailwind CSS, sample data in JSON files, Docker

**Pages built:**
- Homepage with hero, quick actions, upcoming events, community spotlight, Our Mountain campaign, sponsor bar
- Events & Clinics — filterable event listing with 12 real NRM events
- My Pathway — personalized certification dashboard with real PSIA national API data (Alpine Level 1, Alpine Level 2, Children's Specialist 1, 6 seasons of CEU history)
- Community hub — links to directory, Discord, gallery, newsletter, job board
- Member Directory — search/filter by name, discipline, resort, role, "open to" tags
- Community Gallery — filterable gallery with media submission form
- 21 individual member profiles with certifications, roles, bios
- Board of Directors — dynamically generated from members tagged with "Board Member" role
- Member Schools — 20 schools grouped by state
- Resources — disciplines, specialties, office staff, org info
- Scholarships — deadlines, eligibility, individual + school grants
- Our Mountain — campaign page with three pillars
- Login — demonstrates PSIA national API integration with real data

**Key design decisions:**
- PSIA brand colors: primary teal `#035368`, shield blue `#045996`, shield red `#E31636`
- DM Sans typography (Google Fonts)
- Official PSIA-AASI NRM logos used throughout (downloaded from psia-nrm.org and thesnowpros.org)
- Mobile-first responsive design
- Real organizational data from the current psia-nrm.org WordPress REST API

### Phase 2: WordPress Production Site

Migrated from the static prototype to a live WordPress installation on the same port, maintaining the modern design while enabling staff content management.

**Stack:** WordPress 6.7 (PHP 8.3), MySQL 8.0, custom theme, Docker

**Custom theme (`psia-nrm`):**
- Clean, modern design matching the prototype aesthetic
- PSIA brand color system implemented as CSS custom properties
- Responsive layout with card-based components
- Templates: front-page, event archive, event single, member archive, member single, generic page

**Custom post types:**
- `nrm_member` — Member profiles at `/people/[slug]`
- `nrm_event` — Events at `/events/[slug]`

**Custom taxonomies:**
- `nrm_role` — Member roles (Member, Board Member, Education Staff, Examiner, Trainer, National Team Member, Committee Chair, Ski School Director, Office Staff)
- `nrm_discipline` — Disciplines (Alpine, Snowboard, Adaptive, Telemark, Nordic)
- `nrm_event_type` — Event types (Clinic, Assessment, Community)

**Custom meta fields (Members):**
- `nrm_resort` — Home resort
- `nrm_member_since` — Year joined
- `nrm_bio` — Bio text
- `nrm_certifications` — JSON array of certifications with name, designation, date
- `nrm_specialties` — Comma-separated specialty tags
- `nrm_open_to` — Comma-separated "open to" tags
- `nrm_goals` — Career goals
- `nrm_how_to_book` — Free text (e.g. "Contact Big Sky Resort at 555-1212")
- `nrm_board_title` — Board position title
- `nrm_in_progress` — Certification currently in progress

**Custom meta fields (Events):**
- `nrm_event_start` — Start date
- `nrm_event_end` — End date
- `nrm_event_location` — Venue/resort
- `nrm_event_price` — Price text
- `nrm_event_reg_url` — Registration link (external)

**Data imported:**
- 21 member profiles with roles, disciplines, certifications, bios
- 12 events with dates, locations, disciplines, types, prices

---

## 2. Infrastructure

### Deployment

| Service | Container | Port | URL |
|---------|-----------|------|-----|
| WordPress site | `nrm-wp` | 3080 → 80 | `https://psia-nrm.dzsec.net` |
| MySQL database | `nrm-wp-db` | internal | — |
| Next.js prototype | `nrm-prototype` | 3081 → 3000 | `http://maverick.dzsec.net:3081` |

**Host:** `maverick.dzsec.net` (10.15.15.99), Ubuntu 24.10
**Proxy:** Nginx Proxy Manager on Whistler (DMZ), proxies `psia-nrm.dzsec.net` → `10.15.15.99:3080`
**DNS:** `psia-nrm.dzsec.net` → `10.25.1.182` (Whistler) via Samba AD DNS
**pfSense rule:** DMZ (Whistler) → SERVERS (Maverick) TCP 3080

### WordPress Admin

- **URL:** `https://psia-nrm.dzsec.net/wp-admin/`
- **Username:** `admin`
- **Password:** `NRM2026demo!`

### Docker Compose Location

- WordPress: `/home/administrator/nrm-wordpress/docker-compose.yml`
- Prototype: `/home/administrator/nrm-website/docker-compose.yml`

---

## 3. PSIA National API Discovery

Analysis of the PSIA member portal (`members.thesnowpros.org`) via HAR file capture revealed a well-structured REST API at `api.thesnowpros.org`.

### API Architecture

- **Protocol:** OpenID Connect (OAuth 2.0)
- **Auth endpoint:** `https://api.thesnowpros.org/connect/authorize`
- **Token endpoint:** `https://api.thesnowpros.org/connect/token`
- **Infrastructure:** Azure (ASP.NET backend, Azure Front Door CDN)
- **CORS:** `Access-Control-Allow-Origin: *` (permissive)
- **Token lifetime:** ~14 days
- **Grant types supported:** authorization_code, client_credentials, refresh_token, implicit, password, device_code
- **Member portal client ID:** `PSIAMemberPortal`
- **Member portal auth flow:** Implicit (`response_type=id_token token`)

### Known Endpoints

| Endpoint | Returns |
|----------|---------|
| `GET /api/Education/MyEducation/AccountCertifications/Summary` | Certification history (name, date, designation, discipline, group, priority) |
| `GET /api/Education/MyEducation/CEUTracking` | CEU credits per season (earned, required, satisfied) |
| `GET /api/Members/MyAccount/CurrentMembershipHistoryInfo` | Membership type, division, dates, active status |
| `GET /api/Members/MyAccount/WithInfo` | Account details (name, email, account code) |

### JWT Token Claims

```
sub, given_name, email, username, account_id, account_code,
metadata.current_member, metadata.user_is_school,
metadata.open_dues_invoices, roles
```

### NRM-Specific Identifiers

- **NRM division ID:** 7
- **Membership type:** "Northern Rocky Mountain Certified" (ID 237)
- **Discipline IDs:** Alpine=1, Snowboard=2, Adaptive=4, Telemark=5, XC=6, Children's=7/8, Senior=9, Freestyle=10

### National Calendar

The event calendar on the current NRM site links to the national calendar at `thesnowpros.org/calendar/`. URL parameters:
- `divisions=7` — filters to NRM events
- `disciplines=1,2,4,5,6,7,8,9,10,12` — discipline filter
- `event=16418` — individual event detail

The national site uses **Givebutter** for donation integration.

### Verified Real Data (Nick Dawson, Member #402831)

```json
Certifications:
  - Alpine Level 1 (A1) — earned 2021-02-08
  - Alpine Level 2 (A2) — earned 2022-04-07
  - Children's Specialist 1 (CS1) — earned 2022-12-17

Membership:
  - Type: Northern Rocky Mountain Certified
  - Division: 7 (NRM)
  - Period: 2025-07-01 to 2026-06-30
  - Status: Active

CEU History (6 seasons):
  - 25-26: 6/6 ✓
  - 24-25: 36/6 ✓
  - 23-24: 12/6 ✓
  - 22-23: 13/6 ✓
  - 21-22: 55/6 ✓
  - 20-21: 12/6 ✓
```

---

## 4. Authentication Roadmap

### Current State (Prototype)

- WordPress admin login for staff (username/password)
- No member self-service authentication yet
- Prototype demonstrated PSIA OAuth flow (blocked by redirect_uri restriction, as expected)

### Next Step: Request OAuth Client Registration

**What NRM needs from PSIA national:** An OAuth client registration for `psia-nrm.org` (or whatever the production domain will be). This is one administrative request — the technical infrastructure already exists.

**Specifically, request:**
1. A `client_id` and `client_secret` for the NRM site
2. Allowed redirect URIs: `https://psia-nrm.org/auth/callback` (or similar)
3. Scopes: `openid PSIA` (same as the member portal)
4. Grant type: `authorization_code` (more secure than implicit for server-side apps)

### How Authentication Will Work in Production

**For members (self-service):**
1. Member visits `psia-nrm.org` and clicks "Log In with PSIA-AASI"
2. Redirected to `api.thesnowpros.org/connect/authorize` (the same login page used by `members.thesnowpros.org`)
3. Member logs in with their existing PSIA credentials
4. Redirected back to `psia-nrm.org/auth/callback` with an authorization code
5. NRM server exchanges the code for access token + id_token
6. JWT claims provide: name, email, account_id, membership status, roles
7. NRM site calls the certification and CEU endpoints to populate the member's profile
8. WordPress user account is created or linked, member is logged in

**For staff (Jessica, Jill, etc.):**
- Same flow — Jill logs into `psia-nrm.org` with her PSIA credentials
- Her WordPress account is linked to her PSIA identity
- WordPress role (Administrator/Editor) is assigned by a site admin
- She gets access to the WordPress admin panel for content management
- No separate password to remember

**For admin-created profiles (board members, ed staff who don't log in):**
- Staff creates a member profile in WordPress admin
- Profile is published immediately (no login required)
- If the person later wants to manage their own profile, they "claim" it by logging in with PSIA — the system matches by email or account_id and links the existing profile to their account

### WordPress Integration

The PSIA OAuth integration would be implemented as a WordPress plugin using the `authorization_code` grant flow:

1. **Login button** — replaces the default WordPress login form
2. **Callback handler** — exchanges auth code for tokens, creates/links WordPress user
3. **Profile sync** — pulls certifications, membership, CEU data on login
4. **Role mapping** — PSIA `roles` claim maps to WordPress capabilities
5. **Token refresh** — refresh tokens keep the session alive without re-authentication

Recommended WordPress plugin: Custom plugin wrapping a standard OAuth2 client library, or adapt an existing OpenID Connect plugin (e.g., `openid-connect-generic`) to work with the PSIA endpoint.

---

## 5. What's Next

### Immediate

- [ ] Board review of WordPress prototype at `psia-nrm.dzsec.net`
- [ ] Gather feedback on design, content, and admin workflow
- [ ] Nick to request OAuth client registration from PSIA national
- [ ] Confirm event system ownership (NRM-managed or national tool?)

### Production Build

- [ ] Select managed hosting provider (SiteGround, Cloudways, or WP Engine — $20-40/mo)
- [ ] Install ACF Pro for richer member/event editing UX ($49/yr)
- [ ] Build "claim your profile" flow
- [ ] Add member self-registration
- [ ] Implement PSIA OAuth login (once client registration is granted)
- [ ] Media submission form with moderation queue
- [x] Dynamic organizational pages (Board, Education Teams generated from role tags)
- [ ] Migrate remaining content from current psia-nrm.org
- [x] Seed ~80 member profiles (board, ed staff, office staff)

### Content & Assets Needed

- [ ] Official NRM logo in SVG format (currently using PNG from current site)
- [ ] PSIA-AASI Brand Standards Guide (2022) for final compliance review
- [ ] Member-submitted photography to replace placeholder content
- [ ] Homepage hero copy finalized
- [ ] Our Mountain campaign copy finalized
- [x] Discord server created — invite: https://discord.gg/khuz6TYKX3

---

## 3. Phase 3: Database-Driven "Who's Who" Migration (April 15, 2026)

Migrated the entire "Who's Who" section from 13 static WordPress pages to a database-driven architecture where every person is a single `nrm_member` post that appears on organizational pages via taxonomy queries.

### What Changed

- **79 real NRM people** imported as member profiles (replaced 21 fictional sample profiles)
- **All education teams, board, chairs, staff** are now dynamic — generated by querying role/discipline/specialty tags
- **Clickable badges** on member profiles — click "Alpine" to see the Alpine Ed Team, click "Discipline Chair" to see all chairs, click a school name to see all instructors there
- **Member schools as taxonomy** — 29 schools as taxonomy terms (not a static list), each with auto-generated school pages showing instructors at that school
- **Jackson Hole** removed as member school per Nick's direction
- **Office Staff** updated to current: only Jessica Quay, Jill Imsand Chumbley, Herb Davis
- **National Team Members** added as role: AJ Oliver, Zoe Mavis, Katie White
- **Discord** integrated — invite link (https://discord.gg/khuz6TYKX3) wired into homepage and footer

### Taxonomies

| Taxonomy | Purpose | Terms |
|----------|---------|-------|
| `nrm_role` | Organizational roles (multi-select per person) | Member, Board Member, Education Staff, Discipline Chair, Specialty Chair, Office Staff, Iron Team, National Team Member, Examiner |
| `nrm_discipline` | Teaching disciplines (multi-select) | Alpine, Snowboard, Adaptive, Telemark, Nordic |
| `nrm_specialty` | Specialty areas (multi-select) | Children's Specialist, Freestyle, Senior Teaching |
| `nrm_school` | Member schools (one per person) | 29 schools + Yellowstone Club |
| `nrm_event_type` | Event types | Clinic, Assessment, Community |

### Dynamic Page Architecture

Every organizational page has `nrm_query_role`, `nrm_query_discipline`, and/or `nrm_query_specialty` meta fields. The `page.php` template reads these and runs the appropriate WP_Query. Staff creates a page, sets the query fields in the sidebar, and the people listing generates automatically.

| Page | Query |
|------|-------|
| Board of Directors | `role = Board Member` |
| Discipline Chairs | `role = Discipline Chair` |
| Specialty Chairs | `role = Specialty Chair` |
| Office Staff | `role = Office Staff` |
| National Team Members | `role = National Team Member` |
| Alpine Education Team | `role = Education Staff` AND `discipline = Alpine` |
| Snowboard Education Team | `role = Education Staff` AND `discipline = Snowboard` |
| Telemark Education Team | `role = Education Staff` AND `discipline = Telemark` |
| Cross Country Education Team | `role = Education Staff` AND `discipline = Nordic` |
| Adaptive Education Team | `role = Education Staff` AND `discipline = Adaptive` |
| Children's Specialist Education Team | `role = Education Staff` AND `specialty = Children's Specialist` |
| Senior Teaching Education Team | `role = Education Staff` AND `specialty = Senior Teaching` |
| Iron Team (Freestyle) | `role = Iron Team` |

### Theme Templates

| Template | Purpose |
|----------|---------|
| `page-whos-who.php` | Hub page — shows all org sections with people counts and school directory |
| `page.php` | Generic page + dynamic people query (reads nrm_query_* meta) |
| `single-nrm_member.php` | Member profile with clickable badges linking to org pages |
| `taxonomy-nrm_school.php` | School page — lists all instructors at a school |
| `archive-nrm_member.php` | Member directory — all people with badges |
| `archive-nrm_event.php` | Event listing with discipline/type badges |
| `front-page.php` | Homepage with hero, quick actions, events, campaign |

---

## 6. Documents Produced

| Document | Location | Purpose |
|----------|----------|---------|
| PRD v1 | `PSIA-NRM_Website_Redesign_PRD_v1.md` | Design, UX, content requirements |
| PRD Addendum v1 | `PSIA-NRM_Website_Redesign_PRD_Addendum_v1.md` | Production architecture, user model, auth, API integration |
| Implementation Log | `PSIA-NRM_Implementation_Log.md` | This document — what was built and how |

---

## Appendix: File Locations

### Local (this repo)
```
NRM Website/
├── PSIA-NRM_Website_Redesign_PRD_v1.md      # Design/UX/content requirements
├── PSIA-NRM_Website_Redesign_PRD_Addendum_v1.md  # Production architecture, auth, API
├── PSIA-NRM_Implementation_Log.md           # This document
├── members.thesnowpros.org.har              # PSIA national API HAR capture
├── events.har                               # National calendar HAR capture
└── wordpress/                               # WordPress source (synced from maverick)
    ├── docker-compose.yml                   # Docker stack (WP + MySQL)
    ├── people.json                          # 79 real NRM people with roles/disciplines
    ├── events.json                          # 12 NRM events
    ├── themes/                              # Custom psia-nrm theme
    │   ├── style.css                        # Full CSS with PSIA brand system
    │   ├── functions.php                    # CPTs, taxonomies, meta fields, admin
    │   ├── header.php                       # Site header with nav
    │   ├── footer.php                       # Site footer with Discord link
    │   ├── front-page.php                   # Homepage template
    │   ├── page.php                         # Generic page + dynamic people query
    │   ├── page-whos-who.php               # Who's Who hub template
    │   ├── single-nrm_member.php           # Member profile with clickable badges
    │   ├── archive-nrm_member.php          # Member directory
    │   ├── archive-nrm_event.php           # Event listing
    │   ├── taxonomy-nrm_school.php         # School page (instructors at school)
    │   └── index.php                        # Fallback template
    └── plugins/
        └── nrm-import.php                   # Data import plugin (79 people, 12 events, schools)
```

### Maverick (deployed)
```
/home/administrator/nrm-wordpress/          # WordPress production site (port 3080)
│   ├── style.css                            # Full CSS with PSIA brand system
│   ├── functions.php                        # CPTs, taxonomies, meta fields, admin
│   ├── header.php                           # Site header with nav
│   ├── footer.php                           # Site footer
│   ├── front-page.php                       # Homepage template
│   ├── index.php                            # Fallback template
│   ├── page.php                             # Generic page template
│   ├── single-nrm_member.php               # Member profile template
│   ├── archive-nrm_member.php              # Member directory template
│   ├── archive-nrm_event.php               # Event listing template
│   └── single-nrm_event.php                # (inherits from index)
├── plugins/
│   └── nrm-import.php                      # One-time data import plugin
├── uploads/                                 # Logos and media
├── members.json                             # Sample member data (21 profiles)
└── events.json                              # Sample event data (12 events)

/home/administrator/nrm-website/             # Next.js prototype (port 3081)
├── docker-compose.yml
├── src/
│   ├── app/                                 # All page routes
│   ├── components/                          # Header, Footer
│   └── data/                                # JSON data files
└── public/                                  # Logos, static assets
```
