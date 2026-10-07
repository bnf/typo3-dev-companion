---
id: D-FBK-057
title: A feedback names the commit that answered it
date: 2026-10-07
status: open
coveredBy:
  - FeedbackTest::aNoteSaysWhichCommitOfTheServerAnsweredIt
---

# D-FBK-057 — A feedback names the commit that answered it

**`typo3_feedback_record` writes `server:` with the commit of this server's
checkout, and `serverStale: true` where the process is older than that code.**

A judgement compares a feedback with the server as it is now. What it cannot
read is which server the session had.

## Evidence

- `feedback/2026-10-07-074922` reported a recurrence of a fix made the same
  morning. It said itself that it could not verify whether the running server
  had the fix. Its instructions carried the new index line, which settled it
  here. The next feedback may carry nothing like that.
- `feedback/archive/2026-07-30-174423` named the server commit by hand. So a
  session asks for the field when it can.
- `Upstream` already reads the commit from the git directory without git.
  `CodeAge` already knows whether the process is older than its checkout.

## Decided

- Two front matter fields, from two readers that exist. No argument for the
  caller to fill, because the caller does not know the value.
- The commit is HEAD. A change in the working tree is not in it, which is the
  case of a session that runs a branch nobody has committed yet.
- No field where there is no git directory. A missing field and a guessed one
  read the same afterwards.

## Wrong if

- A judgement still cannot tell whether a fix was live, because the working tree
  differed from HEAD.
