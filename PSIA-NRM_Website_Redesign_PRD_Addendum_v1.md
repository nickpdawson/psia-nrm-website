# PSIA-NRM Website Redesign: PRD Addendum — Production Architecture

**Version:** 1.0
**Date:** April 2, 2026
**Author:** Nick Dawson, PSIA-NRM Board of Directors
**Status:** Draft for Board Review
**Companion document:** PSIA-NRM_Website_Redesign_PRD_v1.md (design, UX, and content requirements)

---

## 1. Purpose of This Addendum

The v1 PRD defined the user experience, information architecture, and content requirements for the PSIA-NRM website redesign. An interactive prototype demonstrating that vision is deployed for board review.

This addendum addresses the production questions the v1 PRD intentionally deferred: what platform to build on, how users and profiles work, how staff maintains the site day-to-day, how to integrate with PSIA national systems, and how we get from prototype to production.

---

## 2. Production Platform

### 2.1 Recommendation: WordPress on Managed Hosting

**Platform:** WordPress with a modern block-based theme and curated admin experience
**Hosting:** Managed WordPress hosting (e.g. SiteGround GoGeek, Cloudways, or similar) — estimated $20–40/month
**Total monthly cost:** ~$30–50 including hosting, domain, and any premium plugin licenses

### 2.2 Rationale

WordPress is the right choice for NRM because:

1. **Staff familiarity.** NRM's current site is WordPress. Jessica, Jill, and the team already have baseline familiarity with the admin interface. We're upgrading the experience, not forcing a platform migration.

2. **Member profile system.** NRM needs a custom content type for member profiles with structured fields (certifications, disciplines, roles). WordPress supports this natively through custom post types and custom fields. Alternatives like Squarespace and Ghost lack this capability without significant workarounds.

3. **Event management.** WordPress has mature event calendar plugins that handle the display/filtering experience staff needs. Registration can link to the existing external system.

4. **Budget fit.** Managed WordPress hosting at $30–40/month is well within the $50/month budget. No per-seat charges, no usage-based pricing.

5. **Ecosystem.** WordPress plugins exist for every integration need: MailChimp embed, ICS calendar downloads, image galleries with moderation, contact forms, and SEO.

**Why not Squarespace or Ghost?**
- Squarespace has excellent UX for simple sites but cannot handle structured member profiles, role-based dynamic pages, or multi-taxonomy filtering without custom code.
- Ghost excels at content publishing but has no custom content types, no member directory capability, and limited plugin ecosystem.

### 2.3 Theme and Admin Experience

The WordPress admin will be curated to feel simple for staff:

- **Theme:** A lightweight, block-based theme (GeneratePress or Kadence) configured to match the PSIA brand system from PRD v1 (primary teal `#035368`, shield blue `#045896`, shield red `#E31636`, DM Sans typography).
- **Admin simplification:**
  - Custom admin dashboard showing only what staff needs: recent events, pending media submissions, recent member signups
  - Sidebar menu reduced to: Pages, Events, Members, Media Submissions, Appearance (limited), Settings (limited)
  - Block editor with predefined templates for common page types — staff fills in fields, not layout
  - No raw HTML editing required for any standard content task
- **Page templates:** Pre-built for Homepage, Event listing, Member profile, Resource pages. Staff edits content within the template; they don't build layouts.

### 2.4 Key Plugins

| Need | Recommended Plugin | Notes |
|------|--------------------|-------|
| Custom post types & fields | Advanced Custom Fields (ACF) Pro | Member profiles, certifications, event structured data. ~$49/yr. |
| Event calendar | The Events Calendar (free) or Events Calendar Pro | Filtering, calendar views, ICS export. Free tier likely sufficient. |
| Admin simplification | Admin Menu Editor + WP Admin UI Customizer | Hide unnecessary menus, customize dashboard |
| Forms | WPForms Lite or Gravity Forms | Contact forms, media submission forms, member registration |
| Image optimization | ShortProxy or Imagify | WebP conversion, compression for performance |
| SEO | Yoast SEO (free) | Meta tags, sitemaps, social sharing |
| Security | Wordfence (free) | Brute force protection, firewall |
| Backups | UpdraftPlus (free) | Automated backups to cloud storage |

