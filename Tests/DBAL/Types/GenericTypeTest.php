<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class GenericTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $class
     *
     * @return Type $type
     */
    protected function getType($class)
    {
        return $type = $this
                ->getMockBuilder($class)
                ->disableOriginalConstructor()
                ->setMethods(null)
                ->getMock();
    }

    /**
     * @param string $class
     * @param array  $methods
     *
     * @return AbstractPlatform|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPlatform($class = AbstractPlatform::class, $methods = array())
    {
        $mock = $this
            ->getMockBuilder($class)
            ->setMethods($methods);

        $rc = new \ReflectionClass($class);
        if ($rc->isAbstract()) {
            $mock = $mock->getMockForAbstractClass();
        } else {
            $mock = $mock->getMock();
        }

        return $mock;
    }

    /**
     * @param string $class
     * @param array  $original
     * @param array  $expected
     * @param string $platform
     * @param string $method
     */
    public function testGetSQLDeclaration($class, $original, $expected, $platform = AbstractPlatform::class, $method = null)
    {
        $platform = $this->getPlatform($platform, array($method));
        $platform
            ->expects($this->any())
            ->method($method)
            ->willReturn($expected);

        $actual = $this->getType($class)->getSQLDeclaration($original, $platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param string           $class
     * @param string           $original
     * @param string           $expected
     * @param AbstractPlatform $platform
     */
    public function testConvertToPHPValue($class, $original = null, $expected = null, $platform = AbstractPlatform::class)
    {
        $platform = $this->getPlatform($platform, array());

        $actual = $this->getType($class)->convertToPHPValue($original, $platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param string           $class
     * @param string           $original
     * @param string           $expected
     * @param AbstractPlatform $platform
     */
    public function testConvertToDatabaseValue($class, $original = null, $expected = null, $platform = AbstractPlatform::class)
    {
        $platform = $this->getPlatform($platform, array());

        $actual = $this->getType($class)->convertToDatabaseValue($original, $platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param string           $class
     * @param AbstractPlatform $platform
     */
    public function testRequiresSQLCommentHint($class, $platform = AbstractPlatform::class)
    {
        $platform = $this->getPlatform($platform, array());
        $actual = $this->getType($class)->requiresSQLCommentHint($platform);

        $this->assertTrue($actual);
    }

    /**
     * @param string $class
     * @param string $expected
     */
    public function testGetName($class, $expected)
    {
        $actual = $this->getType($class)->getName();

        $this->assertEquals($expected, $actual);
    }
}
