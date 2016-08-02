<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\Tests\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use MS\DoctrineTypes\DBAL\Types\ShortGuidType;

class ShortGuidTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var  GenericTypeTest */
    protected $typeTest;

    public function __construct()
    {
        $this->typeTest = new GenericTypeTest();
    }

    /**
     * @return array
     */
    public function dataGetSQLDeclaration()
    {
        return array(
            array(array(), 'BIGINT', MySqlPlatform::class),
            array(array(), 'NOTBIGINT'),
        );
    }

    /**
     * @dataProvider dataGetSQLDeclaration
     *
     * @param string $original
     * @param array  $expected
     * @param array  $platform
     */
    public function testGetSQLDeclaration($original, $expected, $platform = AbstractPlatform::class)
    {
        $this->typeTest->testGetSQLDeclaration(ShortGuidType::class, $original, $expected, $platform, 'getGuidTypeDeclarationSQL');
    }

    /**
     * @return array
     */
    public function dataConvertToPHPValue()
    {
        return array(
            array(1222, 1222),
            array(null, null),
            array(1222, 1222, MySqlPlatform::class),
            array(null, null, MySqlPlatform::class),
        );
    }

    /**
     * @dataProvider dataConvertToPHPValue
     *
     * @param string $original
     * @param string $expected
     * @param string $platform
     */
    public function testConvertToPHPValue($original = null, $expected = null, $platform = AbstractPlatform::class)
    {
        $this->typeTest->testConvertToPHPValue(ShortGuidType::class, $original, $expected, $platform);
    }

    /**
     * @return array
     */
    public function dataConvertToDatabaseValue()
    {
        return array(
            array(1222, 1222),
            array(null, null),
            array(1222, 1222, MySqlPlatform::class),
            array(null, null, MySqlPlatform::class),
        );
    }

    /**
     * @dataProvider dataConvertToDatabaseValue
     *
     * @param string $original
     * @param string $expected
     * @param string $platform
     */
    public function testConvertToDatabaseValue($original = null, $expected = null, $platform = AbstractPlatform::class)
    {
        $this->typeTest->testConvertToDatabaseValue(ShortGuidType::class, $original, $expected, $platform);
    }

    public function testRequiresSQLCommentHint()
    {
        $this->typeTest->testRequiresSQLCommentHint(ShortGuidType::class);
    }

    public function testGetName()
    {
        $this->typeTest->testGetName(ShortGuidType::class, ShortGuidType::NAME);
    }
}
