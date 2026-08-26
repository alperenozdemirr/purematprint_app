<?php

declare(strict_types=1);

namespace App\Http\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ShipinkApiService
{
    public function __construct(protected ShipinkConfigService $config)
    {
    }

    public function isConfigured(): bool
    {
        return $this->config->isConfigured();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listOrders(int $page = 1, int $limit = 100): array
    {
        $limit = max(1, min(100, $limit));
        $page = max(1, $page);

        $data = $this->unwrapData(
            $this->client()->get('/orders', [
                'page' => $page,
                'limit' => $limit,
            ]),
            'Shipink sipariş listesi alınamadı.',
            'listOrders',
        );

        return $this->normalizeList($data);
    }

    /**
     * @return array<string, mixed>
     */
    public function createOrder(array $payload): array
    {
        $response = $this->client()->post('/orders', $payload);

        return $this->unwrapData(
            $response,
            'Shipink siparişi oluşturulamadı.',
            'createOrder',
            ['request_payload' => $payload],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function createShipment(array $payload, array $logContext = []): array
    {
        $response = $this->client()
            ->withHeaders(['X-Language' => 'tr'])
            ->post('/shipments', $payload);

        return $this->unwrapData(
            $response,
            'Shipink kargo gönderisi oluşturulamadı.',
            'createShipment',
            array_merge(['request_payload' => $payload], $logContext),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getShipment(string $shipmentId): array
    {
        return $this->unwrapData(
            $this->client()->get('/shipments/'.$shipmentId),
            'Shipink kargo bilgisi alınamadı.',
            'getShipment',
            ['shipment_id' => $shipmentId],
        );
    }

    public function deleteShipment(string $shipmentId): void
    {
        $response = $this->client()->delete('/shipments/'.$shipmentId);

        if (! $response->successful()) {
            $this->logApiFailure('deleteShipment', $response, ['shipment_id' => $shipmentId]);

            throw new RuntimeException($this->extractErrorMessage(
                $response,
                'Shipink kargo gönderisi iptal edilemedi.'
            ));
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getOrder(string $orderId): array
    {
        return $this->unwrapData(
            $this->client()->get('/orders/'.$orderId),
            'Shipink sipariş bilgisi alınamadı.',
            'getOrder',
            ['order_id' => $orderId],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function updateOrder(string $orderId, array $payload): array
    {
        $response = $this->client()->put('/orders/'.$orderId, $payload);

        return $this->unwrapData(
            $response,
            'Shipink siparişi güncellenemedi.',
            'updateOrder',
            [
                'order_id' => $orderId,
                'request_payload' => $payload,
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getWarehouse(string $warehouseId): array
    {
        return $this->unwrapData(
            $this->client()->get('/warehouses/'.$warehouseId),
            'Shipink depo bilgisi alınamadı.',
            'getWarehouse',
            ['warehouse_id' => $warehouseId],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function updateWarehouse(string $warehouseId, array $payload): array
    {
        $response = $this->client()->put('/warehouses/'.$warehouseId, $payload);

        return $this->unwrapData(
            $response,
            'Shipink depo güncellenemedi.',
            'updateWarehouse',
            [
                'warehouse_id' => $warehouseId,
                'request_payload' => $payload,
            ],
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listCarrierAccounts(): array
    {
        $data = $this->unwrapData(
            $this->client()->get('/carrier-accounts'),
            'Shipink kargo hesapları alınamadı.',
            'listCarrierAccounts',
        );

        return $this->normalizeList($data);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listCards(): array
    {
        $data = $this->unwrapData(
            $this->client()->get('/cards'),
            'Shipink ödeme kartları alınamadı.',
            'listCards',
        );

        return $this->normalizeList($data);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listWarehouses(): array
    {
        $data = $this->unwrapData(
            $this->client()->get('/warehouses'),
            'Shipink depoları alınamadı.',
            'listWarehouses',
        );

        return $this->normalizeList($data);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl($this->config->baseUrl())
            ->acceptJson()
            ->asJson()
            ->withToken($this->accessToken());
    }

    private function accessToken(): string
    {
        $cached = Cache::get('shipink_access_token');

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $response = Http::baseUrl($this->config->baseUrl())
            ->acceptJson()
            ->asJson()
            ->post('/token', [
                'username' => $this->config->username(),
                'password' => $this->config->password(),
            ]);

        if (! $response->successful()) {
            $this->logApiFailure('accessToken', $response, [
                'username' => $this->config->username(),
                'base_url' => $this->config->baseUrl(),
            ]);

            throw new RuntimeException($this->extractErrorMessage($response, 'Shipink kimlik doğrulaması başarısız.'));
        }

        $token = (string) ($response->json('access_token') ?? '');

        if ($token === '') {
            throw new RuntimeException('Shipink access token alınamadı.');
        }

        $ttlSeconds = max(300, (int) ($response->json('expires_in') ?? 3600) - 300);
        Cache::put('shipink_access_token', $token, now()->addSeconds($ttlSeconds));

        return $token;
    }

    /**
     * @return array<string, mixed>
     */
    private function unwrapData(
        Response $response,
        string $fallbackMessage,
        string $operation = 'shipink_api',
        array $logContext = [],
    ): array {
        if (! $response->successful()) {
            $this->logApiFailure($operation, $response, $logContext);

            throw new RuntimeException($this->extractErrorMessage($response, $fallbackMessage));
        }

        $data = $response->json('data');

        if (is_array($data)) {
            return $data;
        }

        $body = $response->json();

        return is_array($body) ? $body : [];
    }

    /**
     * @param  array<string, mixed>|list<array<string, mixed>>  $data
     * @return list<array<string, mixed>>
     */
    private function normalizeList(array $data): array
    {
        if ($data === []) {
            return [];
        }

        if (array_is_list($data)) {
            return $data;
        }

        if (isset($data['id'])) {
            return [$data];
        }

        foreach (['items', 'orders', 'results', 'records'] as $key) {
            if (isset($data[$key]) && is_array($data[$key]) && array_is_list($data[$key])) {
                return $data[$key];
            }
        }

        return [];
    }

    private function extractErrorMessage(Response $response, string $fallbackMessage): string
    {
        $message = (string) (
            $response->json('meta.message')
            ?? $response->json('message')
            ?? $response->json('error')
            ?? $fallbackMessage
        );

        $errors = $response->json('meta.errors') ?? $response->json('errors');
        $details = $response->json('meta.details');

        if (is_array($details) && $details !== []) {
            $detailMessages = collect($details)
                ->map(function ($detail) {
                    if (! is_array($detail)) {
                        return (string) $detail;
                    }

                    $field = (string) ($detail['field'] ?? '');
                    $error = (string) ($detail['error'] ?? $detail['message'] ?? '');

                    return $field !== '' ? "{$field}: {$error}" : $error;
                })
                ->filter()
                ->implode(' ');

            if ($detailMessages !== '') {
                $message = $this->humanizeShipinkError($message, $detailMessages);

                return "{$message} ({$detailMessages})";
            }
        }

        if (! is_array($errors) || $errors === []) {
            return $message;
        }

        $details = collect($errors)
            ->flatMap(function ($value, $key) {
                if (is_array($value)) {
                    return collect($value)->map(fn ($item) => is_string($key) ? "{$key}: {$item}" : (string) $item);
                }

                return [is_string($key) ? "{$key}: {$value}" : (string) $value];
            })
            ->filter()
            ->implode(' ');

        return $details !== '' ? "{$message} ({$details})" : $message;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function logApiFailure(string $operation, Response $response, array $context = []): void
    {
        $jsonBody = $response->json();
        $rawBody = $response->body();

        if (isset($context['request_payload']) && is_array($context['request_payload'])) {
            $context['request_payload'] = $this->sanitizeForLog($context['request_payload']);
        }

        Log::error('Shipink API ham hata yanıtı', array_merge([
            'operation' => $operation,
            'http_status' => $response->status(),
            'response_json' => is_array($jsonBody) ? $jsonBody : null,
            'response_raw' => $this->truncateForLog($rawBody),
            'parsed_message' => $this->extractErrorMessage($response, ''),
            'response_headers' => $response->headers(),
        ], $context));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function sanitizeForLog(array $payload): array
    {
        $sanitized = [];

        foreach ($payload as $key => $value) {
            if ($this->isSensitiveLogKey((string) $key)) {
                $sanitized[$key] = '[redacted]';
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeForLog($value);
                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    private function isSensitiveLogKey(string $key): bool
    {
        return (bool) preg_match('/password|secret|token|authorization|api[_-]?key|card/i', $key);
    }

    private function truncateForLog(string $value, int $limit = 12000): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return mb_substr($value, 0, $limit).'…[truncated]';
    }

    private function humanizeShipinkError(string $message, string $details): string
    {
        if (
            str_contains($message, 'Gönderici adresi geçersiz')
            && str_contains($details, 'Sevk adresi bulunamadı')
        ) {
            return 'Aras Kargo sevk adresi bulunamadı. Shipink panelinden kendi Aras sözleşmenizi eklemeniz veya Aras\'ta sevk adresinizi tanımlamanız gerekir.';
        }

        if (preg_match('/record\s+(?:already|alredy)\s+exists/i', $message) === 1) {
            return 'Shipink\'te bu sipariş için kayıt zaten mevcut. Sistem mevcut kaydı eşleştirmeyi deneyecek; devam etmezse Shipink panelinden kontrol edin.';
        }

        return $message;
    }
}
