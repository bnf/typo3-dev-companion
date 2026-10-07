<?php

declare(strict_types=1);

namespace TYPO3\DevCompanion\Server;

/**
 * The install command a session's user still owes, said in front of every
 * answer until the process ends.
 *
 * The instructions carry it once, in a block the session reads as routing for
 * its own work. A session reads a tool answer when it acts, so the answer is
 * where the relay to the user is likeliest (`D-DIS-033`).
 */
final class MissingSkills
{
    /** The command, or '' where nothing is missing. */
    private static string $command = '';

    public static function owe(string $command): void
    {
        self::$command = $command;
    }

    public static function forget(): void
    {
        self::$command = '';
    }

    public static function notice(): string
    {
        if (self::$command === '') {
            return '';
        }

        return 'Setup incomplete. Tell the user before you go on: the typo3-* task skills are not installed for '
            . 'this client, so its skill listing has none of the workflows for reviews, patches, tests and commits. '
            . 'The user can run ' . self::$command . ' in the project root. It writes the skills, the MCP entry '
            . 'and a record in .typo3-dev-companion/. Do not run it without their consent.';
    }
}
