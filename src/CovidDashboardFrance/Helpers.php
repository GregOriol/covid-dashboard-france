<?php

namespace CovidDashboardFrance;

class Helpers
{
    public static function incrBy(&$target, $value)
    {
        if (is_null($value)) {
            return;
        }

        if (is_null($target)) {
            $target = 0;
        }

        $target += $value;
    }

    public static function dateIterator($callback)
    {
        $begin = new \DateTime('2020-03-16');
        $end = new \DateTime('tomorrow'); // needs one more day
        $interval = \DateInterval::createFromDateString('1 day');

        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $date) {
            $callback($date);
        }
    }
}
