---
date: 2026-10-07T07:01:08+00:00
category: idea
status: closed
closed: 2026-10-07
model: claude-opus-5-5
tool: typo3_gerrit_lookup
directory: <typo3-core-checkout>
---

# a review.typo3.org URL in the prompt does not route the agent to typo3_gerrit_lookup

## Observation

Task: "review https://review.typo3.org/c/Packages/TYPO3.CMS/+/<change>" in the TYPO3 core checkout.
The agent did not call typo3_gerrit_lookup. It used curl against the Gerrit REST API and git fetch. The user asked why. The tool answered correctly on the second try.
Reasons for the miss:
1. The server instructions map tasks to tools in "What to call for what". That list has no line for a Gerrit change or a review URL. The agent did not connect "review <gerrit url>" to this server.
2. The tool is deferred in the client. Only its name was visible, not its description. A name alone did not pull the agent away from a path it already knew.
3. AGENTS.md in the core repo names review.typo3.org and documents curl for Forge. The agent copied that pattern for Gerrit. The repo text competed with the server and won.
4. The agent also skipped typo3_project_describe and typo3_task_guide. The instruction "Start every task with typo3_project_describe" did not hold for a task that reads like a lookup.
What the tool did well: one call returned message, votes, threaded comments with unresolved state, Forge issue, release lines and mergeable=false. The curl path needed three calls and a JSON parser. mergeable=false matched git merge-tree.
What the agent still had to do outside the tool: fetch the ref and read the diff. Find the commit on main that caused the conflict (git log on the touched file). That cause, a new PayloadDecoder from #<issue>, was the main finding of the review.

## Query

Task text: "review https://review.typo3.org/c/Packages/TYPO3.CMS/+/<change>". Working call: typo3_gerrit_lookup change="<change>" messages="people".

## Suggestion

1. Add a line to "What to call for what": "a review.typo3.org link, a Change-Id or a change number, or a request to review a change: typo3_gerrit_lookup". Name the review task in the first sentences of the server instructions.
2. Accept a full review URL as the change argument. Extract the number from it.
3. When mergeable is false, list the commits on the target branch that touched the change's files after its parent commit. This names the cause of the conflict.
4. Make typo3_task_guide name a "review a Gerrit change" workflow. It should start with typo3_gerrit_lookup and then fetch.ref and git diff.
5. Optional: return the diff hunks for small changes, for example below 100 changed lines. Then a review needs no fetch.
