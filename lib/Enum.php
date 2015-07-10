<?php

namespace MS\Doctrine;

use Doctrine\DBAL\Exception\InvalidArgumentException;

class Enum
{
    public function __construct()
    {
        call_user_func_array(array($this, 'set'), func_get_args());
    }


    /** @var array|string[] List of allowed values populated via reflection. */
    protected static $values = array();

    /**
     * @return array|string[] List of allowed values.
     */
    public static function getValues()
    {
        if (empty(static::$values[static::class])) {
            $reflection = new \ReflectionClass(static::class);

            $position = 0;
            $constants = $reflection->getConstants();
            foreach ($constants as $name => $value) {
                if (is_int($value)) {
                    static::$values[static::class][$value] = $name;
                } else {
                    $index = static::getIndex($position);
                    static::$values[static::class][$index] = $value;
                    $position++;
                }
            }
        }

        return static::$values[static::class];
    }

    /**
     * @param int $position
     *
     * @return int
     */
    protected static function getIndex($position)
    {
        return $position;
    }

    /**
     * @var string
     */
    protected $value;

    /**
     * @param bool $asInteger
     *
     * @return string|int
     */
    public function get($asInteger = false)
    {
        if ($asInteger) {
            return array_search($this->value, static::$values[static::class]);
        }

        return $this->value;
    }

    /**
     * @param string|int $values
     *
     * @throws InvalidArgumentException
     */
    public function set($values)
    {
        if (!array_key_exists($values, static::getValues()) and !in_array($values, static::getValues())) {
            throw new InvalidArgumentException(
                vsprintf(
                    'Value "%1$s" is not in list of allowed values: "%2$s".',
                    array(
                        $values,
                        implode('", "', static::getValues()),
                    )
                )
            );
        }

        if (is_int($values)) {
            $this->value = static::$values[static::class][$values];
        } else {
            $this->value = $values;
        }
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
