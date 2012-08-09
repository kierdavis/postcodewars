$(document).ready(function() {
	$("#settingsPanel").hide();
    
	$("#settingsPanel ul li").each(function() {
	var cookieName = $(this).attr("id").replace("-visibility", "");
		var value = $.cookie(cookieName); 
		if (value == true) {
				$("#" + cookieName).show();
		}else{
			$("#" + cookieName).hide();
		};
		
	});
	
	$(".flip").click(function() {
		$("#settingsPanel").slideToggle();
	});
	
	$("#settingsPanel input").change(function() {
		var cookieName = $(this).attr("id").replace("-visibility", "");
		var newValue = $(this).is(':checked');
		$.cookie(cookieName, newValue, { expires: 7, path: '/' });
		if (newValue) {
			$("#" + cookieName).show();
		}else{
			$("#" + cookieName).hide();
		}
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
