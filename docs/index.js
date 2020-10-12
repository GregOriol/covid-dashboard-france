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

	const getSelectedDatasets = function(name, dataType, color) {
		const selectedData = [];

		color = tinycolor(color);

		for (const [_, datasetElem] of Object.entries([datasetElem1, datasetElem2, datasetElem3])) {
			if (datasetElem.value === 'none') { continue; }

			selectedData.push({name: name + ' ' + datasetElem.options[datasetElem.selectedIndex].text, data: data[datasetElem.value][dataType], color: color.toString()});

			color.spin(36);
		}

		return selectedData;
	}

	const changeFunction = function() {
		bindChart('hosp', getSelectedDatasets('Hosp', 'hosp', '#8393A7'), 'bar');
		bindChart('rea', getSelectedDatasets('Réa', 'rea', '#03539d'), 'bar');
		bindChart('rad', getSelectedDatasets('Rad', 'rad', '#03BD5B'), 'bar');
		bindChart('dc', getSelectedDatasets('Dc', 'dc', '#D1335B'), 'bar');

		bindChart('incidence-hosp', getSelectedDatasets('Incidence hosp', 'incidenceHosp', '#8393A7'), 'line');
		bindChart('incidence-rea', getSelectedDatasets('Incidence rea', 'incidenceRea', '#03539d'), 'line');
		bindChart('incidence-rad', getSelectedDatasets('Incidence rad', 'incidenceRad', '#03BD5B'), 'line');
		bindChart('incidence-dc', getSelectedDatasets('Incidence dc', 'incidenceDc', '#D1335B'), 'line');

		bindChart('t', getSelectedDatasets('Tests réalisés', 't', '#4864cd'), 'bar');
		bindChart('p', getSelectedDatasets('Tests positifs', 'p', '#D1335B'), 'bar');
		bindChart('tx', getSelectedDatasets('Tests incidence quotidien', 'tx', '#ba8c11'), 'line', 'Taux de tests positifs pour 100\'000 personnes');
		bindChart('tx7', getSelectedDatasets('Tests incidence semaine', 'tx7', '#ba8c11'), 'line', 'Taux de tests positifs pour 100\'000 personnes sur une semaine');
		bindChart('txPos', getSelectedDatasets('Tests positivité quotidien', 'txPos', '#7f11ba'), 'line', '% de tests positifs');
		bindChart('txPos7', getSelectedDatasets('Tests positivité semaine', 'txPos7', '#7f11ba'), 'line', '% de tests positifs sur une semaine');

		bindChart('consolTx', getSelectedDatasets('Taux incidence', 'consolTx', '#004192'), 'line', 'Taux en %');
		bindChart('consolTxPos', getSelectedDatasets('Taux positivité tests', 'consolTxPos', '#920016'), 'line', 'Taux en %');
		bindChart('r', getSelectedDatasets('R0', 'r', '#923a00'), 'line');
		bindChart('occup', getSelectedDatasets('Capacité en réanimation', 'occup', '#169200'), 'line', 'Taux d\'occupation en %');

		bindChart('pop', getSelectedDatasets('Population', 'pop', '#3368d1'), 'line');
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
		if (datasetId === 'x') { continue; }

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

function bindChart(id, datasets, type = 'bar', infos = null) {
	// cleaning previous chart
	document.getElementById('chart-'+id+'').innerHTML = '<div class="chart-container"></div><div class="chart-info"></div>';

	// preparing data
	const xs = [...data['x']];
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
			type: type,
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
		point: {
			r: 1.5
		}
		// legend: {
		// 	show: false
		// },
	};

	let start = null;
	let end = null;
	for (const [_, dataset] of Object.entries(datasets)) {
		const values = [...dataset.data];

		let foundStart = false;
		for (let i = 0; i < values.length; i++) {
			if (values[i] !== null) {
				if (!foundStart) {
					start = i;
					foundStart = true;
				}
				end = i;
			}
		}

		values.unshift(dataset.name);
		options.data.columns.push(values);

		options.data.colors[dataset.name] = dataset.color;
	}

	const chart = c3.generate(options);

	document.querySelector('#chart-'+id+' .chart-info').innerHTML = ((infos !== null) ? infos + ' - ' : '') + 'Données : '+xs[start+1]+' - '+xs[end+1];
}
