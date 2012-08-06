$(document).ready(function() {
    $(".nosubmit-form").submit(function(event) {
        event.preventDefault(); // Is this right?
        return false;
    });

    // Pasted from http://webdesignerwall.com/tutorials/cross-browser-html5-placeholder-text
    if(!Modernizr.input.placeholder){
        $('[placeholder]').focus(function() {
          var input = $(this);
          if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
          }
        }).blur(function() {
          var input = $(this);
          if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
          }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
          $(this).find('[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
              input.val('');
            }
          })
        });
    }
});
