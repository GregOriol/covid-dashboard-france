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

		bindChart('incidence-hosp', getSelectedDatasets('HospIncidence hosp', 'incidenceHosp', '#8393A7'), 'line');
		bindChart('incidence-rea', getSelectedDatasets('HospIncidence rea', 'incidenceRea', '#03539d'), 'line');
		bindChart('incidence-rad', getSelectedDatasets('HospIncidence rad', 'incidenceRad', '#03BD5B'), 'line');
		bindChart('incidence-dc', getSelectedDatasets('HospIncidence dc', 'incidenceDc', '#D1335B'), 'line');

		changeAgeFunction();

		bindChart('t', getSelectedDatasets('Tests réalisés', 't', '#4864cd'), 'bar');
		bindChart('p', getSelectedDatasets('Tests positifs', 'p', '#D1335B'), 'bar');
		bindChart('tx', getSelectedDatasets('Tests incidence quotidien', 'tx', '#ba8c11'), 'line', 'Nombre de tests positifs pour 100\'000 habitants');
		bindChart('tx7', getSelectedDatasets('Tests incidence semaine', 'tx7', '#ba8c11'), 'line', 'Nombre de tests positifs pour 100\'000 habitants sur une semaine');
		bindChart('txPos', getSelectedDatasets('Tests positivité quotidien', 'txPos', '#7f11ba'), 'line', '% de tests positifs');
		bindChart('txPos7', getSelectedDatasets('Tests positivité semaine', 'txPos7', '#7f11ba'), 'line', '% de tests positifs sur une semaine');

		bindChart('consolTx', getSelectedDatasets('Taux incidence', 'consolTx', '#004192'), 'line', 'Nombre de tests positifs pour 100\'000 habitants sur une semaine');
		bindChart('consolTxPos', getSelectedDatasets('Taux positivité tests', 'consolTxPos', '#920016'), 'line', '% de tests positifs sur une semaine');
		bindChart('r', getSelectedDatasets('R0', 'r', '#923a00'), 'line', 'Région/pays uniquement');
		bindChart('occup', getSelectedDatasets('Capacité en réanimation', 'occup', '#169200'), 'line', 'Taux d\'occupation en % - région/pays uniquement');

		bindChart('pop', getSelectedDatasets('Population', 'pop', '#3368d1'), 'line');
	};

	const changeAgeFunction = function() {
		let ageHospDatasets = [];
		let ageReaDatasets = [];
		let ageRadDatasets = [];
		let ageDcDatasets = [];
		let agePDatasets = [];
		if (document.getElementById('age-09').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 0-9', 'ageHosp09', '#66C2A3'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 0-9', 'ageRea09', '#66C2A3'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 0-9', 'ageRad09', '#66C2A3'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 0-9', 'ageDc09', '#66C2A3'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 0-9', 'ageP09', '#66C2A3'));
		}
		if (document.getElementById('age-19').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 10-19', 'ageHosp19', '#5DC2CD'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 10-19', 'ageRea19', '#5DC2CD'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 10-19', 'ageRad19', '#5DC2CD'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 10-19', 'ageDc19', '#5DC2CD'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 10-19', 'ageP19', '#5DC2CD'));
		}
		if (document.getElementById('age-29').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 20-29', 'ageHosp29', '#ADBCC3'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 20-29', 'ageRea29', '#ADBCC3'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 20-29', 'ageRad29', '#ADBCC3'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 20-29', 'ageDc29', '#ADBCC3'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 20-29', 'ageP29', '#ADBCC3'));
		}
		if (document.getElementById('age-39').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 30-39', 'ageHosp39', '#698CAF'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 30-39', 'ageRea39', '#698CAF'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 30-39', 'ageRad39', '#698CAF'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 30-39', 'ageDc39', '#698CAF'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 30-39', 'ageP39', '#698CAF'));
		}
		if (document.getElementById('age-49').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 40-49', 'ageHosp49', '#BE9DE2'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 40-49', 'ageRea49', '#BE9DE2'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 40-49', 'ageRad49', '#BE9DE2'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 40-49', 'ageDc49', '#BE9DE2'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 40-49', 'ageP49', '#BE9DE2'));
		}
		if (document.getElementById('age-59').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 50-59', 'ageHosp59', '#828AF7'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 50-59', 'ageRea59', '#828AF7'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 50-59', 'ageRad59', '#828AF7'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 50-59', 'ageDc59', '#828AF7'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 50-59', 'ageP59', '#828AF7'));
		}
		if (document.getElementById('age-69').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 60-69', 'ageHosp69', '#D876C0'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 60-69', 'ageRea69', '#D876C0'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 60-69', 'ageRad69', '#D876C0'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 60-69', 'ageDc69', '#D876C0'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 60-69', 'ageP69', '#D876C0'));
		}
		if (document.getElementById('age-79').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 70-79', 'ageHosp79', '#F490AE'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 70-79', 'ageRea79', '#F490AE'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 70-79', 'ageRad79', '#F490AE'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 70-79', 'ageDc79', '#F490AE'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 70-79', 'ageP79', '#F490AE'));
		}
		if (document.getElementById('age-89').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 80-89', 'ageHosp89', '#F8A6A6'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 80-89', 'ageRea89', '#F8A6A6'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 80-89', 'ageRad89', '#F8A6A6'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 80-89', 'ageDc89', '#F8A6A6'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 80-89', 'ageP89', '#F8A6A6'));
		}
		if (document.getElementById('age-90').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('Hosp 90+', 'ageHosp90', '#F5AA85'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('Réa 90+', 'ageRea90', '#F5AA85'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('Rad 90+', 'ageRad90', '#F5AA85'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('Dc 90+', 'ageDc90', '#F5AA85'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('Tests positifs 90+', 'ageP90', '#F5AA85'));
		}
		bindChart('age-hosp', ageHospDatasets, 'line');
		bindChart('age-rea', ageReaDatasets, 'line');
		bindChart('age-rad', ageRadDatasets, 'line');
		bindChart('age-dc', ageDcDatasets, 'line');
		bindChart('age-p', agePDatasets, 'line');
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
		line: {
			connectNull: true
		},
		point: {
			r: 1.5
		},
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
