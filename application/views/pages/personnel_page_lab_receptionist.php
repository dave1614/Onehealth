<style>
  tr{
    cursor: pointer;
  }
</style>
<script>
  var tests_selected_obj = [];

  function goBackSubTests1(){
    $("#sub-tests-card").hide();
    $("#select-tests-card").show();
    
  }

  function viewTestSubTests (elem,e) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_tests_sub_tests'); ?>";
    var tr = $(elem.parentElement.parentElement);
    var id = tr.find(".tests-checkboxes").attr("data-main-test-id");
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "test_id="+id+"&receptionist=true",
      success : function (response) {
        // console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != "" && response.test_name != ""){
          $(".spinner-overlay").hide();
          var messages = response.messages;
          var test_name = response.test_name;
          var card_header_str = "Sub Tests Of " + test_name;
          $("#sub-tests-card .card-title").html(card_header_str);
          $("#sub-tests-card .card-body").html(messages);

          $("#sub-tests-table").DataTable();
          $("#select-tests-card").hide();
          $("#sub-tests-card").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function testRowClicked(elem,event){
    elem = $(elem);
    var checkbox = elem.find(".tests-checkboxes");
    console.log(checkbox)
    var isChecked = checkbox.prop("checked");
    console.log(isChecked)
    var main_test_id = elem.find(".tests-checkboxes").attr("data-main-test-id");
    var test_id = elem.find(".tests-checkboxes").attr("data-testid");
    var test_name = elem.find(".tests-checkboxes").attr("data-testname");
    var test_cost = elem.find(".tests-checkboxes").attr("rel");
    var ta_time = elem.find(".tests-checkboxes").attr("data-testta");
    var initiation_code = $("#var-dump").html();
    var patient_name = $("#patient-name").val();
    var email = $("#email").val();
    patient_name = $.trim(patient_name);
    var value = {
      "status" : "new_patient",
      "main_test_id" : main_test_id,
      "test_id": test_id,
      "test_name":test_name,
      "test_cost" : test_cost,
      "ta_time" : ta_time,
      "initiation_code" : initiation_code,
      "patient_name" : patient_name,
      "email" : email
    };
    if(isChecked){

      tests_selected_obj.push(value)
      $(checkbox).prop("checked",true);
      console.log(elem.find(".tests-checkboxes"))
    }else{      
      $(checkbox).prop("checked",false);
      console.log(elem.find(".tests-checkboxes"))
      var index = tests_selected_obj.map(function(obj, index) {
          if(obj.test_id === test_id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        tests_selected_obj.splice(index, 1);
      }
    }
  }

  function testRadioClicked(elem,e) {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    var isChecked =  elem.checked;
    var main_test_id = elem.getAttribute("data-main-test-id");
    var test_id = elem.getAttribute("data-testid");
    var test_name = elem.getAttribute("data-testname");
    var test_cost = elem.getAttribute("rel");
    var ta_time = elem.getAttribute("data-testta");
    
    var patient_name = $("#patient-name").val();
    var email = $("#email").val();
    patient_name = $.trim(patient_name);
    var value = {
      "status" : "new_patient",
      "main_test_id" : main_test_id,
      "test_id": test_id,
      "test_name":test_name,
      "test_cost" : test_cost,
      "ta_time" : ta_time,
      
      "patient_name" : patient_name,
      "email" : email
    };
    if(isChecked){
      tests_selected_obj.push(value)
    }else{      
      var index = tests_selected_obj.map(function(obj, index) {
          if(obj.test_id === test_id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        tests_selected_obj.splice(index, 1);
      }
    }
  } 

  function patientCheckBoxEvt() {
    var total = tests_selected_obj.length;
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    // $("input[type=checkbox]:checked").each(function(){
    //   sum += parseInt($(this).attr("rel"));
    // });
    for(var i = 0; i < tests_selected_obj.length; i++){
      var test_cost = tests_selected_obj[i]['test_cost'];
      sum += parseInt(test_cost);
    }
    if(total > 0){
      
      if(!$("#num-tests-para")){
        $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " test selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }else{
        $("#num-tests-para").remove();
        $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }
    }else{
      $("#num-tests-para").html("");
    }
  }

  function proceed (status,patient_user_id,patient_user_name,patient_name1,isemail,table) {
    // var table = $('.table-test').DataTable();
    // table.search("");
    // console.log($("#example_filter input").length)
    // $("#example_filter input").val("");
    
    patientCheckBoxEvt();
    var total = tests_selected_obj.length;
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    
    for(var i = 0; i < tests_selected_obj.length; i++){
      var test_cost = tests_selected_obj[i]['test_cost'];
      sum += parseInt(test_cost);
    }

    if(total > 0){
      
      swal({
        title: 'Continue?',
        text: "<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + " Do Want To Continue?",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
      }).then((result) => {
          var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_tests') ?>";
          
          for(var i = 0; i < tests_selected_obj.length; i++){
            
            if(status == "new_patient"){  
              if(isemail){  
                
              }else{
                delete tests_selected_obj[i]['email'];
              }
            } 
            else if(status == "registered_patient"){
              if(patient_user_name !== ""){
                tests_selected_obj[i].status = status;                
                delete tests_selected_obj[i]['email'];
                tests_selected_obj[i].patient_user_name = patient_user_name;
                tests_selected_obj[i].patient_user_id = patient_user_id;
                tests_selected_obj[i].patient_name = patient_name1;
              }
            }
          }
          console.log({data : tests_selected_obj});
          
          $(".spinner-overlay").show();
          $.ajax({
            url : submit_tests_url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : {data : tests_selected_obj},
            success : function (response) {
              console.log(response)
             if(response.success && response.initiation_code != ""){
              $(".spinner-overlay").hide();
              swal({
                type: 'success',
                title: 'Successful',
                allowOutsideClick : false,
                allowEscapeKey :false,
                text: 'The Tests Have Been Added Successfully. Your initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + response.initiation_code + '\')">' + response.initiation_code +'</b>. Click Initiation Code To Copy.'
                // footer: '<a href>Why do I have this issue?</a>'
              }).then((result) => {
                document.location.reload();
              });
             }
             else{
              $(".spinner-overlay").hide();
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
             }
            },
            error : function(){
              $(".spinner-overlay").hide();
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
            }
          })
            
          $("#submit-form").submit(); 
          
      })
    }else{
      swal({
        type: 'error',
        title: 'Oops.....',
        text: 'Sorry, you have not selected any tests. Please Select To Continue'
        // footer: '<a href>Why do I have this issue?</a>'
      })
    }
    
  }

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

  function submitPatientNameForm1 (elem,status){
    // $(".spinner-overlay").show();
    submitPatientNameForm (elem,status);
  }

  function submitPatientNameForm (elem,status) {    
    tests_selected_obj = [];
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    if(status == "new_patient"){
      var patient_name = $("#patient-name").val();
      var email = $("#email").val();
    }else if(status == "registered_patient"){
      var patient_name = elem.getAttribute("data-name");
      var patient_user_name = elem.getAttribute("data-user-name");
      var patient_user_id = elem.getAttribute("data-user-id");
      var email = elem.getAttribute("data-email");
      console.log(email)
    }
    var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_tests2'); ?>";
    patient_name = $.trim(patient_name);      
      if(format.test(patient_name)){
        $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 

      }else{   
        if(email !== ""){  
          if(validateEmail(email)){  

            $(".spinner-overlay").show();
            $.ajax({
              url : get_tests_url,
              type : "POST",
              responseType : "text",
              dataType : "text",
              data : "get_tests=true",
              success : function (response) {                
                $(".spinner-overlay").hide();
                $("#select-tests-card .card-body").append(response)
                $("#initiate-patient-modal").modal('hide');        
                $("#main-card").hide();
                $("#patient-list-card").hide();
                $("#select-tests-card").show();
                
                $(".welcome-heading").html("Select Required Tests For " + patient_name);              
                $(".nav-pills").after("<button id='proceed' onclick='proceed()' class='btn btn-info'>Proceed</button> <button id='proceed' class='btn btn-warning' onclick='goDefault()'>Go Back</button>");
                if(status == "registered_patient"){
                  $('#proceed').attr('onclick' , "proceed('registered_patient','"+patient_user_id+"','"+patient_user_name+"','"+patient_name+"',false)")
                }else if(status == "new_patient"){
                  $('#proceed').attr('onclick' , "proceed('new_patient','','','',true)")
                }

                $('.table').DataTable( {
                  
                  "paging" : true,
                  
                  
                  language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                  }
                });
                $("#tests-body").focus();
                $("#tests-table_filter input").addClass("form-control").attr("id","search");
                
              },
              error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry something went wrong"
                },{
                  type : "danger"  
                });
              } 
            }); 
          }else{
            $.notify({
            message:"Email Address Format Not Valid"
            },{
              type : "danger"  
            });
          }
        }else{
          $(".spinner-overlay").show();
          $.ajax({
            url : get_tests_url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get_tests=true",
            success : function (response) {
              
                $(".spinner-overlay").hide();
                $("#select-tests-card .card-body").append(response)
                 $("#initiate-patient-modal").modal('hide');        
                  $("#main-card").hide();
                  $("#patient-list-card").hide();
                  $("#select-tests-card").show();
                  
                  $(".welcome-heading").html("Select Required Tests For " + patient_name);              
                  $(".nav-pills").after("<button id='proceed' onclick='proceed()' class='btn btn-info'>Proceed</button> <button id='proceed' class='btn btn-warning' onclick='goDefault()'>Go Back</button>");
                  var table = $('.table-test').DataTable( {
                   
                    language: {
                      search: "_INPUT_",
                      searchPlaceholder: "Search Tests",
                    }
                  });
                  if(status == "registered_patient"){
                    $('#proceed').attr('onclick' , "proceed('registered_patient','"+patient_user_id+"','"+patient_user_name+"','"+patient_name+"',false)")
                  }else if(status == "new_patient"){
                    $('#proceed').attr('onclick' , "proceed('new_patient','','','',false)")
                  }

                  $("#proceed").click(function (evt) {
                    console.log('sslsl')
                    
                  })

                  
                  $("#tests-body").focus();
                  $("#tests-table_filter input").addClass("form-control").attr("id","search");
              
            },
            error : function () {
              $(".spinner-overlay").hide();
              $.notify({
              message:"Sorry something went wrong"
              },{
                type : "danger"  
              });
            } 
          });
        }   
      }
  } 

  function resetDefault () {
    // var myNode = document.getElementById("modal-body");
    // while (myNode.firstChild) {
    //     myNode.removeChild(myNode.firstChild);
    // }
   
    // myNode.innerHTML = '<div class="choose-action"><h4 style="margin-bottom: 40px;">Do You Want To: </h4><button class="btn btn-primary" onclick="selectFromRegistered()">Select From Registered Patients</button><button class="btn btn-info" onclick="newPatient()">Enter New Patient</button></div>';
    var save_table = $('#modal-body #patient-list-table').detach();
    var save_form = $('#modal-body #patient-name-form').detach();
    var save_choose_action = $('#modal-body .choose-action').detach();
    $('#modal-body').empty().append(save_table);
    $('#modal-body').append(save_form);
    $('#modal-body').append(save_choose_action);
    $('#modal-body #patient-name-form').hide();
    $('#modal-body #patient-list-table').hide();
    $('#modal-body .choose-action').show();
  }
  
  function selectFromRegistered(){
    $(".choose-action").hide();
    var edit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_registered_patients_table'); ?>";
        $(".spinner-overlay").show();
        $.ajax({
           url : edit_tests_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "",
          success : function(response){
            $(".spinner-overlay").hide();
            console.log(response);
            $("#initiate-patient-modal").modal("hide");
            $("#main-card").hide();
            $("#patient-list-card .card-body").append(response);
            $("#patient-list-table1").DataTable();
            $("#patient-list-card").show();
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Sorry, something went wrong. Please try again!'
              // footer: '<a href>Why do I have this issue?</a>'
            })
            /* body... */
          }  
        });
    
  }

  function newPatient() {
    $(".choose-action").hide();
    $("#patient-name-form").show();
  }

  function clickEditPatientsTable() {
    
  }
  function reloadPage() {
    document.location.reload();
  }
  function editPatient(){
     $("#welcome-heading").html("Enter Patient Initiation Code");
     $("#welcome-heading").after("<p class='text-secondary'>Note: Code Is case sensitive</p>")
     $("#quest").hide("slow");
     $(".btn-action").hide("slow");
     $("#initiation-code-form").show("slow");
     $(".card-body").append("<button id='proceed' class='btn btn-warning' onclick='reloadPage()'>Go Back</button>");
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
                  <button class="btn btn-primary btn-action" data-toggle="modal" data-target="#initiate-patient-modal">Initiate Patient</button>
                  <button class="btn btn-info btn-action" onclick="editPatient()">Edit A Previously Initiated Patient</button>
                  <?php $attributes = array('class' => '', 'style' => 'display:none;','id' => 'initiation-code-form') ?>
                  <?php echo form_open('',$attributes); ?>
                    <div class="form-group">
                      <label for="initiation-code">Enter Patient Initiation Code: </label>
                      <input type="text" id="initiation-code" class="form-control" name="initiation-code">
                      <span class="initiation-code-form-error" style="color: red; font-style: italic;"></span>
                    </div>
                    <input type="submit" class="btn btn-success" value="REQUEST" name="submit">
                  <?php echo form_close(); ?>
                </div>
              </div>

              <div class="card" id="sub-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1()">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="patient-list-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Registered Patients</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title"></h3>
                </div>
                <div class="card-body">
              
                </div> 
              </div> 
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

     //Submit event for edit test patient form
     $(".edit-patient-test-form").submit(function (evt) {
       evt.preventDefault();
       var test_id = $("#test-record-test-id").val();
       var test_name = $("#test-name").val();
       var patient_name = $("#patient-name").val();
       var test_cost = $("#test-cost").val();
       var ta_time = $("#ta-time").val();
       var id = $("#test-record-id").val();
        if(test_name !== "" && patient_name !== "" && ta_time !== 0 && test_cost !== 0){
          var edit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_tests_record'); ?>";
          $(".spinner-overlay").show();
          $.ajax({
             url : edit_tests_url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "edit_tests=true&id="+id + "&test_name="+test_name+"&patient_name="+patient_name+"&test_cost=" +test_cost+"&ta_time="+ta_time,
            success : function(response){
              console.log(response)
              $(".spinner-overlay").hide();
              if(response == 1){
                
                $("#"+id+"-modal").modal("hide");
                $("#"+id+"-tr .test-id").html(test_id);
                $("#"+id+"-tr .test-name").html(test_name);
                $("#"+id+"-tr .patient-name").html(patient_name);
                $("#"+id+"-tr .test-cost").html(test_cost);
                $("#"+id+"-tr .ta-time").html(ta_time);
              }else{

                $("#"+id+"-modal").modal("hide");
                  swal({
                    type: 'error',
                    title: 'Oops.....',
                    text: 'Sorry, something went wrong. Please try again!'
                    // footer: '<a href>Why do I have this issue?</a>'
                  })
              }
            },
           error : function(){

              $(".spinner-overlay").hide();
              $("#"+id+"-modal").modal("hide");
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
            }
          })
        }
     })

      //Submit event for initiation code form
      $("#initiation-code-form").submit(function (evt) {
        evt.preventDefault();
        var initiation_code = $("#initiation-code").val();
        var edit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_tests'); ?>";
        if(initiation_code == ""){
          $(".initiation-code-form-error").html("This Field Cannot Be Empty");
        }else{
          $(".spinner-overlay").show();
         $.ajax({
            url : edit_tests_url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "edit_tests=true&code="+initiation_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response !== "wrong"){
               $("#welcome-heading").html("Edit Test Fields For Initiation Code: " + initiation_code);
                // $("#quest").hide("slow");
                $("#initiation-code-form").hide("slow");
                
                $(".card-body").append(response);
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
                   swal({
                    type: 'error',
                    title: 'Oops.....',
                    text: 'Sorry, no record with this initiation code. Please try again!'
                    // footer: '<a href>Why do I have this issue?</a>'
                  })
                 }  
            },
           error : function(){
              $(".spinner-overlay").hide();
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
            }
          }); 
        }
      });

     
      var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
      //Submit action for patient name form
      $("#patient-name-form").submit(function(evt){
        var device_height = $(window).height();

        evt.preventDefault();
        submitPatientNameForm('test','new_patient');
        
      });


      //onkeyup event for patient name form
      $("#patient-name-form").on("keyup",function(evt){
        if(evt.key !== "Enter"){
          var patient_name = $("#patient-name").val();
          if($(".form-error").html() == "This Field Cannot Be Empty"){
            $(".form-error").html("");
          }

          if(format.test(patient_name)){
           $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 
          }else{
            $(".form-error").html(""); 
          }
          
        }
      });
      
      
      
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
function submitEditPatientTestRecord(evt,id){
  
       evt.preventDefault();
       var test_id = $("#"+ id +"-test-record-test-id").val();
       var test_name = $("#"+ id +"-test-name").val();
       var patient_name = $("#"+ id +"-patient-name").val();
       var test_cost = $("#"+ id +"-test-cost").val();
       var ta_time = $("#"+ id +"-ta-time").val();
       var id = $("#"+ id +"-test-record-id").val();
      
        if(test_name !== "" && patient_name !== "" && ta_time !== 0 && test_cost !== 0){
          var edit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_tests_record'); ?>";
            $(".spinner-overlay").show();
          $.ajax({
             url : edit_tests_url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "edit_tests=true&id="+id + "&test_name="+test_name+"&patient_name="+patient_name+"&test_cost=" +test_cost+"&ta_time="+ta_time,
            success : function(response){
              $(".spinner-overlay").hide();
              // console.log(response)
              if(response == 1){
                // console.log("#"+id+"-modal")
                $("#"+id+"-modal").modal("hide");
                $("#"+id+"-tr .test-id").html(test_id);
                $("#"+id+"-tr .test-name").html(test_name);
                $("#"+id+"-tr .patient-name").html(patient_name);
                $("#"+id+"-tr .test-cost").html(test_cost);
                $("#"+id+"-tr .ta-time").html(ta_time);
              }else{
                $("#"+id+"-modal").modal("hide");
                  swal({
                    type: 'error',
                    title: 'Oops.....',
                    text: 'Sorry, something went wrong. Please try again!'
                    // footer: '<a href>Why do I have this issue?</a>'
                  })
              }
            },
           error : function(){
              $(".spinner-overlay").hide();
              $("#"+id+"-modal").modal("hide");
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
            }
          })
        }else{
          swal({
            type: 'error',
            title: 'Oops.....',
            text: 'Sorry, one or more fields are empty. Please enter valid values'
            // footer: '<a href>Why do I have this issue?</a>'
          })
        }
}
  </script>
