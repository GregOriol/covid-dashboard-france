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
	<div id="charts" class="clearfix">
		<div class="chart" id="chart-hosp"></div>
		<div class="chart" id="chart-rea"></div>
		<div class="chart" id="chart-rad"></div>
		<div class="chart" id="chart-dc"></div>
	</div>

	<div id="charts" class="clearfix">
		<div class="chart" id="chart-incidence-hosp"></div>
		<div class="chart" id="chart-incidence-rea"></div>
		<div class="chart" id="chart-incidence-rad"></div>
		<div class="chart" id="chart-incidence-dc"></div>
	</div>

	<div id="charts" class="clearfix">
		<div class="chart" id="chart-pop"></div>
		<div class="chart" id="chart-p"></div>
		<div class="chart" id="chart-tx"></div>
		<div class="chart" id="chart-tx7"></div>
	</div>

	<script type="text/javascript">
		const data = [DATA];
	</script>
</body>
</html>
