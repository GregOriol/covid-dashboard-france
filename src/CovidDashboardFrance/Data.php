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
    public $ageHosp09 = null;
    /** @var int|null */
    public $ageHosp19 = null;
    /** @var int|null */
    public $ageHosp29 = null;
    /** @var int|null */
    public $ageHosp39 = null;
    /** @var int|null */
    public $ageHosp49 = null;
    /** @var int|null */
    public $ageHosp59 = null;
    /** @var int|null */
    public $ageHosp69 = null;
    /** @var int|null */
    public $ageHosp79 = null;
    /** @var int|null */
    public $ageHosp89 = null;
    /** @var int|null */
    public $ageHosp90 = null;
    /** @var int|null */
    public $ageRea09 = null;
    /** @var int|null */
    public $ageRea19 = null;
    /** @var int|null */
    public $ageRea29 = null;
    /** @var int|null */
    public $ageRea39 = null;
    /** @var int|null */
    public $ageRea49 = null;
    /** @var int|null */
    public $ageRea59 = null;
    /** @var int|null */
    public $ageRea69 = null;
    /** @var int|null */
    public $ageRea79 = null;
    /** @var int|null */
    public $ageRea89 = null;
    /** @var int|null */
    public $ageRea90 = null;
    /** @var int|null */
    public $ageRad09 = null;
    /** @var int|null */
    public $ageRad19 = null;
    /** @var int|null */
    public $ageRad29 = null;
    /** @var int|null */
    public $ageRad39 = null;
    /** @var int|null */
    public $ageRad49 = null;
    /** @var int|null */
    public $ageRad59 = null;
    /** @var int|null */
    public $ageRad69 = null;
    /** @var int|null */
    public $ageRad79 = null;
    /** @var int|null */
    public $ageRad89 = null;
    /** @var int|null */
    public $ageRad90 = null;
    /** @var int|null */
    public $ageDc09 = null;
    /** @var int|null */
    public $ageDc19 = null;
    /** @var int|null */
    public $ageDc29 = null;
    /** @var int|null */
    public $ageDc39 = null;
    /** @var int|null */
    public $ageDc49 = null;
    /** @var int|null */
    public $ageDc59 = null;
    /** @var int|null */
    public $ageDc69 = null;
    /** @var int|null */
    public $ageDc79 = null;
    /** @var int|null */
    public $ageDc89 = null;
    /** @var int|null */
    public $ageDc90 = null;

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


    /** @var int|null */
    public $ageP09 = null;
    /** @var int|null */
    public $ageP19 = null;
    /** @var int|null */
    public $ageP29 = null;
    /** @var int|null */
    public $ageP39 = null;
    /** @var int|null */
    public $ageP49 = null;
    /** @var int|null */
    public $ageP59 = null;
    /** @var int|null */
    public $ageP69 = null;
    /** @var int|null */
    public $ageP79 = null;
    /** @var int|null */
    public $ageP89 = null;
    /** @var int|null */
    public $ageP90 = null;

    /** @var float|null Taux incidence tests consolidé */
    public $consolTx = null;
    /** @var float|null Taux positivité tests consolidé */
    public $consolTxPos = null;
    /** @var float|null Facteur de reproduction du virus R0 */
    public $r = null;
    /** @var float|null Tension hospitalière sur la capacité en réanimation */
    public $occup = null;

    /** @var int|null */
    public $dose1 = null;
    /** @var int|null */
    public $dose2 = null;
    /** @var int|null */
    public $dose1Tot = null;
    /** @var float|null */
    public $dose1Couv = null;
    /** @var int|null */
    public $dose2Tot = null;
    /** @var float|null */
    public $dose2Couv = null;

    /**
     * HospTotal constructor.
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
