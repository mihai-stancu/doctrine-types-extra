<?php

namespace MS\Doctrine\Tests;

use MS\Doctrine\Enum;
use MS\Doctrine\Set;

include '../../../../autoload.php';

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function exampleDataProvider()
    {
        $values = array('a', 'b', 'c', 'd');

        /* ENUMs by string */
        $cases = array();
        foreach ($values as $value) {
            $cases[] =  array(new ExampleEnum($value));
        }

        /* ENUMs by integer */
        foreach ($values as $i => $_) {
            $cases[] =  array(new ExampleEnum($i+1));
        }

        /* SETs by string */
        $precases = array();
        foreach ($values as $l1value) {
            $precases[implode('-', array($l1value))] = true;

            foreach ($values as $l2value) {
                $groups = array($l1value, $l2value);
                sort($groups);
                $groups = array_unique($groups);
                $precases[implode('-', $groups)] = true;

                foreach ($values as $l3value) {
                    $groups = array($l1value, $l2value, $l3value);
                    sort($groups);
                    $groups = array_unique($groups);
                    $precases[implode('-', $groups)] = true;


                    foreach ($values as $l4value) {
                        $groups = array($l1value, $l2value, $l3value, $l4value);
                        sort($groups);
                        $groups = array_unique($groups);
                        $precases[implode('-', $groups)] = true;
                    }
                }
            }
        }
        foreach (array_keys($precases) as $precase) {
            $cases[] = array(new ExampleSet(explode('-', $precase)));
        }

        /* SETs by integer */
        for ($value = (2*pow(2, max(array_keys($values)))-1); $value >= 0; $value--) {
            $cases[] =  array(new ExampleSet($value));
        }

        return $cases;
    }

    /**
     * @dataProvider exampleDataProvider
     *
     * @param $value
     */
    public function testSerialization($value)
    {
        $encoded = serialize($value);
        $decoded = unserialize($encoded);

        $this->assertEquals($value, $decoded);
    }

    /**
     * @dataProvider exampleDataProvider
     *
     * @param $value
     */
    public function testJsonSerialization($value)
    {
        $class = get_class($value);
        $encoded = json_encode($value);
        $decoded = new $class(json_decode($encoded, true));

        $this->assertEquals($value, $decoded);
    }
}


class ExampleEnum extends Enum
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
}

class ExampleSet extends Set
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
}
