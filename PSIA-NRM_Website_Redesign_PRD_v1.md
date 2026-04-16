# PSIA-NRM Website Redesign: Product Requirements Document

**Version:** 1.0
**Date:** April 2, 2026
**Author:** Nick Dawson, PSIA-NRM Board of Directors
**Status:** Draft for Review

---

## 1. Executive Summary

The PSIA-AASI Northern Rocky Mountain Division website (psia-nrm.org) serves approximately 1,200 professional snow sports instructors across Montana, North Dakota, and South Dakota. The current site is a WordPress brochure built on an outdated theme (Modernize v3, circa 2015) that provides minimal engagement value beyond hosting certification requirements and an event calendar.

Member research conducted in 2024 identified five core needs that the current website fails to address: structured career pathways, connection to a broader community (especially for geographically isolated members), clear and professional communication, support through life transitions, and a sense of organizational progress. This redesign responds directly to those research findings.

The redesigned site will serve as a modern hub that connects members to best-of-breed tools (Discord for community, MailChimp for newsletters, external registration for events) while providing native value through member profiles, a career pathway dashboard, a searchable member directory, and a community media gallery. The site will use the official PSIA-AASI brand identity.

---

## 2. Background and Research Foundation

### 2.1 Member Research Insights (2024 Interview Study)

Qualitative research conducted with current NRM members identified five primary "asks" that should guide all design decisions:

1. **"Give Me a Career Pathway: Legitimize My Role in Skiing."** Members want a structured, visible progression through the certification system. The current site lists requirements across deeply nested pages but provides no sense of personal progress or next steps.

2. **"Connect Me to a Broader Community."** A core tension exists between NRM's deeply connected network of leaders and the isolation experienced by members at smaller resorts. Members at places like Blacktail or Snowbowl have limited access to peers, mentors, and the informal knowledge-sharing that happens naturally at larger resorts like Big Sky.

3. **"Don't Leave Me in the Wild West: Run This Like a Professional Organization."** Members want clear, consistent communication. The research identified "High Standards Paired with Low Communication" as a key theme. The website's disorganized navigation and outdated design undermine the perception of professionalism.

4. **"Support Me Through Life Changes."** The organization supports early-career instructors effectively but lacks continuity for those whose lives evolve. Seasoned professionals who step away due to family, housing costs, or career shifts have no clear pathway back.

5. **"Be Part of Progress: Build on Our Past While Embracing the Future."** Members value NRM's traditions and culture but want evidence that the organization is evolving. The redesigned website is itself a signal of progress.

### 2.2 Current Site Audit

Key problems with the existing psia-nrm.org:

- **Information architecture organized by org chart, not user intent.** Seven top-level nav items with deeply nested dropdowns. "Board of Directors" appears under both "Info" and "Who's Who." Members must already understand NRM's internal structure to find what they need.
- **Zero engagement features.** No member directory, no forums, no mentorship tools, no community content. The only social touchpoint is a Facebook link.
- **Homepage provides no reason to return.** A rotating image carousel with mission/vision statements, a newsletter link, three upcoming events, and sponsor logos. No personalization, no fresh content, no community presence.
- **Dated visual design.** Stock resort photography, inconsistent typography, no clear visual hierarchy. Does not reflect the professionalism members expect.
- **Mobile experience is poor.** The dropdown navigation is nearly unusable on mobile devices, which is how many members access the site (from the mountain, between lessons).

---

## 3. Product Vision

### 3.1 Design Principles

1. **Organize by intent, not org chart.** Navigation and content should be structured around what a member is trying to accomplish: find my next clinic, check my pathway progress, connect with a peer, access study materials.

2. **Hub-and-spoke architecture.** The website is the central hub. It links out to best-of-breed tools for community (Discord), email (MailChimp), event registration (existing system), and digital manuals (national PSIA-AASI platform). It does not attempt to replicate these tools.

3. **Every member has a home.** The member profile page is the atomic unit of the site. It represents each instructor's professional identity within the NRM ecosystem.

