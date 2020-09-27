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
		elem.addEventListener(eventType, handler, false);
	else if (elem.attachEvent)
		elem.attachEvent('on' + eventType, handler);
}

domReady(() => {
	// console.log(data);

	const datasetElem1 = document.getElementById('dataset1');
	const datasetElem2 = document.getElementById('dataset2');
	const datasetElem3 = document.getElementById('dataset3');

	makeDatasets(datasetElem1);
	makeDatasets(datasetElem2, true);
	makeDatasets(datasetElem3, true);

	const getSelectedDatasets = function(name, dataCat, dataType, color) {
		const selectedData = [];

		color = tinycolor(color);

		for (const [_, datasetElem] of Object.entries([datasetElem1, datasetElem2, datasetElem3])) {
			if (datasetElem.value === 'none') { continue; }

			selectedData.push({name: name + ' ' + datasetElem.options[datasetElem.selectedIndex].text, data: data[datasetElem.value][dataCat][dataType], color: color.toString()});

			color.spin(36);
		}

		return selectedData;
	}

	const changeFunction = function() {
		bindChart('total-hosp', getSelectedDatasets('Hosp', 'total', 'hosp', '#8393A7'));
		bindChart('total-rea', getSelectedDatasets('Réa', 'total', 'rea', '#03539d'));
		bindChart('total-rad', getSelectedDatasets('Rad', 'total', 'rad', '#03BD5B'));
		bindChart('total-dc', getSelectedDatasets('Dc', 'total', 'dc', '#D1335B'));

		bindChart('incidence-hosp', getSelectedDatasets('Incidence hosp', 'incidence', 'hosp', '#8393A7'));
		bindChart('incidence-rea', getSelectedDatasets('Incidence rea', 'incidence', 'rea', '#03539d'));
		bindChart('incidence-rad', getSelectedDatasets('Incidence rad', 'incidence', 'rad', '#03BD5B'));
		bindChart('incidence-dc', getSelectedDatasets('Incidence dc', 'incidence', 'dc', '#D1335B'));
	};

	addEventHandler(datasetElem1, 'change', changeFunction);
	addEventHandler(datasetElem2, 'change', changeFunction);
	addEventHandler(datasetElem3, 'change', changeFunction);

	datasetElem1.dispatchEvent(new Event('change'));
});

function makeDatasets(datasetElem, none = false) {
	const datasets = {
		'country': [],
		'region': [],
		'department': [],
	};
	for (const [datasetId, datasetValues] of Object.entries(data)) {
		datasets[datasetValues.type].push({id: datasetId, name: datasetValues.name});
	}

	let options = '';

	if (none) {
		options += '<option value="none">----</option>';
	}

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

function bindChart(id, datasets) {
	// cleaning previous chart
	document.getElementById('chart-'+id+'').innerHTML = '<div class="chart-container"></div><div class="chart-info"></div>';

	// preparing data
	const xs = [...datasets[0].data.x];
	xs.unshift('x');

	const options = {
		bindto: '#chart-'+id+' .chart-container',
		data: {
			x: 'x',
			columns: [
				xs,
				// values,
				// values2,
			],
			type: 'bar',
			colors: {
				// [dataset.name]: dataset.color,
				// [dataset.name+'2']: '#ff0000',
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
	};

	for (const [_, dataset] of Object.entries(datasets)) {
		const values = [...dataset.data.values];
		values.unshift(dataset.name);
		options.data.columns.push(values);

		options.data.colors[dataset.name] = dataset.color;
	}

	const chart = c3.generate(options);

	document.querySelector('#chart-'+id+' .chart-info').innerHTML = 'Données : '+datasets[0].data.x[0]+' - '+datasets[0].data.x[datasets[0].data.x.length - 1];
}
