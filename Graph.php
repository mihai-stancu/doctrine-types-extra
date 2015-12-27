<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes;

class Graph extends Enum
{
    /**
     * @var array
     */
    protected static $graph = [];

    /**
     * @var array
     */
    protected static $edges = [];

    /**
     * @param string $from
     * @param string $to
     *
     * @return bool
     */
    public static function edgeExists($from, $to)
    {
        $edge = $from.' -> '.$to;

        if (empty(static::$edges)) {
            foreach (static::$graph as $_edge) {
                if (!isset($_edge[0], $_edge[1])) {
                    continue;
                }

                list($_from, $_to) = $_edge;
                $_edge = $_from.' -> '.$_to;
                static::$edges[$_edge] = true;
            }
        }

        return isset(static::$edges[$edge]);
    }

    /**
     * @param array|null $graph
     *
     * @return string
     */
    public static function render(array $graph = null)
    {
        $graph = $graph ?: static::$graph;

        $items = [];
        foreach ($graph as $name => $value) {
            $items[] = static::renderItem($value);
        }
        $items = "\n\t".implode("\n\t", $items)."\n";

        return sprintf('digraph %s {%s}', $items);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private static function renderItem($item)
    {
        if (isset($item[0]) and is_array($item[0])) {
            return static::render($item);
        }

        if (isset($item[0], $item[1])) {
            $from = array_shift($item);
            $to = array_shift($item);

            return sprintf('%s -> %s %s', $from, $to, static::renderAttributes($item));
        }

        if (isset($item[0])) {
            $node = array_shift($item);

            return sprintf('%s %s', $node, static::renderAttributes($item));
        }
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    private static function renderAttributes($attributes)
    {
        $result = [];
        foreach ($attributes as $key => $value) {
            $result[] = sprintf('%s="%s"', $key, $value);
        }

        return '['.implode(', ', $result).']';
    }
}
