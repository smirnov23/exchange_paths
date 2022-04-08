<?php

namespace Alfacash\ExchangeRates\Library\Exchanges;

use Alfacash\ExchangeRates\Library\Exchanges\Drivers\ExchangeDriver;
use Alfacash\ExchangeRates\Library\Exchanges\Drivers\Binance;

class ExchangeDriverFactory
{
    /**
     * Создает и возвращает драйвер к бирже binance
     *
     * @param $params параметры подключения к бирже
     * @return ExchangeDriver драйвер для доступа к бирже
     */
    static public function createBinance(array $params = []) : ExchangeDriver
    {
        $exchange = new ExchangeDriver(
            new Binance(
                array_merge(
                    $params,
                    [
                        'enableRateLimit' => true,
                    ]
                )
            )
        );

        $exchange->loadMarkets();

        return $exchange;
    }
}