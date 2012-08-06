$(document).ready(function() {
    $(".nosubmit-form").submit(function(event) {
        event.preventDefault(); // Is this right?
        return false;
    });
});
