<?php

require_once 'src/CovidDashboardFrance/Department.php';
require_once 'src/CovidDashboardFrance/Region.php';
require_once 'src/CovidDashboardFrance/France.php';

require_once 'src/CovidDashboardFrance/Covid.php';
require_once 'src/CovidDashboardFrance/Total.php';
require_once 'src/CovidDashboardFrance/Incidence.php';

// Source: https://www.insee.fr/fr/information/3363419#titre-bloc-23
// https://www.insee.fr/fr/statistiques/fichier/3363419/depts2018-txt.zip
// https://www.insee.fr/fr/statistiques/fichier/3363419/reg2018-txt.zip

$depatmentsDataUrl = 'https://www.insee.fr/fr/statistiques/fichier/3363419/depts2018-txt.zip';
$regionsDataUrl = 'https://www.insee.fr/fr/statistiques/fichier/3363419/reg2018-txt.zip';

// Use the following lines (or any better implementation) to cache the json data instead of downloading it each time
$cachedDepatmentsDataUrl = 'depts.zip';
if (!file_exists($cachedDepatmentsDataUrl)) {
   $depatmentsContents = file_get_contents($depatmentsDataUrl);
   file_put_contents($cachedDepatmentsDataUrl, $depatmentsContents);
}
$depatmentsDataUrl = $cachedDepatmentsDataUrl;

$cachedRegionsDataPath = 'regs.zip';
if (!file_exists($cachedRegionsDataPath)) {
   $regionsContents = file_get_contents($regionsDataUrl);
   file_put_contents($cachedRegionsDataPath, $regionsContents);
}
$regionsDataUrl = $cachedRegionsDataPath;

$france = new \CovidDashboardFrance\France($depatmentsDataUrl, $regionsDataUrl);

// Source: https://www.data.gouv.fr/fr/datasets/donnees-hospitalieres-relatives-a-lepidemie-de-covid-19/#resource-6fadff46-9efd-4c53-942a-54aca783c30c
// https://www.data.gouv.fr/fr/datasets/r/63352e38-d353-4b54-bfd1-f1b3ee1cabd7
// https://www.data.gouv.fr/fr/datasets/r/6fadff46-9efd-4c53-942a-54aca783c30c

$totalDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/63352e38-d353-4b54-bfd1-f1b3ee1cabd7';
$incidenceDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/6fadff46-9efd-4c53-942a-54aca783c30c';

$cachedTotalDataPath = 'total.csv';
if (!file_exists($cachedTotalDataPath)) {
    $totalContents = file_get_contents($totalDataUrl);
    file_put_contents($cachedTotalDataPath, $totalContents);
}
$totalDataUrl = $cachedTotalDataPath;

$cachedIncidenceDataPath = 'incidence.csv';
if (!file_exists($cachedIncidenceDataPath)) {
    $incidenceContents = file_get_contents($incidenceDataUrl);
    file_put_contents($cachedIncidenceDataPath, $incidenceContents);
}
$incidenceDataUrl = $cachedIncidenceDataPath;

$covid = new \CovidDashboardFrance\Covid($totalDataUrl, $incidenceDataUrl);

//var_dump($covid->getTotal());
//var_dump($covid->getTotalConsolidated());

$data = array(
    'france' => [
        'type' => 'country',
        'id' => 'france',
        'name' => 'France',
        'total' => [
            'hosp' => [],
            'rea' => [],
            'rad' => [],
            'dc' => [],
        ],
        'incidence' => [
            'hosp' => [],
            'rea' => [],
            'rad' => [],
            'dc' => [],
        ]
    ]
);

foreach ($france->getRegions() as $region) {
    $data['reg'.$region->number] = [
        'type' => 'region',
        'id' => $region->number,
        'name' => $region->name,
        'total' => [
            'hosp' => [],
            'rea' => [],
            'rad' => [],
            'dc' => [],
        ],
        'incidence' => [
            'hosp' => [],
            'rea' => [],
            'rad' => [],
            'dc' => [],
        ]
    ];
}

foreach ($data as $datasetKey => $datasetValues) {
    foreach ($covid->getTotalConsolidated() as $c) {
        $k = $c->date->format('Y-m-d');

        foreach ($data[$datasetKey]['total'] as $indicator => $v) {
            if (!array_key_exists($k, $data[$datasetKey]['total'][$indicator])) {
                $data[$datasetKey]['total'][$indicator][$k] = 0;
            }

            if ($datasetValues['type'] == 'region') {
                if ($france->getDepartment($c->department) === null) {
                    var_dump($c);
                }
                if ($france->getDepartment($c->department)->region != $datasetValues['id']) {
                    continue;
                }
            }

            $data[$datasetKey]['total'][$indicator][$k] += $c->$indicator;
        }
    }

    foreach ($data[$datasetKey]['total'] as $k => $v) {
        $data[$datasetKey]['total'][$k] = [
            'x'      => array_keys($v),
            'values' => array_values($v),
        ];
    }

    foreach ($covid->getIncidence() as $c) {
        $k = $c->date->format('Y-m-d');

        foreach ($data[$datasetKey]['incidence'] as $indicator => $v) {
            if (!array_key_exists($k, $data[$datasetKey]['incidence'][$indicator])) {
                $data[$datasetKey]['incidence'][$indicator][$k] = 0;
            }

            if ($datasetValues['type'] == 'region') {
                if ($france->getDepartment($c->department)->region != $datasetValues['id']) {
                    continue;
                }
            }

            $data[$datasetKey]['incidence'][$indicator][$k] += $c->$indicator;
        }
    }

    foreach ($data[$datasetKey]['incidence'] as $k => $v) {
        $data[$datasetKey]['incidence'][$k] = [
            'x'      => array_keys($v),
            'values' => array_values($v),
        ];
    }
}

//var_dump(json_encode($data));
//var_dump(json_last_error_msg());

$tpl = file_get_contents('index.tpl');
$tpl = str_replace('[DATA]', json_encode($data), $tpl);

file_put_contents('index.html', $tpl);
