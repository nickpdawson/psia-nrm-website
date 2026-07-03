# PSIA-NRM App Strategy — Design Session Notes

**Date:** 2026-06-19
**Posture:** Flair (divergent / generative)
**Role:** Field Researcher → Studio Peer
**Participant:** Nick Dawson (channeling working instructors)
**Method:** Proxy interview — Nick spoke as specific real instructors he knows, with probes designed to surface workarounds, friction, and tacit practice rather than stated preferences.

---

## 1. The brief Nick walked in with

Two linked initiatives:

1. **NRM website** — gains a real API as part of the move to Azure B2C auth (replacing the WordPress prototype currently at `psia-nrm.dzsec.net`).
2. **IJFS App refactor** — the existing "It's Just Fun Skiing" iOS app (live in App Store, public beta since April 2026) becomes the **official PSIA-AASI NRM app**. Authenticates via thesnowpros.org credentials. Adds:
    - Mini-CRM for instructors' own ski clients
    - Post-lesson video review template (public message + private instructor notes)
    - One-stop hub for training, certifications, client management
    - Low-friction member-generated content capture for NRM social/web
    - LLM-based examiner aid for assessment forms — with hard guardrail that nothing sent to a member should ever feel LLM-authored
    - Possible Cloudflare Workers as a broker; Nick will sponsor a ~$10/mo Anthropic API key for POC

---

## 2. The cast we worked with

| Person | Role | Mountain | Why interesting |
|---|---|---|---|
| **Garrett** | Working instructor, privates, mostly teens/kids | Big Sky | "Fun big brother" / structured-play model. Greenfield CRM user — has never had a tool. |
| **Brenna** | Examiner + instructor with regular client book | (NRM) | Already does the workflow well, manually, in iMessage. Hardest design bar to clear. |
| **Nick himself** | Triangulating data point | — | Surfaced a five-part follow-up shape: *Pride → Did → Helped → Next → Goal* |

---

## 3. What we learned

### The lesson loop is bookended, not in-progress
Nick course-corrected an early frame: don't design for *during* the lesson. The phone interactions are **morning** (logistics, prep) and **evening** (follow-up, reflection). Design surface is a kitchen at 7pm, not a glove at 11am. This simplifies a lot: glove-friendliness matters less; notifications, templates, and the at-home context matter more.

### There is no single follow-up template — there is a family
Three distinct shapes from three instructors:

- **Brenna mode:** video-forward, minimal text. The clip is the message.
- **Garrett mode:** parent-facing technical recap. The audience is the paying adult, not the student.
- **Nick mode:** five-part scaffold — Pride / Did / Helped / Next / Goal.

**Implication:** Ship a family of starter shapes, or let instructors import 3 of their past follow-ups and reflect the shape they already use back to them. The "voice-mirror" onboarding is warmer than fill-in-the-blanks.

### The "Next" beat is the CRM hook
Every follow-up style has a forward-looking line. *That* is what evaporates in iMessage and what the CRM should hold. Not contact info — the **promise to next time**. Three weeks later, when a regular books again, "we'll start to build toward A" is the memory worth keeping.

### Private instructor notes exist universally
Confirmed across all three: everyone keeps notes they don't send ("kid is closer to L2, push next year," "mom is the real client here"). This is a core data model concern, not a setting. Public client-facing summary + private instructor-only notes attached to the same client/lesson record.

### Video is ephemeral — hard constraint
Quote: *"Shoot, send and forget or delete. I don't want the app or system to store video."*

This rewires the architecture:

- Template **captures structure**, references that a video was shared — but does not ingest the video itself
- "Side-by-side with reference video" = instructor's local clip plays alongside *streamed* PSIA reference video; never uploaded
- Member-generated content for NRM = pass-through, not retained at source
- Sidesteps minor-consent retention, cloud cost, liability, and competition with Photos / iMessage

### You cannot research the CRM behavior — it doesn't exist yet
Most instructors have never had a CRM, so requirements-style interviewing is dead end. **Build minimum useful, pilot with 2–3, observe what they actually do across a season.** Don't try to design ahead of the behavior.

### Garrett's "what I'd never admit to PSIA"
*"The org and my certifications matter more to me and my journey than NRM wants to know."*
This is a positioning insight, not a workaround. The app risks feeling like an org tool. If instead it feels like **Garrett's personal career-progress tool that happens to be made by NRM**, framing shifts entirely.

---

## 4. The three concentric loops (working model)

The app's integrated identity, centered on professional craft:

