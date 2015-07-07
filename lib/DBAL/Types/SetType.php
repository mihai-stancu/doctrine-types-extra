<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\SimpleArrayType;

class SetType extends SimpleArrayType
{
    const NAME = 'set';

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @throws DBALException
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform InstanceOf MySqlPlatform) {
            if (!empty($fieldDeclaration['values']) && is_array($fieldDeclaration['values'])) {
                $values = [];
                foreach ($fieldDeclaration['values'] as $value) {
                    $values[] = $platform->quoteStringLiteral($value);
                }

                return 'SET (' . implode(',', $values) . ')';
            }
        } else throw new DBALException('SETs are not supported by ' . $platform->getName() . '.');
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === '') {
            return '';
        }

        return parent::convertToDatabaseValue($value, $platform);
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
        }

        return parent::convertToPHPValue($value, $platform);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
