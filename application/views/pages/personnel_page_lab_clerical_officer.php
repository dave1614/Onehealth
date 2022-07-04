       <?php
        if(is_array($curr_health_facility_arr)){
          foreach($curr_health_facility_arr as $row){
            $health_facility_id = $row->id;
            $health_facility_name = $row->name;
            $health_facility_logo = $row->logo;
            $health_facility_structure = $row->facility_structure;
            $health_facility_email = $row->email;
            $health_facility_phone = $row->phone;
            $health_facility_country = $row->country;
            $health_facility_state = $row->state;
            $health_facility_address = $row->address;
            $health_facility_table_name = $row->table_name;
            $health_facility_date = $row->date;
            $health_facility_time = $row->time;
            $health_facility_slug = $row->slug;
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
      ?>

      <script>
        
        function loadPatientInfoEdit (elem,lab_id) {
          $(".spinner-overlay").show();
          var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_tests') ?>";
          $.ajax({
            url : get_patients_tests,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get_patients_tests=true&lab_id="+lab_id,
            success : function (response) {  
              var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_bio_data') ?>";
              $.ajax({
                url : get_patients_tests,
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : "get_patient_bio_data=true&lab_id="+lab_id,
                success : function (response1) {  
                  
                  $(".spinner-overlay").hide();
                  $("#created-paid-patients-list-card").hide("fast");
                  var full_name = response1.first_name + " " + response1.last_name;
                  $(".edit_card-title").html("Edit " + full_name + "'s Bio Data");
                  var first_name = response1.first_name;
                  var last_name = response1.last_name;
                  var venous_blood = response1.venous_blood;
                  var arterial_blood = response1.arterial_blood;
                  var capillary_blood = response1.capillary_blood;
                  var vitreous = response1.vitreous;
                  var vitreous_fluid = response1.vitreous_fluid;
                  var serum = response1.serum;
                  var address = response1.address;
                  var clinical_summary = response1.clinical_summary;
                  var consultant = response1.consultant;
                  var consultant_email = response1.consultant_email;
                  var consultant_mobile = response1.consultant_mobile;
                  var csf = response1.csf;
                  var urine = response1.urine;
                  var dob = response1.dob;
                  var email = response1.email;
                  var fasting = response1.fasting;
                  var height = response1.height1;
                  var weight = response1.weight;
                  var mobile = response1.mobile_no;
                  if(mobile == 0){
                    mobile = "";
                  }
                  var race = response1.race;
                  var sex = response1.sex;
                  var age = response1.age;
                  var age_unit = response1.age_unit;
                  var referring_dr = response1.referring_dr;
                  var present_medications = response1.present_medications;
                  var sample_other = response1.sample_other;
                  var pathologist = response1.pathologist;
                  var pathologist_email = response1.pathologist_email;
                  var pathologist_mobile = response1.pathologist_mobile;
                  if(pathologist_mobile == 0){
                   pathologist_mobile = "";
                  }if(consultant_mobile == 0){
                    consultant_mobile == "";
                  }
                  var lmp = response1.lmp;

                  $("#edit_first_name").val(first_name);
                  $("#edit_last_name").val(last_name);
                  $("#edit_dob").val(dob);
                  if(sex == 'male'){
                    $("#edit_male").prop('checked', true);
                  }else{
                    $("#edit_female").prop('checked', true);
                  }
                  $("#edit_race").val(race);
                  $("#edit_mobile").val(mobile);
                  $("#edit_email").val(email);
                  $("#edit_clinical_summary").text(clinical_summary);
                  $("#edit_height").val(height);
                  $("#edit_weight").val(weight);
                  $("#edit_present_medications").val(present_medications);
                  if(fasting == 1){
                    $("#edit_fasting_yes").prop('checked', true);
                  }else if(fasting == 0){
                    $("#edit_fasting_no").prop('checked', true);
                  }
                  $("#edit_sample_other").val(sample_other);
                  $("#edit_referring_dr").val(referring_dr);
                  $("#edit_address").text(address);
                  $("#edit_consultant").val(consultant);
                  $("#edit_age").val(age);
                  $("#edit_age_unit").val(age_unit);
                  $("#edit_consultant_email").val(consultant_email);
                  $("#edit_consultant_mobile").val(consultant_mobile);
                  $("#edit_pathologist").val(pathologist);
                  $("#edit_pathologist_email").val(pathologist_email);
                  $("#edit_pathologist_mobile").val(pathologist_mobile);
                  $("#edit_lmp").val(lmp);
                  if(venous_blood == 1){
                    $("#edit_venous_blood").prop('checked', true);
                  }
                   if(arterial_blood == 1){
                    $("#edit_arterial_blood").prop('checked', true);
                  }
                   if(capillary_blood == 1){
                    $("#edit_capillary_blood").prop('checked', true);
                  }
                   if(urine == 1){
                    $("#edit_urine").prop('checked', true);
                  }
                   if(csf == 1){
                    $("#edit_csf").prop('checked', true);
                  }
                   if(vitreous == 1){
                    $("#edit_vitreous").prop('checked', true);
                  }
                   if(vitreous_fluid == 1){
                    $("#edit_vitreous_fluid").prop('checked', true);
                  }
                   if(serum == 1){
                    $("#edit_serum").prop('checked', true);
                  }
                 
                  $(".list-of-tests").append(response);
                  $("#edit-bio-data-form").attr('data-lab-id',lab_id);
                  $("#edit-bio-data-card").show("fast");
                },
                error:function () {
                 $(".spinner-overlay").hide(); 
                }

              });
              
            },
            error : function () {
              
            }  

          }); 
        }
        
        function loadPatientInfo (elem,initiation_code,lab_id,patient_name,patient_email) {
          $(".spinner-overlay").show();

          var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_tests') ?>";
          $.ajax({
            url : get_patients_tests,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get_patients_tests=true&lab_id="+lab_id+"&initiation_code="+initiation_code,
            success : function (response) {   
                   
              $(".spinner-overlay").hide();
              $("#paid-patients-list-card").hide("fast");
              $("#enter-bio-data-card .card-title").html("Enter " + patient_name + "'s Bio Data");
              $("#first_name").val(patient_name);
              $("#email").val(patient_email);
              $(".list-of-tests").append(response);
              $("#enter-bio-data-form").attr('data-lab-id',lab_id);
              $("#enter-bio-data-card").show("fast");  
            },
            error : function () {
              
            }  

          });
         
        }


        function loadPatientInfo2(elem,initiation_code,lab_id,first_name,last_name,dob,age,age_unit,sex,race,email,height,phone,weight,fasting){
          $(".spinner-overlay").show();
          var address = elem.getAttribute("data-address")
          var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_tests') ?>";
          $.ajax({
            url : get_patients_tests,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get_patients_tests=true&lab_id="+lab_id+"&initiation_code="+initiation_code,
            success : function (response) {
            var patient_name = first_name + " " + last_name                      
              $(".spinner-overlay").hide();
              $("#paid-patients-list-card").hide("fast");
              $("#enter-bio-data-card .card-title").html("Enter " + patient_name + "'s Bio Data");
              
              
              $(".list-of-tests").append(response);
              $("#first_name").val(first_name);
              // $("#enter_fa").val(first_name);
              $("#last_name").val(last_name);
              // $("#zzz").val(initiation_code);
              $("#dob").val(dob);
              if(sex == 'male'){
                $("#male").prop('checked', true);
              }else{
                $("#female").prop('checked', true);
              }
              $("#race").val(race);
              $("#mobile").val(phone);
              $("#email").val(email);
              // $("#edit_clinical_summary").text(clinical_summary);
              $("#height").val(height);
              $("#weight").val(weight);
              
              if(fasting == 1){
                $("#fasting_yes").prop('checked', true);
              }else if(fasting == 0){
                $("#fasting_no").prop('checked', true);
              }
              
              $("#address").val(address);
              
              $("#age").val(age);
              $("#age_unit").val(age_unit);
              $("#enter-bio-data-form").attr('data-lab-id',lab_id);
              $("#enter-bio-data-card").show("fast");

          
            },
            error : function () {
              
            }  

          });
         
        }
        
        function checkAll () {
          $("input[type=checkbox]:visible").prop('checked', true);
        }
      
        function goDefault(){
          document.location.reload();
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
         
          <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <?php
            $logged_in_user_name = $user_name;
            $user_position = $this->onehealth_model->getUserPosition($health_facility_table_name,$user_id);
            $personnel_info = $this->onehealth_model->getPersonnelBySlug($fourth_addition);
            if(is_array($personnel_info)){
              
              foreach($personnel_info as $personnel){
                $personnel_id = $personnel->id;
                $spersonnel_dept_name = $personnel->name;
                $personnel_slug = $personnel->slug;
                $personnel_sub_dept = $personnel->sub_dept_id;
                $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                
                  $health_facility_table_info = $this->onehealth_model->getHealthFacilityTableBySubDeptDeptAndPosition($health_facility_table_name,$second_addition,$third_addition,$fourth_addition);
                  if(is_array($health_facility_table_info)){
                    foreach($health_facility_table_info as $user){
                      $personnel_user_name = $user->user_name;
                      $user_name_slug = url_title($personnel_user_name);
                    }
                  }
                
              }
            }
          ?>
          <?php
           if($this->onehealth_model->checkIfUserIsATopAdmin2($health_facility_table_name,$user_id)){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a>&nbsp;&nbsp; > >  <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$third_addition.'/admin') ?>" class="text-info"><?php echo $sub_dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  } elseif($user_position == "sub_admin"){ ?>
           <span style="text-transform: capitalize; font-size: 13px;" ><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin') ?>" class="text-info"><?php echo $sub_dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  } ?>
          <h3 class="text-center" style="text-transform: capitalize;"><?php echo $personnel_name; ?></h3>
          <?php if($user_position == "admin" || $user_position == "sub_admin"){ ?>
            <?php if($personnel_num > 0){ ?>
          <h4>No. Of Personnel: <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/personnel') ?>"><?php echo $personnel_num; ?></a></h4>
        <?php } ?>
          <?php } ?>
          <div class="row">
            <div class="col-sm-12">

              <div class="card col-sm-10" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button id='go-back' style="display: none;" class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  <?php 
                    $attr = array('id' => 'mark-tests-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>

                  <div class="btn-grp">
                    
                    <button class="btn btn-info btn-action" id="register-patient">Register Patient</button>
                    
                    <button class="btn btn-success btn-action" id="edit-registered-patients">Edit Old Registration</button>
                  </div>
                  
                </div>
              </div>
              
              <div class="card col-sm-10" id="paid-patients-list-card" style="display: none;">
                <div class="card-header">
                  
                  <h3 class="card-title" id="welcome-heading">All Patients Waiting To Be Registered</h3>
                  <button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  
                </div>
                <div class="card-body">
                  
                </div>               
              </div>

              <div class="card col-sm-10" id="created-paid-patients-list-card" style="display: none;">
                <div class="card-header">
                  
                  <h3 class="card-title" id="welcome-heading">All Entered Patients</h3>
                  <button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  
                </div>
                <div class="card-body">
                  
                </div>                
              </div>

              <div class="card col-sm-10" id="enter-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                   <button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'enter-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">                 
                      <div class="form-group col-sm-6">
                        <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="last_name" class="label-control"><span class="form-error1">*</span>  LastName: </label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="dob" class="label-control"><span class="form-error1">*</span>  Date Of Birth: </label>
                        <input type="date" class="form-control" name="dob" id="dob">
                        <span class="form-error"></span>
                      </div>
                      
                      <div class="form-group col-sm-2">
                        <label for="age" class="label-control"><span class="form-error1">*</span>  Age: </label>
                        <input type="number" class="form-control" name="age" id="age">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <div id="age_unit">
                          <label for="age_unit" class="label-control"><span class="form-error1">*</span>  Age Unit: </label>
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
                        <p class="label"><span class="form-error1">*</span>  Gender: </p>
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
                        <label for="race" class="label-control"><span class="form-error1">*</span>  Race/Tribe: </label>
                        <input type="text" class="form-control" id="race" name="race">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="mobile" class="label-control"><span class="form-error1">*</span>  Mobile No: </label>
                        <input type="number" class="form-control" id="mobile" name="mobile">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="email" class="label-control"><span class="form-error1">*</span>  Email: </label>
                        <input type="email" class="form-control" id="email" name="email">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="wrap">
                    <h4 class="form-sub-heading">Medical Information</h4>
                    <div class="form-row">
                     
                      <div class="form-group col-sm-4">
                        <label for="height" class="label-control"><span class="form-error1">*</span>  Height(metre): </label>
                        <input type="number" step="any" max="2" class="form-control" id="height" name="height" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="weight" class="label-control"><span class="form-error1">*</span>  Weight(kg): </label>
                        <input type="number" step="any" class="form-control" id="weight" name="weight">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <p class="label"><span class="form-error1">*</span>  Fasting?</p>
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
                        <label for="lmp" class="label-control">LMP: </label>
                        <input type="date" class="form-control" name="lmp" id="lmp">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="clinical_summary" class="label-control"><span class="form-error1">*</span>  Clinical Summary/Diagnosis: </label>
                        <textarea name="clinical_summary" id="clinical_summary" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>  Sample: </p>
                        <div id="sample_other">
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="venous blood" id="venous_blood"> Venous Blood
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="arterial blood" id="arterial_blood"> Arterial Blood
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="capillary blood" id="capillary_blood"> Capillary Blood
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>

                           <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="urine" id="urine"> Urine
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="csf" id="csf"> CSF
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="vitreous" id="vitreous"> Vitreous
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>

                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="vitreous fluid" id="vitreous_fluid"> Vitreous Fluid
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="serum" id="serum"> Serum /Plasma
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>

                          <input type="text" class="form-control" placeholder="Other..." class="sample" id="sample_other" name="sample_other"> 
                        </div>
                        <span class="form-error"></span>       
                      </div>
                      
                      <div class="form-group col-sm-9">
                        <label for="referring_dr" class="label-control">Referring Dr: </label>
                        <input type="text" class="form-control" id="referring_dr" name="referring_dr">
                        <span class="form-error"></span>
                      </div>  
                      
                      <div class="form-group col-sm-4">
                        <label for="consultant" class="label-control">Consultant Name: </label>
                        <input type="text" class="form-control" id="consultant" name="consultant">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-4">
                        <label for="consultant_email" class="label-control">Consultant Email: </label>
                        <input type="email" class="form-control" id="consultant_email" name="consultant_email">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-4">
                        <label for="consultant_mobile" class="label-control">Consultant Mobile No: </label>
                        <input type="number" class="form-control" id="consultant_mobile" name="consultant_mobile">
                        <span class="form-error"></span>
                      </div>
                      
                       <div class="form-group col-sm-4">
                        <label for="pathologist" class="label-control">Pathologist Name: </label>
                        <input type="text" class="form-control" id="pathologist" name="pathologist">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-4">
                        <label for="pathologist_email" class="label-control">Pathologist Email: </label>
                        <input type="email" class="form-control" id="pathologist_email" name="pathologist_email">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-4">
                        <label for="pathologist_mobile" class="label-control">Pathologist Mobile No: </label>
                        <input type="number" class="form-control" id="pathologist_mobile" name="pathologist_mobile">
                        <span class="form-error"></span>
                      </div>
                      
                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"><span class="form-error1">*</span>  Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="list-of-tests">
                    <h3 class="text-center">Required Tests</h3>
                  </div>

                  <input type="submit" class="btn btn-primary" name="submit">  
                  </form>
                </div>
              </div>
             

              <div class="card col-sm-10" id="edit-bio-data-card" style="display: none;">
                  <div class="card-header">
                    <h3 class="card-title"></h3>
                     <button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  </div>
                  <div class="card-body">
                    <?php $attr = array('id' => 'edit-bio-data-form') ?>
                    <?php echo form_open('',$attr); ?>
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">                 
                        <div class="form-group col-sm-6">
                          <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="edit_first_name" name="first_name">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="last_name" class="label-control"><span class="form-error1">*</span>  LastName: </label>
                          <input type="text" class="form-control" id="edit_last_name" name="last_name">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span>  Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="edit_dob" required>
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-2">
                          <label for="age" class="label-control"><span class="form-error1">*</span>  Age: </label>
                          <input type="number" class="form-control" name="age" id="edit_age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-2">
                          <div id="age_unit">
                            
                            <select name="age_unit" id="edit_age_unit" class="form-control">
                              <option value="minutes">Minutes</option>
                              <option value="hours">Hours</option>
                              <option value="days">Days</option>
                              <option value="weeks">Weeks</option>
                              <option value="months">Months</option>
                              <option value="years">Years</option>

                            </select>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-5">
                          <p class="label"><span class="form-error1">*</span>  Gender: </p>
                          <div id="sex">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="female" id="edit_female"> Female
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="male" id="edit_male"> Male
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
                          <label for="race" class="label-control"><span class="form-error1">*</span>  Race/Tribe: </label>
                          <input type="text" class="form-control" id="edit_race" name="race">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-5">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span>  Mobile No: </label>
                          <input type="number" class="form-control" id="edit_mobile" name="mobile">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-5">
                          <label for="email" class="label-control"><span class="form-error1">*</span>  Email: </label>
                          <input type="email" class="form-control" id="edit_email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Medical Information</h4>
                      <div class="form-row">
                       
                        <div class="form-group col-sm-4">
                          <label for="height" class="label-control"><span class="form-error1">*</span>  Height(metre): </label>
                          <input type="number" step="any" max="2" class="form-control" id="edit_height" name="height" >
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="weight" class="label-control"><span class="form-error1">*</span>  Weight(kg): </label>
                          <input type="number" step="any" class="form-control" id="edit_weight" name="weight">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <p class="label"><span class="form-error1">*</span>  Fasting?</p>
                          <div id="edit_fasting">
                            <div class="form-check form-check-radio form-check-inline" id="fasting">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" id="edit_fasting_yes" name="fasting" value="1"> Yes
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline disabled">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" id="edit_fasting_no" name="fasting" value="0"> No
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
                          <input type="text" class="form-control" id="edit_present_medications" name="present_medications">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="lmp" class="label-control">LMP: </label>
                          <input type="date" class="form-control" name="lmp" id="edit_lmp">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="clinical_summary" class="label-control"><span class="form-error1">*</span>  Clinical Summary/Diagnosis: </label>
                          <textarea name="clinical_summary" id="edit_clinical_summary" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <p class="label"><span class="form-error1">*</span>  Sample: </p>
                          <div id="edit_sample">
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="venous blood" id="edit_venous_blood"> Venous Blood
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="arterial blood" id="edit_arterial_blood"> Arterial Blood
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="capillary blood" id="edit_capillary_blood"> Capillary Blood
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>

                             <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="urine" id="edit_urine"> Urine
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="csf" id="edit_csf"> CSF
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="vitreous" id="edit_vitreous"> Vitreous
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>

                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="vitreous fluid" id="edit_vitreous_fluid"> Vitreous Fluid
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" class="sample" name="sample[]" value="serum" id="edit_serum"> Serum /Plasma
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>

                            <input type="text" class="form-control" placeholder="Other..." class="sample" id="edit_sample_other" name="sample_other"> 
                          </div>
                          <span class="form-error"></span>       
                        </div>
                        
                        <div class="form-group col-sm-9">
                          <label for="referring_dr" class="label-control">Referring Dr: </label>
                          <input type="text" class="form-control" id="edit_referring_dr" name="referring_dr">
                          <span class="form-error"></span>
                        </div>  
                        
                        <div class="form-group col-sm-4">
                          <label for="consultant" class="label-control">Consultant Name: </label>
                          <input type="text" class="form-control" id="edit_consultant" name="consultant">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="consultant_email" class="label-control">Consultant Email: </label>
                          <input type="email" class="form-control" id="edit_consultant_email" name="consultant_email">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="consultant_mobile" class="label-control">Consultant Mobile No: </label>
                          <input type="number" class="form-control" id="edit_consultant_mobile" name="consultant_mobile">
                          <span class="form-error"></span>
                        </div>
                        
                         <div class="form-group col-sm-4">
                          <label for="pathologist" class="label-control">Pathologist Name: </label>
                          <input type="text" class="form-control" id="edit_pathologist" name="pathologist">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="pathologist_email" class="label-control">Pathologist Email: </label>
                          <input type="email" class="form-control" id="edit_pathologist_email" name="pathologist_email">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="pathologist_mobile" class="label-control">Pathologist Mobile No: </label>
                          <input type="number" class="form-control" id="edit_pathologist_mobile" name="pathologist_mobile">
                          <span class="form-error"></span>
                        </div>
                       
                        <div class="form-group col-sm-6">
                          <label for="address" class="label-control"><span class="form-error1">*</span>  Address: </label>
                          <textarea name="address" id="edit_address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="list-of-tests">
                      <h3 class="text-center">Required Tests</h3>
                    </div>

                    <input type="submit" class="btn btn-primary" name="submit">  
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
  </div>
  
  
</body>
<script>
    $(document).ready(function() {     

      $("#register-patient").click(function (evt) {
        $("#main-card").hide();
        $("#paid-patients-list-card").show();
        $(".spinner-overlay").show();
        var get_patients_url = "<?php echo 
        site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_paid_patients') ?>";
        $.ajax({
          url : get_patients_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_patients=true",
          success : function (response) {

            if(response !== 0){
              $(".spinner-overlay").hide();
              $("#paid-patients-list-card .card-body").append("<h5 class='text-secondary'>Click User To Input Bio Data</h5>");
              $("#paid-patients-list-card .card-body").append(response);
              var table = $('#example').DataTable();

              $('#example tbody').on('click', 'tr', function () {
                  if ( $(this).hasClass('selected') ) {
                      $(this).removeClass('selected');
                  }
                  else {
                      table.$('tr.selected').removeClass('selected');
                      $(this).addClass('selected');
                  }
              } ); 
            }else{
              $(".spinner-overlay").hide();
              $("#paid-patients-list-card .card-body").append("<h5 class='text-danger' style='font-style: italic;'>No Patient Is Available Yet</h5>");
            }
          },
          error : function () {
            $(".spinner-overlay").hide();
             $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });

          }
        })
      })

      $("#edit-registered-patients").click(function (evt) {
        $("#main-card").hide();
        $("#created-paid-patients-list-card").show();
        $(".spinner-overlay").show();
        var get_patients_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_created_paid_patients') ?>";
        $.ajax({
          url : get_patients_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_patients=true",
          success : function (response) {
          if(response !== 0){  
            $(".spinner-overlay").hide();
            $("#created-paid-patients-list-card .card-body").append("<h5 class='text-secondary'>Click User To Edit Bio Data</h5>");
            $("#created-paid-patients-list-card .card-body").append(response);
            var table = $('#example').DataTable();

            $('#example tbody').on('click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } ); 
          }else{
             $(".spinner-overlay").hide();
            $("#created-paid-patients-list-card .card-body").append("<h5 class='text-danger' style='font-style: italic;'>No Patient Is Initiated Yet.</h5>");
          }
          },
          error : function () {
            $(".spinner-overlay").hide();
             $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });

          }
        })
      })

      
      <?php if($this->session->add_bio_successful){ ?>
         $.notify({
          message:"Bio Data Updated Successfully"
          },{
            type : "success"  
          });
      <?php } ?>  

      <?php
        if($no_admin == true && $this->onehealth_model->checkIfUserIsAdminOrSubAdmin($health_facility_table_name,$user_name)){
      ?>
        swal({
          title: 'Warning?',
          text: "You do not currently have any personnel in this section. Do Want To Add One?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, add!'
        }).then((result) => {
          // if (result.value) {
            window.location.assign("<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>")
            
          // }
        })
      <?php
        }
      ?>

    $("#edit-bio-data-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        $(".spinner-overlay").show();
        var lab_id = me.attr("data-lab-id");
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_bio_data_edit/') ?>"+lab_id;
        var values = me.serializeArray();
        values = values.concat({"lab_id":lab_id});
        console.log(values)
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
               $.notify({
              message:"Bio Data Edit Successful"
              },{
                type : "success"  
              });
               $(".form-error").html("");
            }else if(response.success == true && response.sample_unset == true){
              $.notify({
              message:"One Sample Has To Be Checked Or Entered In The Sample Other Field To Proceed"
              },{
                type : "danger"  
              });
            }else if(response.success == true && response.successful == false){
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "warning"  
              });
            }
            else{
             $.each(response.messages, function (key,value) {

              var element = $('#edit_'+key);
              
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
              message:"Sorry Something Went Wrong: 'Please Make Sure A Sample Is Selected Or Check Your Internet Connection'"
              },{
                type : "danger"  
              });
          }
        });  
     })      

     $("#enter-bio-data-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        var lab_id = me.attr("data-lab-id");
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_bio_data/') ?>"+lab_id;
        var values = me.serializeArray();
        values = values.concat({"lab_id":lab_id});
        $(".spinner-overlay").show();
        console.log(values)
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
            }else if(response.success == true && response.sample_unset == true){
              $.notify({
              message:"One Sample Has To Be Checked Or Entered In The Sample Other Field To Proceed"
              },{
                type : "danger"  
              });
            }else if(response.success == true && response.successful == false){
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "warning"  
              });
            }
            else{
             $.each(response.messages, function (key,value) {

              var element = $('#enter-bio-data-form #'+key);
              
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
              message:"Sorry Something Went Wrong: 'Please Make Sure A Sample Is Selected Or Check Your Internet Connection'"
              },{
                type : "danger"  
              });
          }
        });  
     })

      
      <?php
        if($this->session->submit_successful && $this->session->submit_successful == true){ 
          
          ?>
          $.notify({
          message:"Patient Bio Data Entered Successfully"
          },{
            type : "success"  
          });
        <?php } ?>
    });

  </script>
