$(document).ready(function() {
	$("#settingsPanel").hide();
    
	$(".flip").click(function() {
		$("#settingsPanel").slideToggle();
	});
	
	$("#settingsPanel input").change(function() {
		alert("Settings Changed!");
	});
	
	$("#battle_submit").click(function(event) {
		// event.preventDefault();
		$("#search").after($('<div class="loader"></div>'));

		var postcode1 = $("#battle_postcode1").text();
		var postcode2 = $("#battle_postcode2").text();

		var url = "/results-json.php?postcode1=" + postcode1 + "&postcode2=" + postcode2;

		$.getJSON(url, function(data) {
			$(".loader").remove();
		});
	});
});
