<?php

namespace CovidDashboardFrance;

class Data
{
    /** @var \DateTime */
    public $date = null;
    /** @var string|null */
    public $country = null;
    /** @var string|null */
    public $region = null;
    /** @var string|null */
    public $department = null;

    /** @var int|null */
    public $hosp = null;
    /** @var int|null */
    public $rea = null;
    /** @var int|null */
    public $rad = null;
    /** @var int|null */
    public $dc = null;

    /** @var int|null */
    public $incidenceHosp = null;
    /** @var int|null */
    public $incidenceRea = null;
    /** @var int|null */
    public $incidenceRad = null;
    /** @var int|null */
    public $incidenceDc = null;

    /** @var int|null */
    public $pop = null;
    /** @var int|null */
    public $p = null;
    /** @var float|null */
    public $tx = null;
    /** @var float|null */
    public $tx7 = null;

    /**
     * Total constructor.
     *
     * @param \DateTime   $date
     * @param string      $country
     * @param string|null $region
     * @param string|null $department
     */
    public function __construct(\DateTime $date, string $country, ?string $region, ?string $department)
    {
        $this->date = $date;
        $this->country = $country;
        $this->region = $region;
        $this->department = $department;
    }
}
