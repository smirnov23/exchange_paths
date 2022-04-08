@extends('alfacash-exchange-rates::layouts.app')

@section('content')
<form method="POST">
    <div class="row">
        <div class="col-2">
            <label for="inpAmount">{{ __('alfacash-exchange-rates::rates.quantity') }}</label>
            <input type="number" class="form-control" style="text-align:right" name="amount" min="0" step="0.00000001" id="inpAmount" value="{{ request('amount') ?: 1 }}" required>
        </div>
        <div class="col-4">
            <label for="dropdownOriginalCurrency">{{ __('alfacash-exchange-rates::rates.original_currency') }}</label>
            <select name="original" class="form-select" id="dropdownOriginalCurrency">
            @foreach ($currencies as $currency)
            <option value="{{ $currency}}" @if ($currency === request('original')) selected @endif>{{ $currency }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-4">
            <label for="dropdownReceivedCurrency">{{ __('alfacash-exchange-rates::rates.received_currency') }}</label>
            <select name="received" class="form-select" id="dropdownReceivedCurrency">
            @foreach ($currencies as $currency)
            <option value="{{ $currency}}" @if ($currency === request('received')) selected @endif>{{ $currency }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-2">
            <br>
            <input type="submit" class="btn btn-primary w-100" value="{{ __('alfacash-exchange-rates::rates.send') }}">
        </div>
        @csrf
    </div>
</form>
@foreach ($data as $dataRow)
    <div class="mt-3 card">
        <div class="card-header">
            <h5 class="mb-0">{{ implode('->', $dataRow['path']) }}@if ($loop->first) <span class="text-success">({{ __('alfacash-exchange-rates::rates.best_path') }})</span>@endif</h5>
            <small>{{ number_format($dataRow['original_amount'], 8, '.', ' ') }} {{ request('original') }}-> {{ number_format($dataRow['received_amount'], 8, '.', ' ') }} {{ request('received') }}</small>
        </div>
        <div class="card-body">
            @foreach ($dataRow['glasses'] as $symbol => $glass)
                <div style="float:left; margin-right: 5px">
                    <div class="mt-3 card">
                        <h5 class="card-header">{{ $symbol }}</h5>
                        <div class="card-body">
                            <table class="table table-sm table-hover small">
                                <thead>
                                    <th>{{ __('alfacash-exchange-rates::rates.price') }}</th>
                                    <th style="text-align:right">{{ __('alfacash-exchange-rates::rates.amount') }}</th>
                                </thead>
                                <tbody>
                                @foreach (array_reverse($glass['asks']) as $row)
                                <tr class="text-danger">
                                    <td>{{ number_format($row[0], 8, '.', ' ') }}</td>
                                    <td align="right">{{ number_format($row[1], 4, '.', ' ') }}</td>
                                </tr>
                                @endforeach
                                @foreach (array_reverse($glass['bids']) as $row)
                                <tr class="text-success">
                                    <td>{{ number_format($row[0], 8, '.', ' ') }}</td>
                                    <td align="right">{{ number_format($row[1], 4, '.', ' ') }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer small">
                            {{ __('alfacash-exchange-rates::rates.rate') }}: {{ number_format($dataRow['rates'][$symbol], 8, '.', ' ') }}<br>
                            {{ __('alfacash-exchange-rates::rates.fee') }}, %: {{ $dataRow['fees'][$symbol] }}<br>
                            {{ __('alfacash-exchange-rates::rates.order_price') }}: {{ number_format($dataRow['order_price'][$symbol], 8, '.', ' ') }}<br>
                            {{ __('alfacash-exchange-rates::rates.order_fee') }}: {{ number_format($dataRow['order_fee'][$symbol], 8, '.', ' ') }}
                        </div>
                    </div>
                </div>
            @endforeach
            <div style="clear:both"></div>
        </div>
    </div>
@endforeach
@endsection
