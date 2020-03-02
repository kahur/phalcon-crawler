<?php

namespace AA\Library\Metrics\Type;

use AA\Library\Metrics\Metric;
use AA\Library\Metrics\MetricInterface;

class Time extends Metric
{
    public function getValue()
    {
        if (!isset($this->value['start'])) {
            return 0;
        }

        $startTime = $this->value['start'];
        $endTime = $this->value['end'] ?? microtime(true);

        $diff = round($endTime - $startTime);
        $minutes = floor($diff / 60); //only minutes
        $seconds = $diff % 60; //remaining seconds, using modulo operator

        return $minutes . ':' .$seconds;
    }

    public function start()
    {
        $this->updateValue([
            'start' => microtime(true),
            'end'   => null
        ]);
    }

    public function stop()
    {
        $value = array_merge(
            $this->getValue(),
            ['end' => microtime(true)]
        );

        $this->updateValue($value);
    }
}