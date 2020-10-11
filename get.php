<?php

use CovidDashboardFrance\Helpers;

require_once 'src/CovidDashboardFrance/Helpers.php';

require_once 'src/CovidDashboardFrance/Department.php';
require_once 'src/CovidDashboardFrance/Region.php';
require_once 'src/CovidDashboardFrance/France.php';

require_once 'src/CovidDashboardFrance/Covid.php';
require_once 'src/CovidDashboardFrance/Data.php';

require_once 'src/CovidDashboardFrance/Populators/Total.php';
require_once 'src/CovidDashboardFrance/Populators/Incidence.php';
require_once 'src/CovidDashboardFrance/Populators/Taux.php';

//
// Preparing data about France
//

// Source: https://www.insee.fr/fr/information/3363419#titre-bloc-23
// https://www.insee.fr/fr/statistiques/fichier/3363419/depts2018-txt.zip
// https://www.insee.fr/fr/statistiques/fichier/3363419/reg2018-txt.zip

$depatmentsDataUrl = 'https://www.insee.fr/fr/statistiques/fichier/3363419/depts2018-txt.zip';
$regionsDataUrl = 'https://www.insee.fr/fr/statistiques/fichier/3363419/reg2018-txt.zip';

// Use the following lines (or any better implementation) to cache the json data instead of downloading it each time
$cachedDepatmentsDataPath = './cache/depts.zip';
if (!file_exists($cachedDepatmentsDataPath)) {
   $depatmentsContents = file_get_contents($depatmentsDataUrl);
   file_put_contents($cachedDepatmentsDataPath, $depatmentsContents);
}
$depatmentsDataUrl = $cachedDepatmentsDataPath;

$cachedRegionsDataPath = './cache/regs.zip';
if (!file_exists($cachedRegionsDataPath)) {
   $regionsContents = file_get_contents($regionsDataUrl);
   file_put_contents($cachedRegionsDataPath, $regionsContents);
}
$regionsDataUrl = $cachedRegionsDataPath;

$france = new \CovidDashboardFrance\France($depatmentsDataUrl, $regionsDataUrl);

//
// Preparing data about Covid
//

// Sources:
// - https://www.data.gouv.fr/fr/datasets/donnees-hospitalieres-relatives-a-lepidemie-de-covid-19
// https://www.data.gouv.fr/fr/datasets/r/63352e38-d353-4b54-bfd1-f1b3ee1cabd7 donnees-hospitalieres-covid19-*.csv
// https://www.data.gouv.fr/fr/datasets/r/6fadff46-9efd-4c53-942a-54aca783c30c donnees-hospitalieres-nouveaux-covid19-*.csv
//
// - https://www.data.gouv.fr/fr/datasets/taux-dincidence-de-lepidemie-de-covid-19
// https://www.data.gouv.fr/fr/datasets/r/4180a181-a648-402b-92e4-f7574647afa6 sp-pe-std-quot-dep-*.csv
// https://www.data.gouv.fr/fr/datasets/r/23066f40-ddd2-40c9-931c-4257f36ad778 sp-pe-std-quot-reg-*.csv
// https://www.data.gouv.fr/fr/datasets/r/59ad717b-b64e-4779-85f6-cd1b25b24703 sp-pe-std-quot-fra-*.csv
//
// - https://www.data.gouv.fr/fr/datasets/indicateurs-de-suivi-de-lepidemie-de-covid-19
// https://www.data.gouv.fr/fr/datasets/r/4acad602-d8b1-4516-bc71-7d5574d5f33e
//
// - https://www.data.gouv.fr/fr/datasets/capacite-analytique-de-tests-virologiques-dans-le-cadre-de-lepidemie-covid-19
// https://www.data.gouv.fr/fr/datasets/r/f02b627a-9f9c-45f9-810d-af49ef98a0b1

$totalDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/63352e38-d353-4b54-bfd1-f1b3ee1cabd7';
$incidenceDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/6fadff46-9efd-4c53-942a-54aca783c30c';
$tauxDepDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/4180a181-a648-402b-92e4-f7574647afa6';
$tauxRegDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/23066f40-ddd2-40c9-931c-4257f36ad778';
$tauxFraDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/59ad717b-b64e-4779-85f6-cd1b25b24703';
$indicateursDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/4acad602-d8b1-4516-bc71-7d5574d5f33e';
$analytiqueDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/f02b627a-9f9c-45f9-810d-af49ef98a0b1';

$cachedTotalDataPath = './cache/total.csv';
if (!file_exists($cachedTotalDataPath)) {
    $totalContents = file_get_contents($totalDataUrl);
    file_put_contents($cachedTotalDataPath, $totalContents);
}
$totalDataUrl = $cachedTotalDataPath;

$cachedIncidenceDataPath = './cache/incidence.csv';
if (!file_exists($cachedIncidenceDataPath)) {
    $incidenceContents = file_get_contents($incidenceDataUrl);
    file_put_contents($cachedIncidenceDataPath, $incidenceContents);
}
$incidenceDataUrl = $cachedIncidenceDataPath;

