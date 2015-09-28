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
     * @param Set            $values
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($values, AbstractPlatform $platform)
    {
        if (null === $values || 0 === $values  || '' === $values || array() === $values) {
            return null;
        }

        return implode(',', (array)$values->get());
    }

    /**
     * @param string           $values
     * @param AbstractPlatform $platform
     *
     * @return array
     */
    public function convertToPHPValue($values, AbstractPlatform $platform)
    {
        if ($values === null || $values === 0 || $values === '') {
            return array();
        }

        return explode(',', $values);
    }


    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
