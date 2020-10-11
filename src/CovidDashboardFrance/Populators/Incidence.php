<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class Incidence
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
            if (count($row) !== 6) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data

            $dep = $row[0];
            $jour = $row[1];
            $hosp = $row[2];
            $rea = $row[3];
            $dc = $row[4];
            $rad = $row[5];

            $department = $this->france->getDepartment($dep);
            if (!$department) { continue; }
            $region = $this->france->getRegionForDepartment($department);
            if (!$region) { continue; }

            $date = new \DateTime($jour);
            
            $data = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
            if (!$data) { continue; }

            $data->incidenceHosp = (int)$hosp;
            $data->incidenceRea = (int)$rea;
            $data->incidenceRad = (int)$rad;
            $data->incidenceDc = (int)$dc;
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

                    Helpers::incrBy($regionData->incidenceHosp, $departmentData->incidenceHosp);
                    Helpers::incrBy($regionData->incidenceRea, $departmentData->incidenceRea);
                    Helpers::incrBy($regionData->incidenceRad, $departmentData->incidenceRad);
                    Helpers::incrBy($regionData->incidenceDc, $departmentData->incidenceDc);

                    Helpers::incrBy($countryData->incidenceHosp, $departmentData->incidenceHosp);
                    Helpers::incrBy($countryData->incidenceRea, $departmentData->incidenceRea);
                    Helpers::incrBy($countryData->incidenceRad, $departmentData->incidenceRad);
                    Helpers::incrBy($countryData->incidenceDc, $departmentData->incidenceDc);
                }
            }
        });
    }
}
