<?php

namespace MS\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
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
            $sqlExpr = $columnName . ' + ' . $sqlExpr;
        }

        return parent::convertToDatabaseValueSQL($sqlExpr, $platform, $columnName);
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
