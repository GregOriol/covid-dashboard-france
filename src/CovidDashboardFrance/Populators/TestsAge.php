<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class TestsAge
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
                if ($type === 'department' && count($row) !== 5) { continue; }
                if (($type === 'region' || $type === 'country') && count($row) !== 9) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                if ($type === 'department') {
                    $loc  = $row[0];
                    $jour = $row[1];
                    $pop  = $row[2];
                    $p    = $row[3];
                    $age  = $row[4];
                }
                if ($type === 'region' || $type === 'country') {
                    $loc  = $row[0];
                    $jour = $row[1];
                    $p    = $row[4];
                    $pop  = $row[7];
                    $age  = $row[8];
                }

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

                $data->{'ageP'.$age} = (int)$p;
            }
        }
    }

    public function generateConsolidations()
    {
        // nothing to do
    }
}
