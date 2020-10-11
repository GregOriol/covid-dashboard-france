<?php

namespace CovidDashboardFrance;

class Covid
{
    /** @var Data[] */
    public $data = array();

    public $refs = array();

    /**
     * Covid constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {

    }

    /**
     * @param \DateTime   $date
     * @param string      $country
     * @param string|null $region
     * @param string|null $department
     *
     * @return Data
     */
    public function getDataForDate(\DateTime $date, string $country, ?string $region, ?string $department): Data
    {
        $key = $date->format('Y-m-d').'#'.$country.'#'.$region.'#'.$department;
        if (array_key_exists($key, $this->refs)) {
            return $this->refs[$key];
        }

        $data = new Data($date, $country, $region, $department);
        $this->data[] = &$data;
        $this->refs[$key] = &$data;

        return $data;
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateTests($dataUrl)
    {
        $data = file_get_contents($dataUrl);
        if ($data === false) {
            throw new \Exception('Could not read tests data at '.$dataUrl);
        }
        $data = str_replace("\r", '', $data);
        $data = explode("\n", $data);

        $this->tests = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ";");
            if (count($row) !== 5) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data
            $tests = new Tests(new \DateTime($row[1]), $row[0], (int)$row[2], (int)$row[3], (float)$row[4]);

            $this->tests[] = $tests;
        }

        usort($this->tests, [$this, 'cmpFunction']);
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateIndicateurs($dataUrl)
    {
        $data = file_get_contents($dataUrl);
        if ($data === false) {
            throw new \Exception('Could not read indicateurs data at '.$dataUrl);
        }
        $data = str_replace("\r", '', $data);
        $data = explode("\n", $data);

        $this->indicateurs = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ",");
            if (count($row) !== 15) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data
            $indicateurs = new Indicateurs(
                new \DateTime($row[0]),
                $row[1],
                ($row[5] != 'NA' ? (float)$row[5] : null),
                ($row[6] != 'NA' ? (float)$row[6] : null),
                ($row[7] != 'NA' ? (float)$row[7] : null),
                ($row[8] != 'NA' ? (float)$row[8] : null),
                ($row[9] != '' ? $row[9] : null),
                ($row[10] != '' ? $row[10] : null),
                ($row[11] != '' ? $row[11] : null),
                ($row[12] != '' ? $row[12] : null)
            );

            $this->indicateurs[] = $indicateurs;
        }

        usort($this->indicateurs, [$this, 'cmpFunction']);
    }

    protected static function cmpFunction($a, $b) {
        $departmentA = $a->department;
        if ($departmentA == '2A' || $departmentA == '2B') { $departmentA = 20; } else { $departmentA = (int)$departmentA; }
        $departmentB = $b->department;
        if ($departmentB == '2A' || $departmentB == '2B') { $departmentB = 20; } else { $departmentB = (int)$departmentB; }

        $result = $departmentA <=> $departmentB;
        if ($result === 0) {
            $dateA = (int)$a->date->format('Ymd');
            $dateB = (int)$b->date->format('Ymd');

            $result = $dateA <=> $dateB;
            if ($result === 0) {
                //
            }
        }

        return $result;
    }
}
