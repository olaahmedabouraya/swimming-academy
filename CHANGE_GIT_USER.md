# 🔄 How to Change Git User

Since you haven't committed yet, it's very easy to change!

## ✅ Quick Fix (No Commits Yet)

Since you only did `git init` and `git add .` (no commits), just change the config:

```bash
cd /home/ola/swimming-academy

# Change to your correct GitHub username
git config user.name "YOUR_CORRECT_GITHUB_USERNAME"
git config user.email "your-email@example.com"

# Verify it changed
git config user.name
git config user.email
```

That's it! When you commit, it will use the new user.

---

## If You Already Committed (Not Your Case)

If you had already committed, you would need to:

```bash
# Change config
git config user.name "CORRECT_NAME"
git config user.email "correct@email.com"

# Amend the last commit (if only one commit)
git commit --amend --reset-author --no-edit

# Or rewrite all commits (if multiple)
git filter-branch --env-filter '
export GIT_AUTHOR_NAME="CORRECT_NAME"
export GIT_AUTHOR_EMAIL="correct@email.com"
export GIT_COMMITTER_NAME="CORRECT_NAME"
export GIT_COMMITTER_EMAIL="correct@email.com"
' --tag-name-filter cat -- --branches --tags
```

But you don't need this since you have no commits yet!

---

## Set Global vs Local

**For this repository only:**
```bash
git config user.name "YOUR_USERNAME"
git config user.email "your@email.com"
```

**For all repositories (global):**
```bash
git config --global user.name "YOUR_USERNAME"
git config --global user.email "your@email.com"
```

---

## Verify

```bash
git config user.name
git config user.email
```


