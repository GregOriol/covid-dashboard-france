<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class Indicateurs
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
    public function populateData(string $dataDepUrl, string $dataFraUrl)
    {
        //
        $data = file_get_contents($dataDepUrl);
        if ($data === false) {
            throw new \Exception('Could not read total data at '.$dataDepUrl);
        }
        $data = str_replace("\r", '', $data);
        $data = explode("\n", $data);

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ',');
            if (count($row) !== 15) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data

            $jour = $row[0];
            $dep = $row[1];
            $reg = $row[2];

            $tx = $row[5];
            $r = $row[6];
            $occup = $row[7];
            $txPos = $row[8];

            $txColor = $row[9];
            $rColor = $row[10];
            $occupColor = $row[11];
            $txPosColor = $row[12];

            $nbOrange = $row[13];
            $nbRouge = $row[14];

            $department = $this->france->getDepartment($dep);
            if (!$department) { continue; }
            $region = $this->france->getRegionForDepartment($department);
            if (!$region) { continue; }

            $date = new \DateTime($jour);

            $depData = $this->covid->getDataForDate($date, 'FRA', $region->number, $department->number);
            if (!$depData) { continue; }

            $regData = $this->covid->getDataForDate($date, 'FRA', $region->number, null);
            if (!$regData) { continue; }

            $depData->consolTx = ($tx !== 'NA') ? (float)$tx : null;
            $depData->consolTxPos = ($txPos !== 'NA') ? (float)$txPos : null;
            $depData->r = ($r !== 'NA') ? (float)$r : null;
            $depData->occup = ($occup !== 'NA') ? (float)$occup : null;

            $regData->r = ($r !== 'NA') ? (float)$r : null;
            $regData->occup = ($occup !== 'NA') ? (float)$occup : null;
        }

        //
        $data = file_get_contents($dataFraUrl);
        if ($data === false) {
            throw new \Exception('Could not read total data at '.$dataFraUrl);
        }
        $data = str_replace("\r", '', $data);
        $data = explode("\n", $data);

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ',');
            if (count($row) !== 5) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data

            $jour = $row[0];
            $tx = $row[1];
            $r = $row[2];
            $occup = $row[3];
            $txPos = $row[4];

            $date = new \DateTime($jour);

            $data = $this->covid->getDataForDate($date, 'FRA', null, null);
            if (!$data) { continue; }

            $data->consolTx = ($tx !== 'NA') ? (float)$tx : null;
            $data->consolTxPos = ($txPos !== 'NA') ? (float)$txPos : null;
            $data->r = ($r !== 'NA') ? (float)$r : null;
            $data->occup = ($occup !== 'NA') ? (float)$occup : null;
        }
    }

    public function generateConsolidations()
    {
        // nothing to do
    }
}