4. **Community-generated content.** Member-submitted photography and video replace stock imagery, making the site feel alive and owned by the membership.

5. **Respect the brand.** All design work must use the official PSIA-AASI brand identity: logo, color palette, and typography guidelines as specified in the PSIA-AASI Brand Standards Guide (2022). The NRM region header logo must be used as provided by national.

### 3.2 Strategic Alignment

The website redesign is one component of NRM's broader strategic vision, including the "Our Mountain" campaign focused on three pillars: Instructor Excellence, Professional Sustainability, and Universal Access. The website should naturally surface Our Mountain messaging without feeling like a separate fundraising microsite.

---

## 4. Brand and Visual Design Specifications

### 4.1 Brand Identity

All visual design must conform to the PSIA-AASI Brand Standards Guide (2022 edition). The developer should obtain a copy from the NRM regional director or from PSIA-AASI national headquarters for complete guidelines on logo usage, clear space, and co-branding rules.

**Key requirements:**
- Use the official PSIA-AASI NRM regional logo as the primary site mark. Do not modify, recolor, or recreate the logo. Logo files in web-ready formats (SVG, PNG with transparency) should be obtained from NRM staff.
- All colors, interactive states, and UI elements must be derived from or complement the official brand palette defined in Section 4.2 below.
- Typography should follow brand guidelines. The PSIA-AASI brand guide recommends Calibri for documents. For web, use a clean sans-serif stack that aligns with this sensibility. Recommended web font: DM Sans (available via Google Fonts) for body text and UI elements. For display/headline use, a serif option such as Fraunces or a similar warm serif is acceptable as a complement, provided the PSIA-AASI logo and brand marks remain unaltered.

### 4.2 Color Palette

The following colors were extracted directly from the official PSIA-AASI Northern Rocky Mountain regional logo and should serve as the foundation for the site's design system.

**Primary Brand Colors:**

| Role | Hex | RGB | Usage |
|------|-----|-----|-------|
| **PSIA Teal** (Primary) | `#035368` | 3, 83, 104 | Primary text color, nav background, buttons, headings. This is the dominant brand color: a deep petrol teal, not navy. |
| **Shield Blue** | `#045996` | 4, 89, 150 | Secondary accent, links, active states, interactive highlights. Derived from the PSIA shield icon in the logo. |
| **Shield Red** | `#E31636` | 227, 22, 54 | Accent color for alerts, important badges, CTAs requiring high contrast. Derived from the red stripes in the PSIA shield. Use sparingly. |
| **White** | `#FFFFFF` | 255, 255, 255 | Backgrounds, logo elements, reverse text on dark backgrounds. |

**Extended Palette (derived, for UI use):**

The developer should derive the following functional colors from the primary palette. These are starting recommendations; exact values should be tested for WCAG AA contrast compliance.

| Role | Suggested Hex | Usage |
|------|---------------|-------|
| **Teal Light** | `#E8F4F7` | Light backgrounds, card hover states, badge backgrounds |
| **Teal Mid** | `#5B9AAE` | Secondary text, muted icons, borders |
| **Slate** | `#2D3748` | Body text (if teal is too heavy for long-form reading) |
| **Text Secondary** | `#4A5568` | Supporting text, captions, metadata |
| **Text Muted** | `#718096` | Placeholder text, timestamps, tertiary information |
| **Border** | `#E2E8F0` | Card borders, dividers, input field borders |
| **Ice** | `#F0F3F7` | Page background, alternating section backgrounds |
| **Snow** | `#FAFBFC` | Base page background |

**Color usage principles:**
- The PSIA Teal (`#035368`) is the anchor of the visual identity. It should dominate the header/nav, headings, and primary buttons.
- Shield Red (`#E31636`) is a high-energy accent. Use it for urgency or emphasis only (e.g., assessment deadlines, important notices), not as a general button color.
- Shield Blue (`#045996`) bridges the primary teal and red. Use for links, hover states, and secondary interactive elements.
- All text/background combinations must meet WCAG 2.1 AA contrast ratios (4.5:1 for normal text, 3:1 for large text).

