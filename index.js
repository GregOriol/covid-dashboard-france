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

		bindChart('t', 'Tests réalisés', getSelectedDatasets('t', '#4864cd'), 'bar');
		bindChart('p', 'Tests positifs', getSelectedDatasets('p', '#D1335B'), 'bar');
		bindChart('tx', 'Taux d\'incidence quotidien', getSelectedDatasets('tx', '#ba8c11'), 'line', 'Positifs pour 100\'000 habitants');
		bindChart('tx7', 'Taux d\'incidence semaine', getSelectedDatasets('tx7', '#ba8c11'), 'line', 'Positifs pour 100\'000 habitants sur une semaine');
		bindChart('txPos', 'Taux de positivité quotidien', getSelectedDatasets('txPos', '#7f11ba'), 'line', '% positifs');
		bindChart('txPos7', 'Taux de positivité semaine', getSelectedDatasets('txPos7', '#7f11ba'), 'line', '% positifs sur une semaine');

		bindChart('complet', 'Complet', getSelectedDatasets('complet', '#2464b0'), 'bar');
		bindChart('rappel', 'Rappel', getSelectedDatasets('rappel', '#29a03b'), 'bar');
		bindChart('completTot', 'Complet cumulé', getSelectedDatasets('completTot', '#2464b0'), 'bar');
		bindChart('rappelTot', 'Rappel cumulé', getSelectedDatasets('rappelTot', '#29a03b'), 'bar');
		bindChart('completCouv', 'Complet couverture', getSelectedDatasets('completCouv', '#2464b0'), 'line', 'Taux en %');
		bindChart('rappelCouv', 'Rappel couverture', getSelectedDatasets('rappelCouv', '#29a03b'), 'line', 'Taux en %');

		changeAgeFunction();

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
		let ageCompletDatasets = [];
		let ageRappelDatasets = [];
		let ageCompletTotDatasets = [];
		let ageRappelTotDatasets = [];
		let ageCompletCouvDatasets = [];
		let ageRappelCouvDatasets = [];
		if (document.getElementById('age-09').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp09', '#66C2A3', '0-9'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea09', '#66C2A3', '0-9'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad09', '#66C2A3', '0-9'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc09', '#66C2A3', '0-9'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP09', '#66C2A3', '0-9'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet09', '#66C2A3', '0-9'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel09', '#66C2A3', '0-9'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot09', '#66C2A3', '0-9'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot09', '#66C2A3', '0-9'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv09', '#66C2A3', '0-9'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv09', '#66C2A3', '0-9'));
		}
		if (document.getElementById('age-19').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp19', '#5DC2CD', '10-19'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea19', '#5DC2CD', '10-19'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad19', '#5DC2CD', '10-19'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc19', '#5DC2CD', '10-19'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP19', '#5DC2CD', '10-19'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet19', '#5DC2CD', '10-19'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel19', '#5DC2CD', '10-19'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot19', '#5DC2CD', '10-19'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot19', '#5DC2CD', '10-19'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv19', '#5DC2CD', '10-19'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv19', '#5DC2CD', '10-19'));
		}
		if (document.getElementById('age-29').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp29', '#ADBCC3', '20-29'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea29', '#ADBCC3', '20-29'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad29', '#ADBCC3', '20-29'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc29', '#ADBCC3', '20-29'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP29', '#ADBCC3', '20-29'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet29', '#ADBCC3', '20-29'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel29', '#ADBCC3', '20-29'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot29', '#ADBCC3', '20-29'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot29', '#ADBCC3', '20-29'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv29', '#ADBCC3', '20-29'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv29', '#ADBCC3', '20-29'));
		}
		if (document.getElementById('age-39').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp39', '#698CAF', '30-39'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea39', '#698CAF', '30-39'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad39', '#698CAF', '30-39'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc39', '#698CAF', '30-39'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP39', '#698CAF', '30-39'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet39', '#698CAF', '30-39'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel39', '#698CAF', '30-39'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot39', '#698CAF', '30-39'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot39', '#698CAF', '30-39'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv39', '#698CAF', '30-39'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv39', '#698CAF', '30-39'));
		}
		if (document.getElementById('age-49').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp49', '#BE9DE2', '40-49'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea49', '#BE9DE2', '40-49'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad49', '#BE9DE2', '40-49'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc49', '#BE9DE2', '40-49'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP49', '#BE9DE2', '40-49'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet49', '#BE9DE2', '40-49'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel49', '#BE9DE2', '40-49'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot49', '#BE9DE2', '40-49'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot49', '#BE9DE2', '40-49'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv49', '#BE9DE2', '40-49'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv49', '#BE9DE2', '40-49'));
		}
		if (document.getElementById('age-59').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp59', '#828AF7', '50-59'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea59', '#828AF7', '50-59'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad59', '#828AF7', '50-59'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc59', '#828AF7', '50-59'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP59', '#828AF7', '50-59'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet59', '#828AF7', '50-59'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel59', '#828AF7', '50-59'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot59', '#828AF7', '50-59'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot59', '#828AF7', '50-59'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv59', '#828AF7', '50-59'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv59', '#828AF7', '50-59'));
		}
		if (document.getElementById('age-69').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp69', '#D876C0', '60-69'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea69', '#D876C0', '60-69'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad69', '#D876C0', '60-69'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc69', '#D876C0', '60-69'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP69', '#D876C0', '60-69'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet69', '#D876C0', '60-69'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel69', '#D876C0', '60-69'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot69', '#D876C0', '60-69'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot69', '#D876C0', '60-69'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv69', '#D876C0', '60-69'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv69', '#D876C0', '60-69'));
		}
		if (document.getElementById('age-79').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp79', '#F490AE', '70-79'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea79', '#F490AE', '70-79'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad79', '#F490AE', '70-79'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc79', '#F490AE', '70-79'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP79', '#F490AE', '70-79'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet79', '#F490AE', '70-79'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel79', '#F490AE', '70-79'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot79', '#F490AE', '70-79'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot79', '#F490AE', '70-79'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv79', '#F490AE', '70-79'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv79', '#F490AE', '70-79'));
		}
		if (document.getElementById('age-89').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp89', '#F8A6A6', '80-89'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea89', '#F8A6A6', '80-89'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad89', '#F8A6A6', '80-89'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc89', '#F8A6A6', '80-89'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP89', '#F8A6A6', '80-89'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet89', '#F8A6A6', '80-89'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel89', '#F8A6A6', '80-89'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot89', '#F8A6A6', '80-89'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot89', '#F8A6A6', '80-89'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv89', '#F8A6A6', '80-89'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv89', '#F8A6A6', '80-89'));
		}
		if (document.getElementById('age-90').checked) {
			ageHospDatasets.push.apply(ageHospDatasets, getSelectedDatasets('ageHosp90', '#F5AA85', '90+'));
			ageReaDatasets.push.apply(ageReaDatasets, getSelectedDatasets('ageRea90', '#F5AA85', '90+'));
			ageRadDatasets.push.apply(ageRadDatasets, getSelectedDatasets('ageRad90', '#F5AA85', '90+'));
			ageDcDatasets.push.apply(ageDcDatasets, getSelectedDatasets('ageDc90', '#F5AA85', '90+'));
			agePDatasets.push.apply(agePDatasets, getSelectedDatasets('ageP90', '#F5AA85', '90+'));
			ageCompletDatasets.push.apply(ageCompletDatasets, getSelectedDatasets('ageComplet90', '#F5AA85', '90+'));
			ageRappelDatasets.push.apply(ageRappelDatasets, getSelectedDatasets('ageRappel90', '#F5AA85', '90+'));
			ageCompletTotDatasets.push.apply(ageCompletTotDatasets, getSelectedDatasets('ageCompletTot90', '#F5AA85', '90+'));
			ageRappelTotDatasets.push.apply(ageRappelTotDatasets, getSelectedDatasets('ageRappelTot90', '#F5AA85', '90+'));
			ageCompletCouvDatasets.push.apply(ageCompletCouvDatasets, getSelectedDatasets('ageCompletCouv90', '#F5AA85', '90+'));
			ageRappelCouvDatasets.push.apply(ageRappelCouvDatasets, getSelectedDatasets('ageRappelCouv90', '#F5AA85', '90+'));
		}
		bindChart('age-hosp', 'Hospitalisations', ageHospDatasets, 'line');
		bindChart('age-rea', 'Réanimation ou soins intensifs', ageReaDatasets, 'line');
		bindChart('age-rad', 'Retours cumulés à domicile', ageRadDatasets, 'line');
		bindChart('age-dc', 'Décès cumulés à l\'hôpital', ageDcDatasets, 'line');
		bindChart('age-p', 'Tests positifs', agePDatasets, 'line');
		bindChart('age-complet', 'Complet', ageCompletDatasets, 'line');
		bindChart('age-rappel', 'Rappel', ageRappelDatasets, 'line');
		bindChart('age-completTot', 'Complet cumulé', ageCompletTotDatasets, 'line');
		bindChart('age-rappelTot', 'Rappel cumulé', ageRappelTotDatasets, 'line');
		bindChart('age-completCouv', 'Complet couverture', ageCompletCouvDatasets, 'line', 'Taux en %');
		bindChart('age-rappelCouv', 'Rappel couverture', ageRappelCouvDatasets, 'line', 'Taux en %');
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
					{value: '2020-03-17', text: 'c1', class: 'x-line-c1-start', position: 'end'},
					{value: '2020-05-11', text: 'fin c1', class: 'x-line-c1-end', position: 'end'},
					{value: '2020-10-17', text: 'cf', class: 'x-line-start', position: 'end'},
					{value: '2020-10-30', text: 'c2', class: 'x-line-start', position: 'end'},
					{value: '2020-12-14', text: 'fin c2', class: 'x-line-end', position: 'end'},
					{value: '2021-01-16', text: 'cf18h', class: 'x-line-start', position: 'end'},
					{value: '2021-06-20', text: 'fin cf', class: 'x-line-end', position: 'end'},
				]
			},
			y: {
				lines: []
			}
		},
	};

	if (id === 'consolTx') {
		options['grid']['y']['lines'].push({value: 10, text: 'orange', class: 'y-line-orange', position: 'start'});
		options['grid']['y']['lines'].push({value: 50, text: 'rouge', class: 'y-line-red', position: 'start'});
	} else if (id === 'consolTxPos') {
		options['grid']['y']['lines'].push({value: 5, text: 'orange', class: 'y-line-orange', position: 'start'});
		options['grid']['y']['lines'].push({value: 10, text: 'rouge', class: 'y-line-red', position: 'start'});
	} else if (id === 'r') {
		options['grid']['y']['lines'].push({value: 1, text: 'orange', class: 'y-line-orange', position: 'start'});
		options['grid']['y']['lines'].push({value: 1.5, text: 'rouge', class: 'y-line-red', position: 'start'});
	} else if (id === 'occup') {
		options['grid']['y']['lines'].push({value: 40, text: 'orange', class: 'y-line-orange', position: 'start'});
		options['grid']['y']['lines'].push({value: 60, text: 'rouge', class: 'y-line-red', position: 'start'});
	} else if (id === 'completCouv' || id === 'rappelCouv' || id === 'age-completCouv' || id === 'age-rappelCouv') {
		options['grid']['y']['lines'].push({value: 90, text: '90%', class: 'y-line-green', position: 'start'});
		options['grid']['y']['lines'].push({value: 70, text: '70%', class: 'y-line-blue', position: 'start'});
		options['grid']['y']['lines'].push({value: 50, text: '50%', class: 'y-line-purple', position: 'start'});
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
