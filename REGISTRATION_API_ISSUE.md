# Registration / API fails: "Hosting service is injecting ads"

## What’s going wrong

Your **backend** is on a host (e.g. **InfinityFree**, **wuaze**) that **injects HTML/ads** into every HTTP response.  
When the frontend (e.g. on Vercel) calls `POST /api/register`, it expects **JSON**, but the server returns **HTML** (ad scripts). The app can’t parse that, so registration (and other API calls) fail.

## Reliable fix: move the backend off that host

Use a backend host that **does not modify API responses**:

1. **Render** (free tier, no card) – recommended  
   - Follow **[DEPLOYMENT_RENDER.md](DEPLOYMENT_RENDER.md)**  
   - Deploy the Laravel backend to Render, then set the **API URL** in Vercel to your Render backend URL (e.g. `https://swimming-academy-api.onrender.com`).

2. **Fly.io** or **Koyeb**  
   - See **[DEPLOYMENT_FLYIO.md](DEPLOYMENT_FLYIO.md)** and **[DEPLOYMENT_KOYEB.md](DEPLOYMENT_KOYEB.md)**.

After the backend is on Render (or similar), **registration and login will work** because the API will return plain JSON.

## What we did in the app

- The frontend **retries** POST requests once with `?i=1` when it detects injected HTML (some hosts return clean JSON on that).
- If the host **always** injects (like InfinityFree/wuaze), that retry still returns HTML, so **registration will still fail** until you move the backend.

**Summary:** Point your frontend’s API URL to a backend on **Render** (or another ad-free host) and redeploy; then registration will work.
