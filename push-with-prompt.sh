#!/bin/bash
# Run this script in a terminal OUTSIDE Cursor (e.g. Terminal, Konsole).
# It disables stored credentials and GUI askpass so Git will prompt you
# for username and Personal Access Token in the terminal.

cd "$(dirname "$0")"

# Don't use any stored credentials; don't use GUI askpass
export GIT_ASKPASS=
export GIT_TERMINAL_PROMPT=1

git -c credential.helper= push "$@"
