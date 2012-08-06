$(document).ready(function() {
    $("#search-button").click(function() {
        search($("#postcode-field").val());
    });
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
        },
    });
}
