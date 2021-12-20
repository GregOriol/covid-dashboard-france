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

    /** @var int|null */
    public $agePop09 = null;
    /** @var int|null */
    public $agePop19 = null;
    /** @var int|null */
    public $agePop29 = null;
    /** @var int|null */
    public $agePop39 = null;
    /** @var int|null */
    public $agePop49 = null;
    /** @var int|null */
    public $agePop59 = null;
    /** @var int|null */
    public $agePop69 = null;
    /** @var int|null */
    public $agePop79 = null;
    /** @var int|null */
    public $agePop89 = null;
    /** @var int|null */
    public $agePop90 = null;

    /** @var float|null Taux incidence tests consolidé */
    public $consolTx = null;
    /** @var float|null Taux positivité tests consolidé */
    public $consolTxPos = null;
    /** @var float|null Facteur de reproduction du virus R0 */
    public $r = null;
    /** @var float|null Tension hospitalière sur la capacité en réanimation */
    public $occup = null;

    /** @var int|null */
    public $debut = null;
    /** @var int|null */
    public $complet = null;
    /** @var int|null */
    public $rappel = null;
    /** @var int|null */
    public $debutTot = null;
    /** @var int|null */
    public $completTot = null;
    /** @var int|null */
    public $rappelTot = null;
    /** @var float|null */
    public $debutCouv = null;
    /** @var int|null */
    public $completCouv = null;
    /** @var float|null */
    public $rappelCouv = null;

    /** @var int|null */
    public $ageDebut09 = null;
    /** @var int|null */
    public $ageDebut19 = null;
    /** @var int|null */
    public $ageDebut29 = null;
    /** @var int|null */
    public $ageDebut39 = null;
    /** @var int|null */
    public $ageDebut49 = null;
    /** @var int|null */
    public $ageDebut59 = null;
    /** @var int|null */
    public $ageDebut69 = null;
    /** @var int|null */
    public $ageDebut79 = null;
    /** @var int|null */
    public $ageDebut89 = null;
    /** @var int|null */
    public $ageDebut90 = null;

    /** @var int|null */
    public $ageComplet09 = null;
    /** @var int|null */
    public $ageComplet19 = null;
    /** @var int|null */
    public $ageComplet29 = null;
    /** @var int|null */
    public $ageComplet39 = null;
    /** @var int|null */
    public $ageComplet49 = null;
    /** @var int|null */
    public $ageComplet59 = null;
    /** @var int|null */
    public $ageComplet69 = null;
    /** @var int|null */
    public $ageComplet79 = null;
    /** @var int|null */
    public $ageComplet89 = null;
    /** @var int|null */
    public $ageComplet90 = null;

    /** @var int|null */
    public $ageRappel09 = null;
    /** @var int|null */
    public $ageRappel19 = null;
    /** @var int|null */
    public $ageRappel29 = null;
    /** @var int|null */
    public $ageRappel39 = null;
    /** @var int|null */
    public $ageRappel49 = null;
    /** @var int|null */
    public $ageRappel59 = null;
    /** @var int|null */
    public $ageRappel69 = null;
    /** @var int|null */
    public $ageRappel79 = null;
    /** @var int|null */
    public $ageRappel89 = null;
    /** @var int|null */
    public $ageRappel90 = null;

    /** @var int|null */
    public $ageDebutTot09 = null;
    /** @var int|null */
    public $ageDebutTot19 = null;
    /** @var int|null */
    public $ageDebutTot29 = null;
    /** @var int|null */
    public $ageDebutTot39 = null;
    /** @var int|null */
    public $ageDebutTot49 = null;
    /** @var int|null */
    public $ageDebutTot59 = null;
    /** @var int|null */
    public $ageDebutTot69 = null;
    /** @var int|null */
    public $ageDebutTot79 = null;
    /** @var int|null */
    public $ageDebutTot89 = null;
    /** @var int|null */
    public $ageDebutTot90 = null;

    /** @var int|null */
    public $ageCompletTot09 = null;
    /** @var int|null */
    public $ageCompletTot19 = null;
    /** @var int|null */
    public $ageCompletTot29 = null;
    /** @var int|null */
    public $ageCompletTot39 = null;
    /** @var int|null */
    public $ageCompletTot49 = null;
    /** @var int|null */
    public $ageCompletTot59 = null;
    /** @var int|null */
    public $ageCompletTot69 = null;
    /** @var int|null */
    public $ageCompletTot79 = null;
    /** @var int|null */
    public $ageCompletTot89 = null;
    /** @var int|null */
    public $ageCompletTot90 = null;

    /** @var int|null */
    public $ageRappelTot09 = null;
    /** @var int|null */
    public $ageRappelTot19 = null;
    /** @var int|null */
    public $ageRappelTot29 = null;
    /** @var int|null */
    public $ageRappelTot39 = null;
    /** @var int|null */
    public $ageRappelTot49 = null;
    /** @var int|null */
    public $ageRappelTot59 = null;
    /** @var int|null */
    public $ageRappelTot69 = null;
    /** @var int|null */
    public $ageRappelTot79 = null;
    /** @var int|null */
    public $ageRappelTot89 = null;
    /** @var int|null */
    public $ageRappelTot90 = null;

    /** @var float|null */
    public $ageDebutCouv09 = null;
    /** @var float|null */
    public $ageDebutCouv19 = null;
    /** @var float|null */
    public $ageDebutCouv29 = null;
    /** @var float|null */
    public $ageDebutCouv39 = null;
    /** @var float|null */
    public $ageDebutCouv49 = null;
    /** @var float|null */
    public $ageDebutCouv59 = null;
    /** @var float|null */
    public $ageDebutCouv69 = null;
    /** @var float|null */
    public $ageDebutCouv79 = null;
    /** @var float|null */
    public $ageDebutCouv89 = null;
    /** @var float|null */
    public $ageDebutCouv90 = null;

    /** @var float|null */
    public $ageCompletCouv09 = null;
    /** @var float|null */
    public $ageCompletCouv19 = null;
    /** @var float|null */
    public $ageCompletCouv29 = null;
    /** @var float|null */
    public $ageCompletCouv39 = null;
    /** @var float|null */
    public $ageCompletCouv49 = null;
    /** @var float|null */
    public $ageCompletCouv59 = null;
    /** @var float|null */
    public $ageCompletCouv69 = null;
    /** @var float|null */
    public $ageCompletCouv79 = null;
    /** @var float|null */
    public $ageCompletCouv89 = null;
    /** @var float|null */
    public $ageCompletCouv90 = null;

    /** @var float|null */
    public $ageRappelCouv09 = null;
    /** @var float|null */
    public $ageRappelCouv19 = null;
    /** @var float|null */
    public $ageRappelCouv29 = null;
    /** @var float|null */
    public $ageRappelCouv39 = null;
    /** @var float|null */
    public $ageRappelCouv49 = null;
    /** @var float|null */
    public $ageRappelCouv59 = null;
    /** @var float|null */
    public $ageRappelCouv69 = null;
    /** @var float|null */
    public $ageRappelCouv79 = null;
    /** @var float|null */
    public $ageRappelCouv89 = null;
    /** @var float|null */
    public $ageRappelCouv90 = null;

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
