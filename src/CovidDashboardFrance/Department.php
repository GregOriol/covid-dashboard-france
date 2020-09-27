<?php

namespace CovidDashboardFrance;

class Department
{
    /** @var string */
    public $number = null;
    /** @var string */
    public $region = null;
    /** @var string */
    public $name = null;

    /**
     * Department constructor.
     *
     * @param string $number
     * @param string $region
     * @param string $name
     */
    public function __construct(string $number, string $region, string $name)
    {
        $this->number = $number;
        $this->region = $region;
        $this->name = $name;
    }
}
