<?php

namespace MS\Doctrine\Tests;

use MS\Doctrine\Enum;
use MS\Doctrine\Set;

class SetTest extends EnumTest
{
    /**
     * @param $array
     *
     * @return array
     */
    private function combinations($array)
    {
        $results = array(array());
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public function setDataProvider()
    {
        $cases = array();

        $values = array('a', 'b', 'c', 'd');
        $combinations = $this->combinations(array('a', 'b', 'c', 'd'));

        /* SETs by string */
        foreach ($combinations as $value) {
            $cases[] = array(new ExampleSet($combinations), $value);
        }
        /* SETs by integers */
        for ($value = (2 * pow(2, max(array_keys($values))) - 1); $value >= 0; $value--) {
            $cases[] = array(new ExampleSet($value), $value);
        }

        return $cases;
    }

    /**
     * @dataProvider setDataProvider
     *
     * @param Set   $object
     * @param array $value
     */
    public function testAccessors($object, $value)
    {
        parent::testAccessors($object, $value);
    }

    /**
     * @dataProvider setDataProvider
     *
     * @param Set   $object
     * @param array $value
     */
    public function testSerialization($object, $value)
    {
        parent::testSerialization($object, $value);
    }

    /**
     * @dataProvider setDataProvider
     *
     * @param Set   $object
     * @param array $value
     */
    public function testJsonSerialization($object, $value)
    {
        parent::testJsonSerialization($object, $value);
    }
}

class ExampleSet extends Set
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
}