### 4.3 Visual Direction

Within the brand guidelines, the site should feel:
- **Modern and clean.** Generous whitespace, clear hierarchy, deliberate typography. The petrol teal provides a distinctive, premium feel that differentiates NRM from generic association websites.
- **Professional but warm.** This is a community of passionate instructors, not a corporate entity. The tone should feel like a well-run outdoor brand (think Patagonia editorial quality) applied to a professional association.
- **Photography-forward.** Member-submitted photography of real NRM instructors, clinics, terrain, and mountain life should be the primary visual element. No stock photography.

### 4.4 Responsive Design

Mobile-first responsive design is required. The majority of members will access the site from mobile devices, often on the mountain between lessons. All features must be fully functional on mobile, with particular attention to navigation, event calendar, and the member directory search/filter experience.

---

## 5. Information Architecture

### 5.1 Primary Navigation (5 items)

Replace the current 7-item nested navigation with 5 clear top-level sections organized by user intent:

| Nav Item | Purpose | Key Content |
|----------|---------|-------------|
| **Home** | Dashboard/landing | Hero, quick actions, upcoming events, community spotlight, Our Mountain |
| **My Pathway** | Career progression (authenticated) | Personal certification progress, recommended next clinics, exam prep materials, scholarship finder, digital manuals |
| **Events & Clinics** | Find and register for events | Filterable calendar (by discipline, location, date, type), event details, registration links (external), nationwide calendar link |
| **Community** | Connection and engagement | Member directory, Discord link, community gallery, The Stoke newsletter archive, member spotlights, gear exchange (via Discord), job board |
| **Resources** | Reference materials and org info | Certification standards by discipline, exam forms and requirements, rules and regulations, board of directors, staff directory, education teams, member schools, sponsor recognition, About PSIA-AASI |

**Utility navigation** (top right): My Profile (authenticated) | Log In / Join

### 5.2 Page Hierarchy

```
Home
My Pathway (requires auth)
  ├── Dashboard (personalized certification progress)
  ├── Certification Standards (by discipline)
  ├── Exam Prep Hub
  ├── Scholarship Finder
  └── Digital Manuals (links to national platform)
Events & Clinics
  ├── Calendar (filterable)
  ├── Event Detail pages
  └── Registration FAQ
Community
  ├── Member Directory (searchable/filterable)
  ├── Member Profile pages
  ├── Community Gallery
  ├── Submit Media
  ├── Newsletter Archive
  ├── Discord (external link)
  └── Job Board
Resources
  ├── Discipline pages (Alpine, Snowboard, XC, Telemark, Adaptive)
  │   └── Certification requirements per level
  ├── Rules & Regulations
  ├── Board of Directors
  ├── Education Teams (all disciplines)
  ├── Member Schools
  ├── Sponsors & Partners
  └── About PSIA-AASI / About NRM
Our Mountain (campaign landing page)
  ├── Vision and pillars
  ├── How to get involved
  └── Donate (external link)
```

---

## 6. Feature Specifications

### 6.1 Member Profile Pages

**Priority: High**
**Research driver:** "Give Me a Career Pathway" + "Connect Me to a Broader Community"

Every active NRM member gets a profile page within a consistent template. This is the core building block of the community features.

**Profile fields:**
- Name (required)
- Avatar/photo (optional, with fallback to initials)
- Home resort (required, select from member schools list)
- Primary discipline (required)
- Member since year (auto-populated from membership data)
- Certifications earned (auto-populated or self-reported, with earned date)
- Certifications in progress (self-reported)
- Future goals (self-reported, optional)
- Bio/about text (optional, 500 character max)
- Specialties / teaching focus (optional tags, e.g., "Children's lessons," "First-timers," "Expert terrain")
- Open to (optional tags: "Mentoring," "Study groups," "Gear exchange," "Connecting")
- Contact preference (optional: email, or "message through site")

**Profile visibility:** Profiles are visible only to logged-in NRM members by default. Members can opt out of the directory entirely.