---

## 3. User Model

### 3.1 User Types

The system recognizes three types of users, each with different capabilities and creation methods:

| Type | Who | How Created | Can Log In? | Manages Own Profile? |
|------|-----|-------------|-------------|---------------------|
| **Self-registered member** | Any active NRM member | Self-registration form | Yes | Yes |
| **Staff-managed listing** | Board members, ed staff, office staff, or any member who doesn't self-register | Admin creates profile | Not unless claimed | No — admin maintains |
| **Administrator** | Jessica, Jill, or other designated staff | Developer creates during setup | Yes | N/A — manages the site |

### 3.2 "Claim Your Profile" Flow

When staff creates a listing for someone (e.g. a board member), that person can later "claim" it:

1. Staff creates a member profile with name, role, certifications, etc.
2. The profile is published and visible on the site immediately.
3. If the person later wants to manage their own profile, they visit their page and click "Claim this profile."
4. They verify their identity via email (must match the email on file).
5. A WordPress user account is created and linked to the existing profile.
6. The member can now log in and edit their own profile fields.

This is critical for the phased rollout: staff seeds the initial profiles, and members gradually take ownership.

### 3.3 Role Taxonomy

Roles are implemented as a WordPress taxonomy (similar to categories/tags) on the Member custom post type. A member can have **multiple roles**.

| Role | Description | Typical Count |
|------|-------------|---------------|
| **Member** | Active NRM member (default for all) | ~1,200 |
| **Board Member** | Elected board member | 8–12 |
| **Education Staff** | Appointed education team member | 40–60 |
| **Internal Trainer** | Clinic leader / internal trainer | 20–30 |
| **Examiner** | Certified examiner | 15–25 |
| **National Team Member** | PSIA/AASI national team | 2–5 |
| **Committee Chair** | Discipline or specialty committee chair | 5–10 |
| **Ski School Director** | Director of a member school's ski school | ~20 |
| **Office Staff** | NRM office staff | 6 |

**Dynamic organizational pages:** The "Board of Directors" page, education team pages, and staff page are not maintained separately. They are generated by querying all members with the corresponding role tag. When someone joins or leaves the board, a staff member adds or removes the "Board Member" tag on their profile — the Board of Directors page updates automatically.

### 3.4 Discipline Taxonomy

Disciplines are a second taxonomy on the Member post type. A member can have **multiple disciplines**.

| Discipline | Notes |
|------------|-------|
| Alpine | Most common |
| Snowboard | |
| Adaptive | Requires Alpine I or Snowboard I prerequisite |
| Telemark | |
| Nordic (Cross Country) | |

### 3.5 Certification Data

Certifications are stored as a structured repeater field (ACF repeater) on each member profile:

```
certifications:
  - discipline: "Alpine"
    level: "Level 2"
    designation: "A2"
    date_earned: "2022-04-07"
    status: "earned"
  - discipline: "Alpine"
    level: "Level 1"
    designation: "A1"
    date_earned: "2021-02-08"
    status: "earned"
```

Specialty certificates use the same structure:

```
specialties:
  - name: "Children's Specialist 1"
    designation: "CS1"
    date_earned: "2022-12-17"
```

This data structure matches the PSIA national API schema (see Section 7) so that certification data can be auto-populated when API integration is available.

### 3.6 Member Profile Fields

