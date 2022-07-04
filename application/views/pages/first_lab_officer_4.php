<style>
  tr{
    cursor: pointer;
  }

  .spinner{
    position: absolute; 
    right: 25px; 
    top: 13px;
    width: 20px;
    height: 20px;
    display: none;
  }
</style>
<script>
  var tests_selected_obj = [];
  var patient_facility_id = "";
  var additional_patient_test_info = [];

 
  function copyText(text) {
    /* Get the text field */
    var elem = document.createElement("textarea");
    elem.value = text;
    document.body.append(elem);

    /* Select the text field */
    elem.select();
    /* Copy the text inside the text field */
    if(document.execCommand("copy")){
      $.notify({
      message:"Copied!"
      },{
        type : "success"  
      });
    }

    document.body.removeChild(elem);

    /* Alert the copied text */
  }
  
  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  
  function reloadPage() {
    document.location.reload();
  }
  
  function goDefault() {
    
    document.location.reload();
  }

  
  function addCommas(nStr)
  {
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }


  function viewReadyResults (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_ready_results_dispatch'); ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.success && response.messages != "" ){
          $("#main-card").hide();
          $("#ready-results-card .card-body").html(response.messages);
          $("#ready-results-card #ready-results-table").DataTable();
          $("#ready-results-card").show();
        }else{
          $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },
      error: function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      }
    
    })
  }

  function goBackFromReadyResultsCard (elem,evt) {
    $("#main-card").show();
    $("#ready-results-card").hide();
  }

  function proceedFromReadyResult(elem,evt,lab_id,referral = false) {
    elem = $(elem);
    patient_name = elem.attr("data-patient-name");
    swal({
      title: 'Choose Action',
      text: "Do You Want To?",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#9124a3',
      cancelButtonColor: '#4caf50',
      confirmButtonText: 'Print All Test Results',
      cancelButtonText : "Select Test Results To Print "
    }).then(function(){
        //Print All Results
       var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_pdf_tests_result_selected'); ?>"+"?lab_id="+lab_id+"&all=true";
       if(referral){
        url += "&referral=true"
       }
        window.location.assign(url);
    }, function(dismiss){
      if(dismiss == 'cancel'){
        // select-tests-table
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_ready_results_for_selection_for_printing_dispatch'); ?>"
        if(referral){
          url += "?referral=true"
         }


        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "lab_id="+lab_id,
          success : function(response){
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#ready-results-card").hide();
              $("#select-tests-card .card-title").html("Select Tests To Print <br>" + "Lab Id:  <em class='text-primary'>"+lab_id+"</em><br> Patient Name: <em class='text-primary'>"+patient_name+"</em>");
              $("#select-tests-card .card-body").html(messages);
              $("#select-tests-card").show();
              $("#print-selected-results").attr("data-referral",referral);
              $("#print-selected-results").show("fast");
            }else{
              
              $.notify({
              message:"Sorry Something Went Wrong."
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
        
      }
    });
  }

  function goBackFromSelectTestsCard (elem,evt) {
    $("#ready-results-card").show();
    
    $("#select-tests-card").hide();
    $("#print-selected-results").hide("fast");
    $("input").prop("checked",false);
  }

  function viewAwaitingResults (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_awaiting_results_dispatch'); ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.success && response.messages != "" ){
          $("#main-card").hide();
          $("#awaiting-results-card .card-body").html(response.messages);
          $("#awaiting-results-card #awaiting-results-table").DataTable();
          $("#awaiting-results-card").show();

        }else{
          $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },
      error: function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      }
    
    })
  }

  function goBackFromAwaitingResultsCard (elem,evt) {
    $("#main-card").show();
    
    $("#awaiting-results-card").hide();
  }

  function proceedFromAwaitingResult(elem,evt,lab_id,referral = false) {
    elem = $(elem);
    patient_name = elem.attr("data-patient-name");
    swal({
      title: 'Choose Action',
      text: "Do You Want To?",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#9124a3',
      cancelButtonColor: '#4caf50',
      confirmButtonText: 'Print All Test Results',
      cancelButtonText : "Select Test Results To Print "
    }).then(function(){
        //Print All Results
       var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_pdf_tests_result_selected'); ?>"+"?lab_id="+lab_id+"&all=true";
       if(referral){
        url += "&referral=true"
       }
        window.location.assign(url);
    }, function(dismiss){
      if(dismiss == 'cancel'){
        // select-tests-table
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_ready_results_for_selection_for_printing_dispatch'); ?>"
         if(referral){
          url += "?referral=true"
         }
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "lab_id="+lab_id,
          success : function(response){
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#awaiting-results-card").hide();
              $("#select-tests-card-awaiting .card-title").html("Select Tests To Print <br>" + "Lab Id:  <em class='text-primary'>"+lab_id+"</em><br> Patient Name: <em class='text-primary'>"+patient_name+"</em>");
              $("#select-tests-card-awaiting .card-body").html(messages);
              $("#select-tests-card-awaiting").show();
              $("#print-selected-results").attr("data-referral",referral);
              $("#print-selected-results").show("fast");
              
            }else{
              
              $.notify({
              message:"Sorry Something Went Wrong."
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
        
      }
    });
  }

  function goBackFromSelectTestsCardAwaiting (elem,evt) {
    $("#awaiting-results-card").show();
    $("#print-selected-results").hide("fast");
    $("#select-tests-card-awaiting").hide();
    $("input").prop("checked",false);
  }

  function checkBoxEvt(elem,event){
    var elem = $(elem);

    var isChecked =  elem.prop("checked");
    var test_id = elem.attr("data-main-test-id");
    var sub_test = elem.attr("data-sub-test");
    var main_test = elem.attr("data-main-test");
    var super_test_id = elem.attr("data-super-test-id");
    var test_name = elem.attr('data-test-name'); 
    var row_id = elem.attr("data-row-id");
    var sub_tests = elem.attr("data-sub-tests");
    

    if(isChecked){
      var data = {
        'test_id' : test_id,
        'sub_test' : sub_test,
        'main_test' : main_test,
        'super_test_id' : super_test_id,
        'test_name' : test_name,
        'row_id' : row_id
      };
      if(sub_tests != "" && main_test == 1){
        var sub_tests_arr = sub_tests.split(",");
        for(var i = 0; i < sub_tests_arr.length; i++){
          var sub_test_id = sub_tests_arr[i];
          $("input[data-row-id='"+sub_test_id+"'][data-sub-test=1]").prop("checked",true);

          var elem = $("input[data-row-id='"+sub_test_id+"']");
          var isChecked =  elem.prop("checked");
          var test_id = elem.attr("data-main-test-id");
          var sub_test = elem.attr("data-sub-test");
          var main_test = elem.attr("data-main-test");
          var super_test_id = elem.attr("data-super-test-id");
          var test_name = elem.attr('data-test-name'); 
          var row_id = elem.attr("data-row-id");
          var sub_tests = elem.attr("data-sub-tests");
          var data = {
            'test_id' : test_id,
            'sub_test' : sub_test,
            'main_test' : main_test,
            'super_test_id' : super_test_id,
            'test_name' : test_name,
            'row_id' : row_id
          };

          
        }
      }else{
        
        
      }
      console.log(data)
      
    }else{
      if(sub_tests != "" && main_test == 1){
        var sub_tests_arr = sub_tests.split(",");
        for(var i = 0; i < sub_tests_arr.length; i++){
          var sub_test_id = sub_tests_arr[i];
          $("input[data-row-id='"+sub_test_id+"']").prop("checked",false)
          
        }
      }else{
         
      }
    }
    console.log(tests_selected_obj)
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
        }
        $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        $logged_in_user_name = $user_name;
      }
    ?>
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
     
      <div class="content" tabindex="-1">
        <div class="container-fluid">
         
          <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <?php
            
          ?>
          <?php
           if($user_position == "admin"){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  }?>
           
          <h3 class="text-center" style="text-transform: capitalize;"><?php echo $personnel_name; ?></h3>
          <?php if($user_position == "admin"){ ?>
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

                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>
                  <button class="btn btn-primary btn-action" onclick="viewReadyResults(this,event)">Print Ready Results</button>
                  <button class="btn btn-info btn-action" onclick="viewAwaitingResults(this,event)">Print Results For Preview</button>
                  
                </div>
              </div>

               <div class="card" id="select-tests-card-awaiting" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromSelectTestsCardAwaiting(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Select Tests To Print </h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromSelectTestsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Select Tests To Print </h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="ready-results-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromReadyResultsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Ready Results</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="awaiting-results-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromAwaitingResultsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Results Awaiting Completion</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


            </div>
          </div>
          
        </div>
      </div>
      
      <div class="modal fade" data-backdrop="static" id="perform-action-on-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Choose Action To Perform On: </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <div class="table-responsive">
      
                <table class="table table-hover" id="perform-action-on-patient-modal" cellspacing="0" width="100%" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Option</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td onclick="initiatePatient(this,event)" class="text-primary">Initiate Patient</td>
                    </tr>

                    <tr>
                      <td>2</td>
                      <td onclick="editPatientInfo(this,event)" class="text-primary">Edit Patient Info</td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td onclick="viewPatientsRecords(this,event)" class="text-primary">View Patient's Records</td>
                    </tr>
                    
                    
                  </tbody>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="initiate-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return goDefault()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <div class="choose-action">
                <h4 style="margin-bottom: 40px;">Do You Want To: </h4>
                <button class="btn btn-primary" onclick="selectFromRegistered()">Select From Registered Patients</button>
                <button class="btn btn-info" onclick="newPatient()">Enter New Patient</button>
              </div>
              
              <?php $attributes = array('class' => '','id' => 'patient-name-form','style' => 'display: none;') ?>
              <?php echo form_open('',$attributes); ?>
                <div class="form-group">
                  <label for="patient-name">Enter Patient Name: </label>
                  <input type="text" id="patient-name" class="form-control" name="patient-name">
                  <span class="form-error"></span>
                  <h5 style="margin-top: 30px;">(Optional)</h5>
                  <div class="form-group">
                    <label for="email">Enter Email Address</label>
                    <input type="email" id="email" name="email" class="form-control">
                  </div>
                </div>
                <input type="submit" class="btn btn-success" value="REQUEST" name="submit">
              <?php echo form_close(); ?>
            
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return goDefault()">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div rel="tooltip" data-toggle="tooltip" title="Print Selected Results" id="print-selected-results" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; display: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">printer</i>
        </div>
      </div>

      <div id="proceed-from-additional-info-tests-selected-btn" onclick="proceedFromAdditionalTestsSelected(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>

      <div id="proceed-btn" onclick="proceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
        <?php
          $code_date = date("j");
          $code_time = date("h");
          $initiation_code = substr(bin2hex($this->encryption->create_key(8)),4). '-' . $code_date .'-' . $code_time;
        ?>
        <p id="var-dump" style="display: none;"><?php echo $initiation_code; ?></p>
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {
      $("#print-selected-results").click(function (evt) {
        var tests_selected_obj = [];
        comment_status = 1;
        
        var all_sub_tests = [];
        var referral = $(this).attr("data-referral");
        $("input[data-row-id]:checked").each(function () {
          var elem = $(this);
          
          var test_id = elem.attr("data-main-test-id");
          var sub_test = elem.attr("data-sub-test");
          var main_test = elem.attr("data-main-test");
          var super_test_id = elem.attr("data-super-test-id");
          var test_name = elem.attr('data-test-name'); 
          var row_id = elem.attr("data-row-id");
          var sub_tests = elem.attr("data-sub-tests");
          var lab_id = elem.attr("data-lab-id");
          
          var data = {
            'test_id' : test_id,
            'sub_test' : sub_test,
            'main_test' : main_test,
            'super_test_id' : super_test_id,
            'test_name' : test_name,
            'row_id' : row_id,
            'lab_id' : lab_id
          };

          console.log(data);

          if(sub_tests != "" && main_test == 1){
            var sub_tests_arr = sub_tests.split(",");          
            all_sub_tests.push(sub_tests_arr)          
          }else{
          }
            tests_selected_obj.push(data);

        })

        var num = tests_selected_obj.length;
        if(num > 0){
          console.log(tests_selected_obj)
          var needed_obj = [];
          for(var j = 0;  j < tests_selected_obj.length; j++ ){
            var test_id = tests_selected_obj[j]['row_id'];
            needed_obj[j] = test_id;
          }
          console.log(needed_obj)
          var lab_id = tests_selected_obj[0]['lab_id'];
          var needed_str = needed_obj.join();

          console.log(needed_str)
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_pdf_tests_result_selected'); ?>"+"?lab_id="+lab_id+"&selected="+needed_str+"&all=false";

          if(referral){
            url += "&referral=true";
          }
          window.location.assign(url);
          
          
          
          
          console.log(all_sub_tests);
        }else{
          swal({
            title: 'Ooops!',
            text: "You Must Select At Least One Test",
            type: 'error'
          })
        }
        
      })
    });

  </script>
