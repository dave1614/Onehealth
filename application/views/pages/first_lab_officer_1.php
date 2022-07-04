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
  var referring_facility_id = "";
  var referring_facility_name = "";
  var referring_facility_lab_to_lab_discount = "";

 
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

  function registerNewPatient (elem,evt) {

    swal({
      title: 'Does This Patient Have A Onehealth&reg; Account?',
      text: "<p><em class='text-primary'>Click Yes To Register His Account With Your Facility.</em></p> <p><em class='text-primary'>No To Register A New Onehealth&reg; Account.</em></p>",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#4caf50',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(function(){
      $("#enter-username-for-registration-modal").modal("show");
    }, function(dismiss){
      if(dismiss == 'cancel'){
        swal({
          title: 'Is This Patient Less Than A Day Old?',
          text: "<em class='text-primary'>Click Yes To Enable Date And Time Of Birth.</em>",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#4caf50',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then(function(){
          $("#register-new-patient-card #dob").datetimepicker({
              // format: 'DD-MM-YYYY h:mm a',
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
          $("#main-card").hide("fast");
          $("#register-new-patient-card #register-new-patient-form").attr("data-type","date-time");
          $("#register-new-patient-card").show("fast");
        }, function(dismiss){
          if(dismiss == 'cancel'){
            $("#register-new-patient-card #dob").datetimepicker({
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
            $("#main-card").hide("fast");
            $("#register-new-patient-card #register-new-patient-form").attr("data-type","date-only");
            $("#register-new-patient-card").show("fast");
          }
        });
      }
    });  
        
    
  }

  function goBackFromRegisterNewPatientCard(elem,evt){
    swal({
      title: 'Are You Sure You Want To Go Back?',
      text: "",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#4caf50',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(function(){
      $("#main-card").show("fast");
      $("#register-new-patient-card").hide("fast");
    });
  }

  function userTypeChangedEnter (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    if(val == "fp"){
      $("#register-new-patient-form #code_div").hide();
    }else if(val == "pfp"){
      $("#register-new-patient-form #code_div").show();
    }else if(val == "nfp"){
      $("#register-new-patient-form #code_div").show();
    }
  }

  function userTypeCodeKeyUp (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val)
    if($("#register-new-patient-form #user_type #fp").prop("checked")){
      var user_type = "fp";
    }else if($("#register-new-patient-form #user_type #pfp").prop("checked")){
      var user_type = "pfp";
    }else if($("#register-new-patient-form #user_type #nfp").prop("checked")){
      var user_type = "nfp";
    }

    var firstname = $("#register-new-patient-form #first_name").val();
    var lastname = $("#register-new-patient-form #last_name").val();
    var spinner = $("#register-new-patient-form .spinner");
    var code_status = $("#register-new-patient-form #code_status");
    
    if(val != ""){
      spinner.show();
      elem.addClass('disabled');
      console.log(val + " : " + user_type + " : " + firstname + " : " + lastname)
        
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/check_if_code_entered_is_correct'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&val="+val+"&user_type="+user_type+"&firstname="+firstname+"&lastname="+lastname,
        success : function (response) {
          console.log(response)
          spinner.hide();
          elem.removeClass('disabled');

          var messages = response.messages;
          if(response.success == true){
            code_status.html("<em class='text-success'>"+messages+"</em>");
          }else{
            code_status.html("<em class='text-danger'>"+messages+"</em>");
          }
          
        },
        error: function (jqXHR,textStatus,errorThrown) {
          spinner.hide();
          elem.removeClass('disabled');
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function viewRegisteredPatients (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_all_registered_patients_front_desk_lab'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#main-card").hide("fast");
          $("#registered-patients-card .card-body").html(messages);
          $("#registered-patients-card #registered-patients-table").DataTable();
          $("#registered-patients-card").show("fast");
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
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

  function goBackRegisteredPatientsCard (elem,evt) {
    $("#main-card").show("fast");
    
    $("#registered-patients-card").hide("fast");
  }

  function goBackFromRegisteredPatientsCard (elem,evt) {
    $("#main-card").show("fast");
    $("#registered-patients-card").hide("fast");
  }

  function performActionOnPatient (elem,evt,id) {
    elem = $(elem);
    if(id != ""){
      var patient_name = elem.attr("data-patient-name");
      patient_facility_id = id;
      $("#perform-action-on-patient-modal .modal-title").html("Choose Action To Perform On " + patient_name + ":")
      $("#perform-action-on-patient-modal").modal("show");
    }
  }

  function initiatePatient(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
      swal({
        title: 'Choose Action: ',
        text: "Do You Want To",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#4caf50',
        confirmButtonText: 'Select Tests From This Facility',
        cancelButtonText: 'Perform Referral'
      }).then(function(){
        
        var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_tests_for_this_lab_front_desk') ?>";
      
     
        $(".spinner-overlay").show();

        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "type=others",
          success : function (response) {
            $(".spinner-overlay").hide();
            var messages = response.messages;
            // console.log(messages)
            if(messages !== ""){  
              $("#registered-patients-card").hide();
              $("#perform-action-on-patient-modal").modal("hide"); 
              
              
              $("#select-tests-card .card-body").html(messages);
              $("#select-tests-card #select-tests-table").DataTable();
              $("#select-tests-card").show();
              $("#proceed-btn").show("fast");
              
            }

            // $('.table').DataTable();
          },
          error : function () {
            $(".spinner-overlay").hide();
            $(".sub-dept-tabs").show();
            $.notify({
            message:"Sorry something went wrong"
            },{
              type : "danger"  
            });
          } 
        }); 
      }, function(dismiss){
        if(dismiss == 'cancel'){
          var get_labs_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_labs_front_desk') ?>";
      
     
          $(".spinner-overlay").show();

          $.ajax({
            url : get_labs_url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "show_records=true",
            success : function (response) {
              $(".spinner-overlay").hide();
              var messages = response.messages;
              // console.log(messages)
              if(messages !== ""){  
                $("#registered-patients-card").hide();
                $("#perform-action-on-patient-modal").modal("hide"); 
                
                $("#health-facilities-card .card-body").html(messages);
                $("#health-facilities-card #health-facilities-table").DataTable();
                $("#health-facilities-card").show();
              }

              // $('.table').DataTable();
            },
            error : function () {
              $(".spinner-overlay").hide();
              $(".sub-dept-tabs").show();
              $.notify({
              message:"Sorry something went wrong"
              },{
                type : "danger"  
              });
            } 
          });  
        }
      });
    }
  }

  function goBackFromHealthFacilitiesCard(elem,evt){
    $("#registered-patients-card").show();
    $("#perform-action-on-patient-modal").modal("show"); 
    $("#health-facilities-card").hide();
  }

  function goBackFromSelectTestsCard(elem,evt){
    if(tests_selected_obj.length == 0){
      $("#registered-patients-card").show();
      $("#perform-action-on-patient-modal").modal("show"); 
      $("#select-tests-card").hide();
      $("#proceed-btn").hide("fast");
    }else{
      swal({
        title: 'Confirm Action',
        text: "Are You Sure You Want To Go Back?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Go Back'
      }).then((result) => {
        tests_selected_obj = [];
        $("#registered-patients-card").show();
        $("#perform-action-on-patient-modal").modal("show"); 
        $("#select-tests-card").hide();
        $("#proceed-btn").hide("fast");
      });  
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
    
    
    var sub_dept_id = elem.getAttribute("data-sub-dept-id");
    var health_facility_slug = elem.getAttribute("data-facility-slug");
    var sub_dept_slug = elem.getAttribute("data-sub-dept-slug");
    var dept_slug = elem.getAttribute("data-dept_slug");
  
    var value = {
      "dept_slug" : dept_slug,
      "sub_dept_slug" : sub_dept_slug,
      "facility_slug" : health_facility_slug,
      "status" : "registered_patient",
      "main_test_id" : main_test_id,
      "test_id": test_id,
      "test_name":test_name,
      "test_cost" : test_cost,
      "ta_time" : ta_time,
      "sub_dept_id" : sub_dept_id
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

    console.log(tests_selected_obj)
  } 

  function patientCheckBoxEvt() {
    var total = tests_selected_obj.length;
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
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

  function proceed (elem,evt) {

    patientCheckBoxEvt();
    
    var total = tests_selected_obj.length;
    console.log({data : tests_selected_obj});
    console.log(JSON.stringify({data : tests_selected_obj}))
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    var sub_dept_id = 0;
    
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
        $("#select-tests-card").hide("fast");
        $("#additional-information-on-tests-selected-card").show("fast");
        $("#proceed-btn").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn").show("fast");
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

  function viewTestSubTests (elem,e,url) {
    
    $(".spinner-overlay").show();
    
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
          $("#select-tests-card").hide("fast");
          $("#sub-tests-card").show("fast");
          $("#proceed-btn").hide("fast");
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function goBackFromSelectSubTestsCard(elem,evt){
    $("#select-tests-card").show("fast");
    $("#sub-tests-card").hide("fast"); 
    $("#proceed-btn").show("fast");
  }

  function goBackFromAdditionalInformationOnTestsSelectedCard (elem,evt) {
    $("#select-tests-card").show("fast");
    $("#additional-information-on-tests-selected-card").hide("fast");
    $("#proceed-from-additional-info-tests-selected-btn").hide("fast");
    $("#proceed-btn").show("fast");
  }

  function proceedFromAdditionalTestsSelected(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_tests_selected_front_desk') ?>";
    $(".spinner-overlay").show(); 

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_facility_id : patient_facility_id
    }
    console.log(obj)
    
    $.ajax({
        url : submit_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : obj,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response);
          if(response.success && response.initiation_code != ""){
            var initiation_code = response.initiation_code;
            swal({
              type: 'success',
              title: 'Successful',
              allowOutsideClick : false,
              allowEscapeKey :false,
              text: 'The Tests Have Been Added Successfully. Initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + initiation_code+ '\')">' + initiation_code +'</b>. Click Initiation Code To Copy. Initiation Code Has Been Sent To The Associated User With Tests Selected And Initiation Code To Proceed To  Payment.'
              // footer: '<a href>Why do I have this issue?</a>'
            }).then((result) => {
              reloadPage();
            }); 
          }else if(response.patient_not_registered){
            swal({
              type: 'error',
              title: 'Error',
              text: 'This Patient Is Not Currently Registered With This Facility. Please Go Back And Select Another Patient'
              
            })
          }else{
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
  }

  function insuranceModalClose(elem,evt){
    swal({
      title: 'Success',
      text: "You Have Successfully Registered This Patient  In Your Facility",
      type: 'success'             
    })
  }

  function selectThisFacilityForReferral(elem,evt){
    elem = $(elem);
    referring_facility_id = elem.attr("data-referring-facility-id");
    referring_facility_name = elem.attr("data-referring-facility-name");
    referring_facility_lab_to_lab_discount = elem.attr("data-referring-facility-lab-to-lab-discount");

    if(referring_facility_id != "" && referring_facility_name != "" && referring_facility_lab_to_lab_discount != ""){
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_tests_for_referral_front_desk') ?>";
       
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others&referring_facility_id="+referring_facility_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          // console.log(messages)
          if(messages !== ""){  
            $("#health-facilities-card").hide();
            
            $("#select-tests-referral-card .card-title").html("Select Tests From " +referring_facility_name);
            $("#select-tests-referral-card .card-body").html(messages);
            $("#select-tests-referral-card #select-tests-table").DataTable();
            $("#select-tests-referral-card").show();
            $("#proceed-btn-referral").show("fast");
            
          }

          // $('.table').DataTable();
        },
        error : function () {
          $(".spinner-overlay").hide();
          $(".sub-dept-tabs").show();
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "danger"  
          });
        } 
      });
    }  
  }

  function goBackFromSelectTestsReferralCard (elem,evt) {
   
    
    if(tests_selected_obj.length == 0){
      $("#select-tests-referral-card").hide();
      $("#proceed-btn-referral").hide("fast");
    }else{
      swal({
        title: 'Confirm Action',
        text: "Are You Sure You Want To Go Back?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Go Back'
      }).then((result) => {
        tests_selected_obj = [];
        $("#select-tests-referral-card").hide();
        $("#proceed-btn-referral").hide("fast");
      });  
    }
  }

  function proceedReferral (elem,evt) {

    patientCheckBoxEvt();
    
    var total = tests_selected_obj.length;
    console.log({data : tests_selected_obj});
    console.log(JSON.stringify({data : tests_selected_obj}))
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    var sub_dept_id = 0;
    
    for(var i = 0; i < tests_selected_obj.length; i++){
      var test_cost = tests_selected_obj[i]['test_cost'];
      sum += parseInt(test_cost);
    }

    if(total > 0){

      var sub_total =  sum - ((referring_facility_lab_to_lab_discount / 100) * sum);

      var text_str = "<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>"
      text_str += "<p>Lab To Lab Discount: <em class='text-primary'>"+referring_facility_lab_to_lab_discount+"%</em></p>";
      text_str += "<p>Sub Total Amount: <em class='text-primary'>₦"+addCommas(sub_total)+"</em></p>";
      text_str += "<p><em class='text-primary'>Do You Want To Proceed?</em></p>";
      
      swal({
        title: 'Continue?',
        text: text_str,
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
      }).then((result) => {
        $("#select-tests-referral-card").hide("fast");
        $("#additional-information-on-tests-selected-referral-card").show("fast");
        $("#proceed-btn-referral").hide("fast");
        $("#proceed-from-additional-info-tests-selected-referral-btn").show("fast");
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

  function proceedFromAdditionalTestsSelectedReferral(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-referral-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_tests_selected_front_desk_referral') ?>";
    $(".spinner-overlay").show(); 

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_facility_id : patient_facility_id,
      referring_facility_id : referring_facility_id
    }
    console.log(obj)
    
    $.ajax({
        url : submit_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : obj,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response);
          if(response.success && response.initiation_code != ""){
            var initiation_code = response.initiation_code;
            swal({
              type: 'success',
              title: 'Successful',
              allowOutsideClick : false,
              allowEscapeKey :false,
              text: 'The Tests Have Been Added Successfully. Initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + initiation_code+ '\')">' + initiation_code +'</b>. Click Initiation Code To Copy. Initiation Code Has Been Sent To The Associated User With Tests Selected And Initiation Code To Proceed To  Payment.'
              // footer: '<a href>Why do I have this issue?</a>'
            }).then((result) => {
              reloadPage();
            }); 
          }else if(response.patient_not_registered){
            swal({
              type: 'error',
              title: 'Error',
              text: 'This Patient Is Not Currently Registered With This Facility. Please Go Back And Select Another Patient'
              
            })
          }else{
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
  }

  function trackPatient (elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Search Patients By: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#4caf50',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Initiation Code',
      cancelButtonText : "Lab Id"
    }).then(function(){
      $("#enter-initiation-code-to-track-patient-modal").modal("show");
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $("#enter-lab-id-to-track-patient-modal").modal("show");
      }
    });  
  }

  function goBackFromTrackPatientByInitiationCodeCard (elem,evt) {
    $("#enter-initiation-code-to-track-patient-modal").modal("show");
    $("#main-card").show();
    
    $("#track-patient-by-initiation-code-card").hide();
  }

  function viewRadiologyResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_radiology_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;

            $("#track-patient-by-initiation-code-card").hide();
            $("#view-patient-results-card .card-body").html(messages)
            $("#view-patient-results-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsCard (elem,evt) {
    $("#track-patient-by-initiation-code-card").show();
   
    $("#view-patient-results-card").hide();
  }

  function viewTestResultImages (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_images_for_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            

            $("#track-patient-by-initiation-code-card").hide();
            $("#view-patient-results-images-card .card-body").html(messages)
            $("#view-patient-results-images-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsImagesCard (elem,evt) {
    $("#track-patient-by-initiation-code-card").show();
   
    $("#view-patient-results-images-card").hide();
  }


  function viewMiniTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_mini_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            

            $("#track-patient-by-initiation-code-card").hide();
            $("#view-patient-results-card .card-body").html(messages)
            $("#view-patient-results-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
           $("#view-patient-results-card #test-result-table").DataTable();
            $("#view-patient-results-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewStandardTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_standard_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            

            $("#track-patient-by-initiation-code-card").hide();
            $("#view-patient-results-card .card-body").html(messages)
            $("#view-patient-results-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
           $("#view-patient-results-card #test-result-table").DataTable();
            $("#view-patient-results-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewTestsSubTestsResults (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_test_sub_tests_tracking') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            

            $("#track-patient-by-initiation-code-card").hide();
            $("#view-sub-tests-results-card .card-body").html(messages)
            $("#view-sub-tests-results-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsCard (elem,evt) {
    $("#track-patient-by-initiation-code-card").show();
   
    $("#view-sub-tests-results-card").hide();
            
  }

  function viewSubTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_sub_test_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id +"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;

            $("#view-sub-tests-results-card").hide();
            $("#view-sub-tests-results-and-images-card .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsAndImagesCard (elem,evt) {
    $("#view-sub-tests-results-card").show();
            
    $("#view-sub-tests-results-and-images-card").hide();
  }

  function viewSubTestResultImages (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_sub_test_images_for_tracking_front_desk') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            

            $("#view-sub-tests-results-card").hide();
            $("#view-patient-results-sub-test-images-card .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-sub-test-images-card").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsSubTestImagesCard (elem,evt) {
    $("#view-sub-tests-results-card").show();
    $("#view-patient-results-sub-test-images-card").hide();
  }

  function referralDoctorsAssessment (elem,evt) {
     var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_referral_doctors_assessment') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "show_records=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#main-card").hide();
            $("#referral-doctors-assessment-card .card-body").html(messages)
            $("#referral-doctors-assessment-card #referral-doctors-assessment-table").DataTable();
            $("#referral-doctors-assessment-card").show();
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
  }

  function goBackFromReferralDoctorsAssessmentCard (elem,evt) {
    $("#main-card").show();
            
    $("#referral-doctors-assessment-card").hide();
  }

  function editPatientInfo(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/load_edit_patient_info_form') ?>";
    
   
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "patient_facility_id="+patient_facility_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          // console.log(messages)
          if(messages !== ""){  
            $("#registered-patients-card").hide();
            $("#perform-action-on-patient-modal").modal("hide"); 
            $("#edit-patient-info-card .card-body").html(messages);
            $("#edit-patient-info-card .my-select").selectpicker()
            
            $("#edit-patient-info-card").show();
            
            
          }

          // $('.table').DataTable();
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

  function goBackFromEditPatientInfoCard (elem,evt) {
    $("#registered-patients-card").show();
    $("#perform-action-on-patient-modal").modal("show"); 
    
    
    $("#edit-patient-info-card").hide();
  }

  function userTypeChanged (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    if(val == "fp"){
      $("#edit-patient-info-card #code-form-group").hide();
    }else if(val == "pfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }else if(val == "nfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }
  }

  function submitEditPatientInfoForm(elem,evt){
    elem = $(elem);
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_edit_patint_info_form') ?>";

    var form_data = elem.serializeArray();
    form_data = form_data.concat({
      "name" : "patient_facility_id",
      "value" : patient_facility_id
    })

    console.log(form_data)

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success){  
          $.notify({
          message:"Patient Type Edited Successfully"
          },{
            type : "success"  
          });
          setTimeout(function () {
            document.location.reload();
          }, 1000);
        }else if(response.invalid_code){
          $.notify({
          message:"Invalid Code Entered"
          },{
            type : "warning"  
          }); 
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

        // $('.table').DataTable();
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

  function trackPatientOnReferral (elem,evt) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_facilities_referred_to_lab') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success && response.messages != ""){  
          var messages = response.messages;
          $("#view-facilities-referred-to-card .card-body").html(messages);
          $("#view-facilities-referred-to-card #view-facilities-referred-to-table").DataTable();
          $("#main-card").hide();
          $("#view-facilities-referred-to-card").show();
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

        // $('.table').DataTable();
      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong. Check Your Internet Connection"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function goBackFromViewFacilitiesReferredToCard (elem,evt) {
    $("#main-card").show();
    $("#view-facilities-referred-to-card").hide();
  }

  function loadAllPatientsReferredToFacility(elem,evt,referring_facility_id){
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/load_lab_referrals_initiations_to_facility') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&referring_facility_id="+referring_facility_id,
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success && response.messages != "" && response.facility_name != ""){  
          var messages = response.messages;
          var facility_name = response.facility_name;
          $("#referral-facility-initiations-card .card-body").html(messages);
          $("#referral-facility-initiations-card .card-title").html("All Referrals To " + facility_name);
          $("#referral-facility-initiations-card #referral-facility-initiations-table").DataTable();
          $("#view-facilities-referred-to-card").hide();
          $("#referral-facility-initiations-card").show();
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

        // $('.table').DataTable();
      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong. Check Your Internet Connection"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function goBackFromReferralFacilityInitiationsCard (elem,evt) {
    $("#view-facilities-referred-to-card").show();
    $("#referral-facility-initiations-card").hide();
  }

  function trackReferralInitiation(elem,evt,referring_facility_id) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/track_referral_initiation') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&referring_facility_id="+referring_facility_id+"&initiation_code="+initiation_code,
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success && response.messages != "" && response.facility_name != ""){  
          var messages = response.messages;
          var facility_name = response.facility_name;
          var patient_name = response.patient_name;



          $("#track-referral-initiation-card .card-body").html(messages);
          $("#track-referral-initiation-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary' style='text-transform:lowercase;'>" +initiation_code + "</em>");
          $("#track-referral-initiation-card #track-referral-initiation-table").DataTable();
          $("#referral-facility-initiations-card").hide();
          $("#track-referral-initiation-card").show();
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }
      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong. Check Your Internet Connection"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromTrackReferralInitiationCard (elem,evt) {
    $("#referral-facility-initiations-card").show();
    $("#track-referral-initiation-card").hide();
  }

  function viewTestsSubTestsResultsReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_test_sub_tests_tracking_referral') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#track-referral-initiation-card").hide();
            $("#view-sub-tests-results-card-referrals .card-body").html(messages)
            $("#view-sub-tests-results-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-referrals #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsCardReferrals (elem,evt) {
    $("#track-referral-initiation-card").show();
    $("#view-sub-tests-results-card-referrals").hide();
  }

  function viewSubTestResultReferral(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_sub_test_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id +"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#view-sub-tests-results-card-referrals").hide();
            $("#view-sub-tests-results-and-images-card-referrals .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-referrals #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsAndImagesCardReferrals (elem,evt) {
    $("#view-sub-tests-results-card-referrals").show();
    $("#view-sub-tests-results-and-images-card-referrals").hide();
  }

  function viewSubTestResultImagesReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_sub_test_images_for_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#view-sub-tests-results-card-referrals").hide();
            $("#view-patient-results-sub-test-images-card-referrals .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-sub-test-images-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsSubTestImagesCardReferrals (elem,evt) {
    $("#view-sub-tests-results-card-referrals").show();
    $("#view-patient-results-sub-test-images-card-referrals").hide();
  }

  function viewMiniTestResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_mini_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;

            var facility_name = response.health_facility_name;
            

            $("#track-referral-initiation-card").hide();
            $("#view-patient-results-card-referrals .card-body").html(messages)
            $("#view-patient-results-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referrals #test-result-table").DataTable();
            $("#view-patient-results-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsCardReferrals (elem,evt) {
    $("#track-referral-initiation-card").show();
    $("#view-patient-results-card-referrals").hide();
  }

  function viewTestResultImagesReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_images_for_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#track-referral-initiation-card").hide();
            $("#view-patient-results-images-card-referrals .card-body").html(messages)
            $("#view-patient-results-images-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsImagesCardReferrals (elem,evt) {
    $("#track-referral-initiation-card").show();
    $("#view-patient-results-images-card-referrals").hide();
  }

  function viewStandardTestResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_standard_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#track-referral-initiation-card").hide();
            $("#view-patient-results-card-referrals .card-body").html(messages)
            $("#view-patient-results-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referrals #test-result-table").DataTable();
            $("#view-patient-results-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewRadiologyResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_result_for_radiology_tracking_front_desk_referrals') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#track-referral-initiation-card").hide();
            $("#view-patient-results-card-referrals .card-body").html(messages)
            $("#view-patient-results-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-referrals #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-referrals").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
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

              <div class="card" id="track-referral-initiation-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Referrals To </h3>
                  <button onclick="goBackFromTrackReferralInitiationCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="referral-facility-initiations-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Referrals To </h3>
                  <button onclick="goBackFromReferralFacilityInitiationsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-facilities-referred-to-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">All Facilities Referred To</h3>
                  <button onclick="goBackFromViewFacilitiesReferredToCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="edit-patient-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Change Patient Type</h3>
                  <button onclick="goBackFromEditPatientInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="referral-doctors-assessment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Referral Doctors Assessment</h3>
                  <button onclick="goBackFromReferralDoctorsAssessmentCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

               <div class="card" id="view-sub-tests-results-and-images-card-referrals" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-referrals" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-referrals" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-referrals" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>
    
              <div class="card" id="view-patient-results-images-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-referrals" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="additional-information-on-tests-selected-referral-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Enter Additional Patient Information</h3>
                  <button onclick="goBackFromAdditionalInformationOnTestsSelectedReferralCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                  <p><em class="text-primary">Note: No Field Is Required. Click The Proceed Button To Continue.</em></p>
                </div>
                <div class="card-body">
                  <?php
                    $attr = array('id' => 'additional-information-on-tests-selected-referral-form');
                    echo form_open('',$attr);
                  ?>
                  
                  
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="height">Height (metres): </label>
                        <input type="number" max="3" class="form-control" step="any" name="height" id="height" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="weight">Weight (kg): </label>
                        <input type="number" class="form-control" step="any" name="weight" id="weight" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label">  Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_yes" name="fasting" value="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_no" name="fasting" value="0" checked> No
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
                        <input type="date" class="form-control" name="lmp" id="lmp">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"> Sample: </p>
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

                      <div class="form-group col-sm-6">
                        <label for="clinical_summary" class="label-control"> Clinical Summary/Diagnosis: </label>
                        <textarea name="clinical_summary" id="clinical_summary" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  </form>
                </div>
              </div>


              <div class="card" id="health-facilities-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">All Registered Laboratories.</h3>
                  <button onclick="goBackFromHealthFacilitiesCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  <p><em class="text-primary">Click Facility To Select For Referral.</em></p>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="additional-information-on-tests-selected-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Enter Additional Patient Information</h3>
                  <button onclick="goBackFromAdditionalInformationOnTestsSelectedCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                  <p><em class="text-primary">Note: No Field Is Required. Click The Proceed Button To Continue.</em></p>
                </div>
                <div class="card-body">
                  <?php
                    $attr = array('id' => 'additional-information-on-tests-selected-form');
                    echo form_open('',$attr);
                  ?>
                  
                  
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="height">Height (metres): </label>
                        <input type="number" max="3" class="form-control" step="any" name="height" id="height" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="weight">Weight (kg): </label>
                        <input type="number" class="form-control" step="any" name="weight" id="weight" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label">  Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_yes" name="fasting" value="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_no" name="fasting" value="0" checked> No
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
                        <input type="date" class="form-control" name="lmp" id="lmp">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"> Sample: </p>
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

                      <div class="form-group col-sm-6">
                        <label for="clinical_summary" class="label-control"> Clinical Summary/Diagnosis: </label>
                        <textarea name="clinical_summary" id="clinical_summary" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="wrap" style="margin-top: 30px;">
                    <h4 class="text-center">Referring Dr. Info</h4>
                    <div class="form-row">

                      <div class="form-group col-sm-6">
                        <label for="referring_doctor">Name: </label>
                        <input type="text"  class="form-control" name="referring_doctor" id="referring_doctor" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="referring_doctor_phone">Phone Number: </label>
                        <input type="number" class="form-control" name="referring_doctor_phone" id="referring_doctor_phone" placeholder="e.g 08127027321">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="referring_doctor_email">Email: </label>
                        <input type="email" class="form-control" name="referring_doctor_email" id="referring_doctor_email">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  </form>
                </div>
              </div>

              <div class="card" id="select-tests-referral-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Select Tests From</h3>
                  <button onclick="goBackFromSelectTestsReferralCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Select Tests</h3>
                  <button onclick="goBackFromSelectTestsCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="registered-patients-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Registered Patients</h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackRegisteredPatientsCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>


              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title">Choose Action: </h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
      
                    <table class="table table-hover" id="choose-action-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="registerNewPatient(this,event)" class="text-primary">Register New Patient</td>
                        </tr>

                        <tr>
                          <td>2</td>
                          <td onclick="viewRegisteredPatients(this,event)" class="text-primary">View All Registered Patients</td>
                        </tr>
                        <!-- <tr>
                          <td>3</td>
                          <td onclick="viewPatientsProgressReports(this,event)" class="text-primary">View Patient's Progress Report</td>
                        </tr> -->
                        <tr>
                          <td>3</td>
                          <td onclick="trackPatient(this,event)" class="text-primary">Track Patient</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td onclick="trackPatientOnReferral(this,event)" class="text-primary">Track Patient On Referral</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td onclick="referralDoctorsAssessment(this,event)" class="text-primary">Referral Doctors Assessment</td>
                        </tr>
                        
                        
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="card" id="register-new-patient-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromRegisterNewPatientCard(this,event)">Go Back</button>
                  <h3 class="card-title">Register New Patient</h3>
                </div>
                <div class="card-body">
                  <?php
                    $attr = array('id' => 'register-new-patient-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/register_new_patient',$attr);
                  ?>
                  
                  <h4 class="form-sub-heading">Sign-in Information</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="title"><span class="form-error1">*</span> Username: </label>
                        <input type="text" class="form-control" name="user_name" id="user_name" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="password"><span class="form-error1">*</span> Password: </label>
                        <input type="text" class="form-control" name="password" id="password" >
                        <span class="form-error"></span>
                      </div>
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-4">
                        <label for="title"><span class="form-error1">*</span> Title: </label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="e.g mr,miss,master,chief" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="first_name"><span class="form-error1">*</span> First Name: </label>
                        <input type="text" class="form-control" name="first_name" id="first_name" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-4">
                        <label for="last_name"><span class="form-error1">*</span> Last Name: </label>
                        <input type="text" class="form-control" name="last_name" id="last_name" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="dob"><span class="form-error1">*</span> Date Of Birth: </label>
                        <input type="text" class="form-control" name="dob" id="dob" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p>Age: </p>
                        <em class="text-primary" id="age-display"></em>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>  Gender: </p>
                        <div id="sex">

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="male" id="male" checked> Male
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="race">Race / Tribe: </label>
                        <input type="text" class="form-control" name="race" id="race">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-5">
                        <label for="phone_code"><span class="form-error1">*</span> Phone Code:  </label>
                        <select name="phone_code" id="phone_code" class="col-12" >
                          <?php  
                          $country_codes = $this->onehealth_model->getAllCountryCodes();
                          if(is_array($country_codes)){
                            foreach($country_codes as $row){
                              $phone_code = $row->phonecode;
                              $nicename = $row->nicename;
                          ?>
                            <option value="<?php echo $phone_code; ?>" <?php if($phone_code == "234"){ echo "selected"; } ?>><?php echo $nicename . "   ( + " . $phone_code . ")"; ?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div class="form-group col-7">
                        <label for="phone_number" class=""><span class="form-error1">*</span> Mobile Number: </label>
                        <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="e.g 08127027321" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"> Email: </label>
                        <input name="email" id="email" type="text" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="">Type Of Funding(H.M.O)</label>
                        <div id="user_type" class="col-sm-12">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="user_type" value="fp" id="fp" onchange="userTypeChangedEnter(this,event)" checked> Full Payment
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="user_type" value="pfp" id="pfp" onchange="userTypeChangedEnter(this,event)"> Part Fee Payment
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="user_type" value="nfp" id="nfp" onchange="userTypeChangedEnter(this,event)"> None Fee Payment
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group col-sm-12" id="code_div" style="display: none;">
                          
                          <input type="text" class="form-control" placeholder="Enter Code" name="code" id="code_input" name="code_input" onkeyup="userTypeCodeKeyUp(this,event)" /><img src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>"  class="spinner">
                          <span id="code_status"></span>
                        </div>
                      </div>

                      <div class="form-group col-sm-12">
                        <label for="address" class="label-control"><span class="form-error1">*</span>  Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control" ></textarea>
                        <span class="form-error"></span>
                      </div>
  
                    </div>
                  </div>
                  <h4 class="form-sub-heading">Next Of Kin Information</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="name_of_next_of_kin">Full Name: </label>
                        <input type="text" class="form-control" name="name_of_next_of_kin" id="name_of_next_of_kin" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="mobile_no_of_next_of_kin">Mobile No.: </label>
                        <input type="number" class="form-control" name="mobile_no_of_next_of_kin" id="mobile_no_of_next_of_kin" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="username_of_next_of_kin">User Name: </label>
                        <input type="text" class="form-control" name="username_of_next_of_kin" id="username_of_next_of_kin" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="relationship_of_next_of_kin">Relationship: </label>
                        <input type="text" class="form-control" name="relationship_of_next_of_kin" id="relationship_of_next_of_kin">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="address_of_next_of_kin">Address:</label>
                        <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" class="form-control" cols="30" rows="10"></textarea>
                        <span class="form-error"></span>
                      </div>
                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>
              </div>

              <div class="card" id="sub-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackFromSelectSubTestsCard(this,event)">Go Back</button>
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

               <div class="card" id="track-patient-by-initiation-code-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromTrackPatientByInitiationCodeCard(this,event)">Go Back</button>
                  <h3 class="card-title">Patient Info By Initiation Code</h3>
                </div>
                <div class="card-body">
              
                </div> 
              </div> 
            </div>
          </div>
          
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="enter-lab-id-to-track-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Enter Patient's Lab Id </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'enter-lab-id-to-track-patient-form');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="lab_id">Lab Id: </label>
                  <input type="text" class="form-control" name="lab_id" id="lab_id">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="enter-initiation-code-to-track-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Enter Patient Initiation Code </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'enter-initiation-code-to-track-patient-form');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="initiation_code">Initiation Code: </label>
                  <input type="text" class="form-control" name="initiation_code" id="initiation_code">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="enter-username-for-registration-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Enter Patients UserName You Wish To Register </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'enter-username-for-registration-form');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="user_name">Username: </label>
                  <input type="text" class="form-control" name="user_name" id="user_name">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

      <div class="modal fade" data-backdrop="static" id="enter-insurance-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title">Enter Insurance Code Details</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="insuranceModalClose(this,event)">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body" style="">
              <?php
              $attr = array('id' => 'enter-insurance-code-form');
              echo form_open("",$attr);
              ?>
                <div class="form-group">
                  <label for="user_type">Insurance Type: </label>
                  <select name="user_type" id="user_type" class="form-control selectpicker" data-style="btn btn-link">
                    <option value="pfp">Part Fee Paying</option>
                    <option value="nfp">None Fee Paying</option>
                  </select>
                  <span class="form-error"></span>
                </div>

                <div class="form-group">
                  <label for="code">Insurance Code: </label>
                  <input type="text" class="form-control" name="code" id="code">
                  <span class="form-error"></span>
                </div>

                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="insuranceModalClose(this,event)">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div id="proceed-from-additional-info-tests-selected-referral-btn" onclick="proceedFromAdditionalTestsSelectedReferral(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>

      <div id="proceed-btn-referral" onclick="proceedReferral(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
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

      $("#enter-lab-id-to-track-patient-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_id = me.find("#lab_id").val();
        if(lab_id != ""){
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/track_patient_by_lab_id') ?>";

          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            dataType : "json",
            responseType : "json",
            data : "lab_id="+lab_id,
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.success && response.messages != "" && response.patient_name != "" && response.initiation_code != ""){
                var messages = response.messages;
                var patient_name = response.patient_name;
                var initiation_code = response.initiation_code;
                $("#enter-lab-id-to-track-patient-modal").modal("hide");
                $("#main-card").hide();
                $("#track-patient-by-initiation-code-card .card-body").html(messages)
                $("#track-patient-by-initiation-code-card #track-patient-by-initiation-code-table").DataTable();
                $("#track-patient-by-initiation-code-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Lab Id: <em class='text-primary'>" +lab_id + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
                $("button[data-toggle='tooltip']").tooltip();
                $("#track-patient-by-initiation-code-card").show();
                
              }else if(response.invalid_initiation_code){
                swal({
                  title: 'Error',
                  text: "This Lab Id Is Invalid",
                  type: 'error'             
                })
              }else{
                swal({
                  title: 'Error',
                  text: "Something Went Wrong.",
                  type: 'error'             
                })
              }
            },error : function () {
              $(".spinner-overlay").hide();
               swal({
                title: 'Error',
                text: "Something Went Wrong. Please Check Your Internet Connection",
                type: 'error'             
              })
            }
          });
        }else{
          swal({
            title: 'Error',
            text: "Lab Id Must Be Entered To Proceed",
            type: 'error'             
          })
        }
      })

      $("#enter-initiation-code-to-track-patient-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var initiation_code = me.find("#initiation_code").val();
        if(initiation_code != ""){
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/track_patient_by_initiation_code') ?>";

          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            dataType : "json",
            responseType : "json",
            data : "initiation_code="+initiation_code,
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.success && response.messages != "" && response.patient_name){
                var messages = response.messages;
                var patient_name = response.patient_name;
                $("#enter-initiation-code-to-track-patient-modal").modal("hide");
                $("#main-card").hide();
                $("#track-patient-by-initiation-code-card .card-body").html(messages)
                $("#track-patient-by-initiation-code-card #track-patient-by-initiation-code-table").DataTable();
                $("#track-patient-by-initiation-code-card .card-title").html("Patient Name: <em class='text-primary'>" +patient_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
                $("button[data-toggle='tooltip']").tooltip();
                $("#track-patient-by-initiation-code-card").show();
                
              }else if(response.invalid_initiation_code){
                swal({
                  title: 'Error',
                  text: "This Initiation Code Is Invalid",
                  type: 'error'             
                })
              }else{
                swal({
                  title: 'Error',
                  text: "Something Went Wrong.",
                  type: 'error'             
                })
              }
            },error : function () {
              $(".spinner-overlay").hide();
               swal({
                title: 'Error',
                text: "Something Went Wrong. Please Check Your Internet Connection",
                type: 'error'             
              })
            }
          });
        }else{
          swal({
            title: 'Error',
            text: "Initiation Code Must Be Entered To Proceed",
            type: 'error'             
          })
        }
      })

      $("#enter-insurance-code-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var form_data = me.serializeArray();

        var user_name = me.attr("data-user-name");
        form_data = form_data.concat({
          "name" : "user_name",
          "value" : user_name
        })
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_insurance_code_patient_registration') ?>";

        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          dataType : "json",
          responseType : "json",
          data : form_data,
          success : function (response) {
            $(".spinner-overlay").hide();
            console.log(response)
            if(response.success){
              $("#enter-insurance-code-modal").modal("hide");
              swal({
                title: 'Success',
                text: "You Have Successfully Registered This Patient  In Your Facility",
                type: 'success'             
              })
            }else if(response.not_registered){
              $.notify({
              message:"This Patient Is Currently Not Registered With This Facility"
              },{
                type : "warning"  
              });
            }else if(response.messages != ""){
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
            }else if(response.insurance_details_invalid){
              swal({
                title: 'Error!',
                text: "The Insurance Details You Entered Are Invalid. Please Try Again",
                type: 'error'             
              })
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

      $("#enter-username-for-registration-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_user_name_for_registration') ?>";
        var user_name = me.find('#user_name').val();
        if(user_name != ""){
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "user_name="+user_name,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success){
                swal({
                  title: 'Choose Action',
                  text: "Do You Have An Insurance Code For This Patient?",
                  type: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#4caf50',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes',
                  cancelButtonText : "No"
                }).then(function(){
                  $(".spinner-overlay").show();
                  $("#enter-username-for-registration-modal").modal("hide");
                  setTimeout(function () {
                    $(".spinner-overlay").hide();
                    $("#enter-insurance-code-modal #enter-insurance-code-form").attr("data-user-name",user_name);
                    $("#enter-insurance-code-modal").modal("show");
                  }, 1500);
                 
                }, function(dismiss){
                  if(dismiss == 'cancel'){
                    swal({
                      title: 'Success',
                      text: "You Have Successfully Registered This Patient In Your Facility",
                      type: 'success'             
                    })
                  }
                });
              }else if(response.invalid_username){
                swal({
                  title: 'Error',
                  text: "The Username Entered Is Invalid. Please Enter A Valid One To Proceed",
                  type: 'error'
                })
              }else if(response.patient_already_registered){
                swal({
                  title: 'Error',
                  text: "This Patient Is Already Registered In This Facility",
                  type: 'error'
                })
              }else if(response.incomplete_patient_info){
                swal({
                  title: 'Error',
                  text: "This Patient Appears To Have Incomplete Bio Data. Please Inform Him/Her To Use The Edit Your Patient Information Tab On His Sidebar To Input His Patient Information",
                  type: 'error'
                })
              }else{
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
            },
            error: function (jqXHR,textStatus,errorThrown) {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              
            }
          });
        }else{
          swal({
            title: 'Error',
            text: "The Username Field Is Required",
            type: 'error'
          })
        }
      })

      $("#register-new-patient-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var values = me.serializeArray();
        console.log(values);
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
            if(response.success == true){
              var user_name = response.user_name;
              var password = response.password;
              var email = response.email;
              var title = response.title;
              var full_name = response.full_name;
              
              var mobile = response.mobile;
              var registration_num = response.registration_num;
              var text_val = "<h4 style='text-transform: capitalize;'>Patient Account Successfully Created And Added As A Patient in your facility with the following details</h4>";
              text_val += "<div class='row'>";
              text_val += "<h5 class='col-sm-6'>User Name: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+user_name+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Password: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+password+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Registration Number: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+registration_num+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Mobile Number: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+mobile+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Email Address: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+email+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Title: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+title+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Full Name: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+full_name+"</em></h6>";


              text_val += "</div>";
              swal({
                title: 'Successful',
                text: text_val,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
                
              }).then(function(){
                document.location.reload();
              });
            }else if(response.incorrect_date){
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Date Of Birth Must Be In The Past. Please Enter Valid Date And Try Again'
              })
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

      $("#register-new-patient-card #dob").on('dp.change', function(e) {
        var elem = $(this)
        var val = "";
        var type = $("#register-new-patient-card #register-new-patient-form").attr("data-type");
        
        var seconds = new Date().getTime() / 1000;
        
        var date_of_birth_seconds = elem.val();
        date_of_birth_seconds = new Date(date_of_birth_seconds).getTime() / 1000;
        var date_diff_seconds = Math.floor(seconds - date_of_birth_seconds);
        
        // console.log(date_diff_seconds);
        if(date_diff_seconds > 0){
          var date_diff_minutes = Math.floor(date_diff_seconds / 60);
          var date_diff_hours = Math.floor(date_diff_minutes / 60);
          var date_diff_days = Math.floor(date_diff_hours / 24);
          var date_diff_weeks = Math.floor(date_diff_days / 7);
          var date_diff_months = Math.floor(date_diff_weeks / 4.345);
          var date_diff_years = Math.floor(date_diff_months / 12);
          
          if(date_diff_minutes < 1){
            val = "";
          }else if(date_diff_hours < 1){
            val = date_diff_minutes + " minute(s)";
          }else if(date_diff_days < 1){
            val = date_diff_hours + " hour(s)";
          }else if(date_diff_weeks < 1){
            val = date_diff_days + " day(s)";
          }else if(date_diff_months < 1){
            val = date_diff_weeks + " week(s)";
          }else if(date_diff_years < 1){
            val = date_diff_months + " month(s)";
          }else{
            val = date_diff_years + " year(s)";
          }
        }else{
          $.notify({
          message:"Date Of Birth Must Be In The Past. Please Enter Valid Date"
          },{
            type : "danger"  
          });
        }
        
        $("#register-new-patient-card #register-new-patient-form #age-display").html(val);

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
