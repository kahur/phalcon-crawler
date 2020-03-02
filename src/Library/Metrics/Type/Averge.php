<?php

namespace AA\Library\Metrics\Type;

use AA\Library\Metrics\Metric;

class Averge extends Metric
{
    /**
     * @return float|int|null
     */
    public function getValue()
    {
        $stream = $this->getValueStream();

        return number_format(array_sum($stream) / count($stream), 2);
    }
}