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

        return $endTime - $startTime;
    }

    public function start()
    {
        $this->value = [
            'start' => microtime(true),
            'end' => null
        ];
    }

    public function stop()
    {
        $this->value['end'] = microtime(true);
    }
}