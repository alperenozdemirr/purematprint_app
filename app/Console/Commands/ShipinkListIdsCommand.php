<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Services\ShipinkApiService;
use App\Http\Services\ShipinkConfigService;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ShipinkListIdsCommand extends Command
{
    protected $signature = 'shipink:list-ids';

    protected $description = 'Shipink depo, kargo hesabı ve kart bilgilerini listeler';

    public function handle(ShipinkApiService $api, ShipinkConfigService $config): int
    {
        if (! $config->hasCredentials()) {
            $this->error('SHIPINK_USERNAME ve SHIPINK_PASSWORD .env dosyasında tanımlı olmalı.');

            return self::FAILURE;
        }

        $this->line('API: '.$config->baseUrl());

        try {
            Cache::forget('shipink_access_token');

            $this->info('Depolar:');
            foreach ($api->listWarehouses() as $warehouse) {
                $this->line('  - '.($warehouse['name'] ?? 'Depo').': '.($warehouse['id'] ?? ''));
            }

            $this->newLine();
            $this->info('Kargo hesapları:');
            foreach ($api->listCarrierAccounts() as $account) {
                $carrier = (string) ($account['carrier_id'] ?? 'unknown');
                $provider = (string) ($account['provider'] ?? 'unknown');
                $this->line("  - {$carrier} [{$provider}]: ".($account['id'] ?? ''));
            }

            $this->newLine();
            $this->info('Ödeme kartları:');
            $cards = $api->listCards();
            if ($cards === []) {
                $this->warn('  Kart bulunamadı.');
            }
            foreach ($cards as $card) {
                $this->line('  - '.($card['name'] ?? 'Kart').': '.($card['id'] ?? ''));
            }

            $setting = Setting::current();
            $this->newLine();
            $this->comment('Veritabanı (admin panel):');
            $this->line('  Depo: '.($setting->shipink_warehouse_name ?: $setting->shipink_warehouse_id ?: '(boş)'));
            $this->line('  Kargo: '.($setting->shipink_carrier_account_label ?: $setting->shipink_carrier_account_id ?: '(boş)'));
            $this->line('  Kart: '.($setting->shipink_card_label ?: $setting->shipink_card_id ?: '(boş)'));

            if (! $config->isConfigured()) {
                $this->newLine();
                $this->warn('Admin panelden depo ve kargo hesabı seçin: /admin/shipink-settings');
            }

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
