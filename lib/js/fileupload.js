    
jQuery(function() {
    'use strict';
    $(document).on('click', ".fileupload", function (e) {
      e.preventDefault();
      jQuery.ajax({
        url: 'request/cst.php',
        type: 'POST',
        data:  {
          act: 'getfileupload', 
          id: $(this).data('invoice-id')
        },
        dataType: 'json',
        success: function (data) {
          $(".files").html(data.html);
        }
      });
      $('#fileId').val($(this).data('invoice-id'));
     $('#fileupload').modal({backdrop: 'static', keyboard: false});
  });
  
    // Initialize the jQuery File Upload widget:
  jQuery('#fileupload').fileupload({
      // Uncomment the following to send cross-domain cookies:
      //xhrFields: {withCredentials: true},
      url: 'includes/invoicefile.php',
      
  });
  jQuery('#fileupload').on("fileuploaddone", function (e, data) {
    
      $.each(data.result.files, function (index, file) {
        if(!file.error){
        jQuery.ajax({
          url: 'request/cst.php',
          type: 'POST',
          data:  {
            act: 'fileupload', 
            id: $('#fileId').val(),
            name: file.name
          },
          dataType: 'json',
          success: function (data) {
              if (data.status == "Success") {
                  $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                  $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                  $("html, body").scrollTop($("body").offset().top);
              } else {
                  $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                  $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                  $("html, body").scrollTop($("body").offset().top);

              }
              ;
          },
          error: function (data) {
              $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
              $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
              $("html, body").scrollTop($("body").offset().top);
          }
      });
    }
      });
    });
    
    jQuery('#fileupload').on("fileuploaddestroy", function (e, data) {
      jQuery.ajax({
          url: 'request/cst.php',
          type: 'POST',
          data:  {
            act: 'filedelete', 
            name: data.name
          },
          dataType: 'json',
          success: function (data) {
            $(".files").html(data.html);
          }
      });
    });

    // Enable iframe cross-domain access via redirect option:
    jQuery('#fileupload').fileupload(
      'option',
      'redirect',
      window.location.href.replace(/\/[^/]*$/, '/cors/result.html?%s')
    );
  
    
      // Load existing files:
      /*
      jQuery('#fileupload').addClass('fileupload-processing');
      $.ajax({
        url: jQuery('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: jQuery('#fileupload')[0]
        
      })
      
        .always(function() {
          jQuery(this).removeClass('fileupload-processing');
        })
        
        .done(function(result) {
          jQuery(this)
            .fileupload('option', 'done')
            // eslint-disable-next-line new-cap
            .call(this, $.Event('done'), { result: result });
        });
        */
    
  });