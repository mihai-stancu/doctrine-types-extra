<?php

namespace MS\DoctrineTypes\Tests\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use MS\DoctrineTypes\DBAL\Types\BinaryGuidType;

class BinaryGuidTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return BinaryGuidType $type
     */
    protected function getType()
    {
        return $type = $this
                ->getMockBuilder(BinaryGuidType::class)
                ->disableOriginalConstructor()
                ->setMethods(null)
                ->getMock();
    }

    /**
     * @return AbstractPlatform|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPlatform($methods)
    {
        return $this
            ->getMockBuilder(AbstractPlatform::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

    public function testGetSQLDeclaration()
    {
        $expected = array(
            'length' => 16,
            'fixed'  => true,
            'type'   => 'binary',
        );

        $platform = $this->getPlatform(array('getBinaryTypeDeclarationSQL'));
        $platform
            ->expects($this->any())
            ->method('getBinaryTypeDeclarationSQL')
            ->willReturn($expected);

        $actual = $this->getType()->getSQLDeclaration($expected, $platform);

        $this->assertEquals($expected, $actual);
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
        $platform = $this->getPlatform(array());

        $actual = $this->getType()->convertToPHPValue($original, $platform);

        $this->assertEquals($expected, $actual);
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
        $platform = $this->getPlatform(array());

        $actual = $this->getType()->convertToDatabaseValue($original, $platform);

        $this->assertEquals($expected, $actual);
    }


    public function testRequiresSQLCommentHint()
    {
        $platform = $this->getPlatform(array());
        $actual = $this->getType()->requiresSQLCommentHint($platform);

        $this->assertTrue($actual);
    }

    public function testGetName()
    {
        $actual = $this->getType()->getName();

        $this->assertEquals(BinaryGuidType::NAME, $actual);
    }
}
