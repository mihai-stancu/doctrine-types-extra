<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\Tests;

use MS\DoctrineTypes\Enum;
use MS\DoctrineTypes\Set;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function enumDataProvider()
    {
        $cases = array();

        $values = array('a', 'b', 'c', 'd');

        /* ENUMs by string */
        foreach ($values as $value) {
            $cases[] = array(new ExampleEnum($value), $value);
        }

        /* ENUMs by integer */
        foreach ($values as $index => $v) {
            $cases[] = array(new ExampleEnum($index + 1), $v);
        }

        return $cases;
    }

    /**
     * @dataProvider enumDataProvider
     *
     * @param Enum   $object
     * @param string $value
     */
    public function testAccessors($object, $value)
    {
        $object->set($value);

        if (is_array($value)) {
            $this->assertEquals($value, array_values($object->get()));
        } elseif (is_int($value)) {
            $this->assertEquals($value, $object->get(true));
        } else {
            $this->assertEquals($value, $object->get());
        }
    }

    /**
     * @dataProvider enumDataProvider
     *
     * @param Enum   $object
     * @param string $value
     */
    public function testSerialization($object, $value)
    {
        $encoded = serialize($object);
        $decoded = unserialize($encoded);

        $this->assertEquals($object, $decoded,
            print_r(array($object, $value, $encoded, $decoded), true)
        );
    }

    /**
     * @dataProvider enumDataProvider
     *
     * @param Enum   $object
     * @param string $value
     */
    public function testJsonSerialization($object, $value)
    {
        $class = get_class($object);
        $encoded = json_encode($object);
        $decoded = new $class(json_decode($encoded, true));

        $this->assertEquals($object, $decoded);
    }
}

class ExampleEnum extends Enum
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
}