| Field | Type | Public? | Editable By | Required? |
|-------|------|---------|-------------|-----------|
| Name | Text | Yes | Member or admin | Yes |
| Profile photo | Image | Yes | Member or admin | No (initials fallback) |
| Bio | Textarea (500 char) | Yes | Member or admin | No |
| Home resort | Select (from schools list) | Yes | Member or admin | Yes |
| Primary discipline | Select | Yes | Member or admin | Yes |
| Member since year | Number | Yes | Admin only | Yes |
| Roles | Taxonomy (multi-select) | Yes | Admin only | Auto ("Member") |
| Disciplines | Taxonomy (multi-select) | Yes | Member or admin | At least one |
| Certifications | Repeater | Yes | Member or admin (admin-verified) | No |
| Specialties | Tags | Yes | Member or admin | No |
| How to book a lesson | Textarea (free text) | Yes | Member or admin | No |
| Goals | Text | Members only | Member only | No |
| Open to | Multi-select tags | Members only | Member only | No |
| Contact preference | Select (email / message) | Members only | Member only | No |

### 3.7 Profile URL Structure

Every member profile lives at:

```
psia-nrm.org/people/firstname-lastname
```

This URL is the member's professional identity. It's designed to be shared with clients:
- "Here's my instructor page: psia-nrm.org/people/nick-dawson"
- A client sees: name, photo, certifications, resort, bio, how to book

The `/people/` prefix was chosen over `/members/` because it reads more naturally to non-members (clients).

### 3.8 Profile Visibility

| Content | Public (no login) | Logged-in Member | Admin |
|---------|-------------------|------------------|-------|
| Name, photo, bio, certifications, resort, specialties, "how to book" | Yes | Yes | Yes |
| Goals, "open to" tags, contact preference | No | Yes | Yes |
| Edit own profile | No | Yes (own only) | Yes (all) |
| Create new profiles | No | No | Yes |
| Manage role tags | No | No | Yes |
| Member directory search | No | Yes | Yes |

---

## 4. Authentication & Authorization

### 4.1 v1: Self-Contained Auth

For the initial production launch, authentication is managed within WordPress:

- **Self-registration:** Members register with email + password (or email-based magic links for simplicity). Registration form collects name, resort, and primary discipline to create the initial profile.
- **Admin-created accounts:** Staff creates member profiles through the admin. No login is associated until the member claims the profile.
- **Password reset:** Standard WordPress email-based reset. Jessica or Jill can also reset passwords for members who need help.

### 4.2 v2: PSIA National API Integration (see Section 7)

If API access to `api.thesnowater.org` is granted, members could authenticate with their existing PSIA credentials and have certifications auto-populated. See Section 7 for technical details.

### 4.3 Access Levels

| Level | Can Do |
|-------|--------|
| **Public visitor** | View public site, public profile pages, event calendar, resources |
| **Logged-in member** | All public content + member directory, community fields on profiles, edit own profile, submit media |
| **Administrator** | All member capabilities + create/edit all profiles, manage role tags, moderate media, manage events, edit all pages |

---

## 5. Event System

### 5.1 Architecture

The event calendar on the current NRM site (`psia-nrm.org/events/calendar/`) links to the **PSIA national calendar** at `thesnowpros.org/calendar/?divisions=7`, filtered to NRM (division 7). Events are managed through the national system, not in the NRM WordPress admin.

This means the new site should:
1. Display events attractively with NRM-specific filtering
2. Link to the national calendar/registration system for sign-up
3. Optionally pull event data from the national system if API access becomes available

```
+------------------+       +------------------+       +-------------------+
| National event   |  -->  |  NRM site        |  -->  |  National         |
| system           |       |  displays events |       |  registration     |
| (thesnowpros.org)|       |  with NRM brand  |       |  system           |
+------------------+       +------------------+       +-------------------+
```

### 5.2 v1 Approach: Staff-Entered Events in WordPress

Until API integration is available, staff (Jill) enters events in WordPress using a structured form:

| Field | Type | Notes |
|-------|------|-------|
| Title | Text | e.g. "Alpine Level II Assessment" |
| Date range | Date (start + end) | Supports multi-day events |
| Location | Select (from schools list) or text | e.g. "Big Sky Resort" |
| Discipline | Taxonomy | Alpine, Snowboard, etc. |
| Event type | Taxonomy | Clinic, Assessment, Community |
| Price | Text | e.g. "$325" or "Free" |
| Description | Rich text | Full event details |
| Registration link | URL | Links to national registration system |
| Registration deadline | Date | Optional |

