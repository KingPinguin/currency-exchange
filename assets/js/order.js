var $ = require('jquery');

$( "#get_quote" ).submit(function( event ) {
    $.post($(this).attr('action'), $(this).serialize(), function(json) {
        alert(json);
      }, 'json');

      return false;
  });
