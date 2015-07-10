<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use MS\Doctrine\Set;

class SetType extends EnumType
{
    const NAME = 'set';


    /**
     * @param AbstractPlatform $platform
     *
     * @throws DBALException
     */
    protected function checkPlatform($platform)
    {
        if (!($platform InstanceOf MySqlPlatform)) {
            throw new DBALException('SETs are not supported by ' . $platform->getName() . '.');
        }
    }


    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return array
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === '') {
            return array();
        } elseif ($value === null) {
            return array();
        }

        return parent::convertToPHPValue($value, $platform);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
