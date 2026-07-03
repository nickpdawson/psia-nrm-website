# PSIA-NRM Program — Master Execution Timeline

**Created:** June 9, 2026 · **Horizon:** now → Our Mountain soft launch, January 2027
**Workstreams:** ① Website · ② Engagement/Content · ③ Philanthropy (Our Mountain)
**How to read it:** near-term is week-by-week; later phases are monthly. The **PM (Claude)** column is what I drive so you don't have to hold it in your head.

**Owners:** Nick · **PM** (Claude) · Sean (National IT) · Herb (CEO) · Talent (Katie/Brenna/Jill) · Board

---

## PM operating rhythm (always on)

- **Weekly — Monday 8am:** automated status check runs. It scans email for movement from Sean, Herb, Katie, Brenna; updates the trackers; flags anything overdue; and drafts nudges when someone goes quiet 7+ days. You get a short two-section report (Website / Engagement).
- **Standing trackers:** `PROJECT-TRACKER.md` (website), `engagement/ENGAGEMENT-TRACKER.md` (content). I keep these current.
- **Your job each week:** read the Monday report, send any nudges I've drafted, make the 1–2 decisions it surfaces.

---

## ① Near term — now → board meeting (Jun 9–16)

| When | Action | Workstream | Owner | PM (Claude) drives |
|------|--------|-----------|-------|--------------------|
| Jun 9–16 | Finalize unified deck (you're editing in Keynote) | ③ | Nick | Supply paste-in updates on request; refresh Our Mountain numbers when you give them |
| Jun 9–16 | Watch for replies: Katie (email), Brenna (text), Jill (confirmed) | ② | PM | Track; remind you to check texts re: Brenna |
| Jun (soon) | Conversation with Jessica Quay (member services) re: her website opinions | ① | Nick | Track; capture her feedback into content reconciliation/UAT (item N6) |
| By Jun 16 | Prep board asks: endorse plan · Herb→Jeff · storyteller names (Red Lodge / Lost Trail / YC) | all | Nick | Draft a one-page board talking-points sheet if you want it |

## Board meeting — Jun 17–18

| When | Action | Owner | PM (Claude) drives |
|------|--------|-------|--------------------|
| Jun 17–18 | Present unified plan; get endorsement; collect the 3 storyteller names; confirm Jan 2027 target | Nick / Board | Pre-meeting brief; capture decisions + action items into the trackers right after |

---

## ① Website critical path — Jun 17 → early Aug (the gating workstream)

| Target | Milestone | Owner | PM (Claude) drives |
|--------|-----------|-------|--------------------|
| ~Jun 16 | Herb closes $ / agreement with Jeff *(was Jun 12; Herb OOO — slipping)* | Herb | Nudge on his return; escalate if needed |
| Jun 26 | Sean: hosting agreement + Azure subscription + access (nick@nickdawson.net) + OAuth client_id/secret | Sean | Weekly nudge; keep the ask specific and dated |
| **Jun 30** | **Decision gate:** if access/agreement still not done → trigger independent-hosting fallback | Nick + Herb | **Flag the trigger and tee up the fallback decision** |
| Jul 3 | Source audit of old site complete — **must capture the ed-staff invoice system** (what it is, data, can it run on a subdomain) | Nick | Remind + hold the checklist (`migration/source-audit.md`) |
| Jul (before cutover) | **Decouple the invoice system** onto a stable subdomain on the old host so launch + decommission don't break it | Nick + Sean | Track as launch dependency (N7); coordinate Sean |
| Jul 10 | Deploy theme + import to Azure staging | Nick | Track once access lands |
| Jul 17 | Wire PSIA OAuth login | Nick | Track |
| Jul 24 | Content reconciliation done; copy + SVG logos final | Nick (+ staff) | Track; chase copy from Jessica/Jill |
| Jul 31 | Board/staff UAT + punch list | Nick + Board | Coordinate testers; compile punch list |
| **Wk of Aug 3** | **DNS cutover → LAUNCH** new psia-nrm.org | Nick + Sean | Launch checklist; coordinate Sean on DNS |

## ② Engagement — filming the off-season (Jul → Aug)

| Target | Milestone | Owner | PM (Claude) drives |
|--------|-----------|-------|--------------------|
| Late Jun | Confirm "Why I Teach" full cast (incl. board-suggested names) | Nick + Jill | Maintain the roster; chase open slots |
| July | Schedule shoots: Katie (1 day @ Big Sky) · Brenna (collect clips → batch VO) · Jill cohort · Why-I-Teach (cluster by hill) | Nick / Talent | Build + hold a **filming calendar**; confirm dates with each |
| Jul–Aug | Film all series | Nick | Track against the calendar; flag slippage |

---

## ② + Connection tools — content rollout (Aug → Oct)

| Target | Milestone | Workstream | Owner | PM (Claude) drives |
|--------|-----------|-----------|-------|--------------------|
| Aug→ | Edit & release video series on cadence (~1 per 1–2 weeks) | ② | Nick | Hold the **editorial calendar**; track releases |
| Aug–Sep | Build connection tools: client landing page · share link/QR · "share a moment" capture · follow/opt-ins (profiles already done) | ② | Nick | Track the build list (`connection-tools-concept.md`) |
| Sep–Oct | Pilot the soft-share with Katie/Brenna/Jill cohort; learn what feels natural | ② | Nick + Talent | Capture learnings; refine the toolkit |
| Oct | Open member-generated content (submission + light moderation) | ② | Nick | Track |

## Fast-follow (post-launch, Aug+)

| Target | Milestone | Workstream | Owner | PM (Claude) drives |
|--------|-----------|-----------|-------|--------------------|
| Aug+ (after launch) | **Ed-staff invoice system REVAMP.** (Note: *decoupling* it happens pre-launch — see above. The actual rebuild is the fast-follow.) Coordinate with Jessica. | ① | Nick | Help scope requirements after launch |

## ③ Campaign prep → launch (Nov → Jan 2027)

| Target | Milestone | Owner | PM (Claude) drives |
|--------|-----------|-------|--------------------|
| Nov | Refresh Our Mountain plan — current numbers, pillars, asks | ③ | Nick | Update materials once you give me the figures |
| Nov–Dec | Re-cut campaign sizzle from the story library; connection toolkit live + adopted | ②③ | Nick | Asset checklist |
| Dec | Board alignment on launch | ③ | Nick + Board | Readiness one-pager |
| **Jan 2027** | **Our Mountain soft launch** — into a warm, aware audience | ③ | Nick + Board | Launch-week coordination |

---

## The three things that most determine the timeline

1. **Herb closing the $ with Jeff** — gates everything on the website. Until it's done, Sean won't provision. *(Currently slipping — Herb OOO.)*
2. **Sean's Azure access by Jun 30** — the hard decision gate for National-vs-independent hosting.
3. **Filming the video content this summer** — the off-season is the only window; a slip here cascades into the fall rollout and weakens the Jan 2027 Connection library.

---

## Want me to set reminders?

I can drop one-time reminders at the hard dates (e.g., **Jun 30** hosting decision gate, **Aug 3** launch week, filming deadlines) so they surface even if a Monday report is light. Say the word and I'll schedule them.

---

*Master schedule. Detail lives in `PROJECT-PLAN.md`, `PROJECT-TRACKER.md`, and the `engagement/` docs.*
