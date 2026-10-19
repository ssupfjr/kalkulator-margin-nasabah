<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        Instrument::updateOrCreate(
            [
                'symbol' => 'XAUUSD',
            ],
            [
                'name' => 'Gold / US Dollar',
                'category' => 'Commodity',
                'contract_size' => 100.00,
                'default_leverage' => 100,
                'margin_call_level' => 70.00,
                'stop_out_level' => 30.00,
                'price_decimals' => 2,
                'is_active' => true,
            ]
        );
    }
}