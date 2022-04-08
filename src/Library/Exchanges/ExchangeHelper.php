<?php

namespace Alfacash\ExchangeRates\Library\Exchanges;

use Alfacash\ExchangeRates\Library\Structures\Graph;
use Alfacash\ExchangeRates\Library\Algorithms\Graph\Backtracking\PathsBetweenTwoVertices;
use Alfacash\ExchangeRates\Library\Exchanges\Drivers\ExchangeDriver;

class ExchangeHelper
{
    /**
     * Ищет все комбинации пар для конвертации исходной валюты в валюту для получения
     *
     * @param ExchangeDriver $exchangeDriver драйвер к бирже
     * @param string $startCurrency исходная валюта
     * @param string $endCurrency валюта к получению
     * @return array массив валютных пар для конвертации исходной валюты в валюту для получения
     */
    static public function getPathsBetweenCurrencies(ExchangeDriver $exchangeDriver, string $startCurrency, string $endCurrency) : array
    {
        return (
            new PathsBetweenTwoVertices(
                new Graph(
                    array_map(
                        function ($market) {
                            return [$market['base'], $market['quote']];
                        },
                        $exchangeDriver->getActiveMarkets()
                    )
                ),
                $startCurrency,
                $endCurrency
            )
        )->run();
    }
}