function fetchTowns(municipality, context) {
	if( typeof fetchTowns.baseUrl == 'undefined' ) {
		if( depTownStore._jsonFileUrl.indexOf("/municipality") != -1 ) {
			fetchTowns.baseUrl = depTownStore._jsonFileUrl.substr(0, depTownStore._jsonFileUrl.indexOf("/municipality"));
		} else {
			fetchTowns.baseUrl = depTownStore._jsonFileUrl;
		}
	}
	if( context == "dep") {
		depTownStore._jsonFileUrl = fetchTowns.baseUrl + "/municipality/" + municipality.value;
		depTownStore.close();
	} else if( context == "arr" ) {
		arrTownStore._jsonFileUrl = fetchTowns.baseUrl + "/municipality/" + municipality.value;
		arrTownStore.close();
	}
	dijit.byId(context + "_town").attr("displayedValue", "");
}

function rideDateChange(input) {
	if(input.value == 1) {
		dojo.byId("fieldset-ride_date").style.display = "none";
		dojo.byId("fieldset-ride_days").style.display = "block";
	} else {
		dojo.byId("fieldset-ride_date").style.display = "block";
		dojo.byId("fieldset-ride_days").style.display = "none";
	}
}

function rideTypeChange(newtype) {
	if(newtype.value == 'NEED') {
		dojo.byId("fieldset-ride_spots").style.display = 'none';
	} else {
		dojo.byId("fieldset-ride_spots").style.display = 'block';
	}
}

dojo.addOnLoad(function () {
		rideDateChange(dojo.query('[name="repeatable"]:checked'));
		if( dojo.byId("fieldset-ride_spots") ) {
		dojo.byId("fieldset-ride_spots").style.display = 'none';
		}
		})
