<?php

namespace MS\Doctrine;

use Doctrine\DBAL\Exception\InvalidArgumentException;

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

        return $values;
    }

    /**
     * @param array|string|int $values,...
     *
     * @throws InvalidArgumentException
     */
    public function set($values = null)
    {
        if (func_num_args() > 1) {
            $this->set(func_num_args());

            return;
        }

        if (null === $values || '' === $values || 0 === $values || array() === $values) {
            $this->value = array();

            return;
        }

        if (is_array($values) && array_filter($values, 'is_int') === $values) {
            $this->value = $this->parseInteger(array_sum($values));

            return;
        }

        if (is_int($values)) {
            $this->value = $this->parseInteger($values);

            return;
        }

        if (is_array($values) && $values === array_filter($values, 'is_string')) {
            $this->value = $this->parseString(implode(',', $values));

            return;
        }

        if (is_string($values)) {
            $this->value = $this->parseString($values);

            return;
        }
    }

    /**
     * @param int $values
     *
     * @throws InvalidArgumentException
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
     * @param string $value
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    private function parseString($value)
    {
        $values = explode(',', $value);

        $newValues = array();
        foreach ($values as $value) {
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
