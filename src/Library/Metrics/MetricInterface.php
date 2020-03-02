<?php

namespace AA\Library\Metrics;

interface MetricInterface
{
    public function getValue();
    public function getName();
    public function updateValue($value);
    public function getValueStream();

    public function __toString();
}