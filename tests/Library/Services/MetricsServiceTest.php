<?php

namespace AA\Tests\Services;

use AA\Library\Metrics\Metric;
use AA\Library\Metrics\Metrics;
use AA\Library\Metrics\Type\Count;
use AA\Library\Metrics\Type\Time;
use AA\Services\MetricsService;
use PHPUnit\Framework\TestCase;

class MetricsServiceTest extends TestCase
{
    public function testCreateMetric()
    {
        $factory = $this->getMockBuilder(Metrics::class)
            ->setMethods(['createTimeMetric', 'createCountMetric', 'createMetric'])
            ->getMock();

        $factory
            ->expects($this->once())
            ->method('createMetric')
            ->with('value', null)
            ->willReturn(new Metric('value'));

        $factory
            ->expects($this->once())
            ->method('createCountMetric')
            ->with('count', null)
            ->willReturn(new Count('count'));

        $factory
            ->expects($this->once())
            ->method('createTimeMetric')
            ->with('time', false)
            ->willReturn(new Time('time'));

        $metricService = new MetricsService($factory);

        $time = $metricService->createMetric(MetricsService::METRIC_TYPE_TIME,'time');
        $count = $metricService->createMetric(MetricsService::METRIC_TYPE_COUNT, 'count');
        $basic = $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'value');

        $this->assertInstanceOf(Time::class, $time);
        $this->assertInstanceOf(Count::class, $count);
        $this->assertInstanceOf(Metric::class, $basic);

        try {
            $metricService->createMetric('unsuported', 'whatever');
            $this->assertTrue(false, 'Invalid metric passed');
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetMetric()
    {
        $factory = $this->getMockBuilder(Metrics::class)
            ->setMethods(['createTimeMetric', 'createCountMetric', 'createMetric'])
            ->getMock();

        $factory
            ->expects($this->exactly(2))
            ->method('createMetric')
            ->willReturnOnConsecutiveCalls(new Metric('metric1'), new Metric('metric2'));

        $metricService = new MetricsService($factory);
        $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'metric1');
        $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'metric2');

        $metric = $metricService->getMetric('metric2');
        $this->assertEquals('metric2', $metric->getName());
        $this->assertNull($metricService->getMetric('someName'));
    }

    public function testGetMetricsInGroup()
    {
        $factory = $this->getMockBuilder(Metrics::class)
            ->setMethods(['createTimeMetric', 'createCountMetric', 'createMetric'])
            ->getMock();

        $factory
            ->expects($this->exactly(3))
            ->method('createMetric')
            ->willReturnOnConsecutiveCalls(new Metric('metric1'), new Metric('metric2'), new Metric('metric3'));

        $metricService = new MetricsService($factory);
        $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'metric1', null, 'myGroup');
        $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'metric2', null, 'myGroup');

        $metric = $metricService->createMetric(MetricsService::METRIC_TYPE_VALUE, 'metric3');

        $currentGroup = $metricService->getCurrentMetricGroup($metric);

        // test default group
        $this->assertEquals('default', $currentGroup);
        $metricService->addMetricToGroup($metric, 'myGroup');

        // test reasigned group
        $newGroup = $metricService->getCurrentMetricGroup($metric);
        $this->assertEquals('myGroup', $newGroup);

        $names = ['metric1', 'metric2', 'metric3'];

        $metrics = $metricService->getGroupMetrics('myGroup');

        foreach($metrics as $i => $m) {
            $this->assertEquals($names[$i], $m->getName());
        }
    }


}