# Fix GitHub push (403 / permission denied)

GitHub is rejecting your token. Do this **once** to get a working token, then push.

## 1. Create a **classic** token with **repo** scope

1. Go to: **https://github.com/settings/tokens**
2. Click **"Generate new token"** → **"Generate new token (classic)"**
3. **Note:** e.g. `swimming-academy`
4. **Expiration:** e.g. 90 days
5. Under **Scopes**, check **`repo`** (full control of private repositories)
6. Click **"Generate token"**
7. **Copy the token** (starts with `ghp_...`) — you won’t see it again

**Important:** If you use **"Fine-grained"** tokens, you must:
- Add **Repository access** → "Only select repositories" → choose **swimming-academy**
- Set **Repository permissions** → **Contents** = Read and write  

Classic token with **repo** is simpler and recommended.

## 2. Push (and store the token for next time)

In a **system terminal** (outside Cursor):

```bash
cd ~/swimming-academy
./push-with-prompt.sh
```

- **Username:** `olaahmedabouraya`
- **Password:** paste the **new** token (the `ghp_...` string)

To save it so you don’t have to paste again:

```bash
git config --global credential.helper store
```

Then run `./push-with-prompt.sh` once more and enter username + new token. After that, `git push` will work without asking.

## 3. If you still get 403

- Confirm you’re logged into GitHub as **olaahmedabouraya** and that **olaahmedabouraya** owns the repo **swimming-academy**.
- On the token page, make sure the token has **repo** (classic) or **Contents: Read and write** and repo access (fine-grained).
