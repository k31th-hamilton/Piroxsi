(function($) {
  var p = window.piroxsi;
  
  var resourceStatus = {
    myTimeouts: null,
    
    refreshUpdate: function(oldData) {
      var instance = this;
      
      instance.setLastUpdateInfo();     
      instance.runCommand(oldData);      
      
      instance.myTimeouts = setTimeout($.proxy(function() {
        $.get('/refresh/update', function(newData, status) {
          if (newData.command && newData.command !== 'false') {            
            instance.refreshUpdate(newData);            
          } else {
            instance.setMessage('lblMessages', newData.args.error, 'label-danger');
            clearTimeout(instance.myTimeouts);
          }
        });
      }, this), 5000);
    },
    
    setMessage: function (lbl, message, clss) {
      
      if (clss) {
        $('#'+lbl).removeClass('label-warning')
                  .removeClass('label-danger')
                  .removeClass('label-success')
                  .addClass(clss)
                  .empty()
                  .html(message);
      } else {
        $('#'+lbl).empty().html(message);
      }
    },
    
    runCommand: function(command) {
      var rs = resourceStatus;
      
      switch (command.command) {
        case 'setmessage':
          rs.setMessage('lblConnectionInfo', command.args.message, command.args.cssclass);
          break;
        case 'pidnotfoundmessage':
          rs.setMessage('lblConnectionInfo', 'Disconnected', 'label-danger');
          rs.setMessage('lblIpInfo', 'None', 'label-danger');
          rs.setMessage('lblLastMessage', command.args.message, command.args.cssclass);
          break;
        case 'pidfoundmessage':
          rs.setMessage('lblConnectionInfo', 'Connected', 'label-success');
          rs.setMessage('lblLastMessage', command.args.message, command.args.cssclass);
          
          if (command.args.ipaddress !== 'none') {
            if (!command.args.ipaddress) {
              rs.setMessage('lblIpInfo', 'Awaiting DHCP', 'label-warning');
            } else {
              rs.setMessage('lblIpInfo', command.args.ipaddress, 'label-success');           
            }
          } else {
            rs.setMessage('lblIpInfo', 'None', 'label-danger');
          }
          break;        
      }
    },
    
    connect: function() {     
      resourceStatus.setMessage('lblConnectionInfo', 'Connecting...', 'label-warning');      
      var instance = this;
      
      $.get('/refresh/connect', function(data, status) {
        if (data.command && data.command !== 'false') {                              
          instance.refreshUpdate(data);         
        } else {
          instance.setMessage('lblMessages', data.args.error, 'label-danger');
        }
      });
    },
    
    disconnect: function() {
      var instance = this;
      
      $.get('/refresh/disconnect', function(data, status) {
        if (data.command && data.command === 'true') {
          clearTimeout(instance.myTimeouts);          
          window.location = '/';
        } else {
          instance.setMessage('lblMessages', data.error, 'label-danger');
        }
      });
    },
    
    setLastUpdateInfo: function() {
      resourceStatus.setMessage('lblLastUpdate_Date', (new Date()).toLocaleDateString() + ' ' +
                                                      (new Date()).toLocaleTimeString());
    }
  };
  
  $('#btnConnect').on('click', $.proxy(resourceStatus.connect, resourceStatus));  
  $('#btnDisconnect').on('click', $.proxy(resourceStatus.disconnect, resourceStatus));  
})(jQuery);