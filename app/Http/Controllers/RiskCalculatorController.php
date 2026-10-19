<?php

namespace App\Http\Controllers;

use App\Services\RiskCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskCalculatorController extends Controller
{
    public function index(): View
    {
        return view('calculator.index');
    }

    public function calculate(
        Request $request,
        RiskCalculatorService $riskCalculator
    ): View {
        $data = $request->validate([
            'equity' => ['required', 'numeric', 'gte:0'],
            'position_type' => ['required', 'in:DAYTRADE,OVERNIGHT,PAIR'],
            'direction' => ['required', 'in:BUY,SELL'],
            'lot_open' => ['required', 'numeric', 'gt:0', 'max:100'],
            'open_price' => ['required', 'numeric', 'gt:0'],
            'current_price' => ['required', 'numeric', 'gt:0'],
        ]);

        $result = $riskCalculator->calculate($data);

        return view('calculator.index', [
            'formData' => $data,
            'result' => $result,
        ]);
    }
}