function loadToppings() {
	var request = new XMLHttpRequest();
	request.open("GET","./api/index.php/toppings",true);
	request.onreadystatechange = function(e) {
		if (request.readyState === 4) {
			var topping_data = JSON.parse(request.responseText);

			$("#toppingsChart").dxChart({
				title: "Toppings Sold",
				dataSource: topping_data,
				series: [
				{
					type: 'bar',
					argumentField: "name",
					valueField: "quantity_sold",
					name: 'Toppings',
				}],
				argumentAxis: {
	            grid: { visible: true },
	            label: { indentFromAxis: 25, rotationAngle: 90, font: { color: 'black', size: 15 } }
	        	},
				tooltip: 
				{
					enabled: true,
					customizeText: function() { 
						return this.argumentText + " - " + this.valueText;
					}
				}
			});
		}
	}
	request.send();
}


function loadFillings() {
	var request = new XMLHttpRequest();
	request.open("GET","./api/index.php/fillings",true);
	request.onreadystatechange = function(e) {
		if (request.readyState === 4) {
			var filling_data = JSON.parse(request.responseText);

			$("#fillingsChart").dxPieChart({
				title: "Fillings Sold",
				dataSource: filling_data,
				series: [
				{
					argumentField: "name",
					valueField: "quantity_sold",
					label:{
						visible: true,
						connector:{
							visible:true,
							width: 1
						}
					}
				}],
				tooltip: 
				{
					enabled: true,
					percentPrecision: 2,
					customizeText: function() { 
						return this.argumentText + " - " + this.percentText;
					}
				}
			});

		}
	}
	request.send();
}

function loadIcings() {
	var request = new XMLHttpRequest();
	request.open("GET","./api/index.php/icings",true);
	request.onreadystatechange = function(e) {
		if (request.readyState === 4) {
			var icing_data = JSON.parse(request.responseText);

			$("#icingsChart").dxPieChart({
				title: "Icings Sold",
				dataSource: icing_data,
				series: [
				{
					argumentField: "name",
					valueField: "quantity_sold",
					label:{
						visible: true,
						connector:{
							visible:true,
							width: 1
						}
					}
				}],
				tooltip: 
				{
					enabled: true,
					percentPrecision: 2,
					customizeText: function() { 
						return this.argumentText + " - " + this.percentText;
					}
				}
			});

		}
	}
	request.send();
}

function loadFlavors() {
	var request = new XMLHttpRequest();
	request.open("GET","./api/index.php/flavors",true);
	request.onreadystatechange = function(e) {
		if (request.readyState === 4) {
			var flavor_data = JSON.parse(request.responseText);

			$("#flavorsChart").dxPieChart({
				title: "Flavors Sold",
				dataSource: flavor_data,
				series: [
				{
					argumentField: "name",
					valueField: "quantity_sold",
					label:{
						visible: true,
						connector:{
							visible:true,
							width: 1
						}
					}
				}],
				tooltip: 
				{
					enabled: true,
					percentPrecision: 2,
					customizeText: function() { 
						return this.argumentText + " - " + this.percentText;
					}
				}
			});
		}
	}
	request.send();
}

$(document).ready(function () {
	loadToppings();
	loadFlavors();
	loadFillings();
	loadIcings();
});

