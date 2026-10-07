<?php

declare(strict_types=1);

namespace TYPO3\DevCompanion\Sdk;

use Mcp\Schema\Implementation;
use Mcp\Schema\JsonRpc\Request;
use Mcp\Schema\JsonRpc\Response;
use Mcp\Schema\Request\InitializeRequest;
use Mcp\Schema\Result\InitializeResult;
use Mcp\Schema\ServerCapabilities;
use Mcp\Server\Configuration;
use Mcp\Server\Handler\Request\InitializeHandler as SdkInitializeHandler;
use Mcp\Server\Handler\Request\RequestHandlerInterface;
use Mcp\Server\Session\SessionInterface;
use TYPO3\DevCompanion\Knowledge\Coverage;
use TYPO3\DevCompanion\Server\Installer;

/**
 * The SDK's own handshake, with instructions that know which client asked.
 *
 * The instructions are fixed before a client connects, and only the
 * initialize request names the client. A client whose skills directory
 * `install` never wrote into gets the command that writes it (`D-DIS-033`).
 * Everything else is the SDK's handler, which this hands the request to.
 *
 * @implements RequestHandlerInterface<InitializeResult>
 */
final class InitializeHandler implements RequestHandlerInterface
{
    /**
     * @param string $notice what the start found wrong with the project, which
     *     wins over what the client adds
     */
    public function __construct(
        private readonly Implementation $serverInfo,
        private readonly ServerCapabilities $capabilities,
        private readonly string $notice,
        private readonly string $project,
    ) {}

    public function supports(Request $request): bool
    {
        return $request instanceof InitializeRequest;
    }

    /** @return Response<InitializeResult> */
    public function handle(Request $request, SessionInterface $session): Response
    {
        \assert($request instanceof InitializeRequest);
        $notice = $this->notice;
        if ($notice === '') {
            $notice = Installer::unread($this->project, $request->clientInfo->name);
            // The long form beside it, as the start writes it for a project
            // without skills, for whoever reads the client's server log.
            if ($notice !== '') {
                fwrite(STDERR, 'typo3-dev-companion: ' . $request->clientInfo->name . ' reads none of the skills '
                    . 'published in ' . $this->project . ', so no typo3-* skill is in its listing. Run '
                    . 'typo3-dev-companion install --agent=' . Installer::agentOf($request->clientInfo->name)
                    . ' there.' . "\n");
            }
        }

        return (new SdkInitializeHandler(new Configuration(
            $this->serverInfo,
            $this->capabilities,
            instructions: Coverage::instructions($notice),
        )))->handle($request, $session);
    }
}
