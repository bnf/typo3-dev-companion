---
date: 2026-10-07T07:49:22+00:00
category: bug
status: closed
closed: 2026-10-07
model: claude-opus-5-5
tool: typo3_gerrit_lookup, typo3_project_describe, typo3-core-patch-review
directory: <typo3-core-checkout>
---

# a review link still bypasses typo3_gerrit_lookup after fix 6ba0bad8; no typo3-* review skill was ...

## Observation

Task: review Gerrit change <change> in a TYPO3 core checkout (main).
This is a recurrence of the closed feedback "a review.typo3.org URL in the prompt does not route the agent to typo3_gerrit_lookup" (closed by 6ba0bad8 on 2026-10-07).

What happened:
- The agent did not call typo3_project_describe first.
- The agent did not call typo3_gerrit_lookup.
- The agent read the change with curl on the Gerrit REST API and with git fetch.
- The user asked afterwards why the MCP tool was not used.
- A later typo3_gerrit_lookup call gave the same data, plus mergeable, the Forge issue title and the release line states.
- The review result did not change. The workflow rule was broken.

Context that can explain it:
- The server instructions say: "a change on review.typo3.org: typo3_gerrit_lookup". That line was present in this session.
- The server instructions also say: "Activate the typo3-* skill in your listing that covers the task". This session listed no typo3-* skill. The user has the MCP server, but not the skills. So the routing rested on one line in a long instruction block.
- All server tools were deferred tools. The agent had to load each schema with ToolSearch first. Direct Bash with curl had no such step. The cheaper path won.
- The project AGENTS.md documents the Forge JSON API with curl, but names no MCP tool. This makes curl look like the documented path.

I cannot verify whether the running server already contained 6ba0bad8.

## Query

User prompt: "review https://review.typo3.org/c/Packages/TYPO3.CMS/+/<change>"

## Suggestion

1. Do not depend on skills for routing. A session without the typo3-* skills must get the same routing from the server instructions alone.
2. Put the routing rule at the top of the instructions, as a hard rule: "For any review.typo3.org or forge.typo3.org URL, call typo3_gerrit_lookup or typo3_forge_lookup before curl, git fetch or WebFetch."
3. Repeat the rule in the tool description of typo3_gerrit_lookup: "Use this instead of the Gerrit REST API."
4. Tell the agent that a deferred schema load is expected and cheap, so it does not fall back to curl.
5. Consider a short hint for project AGENTS.md files: name the MCP tools next to the curl recipes for Forge and Gerrit.
6. Let typo3_project_describe or the instructions state the server build or commit. Then a feedback can say whether a fix was live.
