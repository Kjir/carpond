function fetchTowns(municipality, context) {
    if( typeof fetchTowns.baseUrl == 'undefined' ) {
	fetchTowns.baseUrl = townStore._jsonFileUrl;
    }
    if( context == "live") {
	townStore._jsonFileUrl = fetchTowns.baseUrl + "/municipality/" + municipality.value;
	townStore.close();
    } else if( context == "work" ) {
	workTownStore._jsonFileUrl = fetchTowns.baseUrl + "/municipality/" + municipality.value;
	workTownStore.close();
    }
    dijit.byId(context + "_town").attr("displayedValue", "");
}
