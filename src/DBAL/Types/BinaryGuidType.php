<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BinaryType;
use Doctrine\DBAL\Types\IntegerType;

class BinaryGuidType extends IntegerType {}

__halt_compiler();

class BinaryGuidType extends extends BinaryType
{
    const NAME = 'binary_guid';

    /**
     * @param array            $field
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $field['length'] = 16;
        $field['fixed'] = true;
        $field['type'] = 'binary';

        return $platform->getBinaryTypeDeclarationSQL($field);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return int|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;
        $value = bin2hex($value);
        $value = sscanf($value, '%8s%4s%4s%4s%12s');
        $value = vsprintf('%8s-%4s-%4s-%4s-%12s', $value);

        return $value;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return int|mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $value = str_replace('-', '', $value);

        if (strlen($value) === 32) {
            $value = hex2bin($value);
        } elseif (strlen($value) === 22) {
            $value = base64_decode($value);
        }

        return $value;
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
