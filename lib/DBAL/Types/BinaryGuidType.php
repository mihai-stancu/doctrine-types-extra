<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\GuidType;

class BinaryGuidType extends GuidType
{
    const BINARY_GUID = 'binary_guid';

    /**
     * @param array            $field
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        if ($platform InstanceOf MySqlPlatform) {
            $field['length'] = 16;
            $field['fixed'] = true;
            $field['type'] = 'binary';

            return parent::getSQLDeclaration($field, $platform);
        } else {
            return parent::getSQLDeclaration($field, $platform);
        }
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return int|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        switch (true) {
            case ($platform InstanceOf MySqlPlatform):
                $value = bin2hex($value);
                $value = substr($value,  0, 8) . '-'
                       . substr($value,  8, 4) . '-'
                       . substr($value, 12, 4) . '-'
                       . substr($value, 16, 4) . '-'
                       . substr($value, 20);
                return $value;
            default:
                return parent::convertToPHPValue($value, $platform);
        }
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return int|mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        switch (true) {
            case ($platform InstanceOf MySqlPlatform):
                $value = str_replace('-', '', $value);
                $value = hex2bin($value);
                return $value;
            default:
                return parent::convertToDatabaseValue($value, $platform);
        }
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
        return static::BINARY_GUID;
    }
}
