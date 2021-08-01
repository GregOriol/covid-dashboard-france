<?php

namespace CovidDashboardFrance\Populators;

use CovidDashboardFrance\Covid;
use CovidDashboardFrance\France;
use CovidDashboardFrance\Helpers;

class VacsiAge
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
                if (count($row) !== 9) { continue; }
                if ($row[0] === '') { continue; } // cleaning invalid data

                $loc      = $row[0];
                $age      = $row[1];
                $jour     = $row[2];
                $dose1    = $row[3];
                $dose2    = $row[4];
                $dose1Tot = $row[5];
                $dose2Tot = $row[6];

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

                if ($age === '9') {
                    $age = '09'; // fixing value
                }

                // Trying to match age ranges from other datasets
                if ($age === '17') {
                    Helpers::incrBy($data->{'ageDose1'.'19'}, $dose1);
                    Helpers::incrBy($data->{'ageDose2'.'19'}, $dose2);
                    Helpers::incrBy($data->{'ageDose1Tot'.'19'}, $dose1Tot);
                    Helpers::incrBy($data->{'ageDose2Tot'.'19'}, $dose2Tot);
                } else if ($age === '24') {
                    // Within the 18-24 age range, about 30% are 18-19 and 70% are 20-24
                    Helpers::incrBy($data->{'ageDose1'.'19'}, round((int)$dose1 * 0.30));
                    Helpers::incrBy($data->{'ageDose2'.'19'}, round((int)$dose2 * 0.30));
                    Helpers::incrBy($data->{'ageDose1Tot'.'19'}, round((int)$dose1Tot * 0.30));
                    Helpers::incrBy($data->{'ageDose2Tot'.'19'}, round((int)$dose2Tot * 0.30));

                    Helpers::incrBy($data->{'ageDose1'.'29'}, round((int)$dose1 * 0.70));
                    Helpers::incrBy($data->{'ageDose2'.'29'}, round((int)$dose2 * 0.70));
                    Helpers::incrBy($data->{'ageDose1Tot'.'29'}, round((int)$dose1Tot * 0.70));
                    Helpers::incrBy($data->{'ageDose2Tot'.'29'}, round((int)$dose2Tot * 0.70));
                } else if ($age === '29') {
                    Helpers::incrBy($data->{'ageDose1'.'29'}, (int)$dose1);
                    Helpers::incrBy($data->{'ageDose2'.'29'}, (int)$dose2);
                    Helpers::incrBy($data->{'ageDose1Tot'.'29'}, (int)$dose1Tot);
                    Helpers::incrBy($data->{'ageDose2Tot'.'29'}, (int)$dose2Tot);
                } else if ($age === '64' || $age === '69') {
                    Helpers::incrBy($data->{'ageDose1'.'69'}, (int)$dose1);
                    Helpers::incrBy($data->{'ageDose2'.'69'}, (int)$dose2);
                    Helpers::incrBy($data->{'ageDose1Tot'.'69'}, (int)$dose1Tot);
                    Helpers::incrBy($data->{'ageDose2Tot'.'69'}, (int)$dose2Tot);
                } else if ($age === '74' || $age === '79') {
                    Helpers::incrBy($data->{'ageDose1'.'79'}, (int)$dose1);
                    Helpers::incrBy($data->{'ageDose2'.'79'}, (int)$dose2);
                    Helpers::incrBy($data->{'ageDose1Tot'.'79'}, (int)$dose1Tot);
                    Helpers::incrBy($data->{'ageDose2Tot'.'79'}, (int)$dose2Tot);
                } else if ($age === '80') {
                    // Within the 80+ age range, about 78% are 80-89 and 22% are 90+
                    $data->{'ageDose1'.'89'} = round((int)$dose1 * 0.78);
                    $data->{'ageDose2'.'89'} = round((int)$dose2 * 0.78);
                    $data->{'ageDose1Tot'.'89'} = round((int)$dose1Tot * 0.78);
                    $data->{'ageDose2Tot'.'89'} = round((int)$dose2Tot * 0.78);

                    $data->{'ageDose1'.'90'} = round((int)$dose1 * 0.22);
                    $data->{'ageDose2'.'90'} = round((int)$dose2 * 0.22);
                    $data->{'ageDose1Tot'.'90'} = round((int)$dose1Tot * 0.22);
                    $data->{'ageDose2Tot'.'90'} = round((int)$dose2Tot * 0.22);
                } else {
                    $data->{'ageDose1'.$age} = (int)$dose1;
                    $data->{'ageDose2'.$age} = (int)$dose2;
                    $data->{'ageDose1Tot'.$age} = (int)$dose1Tot;
                    $data->{'ageDose2Tot'.$age} = (int)$dose2Tot;
                }
            }
        }
    }

    public function generateConsolidations()
    {
        // nothing to do
    }
}
