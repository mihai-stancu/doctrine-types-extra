<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const NAME = 'enum';

    const DATA_CLASS = '';

    /**
     * @param array $field
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getValues($field)
    {
        if (!empty($class = static::DATA_CLASS) and method_exists($class, 'getValues')) {
            return $class::getValues();
        }

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
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return;
        }

        if ($platform instanceof MySqlPlatform) {
            return (string) $value;
        }

        return $value->get(true);
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

        if (!empty($className = static::DATA_CLASS)) {
            return new $className($value);
        }

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
        $values = array();
        foreach ($this->getValues($fieldDeclaration) as $value) {
            $values[] = $platform->quoteStringLiteral($value);
        }

        if ($platform instanceof MySqlPlatform) {
            return 'ENUM('.implode(',', $values).')';
        }

        return $platform->getBigIntTypeDeclarationSQL($fieldDeclaration);
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
