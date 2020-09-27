<?php

namespace CovidDashboardFrance;

class France
{
    /** @var Department[] */
    protected $departments = array();

    /** @var Region[] */
    protected $regions = array();

    /**
     * France constructor.
     *
     * @param $depatmentsDataUrl
     * @param $regionsDataUrl
     *
     * @throws \Exception
     */
    public function __construct($depatmentsDataUrl, $regionsDataUrl)
    {
        $this->populateDepartments($depatmentsDataUrl);
        $this->populateRegions($regionsDataUrl);
    }

    /**
     * @return Department[]
     */
    public function getDepartments(): array
    {
        return $this->departments;
    }

    /**
     * @param int|string $number
     *
     * @return Department|null
     */
    public function getDepartment($number): ?Department
    {
        foreach ($this->departments as $department) {
            if ($department->number == $number) {
                return $department;
            }
        }

        return null;
    }

    /**
     * @return Region[]
     */
    public function getRegions(): array
    {
        return $this->regions;
    }

    /**
     * @param int|string $number
     *
     * @return Region|null
     */
    public function getRegion($number): ?Region
    {
        foreach ($this->regions as $region) {
            if ($region->number == $number) {
                return $region;
            }
        }

        return null;
    }

    /**
     * @param Department $department
     *
     * @return Region|null
     */
    public function getRegionForDepartment(Department $department): ?Region
    {
        foreach ($this->regions as $region) {
            if ($region->number == $department->number) {
                return $region;
            }
        }

        return null;
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateDepartments($dataUrl)
    {
        $data = $this->getDataFromZipUrl($dataUrl);

        $this->departments = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, "	");
            if (count($row) !== 6) { continue; }
            $department = new Department($row[1], $row[0], utf8_encode($row[5]));

            $this->departments[] = $department;
        }

        usort($this->departments, function ($a, $b) {
            $aNumber = $a->number;
            $bNumber = $b->number;

            if ($a->number == '2A' || $a->number == '2B') { $aNumber = 20; }
            if ($b->number == '2A' || $b->number == '2B') { $bNumber = 20; }

            return $aNumber - $bNumber;
        });
    }

    /**
     * @param $dataUrl
     *
     * @throws \Exception
     */
    protected function populateRegions($dataUrl)
    {
        $data = $this->getDataFromZipUrl($dataUrl);

        $this->regions = array();

        $i = 0;
        foreach ($data as $row) {
            $i += 1;

            if ($i === 1) { continue; } // skipping header

            $row = str_getcsv($row, "	");
            if (count($row) !== 5) { continue; }
            $region = new Region($row[0], utf8_encode($row[4]));

            $this->regions[] = $region;
        }

        usort($this->regions, function ($a, $b) {
            return $a->number - $b->number;
        });
    }

    /**
     * @param $dataUrl
     *
     * @return false|string[]
     * @throws \Exception
     */
    protected function getDataFromZipUrl($dataUrl)
    {
        $data = file_get_contents($dataUrl);
        if ($data === false) {
            throw new \Exception('Could not read regions data at '.$dataUrl);
        }

        $fileUrl = 'tmp.zip';
        file_put_contents($fileUrl, $data);

        $zip = new \ZipArchive;
        $res = $zip->open($fileUrl);
        if ($res === TRUE) {
            $data = $zip->getFromIndex(0);
            $zip->close();
        } else {
            unlink($fileUrl);
            throw new \Exception('Could not read regions from zip');
        }

        unlink($fileUrl);

        $data = explode("\n", $data);

        return $data;
    }
}
