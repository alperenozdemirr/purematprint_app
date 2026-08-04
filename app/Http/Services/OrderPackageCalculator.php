<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Order;

class OrderPackageCalculator
{
    public function __construct(protected ShipinkConfigService $config)
    {
    }

    /**
     * Sipariş kalemlerinden tek paket ölçüsü hesaplar.
     *
     * Formül:
     * - Her ürünün ölçüleri L≥W≥H olacak şekilde döndürülür
     * - Paket boyu = max(L), paket eni = max(W), paket yüksekliği = Σ(H × adet) (üst üste istif)
     * - Ağırlık = Σ(ağırlık × adet)
     * - Eksik ölçü/ağırlık için Shipink varsayılan paketi kullanılır
     * - Desi = (L × W × H) / 3000
     *
     * @return array{
     *     weight: int,
     *     length: int,
     *     width: int,
     *     height: int,
     *     weight_unit: string,
     *     dimension_unit: string,
     *     desi: float,
     *     source: string,
     *     items: list<array<string, mixed>>,
     *     warnings: list<string>
     * }
     */
    public function calculate(Order $order): array
    {
        $order->loadMissing(['details.product']);
        $defaults = $this->config->defaultPackage();

        $items = [];
        $warnings = [];
        $maxLength = 0;
        $maxWidth = 0;
        $sumHeight = 0;
        $sumWeight = 0.0;
        $hasDimension = false;
        $hasWeight = false;

        foreach ($order->details as $detail) {
            $product = $detail->product;
            $qty = max(1, (int) $detail->quantity);

            $length = $product?->shipping_length !== null ? (int) $product->shipping_length : null;
            $width = $product?->shipping_width !== null ? (int) $product->shipping_width : null;
            $height = $product?->shipping_height !== null ? (int) $product->shipping_height : null;
            $weight = $product?->shipping_weight !== null ? (float) $product->shipping_weight : null;

            $normalized = null;

            if ($length !== null && $width !== null && $height !== null && $length > 0 && $width > 0 && $height > 0) {
                $sides = [$length, $width, $height];
                rsort($sides);
                $normalized = [
                    'length' => $sides[0],
                    'width' => $sides[1],
                    'height' => $sides[2],
                ];
                $hasDimension = true;
                $maxLength = max($maxLength, $normalized['length']);
                $maxWidth = max($maxWidth, $normalized['width']);
                $sumHeight += $normalized['height'] * $qty;
            } else {
                $warnings[] = ($product?->title ?? 'Ürün').' için kargo ölçüleri eksik.';
            }

            if ($weight !== null && $weight > 0) {
                $hasWeight = true;
                $sumWeight += $weight * $qty;
            } else {
                $warnings[] = ($product?->title ?? 'Ürün').' için kargo ağırlığı eksik.';
            }

            $items[] = [
                'product_id' => $product?->id,
                'title' => $product?->title ?? 'Ürün',
                'quantity' => $qty,
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'normalized' => $normalized,
            ];
        }

        $packageLength = $hasDimension ? max(1, $maxLength) : (int) ($defaults['length'] ?? 20);
        $packageWidth = $hasDimension ? max(1, $maxWidth) : (int) ($defaults['width'] ?? 15);
        $packageHeight = $hasDimension ? max(1, $sumHeight) : (int) ($defaults['height'] ?? 10);
        $packageWeight = $hasWeight
            ? max(1, (int) ceil($sumWeight))
            : (int) ($defaults['weight'] ?? 1);

        $source = match (true) {
            $hasDimension && $hasWeight => 'calculated',
            $hasDimension || $hasWeight => 'partial',
            default => 'default',
        };

        $desi = round(($packageLength * $packageWidth * $packageHeight) / 3000, 2);

        return [
            'weight' => $packageWeight,
            'length' => $packageLength,
            'width' => $packageWidth,
            'height' => $packageHeight,
            'weight_unit' => 'kg',
            'dimension_unit' => 'cm',
            'desi' => $desi,
            'source' => $source,
            'items' => $items,
            'warnings' => array_values(array_unique($warnings)),
        ];
    }
}
