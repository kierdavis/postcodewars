$(document).ready(function() {
	//By default the settings tab should be hidden
	$("#settingsPanel").hide();
	//Cookie does not remember hide/show settings on load
	//Cookie somehow overides the setting code and shows it on load (by default the settings are meant to be hidden).
	$("#settingsPanel input").each(function() {
		var cookieName = $(this).attr("id").replace("-visibility", "");

		if ($.cookie(cookieName) == "hidden") {
			$("#" + cookieName).hide();
			$(this).attr('checked', false);
		} else {
			$("#" + cookieName).show();
			$(this).attr('checked', true);
		}
	});
	//When settings button is pressed : Slide transition (hide/show)
	$(".flip").click(function() {
		$("#settingsPanel").slideToggle();
	});
	//When a setting is changed this function saves that change in cookies
	$("#settingsPanel input").change(function() {
		var cookieName = $(this).attr("id").replace("-visibility", "");

		if ($(this).attr('checked') == "checked") {
			var newValue = "";
		} else {
			var newValue = "hidden";
		}
		
		$.cookie(cookieName, newValue, {
			expires : 7,
			path : '/'
		});

		var leftScoreDiff 		= parseInt($("#" + cookieName).children('.score-left').text());
		var rightScoreDiff 		= parseInt($("#" + cookieName).children('.score-right').text());
		var leftScoreCurrent  	= parseInt($("#leftScore").text());
		var rightScoreCurrent  	= parseInt($("#rightScore").text());

		if (newValue == "") {
			$("#" + cookieName).show();
			leftScoreCurrent = leftScoreCurrent + leftScoreDiff;
			rightScoreCurrent = rightScoreCurrent + rightScoreDiff;
		} else {
			$("#" + cookieName).hide();
			leftScoreCurrent = leftScoreCurrent - leftScoreDiff;
			rightScoreCurrent = rightScoreCurrent - rightScoreDiff;
		}

		$("#leftScore").text(leftScoreCurrent);
		$("#rightScore").text(rightScoreCurrent);

		var leftPostcode 		= $('#battle_postcode1').val();
		var rightPostcode 		= $('#battle_postcode2').val();

		if (leftScoreCurrent > rightScoreCurrent) {
			$('.congrats').html('<span>' + leftPostcode + '</span> wins!');
		} else if (leftScoreCurrent < rightScoreCurrent) {
			$('.congrats').html('<span>' + rightPostcode + '</span> wins!');
		} else {
			$('.congrats').text('Its a draw!');
		}
	});
	//When battle button is pressed it reads the two entered postcodes and loads up the categories and data
	$("#battle_submit").click(function(event) {
		//event.preventDefault();
		$("#search").after($('<div class="loader"></div>'));

		var postcode1 = $("#battle_postcode1").val();
		var postcode2 = $("#battle_postcode2").val();

		var url = "/results-json.php?postcode1=" + escape(postcode1) + "&postcode2=" + escape(postcode2);

		$.getJSON(url, function(data) {
			$(".loader").remove();
		});
	});
});
