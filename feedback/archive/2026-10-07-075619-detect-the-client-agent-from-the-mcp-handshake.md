---
date: 2026-10-07T07:56:19+00:00
category: idea
status: closed
closed: 2026-10-07
model: claude-opus-5-5
tool: typo3_project_describe, typo3_server_scope, typo3-dev-companion, install
directory: <typo3-core-checkout>
---

# detect the client agent from the MCP handshake and warn when its skills are not installed

## Observation

Task: review Gerrit change <change> in Claude Code. Follow-up to feedback 2026-10-07-074922 (review link bypasses typo3_gerrit_lookup).

Root cause, found by the user:
- The user installed the server with "typo3-dev-companion install".
- The user did not pass "--agent=claude".
- So the MCP server was installed, but the typo3-* skills for Claude Code were not.
- The user did not notice this. Nothing told the user or the agent.

Effect:
- The server instructions say "Activate the typo3-* skill in your listing that covers the task".
- The session listed no typo3-* skill. The agent had no typo3-core-patch-review skill.
- The agent read the change with curl and git fetch, not with typo3_gerrit_lookup.
- After a reinstall with --agent=claude, the session listed all typo3-* skills, including typo3-core-patch-review.

The server can know the client. The MCP initialize request carries clientInfo (name and version), for example "claude-code". An HTTP transport also has a User-Agent header. The server does not use this today to check the install.

## Query

Installation: "typo3-dev-companion install" without --agent. Correct call: "typo3-dev-companion install --agent=claude". Then: "review https://review.typo3.org/c/Packages/TYPO3.CMS/+/<change>" in Claude Code.

## Suggestion

1. Read the client identity at connect time: clientInfo.name from the MCP initialize request, and the User-Agent header on HTTP transports.
2. Map the client to its agent target of the installer (for example "claude-code" to --agent=claude).
3. Check whether the skills or plugins for that agent are installed, for example the skill directory for Claude Code.
4. If they are missing, say so in the server instructions at the top, and in typo3_project_describe and typo3_server_scope. Give the exact fix command: "typo3-dev-companion install --agent=claude".
5. Tell the agent to tell the user once. The user cannot see the server instructions.
6. In the installer: when --agent is missing, detect installed agents (for example ~/.claude) and ask, or print a clear warning that no skills were installed.
7. Keep a safe fallback for unknown clients: state that skills are not verified, and keep the routing rules in the instructions themselves.