$cachedTauxDepDataPath = './cache/taux-dep.csv';
if (!file_exists($cachedTauxDepDataPath)) {
    $tauxContents = file_get_contents($tauxDepDataUrl);
    file_put_contents($cachedTauxDepDataPath, $tauxContents);
}
$tauxDepDataUrl = $cachedTauxDepDataPath;

$cachedTauxRegDataPath = './cache/taux-reg.csv';
if (!file_exists($cachedTauxRegDataPath)) {
    $tauxContents = file_get_contents($tauxRegDataUrl);
    file_put_contents($cachedTauxRegDataPath, $tauxContents);
}
$tauxRegDataUrl = $cachedTauxRegDataPath;

$cachedTauxFraDataPath = './cache/taux-fra.csv';
if (!file_exists($cachedTauxFraDataPath)) {
    $tauxContents = file_get_contents($tauxFraDataUrl);
    file_put_contents($cachedTauxFraDataPath, $tauxContents);
}
$tauxFraDataUrl = $cachedTauxFraDataPath;

//$cachedIndicateursDataPath = './cache/indicateurs.csv';
//if (!file_exists($cachedIndicateursDataPath)) {
//    $indicateursContents = file_get_contents($indicateursDataUrl);
//    file_put_contents($cachedIndicateursDataPath, $indicateursContents);
//}
//$indicateursDataUrl = $cachedIndicateursDataPath;
//
//$cachedAnalytiqueDataPath = './cache/analytique.csv';
//if (!file_exists($cachedAnalytiqueDataPath)) {
//    $analytiqueContents = file_get_contents($analytiqueDataUrl);
//    file_put_contents($cachedAnalytiqueDataPath, $analytiqueContents);
//}
//$analytiqueDataUrl = $cachedAnalytiqueDataPath;

$covid = new \CovidDashboardFrance\Covid();

$populator = new \CovidDashboardFrance\Populators\Total($france, $covid);
$populator->populateData($totalDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Incidence($france, $covid);
$populator->populateData($incidenceDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Taux($france, $covid);
$populator->populateData($tauxDepDataUrl, $tauxRegDataUrl, $tauxFraDataUrl);
$populator->generateConsolidations();

//var_dump($covid->data);
//var_dump($covid->refs);

$indicators = array('hosp', 'rea', 'rad', 'dc', 'incidenceHosp', 'incidenceRea', 'incidenceRad', 'incidenceDc', 'pop', 'p', 'tx', 'tx7');

$output = array(
    'x' => [],
);

Helpers::dateIterator(function ($date) use (&$output) {
    $output['x'][] = $date->format('Y-m-d');
});

$dataStruct = array(
    'type' => null,
    'id' => null,
    'name' => null,
);
foreach ($indicators as $indicator) {
    $dataStruct[$indicator] = [];
}

$output['fra'] = $dataStruct;
$output['fra']['type'] = 'country';
$output['fra']['id']   = 'fra';
$output['fra']['name'] = 'France';

foreach ($france->getRegions() as $region) {
    $key = 'reg'.$region->number;
    $output[$key] = $dataStruct;
    $output[$key]['type'] = 'region';
    $output[$key]['id']   = $region->number;
    $output[$key]['name'] = $region->name;
}
foreach ($france->getDepartments() as $department) {
    $key = 'dep'.$department->number;
    $output[$key] = $dataStruct;
    $output[$key]['type'] = 'department';
    $output[$key]['id']   = $department->number;
    $output[$key]['name'] = str_pad($department->number, 2, '0', STR_PAD_LEFT).' '.$department->name;
}

foreach ($output as $datasetKey => $dataset) {
    if ($datasetKey === 'x') {
        continue;
    }

    Helpers::dateIterator(function ($date) use (&$indicators, &$output, &$covid, &$france, &$datasetKey, &$dataset) {
        $country = 'FRA';
        $region = null;
        $department = null;
        if ($dataset['type'] === 'region') {
            $region = $dataset['id'];
        }
        if ($dataset['type'] === 'department') {
            $region = $france->getDepartment($dataset['id'])->region;
            $department = $dataset['id'];
        }
        $data = $covid->getDataForDate($date, $country, $region, $department);
        //var_dump($data);

        foreach ($indicators as $indicator) {
            //var_dump($datasetKey); var_dump($indicator); var_dump($data->$indicator);
            if (in_array($indicator, ['tx', 'tx7'])) {
                $output[$datasetKey][$indicator][] = round($data->$indicator, 2);
            } else {
                $output[$datasetKey][$indicator][] = $data->$indicator;
            }
        }
    });
}

//var_dump($output);

//var_dump(json_encode($data));
//var_dump(json_last_error_msg());

$tpl = file_get_contents('index.tpl');
$tpl = str_replace('[DATA]', json_encode($output), $tpl);

file_put_contents('index.html', $tpl);
