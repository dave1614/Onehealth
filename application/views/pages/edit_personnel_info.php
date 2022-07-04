

</style>
<script>
  function goBack(){
    window.history.back();
  }
</script>
      <!-- End Navbar -->
      

      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
     
      <div class="content" tabindex="-1">
        <div class="container-fluid">
          
         <h2 class="text-center">Edit Personnel Details</h2>
          <?php
            $logged_in_user_name = $user_name;
           
          ?>
          
          <div class="row justify-content-center">

            <div class="col-sm-6">
              <button class="btn btn-warning" onclick="goBack()"> < < Go Back</button>
              <!-- <h1>remember to ask for signature and qualifications if not available and build the page</h1> -->

              <div class="card" id="main-card">
                <!-- <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit Your</h3>
                </div> -->
                <div class="card-body">

                  <?php
                  $attr = array('id' => 'edit-personnel-info-form');
                  echo form_open_multipart("onehealth/process_edit_personnel_form",$attr);
                  ?>
                    <div class="form-group">
                      <label for="title">Title: </label>
                      <input type="text" class="form-control" name="title" id="title" value="<?php echo $this->onehealth_model->getUserParamById("title",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="full_name">Full Name: </label>
                      <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $this->onehealth_model->getUserParamById("full_name",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="qualification">Qualifications: </label>
                      <input type="text" class="form-control" name="qualification" id="qualification" value="<?php echo $this->onehealth_model->getUserParamById("qualification",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>
                    
                    <?php
                    $signature = $this->onehealth_model->getUserParamById("signature",$user_id);
                    // echo $signature;
                    if($signature != ""){
                    ?>
                    <p><em>This Is Your Current Signature. Select Another Image Below.</em></p>
                    <img src="<?php echo base_url('assets/images/'.$signature) ?>" alt="Signature Image" style="width: 200px; height: 200px;">
                    <?php 
                    }
                    ?>
                    

                    <div class="">
                      <label for="signature">Signature: </label>
                      <input class="" type="file" name="signature" id="signature" accept="image/*" >
                       <span class="form-error"></span>
                    </div>
                    
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
                </div>
              </div>

              </div> 
            </div>
          </div>
          
        </div>
      </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer> -->
        </div>
       
      </footer>
  </div>
  
  
</body>
<script>
  $(document).ready(function() {
    $("#edit-personnel-info-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        // var elem = me[0]
        var form_data = new FormData();
        var title = me.find('#title').val();
        var full_name = me.find('#full_name').val();
        var qualification = me.find('#qualification').val();
        var signature = document.querySelector('#edit-personnel-info-form #signature');
        console.log(typeof signature.files)
        if (typeof signature.files !== 'undefined') {
          var img_count = signature.files.length;
          console.log(img_count)
        }
        

        form_data.append('title',title);
        form_data.append('full_name',full_name);
        form_data.append('qualification',qualification);
        if (typeof signature.files !== 'undefined') {
          // if(img_count == 1){
            form_data.append('signature',signature.files[0]);
          // }
        }
        console.log(form_data)
        
        $(".spinner-overlay").show();
        $.ajax({
          url : me.attr("action"),
          type : "POST",
          cache: false,
          dataType : "json",
          contentType: false,
          processData: false,
          data : form_data,
          success : function (response) {
            $(".spinner-overlay").hide();
            console.log(response)
            if(response.success){
              $.notify({
                message:"Successful"
                },{
                  type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              }, 1500)
            }else if(!response.signature_already_in_database && response.no_image){
              $.notify({
                message:"You Currently Do Have Not Uploaded A Signature Before. Please Select One And Upload."
                },{
                  type : "warning"  
              });
            }else if(response.multiple_images){
              $.notify({
                message:"You Can Only Upload One Image."
                },{
                  type : "warning"  
              });
            }else if(response.ci_image_upload_err != ""){
              var text = "<em class='text-primary'>An Error Occured When Uploading Your Signature.</em>";
              text += response.ci_image_upload_err;
              swal({
                title: 'Ooops!',
                text: text,
                type: 'error'         
              })
            }else{
              $.each(response.messages, function (key,value) {

                var element = me.find('#'+key);
                
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
            $(".spinner-overlay").hide();
            $.notify({
              message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
              },{
                type : "danger" 
            });
          }
        });
      });
  });
</script>
