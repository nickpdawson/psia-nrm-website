# Draft email → Sean Steele (National IT) — DO NOT SEND until reviewed

**Status:** Ready to send. ⚠️ The weekly check (Jul 6) found that the OAuth + SMTP request the tracker had marked "sent Jul 4" was **never actually sent** — there is no such message in Sent mail and no Gmail draft. The full technical spec exists at `migration/psia-oauth-registration-request.md`; this is a short cover email to go with it. This is the true long pole for member login (N5) — the clock on Sean has not started yet.
**Send from:** nick@nickdawson.net
**To:** ssteele@thesnowpros.org
**Cc:** nforktrails@gmail.com (Herb — optional, keeps him in the loop)
**Subject:** OAuth client registration + website email for psia-nrm.org

---

Hi Sean,

Thanks again for getting the PSIA-NRM subscription and access stood up — that unblocked us and the new site is already running on Azure staging in that subscription.

Two things we need from National to finish member login and site email. I've kept the detail in an attached spec, but the short version:

**1. OAuth/OIDC client** so members and staff can sign in with their existing PSIA-AASI credentials. We need a `client_id` + `client_secret` (delivered out-of-band, not over email), confirmation of our redirect URIs, `authorization_code` + PKCE, and scopes `openid PSIA`. Redirect URIs to whitelist:
- `https://northernrocky.org/wp-login.php?action=psia-oauth-callback` and the `www` variant (our production domain)
- `https://psia-nrm-website-gvcsgxcxdpaxg0hp.canadacentral-01.azurewebsites.net/wp-login.php?action=psia-oauth-callback` (Azure origin / staging)

**2. Website email (SMTP)** so the contact/scholarship/grant forms can notify the office at info@psia-nrm.org. Azure Communication Services Email in the PSIA-NRM subscription would be ideal (keeps it in the passthrough), or an M365/SMTP-auth credential for that mailbox — whatever's easiest on your end.

Full spec with the endpoint list and IS4-specific questions is here: [paste/attach `migration/psia-oauth-registration-request.md`].

This is the last National dependency before we can wire up login, so anything you can do to get the client registered would be a big help. Happy to jump on a call to speed it along.

Thanks Sean,
Nick
