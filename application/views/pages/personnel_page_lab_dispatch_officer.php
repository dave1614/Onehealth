<style>
  tr{
    cursor: pointer;
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
          $health_facility_country = $row->country;
          $health_facility_state = $row->state;
          $health_facility_address = $row->address;
          $health_facility_table_name = $row->table_name;
          $health_facility_date = $row->date;
          $health_facility_time = $row->time;
          $health_facility_slug = $row->slug;
        }
        $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
      }
    ?>
<script>
  

  

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
  
  
  
  function goDefault() {
    
    document.location.reload();
  }

  function viewReadyResults(elem,evt) {
    $("#main-card").hide();
    $(".table").DataTable();
    $("#ready-results-card").show();

  }

  function viewAwaitingResults (elem,evt) {
    $("#main-card").hide();
    $(".table").DataTable();
    $("#results-awaiting-comments-card").show();
  }

  function printResult (lab_id,status) {
    var pdf_url = "<?php echo $health_facility_slug; ?>" + "_"+lab_id+"_result.html";
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
       document.location.assign("<?php echo base_url('assets/images/'); ?>" + pdf_url);
    }, function(dismiss){
      if(dismiss == 'cancel'){
        // select-tests-table
        console.log('select tests')
        $("#select-tests-card").attr("data-status",status);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_ready_results_dispatch'); ?>"
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
              $("#results-awaiting-comments-card").hide();
              $("#ready-results-card").hide();
              $("#select-tests-card .card-body").html(messages);
              $("#select-tests-card").show();
              $("#print-selected-results").show();
            }
          },error : function () {
            
          } 
        });   
        
      }
    });
    
  }

  function goBackFromSelectUserTests(elem,event){
    $("#select-tests-card").hide();
    // tests_selected_obj = [];
    var status = $("#select-tests-card").attr("data-status");
    if(status == "awaiting"){
      $("#results-awaiting-comments-card").show();
    }else{
      $("#ready-results-card").show();
    }
    $("#print-selected-results").hide();
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
          $("input[data-row-id='"+sub_test_id+"']").prop("checked",true);

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
      //console.log(data)
      
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
    // console.log(tests_selected_obj)
  }

  function mailResult(elem,e,lab_id){
    e.preventDefault();
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    console.log(lab_id)
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_email'); ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "lab_id="+lab_id,
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.success && response.email != ""){
          var patient_email = response.email;
          $("#emails-modal .modal-title").html("Sending Email To '" + patient_email + "' Enter Additional Emails To Send Result To")
          $("#emails-modal #lab-id").val(lab_id);
          $("#emails-modal").modal("show");
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

  function refreshPage() {
    document.location.reload();
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

              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromSelectUserTests(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Select User Tests To Print</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="results-awaiting-comments-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">Preview Results</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                </div>
                <div class="card-body">
                   <?php  
                      $health_facility_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
                          $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                        $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                        $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $form_array = array(
                        'verified' => 1,
                        'pathologists_comment' => ''
                      );
                      $all_patients = $this->onehealth_model->getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id);

                      if(is_array($all_patients)){
                        $all_patients = array_reverse($all_patients);
                        
                    ?>
                    
                    <div class="table-responsive">
                    <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Lab Id</th>
                          <th>Patient Name</th>
                          <th>Last Data Entry Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i = 0;
                          foreach($all_patients as $row){
                           $i++;
                            $id = $row->id;
                            $lab_id = $row->lab_id;
                            $first_name = $row->firstname;
                            $last_name = $row->surname;
                            $verification_date = $row->verification_date;
                            $verified = $row->verified;
                            $date_of_verification = $row->date_of_verification;
                            $date_of_verification2 = date_create($row->date_of_verification);
                            $curr_date = date_create(date("j M Y"));
                            $curr_time = date("h:i:sa"); 
                            // $date_diff = date_diff($curr_date,$date_of_verification2);
                            // $date_diff = $date_diff->format("%d");
                            // if($date_diff <= 1)  {

                        ?>
                        <tr onclick="return printResult('<?php echo $lab_id; ?>','awaiting')">
                          <td><?php echo $i; ?></td>
                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $first_name . " " . $last_name; ?></td>
                          <td><?php echo $date_of_verification; ?></td>
                          <td>
                            <a href="#" rel="tooltip" onclick="mailResult(this,event,'<?php echo $lab_id; ?>')" data-toggle="tooltip" title="Mail Result" class="btn btn-primary text-right">
                              <i class="fas fa-envelope"></i>
                            </a>
                          </td>
                        </tr>
                      <?php  } ?>
                      </tbody>
                    </table>
                  </div>
                  </div>
                  <?php }else{ ?>
                    <span class="text-warning">No Verified Test Result To Display.</span>
                  <?php } ?>
                </div> 
              </div> 


              <div class="card" id="ready-results-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Ready Results</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                </div>
                <div class="card-body">
                   <?php  
                      $health_facility_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
                          $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                        $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                        $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      
                      $all_patients = $this->onehealth_model->getPatientsTestRecordsWherePathologistHasEnteredComment($health_facility_patient_db_table,$sub_dept_id);

                      if(is_array($all_patients)){
                        $all_patients = array_reverse($all_patients);
                        echo "<h4>Click User To Print Result</h4>";
                    ?>
                    
                    <div class="table-responsive">
                    <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Lab Id</th>
                          <th>Patient Name</th>
                          <th>Last Data Entry Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i = 0;
                          foreach($all_patients as $row){
                           $i++;
                            $id = $row->id;
                            $lab_id = $row->lab_id;
                            $first_name = $row->firstname;
                            $last_name = $row->surname;
                            $verification_date = $row->verification_date;
                            $verified = $row->verified;
                            $date_of_verification = $row->date_of_verification;
                            $date_of_verification2 = date_create($row->date_of_verification);
                            $curr_date = date_create(date("j M Y"));
                            $curr_time = date("h:i:sa"); 
                            // $date_diff = date_diff($curr_date,$date_of_verification2);
                            // $date_diff = $date_diff->format("%d");
                            // if($date_diff <= 1)  {

                        ?>
                        <tr onclick="return printResult('<?php echo $lab_id; ?>','ready')">
                          <td><?php echo $i; ?></td>
                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $first_name . " " . $last_name; ?></td>
                          <td><?php echo $date_of_verification; ?></td>
                          <td>
                            <a href="#" rel="tooltip" onclick="mailResult(this,event,'<?php echo $lab_id; ?>')" data-toggle="tooltip" title="Mail Result" class="btn btn-primary text-right">
                              <i class="fas fa-envelope"></i>
                            </a>
                          </td>
                        </tr>
                      <?php  } ?>
                      </tbody>
                    </table>
                  </div>
                  </div>
                  <?php }else{ ?>
                    <span class="text-warning">No Verified Test Result To Display.</span>
                  <?php } ?>
                </div> 
              </div> 
            </div>
          </div>


          <div rel="tooltip" data-toggle="tooltip" title="Print Selected Results" id="print-selected-results" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; display: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">printer</i>
            </div>
          </div>
          
        </div>
      </div>
      <div class="modal fade" data-backdrop="static" id="emails-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Enter Additional Emails To Send Result To</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return goDefault()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="modal-body" id="modal-body">
          
            <?php $attributes = array('class' => '','id' => 'emails-form') ?>
            <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/send_emails_printing',$attributes); ?>
              <input type="hidden" id="lab-id" name="lab_id">
              <div class="form-group">
                
                <input type="text" id="emails" class="form-control" placeholder="e.g contact@onehealthissues.com,admin@onehealthissues.com,ikechukwunwogo@gmail.com" name="emails">
                <span class="form-error"></span>
                
              </div>
              <div class="form-check">
                  <label class="form-check-label">
                      <input class="form-check-input" name="send_to_user" id="send_to_user" checked="checked" type="checkbox" value="1">Send To This User
                      <!-- Option one is this and that&mdash;be sure to include why it's great -->
                      <span class="form-check-sign">
                          <span class="check"></span>
                      </span>
                  </label>
              </div>
              <input type="submit" class="btn btn-primary" value="PROCEED" name="submit">
            <?php echo form_close(); ?>
          
            
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return goDefault()">Close</button>
          </div>
        </div>
      </div>

      </div>
      <footer class="footer">
        <div class="container-fluid">
          
        </div>
        
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {

      $("#print-selected-results").click(function (evt) {
        var tests_selected_obj = [];
        comment_status = 1;
        
        var all_sub_tests = [];
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
          swal({
            title: 'Choose Action',
            text: "Do You Want To Print With Comment?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
          }).then(function(){
             comment_status = 1;
             var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pdf_tests_result_selected'); ?>"+"?lab_id="+lab_id+"&comment="+ comment_status +"&selected="+needed_str;
              window.location.assign(url);
          }, function(dismiss){
            if(dismiss == 'cancel'){
              comment_status = 0;
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pdf_tests_result_selected'); ?>"+"?lab_id="+lab_id+"&comment="+ comment_status +"&selected="+needed_str;
              window.location.assign(url);
            }
          });  
          
          
          
          console.log(all_sub_tests);
        }else{
          swal({
            title: 'Ooops!',
            text: "You Must Select At Least One Test",
            type: 'error'
          })
        }
        
      })
      
      $("#emails-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        console.log(form_data);
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function(response){
            $(".spinner-overlay").hide();
            if(response.success){
              $.notify({
              message:"Mail Sent Successfully"
              },{
                type : "success"  
              });
              setTimeout(refreshPage(),2000);
            }else if(response.empty){
              $.notify({
              message:"Both Fields Cannot Be Empty"
              },{
                type : "warning"  
              });
            }else{
              $.notify({
              message:"Sorry Something Went Wrong"
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
