<?php

namespace CovidDashboardFrance;

class Region
{
    /** @var string */
    public $number = null;
    /** @var string */
    public $name = null;

    /**
     * Region constructor.
     *
     * @param string $number
     * @param string $name
     */
    public function __construct(string $number, string $name)
    {
        $this->number = $number;
        $this->name = $name;
    }
}
