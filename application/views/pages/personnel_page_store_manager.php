<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>

<script>
  

  function goBackPerformFunctions (elem,evt) {
    $("#main-card").show();
    $("#perform-functions-card").hide(); 
  }

  function goBackMainStoreCard () {
    $("#perform-functions-card").show("slow");
    $("#main-store-card").hide("slow");
    $("#add-drugs-main-store-btn").hide("fast");
  }

  function goBackAddDrugsMainStoreCard () {
    $("#main-store-card").show("slow");
    $("#add-drugs-main-store-btn").show("fast");
    $("#add-drugs-main-store-card").hide("slow");
  }

  function goBackViewDrugMainStoreCard () {
    $("#main-store-card").show("slow");
    $("#add-drugs-main-store-btn").show("fast");
    $("#view-drug-main-store-card").hide("slow");
    $("#edit-drug-main-store-btn").hide("fast");
    $("#move-drug-to-dispenary-btn").hide("fast");
  }

  function goBackEditDrugCard () {
    $("#view-drug-main-store-card").show("slow");
    $("#edit-drug-main-store-btn").show("fast");
    $("#move-drug-to-dispenary-btn").show("fast");
    $("#edit-drug-form").attr("data-id","");

    $("#edit-drug-card").hide();
  }

  function goBackPoisonRegisterCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#poison-register-card").hide();
  }

  function goBackErrorRegister (elem,evt) {
    $("#perform-functions-card").show();
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
  }

  function goBackAddNewErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-card").hide();
    $("#add-new-error-btn").show("fast");

  }

  function goBackEditErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-btn").show("fast");
    
    $("#edit-new-error-card").hide();
  }

  function loadErrorRegisterInfo(elem,evt,id){
    if(id !== ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register_info_for_edit'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "get_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#error-register-card").hide();
            $("#add-new-error-btn").hide("fast");
            $("#edit-new-error-form").attr("data-id",id);
            var event = response.messages.event;
            var action = response.messages.action;
            var remedied = response.messages.remedied;

            $("#edit-new-error-card #event").val(event);
            $("#edit-new-error-card #action").val(action);
            if(remedied == 1){
              $("#edit-new-error-card #yes").prop("checked",true);
            }else{
              $("#edit-new-error-card #yes").prop("checked",false);
            }
            
            $("#edit-new-error-card").show();
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        }
      });   
    }
  }

  function addNewError (elem,evt) {
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
    $("#add-new-error-card").show();
  }

  function errorRegister (elem,evt) {
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "get_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#error-register-card .card-body").html(messages)
          $("#error-register-card").show();
          $("#add-new-error-btn").show("fast");
          $("#error-register-table").DataTable();
        }
      },error : function () {
        $(".spinner-overlay").hide();
        swal({
         title: 'Ooops',
          text: "Something Went Wrong",
          type: 'error'
        })
      }
    });    
  }

  function poisonRegister(elem,evt){
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_pharmacy_poison_register'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#poison-register-card .card-body").html(messages);
          $("#poison-register-card #poison-register-table").DataTable();
          $("#poison-register-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
  }

  function deleteDrug (elem,e,id) {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    if(id != ""){
      swal({
        title: 'Warning?',
        text: "Are You Sure You Want To Delete This Drug?",
        html : "<h4 class='text-secondary'>Note: This Is Irreversible</h4>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete!'
      }).then((result) => {
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/delete_drug'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "id="+id,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true){
              document.location.reload();
            }else{
              swal({
                title: 'Ooops!',
                text: "Sorry Something Went Wrong. Please Try Again",
                type: 'warning'
                
              })
            }
          },error :function () {
            $(".spinner-overlay").hide();
             swal({
              title: 'Error!',
              text: "Sorry Something Went Wrong",
              type: 'error'
              
            })
          }
        });
      });
    }
    
  }

  function moveDrugToDispensary (elem,e) {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    var id = $(elem).attr("data-id");
    var quantity = $(elem).attr("data-quantity");
    if(id != "" && quantity != ""){
      // $("#move-drug-to-dispensary-form #quantity").attr("max",quantity);
      $("#move-drug-to-dispensary-form").attr("data-id",id);
      $("#add-drug-to-dispensary-modal").modal("show");
    }
  }


  function editDrugMainStore(elem,evt){
    var id = $(elem).attr("data-id");
    if(id != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drug_info'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages.length != 0){
            var messages = response.messages;
            $("#view-drug-main-store-card").hide("slow");
            $("#edit-drug-main-store-btn").hide("fast");
            $("#move-drug-to-dispenary-btn").hide("fast");
            $("#edit-drug-form").attr("data-id",id);
            $.each(messages, function(index, val) {
              $("#edit-drug-form").find("#"+index).val(val);
              $('#edit-drug-form select#'+index).val(val);
              $('#edit-drug-form .selectpicker').selectpicker('refresh');
              if(index == "yes"){
                console.log('yes')
                $('#edit-drug-form #yes').prop('checked',true);
                $('#edit-drug-form #no').prop('checked',false);
              }
              
            });
            $("#edit-drug-card").show();


          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function viewDrugMainStore (elem,event,id) {
    var quantity = $(elem).attr("data-quantity");
    if(id != "" && quantity != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_drug_main_store'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            
            $("#main-store-card").hide("slow");
            $("#add-drugs-main-store-btn").hide("fast");
            $("#view-drug-main-store-card .card-body").html(messages);
            
            $("#view-drug-main-store-card").show("slow");
            $("#edit-drug-main-store-btn").attr("data-id",id);
            $("#edit-drug-main-store-btn").show("fast");
            $("#move-drug-to-dispenary-btn").attr("data-id",id);
            $("#move-drug-to-dispenary-btn").attr("data-quantity",quantity);
            $("#move-drug-to-dispenary-btn").show("fast");

          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function addNewDrugMainStore(elem,event){
    $("#main-store-card").hide("slow");
    $("#add-drugs-main-store-btn").hide("fast");
    $("#add-drugs-main-store-card").show("slow");
  }

  function viewStoreRecords (elem,evt) {
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_main_store'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide("slow");
          
          $("#main-store-card .card-body").html(messages);
          $("#main-store-card #main-store-table").DataTable();
          $("#main-store-card").show("slow");
          $("#add-drugs-main-store-btn").show("fast");
          // $("#add-drugs-main-store-btn").show("fast");
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
  }

  function performFunctions (elem,evt) {
    $("#main-card").hide();
    $("#perform-functions-card").show();
  }
</script>
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
            $health_facility_country = $row->country;
            $health_facility_state = $row->state;
            $health_facility_address = $row->address;
            $health_facility_table_name = $row->table_name;
            $health_facility_date = $row->date;
            $health_facility_time = $row->time;
            $health_facility_slug = $row->slug;
            $color = $row->color;
          }
          if(is_null($health_facility_logo)){
            $no_logo = true;
            
            $data_url_img = "<img style='display:none;' id='facility_img' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$health_facility_name."' col='".$color."'>";
            
          }else{
            $data_url_img = '<img src="'.base_url('assets/images/'.$health_facility_logo).'" style="display: none;" alt="" id="facility_img">';
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
        echo $data_url_img;
      ?>
<script> 
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
           
          ?>
          <?php
           if($user_position == "admin"){ ?>
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
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="performFunctions(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="perform-functions-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackPerformFunctions(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Your Functions</h3>
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-2" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="viewStoreRecords(this,event)">View Store Records</td>
                        </tr>
                        
                        <tr>
                          <td>2</td>
                          <td onclick="poisonRegister(this,event)" style="text-transform: capitalize;">View Poison Register</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="errorRegister(this,event)" style="text-transform: capitalize;">View And Update Error And Occurence Register</td>
                        </tr>

                        <!-- <tr>
                          <td>4</td>
                          <td onclick="drugReactionsRegister(this,event)" style="text-transform: capitalize;">View Drug Reactions Register</td>
                        </tr> -->

                        
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>5</td>
                          <td onclick="viewClinicPatientsRecords(this,event)" style="text-transform: capitalize;">View Clinic Patients Records</td>
                        </tr> -->
                        <?php } ?>
                        
                      </tbody>
                    </table>
                  </div> 
                </div>
              </div>

              <div class="card" id="main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">All Drugs In Store</h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="add-drugs-main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddDrugsMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Add New Drug To Main Store</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'add-drugs-main-store-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_drug_to_store',$attr);
                  ?> 
                    <span class="form-error1">*</span>: Required
                    <h3 class="form-sub-heading text-center">Drug Info</h4>
                    <div class="wrap">
                      <div class="form-row">

                        <div class="form-group col-sm-6">
                          <label for="class_name" class="label-control"><span class="form-error1">* </span> Class Name: </label>
                          <input name="class_name" id="class_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="generic_name" class="label-control"><span class="form-error1">* </span> Generic Name: </label>
                          <input name="generic_name" id="generic_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="formulation" class="label-control"><span class="form-error1">* </span> Formulation: </label>
                          <input name="formulation" id="formulation" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="brand_name" class="label-control"><span class="form-error1">* </span> Brand Name: </label>
                          <input name="brand_name" id="brand_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3">
                          <label for="strength" class="label-control"><span class="form-error1">* </span> Strength: </label>
                          <input name="strength" id="strength" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="strength_unit" class="label-control"><span class="form-error1">* </span> Unit Of Strength: </label>
                          <input name="strength_unit" id="strength_unit" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          
                          <label for="unit" class="label-control"><span class="form-error1">* </span> Unit: </label>
                          <input name="unit" id="unit" class="form-control" data-html="true" data-toggle="tooltip" data-placement="top" title="This Is The Unit In Which The Physician Must Prescribe e.g (tablets,mg,ml e.t.c)">
                          <span class="form-error"></span>
                        </div> 

                        

                        <div class="form-group col-sm-4">
                          
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" data-html="true" data-toggle="tooltip" title="This Is The Total Units Of This Drug In Your Facility e.g(Total Tablets,Total ml's, Total mg's e.t.c). Total Must Be In The Units Already Inputed. This Is Also The Unit Upon Which This Agent Will Be Dispensed.">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <p class="label"><span class="form-error1">*</span>  Poison? </p>
                          <div id="poison">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="1" id="yes"> Yes
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="0" id="no" checked> No
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          
                          <label for="expiry_date" class="label-control"><span class="form-error1">* </span> Expiry Date: </label>
                          <input name="expiry_date" id="expiry_date" class="form-control" type="date" >
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>


                    <h3 class="form-sub-heading text-center">Prescription Info</h4>
                    <div class="wrap" style="padding-bottom: 40px;">
                      <h4 style="font-size: 21px;" class="text-center">Common Adult Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_adult_dosage" class="label-control">Dosage: </label>
                          <input name="common_adult_dosage" type="number" step="any" id="common_adult_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_frequency_num" id="common_adult_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_frequency_time" id="common_adult_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="nocte">Nocte</option>
                            <option value="stat">Stat</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_duration_num" id="common_adult_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_duration_time" id="common_adult_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                          </select>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px; ]padding-bottom: 40px;" class="text-center">Common Pediatric Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_pediatric_dosage" class="label-control">Dosage: </label>
                          <input name="common_pediatric_dosage" type="number" step="any" id="common_pediatric_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_pediatric_dose_frequency_num" id="common_pediatric_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_frequency_time" id="common_pediatric_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="nocte">Nocte</option>
                            <option value="stat">Stat</option>
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any"  name="common_pediatric_dose_duration_num" id="common_pediatric_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_duration_time" id="common_pediatric_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                      </div>

                    </div>  

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px;" class="text-center">Pricing Info</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="price" class="label-control">Enter Price Per Unit: </label>
                          <input name="price" step="any" id="price" class="form-control">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>      
                    <input type="submit" class="btn btn-success">
                  </form>
                </div> 
              </div>

              <div class="card" id="view-drug-main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackViewDrugMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="edit-drug-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditDrugCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Edit This Drug</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'edit-drug-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/edit_drug',$attr);
                  ?>
                   <span class="form-error1">*</span>: Required
                    <h3 class="form-sub-heading text-center">Drug Info</h4>
                    <div class="wrap">
                      <div class="form-row">

                        <div class="form-group col-sm-6">
                          <label for="class_name" class="label-control"><span class="form-error1">* </span> Class Name: </label>
                          <input name="class_name" id="class_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="generic_name" class="label-control"><span class="form-error1">* </span> Generic Name: </label>
                          <input name="generic_name" id="generic_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="formulation" class="label-control"><span class="form-error1">* </span> Formulation: </label>
                          <input name="formulation" id="formulation" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="brand_name" class="label-control"><span class="form-error1">* </span> Brand Name: </label>
                          <input name="brand_name" id="brand_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3">
                          <label for="strength" class="label-control"><span class="form-error1">* </span> Strength: </label>
                          <input name="strength" id="strength" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="strength_unit" class="label-control"><span class="form-error1">* </span> Unit Of Strength: </label>
                          <input name="strength_unit" id="strength_unit" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          
                          <label for="unit" class="label-control"><span class="form-error1">* </span> Unit: </label>
                          <input name="unit" id="unit" class="form-control" data-html="true" data-toggle="tooltip" data-placement="top" title="This Is The Unit In Which The Physician Must Prescribe e.g (tablets,mg,ml e.t.c)">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" data-html="true" data-toggle="tooltip" title="This Is The Total Units Of This Drug In Your Facility e.g(Total Tablets,Total ml's, Total mg's e.t.c). Total Must Be In The Units Already Inputed. This Is Also The Unit Upon Which This Agent Will Be Dispensed.">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <p class="label"><span class="form-error1">*</span>  Poison? </p>
                          <div id="poison">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="1" id="yes"> Yes
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="0" id="no" checked> No
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          
                          <label for="expiry_date" class="label-control"><span class="form-error1">* </span> Expiry Date: </label>
                          <input name="expiry_date" id="expiry_date" class="form-control" type="date" >
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>


                    <h3 class="form-sub-heading text-center">Prescription Info</h4>
                    <div class="wrap" style="padding-bottom: 40px;">
                      <h4 style="font-size: 21px;" class="text-center">Common Adult Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_adult_dosage" class="label-control">Dosage: </label>
                          <input name="common_adult_dosage" type="number" step="any" id="common_adult_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_frequency_num" id="common_adult_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_frequency_time" id="common_adult_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_duration_num" id="common_adult_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_duration_time" id="common_adult_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                          </select>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px; ]padding-bottom: 40px;" class="text-center">Common Pediatric Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_pediatric_dosage" class="label-control">Dosage: </label>
                          <input name="common_pediatric_dosage" type="number" step="any" id="common_pediatric_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_pediatric_dose_frequency_num" id="common_pediatric_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_frequency_time" id="common_pediatric_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any"  name="common_pediatric_dose_duration_num" id="common_pediatric_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_duration_time" id="common_pediatric_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                      </div>

                    </div>  

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px;" class="text-center">Pricing Info</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="price" class="label-control">Enter Price Per Unit: </label>
                          <input name="price" step="any" id="price" class="form-control">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>      
                    <input type="submit" class="btn btn-success">
                  </form>
                </div> 
              </div>

              <div class="card" id="poison-register-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPoisonRegisterCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Poison Register</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="error-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Error Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="add-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Data To Error / Occurrence Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="edit-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit Data</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'edit-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
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

      <div class="modal fade" data-backdrop="static" id="add-drug-to-dispensary-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="text-transform: capitalize;"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <?php $attributes = array('class' => '','id' => 'move-drug-to-dispensary-form') ?>
              <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_add_to_dispensary_form',$attributes); ?>
              <div class="form-group">
                <label for="quantity">Enter Quantity: </label>
                <input type="number" name="quantity" id="quantity" class="form-control" max="" step="any" required>
                <span class="form-error"></span>
              </div>
              <input type="submit" class="btn btn-primary">
              <?php echo form_close(); ?>                               
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

        </div>
      </div>



      <div id="move-drug-to-dispenary-btn" onclick="moveDrugToDispensary(this,event)" rel="tooltip" data-toggle="tooltip" title="Move This Drug To Dispsenary" style="background: #e91e63; cursor: pointer; position: fixed; bottom: 60px; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;">folder</i>

        </div>
      </div>

      <div id="add-drugs-main-store-btn" onclick="addNewDrugMainStore(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Drug To Main Store" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

        </div>
      </div>

      <div id="edit-drug-main-store-btn" onclick="editDrugMainStore(this,event)" rel="tooltip" data-toggle="tooltip" title="Edit This Drug" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

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
    $("#edit-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      var id = me.attr("data-id");
      form_data = form_data.concat({
        "name" : "id",
        "value" : id
      })
      console.log(form_data);
      if(id !== ""){
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
            if(response.success){
              $.notify({
              message:"This Register Has Been Edited Successfully"
              },{
                type : "success"  
              });
            }else if(response.expired){
              $.notify({
              message:"Date Entered Must Be In The In The Future"
              },{
                type : "warning"  
              });
            }else{
              $.each(response.error_messages, function (key,value) {

              var element = $('#edit-new-error-form #'+key);
              
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
            swal({
             title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        }); 
      }
    })

    $("#add-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      console.log(form_data);
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
          if(response.success){
            document.location.reload()
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#add-new-error-form #'+key);
            
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
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    })

    $("#move-drug-to-dispensary-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var url = me.attr("action");
      var form_data = me.serializeArray();
      var quantity = me.find("#quantity").val();
      var id = me.attr("data-id");
      form_data = form_data.concat({
        "name" : "id",
        "value" : id
      });
      var max = me.find("#quantity").attr("max");

      console.log(form_data)
      if(!isNaN(quantity)  && quantity != "" ){
        quantity = parseFloat(quantity);
        // max = parseFloat(max);
        
        swal({
          title: 'Warning?',
          text: "You Are About To Move " + addCommas(quantity) +" Units Of This Drug To Dispensary. Are You Sure You Want To Proceed?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed!'
        }).then((result) => {
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
              if(response.success && response.main_store_quantity !== "" && response.main_store_quantity1 !== "" && response.dispensary_quantity !== ""){
                var main_store_quantity = response.main_store_quantity;
                var main_store_quantity1 = response.main_store_quantity1;
                var dispensary_quantity = response.dispensary_quantity;
                console.log(main_store_quantity)
                console.log(dispensary_quantity)
                $.notify({
                message:addCommas(quantity) +" Units Of This Drug Has Been Successfully Moved To Dispensary"
                },{
                  type : "success"  
                });
                $('#move-drug-to-dispensary-form .form-error').html("");
                $("#main-store-card tr[data-id='"+id+"'] .main-store-quantity").html(main_store_quantity);
                $("#main-store-card tr[data-id='"+id+"'] .dispensary-quantity").html(dispensary_quantity);
                $("#main-store-card tr[data-id='"+id+"']").attr("data-quantity",main_store_quantity1);
                $("#view-drug-main-store-card .main-store-quantity").html(main_store_quantity);
                $("#view-drug-main-store-card .dispensary-quantity").html(dispensary_quantity);
                // me.find("#quantity").attr("max",main_store_quantity1);
                $("#add-drug-to-dispensary-modal").modal("hide");
              }else if(response.too_big && response.main_store_quantity !== ""){
                swal({
                  title: 'Ooops',
                  text: "Quantity Entered Cannot Exceed Main Store Quantity Which Is " + response.main_store_quantity + " Units",
                  type: 'error'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#move-drug-to-dispensary-form #'+key);
                
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
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'error'
              })
            } 
          }); 
        })
       
      }else{
        swal({
          title: 'Ooops',
          text: "Something Went Wrong",
          type: 'error'
        })
      }
    })

    $('#edit-drug-form').submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      
      var form_data = me.serializeArray();
      var url = me.attr("action");
      var id = me.attr("data-id");
      form_data = form_data.concat({
        "name" : "id",
        "value" : id
      });
      console.log(form_data);
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
          if(response.success){
            $.notify({
            message:"Drug Edited Successfully"
            },{
              type : "success"  
            });
            $('#edit-drug-form .form-error').html("");
          }else if(response.expired){
            swal({
              title: 'Ooops',
              text: "Date Entered Must Be In The In The Future",
              type: 'error'
            })
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#edit-drug-form #'+key);
            
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
          swal({
            title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    })

    $('#add-drugs-main-store-form').submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      console.log($(this).serializeArray())
      $(".spinner-overlay").show();
      var form_data = $(this).serializeArray();
      var url = me.attr("action");
    
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : form_data,
        success : function (response) {
          console.log(response)
          console.log(JSON.stringify(response.form_data))
          $(".spinner-overlay").hide();
          if(response.success){
            document.location.reload();
          }else if(response.expired){
            swal({
              title: 'Ooops',
              text: "Date Entered Must Be In The In The Future",
              type: 'error'
            })
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#add-drugs-main-store-form #'+key);
            
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
          swal({
            title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    });

    $('#add-drugs-main-store-form #unit').tooltip({'trigger':'focus', 'title': 'Password tooltip'});
    $('#add-drugs-main-store-form #quantity').tooltip({'trigger':'focus', 'title': 'Password tooltip'});

    $( "#add-drugs-main-store-form #generic_name" ).autocomplete({
      source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=generic_name') ?>"
    });

    $( "#add-drugs-main-store-form #formulation" ).autocomplete({
      source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=formulation') ?>"
    });

    $( "#add-drugs-main-store-form #class_name" ).autocomplete({
      source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=class_name') ?>"
    });

    $( "#add-drugs-main-store-form #brand_name" ).autocomplete({
      source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=brand_name') ?>"
    });



    <?php if($this->session->drug_deleted){ ?>
     $.notify({
      message:"Drug Deleted Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->drug_added){ ?>
     $.notify({
      message:"Drug Added Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->error_register_data_entered){ ?>
     $.notify({
      message:"Data Has Been Added To The Error / Occurrence Register Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>
  });
</script>
