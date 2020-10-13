<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Covid Dashboard France</title>

	<link href="node_modules/normalize.css/normalize.css" rel="stylesheet">
	<link href="node_modules/c3/c3.min.css" rel="stylesheet">

	<link href="index.css" rel="stylesheet">

	<script src="node_modules/d3/dist/d3.min.js" charset="utf-8"></script>
	<script src="node_modules/c3/c3.min.js"></script>
	<script src="node_modules/tinycolor2/dist/tinycolor-min.js"></script>

	<script src="index.js"></script>
</head>
<body>
	<div id="options">
		Dataset 1: <select id="dataset1" name="dataset1" class="dataset"></select><br>
		Dataset 2: <select id="dataset2" name="dataset2" class="dataset"></select><br>
		Dataset 3: <select id="dataset3" name="dataset3" class="dataset"></select>
	</div>

	<h2>Données hospitalières</h2>
	<div class="charts clearfix">
		<div class="chart" id="chart-hosp"></div>
		<div class="chart" id="chart-rea"></div>
		<div class="chart" id="chart-rad"></div>
		<div class="chart" id="chart-dc"></div>
	</div>

	<div class="charts clearfix">
		<div class="chart" id="chart-incidence-hosp"></div>
		<div class="chart" id="chart-incidence-rea"></div>
		<div class="chart" id="chart-incidence-rad"></div>
		<div class="chart" id="chart-incidence-dc"></div>
	</div>

	<h2>Tests et incidence</h2>
	<div class="charts clearfix">
		<div class="chart" id="chart-t"></div>
		<div class="chart" id="chart-p"></div>
		<div class="chart" id="chart-txPos"></div>
		<div class="chart" id="chart-txPos7"></div>
		<div class="chart" id="chart-tx"></div>
		<div class="chart" id="chart-tx7"></div>
	</div>

	<h2>Par classe d'âge</h2>
	<div class="charts clearfix">
		<div class="selector">
			<div class="checkbox"><input type="checkbox" name="age-09" id="age-09" checked="checked"><label for="age-09">0-9 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-19" id="age-19" checked="checked"><label for="age-19">10-19 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-29" id="age-29" checked="checked"><label for="age-29">20-29 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-39" id="age-39" checked="checked"><label for="age-39">30-39 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-49" id="age-49" checked="checked"><label for="age-49">40-49 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-59" id="age-59" checked="checked"><label for="age-59">50-59 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-69" id="age-69" checked="checked"><label for="age-69">60-69 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-79" id="age-79" checked="checked"><label for="age-79">70-79 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-89" id="age-89" checked="checked"><label for="age-89">80-89 ans</label></div>
			<div class="checkbox"><input type="checkbox" name="age-90" id="age-90" checked="checked"><label for="age-90">90+ ans</label></div>
		</div>
		<div class="chart" id="chart-age-hosp"></div>
		<div class="chart" id="chart-age-rea"></div>
		<div class="chart" id="chart-age-rad"></div>
		<div class="chart" id="chart-age-dc"></div>
		<div class="chart" id="chart-age-p"></div>
	</div>

	<h2>Indicateurs de suivi de l’épidémie</h2>
	<div class="charts clearfix">
		<div class="chart" id="chart-consolTx"></div>
		<div class="chart" id="chart-consolTxPos"></div>
		<div class="chart" id="chart-r"></div>
		<div class="chart" id="chart-occup"></div>
	</div>

	<h2>Divers</h2>
	<div class="charts clearfix">
		<div class="chart" id="chart-pop"></div>
	</div>

	<h2>Sources</h2>
	<div class="sources">
		<dl>
			<dt>* Données hospitalières relatives à l'épidémie de COVID-19 - Santé publique France</dt>
			<dd><a href="https://www.data.gouv.fr/fr/datasets/donnees-hospitalieres-relatives-a-lepidemie-de-covid-19" target="_blank" rel="noopener noreferrer">https://www.data.gouv.fr/fr/datasets/donnees-hospitalieres-relatives-a-lepidemie-de-covid-19</a></dd>
			<dt>* Taux d'incidence de l'épidémie de COVID-19 (SI-DEP) - Santé publique France</dt>
			<dd><a href="https://www.data.gouv.fr/fr/datasets/taux-dincidence-de-lepidemie-de-covid-19" target="_blank" rel="noopener noreferrer">https://www.data.gouv.fr/fr/datasets/taux-dincidence-de-lepidemie-de-covid-19</a></dd>
			<dt>* Capacité analytique de tests virologiques dans le cadre de l'épidémie COVID-19 (SI-DEP) - Santé publique France</dt>
			<dd><a href="https://www.data.gouv.fr/fr/datasets/capacite-analytique-de-tests-virologiques-dans-le-cadre-de-lepidemie-covid-19" target="_blank" rel="noopener noreferrer">https://www.data.gouv.fr/fr/datasets/capacite-analytique-de-tests-virologiques-dans-le-cadre-de-lepidemie-covid-19</a></dd>
			<dt>* Indicateurs de suivi de l’épidémie de COVID-19 - Ministère des Solidarités et de la Santé</dt>
			<dd><a href="https://www.data.gouv.fr/fr/datasets/indicateurs-de-suivi-de-lepidemie-de-covid-19" target="_blank" rel="noopener noreferrer">https://www.data.gouv.fr/fr/datasets/indicateurs-de-suivi-de-lepidemie-de-covid-19</a></dd>
		</dl>
		Code source de cette page: <a href="http://github.com/GregOriol/covid-dashboard-france" target="_blank" rel="noopener noreferrer">http://github.com/GregOriol/covid-dashboard-france</a><br>
	</div>

	<script type="text/javascript">
		const data = [DATA];
	</script>
</body>
</html>
