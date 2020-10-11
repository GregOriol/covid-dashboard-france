<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class Tests
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
                if (count($row) !== 5) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc = $row[0];
                $jour = $row[1];
                $pop = $row[2];
                $p = $row[3];
                $tx = $row[4];

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

                $data->pop = (int)$pop;
                $data->p = (int)$p;
                $data->tx = (float)$tx;
            }
        }
    }

    public function generateConsolidations()
    {
        // Calculating tx7
        Helpers::dateIterator(function ($date) {
            /** @var \DateTime $date */

            $countryData = $this->covid->getDataForDate($date, 'FRA', null, null);
            if ($countryData->tx !== null) {
                Helpers::incrBy($countryData->tx7, $countryData->tx);

                $dateIterCountry = clone $date;
                for ($i = 1; $i <= 6; $i++) {
                    $countryDataIter = $this->covid->getDataForDate($dateIterCountry->modify('-1 day'), 'FRA', null, null);
                    Helpers::incrBy($countryData->tx7, $countryDataIter->tx);
                }
            }

            foreach ($this->france->getRegions() as $region) {
                $regionData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);
                if ($regionData->tx !== null) {
                    Helpers::incrBy($regionData->tx7, $regionData->tx);

                    $dateIterRegion = clone $date;
                    for ($i = 1; $i <= 6; $i++) {
                        $regionDataIter = $this->covid->getDataForDate($dateIterRegion->modify('-1 day'), 'FRA', $region->number, null);
                        Helpers::incrBy($regionData->tx7, $regionDataIter->tx);
                    }
                }

                foreach ($this->france->getDepartmentsForRegion($region) as $department) {
                    $departmentData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);

                    if ($departmentData->tx !== null) {
                        Helpers::incrBy($departmentData->tx7, $departmentData->tx);

                        $dateIterDepartment = clone $date;
                        for ($i = 1; $i <= 6; $i++) {
                            $departmentDataIter = $this->covid->getDataForDate($dateIterDepartment->modify('-1 day'), 'FRA', $region->number, $department->number);
                            Helpers::incrBy($departmentData->tx7, $departmentDataIter->tx);
                        }
                    }
                }
            }
        });
    }
}
