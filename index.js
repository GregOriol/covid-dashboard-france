function domReady(fn) {
	// If we're early to the party
	document.addEventListener('DOMContentLoaded', fn);
	// If late; I mean on time.
	if (document.readyState === 'interactive' || document.readyState === 'complete' ) {
		fn();
	}
}
function addEventHandler(elem, eventType, handler) {
	if (elem.addEventListener)
		elem.addEventListener (eventType, handler, false);
	else if (elem.attachEvent)
		elem.attachEvent ('on' + eventType, handler);
}

domReady(() => {
	// console.log(data);

	const datasetElem = document.getElementById('dataset');

	makeDatasets(datasetElem);

	addEventHandler(datasetElem, 'change', function() {
		const dataset = datasetElem.value;

		bindChart('total-hosp', 'hosp', data[dataset].total.hosp, '#8393A7');
		bindChart('total-rea', 'rea', data[dataset].total.rea, '#03539d');
		bindChart('total-rad', 'rad', data[dataset].total.rad, '#03BD5B');
		bindChart('total-dc', 'dc', data[dataset].total.dc, '#D1335B');

		bindChart('incidence-hosp', 'incidence hosp', data[dataset].incidence.hosp, '#8393A7');
		bindChart('incidence-rea', 'incidence rea', data[dataset].incidence.rea, '#03539d');
		bindChart('incidence-rad', 'incidence rad', data[dataset].incidence.rad, '#03BD5B');
		bindChart('incidence-dc', 'incidence dc', data[dataset].incidence.dc, '#D1335B');
	});

	datasetElem.dispatchEvent(new Event('change'));
});

function makeDatasets(datasetElem) {
	const datasets = {
		'country': [],
		'region': [],
		'department': [],
	};
	for (const [datasetId, datasetValues] of Object.entries(data)) {
		datasets[datasetValues.type].push({id: datasetId, name: datasetValues.name});
	}

	let options = '';
	for (const [group, values] of Object.entries(datasets)) {
		if (values.length === 0) { continue; }

		let label = '';
		if (group === 'country') { label = 'Pays'; }
		if (group === 'region') { label = 'Régions'; }
		if (group === 'department') { label = 'Départements'; }

		options += '<optgroup label="'+label+'">';
		for (const option of values) {
			options += '<option value="'+option.id+'">'+option.name+'</option>';
		}
		options += '</optgroup>';
	}

	datasetElem.innerHTML = options;
}

function bindChart(id, name, data, color) {
	// cleaning previous chart
	document.getElementById('chart-'+id+'').innerHTML = '<div class="chart-container"></div><div class="chart-info"></div>';

	// preparing data
	const xs = [...data.x];
	xs.unshift('x');
	const values = [...data.values];
	values.unshift(name);

	const chart = c3.generate({
		bindto: '#chart-'+id+' .chart-container',
		data: {
			x: 'x',
			columns: [
				xs,
				values,
			],
			type: 'bar',
			// color: function (color_, d) { console.log(color_, d); return color; }
			colors: {
				[name]: color,
			}
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: '%d/%m',
					rotate: -45,
					centered: true,
					// culling: false, // done in css
					// culling: {
					// 	max: 12
					// }
				}
			},
			// y: {
			// 	show: false
			// }
		},
		transition: {
			duration: null
		},
		bar: {
			width: {
				ratio: 0.5
			}
		},
		// legend: {
		// 	show: false
		// },
	});

	document.querySelector('#chart-'+id+' .chart-info').innerHTML = 'Update: '+data.x[data.x.length - 1];
}
