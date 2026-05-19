<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Mcp\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Caydeesoft\SaasKit\Mcp\Contracts\McpServerInterface;
use Symfony\Component\HttpFoundation\Response;

final class McpController
{
    public function __construct(
        private readonly McpServerInterface $server,
    ) {
    }

    public function __invoke(Request $request): JsonResponse|Response
    {
        if ($this->hasInvalidOrigin($request)) {
            return response()->json($this->error(null, -32000, 'Invalid Origin header'), Response::HTTP_FORBIDDEN);
        }

        if ($this->hasUnsupportedProtocolVersion($request)) {
            return response()->json($this->error(null, -32000, 'Unsupported MCP protocol version'), Response::HTTP_BAD_REQUEST);
        }

        if (! $request->isMethod('post')) {
            return response('', Response::HTTP_METHOD_NOT_ALLOWED)
                ->header('Allow', 'GET, POST, DELETE');
        }

        try {
            /** @var array<string, mixed> $message */
            $message = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return response()->json($this->error(null, -32700, 'Parse error'), Response::HTTP_BAD_REQUEST);
        }

        if (! is_array($message)) {
            return response()->json($this->error(null, -32600, 'Invalid Request'), Response::HTTP_BAD_REQUEST);
        }

        $response = $this->server->handle($message);

        if ($response === null) {
            return response('', Response::HTTP_ACCEPTED);
        }

        return response()->json($response);
    }

    private function hasInvalidOrigin(Request $request): bool
    {
        $origin = $request->headers->get('Origin');

        if ($origin === null || $origin === '') {
            return false;
        }

        $allowedOrigins = (array) config('saas-kit.mcp.allowed_origins', []);

        return ! in_array($origin, $allowedOrigins, true);
    }

    private function hasUnsupportedProtocolVersion(Request $request): bool
    {
        $protocolVersion = $request->headers->get('MCP-Protocol-Version');

        if ($protocolVersion === null || $protocolVersion === '') {
            return false;
        }

        $supportedVersions = (array) config('saas-kit.mcp.supported_protocol_versions', ['2025-11-25', '2025-06-18', '2025-03-26']);

        return ! in_array($protocolVersion, $supportedVersions, true);
    }

    /**
     * @return array<string, mixed>
     */
    private function error(mixed $id, int $code, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];
    }
}
