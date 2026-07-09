# NRM Website — Feature Backlog (from feedback meeting 2026-07-09)

Triaged by dependency. **Buildable now** = no external blockers. **API/OAuth** = needs the
national `api.thesnowpros.org` client (S5, not yet issued) or member login. **Policy** = needs a
Nick/board decision before building.

## Events
| # | Item | Bucket | Notes |
|---|------|--------|-------|
| E1 | **Calendar (month) view** | Buildable now | JS calendar over the `nrm_event` CPT; toggle with the existing list view. |
| E2 | **Filters** (discipline, event type, "just CEUs", state) | Buildable now | Client-side filter on the archive; mirrors old psia-calendar filters. Needs a "CEU-eligible" flag on events (new meta) + event-type already exists. |
| E3 | **.ICS export + subscription** | Buildable now | Per-event `.ics` download + a `webcal://` feed endpoint (all events, and filtered feeds). No API needed. |
| E4 | **Other regions + national events, flagged** | **API** | Like thesnowpros.org/calendar. Needs the national events feed/API (or a scrape). Blocked on S5 or a data source. |

## Member directory & profiles
| # | Item | Bucket | Notes |
|---|------|--------|-------|
| D1 | **Find-a-member search** | Buildable now | Search + filter (name, discipline, school, role) over `nrm_member`. |
| D2 | **Multidisciplinary support** | Buildable now (mostly done) | `nrm_discipline` is already multi-term; audit profile + card rendering to show ALL disciplines/certs cleanly. |
| D3 | **Social links + payment/tip links on profiles** | Buildable now | New member meta (website, IG, FB, LinkedIn, Venmo/PayPal/tip URL) + render. Editable in the member metabox. |
| D4 | **New member tags**: School Director / Contact / Maintainer | Buildable now | Add to `nrm_role` (or a new `nrm_school_role` taxonomy). Drives D5/S-school features. |
| D5 | **Public-profile opt-in + policy pop-up** ("strictly professional use") | **Policy + API** | The *policy text + consent modal* is buildable; the *member self-opting-in* implies member login (OAuth). Interim: staff toggle a `public` flag per member. Needs the policy wording from Nick/board. |
| D6 | **Annual pruning** — re-confirm public profiles at renewal | **API + Policy** | Ties to renewal data (national API) + a workflow. Design after OAuth. |

## Member schools
| # | Item | Bucket | Notes |
|---|------|--------|-------|
| S1 | **Member-school info page** (how to pay, program info) | Buildable now | Content page, mirrors old `/membership/member-school/`. |
| S2 | **Member-school public profiles** (like member profiles) | Buildable now | Promote `nrm_school` from taxonomy to a richer profile (logo, blurb, contact, link) — either enhance the taxonomy page or add a `nrm_school` CPT. Bigger lift; decide data model. |

## Member-facing profile (the /pathway dashboard)
| # | Item | Bucket | Notes |
|---|------|--------|-------|
| P1 | **CEU status + "quickest way to get CEUs" advisor** | **API** | Live CEU/cert data is OAuth-gated. The advisor logic (match member's gap to upcoming CEU-eligible events) can be prototyped now against E2's CEU flag once login exists. |

## Help / documentation (member-facing)
| # | Item | Bucket | Notes |
|---|------|--------|-------|
| H1 | **Help/docs pages**: set up your member page, pay dues, find events, etc. | Buildable now | Public help section. Distinct from the staff `EDITING-GUIDE.md`. |

## Recommended sequence
1. **Events upgrade E1+E2+E3** (calendar view, filters, ICS/subscribe) — highest member value, fully unblocked, and the events area is the most-used page. *Start here.*
2. **D1 Find-a-member search** + **D2 multidisciplinary audit** + **D3 social/tip links** + **D4 tags** — directory becomes a real tool; all unblocked.
3. **S1 member-school info page** (quick) then **S2 school profiles** (decide CPT vs taxonomy first).
4. **H1 help/docs**.
5. **Policy decisions needed from Nick/board** before D5/D6: public-profile consent wording, opt-in model (staff-toggle now vs member-self-serve post-OAuth), annual re-confirm process, allowed payment/tip providers.
6. **After OAuth (S5)**: E4 national events feed, D5/D6 self-serve + pruning, P1 CEU advisor.
