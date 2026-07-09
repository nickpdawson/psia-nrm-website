# Member self-service profiles (post-OAuth) — design

**Question:** once members sign in through PSIA OAuth, how do they add their own photo, bio,
social/tip links, and public toggle?

**Answer:** they edit their own `nrm_member` record through a **front-end "Edit My Profile" form**
(not wp-admin — members aren't WordPress users). The fields already exist (built 2026-07-09): photo
(Featured Image), bio, website/IG/FB/LinkedIn, Venmo/Zelle/Apple Cash, and the public-profile flag +
consent. OAuth just unlocks *who* may edit *which* record.

## Flow
1. Member clicks **Log In with PSIA** → OAuth to `api.thesnowpros.org` → returns their identity
   (the `sub` / PSIA account id).
2. **Identity → member record mapping.** We store `nrm_psia_account_id` on each `nrm_member` post.
   - Existing members: backfill this from the PSIA account code (people.json has an `id`; confirm it
     equals the OAuth `sub`, else match on email as a fallback and store the id on first login).
   - New members: first login creates/links a record.
3. Member lands on **My Profile** (`/pathway`, currently a mock) which gains an **edit mode**, or a
   dedicated `/my-profile/edit`. A custom front-end form writes to *their own* record's meta —
   guarded so the session's account id must equal the post's `nrm_psia_account_id`.
4. **Photo upload:** front-end file input → our plugin creates an attachment and sets it as their
   member Featured Image (same mechanism as the `member-photos.json` importer, but member-initiated
   and self-scoped).
5. **Public toggle** shows the approved consent text; unchecking removes them from the directory.
6. **Annual re-confirm (pruning):** at renewal, prompt the member to re-confirm their public profile;
   if they don't, auto-unpublish. Ties to renewal status from the national API.

## Decisions needed (Nick/board)
- **Moderation:** do member edits (photo, bio) go live immediately, or queue for staff approval?
- **WP users vs. session mapping:** front-end form + lightweight OAuth session (recommended — keeps
  wp-admin closed to members), vs. creating a restricted WP user per member.
- **Default visibility for new members:** private until they opt in (recommended), matching consent.
- **What a member may edit** vs. what's authoritative from the national API (certs/CEUs are read-only
  from PSIA; bio/photo/links/tips are member-owned).

## Dependencies
- S5 OAuth client from National (the gate for all of this).
- Confirm the `sub` claim = the PSIA account code so the mapping is stable (already flagged in the
  OAuth request as a question).
