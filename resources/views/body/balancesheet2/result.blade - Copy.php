<!DOCTYPE html>
<html>
<head>
    <title>Balance Sheet</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 8px; vertical-align: top; border: 1px solid #ccc; }
        .category { font-weight: bold; background: #f2f2f2; }
        .group-row { font-style: italic; background-color: #f9f9f9; }
        .account-row { padding-left: 20px; }
        .total { font-weight: bold; background-color: #ddd; }
        .column { width: 50%; }
    </style>
</head>

@php
    function formatAmount($amount) {
        return $amount < 0
            ? '(' . number_format(abs($amount), 2) . ')'
            : number_format($amount, 2);
    }
@endphp

<body>
    <h2 style="text-align: center;">Balance Sheet</h2>
    <table>
        <thead>
            <tr>
                <th class="column">Liabilities & Equity</th>
                <th>Amount</th>
                <th class="column">Assets</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $maxCategories = max(count($liabilitiesAndEquity), count($assets));
            @endphp

            @for ($i = 0; $i < $maxCategories; $i++)
                {{-- Category row --}}
                <tr>
                    {{-- Liabilities & Equity --}}
                    @if(isset($liabilitiesAndEquity[$i]))
                        <td class="category">{{ $liabilitiesAndEquity[$i]['name'] }}</td>
                        <td class="category total">{{ formatAmount($liabilitiesAndEquity[$i]['total']) }}</td>
                    @else
                        <td></td><td></td>
                    @endif

                    {{-- Assets --}}
                    @if(isset($assets[$i]))
                        <td class="category">{{ $assets[$i]['name'] }}</td>
                        <td class="category total">{{ formatAmount($assets[$i]['total']) }}</td>
                    @else
                        <td></td><td></td>
                    @endif
                </tr>

                @php
                    // Filter groups with at least one non-zero account
                    $liabGroups = array_filter($liabilitiesAndEquity[$i]['groups'] ?? [], function ($group) {
                        return collect($group['accounts'] ?? [])->pluck('balance')->filter(function ($b) {
                            return $b != 0;
                        })->count() > 0;
                    });

                    $assetGroups = array_filter($assets[$i]['groups'] ?? [], function ($group) {
                        return collect($group['accounts'] ?? [])->pluck('balance')->filter(function ($b) {
                            return $b != 0;
                        })->count() > 0;
                    });

                    $liabGroups = array_values($liabGroups);
                    $assetGroups = array_values($assetGroups);
                    $maxGroups = max(count($liabGroups), count($assetGroups));
                @endphp

                @for ($g = 0; $g < $maxGroups; $g++)
                    @php
                        $liabGroup = $liabGroups[$g] ?? null;
                        $assetGroup = $assetGroups[$g] ?? null;
                    @endphp

                    {{-- Group row --}}
                    <tr>
                        {{-- Liabilities group --}}
                        @if($liabGroup)
                            <td class="group-row">{{ $liabGroup['group_name'] }}</td>
                            <td class="group-row total">{{ formatAmount($liabGroup['group_total']) }}</td>
                        @else
                            <td></td><td></td>
                        @endif

                        {{-- Assets group --}}
                        @if($assetGroup)
                            <td class="group-row">{{ $assetGroup['group_name'] }}</td>
                            <td class="group-row total">{{ formatAmount($assetGroup['group_total']) }}</td>
                        @else
                            <td></td><td></td>
                        @endif
                    </tr>

                    {{-- Accounts inside group --}}
                    @php
                        $liabAccounts = collect($liabGroup['accounts'] ?? [])->filter(function ($acc) {
                            return $acc['balance'] != 0;
                        })->values();

                        $assetAccounts = collect($assetGroup['accounts'] ?? [])->filter(function ($acc) {
                            return $acc['balance'] != 0;
                        })->values();

                        $maxAccounts = max($liabAccounts->count(), $assetAccounts->count());
                    @endphp

                    @for ($a = 0; $a < $maxAccounts; $a++)
                        <tr>
                            {{-- Liability Account --}}
                            @if(isset($liabAccounts[$a]))
                                <td class="account-row">{{ $liabAccounts[$a]['name'] }}</td>
                                <td>{{ formatAmount($liabAccounts[$a]['balance']) }}</td>
                            @else
                                <td></td><td></td>
                            @endif

                            {{-- Asset Account --}}
                            @if(isset($assetAccounts[$a]))
                                <td class="account-row">{{ $assetAccounts[$a]['name'] }}</td>
                                <td>{{ formatAmount($assetAccounts[$a]['balance']) }}</td>
                            @else
                                <td></td><td></td>
                            @endif
                        </tr>
                    @endfor
                @endfor
            @endfor

            {{-- Grand Totals --}}
            <tr>
                <td class="total">Total Liabilities & Equity</td>
                <td class="total">
                    {{ formatAmount(collect($liabilitiesAndEquity)->sum('total')) }}
                </td>
                <td class="total">Total Assets</td>
                <td class="total">
                    {{ formatAmount(collect($assets)->sum('total')) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
