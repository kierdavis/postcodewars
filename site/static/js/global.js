$(document).ready(function() {
    $(".flip").click(function() {
		$("#settingsPanel").slideToggle();
	});
	
	$("#settingsPanel input").change(function() {
		alert("Settings Changed!");
	});
		
	$("#battle_submit").click(function(event) {
		event.preventDefault();
		$("#search").after($('<div class="loader"></div>'));

		var url = "/results-json.php?postcode1=" + "&postcode2=";
		
		$.getJSON(url, function(data) {
			console.log(data);
		});
	});
});
