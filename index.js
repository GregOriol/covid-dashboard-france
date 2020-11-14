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
	const datasetElem1 = document.getElementById('dataset1');
	const datasetElem2 = document.getElementById('dataset2');
	const datasetElem3 = document.getElementById('dataset3');

	makeDatasets(datasetElem1);
	makeDatasets(datasetElem2, true);
	makeDatasets(datasetElem3, true);

	const getSelectedDatasets = function(dataType, color, name = null) {
		const selectedData = [];

		color = tinycolor(color);

		for (const [_, datasetElem] of Object.entries([datasetElem1, datasetElem2, datasetElem3])) {
			if (datasetElem.value === 'none') { continue; }

			selectedData.push({name: (name ? name + ' ' : '') + datasetElem.options[datasetElem.selectedIndex].text, data: data[datasetElem.value][dataType], color: color.toString()});

			color.spin(36);
		}

		return selectedData;
	}

	const changeFunction = function() {
		bindChart('hosp', 'Hospitalisations', getSelectedDatasets('hosp', '#8393A7'), 'bar');
		bindChart('rea', 'Réanimation ou soins intensifs', getSelectedDatasets('rea', '#03539d'), 'bar');
		bindChart('rad', 'Retours cumulés à domicile', getSelectedDatasets('rad', '#03BD5B'), 'bar');
		bindChart('dc', 'Décès cumulés à l\'hôpital', getSelectedDatasets('dc', '#D1335B'), 'bar');

		bindChart('incidence-hosp', 'Nouvelles hospitalisations', getSelectedDatasets('incidenceHosp', '#8393A7'), 'line');
		bindChart('incidence-rea', 'Nouvelles admissions en réanimation', getSelectedDatasets('incidenceRea', '#03539d'), 'line');
		bindChart('incidence-rad', 'Nouveaux retours à domicile', getSelectedDatasets('incidenceRad', '#03BD5B'), 'line');
		bindChart('incidence-dc', 'Nouveaux décès', getSelectedDatasets('incidenceDc', '#D1335B'), 'line');

		changeAgeFunction();

		bindChart('t', 'Tests réalisés', getSelectedDatasets('t', '#4864cd'), 'bar');
		bindChart('p', 'Tests positifs', getSelectedDatasets('p', '#D1335B'), 'bar');
		bindChart('tx', 'Taux d\'incidence quotidien', getSelectedDatasets('tx', '#ba8c11'), 'line', 'Positifs pour 100\'000 habitants');
		bindChart('tx7', 'Taux d\'incidence semaine', getSelectedDatasets('tx7', '#ba8c11'), 'line', 'Positifs pour 100\'000 habitants sur une semaine');
		bindChart('txPos', 'Taux de positivité quotidien', getSelectedDatasets('txPos', '#7f11ba'), 'line', '% positifs');
		bindChart('txPos7', 'Taux de positivité semaine', getSelectedDatasets('txPos7', '#7f11ba'), 'line', '% positifs sur une semaine');

		bindChart('consolTx', 'Activité épidémique (taux d\'incidence des tests)', getSelectedDatasets('consolTx', '#004192'), 'line', 'Positifs pour 100\'000 habitants sur une semaine');
		bindChart('consolTxPos', 'Taux de positivité des tests', getSelectedDatasets('consolTxPos', '#920016'), 'line', '% positifs sur une semaine');
		bindChart('r', 'Facteur de reproduction du virus (R0)', getSelectedDatasets('r', '#923a00'), 'line', 'Région/pays uniquement');
		bindChart('occup', 'Tension hospitalière sur la capacité en réanimation', getSelectedDatasets('occup', '#169200'), 'line', 'Taux d\'occupation en % - région/pays uniquement');

		bindChart('pop', 'Population', getSelectedDatasets('pop', '#3368d1'), 'line');
	};

	const changeAgeFunction = function() {
		let ageHospDatasets = [];
		let ageReaDatasets = [];
		let ageRadDatasets = [];
		let ageDcDatasets = [];
		let agePDatasets = [];
		if (document.getElementById('age-09').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp09', '#66C2A3', '0-9'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea09', '#66C2A3', '0-9'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad09', '#66C2A3', '0-9'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc09', '#66C2A3', '0-9'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP09', '#66C2A3', '0-9'));
		}
		if (document.getElementById('age-19').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp19', '#5DC2CD', '10-19'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea19', '#5DC2CD', '10-19'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad19', '#5DC2CD', '10-19'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc19', '#5DC2CD', '10-19'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP19', '#5DC2CD', '10-19'));
		}
		if (document.getElementById('age-29').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp29', '#ADBCC3', '20-29'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea29', '#ADBCC3', '20-29'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad29', '#ADBCC3', '20-29'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc29', '#ADBCC3', '20-29'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP29', '#ADBCC3', '20-29'));
		}
		if (document.getElementById('age-39').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp39', '#698CAF', '30-39'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea39', '#698CAF', '30-39'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad39', '#698CAF', '30-39'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc39', '#698CAF', '30-39'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP39', '#698CAF', '30-39'));
		}
		if (document.getElementById('age-49').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp49', '#BE9DE2', '40-49'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea49', '#BE9DE2', '40-49'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad49', '#BE9DE2', '40-49'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc49', '#BE9DE2', '40-49'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP49', '#BE9DE2', '40-49'));
		}
		if (document.getElementById('age-59').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp59', '#828AF7', '50-59'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea59', '#828AF7', '50-59'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad59', '#828AF7', '50-59'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc59', '#828AF7', '50-59'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP59', '#828AF7', '50-59'));
		}
		if (document.getElementById('age-69').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp69', '#D876C0', '60-69'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea69', '#D876C0', '60-69'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad69', '#D876C0', '60-69'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc69', '#D876C0', '60-69'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP69', '#D876C0', '60-69'));
		}
		if (document.getElementById('age-79').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp79', '#F490AE', '70-79'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea79', '#F490AE', '70-79'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad79', '#F490AE', '70-79'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc79', '#F490AE', '70-79'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP79', '#F490AE', '70-79'));
		}
		if (document.getElementById('age-89').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp89', '#F8A6A6', '80-89'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea89', '#F8A6A6', '80-89'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad89', '#F8A6A6', '80-89'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc89', '#F8A6A6', '80-89'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP89', '#F8A6A6', '80-89'));
		}
		if (document.getElementById('age-90').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp90', '#F5AA85', '90+'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea90', '#F5AA85', '90+'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad90', '#F5AA85', '90+'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc90', '#F5AA85', '90+'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP90', '#F5AA85', '90+'));
		}
		bindChart('age-hosp', 'Hospitalisations', ageHospDatasets, 'line');
		bindChart('age-rea', 'Réanimation ou soins intensifs', ageReaDatasets, 'line');
		bindChart('age-rad', 'Retours cumulés à domicile', ageRadDatasets, 'line');
		bindChart('age-dc', 'Décès cumulés à l\'hôpital', ageDcDatasets, 'line');
		bindChart('age-p', 'Tests positifs', agePDatasets, 'line');
	};

	addEventHandler(datasetElem1, 'change', changeFunction);
	addEventHandler(datasetElem2, 'change', changeFunction);
	addEventHandler(datasetElem3, 'change', changeFunction);

	addEventHandler(document.getElementById('age-09'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-19'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-29'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-39'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-49'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-59'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-69'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-79'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-89'), 'change', changeAgeFunction);
	addEventHandler(document.getElementById('age-90'), 'change', changeAgeFunction);

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

