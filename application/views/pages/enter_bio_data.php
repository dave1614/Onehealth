         <!-- End Navbar -->
      <?php
        if(is_array($curr_health_facility_arr)){
          foreach($curr_health_facility_arr as $row){
            $hospital_id = $row->id;
            $hospital_name = $row->name;
            $hospital_logo = $row->logo;
            $hopsital_email = $row->email;
            $hospital_phone = $row->phone;
            $hospital_country = $this->onehealth_model->getCountryById($row->country);
            $hospital_state = $this->onehealth_model->getStateById($row->state);
            $hospital_address = $row->address;
            $hospital_slug = $row->slug;
            $hospital_table_name = $row->table_name;
            $facility_structure = $row->facility_structure;
            $color = $row->color;
            $no_logo = false;
            $patient_bio_data_table = $this->onehealth_model->createpatientBioDataTableString($hospital_id,$hospital_name);
            
            $is_admin = $this->onehealth_model->checkIfUserIsAdminOfFacility($hospital_table_name,$user_id);
            if($this->onehealth_model->checkIfBioDataHasBeenEnteredByPatient($user_name,$user_id)){
              $bio_data_entered = true;
            }else{
              $bio_data_entered = false;
            }
          }
        }    
      ?>
      <script>
         function registerPatient (elem) {
            var facility_table_name = String(elem.getAttribute("data-hospital-name1"));
            var hospital_name = String(elem.getAttribute("data-hospital-name"));
            var hospital_id = elem.getAttribute("data-hosid");
            var url = "<?php echo site_url('onehealth/register_patient'); ?>";
            var data = "table_name="+facility_table_name+"&facility_name="+hospital_name;
             var str = "registered_num";
             $(".spinner-overlay").show();
            $.ajax({
              url : url,
              type : "POST",
              responseType : "text",
              dataType : "text",
              data : data,
              success : function(response){
                $(".spinner-overlay").hide();
                if(response == "values_messed"){
                  $.notify({
                  message:"Sorry Something Went Wrong"
                  },{
                    type : "warning"  
                  });
                }else if(response == "could not register patient"){
                 $.notify({
                  message:"Sorry You Could Not Be Registered"
                  },{
                    type : "warning"  
                  });
                }else if(response == "already_registered"){
                 $.notify({
                  message:"Sorry You've Already Registered On This Facility"
                  },{
                    type : "warning"  
                  });
                }else if(response == "successful"){
                  document.location.reload();
                }
              },
              error: function () {
                $(".spinner-overlay").hide();
                 $.notify({
                  message:"Sorry You Could Not Be Registered"
                  },{
                    type : "danger"  
                  });
              }
            })
        }

        function proceedBtn(elem) {
          document.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/access-laboratory-services'); ?>");
        }

      </script>
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-10">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?php if($bio_data_entered){ echo "Edit"; } else{ echo "Enter"; } ?> Your Bio Data</h3>
                </div>
                <div class="card-body">
                  <?php if($this->onehealth_model->checkIfUserIsRegisteredOnThisFacility($hospital_table_name,$user_name,$hospital_name,$user_id)){ ?>
                  <?php if($bio_data_entered){ ?>
                  <p class="text-secondary">Check Your Bio Data And Edit To Proceed</p>
                  
                  <?php }else{ ?>
                    <p class="text-secondary">Enter Your Bio Data To Proceed</p>
                  <?php } ?>

                  <?php if(!$bio_data_entered){ ?>
                  <?php $attr = array('id' => 'enter-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">             
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
                      <div class="form-group col-sm-6">
                        <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                        <input type="date" class="form-control" name="dob" id="dob">
                        <span class="form-error"></span>
                      </div>
                      
                      <div class="form-group col-sm-2">
                        <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                        <input type="number" class="form-control" name="age" id="age">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <div id="age_unit">
                          <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                          <select name="age_unit" id="age_unit" class="form-control selectpicker" data-style="btn btn-link">
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years" selected>Years</option>

                          </select>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <p class="label"><span class="form-error1">*</span> Gender: </p>
                        <div id="sex">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                        <input type="text" class="form-control" id="race" name="race">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                        <input type="number" class="form-control" id="mobile" name="mobile">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                        <input type="email" class="form-control" id="email" name="email">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="wrap">
                    <h4 class="form-sub-heading">Medical Information</h4>
                    <div class="form-row">
                     
                      <div class="form-group col-sm-4">
                        <label for="height" class="label-control"><span class="form-error1">*</span> Height(metre): </label>
                        <input type="number" step="any" max="3" class="form-control" id="height" name="height" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="weight" class="label-control"><span class="form-error1">*</span> Weight(kg): </label>
                        <input type="number" step="any" class="form-control" id="weight" name="weight">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <p class="label"><span class="form-error1">*</span> Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="present-medications" class="label-control">Present Medications: </label>
                        <input type="text" class="form-control" id="present-mediacations" name="present_medications">
                        <span class="form-error"></span>
                      </div>                      
                                       
                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  <?php }else{ ?>
                  <?php
                    $patient_bio_data = $this->onehealth_model->getPatientData($user_id,$user_name);
                    if(is_array($patient_bio_data)){
                      foreach($patient_bio_data as $row){
                        $id = $row->id;
                        $first_name = $row->firstname;
                        $last_name = $row->lastname;
                        $dob = $row->dob;
                        $age = $row->age;
                        $age_unit = $row->age_unit;
                        $sex = $row->sex;
                        $fasting = $row->fasting;
                        $race = $row->race;
                        $mobile = $row->mobile_no;
                        $email = $row->email;
                        $present_medications = $row->present_medications;
                        $height = $row->height;
                        $weight = $row->weight;
                        $address = $row->address;
                        $date = $row->date;
                        $time = $row->time;
                      
                  ?>
                    <?php $attr = array('id' => 'edit-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">   
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">              
                      <div class="form-group col-sm-6">
                        <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $dob; ?>">
                        <span class="form-error"></span>
                      </div>
                      
                      <div class="form-group col-sm-2">
                        <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                        <input type="number" class="form-control" name="age" id="age" value="<?php echo $age; ?>">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <div id="age_unit">
                          <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                          <select name="age_unit" id="age_unit" class="form-control selectpicker" data-style="btn btn-link">
                            <option value="minutes" <?php if($age_unit == "minutes"){ echo "selected"; } ?>>Minutes</option>
                            <option value="hours" <?php if($age_unit == "hours"){ echo "selected"; } ?>>Hours</option>
                            <option value="days" <?php if($age_unit == "days"){ echo "selected"; } ?>>Days</option>
                            <option value="weeks" <?php if($age_unit == "weeks"){ echo "selected"; } ?>>Weeks</option>
                            <option value="months" <?php if($age_unit == "months"){ echo "selected"; } ?>>Months</option>
                            <option value="years" <?php if($age_unit == "years"){ echo "selected"; } ?>>Years</option>

                          </select>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <p class="label"><span class="form-error1">*</span> Gender: </p>
                        <div id="sex">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="female" id="female" <?php if($sex == "female"){ echo "checked"; } ?>> Female
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="male" id="male" <?php if($sex == "male"){ echo "checked"; } ?>> Male
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="na"> N/A
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                        <input type="text" class="form-control" id="race" name="race" value="<?php echo $race; ?>">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                        <input type="number" class="form-control" id="mobile" name="mobile" value="<?php echo $mobile; ?>">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="wrap">
                    <h4 class="form-sub-heading">Medical Information</h4>
                    <div class="form-row">
                     
                      <div class="form-group col-sm-4">
                        <label for="height" class="label-control"><span class="form-error1">*</span> Height(metre): </label>
                        <input type="number" step="any" max="3" class="form-control" id="height" name="height" value="<?php echo $height; ?>">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="weight" class="label-control"><span class="form-error1">*</span> Weight(kg): </label>
                        <input type="number" step="any" class="form-control" id="weight" name="weight" value="<?php echo $weight; ?>">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <p class="label"><span class="form-error1">*</span> Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="yes" <?php if($fasting == 1){ echo "checked"; } ?>> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="no" <?php if($fasting == 0){ echo "checked"; } ?>> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="present-medications" class="label-control">Present Medications: </label>
                        <input type="text" class="form-control" id="present-mediacations" name="present_medications" value="<?php echo $present_medications; ?>">
                        <span class="form-error"></span>
                      </div>                      
                                       
                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control"><?php echo $address; ?></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  <?php } } } ?>
                  <?php }else{ ?>
                    <h6 class="form-error">Sorry You Are Not Registered On This Facility. You Need To Register To Proceed</h6>
                    <button class="btn btn-success" data-hospital-name1="<?php echo $hospital_table_name; ?>" data-hospital-name="<?php echo $hospital_name ?>" data-hosid="<?php echo $hospital_id;?>" onclick="return registerPatient(this)">Register</button>
  
                  <?php } ?>
                </div>

              </div>
            </div>
          </div>



        </div>
        <?php if($bio_data_entered){ ?>
        <div onclick="proceedBtn(this)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Proceed To Tests Selection And Payment" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

            </div>
          </div>
          <?php } ?>
      </div>
  
      

      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      
      <script>
        $(document).ready(function() {
          var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
          $("#enter-bio-data-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);           
            
            if(format.test($('#edit-bio-data-form #first_name').val()) || format.test($('#edit-bio-data-form #last_name').val())){
                swal({
                  type: 'error',
                  title: 'Oops.....',
                  text: 'First Name And Last Name Fields Can Only Contain Letters!'
                  // footer: '<a href>Why do I have this issue?</a>'
                })
            }else{
              $(".spinner-overlay").show();
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/submit-patient-bio-data'); ?>";
              var values = me.serializeArray();
              
              $.ajax({
                url : url,
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : values,
                success : function (response) {
                  console.log(response)
                  $(".spinner-overlay").hide();
                  if(response.success == true && response.successful == true){
                    
                    document.location.reload();
                  }else if(response.success == true && response.successful == false){
                    $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "warning"  
                    });
                  }
                  else{
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
                },
                error: function (jqXHR,textStatus,errorThrown) {
                  $(".spinner-overlay").hide();
                   $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "danger"  
                    });
                }
              }); 
            } 
          })

          $("#edit-bio-data-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);
            
            if(format.test($('#edit-bio-data-form #first_name').val()) || format.test($('#edit-bio-data-form #last_name').val())){
              swal({
                  type: 'error',
                  title: 'Oops.....',
                  text: 'First Name And Last Name Fields Can Only Contain Letters!'
                  // footer: '<a href>Why do I have this issue?</a>'
                })
            }else{
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/submit-patient-bio-data'); ?>";
              var values = me.serializeArray();
              $(".spinner-overlay").show();
              $.ajax({
                url : url,
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : values,
                success : function (response) {
                  console.log(response)
                  $(".spinner-overlay").hide();
                  if(response.success == true && response.successful == true){
                    
                    document.location.reload();
                  }else if(response.success == true && response.successful == false){
                    $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "warning"  
                    });
                  }
                  else{
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
                },
                error: function (jqXHR,textStatus,errorThrown) {
                  $(".spinner-overlay").hide();
                   $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "danger"  
                    });
                }
              });
            }  
          })
        <?php
         if($this->session->added_successfully){ 
          ?>
          $.notify({
          message:"Successfully Added"
          },{
            type : "success"  
          });
        <?php } ?>  
        
      });
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 