| Loop | What it serves | Frequency | Examples |
|---|---|---|---|
| **Inner — your craft as a teacher** | Daily lesson loop | Daily | Mini-CRM, follow-up template family, private notes, promise memory, video send-via-Photos |
| **Middle — your craft as a learner** | Cert pathway | Weekly | Pathway dashboard mirrored from website, study tools, register for clinics/exams (if PSIA national API allows), examiner tools when qualified |
| **Outer — your craft as part of NRM** | Community / region | Occasional, high emotion | Member-generated content capture, Why I Teach moments, peer visibility |

**Sequencing principle:** *the app earns the right to ask for content by being useful first.* Build inner loop first; outer loop gets traffic for free. Reverse order = another portal nobody opens.

---

## 5. Member content capture — the inversion

Current NRM engagement strategy is *NRM asks → member responds* (member-stories-brief, outreach docs). The new app inverts it: *member offers → NRM receives*.

**Design moves:**

- **In the moment, not after.** Capture feels like Stories, not a Google Form.
- **Consent framed by default.** "Share with NRM" implies baseline permission (newsletter, social, gallery — with attribution unless declined). Rules visible once, not re-prompted.
- **Acknowledged fast by a human.** Brenna or Jill at NRM responds within 24 hours: *"Love this — ok if we use it in next month's newsletter?"* Acknowledgment is the engagement loop, not the content itself.
- **Credit visible.** Member sees their moment used. Reinforces participation.
- **The wedge:** the "share with NRM too?" toggle appears *in the same screen the instructor is already using to pick photos for their lesson follow-up.* No extra work; existing daily loop becomes the content pipeline.

---

## 6. LLM principle — pen, not autopen

For both the examiner aid AND lesson follow-up templates:

- LLM never *writes the message.* It suggests shapes, offers phrasings, mirrors voice.
- Examiner / instructor approves and edits every word.
- Output structurally constrained to feel like *their* voice.
- Examiner cues: "here are three ways someone might describe this movement — pick or remix." Never a paragraph.
- Voice-mirroring onboarding: import 3 past follow-ups → LLM reflects shape and tone.

---

## 7. Risks named

1. **Scope.** Three loops + LLM + event registration is ~18 months, not 6. The inner loop alone is a v2.0 of IJFS. Phasing required.
2. **NRM curatorial capacity.** Capture is the easy half. *"Thanks, here's how we'll use it"* within 24 hours is the load-bearing half. No staff capacity → feature dies in week three.
3. **PSIA National API readiness.** The middle loop's tactile features (register-in-app for clinics, pathway-as-transaction) depend on what Sean Steele's team exposes. Auth + read likely; write/registration may not exist.

---

## 8. Open questions

- **Curatorial inbox at NRM** — who? what cadence? what tooling on the receiving end?
- **PSIA national API surface** — read-only or transactional?
- **Consent model for video shared with minors** — even though we don't store video, when a parent's phone receives an instructor's clip, what's the implied permission for that clip's reuse? Likely none — keeps video strictly 1:1 instructor↔client, while NRM content capture is a separate explicit submission.
- **Cross-discipline coverage** — Brenna and Garrett are alpine. How does this look for snowboard and XC? Different lesson rhythms, different gear, different cert paths.
- **Examiner workflow today** — we haven't grilled this yet. What does a post-clinic / post-exam feedback to candidates look like today? How long does it take? Where's the friction the LLM aid actually relieves?
- **Repeat-client memory in practice** — when Brenna's October booking arrives, does she actually look back at last year's notes? If not today, would she with the right surface?

---

## 9. Step zero

> Text Garrett, Brenna, and one other (Jill?) and ask:
> *"If I built a one-screen tool where after a lesson you pick a photo from your roll, tag what it was about, and one toggle says 'share with NRM' — would you use it next time you teach? What would have to be true?"*

Not a survey. Not a wireframe. Three texts, three honest answers, before we design another pixel.

---

## 10. Suggested next sessions

In rough priority:

1. **Examiner LLM aid — deep dive.** Brenna's examiner hat. What's the current post-clinic / post-exam feedback workflow? What does an examiner write today, how long does it take, what gets dropped? The "must never feel LLM-y" guardrail needs to be grilled against a real workflow before we design the prompt scaffold.
2. **NRM curatorial workflow design.** The receiving end of the content inbox. Who owns it, what tools, what the 24-hour acknowledgment looks like as a real human process.
3. **Phasing & POC scope.** Given the three-loop model, what's the smallest version of the inner loop that's worth a real pilot with three instructors across a season? What ships in Phase 1?
4. **PSIA National API discovery.** Conversation with Sean Steele to confirm what's exposed beyond OAuth.
