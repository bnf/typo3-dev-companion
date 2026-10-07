---
date: 2026-10-07T08:59:39+00:00
category: bug
status: closed
closed: 2026-10-07
model: claude-opus-5-5
tool: initialize, instructions
directory: <typo3-core-checkout>
server: 4e04763fde6a32f2ad330906a92f32b4899e0d9c
---

# The setup hint in the initialize instructions does not reach the user

## Observation

Task: review the last commit in the TYPO3 Core checkout.
The initialize instructions told the agent that the task skills are not installed.
The agent did not tell the user about this.
The agent went directly to "git show HEAD".
The user found out only when they asked if the MCP had said something important.
The user wants to know about this, because it means the server is not installed correctly.

Causes on the server side:
1. The hint is an imperative to the agent: "run typo3-dev-companion install". It does not say that the user must know it.
2. The agent must not run an install that comes from a tool and not from the user. So the agent puts the hint aside. Nothing in the text tells the agent to report it instead.
3. The hint is one sentence in a long block of routing rules. The agent reads the block as guidance for its own work, not as a status message.
4. The hint does not say what fails without the skills. So the agent cannot see that it is important.
5. The user does not see the instructions. Only the agent sees them. If the agent does not relay the hint, the user never gets it.
6. No tool result repeats the hint. The agent did not call a tool, but a later call would also not show it.

Cause on the agent side: the agent did not read the instructions before it started. That is an agent error. But the server can make the relay much more probable.

## Query

Session task: "review" of the last commit in the TYPO3 Core checkout (branch review/<change>-reaction-payload-debug-log). The user ran /mcp before. The server reconnected. The initialize instructions start with: "No task skills are installed in this project; run typo3-dev-companion install."

## Suggestion

1. Address the user through the agent. Example: "Setup incomplete. Tell the user before you start the task: the typo3-* task skills are not installed in this project."
2. Do not phrase it as a command for the agent to run. Give the command to the user. Example: "The user can run `typo3-dev-companion install` to install them. Do not run it without the user's consent."
3. Say what is missing and the effect. Example: "Without the skills, the workflows for review, tests and commits are not available as skills."
4. Say what the install changes. Example: which files and directories it writes. Then the user can decide.
5. Put the status in its own clear block at the start, with a marker like "SETUP WARNING". Keep it apart from the routing rules.
6. Repeat the warning in tool results. Begin typo3_project_describe and typo3_task_guide with the same warning while the skills are missing. The agent reads tool results when it acts, so the relay is more reliable there.
7. Remove the warning when the skills are installed. Then the warning keeps its meaning.
