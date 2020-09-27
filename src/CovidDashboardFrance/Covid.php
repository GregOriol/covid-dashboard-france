<?php

namespace CovidDashboardFrance;

class Covid
{
    /** @var Total[] */
    protected $total = array();

    /** @var Incidence[] */
    protected $incidence = array();

    /**
     * France constructor.
     *
     * @param $totalDataUrl
     * @param $incidenceDataUrl
     *
     * @throws \Exception
     */
    public function __construct($totalDataUrl, $incidenceDataUrl)
    {
        $this->populateTotal($totalDataUrl);
        $this->populateIncidence($incidenceDataUrl);
    }

    /**
     * @return Total[]
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sums data by sex for each department
     *
     * @return Total[]
     */
    public function getTotalConsolidated()
    {
        $consolidated = array();
        foreach ($this->total as $total) {
            if ($total->sex === 0) {
                $consolidated[] = $total;
            }
        }

        return $consolidated;
    }

    /**
     * @return Incidence[]
     */
    public function getIncidence()
    {
        return $this->incidence;
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateTotal($dataUrl)
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

        $this->total = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ";");
            if (count($row) !== 7) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data
            $total = new Total(new \DateTime($row[2]), $row[0], (int)$row[1], (int)$row[3], (int)$row[4], (int)$row[5], (int)$row[6]);

            $this->total[] = $total;
        }
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateIncidence($dataUrl)
    {
        $data = file_get_contents($dataUrl);
        if ($data === false) {
            throw new \Exception('Could not read incidence data at '.$dataUrl);
        }
        $data = str_replace("\r", '', $data);
        $data = explode("\n", $data);

        $this->incidence = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, ";");
            if (count($row) !== 6) { continue; }
            if ($row[0] === '') { continue; } // cleaning invalid data
            $incidence = new Incidence(new \DateTime($row[1]), $row[0], (int)$row[2], (int)$row[3], (int)$row[4], (int)$row[5]);

            $this->incidence[] = $incidence;
        }
    }
}
