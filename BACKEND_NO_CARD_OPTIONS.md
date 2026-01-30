# Backend hosting when you can’t add a credit card

## The situation

- **Render, Koyeb, Fly.io** (and similar “free API” hosts) often **require a credit card** for signup/verification. They usually **don’t charge** if you stay in the free tier, but they ask for a card.
- **InfinityFree, wuaze** (and similar “no card” PHP hosts) **inject HTML/ads** into responses, so your API returns HTML instead of JSON and **login/register break**. (**000webhost** was shut down by Hostinger in 2024; Hostinger does not offer free hosting.)

So: hosts that don’t inject ads usually want a card; hosts that don’t want a card usually inject ads.

---

## Options if you really can’t add a card

### 1. Use a card only for verification (no spend)

- Add a **prepaid or virtual card** with **no or minimal balance** (e.g. Privacy.com, Revolut virtual card, or a local prepaid).
- Sign up to **Render**, **Koyeb**, or **Fly.io** with it. Stay in the **free tier** so you’re not charged.
- Use that backend URL as `API_URL` in Vercel. Login/register will work.

### 2. ~~000webhost~~ (no longer available)

- **000webhost** was shut down by Hostinger in 2024 (no new signups; platform closed). Hostinger does not offer free web hosting, so this option is no longer available.

### 3. Self-host + tunnel (for testing only)

- Run the Laravel backend **on your own machine** (e.g. `php artisan serve`).
- Expose it with **ngrok** or similar: `ngrok http 8000` → you get a public URL.
- In Vercel, set **`API_URL`** to that ngrok URL (e.g. `https://xxxx.ngrok.io`).
- **Limitations:** Your PC must be on and the app running; the ngrok URL changes when you restart (free tier). Good for **development/demos**, not for a permanent production API.

### 4. Oracle Cloud free tier (card for verification only)

- **Oracle Cloud** has an “always free” tier (VMs, etc.).
- Signup usually **requires a card** for verification; they **don’t charge** if you stay within the free tier limits.
- So it’s “no card” in the sense of “no ongoing cost,” but not “no card at signup.”

---

## Summary

| Option                    | Card required? | API works (no ad injection)? |
|---------------------------|----------------|------------------------------|
| Render / Koyeb / Fly.io   | Yes (verify)   | Yes                          |
| InfinityFree / wuaze       | No             | No (ads injected)             |
| Self-host + ngrok         | No             | Yes (while your PC + ngrok run) |
| Oracle Cloud free         | Yes (verify)   | Yes                          |

**Practical recommendation:** If you can use **any** card (even a virtual/prepaid with no balance) only for verification, use **Render**, **Koyeb**, or **Fly.io** and stay in the free tier — that’s the most reliable way to get a working API. If you absolutely cannot add a card, the only no-card option that returns clean JSON is **self-host + ngrok** for testing. (000webhost closed in 2024.)
