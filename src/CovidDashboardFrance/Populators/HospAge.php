<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class HospAge
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
     * @param $dataUrl
     *
     * @throws \Exception
     */
    public function populateData(string $dataUrl)
    {
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
            if (count($row) !== 10) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data

            $dep = $row[0];
            $age = $row[1];
            $jour = $row[2];
            $hosp = $row[3];
            $rea = $row[4];
            $rad = $row[8];
            $dc = $row[9];

            if ($age === '0') { continue; } // only taking into account data for ages and not 0 which is the sum of them

            $department = $this->france->getDepartment($dep);
            if (!$department) { continue; }
            $region = $this->france->getRegionForDepartment($department);
            if (!$region) { continue; }

            $date = new \DateTime($jour);
            
            $data = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
            if (!$data) { continue; }

            $data->{'ageHosp'.$age} = (int)$hosp;
            $data->{'ageRea'.$age} = (int)$rea;
            $data->{'ageRad'.$age} = (int)$rad;
            $data->{'ageDc'.$age} = (int)$dc;
        }
    }

    public function generateConsolidations()
    {
        Helpers::dateIterator(function ($date) {
            $countryData = $this->covid->getDataForDate($date, 'FRA', null, null);

            foreach ($this->france->getRegions() as $region) {
                $regionData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);

                foreach ($this->france->getDepartmentsForRegion($region) as $department) {
                    $departmentData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);

                    Helpers::agesIterator(function ($age) use (&$countryData, &$regionData, &$departmentData) {
                        Helpers::incrBy($regionData->{'ageHosp'.$age}, $departmentData->{'ageHosp'.$age});
                        Helpers::incrBy($regionData->{'ageRea'.$age}, $departmentData->{'ageRea'.$age});
                        Helpers::incrBy($regionData->{'ageRad'.$age}, $departmentData->{'ageRad'.$age});
                        Helpers::incrBy($regionData->{'ageDc'.$age}, $departmentData->{'ageDc'.$age});

                        Helpers::incrBy($countryData->{'ageHosp'.$age}, $departmentData->{'ageHosp'.$age});
                        Helpers::incrBy($countryData->{'ageRea'.$age}, $departmentData->{'ageRea'.$age});
                        Helpers::incrBy($countryData->{'ageRad'.$age}, $departmentData->{'ageRad'.$age});
                        Helpers::incrBy($countryData->{'ageDc'.$age}, $departmentData->{'ageDc'.$age});
                    });
                }
            }
        });
    }
}
