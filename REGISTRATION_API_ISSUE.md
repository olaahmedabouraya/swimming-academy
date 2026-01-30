# Registration / API fails: "Hosting service is injecting ads"

## What’s going wrong

Your **backend** is on a host (e.g. **InfinityFree**, **wuaze**) that **injects HTML/ads** into every HTTP response.  
When the frontend (e.g. on Vercel) calls `POST /api/register`, it expects **JSON**, but the server returns **HTML** (ad scripts). The app can’t parse that, so registration (and other API calls) fail.

## Reliable fix: move the backend off that host

Use a backend host that **does not modify API responses**. These **do not require a credit card**:

1. **Koyeb** (no card, no verification – GitHub signup only) – recommended  
   - Follow **[DEPLOYMENT_KOYEB.md](DEPLOYMENT_KOYEB.md)**  
   - Deploy the Laravel backend to Koyeb, then set the **API URL** in Vercel to your Koyeb backend URL (e.g. `https://swimming-academy-api-xxxxx.koyeb.app`).

2. **Fly.io** (no card required)  
   - Follow **[DEPLOYMENT_FLYIO.md](DEPLOYMENT_FLYIO.md)**  
   - Deploy with Fly CLI, then set **API URL** in Vercel to your Fly URL (e.g. `https://swimming-academy-api.fly.dev`).

**Note:** Render, Koyeb, and Fly.io may all require a credit card for signup/verification (they usually don’t charge on the free tier). If you **cannot add a card at all**, see **[BACKEND_NO_CARD_OPTIONS.md](BACKEND_NO_CARD_OPTIONS.md)** for options (e.g. self-host + ngrok, or using a virtual/prepaid card only for verification).

## What we did in the app

- The frontend **retries** POST requests once with `?i=1` when it detects injected HTML (some hosts return clean JSON on that).
- If the host **always** injects (like InfinityFree/wuaze), that retry still returns HTML, so **registration will still fail** until you move the backend.

**Summary:** Point your frontend’s API URL to a backend on **Koyeb** or **Fly.io** (no card required) and redeploy; then registration will work.
