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

function search(postcode) {
    $.ajax({
        url: "/results.php?postcode=" + encodeURIComponent(postcode),
        dataType: "json",
        
        error: function(jqXHR, status, error) {
            alert("Error! - " + status + " - " + error);
        },
        
        success: function(data) {
            // do something with data. It is the same structure as the object constructed in
            // results.php
            alert(data);
        },
    });
}


