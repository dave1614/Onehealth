         <!-- End Navbar -->

      <script>
        
      </script>
      <div class="content">
        <div class="container-fluid">
          <h2>Welcome <?php echo $user_name; ?></h2>
          <div class="row">
            <div class="col-sm-8">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Change Your Password</h3>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'change-password-from');
                    echo form_open('',$attr);
                  ?>    
                  <div class="form-group">
                    <label for="old_password">Enter Old Password</label>
                    <input type="password" id="old_password" class="form-control" name="old_password">
                    <span class="form_error"></span>
                  </div>    
                  <div class="form-group">
                    <label for="new_password">Enter New Password</label>
                    <input type="password" id="new_password" class="form-control" name="new_password">
                    <span class="form_error"></span>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>        
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      
      <script>
        $(document).ready(function () {
          $("#change-password-from").submit(function (evt) {
            evt.preventDefault();
            var form_data = $(this).serializeArray();
            form_data.push({
              'name' : 'change_password',
              'value' : 'true'
            });
            var url = "<?php echo site_url('onehealth/process_change_password') ?>";
            // console.log(form_data);
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : form_data,
              success : function (response) {
                if(response.success == true){
                  window.location.assign("<?php echo site_url('onehealth/cl_admin') ?>");
                }else if(response.wrong_password == true){
                  $.notify({
                    message:"Wrong Password Inputed. Please Try Again"
                  },{
                    type : "warning"  
                  });
                }else{
                  $.each(response.messages, function (key,value) {

                  var element = $('#'+key);
                  
                  element.closest('div.form-group')
                          
                          .find('.form-error').remove();
                  element.after(value);
                  
                 });
                  $.notify({
                  message:"Some Values Where Not Valid. Please Enter Valid Values"
                  },{
                    type : "warning"  
                  });
                }
              },error : function () {
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              } 
            });   
          })
        })
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 