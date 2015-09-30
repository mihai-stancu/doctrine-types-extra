<?php

namespace MS\DoctrineTypes\Tests\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use MS\DoctrineTypes\DBAL\Types\BinaryGuidType;

class BinaryGuidTypeTest extends AbstractTypeTest
{
    public function testGetSQLDeclaration()
    {
        parent::testGetSQLDeclaration(BinaryGuidType::class);
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
        parent::testGetName(BinaryGuidType::class);
    }
}
