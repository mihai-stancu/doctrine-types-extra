<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\Tests\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use MS\DoctrineTypes\DBAL\Types\BinaryGuidType;

class BinaryGuidTypeTest extends AbstractTypeTest
{
    public function testGetSQLDeclaration()
    {
        $original = array(
            'length' => 255,
            'fixed' => false,
            'type' => 'varchar',
        );
        $expected = array(
            'length' => 16,
            'fixed' => true,
            'type' => 'binary',
        );
        parent::testGetSQLDeclaration(BinaryGuidType::class, $original, $expected, AbstractPlatform::class, 'getBinaryTypeDeclarationSQL');
    }

    /**
     * @return array
     */
    public function dataConvertToPHPValue()
    {
        return array(
            array("\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0", '00000000-0000-0000-0000-000000000000'),
            array(fopen('data:;base64,'.base64_encode("\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"), 'r'), '00000000-0000-0000-0000-000000000000'),
            array(null, null),
        );
    }

    /**
     * @dataProvider dataConvertToPHPValue
     *
     * @param string $original
     * @param string $expected
     */
    public function testConvertToPHPValue($original = null, $expected = null)
    {
        parent::testConvertToPHPValue(BinaryGuidType::class, $original, $expected);
    }

    /**
     * @return array
     */
    public function dataConvertToDatabaseValue()
    {
        return array(
            array('00000000-0000-0000-0000-000000000000', "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"),
            array(base64_encode("\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"), "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"),
            array(null, null),
        );
    }

    /**
     * @dataProvider dataConvertToDatabaseValue
     *
     * @param string $original
     * @param string $expected
     */
    public function testConvertToDatabaseValue($original = null, $expected = null)
    {
        parent::testConvertToDatabaseValue(BinaryGuidType::class, $original, $expected);
    }

    public function testRequiresSQLCommentHint()
    {
        parent::testRequiresSQLCommentHint(BinaryGuidType::class);
    }

    public function testGetName()
    {
        parent::testGetName(BinaryGuidType::class, BinaryGuidType::NAME);
    }
}