### 5.3 v2 Approach: Pull from National Calendar API

The national calendar at `thesnowpros.org/calendar/` supports query parameters:
- `cal-month` and `cal-year` for date navigation
- `disciplines=4,9,10,1,8,6,12,5,2,7` for discipline filtering
- `divisions=7` for NRM-specific events
- `event=16418` for individual event details

If the national system exposes an API for event data, the new site could pull NRM events automatically, eliminating double-entry by Jill.

### 5.4 Staff Workflow (v1)

1. Jill logs into WordPress admin
2. Clicks "Events → Add New"
3. Fills in structured fields (title, dates, location, discipline, type, price, description)
4. Pastes the registration URL from the national system
5. Clicks Publish
6. Event appears on the calendar with appropriate filtering tags

No HTML. No layout decisions. Just fill in the fields and publish.

### 5.5 Public Display

- **Homepage:** Next 3 upcoming events shown automatically
- **Events page:** Full calendar with filters (discipline, type, location, date range)
- **Event detail:** Card/page with all details + prominent "Register" button linking to national system
- **ICS download:** "Add to calendar" button generates .icc file for each event

---

## 6. Content Management Workflows

### 6.1 Who Updates What

| Content | Primary Editor | Frequency | Method |
|---------|---------------|-----------|--------|
| Events | Jill Imsand Chumbley | Weekly during season, monthly off-season | Structured event form in admin |
| News / spotlights | Jessica Quay | Monthly | Block editor with template |
| Member profiles (staff-managed) | Jessica Quay | Annually (role changes) or as needed | Edit member profile in admin |
| Member profiles (self-service) | Individual members | Self-managed | Member edits own profile |
| Media submissions (moderation) | Jessica Quay | As submissions arrive | Approve/reject in moderation queue |
| Homepage hero/spotlight | Jessica Quay | Monthly | Select featured member + update hero text |
| Resource pages (cert standards, rules) | Jessica Quay | Annually | Block editor |
| Board / education team pages | Automatic | When role tags change | No manual editing needed |
| Sponsor logos | Jessica Quay | Annually | Upload/remove logos in admin |

### 6.2 Dynamic Organizational Pages

The following pages require **zero manual maintenance** — they are generated by querying member profiles with specific role tags:

| Page | Generated From |
|------|---------------|
| Board of Directors | All members tagged "Board Member" |
| Alpine Education Team | All members tagged "Education Staff" + discipline "Alpine" |
| Snowboat Education Team | All members tagged "Education Staff" + discipline "Snowboard" |
| Adaptive Education Team | All members tagged "Education Staff" + discipline "Adaptive" |
| Telemark Education Team | All members tagged "Education Staff" + discipline "Telemark" |
| Cross Country Education Team | All members tagged "Education Staff" + discipline "Nordic" |
| Iron Team (Freestyle) | All members tagged "Education Staff" + specialty "Freestyle" |
| Office Staff | All members tagged "Office Staff" |
| Examiners | All members tagged "Examiner" |

When a board member's term ends, Jessica removes the "Board Member" tag from their profile. The Board page updates immediately. No separate page edit needed.

### 6.3 Media Submission Pipeline

1. Member submits photo/video via form (name, file, resort, category, caption, permission checkbox)
2. Submission appears in moderation queue
3. Jessica reviews: approve (appears in gallery) or reject
4. Approved submissions optionally promoted to homepage hero or spotlight

---

## 7. PSIA National API Integration Strategy

### 7.1 Discovery

Analysis of the PSIA member portal (`members.thesnowpros.org`) reveals a well-structured REST API at `api.thesnowpros.org`. The API is built on ASP.NET, hosted on Azure, and uses OAuth2/OpenID Connect authentication with JWT bearer tokens. The API returns `Access-Control-Allow-Origin: *` headers, indicating it accepts cross-origin requests.

