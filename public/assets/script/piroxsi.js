(function($) {
  window.piroxsi = {
    config: null,
  
    setSpanMessage: function(id, message, color) {
      var lastMessage = $('<span />').attr('id', id)
                                     .html(message);
      
      if (color) {
        $(lastMessage).css('color', color);
      }
      
      $('#'+id).replaceWith(lastMessage);
    },
    
    setModalMessage: function(message, color) {
      window.piroxsi.setSpanMessage('lblModalMessage', message, color);
    },
    
    toggleModal: function() {
      $('#modalRefresh').modal('toggle');
    }    
  };
  
  $('#lnkRefreshResources').on('click', function() {
    window.piroxsi.setModalMessage('Refreshing resource list.  Please wait.');
    $('#modalRefresh').modal('show');

    $.get('/refresh', function(data, status) {
      if (eval(data.command)) {
        window.location = '/';
      } else {
        window.piroxsi.setModalMessage(data.args.error, 'red');        
      }
    }); 
  });
})(jQuery);