**Profile URL:** Each profile gets a clean URL: `psia-nrm.org/members/[firstname-lastname]`

### 6.2 Career Pathway Dashboard

**Priority: High**
**Research driver:** "Give Me a Career Pathway"

Authenticated members see a visual representation of their certification journey.

**Requirements:**
- Visual "trail map" showing certifications earned, in progress, and available next steps, displayed as a vertical timeline with status indicators (earned / in progress / future)
- Each earned certification shows the date achieved
- "In progress" certifications link to relevant upcoming clinics and exam dates
- "Recommended next clinic" block showing the most relevant upcoming event based on the member's current certifications and stated goals
- Link to scholarship finder filtered to the member's eligibility
- Links to relevant digital manuals and exam prep materials

**Data source:** Ideally pulled from PSIA-AASI national membership database via API. If API access is not available in v1, members self-report their certifications through their profile, with manual verification by NRM staff.

### 6.3 Member Directory

**Priority: High**
**Research driver:** "Connect Me to a Broader Community" + "Isolated Members at Smaller Resorts"

A searchable, filterable directory of all NRM members who have opted into visibility.

**Search and filter:**
- Free-text search (name, resort)
- Filter by discipline (Alpine, Snowboard, Cross Country, Telemark, Adaptive)
- Filter by resort (dropdown of all NRM member schools)
- Filter by certification level
- Filter by "Open to" tags (Mentoring, Study groups, etc.)

**Display:** Card-based grid layout showing avatar, name, resort, member-since year, and certification/specialty tags. Clicking a card opens the full member profile.

**Privacy:** Only logged-in members can access the directory. Members can opt out entirely or control which fields are visible.

### 6.4 Community Media Submission and Gallery

**Priority: Medium**
**Research driver:** "Build on Our Past While Embracing the Future"

Members submit photos and videos to be featured on the site, replacing stock imagery.

**Submission form fields:**
- File upload (JPG, PNG, HEIC, MP4, MOV; max 50MB per file)
- Submitter name (auto-populated for logged-in members)
- Resort / location
- Date taken
- Category (select: Teaching/Clinics, Mountain Terrain, Community Events, Off-Season Adventures, Gear/Equipment, Action/Skiing/Riding)
- Caption or story (optional, 500 characters max)
- Permission checkbox: "I grant PSIA-NRM permission to feature this content on the website, newsletter, and social media with credit."

**Moderation:** Submissions go to a review queue accessible by NRM communications staff. Only approved submissions appear in the public gallery.

**Gallery display:** Masonry or grid layout, filterable by category. Each image shows photographer credit and location. Featured images can be promoted to the homepage hero area or community spotlight section.

### 6.5 Event Calendar

**Priority: High**
**Research driver:** "Clear, Consistent Communication"

A redesigned calendar that is filterable and scannable.

**Requirements:**
- Filter by discipline, location (resort), event type (clinic, assessment, community), and date range
- Card-based event display with: date, title, location, brief description, discipline tag, and registration link
- Prominent display of next 3 upcoming events on the homepage
- Link to nationwide PSIA-AASI event calendar
- "Add to calendar" functionality (ICS download) for individual events

**Data source:** If the current WordPress calendar plugin supports an API or RSS feed, consume that data. Otherwise, manual entry by NRM staff through a CMS.

### 6.6 Homepage

**Priority: High**

The homepage serves both first-time visitors and returning members.

**Sections (top to bottom):**

1. **Hero:** Headline ("Your mountain. Your craft. Your community." or similar), subhead describing NRM's role, two CTAs ("Find Your Next Clinic" and "New Member Guide"), and key stats (member count, resort count, events this season). Background should feature member-submitted photography.

2. **Quick Actions (4 cards):** Register for a Clinic, Exam Prep Materials, Join the Community (Discord), Scholarships. Each card links to the relevant section.

3. **Upcoming Events (3 cards):** Next three events from the calendar, with date, title, location, discipline tag. "View full calendar" link.