### 7.2 Known API Endpoints

The following endpoints were identified and return structured JSON data:

| Endpoint | Returns | Relevance to NRM Site |
|----------|---------|----------------------|
| `GET /api/Education/MyEducation/AccountCertifications/Summary` | Full certification history with dates, designations, discipline, certification group | **High** — auto-populate member certification data |
| `GET /api/Education/MyEducation/CEUTracing` | Continuing education credits per season (earned, required, satisfied) | **Medium** — display CEU status on pathway dashboard |
| `GET /api/Members/MyAccount/CurrentMembershipHistoryInfo` | Membership type, division, start/expiry dates, active status | **High** — verify active membership, auto-populate member-since |
| `GET /api/Members/MyAccount/WithInfo` | Account details (name, email, account code) | **High** — profile auto-population |

### 7.3 Data Schema (from API responses)

**Certification record:**
```json
{
  "id": 206571,
  "accountId": 132935,
  "certificationId": 232,
  "date": "2022-04-07",
  "expiresOn": null,
  "accountName": "Dawson, Nick",
  "certificationName": "Alpine Level 2",
  "certificationPriority": 2,
  "certificationType": "National",
  "educationDivisionId": 11,
  "educationDivisionName": "National",
  "designation": "A2",
  "certificationGroupId": 10,
  "certificationGroupName": "Alpine",
  "disciplineId": 1,
  "disciplineName": "Alpine"
}
```

**Membership record:**
```json
{
  "membershipType": {
    "name": "Northern Rocky Mountain Certified",
    "divisionId": 7,
    "categories": ["Certified"]
  },
  "membershipStart": "2025-07-01",
  "membershipExpiry": "2026-06-28",
  "isActive": true,
  "status": "Renew",
  "classificationStatus": "Active",
  "accountId": 132935,
  "divisionId": 7
}
```

**CEU tracking record:**
```json
{
  "year": "25-26",
  "earned": 6.00,
  "required": 6,
  "ceuSatisfied": true,
  "membershipTypeName": "Northern Rocky Mountain Certified"
}
```

**JWT token claims (from OpenID Connect):**
```
sub: "274203"
given_name: "Dawson, Nick"
email: "nd@nickdawson.net"
account_id: 132935
account_code: "402855"
metadata.current_member: true
metadata.user_is_school: "Individual"
roles: "Member"
```

### 7.4 Key Technical Details

- **Auth protocol:** OpenID Connect (standard OAuth2 with id_token)
- **Token issuer:** `https://api.thesnowpros.org`
- **Client ID (member portal):** `PSIAMemberPortal`
- **Token lifetime:** ~14 days (1,209,600 seconds)
- **CORS:** `Access-Control-Allow-Origin: *` (permissive)
- **Infrastructure:** Azure (Azure Front Door CDN + ASP.NET backend)
- **NRM division ID:** 7

### 7.5 Integration Phases

**v1 (Production Launch) — No API dependency:**
- All member data is self-reported or staff-entered
- Certification fields use the same schema as the national API (designation codes like "A1", "A2", "CS1") so data can be merged later
- Authentication is self-contained in WordPress

**v2 (Post-Launch) — "Log in with PSIA" + cert auto-population:**

Requires: PSIA national grants NRM an OAuth client registration for `psia-nrm.org`.

Flow:
1. Member clicks "Log in with your PSIA account" on `psia-nrm.org`
2. Redirect to `api.thesnowpros.org` OAuth authorization endpoint
3. Member logs in with their existing PSIA credentials
4. Redirect back to `psia-nrm.org` with authorization code
5. NRM site exchanges code for access token + id_token
6. JWT claims provide: name, email, account_id, membership status
7. NRM site calls `/api/Education/MyEducation/AccountCertifications/Summary` with the token
8. Certifications auto-populate the member's NRM profile
9. NRM site calls `/api/Members/MyAccount/CurrentMembershipHistoryInfo` to verify active membership

