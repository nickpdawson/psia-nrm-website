# Draft nudge → Sean Steele (National IT)

**To:** ssteele@thesnowpros.org
**Cc:** Herb Davis
**Subject:** NRM site — ready to roll the moment Azure access lands

---

Hey Sean,

Quick check-in to keep the NRM site moving — our side is built and board-approved, so we're really just waiting on the Azure setup to launch this summer (the slow season is our ideal window to cut over).

Here's the short list we need from you, roughly in order:

1. **Hosting agreement** — whatever you and Jeff land on. Herb's handling the $ in parallel, so hopefully quick.
2. **Azure subscription + access** — the new PSIA-NRM subscription on the National tenant, with guest access for **nd@nickdawson.net**.
3. **Infra** — App Service (**Linux B2**) + a **MySQL Flexible Server** (burstable). Our rebuild is a clean theme without the old resource-heavy plugins, so B2 should land us near the ~$40/mo you mentioned.
4. **OAuth client** — a `client_id` and `client_secret` for member sign-on.

If it's easier to grant access (#2/#3) while the agreement finalizes, I'm glad to start on staging right away. Could we target subscription + access by **June 26**? Happy to hop on a quick call this week if that's faster.

Thanks Sean — appreciate it.

— Nick
