<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class HospTotal
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
        $data = str_replace('27/06/2020', '2020-06-27', $data); // cleaning invalid data
        $data = str_replace('28/06/2020', '2020-06-28', $data);
        $data = str_replace('29/06/2020', '2020-06-29', $data);
        $data = explode("\n", $data);

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ';');
            if (count($row) !== 10) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data

            $dep = $row[0];
            $sexe = $row[1];
            $jour = $row[2];
            $hosp = $row[3];
            $rea = $row[4];
            $rad = $row[8];
            $dc = $row[9];

            if ($sexe !== '0') { continue; } // only taking into account data for all sexes

            $department = $this->france->getDepartment($dep);
            if (!$department) { continue; }
            $region = $this->france->getRegionForDepartment($department);
            if (!$region) { continue; }

            $date = new \DateTime($jour);
            
            $data = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
            if (!$data) { continue; }

            $data->hosp = (int)$hosp;
            $data->rea = (int)$rea;
            $data->rad = (int)$rad;
            $data->dc = (int)$dc;
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

                    Helpers::incrBy($regionData->hosp, $departmentData->hosp);
                    Helpers::incrBy($regionData->rea, $departmentData->rea);
                    Helpers::incrBy($regionData->rad, $departmentData->rad);
                    Helpers::incrBy($regionData->dc, $departmentData->dc);

                    Helpers::incrBy($countryData->hosp, $departmentData->hosp);
                    Helpers::incrBy($countryData->rea, $departmentData->rea);
                    Helpers::incrBy($countryData->rad, $departmentData->rad);
                    Helpers::incrBy($countryData->dc, $departmentData->dc);
                }
            }
        });
    }
}
