<?php

namespace MS\Doctrine;

use Psr\Log\InvalidArgumentException;

class Set extends Enum
{
    /**
     * @param string     $name
     * @param string|int $value
     * @param int        $position
     *
     * @return array
     */
    protected static function processValue($name, $value, $position)
    {
        return array($value, pow(2, $position));
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
     * @param array|string|int $values,...
     *
     * @throws InvalidArgumentException
     */
    public function set($values = null)
    {
        if (is_array($values)) {
            $this->value = $this->parseArray($values);
        } elseif (is_string($values) and func_num_args() > 1) {
            $this->value = $this->parseArray(func_get_args());
        } elseif (is_string($values)) {
            $this->value = $this->parseString($values);
        } elseif (is_int($values)) {
            $this->value = $this->parseInteger($values);
        } elseif (is_null($values)) {
            $this->value = array();
        }
    }

    /**
     * @param int $values
     *
     * @return array
     */
    private function parseInteger($values)
    {
        $newValues = array();
        $total = 0;
        foreach (static::getValues() as $integer => $string) {
            if ($integer & $values) {
                $newValues[$integer] = static::$values[static::class][$integer];
                $total += $integer;
            }
        }

        if ($total != $values) {
            throw new InvalidArgumentException(
                vsprintf(
                    'Values "%1$s" are not in the list of allowed values: "%2$s".',
                    array(
                        $values,
                        implode('", "', array_keys(static::getValues())),
                    )
                )
            );
        }

        return $newValues;
    }

    /**
     * @param array $values
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    private function parseArray($values)
    {
        $newValues = array();
        foreach (static::getValues() as $integer => $string) {
            if (in_array($string, $values)) {
                $newValues[$integer] = $string;
            }
        }

        if ($diff = array_diff($values, $newValues)) {
            throw new InvalidArgumentException(
                vsprintf(
                    'Values "%1$s" are not in the list of allowed values: "%2$s".',
                    array(
                        implode('", "', $diff),
                        implode('", "', static::getValues()),
                    )
                )
            );
        }

        return $newValues;
    }

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    private function parseString($value)
    {
        $newValues = array();
        $index = array_search($value, static::getValues());
        if ($index !== false) {
            $newValues[$index] = $value;
        } else {
            throw new InvalidArgumentException(
                vsprintf(
                    'Value "%1$s" is not in the list of allowed values: "%2$s".',
                    array(
                        $value,
                        implode('", "', static::getValues()),
                    )
                )
            );
        }

        return $newValues;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', (array)$this->value);
    }
}
