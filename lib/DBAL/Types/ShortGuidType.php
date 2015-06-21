<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\GuidType;

class ShortGuidType extends GuidType
{
    const NAME = 'short_guid';

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform InstanceOf MySqlPlatform) {
            return 'BIGINT';
        } else {
            return parent::getSQLDeclaration($fieldDeclaration, $platform);
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
        return static::NAME;
    }
}
