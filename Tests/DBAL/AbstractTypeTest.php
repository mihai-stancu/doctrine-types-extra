<?php

namespace MS\DoctrineTypes\Tests\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use MS\DoctrineTypes\DBAL\Types\BinaryGuidType;

abstract class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $class
     *
     * @return BinaryGuidType $type
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
     * @return AbstractPlatform|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPlatform($methods)
    {
        return $this
            ->getMockBuilder(AbstractPlatform::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }


    public function testGetSQLDeclaration($class)
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

        $actual = $this->getType($class)->getSQLDeclaration($expected, $platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param string $class
     * @param string $original
     * @param string $expected
     */
    public function testConvertToPHPValue($class, $original = null, $expected = null)
    {
        $platform = $this->getPlatform(array());

        $actual = $this->getType($class)->convertToPHPValue($original, $platform);

        $this->assertEquals($expected, $actual);
    }


    /**
     * @param string $class
     * @param string $original
     * @param string $expected
     */
    public function testConvertToDatabaseValue($class, $original = null, $expected = null)
    {
        $platform = $this->getPlatform(array());

        $actual = $this->getType($class)->convertToDatabaseValue($original, $platform);

        $this->assertEquals($expected, $actual);
    }


    public function testRequiresSQLCommentHint($class)
    {
        $platform = $this->getPlatform(array());
        $actual = $this->getType($class)->requiresSQLCommentHint($platform);

        $this->assertTrue($actual);
    }

    public function testGetName($class)
    {
        $actual = $this->getType($class)->getName();

        $this->assertEquals(BinaryGuidType::NAME, $actual);
    }
}
