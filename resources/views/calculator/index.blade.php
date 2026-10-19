<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Risk Calculator XUL10</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0b0b0b]">
    @php
        $formData = $formData ?? [
            'equity' => 42864.75,
            'position_type' => 'DAYTRADE',
            'direction' => 'BUY',
            'lot_open' => 1,
            'open_price' => 4308.69,
            'current_price' => 4291.13,
        ];
    @endphp

    <main class="min-h-screen bg-[#0b0b0b] py-8 text-zinc-100 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-9 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">
                        Risk Calculator
                    </h1>

                    <p class="mt-2 text-sm text-zinc-400">
                        Kalkulasi risiko posisi trading XUL10.
                    </p>
                </div>

                <a
                    href="{{ route('calculator.index') }}"
                    class="rounded-lg border border-zinc-700 px-4 py-2 text-sm font-semibold text-zinc-200 transition hover:border-zinc-500 hover:bg-zinc-800"
                >
                    Reset
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-900 bg-red-950/40 p-4 text-red-200">
                    <p class="font-semibold">
                        Mohon periksa kembali input Anda.
                    </p>

                    @foreach ($errors->all() as $error)
                        <p class="mt-1 text-sm">
                            • {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <section class="rounded-2xl border border-zinc-700 bg-[#1b1b1a] p-5 shadow-2xl sm:p-9">
                <h2 class="mb-2 text-base font-bold uppercase tracking-wide text-zinc-300">
                    Detail Posisi
                </h2>

                <p class="mb-8 text-sm text-zinc-500">
                    Margin Daytrade $1,000/lot, Overnight $3,000/lot, dan Pair $300/lot.
                </p>

                <form
                    method="POST"
                    action="{{ route('calculator.calculate') }}"
                    class="grid gap-x-6 gap-y-5 md:grid-cols-2"
                >
                    @csrf

                    <div>
                        <label
                            for="equity"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Equity ($)
                        </label>

                        <input
                            id="equity"
                            type="number"
                            name="equity"
                            min="0"
                            step="0.01"
                            value="{{ old('equity', $formData['equity'] ?? 42864.75) }}"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                    </div>

                    <div>
                        <label
                            for="position_type"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Jenis Posisi
                        </label>

                        <select
                            id="position_type"
                            name="position_type"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                            <option
                                value="DAYTRADE"
                                @selected(old('position_type', $formData['position_type'] ?? 'DAYTRADE') === 'DAYTRADE')
                            >
                                Daytrade — $1,000 / lot
                            </option>

                            <option
                                value="OVERNIGHT"
                                @selected(old('position_type', $formData['position_type'] ?? 'DAYTRADE') === 'OVERNIGHT')
                            >
                                Overnight — $3,000 / lot
                            </option>

                            <option
                                value="PAIR"
                                @selected(old('position_type', $formData['position_type'] ?? 'DAYTRADE') === 'PAIR')
                            >
                                Pair — $300 / lot
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            for="direction"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Arah Posisi
                        </label>

                        <select
                            id="direction"
                            name="direction"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                            <option
                                value="BUY"
                                @selected(old('direction', $formData['direction'] ?? 'BUY') === 'BUY')
                            >
                                BUY
                            </option>

                            <option
                                value="SELL"
                                @selected(old('direction', $formData['direction'] ?? 'BUY') === 'SELL')
                            >
                                SELL
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            for="lot_open"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Lot Open
                        </label>

                        <input
                            id="lot_open"
                            type="number"
                            name="lot_open"
                            min="0.01"
                            max="100"
                            step="0.01"
                            value="{{ old('lot_open', $formData['lot_open'] ?? 1) }}"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                    </div>

                    <div>
                        <label
                            for="open_price"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Harga Open XUL10
                        </label>

                        <input
                            id="open_price"
                            type="number"
                            name="open_price"
                            min="0.01"
                            step="0.01"
                            value="{{ old('open_price', $formData['open_price'] ?? 4308.69) }}"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                    </div>

                    <div>
                        <label
                            for="current_price"
                            class="mb-2 block text-sm font-semibold text-zinc-300"
                        >
                            Harga Saat Ini XUL10
                        </label>

                        <input
                            id="current_price"
                            type="number"
                            name="current_price"
                            min="0.01"
                            step="0.01"
                            value="{{ old('current_price', $formData['current_price'] ?? 4291.13) }}"
                            required
                            class="block w-full rounded-lg border border-zinc-700 bg-[#1b1b1a] px-4 py-3 text-lg text-zinc-100 outline-none transition focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                    </div>

                    <div class="mt-3 md:col-span-2">
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-green-600 px-5 py-3 font-bold text-white transition hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-[#1b1b1a]"
                        >
                            Hitung Risiko
                        </button>
                    </div>
                </form>
            </section>

            @isset($result)
                @php
                    $plClass = $result['floating_pl'] >= 0
                        ? 'text-green-400'
                        : 'text-red-400';

                    $statusClass = match ($result['risk_status']) {
                        'SAFE' => 'text-green-400',
                        'WARNING' => 'text-amber-400',
                        'MARGIN CALL' => 'text-orange-400',
                        default => 'text-red-400',
                    };
                @endphp

                <section class="mt-8 rounded-2xl border border-zinc-700 bg-[#1b1b1a] p-5 shadow-2xl sm:p-9">
                    <h2 class="mb-8 text-base font-bold uppercase tracking-wide text-zinc-300">
                        Hasil Perhitungan
                    </h2>

                    <div class="grid gap-x-10 gap-y-8 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Floating P/L
                            </p>

                            <p class="mt-2 font-mono text-2xl {{ $plClass }}">
                                ${{ number_format($result['floating_pl'], 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Margin Required
                            </p>

                            <p class="mt-2 font-mono text-2xl text-zinc-100">
                                ${{ number_format($result['margin_required'], 2) }}
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                ${{ number_format($result['margin_required_per_lot'], 2) }} per lot
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Equity
                            </p>

                            <p class="mt-2 font-mono text-2xl text-zinc-100">
                                ${{ number_format($result['equity'], 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Equity Ratio
                            </p>

                            <p class="mt-2 font-mono text-2xl {{ $statusClass }}">
                                {{ number_format($result['equity_ratio'], 2) }}%
                            </p>
                        </div>
                    </div>

                    <div class="my-8 border-t border-zinc-700"></div>

                    <div class="grid gap-x-10 gap-y-8 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Call Margin Price XUL10
                            </p>

                            <p class="mt-2 font-mono text-2xl text-amber-400">
                                ${{ number_format($result['call_margin_price'], 2) }}
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                Margin Call Level: {{ number_format($result['margin_call_level'], 0) }}%
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium uppercase text-zinc-400">
                                Auto Liquidation Price XUL10
                            </p>

                            <p class="mt-2 font-mono text-2xl text-red-400">
                                ${{ number_format($result['auto_liquidation_price'], 2) }}
                            </p>

                            <p class="mt-1 text-xs text-zinc-500">
                                Auto Liquidation Level: {{ number_format($result['auto_liquidation_level'], 0) }}%
                            </p>
                        </div>
                    </div>

                    <div class="my-8 border-t border-zinc-700"></div>

                    <div class="rounded-xl border border-zinc-700 bg-[#151515] px-5 py-7 text-center">
                        <p class="text-sm font-medium uppercase text-zinc-400">
                            Status Risiko
                        </p>

                        <p class="mt-3 text-3xl font-bold {{ $statusClass }}">
                            {{ $result['risk_status'] }}
                        </p>

                        <p class="mt-2 text-zinc-300">
                            Equity Ratio: {{ number_format($result['equity_ratio'], 2) }}%
                        </p>
                    </div>
                </section>

                <section class="mt-6 rounded-xl border border-blue-800 bg-blue-950/70 p-5 text-blue-100">
                    <h3 class="font-bold">
                        Rumus Perhitungan
                    </h3>

                    <div class="mt-3 space-y-1 text-sm">
                        <p>
                            Floating P/L BUY = (Harga Saat Ini − Harga Open) × Lot Open × 100.
                        </p>

                        <p>
                            Floating P/L SELL = (Harga Open − Harga Saat Ini) × Lot Open × 100.
                        </p>

                        <p>
                            Equity Ratio = Equity ÷ Margin Required × 100%.
                        </p>

                        <p>
                            Call Margin BUY = Current Price − ((Equity − (70% × Margin Required)) ÷ (Lot Open × 100)).
                        </p>

                        <p>
                            Auto Liquidation BUY = Current Price − ((Equity − (30% × Margin Required)) ÷ (Lot Open × 100)).
                        </p>
                    </div>
                </section>
            @endisset
        </div>
    </main>
</body>
</html>