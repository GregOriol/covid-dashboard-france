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

	<script src="index.js"></script>
</head>
<body>
	<div id="options">
		Dataset: <select id="dataset" name="dataset"></select>
	</div>
	<div id="charts" class="clearfix">
		<div class="chart" id="chart-total-hosp"></div>
		<div class="chart" id="chart-total-rea"></div>
		<div class="chart" id="chart-total-rad"></div>
		<div class="chart" id="chart-total-dc"></div>
	</div>

	<div id="charts" class="clearfix">
		<div class="chart" id="chart-incidence-hosp"></div>
		<div class="chart" id="chart-incidence-rea"></div>
		<div class="chart" id="chart-incidence-rad"></div>
		<div class="chart" id="chart-incidence-dc"></div>
	</div>

	<script type="text/javascript">
		const data = [DATA];
	</script>
</body>
</html>
