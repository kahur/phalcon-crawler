<?php

namespace AA\Services;

use AA\Library\Metrics\Metric;
use AA\Library\Metrics\MetricInterface;
use AA\Library\Metrics\Metrics;
use AA\Library\Metrics\Type\Time;

class MetricsService
{
    const METRIC_TYPE_TIME = 'time';
    const METRIC_TYPE_COUNT = 'count';
    const METRIC_TYPE_VALUE = 'value';

    /**
     * @var Metrics
     */
    protected $metricFactory;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $metricGroups = [];

    /**
     * MetricsService constructor.
     *
     * @param Metrics $metricFactory
     */
    public function __construct(Metrics $metricFactory)
    {
        $this->metricFactory = $metricFactory;
    }

    /**
     * @param      $name
     * @param null $group
     *
     * @return MetricInterface
     */
    public function createMetric($type, $name, $initValue = null, $group = 'default')
    {
        if ($this->getMetric($name, $group)) {
            throw new \RuntimeException('Metric with this name already exist');
        }

        switch ($type) {
            case self::METRIC_TYPE_TIME:
                $initValue = $initValue ? true : false;
                $metric = $this->metricFactory->createTimeMetric($name, $initValue);
                break;
            case self::METRIC_TYPE_COUNT:
                $metric = $this->metricFactory->createCountMetric($name, $initValue);
                break;
            case self::METRIC_TYPE_VALUE:
                $metric = $this->metricFactory->createMetric($name, $initValue);
                break;
            default:
                throw new \RuntimeException('Unsuported metric');
                break;
        }

        $this->addMetricToGroup($metric, $group);

        return $metric;
    }


    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getMetric($name, $groupName = 'default')
    {
        if (!isset($this->groups[$groupName])) {
            return null;
        }

        return $this->groups[$groupName][$name] ?? null;
    }

    /**
     * @param                 $group
     * @param MetricInterface $metric
     */
    public function addMetricToGroup(MetricInterface $metric, $groupName = 'default' )
    {
        if (!isset($this->groups[$groupName])) {
            $this->groups[$groupName] = [];
        }

        $group = $this->getCurrentMetricGroup($metric);
        // remove metric from current group
        if ($group) {
            unset($this->groups[$group][$metric->getName()]);
        }

        $this->groups[$groupName][$metric->getName()] = $metric;
        $this->metricGroups[$metric->getName()] = $groupName;
    }

    /**
     * @param $groupName
     *
     * @return MetricInterface[]
     */
    public function getGroupMetrics($groupName)
    {
        $group = $this->metricGroups[$groupName] ?? [];

        foreach ($group as $index) {
            yield $this->getMetric($index);
        }
    }

    /**
     * @param MetricInterface $metric
     *
     * @return mixed|null
     */
    public function getCurrentMetricGroup(MetricInterface $metric)
    {
        return $this->metricGroups[$metric->getName()] ?? null;
    }
}
