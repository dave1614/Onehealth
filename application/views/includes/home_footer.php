<div class="modal fade" data-backdrop="static" id="login-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center" style="color: #000;">Create Your Free Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-body">
          <p class="">Sign Up As: </p>
          <a href="<?php echo site_url('onehealth/sign_in_as_health_facility') ?>" class="btn btn-success">Health Facility</a>
          <a href="<?php echo site_url('onehealth/sign_up_as_patient') ?>" class="btn btn-info">Patient</a>
          
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
        </div>
      </div>
    </div>
  </div>

  <footer style="height: 200px; background: #000; margin-top: 30px;">
    
  </footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url('assets/js/bootstrap-notify.js')?> "></script>
  <script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/js/sweetalert2.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/js/swal-forms.js') ?>"></script>

  <script>
    $(document).ready(function () {
      $("#send-message-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var form_data = $(this).serializeArray();
        var url = $(this).attr("action");
        var submit_btn1 = $(this).find("button");
        var submit_btn_spinner1 = $(this).find(".spinner");
        submit_btn1.addClass('disabled');
        submit_btn_spinner1.show();
        $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              submit_btn_spinner1.hide();
              submit_btn1.removeClass("disabled");
              console.log(response)
              if(response.success){
                me.find("input").val("");
                me.find("textarea").val("");
                me.find("form-error").html("");
                $.notify({
                  message:"Message Sent Successfully."
                  },{
                    type : "success"  
                });
              }else{
                $.each(response.messages, function (key,value) {

                  var element = $('#'+key);
                  
                  element.closest('div.form-group')
                          
                          .find('.form-error').remove();
                  element.after(value);
                  
                 });
                $.notify({
                  message:"Sorry Something Went Wrong."
                  },{
                    type : "warning"  
                });
              }
            },error : function (jqXHR,error, errorThrown) {
              submit_btn_spinner1.hide();
              submit_btn1.removeClass("disabled");
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            }
        });  
      });
      // showLogInModal();
     
    })

    
  </script>
  
</body>

</html>