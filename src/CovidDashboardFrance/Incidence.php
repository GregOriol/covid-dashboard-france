<?php

namespace CovidDashboardFrance;

class Incidence
{
    /** @var \DateTime */
    public $date = null;
    /** @var string */
    public $department = null;
    /** @var int */
    public $hosp = null;
    /** @var int */
    public $rea = null;
    /** @var int */
    public $rad = null;
    /** @var int */
    public $dc = null;

    /**
     * Total constructor.
     *
     * @param \DateTime $date
     * @param string $department
     * @param int $hosp
     * @param int $rea
     * @param int $rad
     * @param int $dc
     */
    public function __construct(\DateTime $date, string $department, int $hosp, int $rea, int $rad, int $dc)
    {
        $this->date = $date;
        $this->department = $department;
        $this->hosp = $hosp;
        $this->rea = $rea;
        $this->rad = $rad;
        $this->dc = $dc;
    }
}
