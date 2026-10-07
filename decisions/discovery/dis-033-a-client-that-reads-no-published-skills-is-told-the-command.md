---
id: D-DIS-033
title: A client that reads no published skills is told the command
date: 2026-10-07
status: open
coveredBy:
  - InstallerRecordTest::aClientThatReadsNoPublishedSkillsIsToldTheCommandThatWritesThem
  - ScopeTest::theInstructionsFitWhatAClientKeeps
---

# D-DIS-033 — A client that reads no published skills is told the command

**Where `install` published skills, and the client that connects reads none of
the directories it wrote into, the initialize instructions open on
`install --agent=<client>`.**

`install` without `--agent` writes `.mcp.json` and publishes the skills to
`.agents/skills`. Claude Code reads the first and not the second. So it had the
server and none of the skills, and nothing said so.

## Evidence

- `feedback/2026-10-07-075619`. The user had run `install` without `--agent`.
  The session listed no `typo3-*` skill. After `install --agent=claude`, it
  listed all of them, `typo3-core-patch-review` included.
- `feedback/2026-10-07-074922` is the review that session ran. It read the
  change with curl, although the index line of `D-ANS-175` was in its
  instructions. The instructions told it to activate a `typo3-*` skill that was
  not there.
- `D-FBK-056`: a core patch review in a checkout with the skills read the change
  through `typo3_gerrit_lookup` and reported six answers that saved it work.
- The server could not tell. `Installer::absent()` reads the record, and the
  record held `generic`. `ABSENT` and `NOTICE` were both silent.
- The `clientInfo.name` values are measured, not guessed. `claude-code` stands
  in `scenarios/runs/` and in `feedback/archive/2026-07-30-174423`.
  `antigravity-client` stands in `feedback/archive/2026-10-06-131817`.

## Decided

- Step 1b of the ladder: the shape is absent. The fact is in the project, and
  the one place that names the client is the initialize request.
- `Sdk\InitializeHandler` answers the handshake. It hands the request to the
  SDK's own handler with the instructions for that client. A notice the start
  found, absent or stale, wins.
- `Installer::unread()` maps the client to its `--agent=` value. It compares
  that client's skills directory with the directories of the recorded clients.
  Only the two measured names map. An unknown client gets no notice rather than
  a guess.
- `UNREAD` stays within the budget that `NOTICE` and `ABSENT` share.
  `ScopeTest::theInstructionsFitWhatAClientKeeps` measures the longest form.
- The installer's line for the setup without a client now says that Claude Code
  reads `.claude/skills` only.
- Not decided: whether `install` without `--agent` also writes `.claude/skills`.
  That changes `R-DIS-020`, and the notice reaches the same session first.
- `D-ANS-162` stands. The answers stay one shape for every client. Only the
  notice in front of the instructions reads `clientInfo`.

## Assumed

- That a client keeps its `clientInfo.name` across releases.
- That a session acts on a notice at the top of the instructions. `ABSENT` rests
  on the same assumption, and nothing has measured it.

## Wrong if

- A session gets the notice and neither it nor the user runs the command.
- A client changes its name, and a project that lacks its skills is silent
  again.

## Since then

Tried on 2026-10-07 in the checkout behind the feedback, with the record at
`generic` again. The notice reached the session, and the transcript holds it.
The user saw nothing at start, because Claude Code shows its user neither the
instructions nor stderr. The session passed it on only when the user asked. So
`UNREAD` now asks the session to tell the user, in the same length. The second
**Assumed** is now the open question: whether a session says it unasked.
