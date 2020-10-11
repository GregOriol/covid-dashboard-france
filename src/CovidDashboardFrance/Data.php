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

    /** @var int|null Population */
    public $pop = null;
    /** @var int|null Nb tests positifs */
    public $p = null;
    /** @var int|null Nb tests réalisés */
    public $t = null;
    /** @var float|null Taux incidence tests */
    public $tx = null;
    /** @var float|null Taux incidence tests sur une semaine */
    public $tx7 = null;
    /** @var float|null Taux positivité tests */
    public $txPos = null;
    /** @var float|null Taux positivité tests sur une semaine */
    public $txPos7 = null;

    /** @var float|null Taux incidence tests consolidé */
    public $consolTx = null;
    /** @var float|null Taux positivité tests consolidé */
    public $consolTxPos = null;
    /** @var float|null Facteur de reproduction du virus R0 */
    public $r = null;
    /** @var float|null Tension hospitalière sur la capacité en réanimation */
    public $occup = null;

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