**Benefits of v2:**
- Members don't need a separate password for the NRM site
- Certifications are always current and authoritative (no self-reporting errors)
- Membership status is verified automatically (active, lapsed, expired)
- CEU tracking could be displayed on the pathway dashboard
- New members who register on the national site automatically have data available

**What NRM needs from PSIA national:**
1. An OAuth client registration (client_id + client_secret) for `psia-nrm.org`
2. Confirmation that the API endpoints above are stable and available for regional use
3. Any rate limits or usage policies

### 7.6 National Calendar Integration (Potential)

The national event calendar at `thesnowpros.org/calendar/` uses URL parameters for filtering:
- `divisions=7` — filters to NRM events
- `disciplines=1,2,4,5,6,7,8,9,10,12` — discipline IDs
- `event=16418` — individual event detail

If the national calendar exposes a data API (not yet confirmed), NRM could pull events automatically instead of Jill re-entering them. The national site also uses **Givebutter** (`widgets.givebutter.com`) for donation integration, confirming the PRD v1 recommendation.

### 7.7 Discipline ID Reference (from national system)

| ID | Discipline |
|----|------------|
| 1 | Alpine |
| 2 | Snowboard |
| 4 | Adaptive |
| 5 | Telemark |
| 6 | Cross Country |
| 7 | Children's Specialist |
| 8 | Children's |
| 9 | Senior |
| 10 | Freestyle |
| 12 | (Unknown — possibly a specialty) |

---

## 8. Migration Plan

### 8.1 Content Migration from Current Site

| Content | Source | Migration Method |
|---------|--------|-----------------|
| Certification standards (by discipline) | Current WP pages | Manual transfer to new page templates |
| Event data | National calendar system | v1: manual re-entry; v2: API pull |
| Board and staff listings | Current WP pages | Become member profiles with role tags |
| Member school directory | Current WP pages | Import as structured data |
| Rules & regulations | Current WP pages | Transfer to new page templates |
| Scholarship info | Current WP pages | Transfer to resource page |
| Sponsor logos and links | Current WP pages | Upload to new sponsor section |
| Newsletter archive | Links to MailChimp | Link transfer only |

### 8.2 Member Profile Seeding

**Phase 1 — Staff-created profiles (pre-launch):**
- Board of Directors (~10 profiles)
- Office staff (~6 profiles)
- Education team members across all disciplines (~50 profiles)
- Discipline and specialty chairs (~10 profiles)
- National team members (~3 profiles)
- **Total: ~80 profiles created by staff before launch**

**Phase 2 — Open self-registration (post-launch):**
- Announce self-registration in The Stoke newsletter and at events
- Encourage signup at Fall Fest and other major events
- Target: 300 self-registered profiles in first season (25% of membership, per PRD v1 success metrics)

**Phase 3 — Profile claim campaign:**
- Email all staff-created profile holders with a link to claim their profile
- Claimed profiles transition from staff-managed to self-managed

### 8.3 DNS & Hosting Migration

1. Build production site on new managed hosting
2. Develop and test with temporary domain (e.g. `new.psia-nrm.org` or staging URL)
3. Board review and approval on staging
4. DNS cutover: point `psia-nrm.org` to new hosting
5. Keep old site backup available for 90 days

---

## 9. Timeline

| Phase | Duration | Deliverables |
|-------|----------|-------------|
| **Prototype review** (current) | 1–2 weeks | Board reviews prototype, provides feedback |
| **Production build** | 6–8 weeks | WordPress site with all page types, member CPT, event system, admin customization |
| **Content migration** | 2–3 weeks (parallel) | All current site content transferred; ~80 member profiles seeded |
| **Testing & refinement** | 2 weeks | Staff testing of admin workflows, mobile testing, performance optimization |
| **Launch** | 1 week | DNS cutover, monitoring, staff support |
| **Post-launch** | Ongoing | Self-registration campaign, media submission collection, iteration based on usage |
| **v2: National API integration** | TBD | Dependent on PSIA national granting API access |

