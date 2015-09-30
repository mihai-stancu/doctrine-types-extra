<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class DeltaType extends IntegerType
{
    const NAME = 'delta';

    /**
     * @return bool
     */
    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @param string           $sqlExpr
     * @param AbstractPlatform $platform
     * @param string           $columnName
     *
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform, $columnName = null)
    {
        if ($columnName != null) {
            $sqlExpr = $columnName.' + '.$sqlExpr;
        }

        return parent::convertToDatabaseValueSQL($sqlExpr, $platform, $columnName);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return 0;
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
