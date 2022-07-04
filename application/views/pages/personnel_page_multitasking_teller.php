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
      
    }
    $admin = false;
    $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
    $selected_sub_dept_id = "";

  }
?>
<style>
  tr{
    cursor: pointer;
  }

  .card-title{
    text-transform: capitalize;
  }
</style>
<script>
  var tests_selected_obj = [];
  var lab_base_url = "";
  var selected_lab_name = "";
  var lab_base_url_comp = "";
  var selected_personnel_name = "";
  var selected_sub_dept_id = "";

  function goBackInitiationCodesCard(elem,evt){
    $("#initiation-codes-card").hide();
    $("#inpute-patient-code-modal").modal("show");
    $("#teller-card").show();
  }

  function goBackInitiationCodeCard(elem,evt){
    $("#initiation-code-card").hide("fast");
    $("#initiation-codes-card").show("fast");
  }

  function goBackInitiationCodeCard1(elem,evt){
    $("#initiation-code-card").hide("fast");
    $("#inpute-patient-code-modal").modal("show");
  }

  function loadPatientInitiationCodeTable(initiation_code,type) {
    var url = lab_base_url_comp + "/get-tests-initiation-code-teller";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "initiation_code="+initiation_code,
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.success && response.messages != "" ){
          var patient_name = response.patient_name;
          $("#initiation-code-card .card-title").html("Tests Selected For : <span class='text-primary' style='font-style: italic;'>"+patient_name+"<span>");
          if(type == 2){
            $("#initiation-code-card #go-back").attr('onclick', 'goBackInitiationCodeCard1(this,event)');
          }else if(type == 1){
            $("#initiation-code-card #go-back").attr('onclick', 'goBackInitiationCodeCard(this,event)');
          }
          $("#initiation-code-card .card-body").html(response.messages);
          $("#initiation-codes-card").hide("fast");
          $("#initiation-code-table").DataTable();
          $("#payment-history-table").DataTable();
          
          $("#initiation-code-card").show("fast");
          
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
          var submit_tests_url = lab_base_url_comp + "/submit_tests";
          
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
    var get_tests_url = lab_base_url_comp + "/get_tests2";
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
                $("#receptionist-card").hide();
                $("#patient-list-card").hide();
                $("#select-tests-card").show();
                
                $(".welcome-heading").html("Select Required Tests For " + patient_name);              
                $(".nav-pills").after("<button id='proceed' onclick='proceed()' class='btn btn-info'>Proceed</button> <button id='proceed' class='btn btn-warning' onclick='goDefault()'>Go Back</button>");
                if(status == "registered_patient"){
                  $('#proceed').attr('onclick' , "proceed('registered_patient','"+patient_user_id+"','"+patient_user_name+"','"+patient_name+"',false)")
                }else if(status == "new_patient"){
                  $('#proceed').attr('onclick' , "proceed('new_patient','','','',true)")
                }

                $('#select-tests-card .table').DataTable( {
                  
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
                  $("#receptionist-card").hide();
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
    var edit_tests_url = lab_base_url_comp + "/get_registered_patients_table";
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
        $("#receptionist-card").hide();
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

  function performFunctions (elem,evt) {
    $("#main-card").hide();
    $("#choose-lab-to-perform-functions-card").show();
  }

  function goBackFromChooseLabToPerformFunctionsCard  (elem,evt) {
    $("#main-card").show();
    $("#choose-lab-to-perform-functions-card").hide();
  }

  function chooseThisLabToPerformFunction(elem,evt,url,lab_name,sub_dept_id){
    evt.preventDefault();
    lab_base_url = url;
    selected_lab_name = lab_name;
    lab_base_url_comp = "";
    selected_sub_dept_id = sub_dept_id;
    $("#choose-lab-to-perform-functions-card").hide();
    choosePersonnelPerformFunction(elem,evt,"teller","teller",2) ;
  }

  function goBackFromChooseFunctionToPerformCard (elem,evt) {
    $("#choose-function-to-perform-card").hide();
    $("#choose-lab-to-perform-functions-card").show();
  }

  function choosePersonnelPerformFunction(elem,evt,personnel_slug,personnel_name,personnel_id){
    evt.preventDefault()
    lab_base_url_comp = lab_base_url + "/" +personnel_slug;
    selected_personnel_name = personnel_name;
    if(personnel_id == 1){
      $("#receptionist-card .card-title").html("Function As Receptionist In " +selected_lab_name);
      $("#receptionist-card").show();
    }else if(personnel_id == 2){
      
      $("#teller-card .card-title").html("Function As Teller In " +selected_lab_name);
      $("#teller-card").show();
    }else if(personnel_id == 3){
      
      $("#clerical-officer-card .card-title").html("Function As Clerical Officer In " +selected_lab_name);
      $("#clerical-officer-card").show();
    }
    $("#choose-function-to-perform-card").hide();
  }

  function goBackFromClericalOfficerCard (elem,evt) {
    $("#clerical-officer-card").hide();
    $("#choose-lab-to-perform-functions-card").show();
  }

  function goBackFromReceptionistCard (elem,evt) {
    $("#choose-lab-to-perform-functions-card").show();
    $("#receptionist-card").hide();
  }

  function goBackFromTellerCard (elem,evt) {
    $("#choose-lab-to-perform-functions-card").show();
    $("#teller-card").hide();
  }

  function submitMakePaymentsForm(elem,evt) {
    evt.preventDefault();
    var health_facility_logo = $("#facility_img");
    var logo_src = health_facility_logo.attr("src");
    var logo_src_substr = logo_src.substring(0,4);
    
    if(logo_src_substr !== "data"){
      var img_data_url = $("#facility_img").attr("src");
      var company_logo = {
       src:img_data_url,
        w: 80,
        h: 50
      };
    }else{
      var img_data_url = $("#facility_img").attr("src");
      var company_logo = "";
    }
    var url = lab_base_url_comp +  "/submit-test-payment-form";
    
    var amount = elem.querySelector("#make-payments-input").value;
    amount = Number(amount);
    var initiation_code = elem.getAttribute("data-initiation-code");
    
    var max = Number(elem.querySelector("#make-payments-input").getAttribute("max"));
    
    if(amount > max){
      swal({
        title: 'Error',
        text: "Amount Entered Here Cannot Be Greater Than "+addCommas(max),
        type: 'warning',
        
      })
    }else{
      swal({
        title: 'Warning',
        text: "You Are About To Make Payment For This Patient, Do You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed'
      }).then((result) => {
        $(".spinner-overlay").show();
        $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "amount="+amount+"&initiation_code="+initiation_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response.success && response.records != ""){
                response = response.records
                var lab_id = response.lab_id;
                
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = response.facility_state;
                var facility_country = response.facility_country;
                var facility_address = response.facility_address;
                var receipt_file = response.receipt_file;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var total_price = response.total_price;
                sum = response.amount;
                var balance = response.balance;
                balance = balance - sum;
                
                console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
                $(".spinner-overlay").hide();
                var get_pdf_tests_url = lab_base_url_comp + "/get_pdf_tests";
                $(".spinner-overlay").show();
                $.ajax({
                  url : get_pdf_tests_url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "get_pdf_tests=true&lab_id="+lab_id,
                  success : function (response) {
                    $(".spinner-overlay").hide();
                    var pdf_data =  {
                      'logo' : company_logo,
                      'color' : <?php echo $color; ?>,
                      'tests' : response,
                      'sum' : String(addCommas(sum)),
                      'facility_name' : facility_name,
                      'facility_id' : "<?php echo $health_facility_id; ?>",
                      'initiation_code' : initiation_code,
                      'lab_id' : lab_id,
                      'mod' : 'teller',
                      'facility_address' : "<?php echo $health_facility_address; ?>",
                      'patient_name' : patient_name,
                      'receipt_number' : receipt_number,
                      'facility_state' : facility_state,
                      'facility_country' : facility_country,
                      'date' : date,
                      'receipt_file' : receipt_file,
                      'is_teller' : 1,
                      'total_price' : String(addCommas(total_price)),
                      'balance' : String(addCommas(balance))
                    };
                    console.log(JSON.stringify(pdf_data));
                
                    var url = lab_base_url_comp + "/save_receipt";
                    // var pdf = btoa(doc.output());
                    $.ajax({
                      url : url,
                      type : "POST",
                      responseType : "json",
                      dataType : "json",
                      data : pdf_data,
                      
                      success : function (response) {
                        console.log(response)
                        if(response.success == true){
                          var pdf_url = "<?php echo base_url('assets/images/')?>" +receipt_file;
                          window.location.assign(pdf_url);
                        }else{
                          console.log('false')
                        }
                      },
                      error : function () {
                        
                      }
                    })
                  },
                  error : function () {
                    
                  }
                });  
                
              }else if(response.excess){
                swal({
                  title: 'Error',
                  text: "Amount Entered Here Cannot Be Greater Than "+addCommas(max),
                  type: 'warning',
                  
                })
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
      });
    }
  }

  function loadPreviousTransactions(elem) {
    $("#inpute-patient-code-modal").modal("hide");
    $("#teller-card").hide();
    var url = lab_base_url_comp + "/get-initiation-codes-teller"
    
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "get-initiation-codes=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        $("#initiation-codes-card .card-body").html(response);
        var table = $("#initiation-codes-table").DataTable();
        $('#initiation-codes-table tbody').on('click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } ); 
        $("#initiation-codes-card").show();

      },
      error : function () {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function goBackFromRefundPaymentCard (elem,evt) {
    $("#refund-payment-card").hide();
    $("#teller-card").show();
  }

  function checkAll () {
    $("input[type=checkbox]:visible").prop('checked', true);
  }

  function selectTimeRangeDisplayFundsChanged (elem,evt) {
    elem  = $(elem);
    var time_range = elem.val();
    var range = time_range;
    var url = lab_base_url_comp + "/change_funds_range";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "change_range=true&range="+range,
      success : function(response){
        
        $(".spinner-overlay").hide();
        $("#display-funds-table").html("");
        $("#display-funds-table").html(response);
      },
      error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    }); 
  }

  function goBackFrompaidPatientsListCard (elem,evt) {
    $("#clerical-officer-card").show();
    $("#paid-patients-list-card").hide();
  }

  function loadPatientInfo (elem,initiation_code,lab_id,patient_name,patient_email) {
    $(".spinner-overlay").show();

    var get_patients_tests = lab_base_url_comp + "/get_patients_tests";
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
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      }  

    });
   
  }

  function goBackFromEnterBioDataCard (elem,evt) {
    
    $("#paid-patients-list-card").show("fast");
    
    $("#enter-bio-data-card").hide("fast");  
  }

  function goBackFromCreatedPatientsListCard (elem,evt) {
    $("#clerical-officer-card").show();
    $("#created-paid-patients-list-card").hide();
  }

  function loadPatientInfoEdit (elem,lab_id) {
    $(".spinner-overlay").show();
    var get_patients_tests =lab_base_url_comp +  "/get_patients_tests";
    $.ajax({
      url : get_patients_tests,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "get_patients_tests=true&lab_id="+lab_id,
      success : function (response) {  
        var get_patients_tests = lab_base_url_comp + "/get_patient_bio_data";
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
              <?php
               if(is_null($health_facility_logo)){
                echo $data_url_img; 
               }else{ 
                ?> 
              <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
              <?php } ?>

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


              <div class="card" id="enter-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                   <button id='go-back' class='btn btn-warning' onclick='goBackFromEnterBioDataCard(this,event)'>Go Back</button>
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


              <div class="card" id="display-funds-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Transaction History</h3>
                </div>
                <div class="card-body" id="main-card-body">
                  
                  
                </div>
                  
                </div>
              </div>

  

              <div class="card" id="refund-payment-card" style="display: none;">
                <div class="card-header">
                   <?php
                   if($this->onehealth_model->checkIfUserIsMainAdminBool($health_facility_table_name,$user_name)){
                      $admin = true;
                   } 
                   ?>
                  <h3 class="card-title" id="welcome-heading">Refund Payment</h3>
                  <button class='btn btn-warning' onclick='goBackFromRefundPaymentCard(this,event)'>Go Back</button>
                  <?php 
                    $attr = array('id' => 'refund-payment-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                </div>
                <div class="card-body">
                
                </div>
                  
                </div>
              </div>


              <div class="card" id="initiation-codes-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Previously Initiated Patients</h3>
                  <button class='btn btn-warning' onclick='goBackInitiationCodesCard(this,event)'>Go Back</button>
                </div>
                <div class="card-body">
                  
                  
                </div>
              </div>

              <div class="card" id="initiation-code-card" style="display: none;">
                <div class="card-header">
                   
                  <h3 class="card-title"></h3>
                  <button id='go-back' class='btn btn-warning' onclick=''>Go Back</button>
                  
                </div>
                <div class="card-body">
                
                </div>
              </div>

              <div class="card" id="teller-card" style="display: none;">
                <div class="card-header">
                  <button id='go-back' class='btn btn-warning' onclick='goBackFromTellerCard(this,event)'>Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Function As Teller In</h3>
                </div>
                <div class="card-body">

                  <?php 
                    $attr = array('id' => 'mark-tests-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>
                  <div class="btn-grp">
                    <button class="btn btn-primary btn-action" data-toggle="modal" data-target="#inpute-patient-code-modal">Receive Payment</button>
                    <button class="btn btn-info btn-action" id="refund-payment">Refund Payment</button>
                    <?php if($this->onehealth_model->checkIfUserIsAnAdminBool($health_facility_table_name,$user_name)){ ?>
                      
                    <?php } ?>
                    <button class="btn btn-success btn-action" id="display-funds">Display Available Funds</button>
                  </div>
                  
                </div>
              </div>


              <div class="card" id="clerical-officer-card" style="display: none;">
                <div class="card-header">
                  <button id='go-back' class='btn btn-warning' onclick='goBackFromClericalOfficerCard(this,event)'>Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Function As Clerical Officer In In</h3>
                </div>
                <div class="card-body" style="margin-top: 40px;">
                  <?php 
                    $attr = array('id' => 'mark-tests-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                  <div class="btn-grp">
                    
                    <button class="btn btn-info btn-action" id="register-patient">Register Patient</button>
                    
                    <button class="btn btn-success btn-action" id="edit-registered-patients">Edit Old Registration</button>
                  </div>
                  
                </div>
              </div>

              <div class="card" id="receptionist-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromReceptionistCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Function As Receptionist In</h3>
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


              <div class="card" id="choose-function-to-perform-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromChooseFunctionToPerformCard(this,event)">Go Back</button>
                  <h3 class="card-title">Select Functionality To Perform In</h3>
                </div>
                <div class="card-body">
                  <?php
                  $all_labs_personnel = $this->onehealth_model->getPersonnelsBySubDeptId(1);
                  if(is_array($all_labs_personnel)){
                  ?>
                  <table class="table">
                    <tbody>
                      <?php 
                      $i = 0;
                      foreach($all_labs_personnel as $row){
                        $i++;
                        $personnel_id = $row->id;
                        $name = $row->name;
                        
                        $personnel_slug = $row->slug;
                        if($personnel_id == 1 || $personnel_id == 2 || $personnel_id == 3){
                        
                      ?>
                      <tr class="pointer-cursor">
                        <td><?php echo $i; ?>.</td>
                        <td><a style="text-transform: capitalize;" href="#" onclick="choosePersonnelPerformFunction(this,event,'<?php echo $personnel_slug; ?>','<?php echo  $name; ?>',<?php echo $personnel_id; ?>)"><?php echo $name; ?></a></td>
                      </tr>

                      <?php } } ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>

              <div class="card" id="choose-lab-to-perform-functions-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromChooseLabToPerformFunctionsCard(this,event)">Go Back</button>
                  <h3 class="card-title">Choose Lab. Dept. To Perform Function</h3>
                </div>
                <div class="card-body">
                  <?php
                  $all_labs = $this->onehealth_model->getSubDeptsByDeptId(1);
                  if(is_array($all_labs)){
                  ?>
                  <table class="table">
                    <tbody>
                      <?php 
                      $i = 0;
                      foreach($all_labs as $row){
                        $i++;
                        $sub_dept_id = $row->id;
                        $name = $row->name;
                        $dept_slug = $this->onehealth_model->getDeptParamById("slug",1);
                        $sub_dept_slug = $row->slug;
                        $personnel_slug = "receptionist";
                        
                        $url = site_url("onehealth/index/".$addition . "/".$dept_slug."/".$sub_dept_slug);
                      ?>
                      <tr class="pointer-cursor">
                        <td><?php echo $i; ?>.</td>
                        <td><a style="text-transform: capitalize;" href="#" onclick="chooseThisLabToPerformFunction(this,event,'<?php echo $url; ?>','<?php echo $name; ?>',<?php echo $sub_dept_id ?>)"><?php echo $name; ?></a></td>
                      </tr>

                      <?php } ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3>Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body" style="margin-top: 40px;">
                  <button class="btn btn-primary" onclick="performFunctions(this,event)">Perform Functions</button>
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

              <div class="card" id="paid-patients-list-card" style="display: none;">
                <div class="card-header">
                  
                  <h3 class="card-title" id="welcome-heading">All Patients Waiting To Be Registered In</h3>
                  <button id='go-back' class='btn btn-warning' onclick='goBackFrompaidPatientsListCard(this,event)'>Go Back</button>
                  
                </div>
                <div class="card-body">
                  
                </div>               
              </div>

              <div class="card" id="created-paid-patients-list-card" style="display: none;">
                <div class="card-header">
                  
                  <h3 class="card-title" id="welcome-heading">All Entered Patients</h3>
                  <button id='go-back' class='btn btn-warning' onclick='goBackFromCreatedPatientsListCard(this,event)'>Go Back</button>
                  
                </div>
                <div class="card-body">
                  
                </div>                
              </div>
            </div>
          </div>
          
        </div>
      </div>
      

      <div class="modal fade" data-backdrop="static" id="inpute-patient-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php $attributes = array('class' => '','id' => 'patient-code-form') ?>
              <?php echo form_open('',$attributes); ?>
                <div class="form-group">
                  <label for="patient-name">Enter Patient Initiation Code: </label>
                  <input type="text" id="patient-code" class="form-control" name="patient-code">
                  <span class="form-error"></span>
                </div>
                <input type="submit" class="btn btn-success" value="REQUEST" name="submit">
              <?php echo form_close(); ?>
              <h4 style="font-weight: bold;">OR</h4>
              <button class="btn btn-primary" onclick="loadPreviousTransactions(this)">View Initiated Patients</button>
            
              
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

      $("#edit-bio-data-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        $(".spinner-overlay").show();
        var lab_id = me.attr("data-lab-id");
        var url = lab_base_url_comp + "/submit_bio_data_edit/"+lab_id;
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

              var element = $('#edit-bio-data-form #edit_'+key);
              
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

      $("#edit-registered-patients").click(function (evt) {
        
        $(".spinner-overlay").show();
        var get_patients_url = lab_base_url_comp + "/get_created_paid_patients";
        $.ajax({
          url : get_patients_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_patients=true",
          success : function (response) {
            if(response !== 0){  
              $(".spinner-overlay").hide();
              $("#clerical-officer-card").hide();
              
              $("#created-paid-patients-list-card .card-body").html(response);
              $("#created-paid-patients-list-card").show();
              var table = $('#created-paid-patients-list-card #example').DataTable();

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
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "warning"  
              });

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


      $("#enter-bio-data-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        var lab_id = me.attr("data-lab-id");
        var url = lab_base_url_comp + "/submit_bio_data/"+lab_id;
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

      $("#clerical-officer-card #register-patient").click(function (evt) {
        
        $(".spinner-overlay").show();
        var get_patients_url = lab_base_url_comp + "/get_paid_patients";
        $.ajax({
          url : get_patients_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_patients=true",
          success : function (response) {

            if(response !== 0){
              $(".spinner-overlay").hide();
              $("#paid-patients-list-card .card-title").html("All Patients Waiting To Be Registered In " +selected_lab_name);

              $("#paid-patients-list-card .card-body").html(response);
              $("#clerical-officer-card").hide();
              $("#paid-patients-list-card").show();
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
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "warning"  
              });
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

      $("#select-time-range").change(function (evt) {
          
      });

      $("#display-funds").click(function (evt) {
       
        
        $(".spinner-overlay").show();
        var url = lab_base_url_comp + "/view_transaction_history_lab_teller";
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true",
          success : function(response){
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $(".spinner-overlay").hide();
              $("#teller-card").hide();
              $("#display-funds-card .card-body").html(messages);
              $("#display-funds-card #select-time-range").selectpicker();
              $("#display-funds-card").show();
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
        }); 
      })

      $("#refund-payment-form").submit(function (evt) {
        
          evt.preventDefault();
          var table = $('#example1').DataTable();
          table.search("").draw();
          patientCheckBoxEvt();
          var total = $('input[type=checkbox]:checked').length;
          // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
          var sum = 0;
          // var selectedRows = client_table.rows({ selected: true }).length;
          var patient_names = ",";
          var test_names = ",";
          $("input[type=checkbox]:checked").each(function(){
            sum += parseInt($(this).attr("data-cost"));
            patient_names += $(this).attr("data-patient-name");
            test_names += $(this).attr("test-name");
          });

          if(total > 0){
            <?php if($admin == true){ ?>
            swal({
              title: 'Continue?',
              text: "<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + " Are You Sure You Want To Refund Payment?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Refund'
            }).then((result) => {
                var url = lab_base_url_comp + "/refund_paid_tests";
                
                  var me = $(this);
                  var values = me.serializeArray();
                  values = values.concat(
                  jQuery('#myform input[type=checkbox]:not(:checked)').map(
                          function() {
                              return {"name": this.name, "value": this.value}
                          }).get()
                  );
                 
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "text",
                    dataType : "text",
                    data : values,
                    success : function (response) {
                      
                     if(response !== 0){
                      var lab_id = response;
                      $(".spinner-overlay").hide();
                      swal({
                        type: 'success',
                        title: 'Successful',
                        allowOutsideClick : false,
                        allowEscapeKey :false,
                        text: 'Funds Refunded Successfully',
                        
                        confirmButtonColor: '#3085d6',
                        
                        confirmButtonText: 'OK'
                        // footer: '<a href>Why do I have this issue?</a>'
                      }).then(
                      value => {
                       document.location.reload();
                      }
                    ).catch(swal.noop)
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
                  // $("#submit-form").append("<input type='hidden' name='test[]' value='"+ value + "'>");
                  // $("#submit-form").submit();
                });  
              <?php } else{ ?>
                var url = lab_base_url_comp + "/set_refunds_session_data";
                var request_successful = 0;
                $("input[type=checkbox]:checked").each(function(){
                  
                  var lab_id = $(this).attr("data-lab-id");
                  var date_paid = $(this).attr("data-date-paid");
                  var test_name = $(this).attr("data-test-name");
                  var cost = $(this).attr("data-cost");
                  var id = $(this).val();
                  var initiation_code = $(this).attr("data-initiation-code");
                  var patient_name = $(this).attr("data-patient-name");
                  patient_name = $.trim(patient_name);
                    
                  var value = {
                    "test_name" : test_name,
                    "id" : id,
                    "lab_id" : lab_id,
                    "date_paid": date_paid,
                    "cost":cost,
                    "initiation_code" : initiation_code,
                    "patient_name" : patient_name
                  };
                  
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "text",
                    dataType : "text",
                    async : false,
                    data : {data:value},
                    success : function (response) {
                      $(".spinner-overlay").hide();
                      console.log(response)
                      if(response == 1){
                        request_successful = 1; 
                      }else{
                        $.notify({
                        message:"Sorry Something Went Wrong"
                        },{
                          type : "danger"  
                        });
                      }
                    },
                    error :function () {
                      $(".spinner-overlay").hide();
                      $.notify({
                      message:"Sorry Something Went Wrong"
                      },{
                        type : "danger"  
                      });
                    }
                        
                  });
                });
                console.log(request_successful)
                if(request_successful == 1){
                  $(".spinner-overlay").show();
                  var url = lab_base_url_comp + "/notify_admin_refunds"
                  $.ajax({
                      url : url,
                      type : "POST",
                      responseType : "text",
                      dataType : "text",
                      data : "notify_admin=true&personnel="+"<?php echo $user_name ?>",

                      success : function (response) {
                        $(".spinner-overlay").hide();
                        
                        if(response == 1){
                          swal({
                            title: 'Ooops!',
                            text: "<p class='text-primary'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + "<span class='text-danger' style='font-style: italic;'> But You Do Not Have The Authorization To Refund Payment. Admin has been contacted. If Approved, funds will be refunded and you would be duly notified</span>",
                            type: 'error',
                            allowOutsideClick : false,
                            allowEscapeKey :false,
                            
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                          }).then((result) => {
                        
                            document.location.reload();
                          })
                        }else{
                          $.notify({
                          message:"Sorry Something Went Wrong"
                          },{
                            type : "danger"  
                          });
                        }      
                      },error : function () {
                        $(".spinner-overlay").hide();
                        $.notify({
                        message:"Sorry Something Went Wrong"
                        },{
                          type : "danger"  
                        });
                        }
                  })

                  
                }
              <?php } ?>
              
          }else{
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Sorry, you have not selected any tests. Please Select To Continue'
              // footer: '<a href>Why do I have this issue?</a>'
            })
          }
          return false;
      }) 

      $("#refund-payment").click(function (evt) {
       
        $("#teller-card").hide();
        $("#refund-payment-card").show();
        
        $(".spinner-overlay").show();
        var url =  lab_base_url_comp + "/view_tests";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "view_tests=true",
          success : function(response){
            $(".spinner-overlay").hide();
            
            $("#refund-payment-form").append("<h4>Select Paid Tests To Refund</h4>")
            $("#refund-payment-form").append(response);
            $("#refund-payment-form").show();
             var table = $('#example1').DataTable();

            $('#example1 tbody').on('click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } ); 
            
          },
          error : function () {
            
          }
        });

      })


      $("#patient-code-form").submit(function (evt) {
        evt.preventDefault();
        var patient_code = $("#patient-code").val();
        var get_code_data_url = lab_base_url_comp +  "/edit_tests_2";
        patient_code = $.trim(patient_code);
        if(patient_code == ""){
          $(".form-error").html("This Field Cannot Be Empty");
        }else{
          $(".spinner-overlay").show();
         $.ajax({
            url : get_code_data_url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "edit_tests=true&code="+patient_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response.success == true){
                $("#teller-card").hide();
                $("#inpute-patient-code-modal").modal("hide")
                loadPatientInitiationCodeTable(patient_code,2)
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
        var edit_tests_url = lab_base_url_comp + "/edit_tests";
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
