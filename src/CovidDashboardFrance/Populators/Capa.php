<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class Capa
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
                if (count($row) !== 4) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc = $row[0];
                $jour = $row[1];
                $pop = $row[2];
                $t = $row[3];

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

                //$data->pop = (int)$pop;
                $data->t = (int)$t;
            }
        }
    }

    public function generateConsolidations()
    {
        // Calculating txPos
        Helpers::dateIterator(function ($date) {
            /** @var \DateTime $date */

            $countryData = $this->covid->getDataForDate($date, 'FRA', null, null);
            if ($countryData->t !== null && $countryData->p !== null && $countryData->t > 0) {
                $countryData->txPos = $countryData->p / $countryData->t * 100;
            }

            foreach ($this->france->getRegions() as $region) {
                $regionData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);
                if ($regionData->t !== null && $regionData->p !== null && $regionData->t > 0) {
                    $regionData->txPos = $regionData->p / $regionData->t * 100;
                }

                foreach ($this->france->getDepartmentsForRegion($region) as $department) {
                    $departmentData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
                    if ($departmentData->t !== null && $departmentData->p !== null && $departmentData->t > 0) {
                        $departmentData->txPos = $departmentData->p / $departmentData->t * 100;
                    }
                }
            }
        });

        // Calculating txPos7
        Helpers::dateIterator(function ($date) {
            /** @var \DateTime $date */

            $countryData = $this->covid->getDataForDate($date, 'FRA', null, null);
            $countryP = null;
            $countryT = null;
            Helpers::incrBy($countryP, $countryData->p);
            Helpers::incrBy($countryT, $countryData->t);

            if ($countryP !== null && $countryT !== null) {
                $dateIterCountry = clone $date;
                for ($i = 1; $i <= 6; $i++) {
                    $countryDataIter = $this->covid->getDataForDate($dateIterCountry->modify('-1 day'), 'FRA', null, null);
                    Helpers::incrBy($countryP, $countryDataIter->p);
                    Helpers::incrBy($countryT, $countryDataIter->t);
                }
                if ($countryT !== null && $countryP !== null && $countryT > 0) {
                    $countryData->txPos7 = $countryP / $countryT * 100;
                }
            }

            foreach ($this->france->getRegions() as $region) {
                $regionData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);
                $regionP = null;
                $regionT = null;
                Helpers::incrBy($regionP, $regionData->p);
                Helpers::incrBy($regionT, $regionData->t);

                if ($regionP !== null && $regionT !== null) {
                    $dateIterRegion = clone $date;
                    for ($i = 1; $i <= 6; $i++) {
                        $regionDataIter = $this->covid->getDataForDate($dateIterRegion->modify('-1 day'), 'FRA', $region->number, null);
                        Helpers::incrBy($regionP, $regionDataIter->p);
                        Helpers::incrBy($regionT, $regionDataIter->t);
                    }
                    if ($regionT !== null && $regionP !== null && $regionT > 0) {
                        $regionData->txPos7 = $regionP / $regionT * 100;
                    }
                }

                foreach ($this->france->getDepartmentsForRegion($region) as $department) {
                    $departmentData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
                    $departmentP = null;
                    $departmentT = null;
                    Helpers::incrBy($departmentP, $departmentData->p);
                    Helpers::incrBy($departmentT, $departmentData->t);

                    if ($departmentP !== null && $departmentT !== null) {
                        $dateIterDepartment = clone $date;
                        for ($i = 1; $i <= 6; $i++) {
                            $departmentDataIter = $this->covid->getDataForDate($dateIterDepartment->modify('-1 day'), 'FRA', $region->number, $department->number);
                            Helpers::incrBy($departmentP, $departmentDataIter->p);
                            Helpers::incrBy($departmentT, $departmentDataIter->t);
                        }
                        if ($departmentT !== null && $departmentP !== null && $departmentT > 0) {
                            $departmentData->txPos7 = $departmentP / $departmentT * 100;
                        }
                    }
                }
            }
        });
    }
}
