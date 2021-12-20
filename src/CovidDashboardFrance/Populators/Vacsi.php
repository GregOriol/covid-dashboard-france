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
                if (count($row) !== 11) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc = $row[0];
                $jour = $row[1];
                $debut = $row[2];
                $complet = $row[3];
                $rappel = $row[4];
                $debutTot  = $row[5];
                $completTot  = $row[6];
                $rappelTot  = $row[7];
                $debutCouv = $row[8];
                $completCouv = $row[9];
                $rappelCouv = $row[10];

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

                $data->debut = (int)$debut;
                $data->complet = (int)$complet;
                $data->rappel = (int)$rappel;
                $data->debutTot = (int)$debutTot;
                $data->completTot = (int)$completTot;
                $data->rappelTot = (int)$rappelTot;
                $data->debutCouv = (float)$debutCouv;
                $data->completCouv = (int)$completCouv;
                $data->rappelCouv = (float)$rappelCouv;
            }
        }
    }

    public function generateConsolidations()
    {
        // nothing to do
    }
}
