# PSIA OAuth Client Registration Request

Adapt and send to PSIA national IT (Sean). Adjust contact name + delivery channel based on who you spoke with. Updated 2026-07-03: added the live Azure staging redirect URI and the SMTP ask (§7) so all remaining National dependencies land in one request.

---

**Subject:** OAuth client registration + website email for psia-nrm.org

We're rebuilding the PSIA-NRM division website (`psia-nrm.org`) and want members and staff to log in with their existing PSIA-AASI credentials via the OIDC endpoint at `api.thesnowpros.org/connect/authorize`. Please register an OAuth/OIDC client for us.

### 1. Provide

- `client_id` and `client_secret` (delivered out-of-band, not over email).
- Confirmation that the following redirect URIs are accepted:
  - `https://northernrocky.org/wp-login.php?action=psia-oauth-callback` and `https://www.northernrocky.org/wp-login.php?action=psia-oauth-callback` (**new production domain** — the division is moving from psia-nrm.org to northernrocky.org; the site is already live here behind Cloudflare)
  - `https://psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net/wp-login.php?action=psia-oauth-callback` (Azure origin / staging)
  - `https://psia-nrm.org/wp-login.php?action=psia-oauth-callback` and `https://www.psia-nrm.org/wp-login.php?action=psia-oauth-callback` (legacy domain, which will 301-redirect to northernrocky.org at go-live)
  - `http://localhost:8080/wp-login.php?action=psia-oauth-callback` (local dev)

### 2. Confirm configuration

- Grant type: `authorization_code` with PKCE if supported (preferred over `implicit`, which is what `PSIAMemberPortal` currently uses).
- Scopes: `openid PSIA` (same as the member portal).
- Audience: `PSIA`.
- Token lifetimes: access token, ID token, refresh token — and is refresh token rotation enabled?
- Will `offline_access` be granted so we can refresh without re-prompting?

### 3. Authorized API endpoints for this client

We plan to call these on a member's behalf after login — please confirm they're permitted for a third-party (division-owned) client, not just the first-party member portal:

- `GET /api/Members/MyAccount/WithInfo`
- `GET /api/Members/MyAccount/CurrentMembershipHistoryInfo`
- `GET /api/Education/MyEducation/AccountCertifications/Summary`
- `GET /api/Education/MyEducation/CEUTracking`

### 4. IS4-specific questions

We understand the backend is IdentityServer 4. To avoid surprises:

- Please share or confirm the OIDC discovery doc URL: `https://api.thesnowpros.org/.well-known/openid-configuration`. The fields in there (`grant_types_supported`, `response_types_supported`, `code_challenge_methods_supported`, `token_endpoint_auth_methods_supported`) tell us exactly what's enabled on your tenant.
- Are you planning to front IS4 with a middleware proxy on Azure for third-party clients like ours, or are we registering directly against IS4?
- If middleware: what's the issuer URL we should code against, and is the OIDC contract identical to IS4 from our perspective?

### 5. Operational questions

- Is `api.thesnowpros.org` the canonical production host, or is there a separate partner/integration host?
- Sandbox or test tenant available, or do we develop against production with our own real accounts?
- Rate limits per client / per token / per endpoint?
- Is the `sub` claim (account_id) stable for the lifetime of the member, or can it change on re-registration / membership lapse?
- Webhook or push notification when a member's certifications/CEUs/membership status changes, or do we re-poll on each login?
- IP allowlist required for the client backend, or is the secret sufficient?

### 7. Website email (SMTP) — separate small ask

The new site has native contact/scholarship/grant forms that need to send notification email to the office (`info@psia-nrm.org`). We need an SMTP credential the website can send through — any of these works, in order of our preference:

- **Azure Communication Services Email** in the PSIA-NRM subscription (keeps billing in the passthrough, no M365 changes), or
- an **M365 mailbox / SMTP-auth credential** for `info@psia-nrm.org` (or a dedicated `website@psia-nrm.org`), or
- an existing **SMTP relay** you already run, with a credential scoped to us.

Whatever the mechanism, we'll need the host/port/username/credential delivered out-of-band, and SPF/DKIM alignment for whatever From-address you assign. Until this lands, form submissions are safely stored in the site's database — nothing is lost — but the office gets no email notification.

### 6. Process

- Typical turnaround for client registration?
- Logo / privacy policy / terms URL needed for any consent screen?
- Security review or contractual step required before issuance?
- Named technical contact on your side for follow-ups?

---

**Technical contact (NRM):** Nick Dawson, `nickpdawson@gmail.com`. Happy to jump on a call to speed this up.
