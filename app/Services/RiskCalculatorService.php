<?php

namespace App\Services;

use InvalidArgumentException;

class RiskCalculatorService
{
    private const CONTRACT_SIZE = 100.00;

    private const DAYTRADE_MARGIN_PER_LOT = 1000.00;
    private const OVERNIGHT_MARGIN_PER_LOT = 3000.00;
    private const PAIR_MARGIN_PER_LOT = 300.00;

    private const MARGIN_CALL_LEVEL = 70.00;
    private const AUTO_LIQUIDATION_LEVEL = 30.00;

    public function calculate(array $data): array
    {
        $equity = (float) $data['equity'];
        $openPrice = (float) $data['open_price'];
        $currentPrice = (float) $data['current_price'];
        $lotOpen = (float) $data['lot_open'];
        $direction = strtoupper((string) $data['direction']);
        $positionType = strtoupper((string) $data['position_type']);

        if ($equity < 0) {
            throw new InvalidArgumentException(
                'Equity tidak boleh bernilai negatif.'
            );
        }

        if ($openPrice <= 0 || $currentPrice <= 0) {
            throw new InvalidArgumentException(
                'Harga open dan harga saat ini harus lebih besar dari nol.'
            );
        }

        if ($lotOpen <= 0) {
            throw new InvalidArgumentException(
                'Lot Open harus lebih besar dari nol.'
            );
        }

        if (! in_array($direction, ['BUY', 'SELL'], true)) {
            throw new InvalidArgumentException(
                'Arah posisi harus BUY atau SELL.'
            );
        }

        if (! in_array($positionType, ['DAYTRADE', 'OVERNIGHT', 'PAIR'], true)) {
            throw new InvalidArgumentException(
                'Jenis posisi harus DAYTRADE, OVERNIGHT, atau PAIR.'
            );
        }

        $contractSize = self::CONTRACT_SIZE;
        $priceMultiplier = $lotOpen * $contractSize;

        /*
         | Floating P/L:
         |
         | BUY  = (Harga Saat Ini - Harga Open) × Lot × Contract Size
         | SELL = (Harga Open - Harga Saat Ini) × Lot × Contract Size
         */
        $floatingPl = $direction === 'BUY'
            ? ($currentPrice - $openPrice) * $priceMultiplier
            : ($openPrice - $currentPrice) * $priceMultiplier;

        /*
         | Margin Required sesuai jenis posisi.
         */
        $marginRequiredPerLot = $this->marginRequiredPerLot($positionType);
        $marginRequired = $lotOpen * $marginRequiredPerLot;

        /*
         | Equity Ratio = Equity / Margin Required × 100%
         */
        $equityRatio = ($equity / $marginRequired) * 100;

        /*
         | Call Margin dan Auto Liquidation menggunakan
         | Equity sebagai nilai input pengguna.
         |
         | BUY:
         | Call Margin = Current Price - ((Equity - (70% × MR)) / (Lot × CS))
         | Liquidation = Current Price - ((Equity - (30% × MR)) / (Lot × CS))
         |
         | SELL:
         | Call Margin = Current Price + ((Equity - (70% × MR)) / (Lot × CS))
         | Liquidation = Current Price + ((Equity - (30% × MR)) / (Lot × CS))
         */
        $callMarginTargetEquity = (
            self::MARGIN_CALL_LEVEL / 100
        ) * $marginRequired;

        $autoLiquidationTargetEquity = (
            self::AUTO_LIQUIDATION_LEVEL / 100
        ) * $marginRequired;

        $callMarginDistance = (
            $equity - $callMarginTargetEquity
        ) / $priceMultiplier;

        $autoLiquidationDistance = (
            $equity - $autoLiquidationTargetEquity
        ) / $priceMultiplier;

        $callMarginPrice = $direction === 'BUY'
            ? $currentPrice - $callMarginDistance
            : $currentPrice + $callMarginDistance;

        $autoLiquidationPrice = $direction === 'BUY'
            ? $currentPrice - $autoLiquidationDistance
            : $currentPrice + $autoLiquidationDistance;

        $riskStatus = match (true) {
            $equityRatio < self::AUTO_LIQUIDATION_LEVEL => 'LIQUIDATION',
            $equityRatio < self::MARGIN_CALL_LEVEL => 'MARGIN CALL',
            $equityRatio < 100 => 'WARNING',
            default => 'SAFE',
        };

        return [
            'equity' => round($equity, 2),
            'open_price' => round($openPrice, 2),
            'current_price' => round($currentPrice, 2),
            'lot_open' => round($lotOpen, 2),
            'direction' => $direction,
            'position_type' => $positionType,

            'contract_size' => $contractSize,
            'floating_pl' => round($floatingPl, 2),

            'margin_required_per_lot' => round(
                $marginRequiredPerLot,
                2
            ),
            'margin_required' => round($marginRequired, 2),

            'equity_ratio' => round($equityRatio, 2),

            'margin_call_level' => self::MARGIN_CALL_LEVEL,
            'auto_liquidation_level' => self::AUTO_LIQUIDATION_LEVEL,

            'call_margin_price' => round($callMarginPrice, 2),
            'auto_liquidation_price' => round(
                $autoLiquidationPrice,
                2
            ),

            'risk_status' => $riskStatus,
        ];
    }

    private function marginRequiredPerLot(string $positionType): float
    {
        return match ($positionType) {
            'DAYTRADE' => self::DAYTRADE_MARGIN_PER_LOT,
            'OVERNIGHT' => self::OVERNIGHT_MARGIN_PER_LOT,
            'PAIR' => self::PAIR_MARGIN_PER_LOT,
        };
    }
}