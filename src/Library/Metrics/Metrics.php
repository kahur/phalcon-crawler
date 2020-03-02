<?php

namespace AA\Library\Metrics;

use AA\Library\Metrics\Type\Count;
use AA\Library\Metrics\Type\Time;

/**
 * Class Metrics
 * @package AA\Library\Metrics
 */
class Metrics
{

    /**
     * @param      $name
     * @param null $defaultValue
     *
     * @return Metric
     */
    public function createMetric($name, $defaultValue = null)
    {
        return new Metric($name, $defaultValue);
    }

    /**
     * @param      $name
     * @param bool $start
     *
     * @return Time
     */
    public function createTimeMetric($name, $start = false)
    {
        $initialValue = $start ? microtime(true) : null;

        return new Time($name, $initialValue);
    }

    /**
     * @param     $name
     * @param int $startValue
     *
     * @return Count
     */
    public function createCountMetric($name, $startValue = 0)
    {
        return new Count($name, $startValue);
    }
}
