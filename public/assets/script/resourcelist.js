(function($) {
  var piroxsi = window.piroxsi;
  
  $('.controlButton').each(function() {
    $(this).on('click', function() {
      var currentId = $(this).attr('data-id');
      
      piroxsi.setModalMessage('Connecting to Resource', 'green');      
      piroxsi.toggleModal();      
      
      $.get('download/' + currentId, function(newData, status) {
        if (!eval(newData.command)) {
          piroxsi.setModalMessage(newData.args.error, 'red');
        } else {
          window.location = 'status/' + currentId;
        }
      });
    });
  });
})(jQuery);