4. **Community Spotlight:** Featured member story or profile (rotated periodically), latest issue of The Stoke newsletter, Discord community stats/link.

5. **Our Mountain Banner:** Campaign messaging with the three pillars and a CTA to the campaign landing page. Integrated naturally, not as a pop-up or separate appeal.

6. **Sponsor Recognition:** Logo bar of current sponsors and partners.

### 6.7 Hub-and-Spoke Integrations

The website links out to external tools. These are not features to build but connections to configure and present clearly.

| External Tool | Integration Point on Site |
|---------------|--------------------------|
| **Discord** | Linked from Community section, homepage quick actions, and contextually throughout (e.g., "Join the #level-3-prep channel"). Provide a persistent Discord invite link. |
| **MailChimp** | Newsletter signup form embedded on site. Newsletter archive page links to past issues. |
| **Event registration system** | Calendar event detail pages link to the existing registration flow. |
| **PSIA-AASI national site** | Digital manuals, nationwide calendar, job board, membership management all link to thesnowpros.org. |
| **Donation platform** | Our Mountain campaign page links to donation flow (Give Lively, PayPal Giving Fund, or similar). |
| **Social media** | Links to Facebook and Instagram (suggest NRM also establish an Instagram presence). |

---

## 7. Technical Specifications

### 7.1 Proof of Concept

The initial build will be a proof-of-concept prototype deployed in Docker for internal review and board presentation. This prototype should demonstrate the full user experience across all major page types (home, profile, directory, calendar, media gallery, campaign page) with realistic sample data.

**Prototype stack:**
- Static site generator or lightweight framework (Next.js, Astro, or similar)
- Responsive HTML/CSS/JS
- Sample data in JSON files (no database required for prototype)
- Docker containerized for local deployment

### 7.2 Production Considerations

The production site will need to operate within NRM's existing hosting constraints (currently WordPress on shared hosting). Options for production include:

- **Option A: WordPress rebuild** with a modern theme (GeneratePress, Flavor, or custom) and plugins for member profiles (BuddyPress or custom post types), filterable calendar (The Events Calendar Pro), and media gallery (Envira or custom).
- **Option B: Headless CMS** (WordPress as backend API, Next.js or similar as frontend) deployed on modern hosting (Vercel, Netlify, or similar). Better performance and developer experience, but higher technical complexity for ongoing maintenance.
- **Option C: Static site with CMS** (Astro or similar with a headless CMS like Sanity, Contentful, or even Google Sheets for simple data). Lowest hosting cost and best performance, but requires a build step for content updates.

The board should evaluate these options based on: who will maintain the site day-to-day (NRM staff technical capability), budget for hosting and ongoing development, and integration requirements with PSIA-AASI national systems.

### 7.3 Authentication

Member-only features (My Pathway, directory, profile editing) require authentication. Options:

- **v1 (prototype):** Simulated authentication with toggle
- **v2 (production):** Integration with PSIA-AASI member database if API is available, or standalone auth (email-based magic links recommended over passwords for this audience)

### 7.4 Accessibility

The site must meet WCAG 2.1 Level AA compliance. Key requirements: sufficient color contrast (particularly important given the PSIA brand palette), keyboard navigation, screen reader compatibility, alt text on all images, and form labels on all inputs.

### 7.5 Performance

Target: Lighthouse score of 90+ on Performance, Accessibility, Best Practices, and SEO. Critical for mobile users on mountain cellular connections. Optimize images aggressively (WebP with fallbacks), minimize JavaScript payload, and use lazy loading for below-fold content.

---

## 8. Content Requirements

### 8.1 Content to Migrate from Current Site

- All certification standards and requirements (by discipline and level)
- Event calendar data
- Board of directors and education team listings
- Member school directory
- Rules and regulations
- Scholarship information
- Sponsor/partner logos and links
- Newsletter archive links

### 8.2 New Content to Create

