<?php

namespace Alfacash\ExchangeRates\Library\Algorithms\Graph\Backtracking;

use Alfacash\ExchangeRates\Library\Structures\Graph;

/**
 * Реализация поиска всех путей между двумя вершинами ориентированного графа
 * с помощью алгоритма перебора с возвратом.
 */
class PathsBetweenTwoVertices
{
    /**
     * Ориентированный граф
     *
     * @var Graph
     */
    private Graph $graph;

    /**
     * Начальная вершина графа
     *
     * @var string
     */
    private string $startVertice;

    /**
     * Конечная вершина графа
     *
     * @var string
     */
    private string $endVertice;

    /**
     * Исследуемый путь между вершиными
     *
     * @var array
     */
    private array $exploredPath;

    /**
     * Найденые пути между задаными вершинами
     *
     * @var array
     */
    private array $pathsFound;

    /**
     * Конструктор
     *
     * @param Graph $graph ориентированный граф
     * @param string $startVertice начальная вершина
     * @param string $endVertice конечная вершина
     */
    public function __construct(Graph $graph, string $startVertice, string $endVertice)
    {
        $this->graph = $graph;
        $this->startVertice = $startVertice;
        $this->endVertice = $endVertice;
    }

    /**
     * Запускает рекурсивный алгоритм перебора с возвратом
     *
     * @param int $researchedStep номер исследуемого шага пути к конечной вершине
     * @return void
     */
    private function backtrack(int $researchedStep) : void
    {
        if (
            isset($this->exploredPath[$researchedStep])
            && ($this->exploredPath[$researchedStep] === $this->endVertice)
        ) {
            $this->pathsFound[] = array_slice($this->exploredPath, 0, $researchedStep);
        }
        else
        {
            ++$researchedStep;
            foreach ($this->constructVerticeCandidates($researchedStep) as $vertice)
            {
                $this->exploredPath[$researchedStep] = $vertice;
                $this->backtrack($researchedStep);
            }
        }
    }

    /**
     * Составляет список вершин-кандидатов на следующий шаг пути к конечной вершине
     * 
     * @param int $researchedStep номер исследуемого шага пути к конечной вершине
     * @return array массив вершин-кандидатов на следующий шаг пути к конечной вершине
     */
    private function constructVerticeCandidates(int $researchedStep) : array
    {
        if ($researchedStep === 1)
        {
            return [$this->startVertice];
        }

        $verticeCandidates = [];

        $verticesInExploredPath = [];
        foreach ($this->exploredPath as $vertice)
        {
            $verticesInExploredPath[$vertice] = 1;
        }

        $lastStepVertice = $this->exploredPath[$researchedStep - 1];
        $lastStepVerticeEdge = $this->graph->getEdge($lastStepVertice);

        while ($lastStepVerticeEdge)
        {
            if (!isset($verticesInExploredPath[$lastStepVerticeEdge->endVertice]))
            {
                $verticeCandidates[] = $lastStepVerticeEdge->endVertice;
            }

            $lastStepVerticeEdge = $lastStepVerticeEdge->next;
        }

        return $verticeCandidates;
    }

    /**
     * Запуск алгоритма поиска
     *
     * @return array массив найденых путей между двумя вершинами
     */
    public function run() : array
    {
        $this->exploredPath = [];
        $this->pathsFound = [];
        $this->backtrack(0);

        return $this->pathsFound;
    }
}