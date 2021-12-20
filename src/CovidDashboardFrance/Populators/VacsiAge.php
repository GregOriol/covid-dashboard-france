<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class VacsiAge
{
    /** @var France */
    protected $france;

    /** @var Covid */
    protected $covid;

    /**
     * @param France $france
     * @param Covid  $covid
     *
     * @throws \Exception
     */
    public function __construct(France $france, Covid $covid)
    {
        $this->france = $france;
        $this->covid = $covid;
    }

    /**
     * @param string $dataDepUrl
     * @param string $dataRegUrl
     * @param string $dataFraUrl
     *
     * @throws \Exception
     */
    public function populateData(string $dataDepUrl, string $dataRegUrl, string $dataFraUrl)
    {
        foreach ([$dataDepUrl => 'department', $dataRegUrl => 'region', $dataFraUrl => 'country'] as $dataUrl => $type) {
            $data = file_get_contents($dataUrl);
            if ($data === false) {
                throw new \Exception('Could not read total data at '.$dataUrl);
            }
            $data = str_replace("\r", '', $data);
            $data = explode("\n", $data);

            $i = 0;
            foreach ($data as $row) {
                $i += 1;

                if ($i === 1) { continue; } // skipping header

                $row = str_getcsv($row, ';');
                if (count($row) !== 12) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc      = $row[0];
                $age      = $row[1];
                $jour     = $row[2];
                $debut    = $row[3];
                $complet  = $row[4];
                $rappel   = $row[5];
                $debutTot = $row[6];
                $completTot = $row[7];
                $rappelTot = $row[8];
                $debutCouv = $row[9];
                $completCouv = $row[10];
                $rappelCouv = $row[11];

                if ($age === null || $age === '0') { continue; } // only taking into account data for ages and not 0 which is the sum of them

                $country = 'FRA';
                $region = null;
                $department = null;
                if ($type === 'region') {
                    $region = $this->france->getRegion($loc);
                    if (!$region) { continue; }
                }
                if ($type === 'department') {
                    $department = $this->france->getDepartment($loc);
                    if (!$department) { continue; }
                    $region = $this->france->getRegionForDepartment($department);
                    if (!$region) { continue; }
                }

                $date = new \DateTime($jour);

                $data = $this->covid->getDataForDate($date, $country, $region ? $region->number : null, $department ? $department->number : null);
                if (!$data) { continue; }

                if ($age === '9') {
                    $age = '09'; // fixing value
                }

                // Trying to match age ranges from other datasets
                if ($age === '17') {
                    Helpers::incrBy($data->{'ageDebut'.'19'}, $debut);
                    Helpers::incrBy($data->{'ageComplet'.'19'}, $complet);
                    Helpers::incrBy($data->{'ageRappel'.'19'}, $rappel);
                    Helpers::incrBy($data->{'ageDebutTot'.'19'}, $debutTot);
                    Helpers::incrBy($data->{'ageCompletTot'.'19'}, $completTot);
                    Helpers::incrBy($data->{'ageRappelTot'.'19'}, $rappelTot);
                } else if ($age === '24') {
                    // Within the 18-24 age range, about 30% are 18-19 and 70% are 20-24
                    Helpers::incrBy($data->{'ageDebut'.'19'}, round((int)$debut * 0.30));
                    Helpers::incrBy($data->{'ageComplet'.'19'}, round((int)$complet * 0.30));
                    Helpers::incrBy($data->{'ageRappel'.'19'}, round((int)$rappel * 0.30));
                    Helpers::incrBy($data->{'ageDebutTot'.'19'}, round((int)$debutTot * 0.30));
                    Helpers::incrBy($data->{'ageCompletTot'.'19'}, round((int)$completTot * 0.30));
                    Helpers::incrBy($data->{'ageRappelTot'.'19'}, round((int)$rappelTot * 0.30));

                    Helpers::incrBy($data->{'ageDebut'.'29'}, round((int)$debut * 0.70));
                    Helpers::incrBy($data->{'ageComplet'.'29'}, round((int)$complet * 0.70));
                    Helpers::incrBy($data->{'ageRappel'.'29'}, round((int)$rappel * 0.70));
                    Helpers::incrBy($data->{'ageDebutTot'.'29'}, round((int)$debutTot * 0.70));
                    Helpers::incrBy($data->{'ageCompletTot'.'29'}, round((int)$completTot * 0.70));
                    Helpers::incrBy($data->{'ageRappelTot'.'29'}, round((int)$rappelTot * 0.70));
                } else if ($age === '29') {
                    Helpers::incrBy($data->{'ageDebut'.'29'}, (int)$debut);
                    Helpers::incrBy($data->{'ageComplet'.'29'}, (int)$complet);
                    Helpers::incrBy($data->{'ageRappel'.'29'}, (int)$rappel);
                    Helpers::incrBy($data->{'ageDebutTot'.'29'}, (int)$debutTot);
                    Helpers::incrBy($data->{'ageCompletTot'.'29'}, (int)$completTot);
                    Helpers::incrBy($data->{'ageRappelTot'.'29'}, (int)$rappelTot);
                } else if ($age === '64' || $age === '69') {
                    Helpers::incrBy($data->{'ageDebut'.'69'}, (int)$debut);
                    Helpers::incrBy($data->{'ageComplet'.'69'}, (int)$complet);
                    Helpers::incrBy($data->{'ageRappel'.'69'}, (int)$rappel);
                    Helpers::incrBy($data->{'ageDebutTot'.'69'}, (int)$debutTot);
                    Helpers::incrBy($data->{'ageCompletTot'.'69'}, (int)$completTot);
                    Helpers::incrBy($data->{'ageRappelTot'.'69'}, (int)$rappelTot);
                } else if ($age === '74' || $age === '79') {
                    Helpers::incrBy($data->{'ageDebut'.'79'}, (int)$debut);
                    Helpers::incrBy($data->{'ageComplet'.'79'}, (int)$complet);
                    Helpers::incrBy($data->{'ageRappel'.'79'}, (int)$rappel);
                    Helpers::incrBy($data->{'ageDebutTot'.'79'}, (int)$debutTot);
                    Helpers::incrBy($data->{'ageCompletTot'.'79'}, (int)$completTot);
                    Helpers::incrBy($data->{'ageRappelTot'.'79'}, (int)$rappelTot);
                } else if ($age === '80') {
                    // Within the 80+ age range, about 78% are 80-89 and 22% are 90+
                    $data->{'ageDebut'.'89'} = round((int)$debut * 0.78);
                    $data->{'ageComplet'.'89'} = round((int)$complet * 0.78);
                    $data->{'ageRappel'.'89'} = round((int)$rappel * 0.78);
                    $data->{'ageDebutTot'.'89'} = round((int)$debutTot * 0.78);
                    $data->{'ageCompletTot'.'89'} = round((int)$completTot * 0.78);
                    $data->{'ageRappelTot'.'89'} = round((int)$rappelTot * 0.78);

                    $data->{'ageDebut'.'90'} = round((int)$debut * 0.22);
                    $data->{'ageComplet'.'90'} = round((int)$complet * 0.22);
                    $data->{'ageRappel'.'90'} = round((int)$rappel * 0.22);
                    $data->{'ageDebutTot'.'90'} = round((int)$debutTot * 0.22);
                    $data->{'ageCompletTot'.'90'} = round((int)$completTot * 0.22);
                    $data->{'ageRappelTot'.'90'} = round((int)$rappelTot * 0.22);
                } else {
                    $data->{'ageDebut'.$age} = (int)$debut;
                    $data->{'ageComplet'.$age} = (int)$complet;
                    $data->{'ageRappel'.$age} = (int)$rappel;
                    $data->{'ageDebutTot'.$age} = (int)$debutTot;
                    $data->{'ageCompletTot'.$age} = (int)$completTot;
                    $data->{'ageRappelTot'.$age} = (int)$rappelTot;
                }
            }
        }
    }

    public function generateConsolidations()
    {
        $ages = ['09', '19', '29', '39', '49', '59', '69', '79', '89', '90'];

        // Generating ageDoseXCouv data
        Helpers::dateIterator(function ($date) use ($ages) {
            $countryData = $this->covid->getDataForDate($date, 'FRA', null, null);

            foreach ($ages as $age) {
                if ($countryData->{'agePop'.$age} !== null) {
                    $countryData->{'ageDebutCouv'.$age} = round($countryData->{'ageDebutTot'.$age} / $countryData->{'agePop'.$age} * 100, 1);
                    $countryData->{'ageCompletCouv'.$age} = round($countryData->{'ageCompletTot'.$age} / $countryData->{'agePop'.$age} * 100, 1);
                    $countryData->{'ageRappelCouv'.$age} = round($countryData->{'ageRappelTot'.$age} / $countryData->{'agePop'.$age} * 100, 1);
                }
            }

            foreach ($this->france->getRegions() as $region) {
                $regionData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);

                foreach ($ages as $age) {
                    if ($regionData->{'agePop'.$age} !== null) {
                        $regionData->{'ageDebutCouv'.$age} = round($regionData->{'ageDebutTot'.$age} / $regionData->{'agePop'.$age} * 100, 1);
                        $regionData->{'ageCompletCouv'.$age} = round($regionData->{'ageCompletTot'.$age} / $regionData->{'agePop'.$age} * 100, 1);
                        $regionData->{'ageRappelCouv'.$age} = round($regionData->{'ageRappelTot'.$age} / $regionData->{'agePop'.$age} * 100, 1);
                    }
                }

                foreach ($this->france->getDepartmentsForRegion($region) as $department) {
                    $departmentData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);

                    foreach ($ages as $age) {
                        if ($departmentData->{'agePop'.$age} !== null) {
                            $departmentData->{'ageDebutCouv'.$age} = round($departmentData->{'ageDebutTot'.$age} / $departmentData->{'agePop'.$age} * 100, 1);
                            $departmentData->{'ageCompletCouv'.$age} = round($departmentData->{'ageCompletTot'.$age} / $departmentData->{'agePop'.$age} * 100, 1);
                            $departmentData->{'ageRappelCouv'.$age} = round($departmentData->{'ageRappelTot'.$age} / $departmentData->{'agePop'.$age} * 100, 1);
                        }
                    }
                }
            }
        });
    }
}
