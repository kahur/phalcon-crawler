<?php

namespace AA\Library\Metrics\Type;

use AA\Library\Metrics\Metric;

class Count extends Metric
{
    /**
     * @var string|string
     */
    protected $name;

    /**
     * @var int|int
     */
    protected $value = 0;

    /**
     * Count constructor.
     *
     * @param string $name
     * @param int    $startValue
     */
    public function __construct(string $name, $startValue = 0)
    {
        $this->name = $name;
        $this->value = $startValue;
    }

    /**
     * Increase value
     */
    public function increase()
    {
        $this->updateValue($this->getValue() + 1);
    }

    /**
     * Decrease value
     */
    public function decrease()
    {
        if ($this->getValue() === 0) {
            return;
        }

        $this->updateValue($this->getValue() - 1);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() . ': ' . $this->getValue();
    }
}