function bindChart(id, name, datasets, type = 'bar', infos = null) {
	// cleaning previous chart
	document.getElementById('chart-'+id+'').innerHTML = '<h3>' + name + '</h3><div class="chart-container"></div><div class="chart-info"></div>';

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
		line: {
			connectNull: true
		},
		point: {
			r: 1.5
		},
		grid: {
			x: {
				lines: [
					{value: '2020-03-17', text: 'début c1', class: 'x-line-c1-start', position: 'end'},
					{value: '2020-05-11', text: 'fin c1', class: 'x-line-c1-end', position: 'end'},
					{value: '2020-10-17', text: 'début cf', class: 'x-line-c2-start', position: 'end'},
					{value: '2020-10-30', text: 'début c2', class: 'x-line-c2-start', position: 'end'}
				]
			},
			y: {
				lines: []
			}
		},
	};

	if (id === 'consolTx') {
		options['grid']['y']['lines'].push({value: 10, text: 'orange', class: 'y-line-orange'});
		options['grid']['y']['lines'].push({value: 50, text: 'rouge', class: 'y-line-red'});
	} else if (id === 'consolTxPos') {
		options['grid']['y']['lines'].push({value: 5, text: 'orange', class: 'y-line-orange'});
		options['grid']['y']['lines'].push({value: 10, text: 'rouge', class: 'y-line-red'});
	} else if (id === 'r') {
		options['grid']['y']['lines'].push({value: 1, text: 'orange', class: 'y-line-orange'});
		options['grid']['y']['lines'].push({value: 1.5, text: 'rouge', class: 'y-line-red'});
	} else if (id === 'occup') {
		options['grid']['y']['lines'].push({value: 40, text: 'orange', class: 'y-line-orange'});
		options['grid']['y']['lines'].push({value: 60, text: 'rouge', class: 'y-line-red'});
	}

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

	document.querySelector('#chart-'+id+' .x-line-c1-start text').setAttribute('dy', 12); // moving label to the other side of the line
	document.querySelector('#chart-'+id+' .x-line-c1-end text').setAttribute('dy', 12); // moving label to the other side of the line
}
