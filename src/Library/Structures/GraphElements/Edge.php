<?php

namespace Alfacash\ExchangeRates\Library\Structures\GraphElements;

/**
 * Ребро графа. Элемент списка смежности
 */
class Edge
{
    /**
     * Конечная вершина ребра
     *
     * @var string
     */
    public string $endVertice;

    /**
     * Следующее ребро от текущей вершины
     *
     * @var ?self
     */
    public ?self $next;

    /**
     * Конструктор
     *
     * @param string $endVertice конечная вершина ребра
     */
    public function __construct(string $endVertice)
    {
        $this->endVertice = $endVertice;
    }
}