<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const NAME = 'enum';


    /**
     * @param array $field
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getValues($field)
    {
        if (!empty($field['values']) and is_array($field['values'])) {
            return $field['values'];
        }

        if (!empty($field['class']) and $method = array($field['class'], 'getValues') and is_callable($method)) {
            return call_user_func($method);
        }

        throw new InvalidArgumentException(
            vsprintf(
                'Field "%1$s" declaration is missing "values" or "class" options.',
                array($field['name'])
            )
        );
    }


    /**
     * @param AbstractPlatform $platform
     *
     * @throws DBALException
     */
    protected function checkPlatform($platform)
    {
        if ($platform InstanceOf DB2Platform or $platform InstanceOf OraclePlatform
            or $platform InstanceOf SqlitePlatform or $platform InstanceOf SQLServerPlatform) {
            throw new DBALException(vsprintf('ENUMs are not supported by %1$s.', array($platform->getName())));
        }
    }


    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return (string)$value;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return array
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        return $value;
    }


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
        $this->checkPlatform($platform);

        $values = array();
        foreach ($this->getValues($fieldDeclaration) as $value) {
            $values[] = $platform->quoteStringLiteral($value);
        }

        return strtoupper(static::NAME) . '(' . implode(',', $values) . ')';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
