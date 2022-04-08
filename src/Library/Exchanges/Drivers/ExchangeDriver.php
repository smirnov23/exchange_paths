<?php

namespace Alfacash\ExchangeRates\Library\Exchanges\Drivers;

use ccxt\Exchange;
use ccxt\ExchangeError;

/**
 * Обертка для драйвера к бирже
 */
class ExchangeDriver
{
    /**
     * Декорируемый драйвер к бирже
     *
     * @var Exchange
     */
    protected $exchange;
    
    /**
     * Конструктор
     *
     * @param Exchange $exchange декорируемый драйвер к бирже
     */
    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }
    
    /**
     * Возвражает маркет биржи по его символу
     * 
     * @param string $symbol символ маркета
     * @return array маркет биржи
     */
    public function getMarket(string $symbol) : array
    {
        return $this->exchange->markets[$symbol];
    }

    /**
     * По кодам пары возвращяет ее символ.
     * Необходимо, т.к. не всегда символ пары является строкой из кодов валюты, разделеных слэшем.
     *
     * @param $base код основной валюты пары
     * @param $quote код квотируемой валюты пары
     * @return string символ торговой пары
     */
    public function getSymbolByPair($base, $quote) : string
    {
        $markets = array_values(
            array_filter(
                $this->exchange->markets,
                function ($market) use ($base, $quote) {
                    return $market['base'] === $base && $market['quote'] === $quote;
                }
            )
        );
        
        if (count($markets) !== 1) {
            throw new \RuntimeError();
        }
        
        return $markets[0]['symbol'];
    }
    
    /**
     * Возвращает массив с кодами доступных на бирже валют
     *
     * @return array
     */
    public function getCurrenciesCodes() : array
    {
        return array_map(
            function ($currency) {
                return $currency['code'];
            },
            $this->exchange->currencies
        );
    }

    /**
     * Возвращает список активных маркетов биржи
     *
     * @return array
     */
    public function getActiveMarkets() : array
    {
        return array_filter(
            $this->exchange->markets,
            function ($market) {
                return $market['active'];
            }
        );
    }
    
    /**
     * Магический метод
     * Все неизвестгные методы объекта преобразуются из camelCase в snake_case и передаются обертываемому объекту
     *
     * @param string $function название вызываемого метода объекта
     * @param array $params параметры вызова метода
     * @return mixed
     */
    public function __call($function, $params)
    {
        try {
            return $this->exchange->$function(...$params);
        } catch (ExchangeError $e) {
            $function = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $function)), '_');
            return $this->exchange->$function(...$params);
        }
    }
}