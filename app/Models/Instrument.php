<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'category',
        'contract_size',
        'default_leverage',
        'margin_call_level',
        'stop_out_level',
        'price_decimals',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'contract_size' => 'decimal:2',
            'margin_call_level' => 'decimal:2',
            'stop_out_level' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}