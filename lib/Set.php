<?php

namespace MS\Doctrine;

use Psr\Log\InvalidArgumentException;

class Set extends Enum
{
    /**
     * @param int $position
     *
     * @return int
     */
    protected static function getIndex($position)
    {
        return pow(2, $position);
    }


    /**
     * @param bool $asInteger
     *
     * @return string|int
     */
    public function get($asInteger = false)
    {
        $values = (array)$this->value;

        if ($asInteger) {
            $result = 0;
            foreach ($values as $value) {
                $result += array_search($value, static::getValues());
            }

            return $result;
        }

        return $this->value;
    }

    /**
     * @param array|string|int $values
     *
     * @throws InvalidArgumentException
     */
    public function set($values)
    {
        if (func_num_args() > 1) {
            $values = func_get_args();
        }

        if (is_string($values)) {
            $values = array_map('trim', explode(',', $values));
        }

        if (is_array($values)) {
            $diff = array_diff($values, static::getValues());
            if (count($diff) > 0) {
                throw new InvalidArgumentException(
                    vsprintf(
                        'Values "%1$s" are not in the list of allowed values: "%2$s".',
                        array(
                            implode('", "', $values),
                            implode('", "', static::getValues()),
                        )
                    )
                );
            }
        } elseif (is_int($values)) {
            $integers = array_keys(static::getValues());
            $total = array_sum($integers);
            if ($values > $total) {
                throw new InvalidArgumentException(
                    vsprintf(
                        'Values "%1$s" are outside the range of allowed values: "%2$s".',
                        array(
                            implode('", "', $values),
                            implode('", "', static::getValues()),
                        )
                    )
                );
            }
        }

        if (is_int($values)) {
            $this->value = array();
            foreach (static::$values[static::class] as $integer => $string) {
                if ($integer & $values) {
                    $this->value[$integer] = static::$values[static::class][$integer];
                }
            }
        } else {
            $this->value = array();
            foreach (static::$values[static::class] as $integer => $string) {
                if (in_array($string, $values)) {
                    $this->value[$integer] = $string;
                }
            }
        }
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', $this->value);
    }
}
