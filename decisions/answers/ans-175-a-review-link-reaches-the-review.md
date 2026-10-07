---
id: D-ANS-175
title: A review link reaches the review
date: 2026-10-07
status: open
coveredBy:
  - GerritTest::aChangeThatNoLongerMergesNamesWhereItsCauseIsRead
  - GerritTest::aReviewLinkIsReadAsTheChangeItNames
  - HintsTest::aReviewLinkRoutesTheCoreReview
  - ScopeTest::theInstructionsIndexTheQuestionEachToolAnswers
---

# D-ANS-175 — A review link reaches the review

**A task that hands over a review.typo3.org link reaches `typo3_gerrit_lookup`
and the core review workflow, and the lookup takes the link as its `change`.**

A session asked to "review
https://review.typo3.org/c/Packages/TYPO3.CMS/+/<change>" in a core checkout
called nothing on this server. It read the change with curl and git instead.

## Evidence

- `feedback/2026-10-07-070108` reports the session. Its client deferred the tool
  schemas, so the session had the names and the `instructions`. The index in the
  `instructions` named no tool for a change on the review server.
- Re-run on 2026-10-07 in that checkout. `typo3_task_guide` with the task text
  opened on the notice for work outside the core. It named no skill and no
  review intent. The cause was `Scope::of()`. It read `packages/` in the
  `/c/Packages/TYPO3.CMS/` of the link as a package path. The `audit` intent
  matched none of its phrases either, because a link follows the word "review".
- `typo3_gerrit_lookup` with the link as `change` answered "The review server
  did not answer". Gerrit refuses a URL in `change:` with HTTP 400, the same
  error `D-ANS-106` found for a hash.
- The same call with `change: "<change>"` answered `mergeable: false`. The cause
  of the conflict was the main finding of the review. The session found it with
  `git log` on the touched file. The procedure for that read is step 1 of
  `core/contribution/rebasing-a-stale-patch`, and no answer led there.

## Decided

- Step 3 and step 2 of the ladder, plus one shape the lookup lacked. Routing:
  the scope placement and the intent. Delivery: an index entry in the
  `instructions`. Shape: the link as `change`.
- `Scope::of()` removes URLs from the task text before it looks for the
  extension markers. A URL names a place on another host. The core markers still
  read the whole text, because `review.typo3.org` is core evidence.
- `audit` matches "review http" and "review https". A link after "review" is a
  review whatever host it names.
- `GerritLookup::changeOf()` reduces a review link to its change number. A patch
  set the link names after the number goes, because the answer reads the current
  one, which `patch-checkout` already asks for.
- A change read by name that no longer merges carries one line. It gives the
  `git log` call over the change's parent and the target branch, and the
  documentId that carries the rest. The server runs no git, so the line hands
  the call over rather than answers it.
- The index entry is "a change on review.typo3.org: typo3_gerrit_lookup". Two
  cuts pay for it under `R-ANS-013`. The label entry loses "a match elsewhere is
  not reusable", which its own call already implies. The changelog entry says "a
  major new to you". The longest assembly measures 2036 characters.
- The diff hunks stay outside the answer. `D-ANS-112` draws that boundary, and
  this session needed the fetch for the reason that decision gives.
- Built in the judging run because the maintainer asked for it on 2026-10-07,
  although the change touches `src/`.

## Assumed

- That the URL shapes of the current server and of the old one are all a task
  hands over. `Forge::handles()` reads the same two from issue notes.
- That "review" followed by a link is never something other than a review.

## Wrong if

- A session with the index entry in its context reads a review link with curl
  again. Then the index is not the channel for this question, which is
  `D-AUD-011`'s first **Wrong if**.
- A task text places its work by a URL that does name a directory of the work.
  Then the URL has to be read and not removed.
- The `git log` line names commits that did not cause the conflict on most stale
  changes. Then a path filter is the wrong narrowing.
