<?php

namespace Alfacash\ExchangeRates\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Alfacash\ExchangeRates\Library\Exchanges\ExchangeDriverFactory;
use Alfacash\ExchangeRates\Library\Exchanges\ExchangeHelper;

class RatesController extends Controller
{
    /**
     * Драйвер для API биржи
     *
     * @var \Alfacash\ExchangeRates\Library\Exchanges\Drivers\ExchangeDriver
     */
    private $exchange;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->exchange = ExchangeDriverFactory::createBinance();
        app()->setLocale('ru');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = $this->exchange->getCurrenciesCodes();
        $data = [];
        return view('alfacash-exchange-rates::rates.index', compact('currencies', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $originalCurrency = $request->input('original');
        $receivedCurrency = $request->input('received');
        $amount = $request->input('amount');

        $currencies = $this->exchange->getCurrenciesCodes();
        $data = [];

        if ($originalCurrency !== $receivedCurrency && $amount > 0)
        {
            $currencyPaths = ExchangeHelper::getPathsBetweenCurrencies($this->exchange, $originalCurrency, $receivedCurrency);

            foreach ($currencyPaths as $currencyPath)
            {
                $rates = [];
                $fees = [];
                $orderPrice = [];
                $orderFee = [];
                $received = $amount;

                $orderBookSymbols = [];
                for ($i = 1; $i < count($currencyPath); $i++)
                {
                    $symbol = $this->exchange->getSymbolByPair($currencyPath[$i-1], $currencyPath[$i]);
                    $orderBookSymbols[] = $symbol;
                }
                $glasses = $this->exchange->fetchOrderBooks($orderBookSymbols);

                // todo В расчете не учитывается запрашиваемый объем заявок на покупку в стакане.
                // todo По нормальному нужно учитывать и распрадовать весь свой объем по нескольким заявкам на покупку.
                foreach ($glasses as $symbol => $glass)
                {
                    $bids = $glass['bids'];
                    $rates[$symbol] = $bids[count($bids)-1][0];
                    
                    $fees[$symbol] = $this->exchange->getMarket($symbol)['taker'];
                    $received *= $rates[$symbol];
                    $fee = $received * $fees[$symbol];
                    $received -= $fee;
                    $orderPrice[$symbol] = $received;
                    $orderFee[$symbol] = $fee;
                }

                $data[] = [
                    'path' => $currencyPath,
                    'glasses' => $glasses,
                    'rates' => $rates,
                    'fees' => $fees,
                    'order_price' => $orderPrice,
                    'order_fee' => $orderFee,
                    'original_amount' => $amount,
                    'received_amount' => $received,
                ];
            }
            
            usort(
                $data,
                function ($a, $b)
                {
                    return $a['received_amount'] < $b['received_amount'];
                }
            );
        }

        return view('alfacash-exchange-rates::rates.index', compact('currencies', 'data'));
    }
}