- Member profile template and sample profiles
- Homepage hero copy and quick action descriptions
- Community spotlight template and initial features
- Our Mountain campaign landing page copy (exists in draft form from board package)
- "Come Back" section for lapsed members
- Updated About NRM / About PSIA-AASI overview

### 8.3 Content Governance

- **Media submissions:** Reviewed by NRM communications staff before publication
- **Member profiles:** Self-managed by members, with staff ability to flag/remove inappropriate content
- **Events:** Managed by NRM staff through CMS
- **Homepage spotlight:** Rotated monthly by NRM staff
- **Certification data:** Managed by staff or synced from national database

---

## 9. Success Metrics

| Metric | Current Baseline | Target (Year 1) |
|--------|-----------------|-----------------|
| Monthly unique visitors | TBD (install analytics) | 2x baseline |
| Returning visitor rate | TBD | 40%+ |
| Member profiles created | 0 | 300+ (25% of membership) |
| Media submissions received | 0 | 100+ per season |
| Discord community members | 0 | 200+ |
| Average session duration | TBD | 3+ minutes |
| Mobile usability score | Poor | Lighthouse 90+ |
| Event calendar page views | TBD | 3x baseline |

---

## 10. Phased Rollout

### Phase 1: Proof of Concept (4-6 weeks)
- Docker-deployed prototype with all page types
- Sample data for 20+ member profiles
- Functional navigation, filtering, and responsive design
- Board presentation and feedback collection

### Phase 2: Production MVP (8-12 weeks after Phase 1 approval)
- Production deployment on selected platform
- Member authentication and profile creation
- Event calendar with real data
- Media submission pipeline
- Discord server setup and integration
- Analytics implementation

### Phase 3: Enhancement (Ongoing)
- Career pathway dashboard with live certification data
- Scholarship finder with eligibility filtering
- Mentorship matching feature
- Integration with PSIA-AASI national member database
- Our Mountain campaign donation integration
- Community gallery curation tools

---

## 11. Open Questions

1. **National database API access:** Can NRM obtain read access to PSIA-AASI membership and certification data? This determines whether pathway data is auto-populated or self-reported.

2. **Hosting decision:** Does the board want to stay on the current WordPress hosting, or invest in modern hosting for better performance and developer experience?

3. **Content management:** Who on NRM staff will be responsible for ongoing content updates (events, spotlights, media moderation)? What is their technical comfort level?

4. **Discord moderation:** Who will moderate the Discord server? Recommend 2-3 volunteer moderators from education staff or board.

5. **Budget:** What is the approved budget for development (Phase 1 and Phase 2), hosting, and any third-party tools?

6. **Brand assets:** Primary brand colors have been extracted and are specified in Section 4.2. Developer still needs: (a) the official NRM regional logo in web-ready formats (SVG preferred, PNG with transparency as fallback), (b) the PSIA-AASI Brand Standards Guide (2022) for complete logo usage rules, clear space requirements, and any additional Pantone or CMYK specifications for print collateral.

---

## Appendix A: Interactive Prototype

An interactive HTML prototype demonstrating the proposed design is available as a companion deliverable. It includes four views: Homepage, Member Profile, Member Directory, and Media Submission/Gallery. The prototype uses a preliminary color palette and should be updated with the official PSIA-AASI brand colors specified in Section 4.2 (primary teal `#035368`, shield blue `#045996`, shield red `#E31636`) before board presentation.

## Appendix B: Research Reference

The design decisions in this PRD are grounded in qualitative member research conducted in 2024, documented in the presentation "PSIA-NRM Member Research" (confidential). Five research themes and their corresponding design responses are mapped in Section 2.1.

## Appendix C: Competitive Reference

For visual and UX inspiration, the developer should review:
- **thesnowpros.org** (PSIA-AASI national site, recently redesigned)
- **psia-w.org** (Western Division, cleaner than NRM but still WordPress)
- **psia-i.org** (Intermountain Division, uses national brand well)
- **Strava** (career pathway / progress visualization inspiration)
- **Patagonia.com** (editorial photography and storytelling approach)
- **Outdoor Voices / REI community features** (member engagement patterns)
