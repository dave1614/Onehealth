

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
         <h2 class="text-center">Edit Your Patient Information</h2>
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
                  $attr = array('id' => 'edit-patient-info-form');
                  echo form_open_multipart("onehealth/process_edit_patient_form",$attr);
                  ?>
                    <div class="form-group">
                      <label for="title">Title: </label>
                      <input type="text" class="form-control" name="title" id="title" value="<?php echo $this->onehealth_model->getPatientParamByUserId("title",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="first_name">First Name: </label>
                      <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $this->onehealth_model->getPatientParamByUserId("first_name",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="last_name">Last Name: </label>
                      <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $this->onehealth_model->getPatientParamByUserId("last_name",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="dob"> Date Of Birth: </label>
                      <input type="text" class="form-control " name="dob" id="dob" value="<?php echo $this->onehealth_model->getPatientParamByUserId("dob",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>


                    <div class="form-group">
                      <p class="label"> Gender: </p>
                      <div id="sex">

                        <div class="form-check form-check-radio form-check-inline">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="sex" value="male" id="male" <?php if($this->onehealth_model->getPatientParamByUserId("sex",$user_id) == "male"){ echo "checked"; } if($this->onehealth_model->getPatientParamByUserId("sex",$user_id) == ""){ echo "checked"; }?>> Male
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                          </label>
                        </div>

                        <div class="form-check form-check-radio form-check-inline">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="sex" value="female" id="female" <?php if($this->onehealth_model->getPatientParamByUserId("sex",$user_id) == "female"){ echo "checked"; } ?>> Female
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                          </label>
                        </div>
                        
                      </div>
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="race">Race: </label>
                      <input type="text" class="form-control" name="race" id="race" value="<?php echo $this->onehealth_model->getPatientParamByUserId("race",$user_id); ?>">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group">
                      <label for="address">Address: </label>
                      <textarea name="address" class="form-control" id="address" cols="30" rows="10"><?php echo $this->onehealth_model->getPatientParamByUserId("address",$user_id); ?></textarea>
                      <span class="form-error"></span>
                    </div>

                    <h4 class="form-sub-heading text-center">Next Of Kin Information</h4>
                    <div class="wrap">
                      
                        <div class="form-group">
                          <label for="name_of_next_of_kin">Full Name: </label>
                          <input type="text" class="form-control" name="name_of_next_of_kin" id="name_of_next_of_kin" value="<?php echo $this->onehealth_model->getPatientParamByUserId("name_of_next_of_kin",$user_id); ?>">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group">
                          <label for="mobile_no_of_next_of_kin">Mobile No.: </label>
                          <input type="number" class="form-control" name="mobile_no_of_next_of_kin" id="mobile_no_of_next_of_kin" value="<?php echo $this->onehealth_model->getPatientParamByUserId("mobile_no_of_next_of_kin",$user_id); ?>">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group">
                          <label for="username_of_next_of_kin">User Name: </label>
                          <input type="text" class="form-control" name="username_of_next_of_kin" id="username_of_next_of_kin" value="<?php echo $this->onehealth_model->getPatientParamByUserId("username_of_next_of_kin",$user_id); ?>">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group">
                          <label for="relationship_of_next_of_kin">Relationship: </label>
                          <input type="text" class="form-control" name="relationship_of_next_of_kin" id="relationship_of_next_of_kin" value="<?php echo $this->onehealth_model->getPatientParamByUserId("relationship_of_next_of_kin",$user_id); ?>">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group">
                          <label for="address_of_next_of_kin">Address:</label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" class="form-control" cols="30" rows="10"><?php echo $this->onehealth_model->getPatientParamByUserId("address_of_next_of_kin",$user_id); ?></textarea>
                          <span class="form-error"></span>
                        </div>
                      
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

    $("#dob").datetimepicker({
      defaultDate : false,
      format: 'MM/DD/YYYY',
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    });
    $("#edit-patient-info-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        // var elem = me[0]
        var form_data = me.serializeArray();
        console.log(form_data)
        
        $(".spinner-overlay").show();
        $.ajax({
          url : me.attr("action"),
          type : "POST",
          dataType : "json",
          responseType : "json",
          data : form_data,
          success : function (response) {
            $(".spinner-overlay").hide();
            console.log(response)
            if(response.success){
              $.notify({
                message:"Your Patient Information Has Been Edited Successful"
                },{
                  type : "success"  
              });
              
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
