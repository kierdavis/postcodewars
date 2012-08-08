$(document).ready(function () {
    function pulsate() {
        $(".pulsate").animate({
            opacity: 0.2
        }, 1200, 'linear').animate({
            opacity: 1
        }, 1200, 'linear', pulsate).click(function () {
            $(this).animate({
                opacity: 1
            }, 1200, 'linear');
            $(this).stop();
        });
    }

    pulsate();
});

function search(postcode1, postcode2) {
    $.ajax({
        url: "/results-json.php?postcode1=" + encodeURIComponent(postcode1) + "&postcode2=" + encodeURIComponent(postcode2),
        dataType: "json",
        
        error: function(jqXHR, status, error) {
            alert("Error! - " + status + " - " + error);
        },
        
        success: function(data) {
            // do something with data. It is the same structure as the object constructed in
            // search.php
            alert(data);
        },
    });
}