---

## 10. Open Items

| Item | Owner | Status |
|------|-------|--------|
| PSIA national API access inquiry | Nick Dawson | To do — request OAuth client registration for psia-nrm.org |
| Confirm event system ownership | Nick / Jill | To do — events appear to be national system, confirm |
| Obtain official NRM logo in SVG format | Nick / NRM staff | To do — needed for production site |
| Obtain PSIA-AASI Brand Standards Guide (2022) | Nick / NRM staff | To do — for final design compliance |
| Select managed hosting provider | Nick | To do — evaluate SiteGround, Cloudways, WP Engine |
| Discord server setup | Nick / volunteers | To do — needed for community launch |

---

## Appendix A: Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                     psia-nrm.org (WordPress)                        │
│                                                                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────────────────┐  │
│  │  Pages   │  │  Events  │  │ Members  │  │ Media Submissions  │  │
│  │ (blocks) │  │  (CPT)   │  │  (CPT)   │  │      (CPT)         │  │
│  └──────────┘  └──────────┘  └──────────┘  └────────────────────┘  │
│                      │              │                                │
│                      │        ┌─────┴─────┐                         │
│                      │        │ Taxonomies│                         │
│                      │        │ - Roles   │                         │
│                      │        │ - Disc.   │                         │
│                      │        └───────────┘                         │
│                      │                                              │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    Dynamic Pages                             │   │
│  │  Board of Directors ← query(role="Board Member")            │   │
│  │  Alpine Ed Team    ← query(role="Ed Staff", disc="Alpine")  │   │
│  │  Examiners         ← query(role="Examiner")                 │   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
         │              │              │                    │
         ▼              ▼              ▼                    ▼
  ┌───────────┐ ┌──────────────┐ ┌──────────┐ ┌────────────────────┐
  │ MailChimp │ │  National    │ │ Discord  │ │ api.thesnowpros.org│
  │ (embed)   │ │  Event Reg   │ │ (invite) │ │ (v2: OAuth + API)  │
  └───────────┘ └──────────────┘ └──────────┘ └────────────────────┘
```

## Appendix B: Relationship to PRD v1

This addendum does not modify the PRD v1 design, content, or UX requirements. It answers the "how" questions that the PRD intentionally deferred:

| PRD v1 Said | This Addendum Answers |
|-------------|----------------------|
| "Static site generator or lightweight framework" for prototype | Production: WordPress on managed hosting |
| "Options A, B, C for production" — board to evaluate | Decision: Option A (WordPress rebuild) with modern theme |
| "Simulated authentication with toggle" for prototype | v1: Self-registration + admin-created + claim flow. v2: PSIA national OAuth. |
| "Integration with PSIA-AASI member database if API is available" | API exists at api.thesnowpros.org. Specific endpoints, data schemas, and integration plan documented in Section 7. |
| "Managed by NRM staff through a CMS" | WordPress admin with curated UX, structured forms, no HTML |

## Appendix C: National API Quick Reference

For the developer implementing v2 integration:

```
Auth endpoint:    https://api.thesnowpros.org (OpenID Connect)
Token audience:   "PSIA"
NRM division ID:  7

GET /api/Education/MyEducation/AccountCertifications/Summary
  → Array of certification records with discipline, level, date, designation

GET /api/Education/MyEducation/CEUTracking
  → Array of CEU records per season with earned/required/satisfied

GET /api/Members/MyAccount/CurrentMembershipHistoryInfo
  → Membership type, dates, active status, division

GET /api/Members/MyAccount/WithInfo
  → Account name, email, account code

All endpoints require: Authorization: Bearer <access_token>
All endpoints return: application/json with { count, total, data[] } wrapper
CORS: Access-Control-Allow-Origin: *
```
