<?php

namespace Alfacash\ExchangeRates\Library\Exchanges\Drivers;

use ccxt\binance as BaseBinance;

/**
 * Обертка для драйвера к бирже binance
 */
class Binance extends BaseBinance
{
    /**
     * Запрашивает у биржи книги заказов для заданых пар валют
     * 
     * @param array $symbols масив с символами пар
     * @return array книги заказов
     */
    // todo Самое медленное место, т.к. binance не предоставляет метод API
    // todo для запроса стаканов по нескольким парам одним запросом.
    // todo Переписать на асинхронные запросы к binance, если позволит лимит
    // todo на количество одновременных запросов.
    public function fetchOrderBooks(array $symbols) : array
    {
        $orderBooks = [];

        foreach ($symbols as $symbol)
        {
            $orderBooks[$symbol] = $this->fetch_order_book($symbol, 5);
        }
        
        return $orderBooks;
    }
}
