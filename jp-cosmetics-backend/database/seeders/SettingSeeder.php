<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'privacy_policy',
                'description' => 'Our Privacy Policy content will be added here. This section explains how we collect, use, and protect your personal information.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cookie_policy',
                'description' => 'Our Cookie Policy content will be added here. This section explains how we use cookies and similar technologies on our website.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'terms_conditions',
                'description' => 'Our Terms and Conditions content will be added here. This section outlines the rules and regulations for using our website and services.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'returns_exchanges',
                'description' => 'Our Returns and Exchanges Policy content will be added here. This section explains our return and exchange procedures for products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'shipping_delivery',
                'description' => 'Our Shipping and Delivery Policy content will be added here. This section provides information about our shipping methods, delivery times, and costs.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], // Check if key exists
                $setting // Update or create with this data
            );
        }

        $this->command->info('✅ Settings seeded successfully!');
    }
}
