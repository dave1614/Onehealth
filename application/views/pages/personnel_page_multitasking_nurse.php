<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>
      <!-- End Navbar -->
      <?php
      if(is_array($curr_health_facility_arr)){
        foreach($curr_health_facility_arr as $row){
          $health_facility_id = $row->id;
          $health_facility_name = $row->name;
          $health_facility_logo = $row->logo;
          $health_facility_structure = $row->facility_structure;
          $health_facility_email = $row->email;
          $health_facility_phone = $row->phone;
          $health_facility_country = $this->onehealth_model->getCountryById($row->country);
          $health_facility_state = $this->onehealth_model->getStateById($row->state);
          $health_facility_address = $row->address;
          $health_facility_table_name = $row->table_name;
          $health_facility_date = $row->date;
          $health_facility_time = $row->time;
          $health_facility_slug = $row->slug;
          $color = $row->color;
        }
        $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        $data_url_img = "<img style='display:none;' id='facility_img' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$health_facility_name."' col='".$color."'>";
      }
    ?>
<script> 
  var clinic_base_url = "";
  var selected_clinic_name = "";

  function goBackOffAppointmentPatientCard (elem) {
    $("#off-appointment-patient-card").hide();
    $("#off-appointment-patients-card").show();
  }

  function goBackOnAppointmentPatientCard (elem) {
    $("#on-appointment-patient-card").hide();
    $("#on-appointment-card").show(); 
  }

  function loadPatientBioOnApp (bio_id,record_id) {
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view-registered-patients-records-edit";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+bio_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          var id = messages.id;
          for (const [key, value] of Object.entries(messages)) {
            $("#on-appointment-patient-card #edit-bio-data-form-on-appointment #" +key).val(value);
            if(key == "sex"){
              $("#on-appointment-patient-card #edit-bio-data-form-on-appointment #" +value).prop("checked",true);
            }
          }
          var hospital_number = messages.hospital_number;

          $("#on-appointment-patient-card .vital-signs-form input").val("");
          $("#edit-bio-data-form-on-appointment").attr("data-id",id);
          $("#on-appointment-card").hide();
          $("#on-appointment-patient-card").show();
          $("#on-appointment-patient-card .hospital_number").val(hospital_number);
          $("#on-appointment-patient-card .record_id").val(record_id);

        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function loadPatientBioOffApp (bio_id,record_id) {
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view-registered-patients-records-edit";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+bio_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          var id = messages.id;
          for (const [key, value] of Object.entries(messages)) {
            $("#off-appointment-patient-card #edit-bio-data-form-off-appointment #" +key).val(value);
            if(key == "sex"){
              $("#off-appointment-patient-card #edit-bio-data-form-off-appointment #" +value).prop("checked",true);
            }
          }
          var hospital_number = messages.hospital_number;

          $("#off-appointment-patient-card .vital-signs-form input").val("");
          $("#edit-bio-data-form-off-appointment").attr("data-id",id);
          $("#off-appointment-patients-card").hide();
          $("#off-appointment-patient-card").show();
          $("#off-appointment-patient-card .hospital_number").val(hospital_number);
          $("#off-appointment-patient-card .record_id").val(record_id);

        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackPreviouslyRegistered(elem) {
    $("#off-appointment-patients-card").hide();
    $("#previously-registered-patients-card").hide();
    $("#choose-action-card").show();
  }

  function goDefault() {
    
    document.location.reload();
  }

  function goBackEditBio() {
    $("#edit-patient-bio-data-card").hide();
    $("#previously-registered-patients-card").show();
  }

  function loadPatientBioDataEdit(id) {
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view-registered-patients-records-edit";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          var id = messages.id;
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-patient-bio-data-card #edit-bio-data-form #" +key).val(value);
            if(key == "sex"){
              $("#edit-patient-bio-data-card #edit-bio-data-form #" +value).prop("checked",true);
            }
          }
          var hospital_number = messages.hospital_number;
          $("#edit-patient-bio-data-card #edit-bio-data-form").attr("data-id",id);
          $("#edit-patient-bio-data-card #new-patient-vital-signs-form").attr("data-id",id);
          
          $("#new-patients-card").hide();
          $("#edit-patient-bio-data-card").show();
          $("#edit-bio-data-form #hospital_number").val(hospital_number);
        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function newPatients (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view-registered-patients-records-paid";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#new-patients-card .card-title").html("New Patients In " +selected_clinic_name);
          $("#new-patients-card .card-body").html(response.messages);
          $("#choose-action-card").hide();
          $("#new-patients-card #registered-patients-table").DataTable();
          $("#new-patients-card").show();
          
        }
        else{
          $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 

  }

  function goBackNewPatientsCard (elem,evt) {
    goDefault();
  }

  function offAppointment (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view_off_appointments_clinic_nurse";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#off-appointment-patients-card .card-body").html(response.messages);
          $("#choose-action-card").hide();
          $("#off-appointment-patients-card").show();
          $("#off-appointment-patients-card table").DataTable();
        }
        else{
          $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 

  }

  function onAppointment (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view_on_appointments_clinic_nurse";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#on-appointment-card .card-body").html(response.messages);
          $("#choose-action-card").hide();
          $("#on-appointment-card table").DataTable();
          $("#on-appointment-card").show();
          
        }
        else{
          $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 

  }

  
  function openPatientBioDataForm(elem,evt) {
    evt.preventDefault();
    $("#main-card").hide();
    $("#patient-bio-data").show();
  }

  function inputVitalSigns(elem,evt) {
    $("#main-card").hide();
    $("#choose-clinic-to-perform-functions-card").show();
  }

  function goBackFromChooseClinicToPerformFunctionsCard(elem,evt) {
    $("#main-card").show();
    $("#choose-clinic-to-perform-functions-card").hide();
  }

  function goBackReferralsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#referrals-card").hide();
  }

  function chooseThisClinicToPerformFunction(elem,evt,url,clinic_name){
    evt.preventDefault();
    clinic_base_url = url;
    selected_clinic_name = clinic_name;
    $("#choose-action-card .card-title").html("Perform Action In " + clinic_name);
    $("#choose-action-card").show();
    $("#choose-clinic-to-perform-functions-card").hide();
  }

  function goBackFromChooseClinicToPerformFunctionsCard (elem,evt) {
    
    $("#choose-action-card").hide();
    $("#choose-clinic-to-perform-functions-card").show();
  }

  function goBackFromChooseActionCard (elem,evt) {
    $("#choose-action-card").show();
    $("#choose-clinic-to-perform-functions-card").hide();
  }

  function viewReferralsOrConsults(elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Referrals',
      cancelButtonText: 'View Consults'
    }).then(function(){
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/view_referrals_to_your_clinic_nurse";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            
            $("#referrals-card .card-title").html("All Referrals In " +selected_clinic_name);
            $("#referrals-card .card-body").html(response.messages);
            $("#choose-action-card").hide();
            $("#referrals-card").show();
            $("#referrals-card #referrals-table").DataTable();
          }
          else{
           $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
            
        var url = clinic_base_url + "/view_consult_to_your_clinic_nurse";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true",
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true){
              $("#referrals-card .card-title").html("All Consults In " +selected_clinic_name);
              $("#referrals-card .card-body").html(response.messages);
              $("#choose-action-card").hide();
              $("#referrals-card").show();
              $("#referrals-card #referrals-table").DataTable();
            }
            else{
             $.notify({
              message:"No Record To Display"
              },{
                type : "warning"  
              });
            }
          },
          error: function (jqXHR,textStatus,errorThrown) {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
            },{
              type : "danger"  
            });
          }
        });
      }
    });
  }

  function viewReferralInfo (elem,evt,id) {
    if(id != ""){
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/view_referral_info_nurse";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            $("#referral-card .card-body").html(response.messages);
            $("#referrals-card").hide();
            $("#referral-card").show();
            $("#proceed-referral-to-doctor").attr("data-id",id);
            $("#proceed-referral-to-doctor").show("fast");
          }
          else{
           $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function goBackReferralCard (elem,evt) {
    $("#referrals-card").show();
    $("#referral-card").hide();
    $("#proceed-referral-to-doctor").hide("fast");
  }

  function proceedReferralToDoctor (elem,evt) {

    var id = $(elem).attr("data-id");
    if(id != ""){
      $("#input-vital-signs-referral-modal").modal("show");
      $("#input-vital-signs-referral-form").attr("data-id",id);
    }
  }

</script>    
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
                $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_table_name,$second_addition,$third_addition,$personnel_slug);
                
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
           if(is_null($health_facility_logo)){
            echo $data_url_img; 
           }else{ 
            ?> 
          <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
          <?php } ?>
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

              <div class="card" id="main-card">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="inputVitalSigns(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="choose-clinic-to-perform-functions-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromChooseClinicToPerformFunctionsCard(this,event)">Go Back</button>
                  <h3 class="card-title">Choose Clinic To Perform Function</h3>
                </div>
                <div class="card-body">
                  <?php
                  $all_clinics = $this->onehealth_model->getSubDeptsByDeptId(3);
                  if(is_array($all_clinics)){
                  ?>
                  <table class="table">
                    <tbody>
                      <?php 
                      $i = 0;
                      foreach($all_clinics as $row){
                        $i++;
                        $sub_dept_id = $row->id;
                        $name = $row->name;
                        $dept_slug = $this->onehealth_model->getDeptParamById("slug",3);
                        $sub_dept_slug = $row->slug;
                        $personnel_slug = "multitasking-nurse";
                        
                        $url = site_url("onehealth/index/".$addition . "/".$dept_slug."/".$sub_dept_slug."/".$personnel_slug);
                      ?>
                      <tr class="pointer-cursor">
                        <td><?php echo $i; ?>.</td>
                        <td><a style="text-transform: capitalize;" href="#" onclick="chooseThisClinicToPerformFunction(this,event,'<?php echo $url; ?>','<?php echo $name; ?>')"><?php echo $name; ?></a></td>
                      </tr>

                      <?php } ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>

              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromChooseActionCard(this,event)">Go Back</button>
                  <h3 class="card-title"></h3>
                </div>
                <div class="card-body" style="margin-top: 50px;">
                  
                  <button onclick="newPatients(this,event)" class="btn btn-primary">New Patients</button>
                  <button onclick="onAppointment(this,event)" class="btn btn-info">Patients On Appointments</button>
                  <button onclick="offAppointment(this,event)" class="btn btn-success">Patients Off Appointments</button>
                  <button onclick="viewReferralsOrConsults(this,event)" class="btn btn-warning">View Referrals Or Consults</button>
                </div>
              </div>



              <div class="card" id="referrals-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Referrals /  Consults</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="on-appointment-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  <h4 class="card-title">All Patients With Appointments Today</h4>
                  
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="referral-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Referral Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="on-appointments-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  
                  <h4 style="margin-bottom: 40px;" id="quest">Verify Patient Bio Data. Submit To Forward To The Next Officer </h4>
                  <?php $attr = array('id' => 'on-appointments-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">             
                      <input type="hidden" name="hospital_number" class="hospital_number" value="">
                      <input type="hidden" name="record_id" class="record_id" value="">
                      
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
                        <p class="label"><span class="form-error1">*</span> Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="yes" > Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting"  value="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="present_medications" class="label-control">Present Medications: </label>
                        <input type="text" class="form-control" id="present_medications" name="present_medications">
                        <span class="form-error"></span>
                      </div>                      
                                       
                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  <input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
                  <input type="submit" class="btn btn-primary">
                </form>


                </div>
              </div>

              <div class="card" id="off-appointment-patient-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOffAppointmentPatientCard(this)" class="btn btn-warning">Go Back</button>
                  <a href="#vital-signs-form" class="btn btn-info">Input Vital Signs</a>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Edit Patient Bio Data: </h4>
                  <?php $attr = array('id' => 'edit-bio-data-form-off-appointment') ?>
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                        <div class="form-group col-sm-6">
                          <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
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

                        <div class="form-group col-sm-6">
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

                        

                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>



                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div> 
                    <input type="submit" class="btn btn-primary">
                  </form>

                  <h3 class="text-center">Input Vital Signs</h3>
                  <?php 
                  $attr = array('id' => 'vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                  <div class="form-row">
                    <input type="hidden" name="record_id" class="record_id" value="">
                    <div class="form-group col-sm-3">
                        <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                        <input type="number" class="form-control" id="pr" name="pr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                        <input type="number" class="form-control" id="rr" name="rr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                        <input type="text" class="form-control" id="bp" name="bp" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                        <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                        <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-6">
                        <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                        <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                        <span class="form-error"></span>
                      </div>                      
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>


    
                </div>
              </div>



              <div class="card" id="on-appointment-patient-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAppointmentPatientCard(this)" class="btn btn-warning">Go Back</button>
                  <a href="#on-appointment-vital-signs-form" class="btn btn-info">Input Vital Signs</a>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Edit Patient Bio Data: </h4>
                  <?php $attr = array('id' => 'edit-bio-data-form-on-appointment') ?>
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                        <div class="form-group col-sm-6">
                          <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
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

                        <div class="form-group col-sm-6">
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

                        

                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>



                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div> 
                    <input type="submit" class="btn btn-primary">
                  </form>

                  <h3 class="text-center">Input Vital Signs</h3>
                  <?php 
                  $attr = array('id' => 'on-appointment-vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                  <div class="form-row">
                    <input type="hidden" name="record_id" class="record_id" value="">
                    <div class="form-group col-sm-3">
                        <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                        <input type="number" class="form-control" id="pr" name="pr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                        <input type="number" class="form-control" id="rr" name="rr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                        <input type="text" class="form-control" id="bp" name="bp" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                        <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                        <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-6">
                        <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                        <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                        <span class="form-error"></span>
                      </div>                      
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>


    
                </div>
              </div>


              <div class="card" id="previously-registered-patients-card" style="display: none;">
                <div class="card-header">
                  
                  <button onclick="goBackPreviouslyRegistered(this)" class="btn btn-warning">Go Back</button>
                  <h4 style="margin-bottom: 40px;" id="quest">All Registered Patients</h4>
                  <p>Click To Input Or Edit Values</p>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="new-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackNewPatientsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h4 id="card-title">New Patients</h4>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="off-appointment-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviouslyRegistered(this)" class="btn btn-warning">Go Back</button>
                  <h4 style="margin-bottom: 40px;" id="quest">All Patients Off Appointments</h4>
                  <p>Click To Input Values</p>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              
              <div class="card" id="edit-patient-bio-data-card" style="display: none;">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goBackEditBio()">Go Back</button>
                  <a href="#new-patient-vital-signs-form" class="btn btn-info">Input Vital Signs</a>
    
                  <h4 style="margin-bottom: 40px;" id="quest">Edit Patient Bio Data: </h4>
                  <?php $attr = array('id' => 'edit-bio-data-form') ?>
                  
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                        <div class="form-group col-sm-6">
                          <label for="firstname" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="lastname" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
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

                        <div class="form-group col-sm-6">
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

                        

                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                        
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>
                        
                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div> 
                    
                    
                    <input type="submit" class="btn btn-primary">
                  </form>

                  <h3 class="text-center">Input Vital Signs</h3>
                  <?php 
                  $attr = array('id' => 'new-patient-vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                  <div class="form-row">
                    
                    <div class="form-group col-sm-3">
                        <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                        <input type="number" class="form-control" id="pr" name="pr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                        <input type="number" class="form-control" id="rr" name="rr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                        <input type="text" class="form-control" id="bp" name="bp" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                        <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                        <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-6">
                        <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                        <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                        <span class="form-error"></span>
                      </div>                      
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>
              </div>


              <div class="modal fade" data-backdrop="static" id="input-vital-signs-referral-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Input Vital Signs For This Referral / Consult Patient</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>


                    <div class="modal-body" id="modal-body">
                      <?php
                        $attr = array('id' => 'input-vital-signs-referral-form');
                       echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_form_referral',$attr);
                      ?>
                        <div class="form-group">
                          <label for="referral_pr">Pulse Rate (b / min): </label>
                          <input type="number" name="referral_pr" id="referral_pr" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_rr">Respiratory Rate (c / min): </label>
                          <input type="number" name="referral_rr" id="referral_rr" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_bp">Blood Pressure (mmHg): </label>
                          <input type="number" name="referral_bp" id="referral_bp" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_temperature">Temperature (&deg; C): </label>
                          <input type="number" step="any" name="referral_temperature" id="referral_temperature" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_waist_circumference">Waist Circumference (cm): </label>
                          <input type="number" name="referral_waist_circumference" id="referral_waist_circumference" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_hip_circumference">Hip Circumference (cm): </label>
                          <input type="number" name="referral_hip_circumference" id="referral_hip_circumference" class="form-control">
                          <span class="form-error"></span>
                        </div>
                        <input type="submit" class="btn btn-success" value="PROCEED">
          
                      </form>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                    </div>
                  </div>
                </div>
              </div>  




              <div id="proceed-referral-to-doctor" onclick="proceedReferralToDoctor(this,event)" rel="tooltip" data-toggle="tooltip" title="Enter Vital Signs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
                <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                  <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
                </div>
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

      $("#on-appointment-vital-signs-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = clinic_base_url + "/submit-patient-bio-data-clinic-edit3";
          var values = me.serializeArray();
          
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
                message:"Patient Vital Signs Inputed Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault, 2000);
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
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
            },
            error: function (jqXHR,textStatus,errorThrown) {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              $(".form-error").html();
            }
          });  
      })

      $("#input-vital-signs-referral-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = clinic_base_url + "/submit_vital_signs_form_referral";
        var form_data = me.serializeArray();
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })

        $(".spinner-overlay").show();
          
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true){
              document.location.reload();
            }
            else{
             $.each(response.messages, function (key,value) {

              var element = $('#input-vital-signs-referral-form #'+key);
              
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
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
            },{
              type : "danger"  
            });
          }
        });
      })


      <?php if($this->session->added_successfully && $this->session->hospital_number){ ?>
        var hospital_number = '<?php echo $this->session->hospital_number; ?>';
        swal({
          title: 'Success',
          text: "<p>Patient Account Successfully Created.</p><p>Hospital Number: <span class='text-primary'>"+hospital_number+"</span></p>",
          type: 'success'
        }).then((result) => {
          document.location.reload();
        })
      <?php } ?>  

      <?php if($this->session->mark_successful){ ?>
        var hospital_number = '<?php echo $this->session->hospital_number; ?>';
        swal({
          title: 'Success',
          text: "<p>Patient Marked As Paid Successfully.</p><p>Hospital Number: <span class='text-primary'>"+hospital_number+"</span></p>",
          type: 'success'
        }).then((result) => {
          // document.location.reload();
        })
      <?php } ?>  

      $("#new-patient-vital-signs-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          var id = me.attr("data-id");
          $(".spinner-overlay").show();
          
          var url = clinic_base_url + "/submit_vital_signs_new_patient";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "id",
            "value" : id
          })
          
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
                message:"Patient Vital Signs Inputed Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault, 2000);
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
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
            },
            error: function (jqXHR,textStatus,errorThrown) {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              $(".form-error").html();
            }
          });  
      })


      $("#vital-signs-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit-patient-bio-data-clinic-edit3'); ?>";
          var values = me.serializeArray();
          
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
                message:"Patient Vital Signs Inputed Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault, 2000);
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
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
            },
            error: function (jqXHR,textStatus,errorThrown) {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              $(".form-error").html();
            }
          });  
      })

      $("#edit-bio-data-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          var id = me.attr("data-id");
          
          var url = clinic_base_url + "/submit-patient-bio-data-clinic-edit";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "id",
            "value" : id
          })
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
                message:"Patient Bio Data Edited Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-bio-data-form #'+key);
                
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
              $(".form-error").html();
            }
         });   
      })

      $("#edit-bio-data-form-off-appointment").submit(function (evt) {
          
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          var id = me.attr("data-id");
          
          var url = clinic_base_url + "/submit-patient-bio-data-clinic-edit";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "id",
            "value" : id
          })
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
                message:"Patient Bio Data Edited Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-bio-data-form-off-appointment #'+key);
                
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
              $(".form-error").html();
            }
         });  
      })

      $("#edit-bio-data-form-on-appointment").submit(function (evt) {
          
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          var id = me.attr("data-id");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit-patient-bio-data-clinic-edit'); ?>";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "id",
            "value" : id
          })
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
                message:"Patient Bio Data Edited Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-bio-data-form-on-appointment #'+key);
                
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
              $(".form-error").html();
            }
         });  
      })

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
            window.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_admin') ?>")
            
          // }
        })
      <?php
        }
      ?>


    });



</script>
