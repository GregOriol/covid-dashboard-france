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
require_once 'src/CovidDashboardFrance/Populators/Tests.php';
require_once 'src/CovidDashboardFrance/Populators/Capa.php';
require_once 'src/CovidDashboardFrance/Populators/Indicateurs.php';

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
// - https://www.data.gouv.fr/fr/datasets/capacite-analytique-de-tests-virologiques-dans-le-cadre-de-lepidemie-covid-19
// https://www.data.gouv.fr/fr/datasets/r/0c230dc3-2d51-4f17-be97-aa9938564b39 sp-capa-quot-dep-*.csv
// https://www.data.gouv.fr/fr/datasets/r/21ff3134-c37c-41ef-bb3d-fbea5f6d4a28 sp-capa-quot-reg-*.csv
// https://www.data.gouv.fr/fr/datasets/r/44b46964-8583-4f18-b93f-80fefcbf3b74 sp-capa-quot-fra-*.csv
//
// - https://www.data.gouv.fr/fr/datasets/indicateurs-de-suivi-de-lepidemie-de-covid-19
// https://www.data.gouv.fr/fr/datasets/r/4acad602-d8b1-4516-bc71-7d5574d5f33e indicateurs-covid19-dep.csv
// https://www.data.gouv.fr/fr/datasets/r/381a9472-ce83-407d-9a64-1b8c23af83df indicateurs-covid19-fra.csv
//

$totalDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/63352e38-d353-4b54-bfd1-f1b3ee1cabd7';
$incidenceDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/6fadff46-9efd-4c53-942a-54aca783c30c';
$testsDepDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/4180a181-a648-402b-92e4-f7574647afa6';
$testsRegDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/23066f40-ddd2-40c9-931c-4257f36ad778';
$testsFraDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/59ad717b-b64e-4779-85f6-cd1b25b24703';
$capaDepDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/0c230dc3-2d51-4f17-be97-aa9938564b39';
$capaRegDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/21ff3134-c37c-41ef-bb3d-fbea5f6d4a28';
$capaFraDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/44b46964-8583-4f18-b93f-80fefcbf3b74';
$indicateursDepDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/4acad602-d8b1-4516-bc71-7d5574d5f33e';
$indicateursFraDataUrl = 'https://www.data.gouv.fr/fr/datasets/r/381a9472-ce83-407d-9a64-1b8c23af83df';

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

$cachedTestsDepDataPath = './cache/tests-dep.csv';
if (!file_exists($cachedTestsDepDataPath)) {
    $testsContents = file_get_contents($testsDepDataUrl);
    file_put_contents($cachedTestsDepDataPath, $testsContents);
}
$testsDepDataUrl = $cachedTestsDepDataPath;

$cachedTestsRegDataPath = './cache/tests-reg.csv';
if (!file_exists($cachedTestsRegDataPath)) {
    $testsContents = file_get_contents($testsRegDataUrl);
    file_put_contents($cachedTestsRegDataPath, $testsContents);
}
$testsRegDataUrl = $cachedTestsRegDataPath;

$cachedTestsFraDataPath = './cache/tests-fra.csv';
if (!file_exists($cachedTestsFraDataPath)) {
    $testsContents = file_get_contents($testsFraDataUrl);
    file_put_contents($cachedTestsFraDataPath, $testsContents);
}
$testsFraDataUrl = $cachedTestsFraDataPath;

$cachedCapaDepDataPath = './cache/capa-dep.csv';
if (!file_exists($cachedCapaDepDataPath)) {
    $capaContents = file_get_contents($capaDepDataUrl);
    file_put_contents($cachedCapaDepDataPath, $capaContents);
}
$capaDepDataUrl = $cachedCapaDepDataPath;

$cachedCapaRegDataPath = './cache/capa-reg.csv';
if (!file_exists($cachedCapaRegDataPath)) {
    $capaContents = file_get_contents($capaRegDataUrl);
    file_put_contents($cachedCapaRegDataPath, $capaContents);
}
$capaRegDataUrl = $cachedCapaRegDataPath;

$cachedCapaFraDataPath = './cache/capa-fra.csv';
if (!file_exists($cachedCapaFraDataPath)) {
    $capaContents = file_get_contents($capaFraDataUrl);
    file_put_contents($cachedCapaFraDataPath, $capaContents);
}
$capaFraDataUrl = $cachedCapaFraDataPath;

$cachedIndicateursDepDataPath = './cache/indicateurs-dep.csv';
if (!file_exists($cachedIndicateursDepDataPath)) {
    $indicateursContents = file_get_contents($indicateursDepDataUrl);
    file_put_contents($cachedIndicateursDepDataPath, $indicateursContents);
}
$indicateursDepDataUrl = $cachedIndicateursDepDataPath;

$cachedIndicateursFraDataPath = './cache/indicateurs-fra.csv';
if (!file_exists($cachedIndicateursFraDataPath)) {
    $indicateursContents = file_get_contents($indicateursFraDataUrl);
    file_put_contents($cachedIndicateursFraDataPath, $indicateursContents);
}
$indicateursFraDataUrl = $cachedIndicateursFraDataPath;

$covid = new \CovidDashboardFrance\Covid();

$populator = new \CovidDashboardFrance\Populators\Total($france, $covid);
$populator->populateData($totalDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Incidence($france, $covid);
$populator->populateData($incidenceDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Tests($france, $covid);
$populator->populateData($testsDepDataUrl, $testsRegDataUrl, $testsFraDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Capa($france, $covid);
$populator->populateData($capaDepDataUrl, $capaRegDataUrl, $capaFraDataUrl);
$populator->generateConsolidations();

$populator = new \CovidDashboardFrance\Populators\Indicateurs($france, $covid);
$populator->populateData($indicateursDepDataUrl, $indicateursFraDataUrl);
//$populator->generateConsolidations();

//var_dump($covid->data);
//var_dump($covid->refs);

$indicators = array('hosp', 'rea', 'rad', 'dc', 'incidenceHosp', 'incidenceRea', 'incidenceRad', 'incidenceDc', 'pop', 't', 'p', 'tx', 'tx7', 'txPos', 'txPos7', 'consolTx', 'consolTxPos', 'r', 'occup');

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
            if (in_array($indicator, ['tx', 'tx7', 'txPos', 'txPos7', 'consolTx', 'consolTxPos', 'r', 'occup'])) {
                $output[$datasetKey][$indicator][] = ($data->$indicator !== null) ? round($data->$indicator, 2) : null;
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
