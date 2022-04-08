<?php

namespace Alfacash\ExchangeRates\Library\Structures;

use Alfacash\ExchangeRates\Library\Structures\GraphElements\Edge;

/**
 * Ориентированный граф
 */
class Graph
{
    /**
     * Список смежности
     *
     * @var array
     */
    private array $edges = [];

    /**
     * Конструктор
     *
     * @param array $edgeData массив ребер графа
     */
    public function __construct(array $edgeData)
    {
        foreach ($edgeData as $edge)
        {
            $this->insertEdge($edge[0], $edge[1]);
        }
    }

    /**
     * Добавляет ребро в список смежности
     *
     * @param string $startVertice начальная вершина ребра
     * @param string $endVertice конечная вершина ребра
     * @return void
     */
    private function insertEdge(string $startVertice, string $endVertice) : void
    {
        if (!array_key_exists($startVertice, $this->edges))
        {
            $this->edges[$startVertice] = null;
        }

        $edge = new Edge($endVertice);
        $edges = $this->edges[$startVertice];
        $edge->next = $edges;
        $this->edges[$startVertice] = $edge;
    }
    
    /**
     * Возвращает для данной вершины первое ребро в списке смежности
     *
     * @return Edge первое ребро от данной вершины
     */
    public function getEdge(string $vertice) : ?Edge
    {
        return $this->edges[$vertice] ?? null;
    }

    /**
     * Вывести граф браузер
     *
     * @return void
     */
    public function print() : void
    {
        foreach ($this->edges as $startVertice => $edges)
        {
            echo "<strong>{$x}</strong>: ";
            $edge = $edges;
            while ($edge)
            {
                echo "{$edge->endVertice} ";
                $edge = $edge->next;
            }
            echo '<br>';
        }
    }
}