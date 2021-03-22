<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class Vacsi
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
                if (count($row) !== 8) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc = $row[0];
                $jour = $row[1];
                $dose1 = $row[2];
                $dose2 = $row[3];
                if ($type === 'country') {
                    $dose1Tot  = $row[4];
                    $dose1Couv = $row[5];
                    $dose2Tot  = $row[6];
                    $dose2Couv = $row[7];
                } elseif ($type === 'region' || $type === 'department') {
                    $dose1Tot  = $row[4];
                    $dose1Couv = $row[6];
                    $dose2Tot  = $row[5];
                    $dose2Couv = $row[7];
                }

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

                $data->dose1 = (int)$dose1;
                $data->dose2 = (int)$dose2;
                $data->dose1Tot = (int)$dose1Tot;
                $data->dose1Couv = (float)$dose1Couv;
                $data->dose2Tot = (int)$dose2Tot;
                $data->dose2Couv = (float)$dose2Couv;
            }
        }
    }

    public function generateConsolidations()
    {
        // nothing to do
    }
}
