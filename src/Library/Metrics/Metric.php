<?php

namespace AA\Library\Metrics;

class Metric implements MetricInterface
{
    protected $stream = [];
    protected $name;
    protected $value;

    /**
     * Metric constructor.
     *
     * @param      $name
     * @param null $initialValue
     */
    public function __construct($name, $initialValue = null)
    {
        $this->name = $name;
        $this->value = $initialValue;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     */
    public function updateValue($value)
    {
        $this->stream[] = $this->getValue();
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getValueStream()
    {
        return $this->stream;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }
}