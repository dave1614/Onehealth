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
            $health_facility_country = $row->country;
            $health_facility_state = $row->state;
            $health_facility_address = $row->address;
            $health_facility_table_name = $row->table_name;
            $health_facility_date = $row->date;
            $health_facility_time = $row->time;
            $health_facility_slug = $row->slug;
            $color = $row->color;
            $clinic_structure = $row->clinic_structure;
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
    
      <?php
      $lab_sub_dept_id = "";

    ?>
<script> 
  String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return (this.length > n) ? this.substr(0, n-1) + '&hellip;' : this;
      };
  var tests_selected_obj = [];
  var user_info = [];
  var selected_drugs = [];
  var patient_hospital_number = "";
  var lab_base_url = "";
  var lab_dept_id = "";
  var lab_sub_dept_id = "";


  var referral_id = "";
  var consultation_id = "";
  var ward_record_id = "";
  var patient_name = "";
  var patient_user_id = "";

  var patient_facility_id = "";




  function viewRadiologyResultReferralsFirstConsultation(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#selected-tests-card-referral-first-consultation").hide();
            $("#view-patient-results-card-referral-first-consultation .card-body").html(messages)
            $("#view-patient-results-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-referral-first-consultation #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-referral-first-consultation").show();
            
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

  function viewStandardTestResultReferralsFirstConsultation(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral-first-consultation").hide();
            $("#view-patient-results-card-referral-first-consultation .card-body").html(messages);
            $("#view-patient-results-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral-first-consultation #test-result-table").DataTable();
            $("#view-patient-results-card-referral-first-consultation").show();
            
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

  function goBackFromViewPatientsResultsImagesCardReferralFirstConsultation (elem,evt) {
    $("#selected-tests-card-referral-first-consultation").show();
    $("#view-patient-results-images-card-referral-first-consultation").hide();
  }

  function viewTestResultImagesReferralsFirstConsultation (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral-first-consultation").hide();
            $("#view-patient-results-images-card-referral-first-consultation .card-body").html(messages)
            $("#view-patient-results-images-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-referral-first-consultation").show();
            
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

  function goBackFromViewPatientsResultsCardReferralFirstconsultation (elem,evt) {
    $("#selected-tests-card-referral-first-consultation").show();
    $("#view-patient-results-card-referral-first-consultation").hide();
  }

  function viewMiniTestResultReferralsFirstConsultation(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral-first-consultation").hide();
            $("#view-patient-results-card-referral-first-consultation .card-body").html(messages)
            $("#view-patient-results-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral-first-consultation #test-result-table").DataTable();
            $("#view-patient-results-card-referral-first-consultation").show();
            
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

  function goBackFromViewPatientsResultsSubTestImagesCardReferralFirstConsultation (elem,evt) {
    $("#view-sub-tests-results-card-referral-first-consultation").show();
    $("#view-patient-results-sub-test-images-card-referral-first-consultation").hide();
  }

  function viewSubTestResultImagesReferralFirstConsultation (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card-referral-first-consultation").hide();
            $("#view-patient-results-sub-test-images-card-referral-first-consultation .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-referral-first-consultation").show();
            
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

  function goBackFromViewSubTestsResultsAndImagesCardReferralFirstConsultation (elem,evt) {
    $("#view-sub-tests-results-card-referral-first-consultation").show();
    $("#view-sub-tests-results-and-images-card-referral-first-consultation").hide();
  }

  function viewSubTestResultReferralFirstConsultation(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card-referral-first-consultation").hide();
            $("#view-sub-tests-results-and-images-card-referral-first-consultation .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-referral-first-consultation #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-referral-first-consultation").show();
            
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

  function goBackFromSelectedTestsReferralFirstConsultation (elem,evt) {
    $("#first-clinic-consultation-referral-card").show();
    $("#selected-tests-card-referral-first-consultation").hide();
  }

  function goBackFromViewSubTestsResultsCardReferralFirstConsultation (elem,evt) {
    $("#selected-tests-card-referral-first-consultation").show();
    $("#view-sub-tests-results-card-referral-first-consultation").hide();
  }

  function viewTestsSubTestsResultsReferralFirstConsultation (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_tracking_referral_first_consultation') ?>";

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
            

            $("#selected-tests-card-referral-first-consultation").hide();
            $("#view-sub-tests-results-card-referral-first-consultation .card-body").html(messages)
            $("#view-sub-tests-results-card-referral-first-consultation .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-referral-first-consultation #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-referral-first-consultation").show();
            
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

  function loadPreviouslySelectedTestsReferralFirstConsultation (elem,evt,id) {
    elem = $(elem);
    consultation_id = id;
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_tests_by_consultation_id_referral_first_consultation'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#selected-tests-card-referral-first-consultation .card-body").html(messages);
          $("#selected-tests-card-referral-first-consultation #selected-tests-table").DataTable();
          $("#first-clinic-consultation-referral-card").hide();
          $("#selected-tests-card-referral-first-consultation").show();
        }else{
           $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function goBackFromSelectedDrugsReferralFirstConsultation (elem,evt) {
    $("#first-clinic-consultation-referral-card").show();
    $("#selected-drugs-card-referral-first-consultation").hide();
  }

  function loadPreviouslySelectedDrugsReferralFirstConsultation (elem,evt,id,health_facility_id) {
    elem = $(elem);
    consultation_id = id;
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id+"&health_facility_id="+health_facility_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#selected-drugs-card-referral-first-consultation .card-body").html(messages);
          $("#selected-drugs-card-referral-first-consultation .table").DataTable();
          $("#first-clinic-consultation-referral-card").hide();
          $("#selected-drugs-card-referral-first-consultation").show();
        }else{
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function viewRadiologyResultReferralsPrevious(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#view-previously-selected-referral-tests-card").hide();
            $("#view-patient-results-card-referral-previous .card-body").html(messages)
            $("#view-patient-results-card-referral-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-referral-previous #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-referral-previous").show();
            
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

  function viewStandardTestResultReferralsPrevious(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#view-previously-selected-referral-tests-card").hide();
            $("#view-patient-results-card-referral-previous .card-body").html(messages);
            $("#view-patient-results-card-referral-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral-previous #test-result-table").DataTable();
            $("#view-patient-results-card-referral-previous").show();
            
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

  function goBackFromViewPatientsResultsImagesCardReferralPrevious (elem,evt) {
    $("#view-previously-selected-referral-tests-card").show();
    $("#view-patient-results-images-card-referral-previous").hide();
  }

  function viewTestResultImagesReferralsPrevious (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-previously-selected-referral-tests-card").hide();
            $("#view-patient-results-images-card-referral-previous .card-body").html(messages)
            $("#view-patient-results-images-card-referral-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-referral-previous").show();
            
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

  function goBackFromPatientResultsCardReferralPrevious (elem,evt) {
    $("#view-previously-selected-referral-tests-card").show();
    $("#view-patient-results-card-referral-previous").hide();
  }

  function viewMiniTestResultReferralsPrevious(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#view-previously-selected-referral-tests-card").hide();
            $("#view-patient-results-card-referral-previous .card-body").html(messages)
            $("#view-patient-results-card-referral-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral-previous #test-result-table").DataTable();
            $("#view-patient-results-card-referral-previous").show();
            
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

  function goBackFromViewPatientsResultsSubTestImagesCardReferralsPrevious (elem,evt) {
    $("#view-sub-tests-results-card-referral-previous").show();
    $("#view-patient-results-sub-test-images-card-referrals-previous").hide();
  }

  function viewSubTestResultImagesReferralsPrevious (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card-referral-previous").hide();
            $("#view-patient-results-sub-test-images-card-referrals-previous .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-referrals-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-referrals-previous").show();
            
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

  function goBackFromViewSubTestsResultsAndImagesCardReferralsPrevious (elem,evt) {
    $("#view-sub-tests-results-card-referral-previous").show();
    $("#view-sub-tests-results-and-images-card-referrals-previous").hide();
  }

  

  function viewSubTestResultReferralsPrevious(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card-referral-previous").hide();
            $("#view-sub-tests-results-and-images-card-referrals-previous .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-referrals-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-referrals-previous #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-referrals-previous").show();
            
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

  function goBackFromViewSubTestsResultsCardReferralPrevious (elem,evt) {
    $("#view-previously-selected-referral-tests-card").show();
    $("#view-sub-tests-results-card-referral-previous").hide();
  }

  function viewTestsSubTestsResultsReferralsPrevious (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_previous_referral_tracking') ?>";

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
            

            $("#view-previously-selected-referral-tests-card").hide();
            $("#view-sub-tests-results-card-referral-previous .card-body").html(messages)
            $("#view-sub-tests-results-card-referral-previous .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-referral-previous #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-referral-previous").show();

            $("[data-toggle='tooltip'").tooltip();
            
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

  function goBackPreviouslySelectedReferralTestsCard (elem,evt) {
    $("#previous-consultations-referral-info-card").show();
    $("#view-previously-selected-referral-tests-card").hide();
  }

  function loadPreviouslySelectedTestsReferralPrevious (elem,evt,id) {
    elem = $(elem);
    referral_id = id;
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previously_selected_tests_for_previous_referral_clinic_doctor') ?>";

    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      dataType : "json",
      responseType : "json",
      data : "show_records=true&referral_id="+referral_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success && response.messages != ""){
          var messages = response.messages;
         
          $("#view-previously-selected-referral-tests-card .card-body").html(messages);
          $("#view-previously-selected-referral-tests-card .table").DataTable();
          $("#previous-consultations-referral-info-card").hide();
          $("#view-previously-selected-referral-tests-card").show();
          
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

  function loadPreviouslySelectedDrugsReferralPrevious (elem,evt,id) {
    elem = $(elem);
    referral_id = id;
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previously_selected_drugs_for_previous_referral_clinic_doctor') ?>";

    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      dataType : "json",
      responseType : "json",
      data : "show_records=true&referral_id="+referral_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success && response.messages != ""){
          var messages = response.messages;
         
          $("#view-previously-selected-referral-drugs-card .card-body").html(messages);
          $("#view-previously-selected-referral-drugs-card .table").DataTable();
          $("#previous-consultations-referral-info-card").hide();
          $("#view-previously-selected-referral-drugs-card").show();
          
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

  function goBackPreviouslySelectedReferralDrugsCard (elem,evt) {
    $("#previous-consultations-referral-info-card").show();
    $("#view-previously-selected-referral-drugs-card").hide();
  }

  function viewStandardTestResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral").hide();
            $("#view-patient-results-card-referral .card-body").html(messages);
            $("#view-patient-results-card-referral .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral #test-result-table").DataTable();
            $("#view-patient-results-card-referral").show();
            
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

  function goBackFromViewPatientsResultsImagesCardReferral (elem,evt) {
    $("#selected-tests-card-referral").show();
    $("#view-patient-results-images-card-referral").hide();
  }

  function viewMiniTestResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral").hide();
            $("#view-patient-results-card-referral .card-body").html(messages)
            $("#view-patient-results-card-referral .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-referral #test-result-table").DataTable();
            $("#view-patient-results-card-referral").show();
            
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

  function viewTestResultImagesReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card-referral").hide();
            $("#view-patient-results-images-card-referral .card-body").html(messages)
            $("#view-patient-results-images-card-referral .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-referral").show();
            
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

  function goBackFromViewPatientsResultsCardReferral (elem,evt) {
    $("#selected-tests-card-referral").show();
    $("#view-patient-results-card-referral").hide();
  }

  function viewRadiologyResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#selected-tests-card-referral").hide();
            $("#view-patient-results-card-referral .card-body").html(messages)
            $("#view-patient-results-card-referral .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-referral #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-referral").show();
            
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
    $("#view-sub-tests-results-card-referral").show();
    $("#view-patient-results-sub-test-images-card-referrals").hide();
  }

  function viewSubTestResultImagesReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card-referral").hide();
            $("#view-patient-results-sub-test-images-card-referrals .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
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


  function goBackFromViewSubTestsResultsAndImagesCardReferrals (elem,evt) {
    $("#view-sub-tests-results-card-referral").show();
    $("#view-sub-tests-results-and-images-card-referrals").hide();
  }

  function viewSubTestResultReferrals(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card-referral").hide();
            $("#view-sub-tests-results-and-images-card-referrals .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-referrals .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
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

  function goBackFromViewSubTestsResultsCardReferral (elem,evt) {
    $("#selected-tests-card-referral").show();
    $("#view-sub-tests-results-card-referral").hide();
  }

  function viewTestsSubTestsResultsReferrals (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_referral_tracking') ?>";

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
            

            $("#selected-tests-card-referral").hide();
            $("#view-sub-tests-results-card-referral .card-body").html(messages)
            $("#view-sub-tests-results-card-referral .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-referral #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-referral").show();
            
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

  function goBackFromSelectedTestsReferral (elem,evt) {
    $("#make-new-consultation-form-referral").show();
    $("#selected-tests-card-referral").hide();
  }

  function selectedTestsReferrals(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_referral_tests_by_referral_id'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&referral_id="+referral_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#selected-tests-card-referral .card-body").html(messages);
          $("#selected-tests-card-referral #selected-tests-table").DataTable();
          $("#make-new-consultation-form-referral").hide();
          $("#selected-tests-card-referral").show();
        }else{
           $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
    

  }

  function goBackSubTests1ReferralsAnother (elem,evt) {
    evt.preventDefault();
    $("#select-test-card-another-referrals").show();
    
    $("#sub-tests-card-referrals-another").hide();
  }


  function proceedFromAdditionalTestsSelectedAnotherReferrals(elem,evt){
    
    var i = 0;
    
    var height = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #height").val();
    var weight = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #weight").val();
    var fasting_yes = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #fasting #fasting_yes").prop("checked");
    var fasting_no = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #fasting #fasting_no").prop("checked");
    var present_medications = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #edit_present_medications").val();
    var lmp = $("#additional-information-on-tests-selected-card-another-referrals #additional-information-on-tests-selected-form #lmp").val();
    if(fasting_yes){
      var fasting = 1;
    }else{
      var fasting = 0;
    }
    var form_data = [
      {
        name : 'height',
        value : height
      },
      {
        name : 'weight',
        value : weight
      },
      {
        name : 'fasting',
        value : fasting
      },
      {
        name : 'present_medications',
        value : present_medications
      },
      {
        name : 'lmp',
        value : lmp
      }
    ];
    additional_patient_test_info = form_data;     
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_wards_dr_another_facility') ?>";
    var referring_facility_id = tests_selected_obj[0].facility_id;

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      
      referring_facility_id : referring_facility_id,
      referral_id : referral_id
    }

    console.log(obj)
    

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn-another-referrals").hide("fast");
              $("#additional-information-on-tests-selected-card-another-referrals").hide();
              $("#select-lab-to-use-card-referrals").show();
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

  function goBackFromAdditionalInformationOnTestsSelectedCardAnotherReferrals (elem,evt) {
    $("#select-test-card-another-referrals").show();
    $("#additional-information-on-tests-selected-card-another-referrals").hide("fast");
    $("#proceed-from-tests-selection-btn-another-referrals").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn-another-referrals").hide("fast");
  }

  function proceedFromTestsSelectionAnotherReferrals (elem,evt) {

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
        $("#select-test-card-another-referrals").hide();
        $("#additional-information-on-tests-selected-card-another-referrals").show("fast");
        $("#proceed-from-tests-selection-btn-another-referrals").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn-another-referrals").show("fast");
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

  function goBackFromSelectTestCardAnotherReferrals (elem,evt) {
    tests_selected_obj = [];
    $("#select-lab-card-referrals").show(); 
    $("#select-test-card-another-referrals").hide();
    $("#proceed-from-tests-selection-btn-another-referrals").hide("fast");
  }

  function viewTestSubTestsReferralsAnother (elem,e,url) {
    
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
          
          var messages = response.messages;
          var test_name = response.test_name;
          var card_header_str = "Sub Tests Of " + test_name;
          $("#sub-tests-card-referrals-another .card-title").html(card_header_str);
          $("#sub-tests-card-referrals-another .card-body").html(messages);

          $("#sub-tests-card-referrals-another #sub-tests-table").DataTable();
          $("#select-test-card-another-referrals").hide();
          
          $("#sub-tests-card-referrals-another").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function selectThisFacilityOthersReferrals(elem,evt){
     elem = $(elem);
     evt.preventDefault();
     console.log(consultation_id)
     
     var facility_id = elem.attr("data-id");
     var facility_name = elem.attr("data-facility-name");
     var facility_slug = elem.attr("data-slug");
      // var patient_user_name = document.getElementById("user_name").innerHTML;
     var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests_referrals_doctor";
      patient_name = $.trim(patient_name);
      
     
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others&another=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(messages !== ""){  
            $("#select-lab-card-referrals").hide(); 
            $("#select-test-card-another-referrals .card-body").html(messages);
            $("#select-test-card-another-referrals").show();
            $("#select-test-card-another-referrals .table").DataTable();
            $("#proceed-from-tests-selection-btn-another-referrals").show("fast");
          }
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

  function goBackSelectLabsReferrals (elem,evt) {
    $("#select-lab-to-use-card-referrals").show();
    $("#select-lab-card-referrals").hide();
  }

  function selectAnotherLabReferrals(elem,evt){
    evt.preventDefault();
    
    $(".spinner-overlay").show();
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_labs_and_health_facilities'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&referral=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-lab-card-referrals .card-body").html(response.messages);
          $("#select-lab-card-referrals #facilities-table").DataTable();
          LetterAvatar.transform();
          $("#select-lab-to-use-card-referrals").hide();
          $("#select-lab-card-referrals").show();
        }else{
          $.notify({
          message:"No Facility Currently Available Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackFromViewPatientsResultsCardWardDuringAdmisssion (elem,evt) {
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
    $("#view-patient-results-card-ward-during-admission").hide();
  }

  function goBackFromViewPatientsResultsImagesCardWardDuringAdmission (elem,evt) {
    $("#current-selected-tests").show();
    $("#view-patient-results-images-card-ward-during-admission").hide();
    $("#request-new-tests-btn").show("fast");
  }

  function viewRadiologyResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-ward-during-admission #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#request-new-tests-btn").hide("fast");
            
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

  function viewStandardTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages);
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward-during-admission #test-result-table").DataTable();
            $("#view-patient-results-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
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

  function viewTestResultImagesWardDuringAdmisssion (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-images-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
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

  function viewMiniTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward-during-admission #test-result-table").DataTable();
            $("#view-patient-results-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
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

  function goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission (elem,evt) {
    $("#view-sub-tests-results-card-ward-during-admission").show();
    $("#view-patient-results-sub-test-images-card-ward-during-admission").hide();
  }

  function viewSubTestResultImagesWardDuringAdmission (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card-ward-during-admission").hide();
            $("#view-patient-results-sub-test-images-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-ward-during-admission").show();
            
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

  function goBackFromViewSubTestsResultsAndImagesCardWardDuringAdmission (elem,evt) {
    $("#view-sub-tests-results-card-ward-during-admission").show();
    $("#view-sub-tests-results-and-images-card-ward-during-admission").hide();
  }

  function viewSubTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card-ward-during-admission").hide();
            $("#view-sub-tests-results-and-images-card-ward-during-admission .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-ward-during-admission #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-ward-during-admission").show();
            
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

  function goBackFromViewSubTestsResultsCardWardDuringAdmission (elem,evt) {
    $("#current-selected-tests").show();
    $("#view-sub-tests-results-card-ward-during-admission").hide();
    $("#request-new-tests-btn").show("fast");
  }

  function viewTestsSubTestsResultsWardDuringAdmission (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_ward_tracking') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&ward_record_id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#request-new-tests-btn").hide("fast");
            $("#current-selected-tests").hide();
            $("#view-sub-tests-results-card-ward-during-admission .card-body").html(messages)
            $("#view-sub-tests-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-ward-during-admission #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-ward-during-admission").show();
            
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

  function viewTestSubTestsWardAnother (elem,e,url) {
    
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
          $("#sub-tests-card-ward-another .card-title").html(card_header_str);
          $("#sub-tests-card-ward-another .card-body").html(messages);

          $("#sub-tests-table").DataTable();
          $("#select-test-card-another-ward").hide();
          
          $("#sub-tests-card-ward-another").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function goBackSubTests1WardAnother (elem,evt) {
    $("#select-test-card-another-ward").show();
          
    $("#sub-tests-card-ward-another").hide();
  }

  function proceedFromAdditionalTestsSelectedAnotherWard(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-card-another-ward #additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_ward_dr_another_facility') ?>";
    var referring_facility_id = tests_selected_obj[0].facility_id;

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      consultation_id : consultation_id,
      referring_facility_id : referring_facility_id,
      ward_record_id : ward_record_id
    }
    

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn-another-ward").hide("fast");
              $("#additional-information-on-tests-selected-card-another-ward").hide();
              currentTests(this,event);
              window.scrollTo(0,document.body.scrollHeight);
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

  function goBackFromAdditionalInformationOnTestsSelectedCardAnotherWard(elem,evt) {
    $("#select-test-card-another-ward").show();
    $("#additional-information-on-tests-selected-card-another-ward").hide("fast");
    $("#proceed-from-tests-selection-btn-another-ward").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn-another-ward").hide("fast");
  }

  function proceedFromTestsSelectionAnotherWard (elem,evt) {

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
        $("#select-test-card-another-ward").hide();
        $("#additional-information-on-tests-selected-card-another-ward").show("fast");
        $("#proceed-from-tests-selection-btn-another-ward").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn-another-ward").show("fast");
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

  function selectThisFacilityOthersWard(elem,evt){
     elem = $(elem);
     evt.preventDefault();
     console.log(consultation_id)
     
     var facility_id = elem.attr("data-id");
     var facility_name = elem.attr("data-facility-name");
     var facility_slug = elem.attr("data-slug");
      // var patient_user_name = document.getElementById("user_name").innerHTML;
     var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests";
      patient_name = $.trim(patient_name);
      
     
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others&consultation_id="+consultation_id+"&ward_another=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(messages !== ""){  
            $("#select-lab-card-ward").hide(); 
            $("#select-test-card-another-ward .card-body").html(messages);
            $("#select-test-card-another-ward").show();
            $("#select-test-card-another-ward .select-test-table").DataTable();
            $("#proceed-from-tests-selection-btn-another-ward").show("fast");
          }
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

  function goBackFromSelectTestCardAnotherWard (elem,evt) {
    tests_selected_obj = [];
    $("#select-lab-card-ward").show(); 
    $("#select-test-card-another-ward").hide();
    $("#proceed-from-tests-selection-btn-another-ward").hide("fast");
  }

  function selectAnotherLabWard(elem,evt){
    evt.preventDefault();
    
    $(".spinner-overlay").show();
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_labs_and_health_facilities'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&ward=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-lab-card-ward .card-body").html(response.messages);
          $("#select-lab-card-ward #facilities-table").DataTable();
          LetterAvatar.transform();
          $("#select-lab-to-use-card-ward").hide();
          $("#select-lab-card-ward").show();
        }else{
          $.notify({
          message:"No Facility Currently Available Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });  
  } 



  function goBackSelectLabsWard (elem,evt) {
    $("#select-lab-to-use-card-ward").show();
    $("#select-lab-card-ward").hide();
  }

  function goBackFromAdditionalInformationOnTestsSelectedCard (elem,evt) {
    $("#other-facility-select-test-card").show();
    $("#additional-information-on-tests-selected-card").hide("fast");
    $("#proceed-from-tests-selection-btn").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn").hide("fast");
  }

  function goBackFromAdditionalInformationOnTestsSelectedCardAnother(elem,evt) {
    $("#select-test-card-another").show();
    $("#additional-information-on-tests-selected-card-another").hide("fast");
    $("#proceed-from-tests-selection-btn-another").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn-another").hide("fast");
  }

  function goBackFromViewSubTestsResultsCard (elme,evt) {
    $("#selected-tests-card").show();
    $("#view-sub-tests-results-card").hide();
  }

  function goBackFromViewPatientsResultsCard (elem,evt) {
    $("#selected-tests-card").show();
    $("#view-patient-results-card").hide();
  }

  function goBackFromViewPatientsResultsImagesCard (elem,evt) {
    $("#selected-tests-card").show();
    $("#view-patient-results-images-card").hide();
  }

  function goBackFromViewSubTestsResultsAndImagesCard (elem,evt) {
    $("#view-sub-tests-results-card").show();
            
    $("#view-sub-tests-results-and-images-card").hide();
  }

  function goBackFromViewPatientsResultsSubTestImagesCard (elem,evt) {
    $("#view-sub-tests-results-card").show();
    $("#view-patient-results-sub-test-images-card").hide();
  }

  function viewSubTestResultImages (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card").hide();
            $("#view-patient-results-sub-test-images-card .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
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

  function viewSubTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card").hide();
            $("#view-sub-tests-results-and-images-card .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
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

  function viewRadiologyResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#selected-tests-card").hide();
            $("#view-patient-results-card .card-body").html(messages)
            $("#view-patient-results-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
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

  function viewStandardTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card").hide();
            $("#view-patient-results-card .card-body").html(messages);
            $("#view-patient-results-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
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

  function viewTestResultImages (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card").hide();
            $("#view-patient-results-images-card .card-body").html(messages)
            $("#view-patient-results-images-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
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

  function viewMiniTestResult(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#selected-tests-card").hide();
            $("#view-patient-results-card .card-body").html(messages)
            $("#view-patient-results-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
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
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_tracking') ?>";

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
            

            $("#selected-tests-card").hide();
            $("#view-sub-tests-results-card .card-body").html(messages)
            $("#view-sub-tests-results-card .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
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

  function selectedTests(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_tests_by_consultation_id'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#selected-tests-card .card-body").html(messages);
          $("#selected-tests-card #selected-tests-table").DataTable();
          $("#new-consultation-form").hide();
          $("#selected-tests-card").show();
        }else{
           $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
    

  }


  function proceedFromAdditionalTestsSelectedAnother(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-card-another #additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_clinic_dr_another_facility') ?>";
    var referring_facility_id = tests_selected_obj[0].facility_id;

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      consultation_id : consultation_id,
      referring_facility_id : referring_facility_id
    }
    

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn-another").hide("fast");
              $("#additional-information-on-tests-selected-card-another").hide();
              $("#new-consultation-form").show();
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

  function proceedFromTestsSelectionAnother (elem,evt) {

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
        $("#select-test-card-another").hide();
        $("#additional-information-on-tests-selected-card-another").show("fast");
        $("#proceed-from-tests-selection-btn-another").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn-another").show("fast");
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

  function goBackFromSelectTestCardAnother (elem,evt) {
    tests_selected_obj = [];
    $("#select-lab-card").show(); 
    $("#select-test-card-another").hide();
    $("#proceed-from-tests-selection-btn-another").hide("fast");
  }

  function selectThisFacilityOthers(elem,evt){
     elem = $(elem);
     evt.preventDefault();
     console.log(consultation_id)
     
     var facility_id = elem.attr("data-id");
     var facility_name = elem.attr("data-facility-name");
     var facility_slug = elem.attr("data-slug");
      // var patient_user_name = document.getElementById("user_name").innerHTML;
     var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests";
      patient_name = $.trim(patient_name);
      
     
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others&consultation_id="+consultation_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(messages !== ""){  
            $("#select-lab-card").hide(); 
            $("#select-test-card-another .card-body").html(messages);
            $("#select-test-card-another").show();
            $("#select-test-card-another .select-test-table").DataTable();
            $("#proceed-from-tests-selection-btn-another").show("fast");
          }

          
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

  function proceedFromAdditionalTestsSelected(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-card #additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_clinic_dr') ?>";
    

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      consultation_id : consultation_id
    }
    console.log(obj)

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn").hide("fast");
              $("#additional-information-on-tests-selected-card").hide();
              $("#new-consultation-form").show();
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
        // $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " test selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }else{
        $("#num-tests-para").remove();
        // $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }
    }else{
      $("#num-tests-para").html("");
    }
  }    

  function proceedFromTestsSelection (elem,evt) {

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
        $("#other-facility-select-test-card").hide();
        $("#additional-information-on-tests-selected-card").show("fast");
        $("#proceed-from-tests-selection-btn").hide("fast");
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

  function goBackInputOutputInfo (elem,evt) {
    $("#input-output-card").show();
    $("#add-input-output-btn").show("fast");
    $("#input-output-info-card").hide(); 
  }

  function goBackInputOutput (elenm,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#input-output-card").hide();
    $("#add-input-output-btn").hide("fast");
  }

  function goBackVitalSignsInfo(elem,evt){
    $("#vital-signs-card").show();
   
    $("#add-vital-signs-btn").show("fast");
    $("#vital-signs-info-card").hide();
  }

  function goBackVitalSigns (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#vital-signs-card").hide();
    
    $("#add-vital-signs-btn").hide("fast");
  }

  function goBackOnNewConsultation(elem,evt){
    $("#add-new-consultation-card").hide();
    $("#drs-current-consultations").show();
    $("#add-consultation-btn").show("fast");
  }

  function addWardConsultation(elem,evt) {
    $("#drs-current-consultations").hide();
    $("#add-new-consultation-card").show();
    $("#add-consultation-btn").hide("fast");
  }

  function goBackCurrentConsultations(elem,evt){
    $("#add-consultation-btn").hide("fast");
    $("#drs-current-consultations").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  function goBackBioData (elem,evt) {
    $("#patient-bio-data-card").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  function goBackOnAdmissionInfo(elem,evt){
    $("#patient-admission-data-card").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  function goBackViewConsultation (elem,evt) {
    $("#drs-current-consultations").show();
    
    $("#view-consultation-card").hide();
    $("#add-consultation-btn").show("fast");
    
    $("#edit-consultation-btn").attr("onclick","editWardConsultation(this,event)");
    $("#edit-consultation-btn").hide("fast");
  }

  function goBackOnEditConsultation (elem,evt,id,ward_id) {
    $("#edit-consultation-card").hide();
    $("#view-consultation-card").show();
    $("#edit-consultation-btn").attr("onclick","editWardConsultation(this,event,"+id+","+ward_id+")");
    $("#edit-consultation-btn").show("fast");
  }

  function goBackRequestTests (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#request-lab-tests-card").hide();
  }

  function goBackClinicalNotes (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#clinical-notes-card").hide();
    $("#add-clinical-note-btn").hide("fast");
  }

  function goBackViewClinicalNote (elem,evt) {
    $("#clinical-notes-card").show();
    $("#view-clinical-note-card").hide();
  }

  function goBackViewOtherCharts(elem,evt){
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#view-other-charts-card").hide();    
  }

  function goBackOtherChartsInfo (elem,event) {
    $("#view-other-charts-card").show();
    $("#other-charts-info-card").hide();
  }

  function goBackOtherChartsInfo1 (elem,evt) {
    $("#other-charts-info-card").show();
    $("#other-charts-info-card1").hide();
  }

  function goBackReportCard (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#report-card").hide();
  }

  function goBackReportInfoCard (elem,evt) {
    $("#report-card").show();
    $("#report-info-card").hide();
  }

  function goBackRequestServices(elem,evt)  {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#request-services-card").hide();
    
  }

  function goBackRequestedServices (elem,evt) {
    $("#request-services-card").show();
    $("#requested-services-card").hide();
  }

  function requestWardService(elem,evt,id,name,type,price){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();

      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";

      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=requested_services_doctor&id="+ward_record_id+"&service_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#request-services-card").hide();
            $("#requested-services-card .card-body").html(messages);
            $("#requested-services-card").show();
            $("#requested-services-table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function requestServices(elem,evt){
   
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=request_services_doctor&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#request-services-card .card-body").html(messages);
            $("#request-services-card").show();
            $("#request-services-card #ward-services-table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewReportInfo(elem,evt,id) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=report_info&id="+ward_record_id+"&report_id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#report-card").hide();
            $("#report-info-card .card-body").html(messages);
            $("#report-info-card .card-title").html("Report Info");
            
            $("#report-info-card").show();
            

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function writeReport(elem,evt){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=get_reports&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#report-card .card-body").html(messages);
            $("#report-card").show();
            
            $("#report-card .table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadOtherChartInfo1(elem,evt,other_chart_id,ward_id, date){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=other_chart_info1&id="+ward_id+"&date="+date+"&other_chart_id="+other_chart_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#other-charts-info-card").hide();
            $("#other-charts-info-card1 .card-body").html(messages);
            $("#other-charts-info-card1 .card-title").html("Data Entered On "+ date);
            $("#other-charts-info-table1").DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false
            });
            
            $("#other-charts-info-card1").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadOtherChartInfo(elem,event,other_chart_id){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=other_chart&id="+ward_record_id+"&other_chart_id="+other_chart_id,
        success : function (response) {
          console.log(response);
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            var chart_name = response.chart_name;
            $("#view-other-charts-card").hide();
            $("#other-charts-info-card .card-title").html(chart_name);
            $("#other-charts-info-card .card-body").html(messages);
            
            $("#other-charts-info-card").show();
            
            $("#other-charts-info-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }


  function viewClinicalNote(elem,evt,id,ward_id){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_clinical_note&id="+ward_id+"&clinical_note_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#clinical-notes-card").hide();
            $("#view-clinical-note-card .card-body").html(messages);
            $("#view-clinical-note-card").show();
            
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function otherCharts (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_other_charts&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#view-other-charts-card .card-body").html(messages);
            $("#view-other-charts-card").show();
            
            $("#view-other-charts-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewClinicalNotes(elem,evt){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_clinical_notes&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html(messages);
            $("#clinical-notes-card").show();
            
            $("#clinical-notes-card .table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOnAdmissionSelectedTests (elem,evt) {
    
    $("#on-admission-selected-tests").hide();
    
    $("#request-lab-tests-card").show();
  }

  function onAdmissionTests (elem,evt) {
    
    
    if(ward_record_id !== "" && consultation_id != ""){
      $(".spinner-overlay").show();
        
        $(".spinner-overlay").show();
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_tests_by_consultation_id'); ?>";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true&type=ward&consultation_id="+consultation_id,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages !== ""){
              var messages = response.messages;
              $("#on-admission-selected-tests .card-body").html(messages);
              $("#on-admission-selected-tests #selected-tests-table").DataTable();
              $("#request-lab-tests-card").hide();
              $("#on-admission-selected-tests").show();
            }else{
               $.notify({
              message: "No Data To Display"
              },{
                type : "warning"  
              });
            }
          },error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message: "Sorry Something Went Wrong"
            },{
              type : "danger"  
            });
          } 
        });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewRadiologyResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

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

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages)
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-ward #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-ward").show();
            
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

  function viewTestResultImagesWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-images-card-ward .card-body").html(messages)
            $("#view-patient-results-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-ward").show();
            
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

  function viewMiniTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

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
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages)
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward #test-result-table").DataTable();
            $("#view-patient-results-card-ward").show();
            
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

  function viewStandardTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

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
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages);
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward #test-result-table").DataTable();
            $("#view-patient-results-card-ward").show();
            
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

  function viewTestsSubTestsResultsWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_tracking') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&ward=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#on-admission-selected-tests").hide();
            $("#view-sub-tests-results-card-ward .card-body").html(messages)
            $("#view-sub-tests-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-ward #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-ward").show();
            
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

  function viewSubTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

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

            $("#view-sub-tests-results-card-ward").hide();
            $("#view-sub-tests-results-and-images-card-ward .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-ward #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-ward").show();
            
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

  function viewSubTestResultImagesWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

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
            

            $("#view-sub-tests-results-card-ward").hide();
            $("#view-patient-results-sub-test-images-card-ward .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-ward").show();
            
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

  function goBackFromViewPatientsResultsSubTestImagesCardWard (elem,evt) {
    $("#view-sub-tests-results-card-ward").show();
    $("#view-patient-results-sub-test-images-card-ward").hide();
  }

  function goBackFromViewSubTestsResultsAndImagesCardWard (elem,evt) {
    $("#view-sub-tests-results-card-ward").show();
    $("#view-sub-tests-results-and-images-card-ward").hide();
  }

  function goBackFromViewSubTestsResultsCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-sub-tests-results-card-ward").hide();
  }

  function goBackFromViewPatientsResultsImagesCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-patient-results-images-card-ward").hide();
  }

  function goBackFromViewPatientsResultsCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-patient-results-card-ward").hide();
  }

  function goBackCurrentSelectedTests (elem,evt) {
    $("#current-selected-tests").hide();
    $("#request-lab-tests-card").show();
    $("#request-new-tests-btn").hide("fast");
  }

  function goBackFromSelectLabToUseWard (elem,evt) {
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
    $("#select-lab-to-use-card-ward").hide();
  }

  function goBackFromSelectOtherLabWard (elem,evt) {
    tests_selected_obj = [];
    $("#select-lab-to-use-card-ward").show();
    $("#proceed-from-tests-selection-btn-ward").hide("fast");
    $("#other-facility-select-test-card-ward").hide();
  }

  

  function requestNewTests (elem,evt) {
    $("#current-selected-tests").hide();
    $("#request-new-tests-btn").hide("fast");
    $("#select-lab-to-use-card-ward").show();
  }

  function currentTests (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=requested_ward_tests&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#current-selected-tests .card-body").html(messages);
            $("#current-selected-tests").show();
            $("#current-selected-tests table").DataTable();
            $("#request-lab-tests-card").hide();
            $("#request-new-tests-btn").show("fast");

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function requestLabTests (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#request-lab-tests-card").show();
  }

  function viewMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#medication-chart-card").show();
  }



  function loadInputOutputInfo(elem,evt,ward_id,date) {
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=input_output_info&id="+ward_id+"&date="+date,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#input-output-card").hide();
            $("#input-output-info-card .card-body").html(messages);
            $("#input-output-info-card .card-title").html("Data Entered On "+ date);
            $("#input-output-info-table").DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false
            });
            $("#add-input-output-btn").hide("fast");
            $("#input-output-info-card").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function inputOutputChart (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=input_output&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#input-output-card .card-body").html(messages);
            $("#input-output-card").show();
            $("#add-input-output-btn").show("fast");
            $("#input-output-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function editWardConsultation(elem,evt,id,ward_id){
    console.log(id)
    console.log(ward_id);
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=consultation_info&id="+ward_id+"&consultation_id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.consultation_info != ""){
            var consultation_info = response.consultation_info;
            var advice_given = consultation_info.advice_given;
            var complaints = consultation_info.complaints;
            var examination_findings = consultation_info.examination_findings;
            var diagnosis = consultation_info.diagnosis;
            var history = consultation_info.history; 

            $("#edit-consultation-form #complaints").val(complaints);
            $("#edit-consultation-form #advice").val(advice_given);
            $("#edit-consultation-form #examination-findings").val(examination_findings)
            $("#edit-consultation-form #diagnosis").val(diagnosis);
            $("#edit-consultation-form #history").val(history);
            $("#view-consultation-card").hide();
            $("#edit-consultation-btn").attr("onclick","editWardConsultation(this,event)");
            $("#edit-consultation-btn").hide("fast");
            $("#edit-consultation-card #go-back").attr("onclick","goBackOnEditConsultation(this,event,"+id+","+ward_id+")");
            $("#edit-consultation-card #edit-consultation-form").attr('data-id', id);
            $("#edit-consultation-card").show();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function patientBioData (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=bio_data&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#patient-bio-data-card .card-body").html(messages);
            $("#patient-bio-data-card").show();
          }else if(!response.user_exists){
            swal({
              title: 'Ooops',
              text: "Sorry This user Does Not Exist",
              type: 'error'
            })
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadVitalSignsInfo(elem,evt,ward_id,date) {
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=vital_signs_info&id="+ward_id+"&date="+date,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#vital-signs-card").hide();
            $("#vital-signs-info-card .card-body").html(messages);
            $("#vital-signs-info-card .card-title").html("Vital Signs Entered On "+ date);
            $(".table-test").DataTable();
            $("#add-vital-signs-btn").hide("fast");
            $("#vital-signs-info-card").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewVitalSigns (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=vital_signs&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#vital-signs-card .card-body").html(messages);
            $("#vital-signs-card").show();
            $("#vital-signs-card .table").DataTable();
            $("#add-vital-signs-btn").show("fast");
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else if(response.no_records){
            swal({
              title: 'Ooops',
              text: "Sorry No Records To Display",
              type: 'warning'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewConsultation(elem,evt,id,ward_id){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_consultation&id="+ward_id+"&consultation_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#drs-current-consultations").hide();
            $("#view-consultation-card .card-body").html(messages);
            $("#view-consultation-card").show();
            $("#add-consultation-btn").hide("fast");
            if(response.consultation_editable){
              $("#edit-consultation-btn").attr("onclick","editWardConsultation(this,event,"+id+","+ward_id+")");
              $("#edit-consultation-btn").show("fast");
            }
            
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function viewWardConsultations (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=current_dr_records&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#drs-current-consultations .card-body").html(messages);
            $("#drs-current-consultations").show();
            $("#add-consultation-btn").show("fast");
            $(".table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#drs-current-consultations .card-body").html("<h5 class='text-warning'>No Previous Consultations</h5>");
            $("#drs-current-consultations").show();
            $("#add-consultation-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function writeConsults (elem,evt) {
    
    if(ward_record_id !== ""){
      <?php if($clinic_structure == "standard"){ ?>
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=get_current_consults&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#consults-card .card-body").html(messages);
            $("#consults-card .card-body tr a.btn").tooltip();
            $("#consults-card").show();
            $("#add-consult-btn").show("fast");
            $(".table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#consults-card .card-body").html("<h5 class='text-warning'>No Clinic Currently Has Consult Access</h5>");
            $("#consults-card").show();
            $("#add-consult-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
      <?php }else{ ?>
      swal({
        title: 'Ooops',
        text: "Your Current Clinic Structure Does Not Support Consults. ",
        type: 'error'
      })
      <?php } ?>   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function addNewConsult (elem,evt) {
    $("#choose-clinic-for-ward-consult-modal").modal("show");
  }

  function selectThisClinicForWardConsult(elem,evt,clinic_id){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=add_clinic_for_consult&id="+ward_record_id+"&clinic_id="+clinic_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success){
            
            $("#choose-clinic-for-ward-consult-modal").modal("hide");
            $(".spinner-overlay").show();
            setTimeout(function () {

              $(".spinner-overlay").hide();
              $.notify({
              message: "Consult Made Successfully"
              },{
                type : "success"  
              });
              $("#wards-patients-card").show();
              $("#choose-action-patient-modal").modal("show");
            
              $("#consults-card").hide();
              $("#add-consult-btn").hide("fast");
            }, 2000);
            
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function restrictAccess(elem,evt,clinic_id,ward_record_id){
    swal({
      title: 'Warning',
      text: "Are You Sure You Want To Restrict Access Of This Clinic To This Users Data?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Proceed'
    }).then((result) => {
      
      if(ward_record_id !== ""){
        $(".spinner-overlay").show();
            
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "view_records=true&type=restrict_consult_access&id="+ward_record_id+"&clinic_id="+clinic_id,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success){
              
              $("#choose-clinic-for-ward-consult-modal").modal("hide");
              $(".spinner-overlay").show();
              setTimeout(function () {

                $(".spinner-overlay").hide();
                $.notify({
                message: "Consult Access Restricted Successfully"
                },{
                  type : "success"  
                });
                $("#wards-patients-card").show();
                $("#choose-action-patient-modal").modal("show");
              
                $("#consults-card").hide();
                $("#add-consult-btn").hide("fast");
              }, 2000);
              
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'error'
              })
            }else{
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
              title: 'Ooops',
              text: "Sorry Something Went Wrong",
              type: 'error'
            })
          } 
        });    
      }else{
        swal({
          title: 'Ooops',
          text: "Sorry Something Went Wrong",
          type: 'error'
        })
      }
    });
  }

  function goBackFromConsultsCard (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#consults-card").hide();
    $("#add-consult-btn").hide("fast");
    
  }

  function patientAdmissionRecords (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=admission_records&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#patient-admission-data-card .card-body").html(messages);
            $("#patient-admission-data-card").show();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function openChoosePatientAction(elem,evt,id,name,consult_id,user_id) {
    if(id !== ""){
      ward_record_id = id;
      consultation_id = consult_id;
      patient_name = name;
      patient_user_id = user_id;
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=get_patient_admission_expiry_status&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success){
            var messages = response.messages;
            var expiry_date = response.expiry_date;
            if(!response.nfp){
              var str = patient_name +"'s Admission Payment Will Expire "+expiry_date
            }else{
              var str = expiry_date;
            }
            if(response.expired_first_stage){
              str = "<em class='text-danger'>" + str + "</em>";
            }else{
              str = "<em class='text-success'>" + str + "</em>";
            }
            
            $("#choose-action-patient-modal .modal-body #expiry-info").html(str);
            $("#choose-action-patient-modal .modal-title").html("Choose Action For " +patient_name);
            $("#choose-action-patient-modal").modal("show");
          }else if(response.expired){
            swal({
              title: 'Ooops',
              text: patient_name +"'s Admission Payment Has Expired",
              type: 'error'
            })
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
    console.log(id)
    // $(".record_id").val(record_id)
  }

  function goBackFromSelectedTests(elem,event) {
    $("#selected-tests-card").hide();
    $("#new-consultation-form").show();
  }

  function goBackFromSelectedSubTests(elem,evt){
    $("#selected-sub-tests-card").hide();
    $("#selected-tests-card").show();
  }

  function goBackFromWardSelectedSubTests(elem,evt){
    $("#ward-selected-sub-tests-card").hide();
    $("#on-admission-selected-tests").show();
  }

  function goBackFromWardSelectedSubTests1(elem,evt){
    $("#ward-selected-sub-tests-card1").hide();
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
  }

  function goBackFromViewResults(elem,evt){
    $("#view-results-card").hide();
    $("#selected-tests-card").show();
  }

  function goBackFromWardViewResults(elem,evt){
    $("#ward-view-results-card").hide();
    $("#on-admission-selected-tests").show();
  }

  function goBackFromWardViewResults1(elem,evt){
    $("#ward-view-results-card1").hide();
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
  }

  function goBackFromViewResultsImages(elem,evt){
    $("#view-results-images-card").hide();
    $("#selected-tests-card").show();
  }

  function goBackFromWardViewResultsImages(elem,evt){
    $("#ward-view-results-images-card").hide();
    $("#on-admission-selected-tests").show();
  }

  function goBackFromWardViewResultsImages1(elem,evt){
    $("#ward-view-results-images-card1").hide();
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
  }

  function goBackFromViewResultsSub(elem,evt){
    $("#view-results-card-sub").hide();
    $("#selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsSub(elem,evt){
    $("#ward-view-results-card-sub").hide();
    $("#ward-selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsSub1(elem,evt){
    $("#ward-view-results-card-sub1").hide();
    $("#ward-selected-sub-tests-card1").show();
  }

  function goBackFromViewResultsImagesSub(elem,evt){
    $("#view-results-images-card-sub").hide();
    $("#selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsImagesSub(elem,evt){
    $("#ward-view-results-images-card-sub").hide();
    $("#ward-selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsImagesSub1(elem,evt){
    $("#ward-view-results-images-card-sub1").hide();
    $("#ward-selected-sub-tests-card1").show();
  }

  function goBackSubTests1(){
    $("#sub-tests-card").hide();
    $("#off-appointment-patient-card").show();
    
  }

  function viewTestResultImagesSub (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          if(isward == 1){
            var card_name = "ward-view-results-images-card-sub";
          }else if(isward == 2){
            var card_name = "ward-view-results-images-card-sub1";
          }else{
            var card_name = "view-results-images-card-sub"; 
          }


          $("#"+card_name+ " .card-body").html(messages);

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Result Images For " +test_name);
          $("#selected-sub-tests-card").hide();
          $("#ward-selected-sub-tests-card").hide();
          $("#ward-selected-sub-tests-card1").hide();
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function viewTestResultsSub (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          if(isward == "1"){
            var card_name = "ward-view-results-card-sub";
          }else if(isward == "2"){
            var card_name = "ward-view-results-card-sub1";
          }else{
            var card_name = "view-results-card-sub"; 
          }


          $("#"+card_name+ " .card-body").html(messages);

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Ready Result For " +test_name);
          $("#selected-sub-tests-card").hide();
          $("#ward-selected-sub-tests-card").hide();
          $("#ward-selected-sub-tests-card1").hide();
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  

  function viewSubTests1(elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    
    evt.preventDefault()
    // console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_sub_tests_clinic'); ?>";

    var form_data = {
      "show_records" : true,
      "record_id" : record_id,
      "initiation_code" : initiation_code,
      "lab_id" : lab_id,
      "health_facility_id" : health_facility_id,
      "main_test_id" : main_test_id,
      "sub_dept_id" : sub_dept_id
    }
    if(isward && type != null){
      form_data.type = "ward1";
    }else if(isward){
      form_data.type = "ward";
    }

    console.log(form_data)
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : form_data,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.main_test_name != ""){
          var messages = response.messages;
          var main_test_name = response.main_test_name;

          if(isward && type != null){
            var card_name = "ward-selected-sub-tests-card1";
          }else if(isward){
            var card_name = "ward-selected-sub-tests-card";
          }else{
            var card_name = "selected-sub-tests-card";
          }
          

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Selected Sub Tests Under " +main_test_name);
          $("#selected-tests-card").hide();
          $("#on-admission-selected-tests").hide();
          $("#current-selected-tests").hide();
          $("#request-new-tests-btn").hide("fast");
          $("#"+card_name).show();
          
          $(".tests-table").DataTable();
          
        }else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
    
  }

 
  function goBackFromOnAppointmentCard (elem,evt) {
    $("#choose-action-card").show();
    $("#on-appointment-card").hide();
  }

  function onAppointment (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_on_appointments_clinic_doctor'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){
          $("#on-appointment-card .card-body").html(response.messages);
          $("#on-appointment-card #on-appointment-table").DataTable();
          $("#choose-action-card").hide();
          $("#on-appointment-card").show();
        }
        else{
          $.notify({
          message: "Sorry Something Went Wrong"
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

  function goBackOffAppointmentPatientCardNewPatient (elem) {
    $("#off-appointment-patient-card").hide();
    $("#new-patients-card").show();
  }

  function loadPatientBioOffApp (id) {
    
    consultation_id = id;
    // console.log(consultation_id)
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          patient_user_id = response.patient_user_id;
          patient_name = response.patient_full_name;
          var registration_num = response.registration_num;
          $("#off-appointment-patient-card .card-title-1").html("Perform Functions On Patient <br>Patient Name: <em class='text-primary'>"+patient_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>");

          $("#off-appointment-patient-card .vital-signs-form input").val("");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-patient-activity-records-edit'); ?>";
    
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "show_records=true&consultation_id="+consultation_id,
            success : function (response1) {
              if(response1.success == true && response1.messages.length != 0){
                var messages = response1.messages;
                for (const [key, value] of Object.entries(messages)) {
                  $("#off-appointment-patient-card #vital-signs-form #" +key).val(value);
                }
              }
            },
            error : function () {
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });
            }
          });
          $("#off-appointment-patient-card #go-back").attr("onclick","goBackOffAppointmentPatientCardNewPatient(this,event)");
          $("#off-appointment-patient-card").show();
          $("#new-patients-card").hide();
          $("#off-appointments-card").hide();
          $("#on-appointment-card").hide();
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

  function loadPatientBioOffAppointment (id) {
    
    consultation_id = id;
    // console.log(consultation_id)
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          patient_user_id = response.patient_user_id;
          patient_name = response.patient_full_name;
          var registration_num = response.registration_num;
          $("#off-appointment-patient-card .card-title-1").html("Perform Functions On Patient <br>Patient Name: <em class='text-primary'>"+patient_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>");

          $("#off-appointment-patient-card .vital-signs-form input").val("");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-patient-activity-records-edit'); ?>";
    
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "show_records=true&consultation_id="+consultation_id,
            success : function (response1) {
              if(response1.success == true && response1.messages.length != 0){
                var messages = response1.messages;
                for (const [key, value] of Object.entries(messages)) {
                  $("#off-appointment-patient-card #vital-signs-form #" +key).val(value);
                }
              }
            },
            error : function () {
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });
            }
          });
          $("#off-appointment-patient-card #go-back").attr("onclick","goBackOffAppointmentPatientCardOffAppointment(this,event)");
          $("#off-appointment-patient-card").show();
          $("#new-patients-card").hide();
          $("#off-appointments-card").hide();
          $("#on-appointment-card").hide();
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

  function goBackOffAppointmentPatientCardOffAppointment (elem,evt) {
    $("#off-appointment-patient-card").hide();
    $("#off-appointments-card").show();
  }

  function goBackFromSelectOtherLab(elem,evt) {
    evt.preventDefault();
    tests_selected_obj = [];
    $("#other-facility-select-test-card").hide();
    $("#select-lab-to-use-card").show();
    $("#proceed-from-tests-selection-btn").hide("fast");
  }
  
  function goBackFromSelectLab(elem,evt) {
    evt.preventDefault();
    $("#select-lab-card .tab-pane .row").html("");
    $("#select-test-card").hide();
    $("#select-lab-to-use-card").show();
  }

  function goBackSelectedInfoDrugs(elem,evt){
    $("#select-drugs-card").show();
    $("#select-drugs-proceed-btn").show("fast");
    $("#select-drugs-proceed-btn-2").hide("fast");
    $("#selected-drugs-info-card").hide();    
  }

  function useYourLab (elem,evt) {
    evt.preventDefault();
    $("#select-lab-to-use-card").hide();
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
    if(status == "new_patient"){
      var patient_name = $("#patient-name").val();
    }else if(status == "registered_patient"){
      var patient_name = document.getElementById("name").innerHTML;
      var patient_user_name = document.getElementById("user_name").innerHTML;
    }
    var get_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug.'/pathology-laboratory-services/'); ?>"+"get_all_facility_tests";
    patient_name = $.trim(patient_name);
    
    
      if(format.test(patient_name)){
        $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 
      }else{
        $(".spinner-overlay").show();

        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "type=mine",
          success : function (response) {
            $(".spinner-overlay").hide();
            var messages = response.messages;
            if(messages !== ""){       
              $("#select-test-card .card-body").html(messages)     
              $("#select-test-card .proceed").attr('onclick' , "proceed('registered_patient','"+patient_user_name+"','"+patient_name+"')");
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

    $("#select-test-card").show();
  }

  function goBackFromSelectLabToUse(elem,evt){
    evt.preventDefault();
    $("#select-lab-to-use-card").hide();
    $("#select-lab-to-use-card-2").hide();
    $("#new-consultation-form").show();
    // $("#new-consultation-form-2").show();
  }

  function selectAnotherLab(elem,evt){
    evt.preventDefault();
    
    $(".spinner-overlay").show();
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_labs_and_health_facilities'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-lab-card .card-body").html(response.messages);
          $("#select-lab-card #facilities-table").DataTable();
          LetterAvatar.transform();
          $("#select-lab-to-use-card").hide();
          $("#select-lab-card").show();
        }else{
          $.notify({
          message:"No Facility Currently Available Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function selectTestsBtn (elem,evt) {
    evt.preventDefault();
    var complaints = $("#new-consultation-form #complaints").val();
    var history = $("#new-consultation-form #history").val();
    var examination_findings = $("#new-consultation-form #examination-findings").val();
    var diagnosis = $("#new-consultation-form #diagnosis").val();
    var advice = $("#new-consultation-form #advice").val();
    var future_appointment = $("#new-consultation-form #future-appointment").val();
    

    $("#new-consultation-form").hide();
    // $("#new-consultation-form-2").hide();
    <?php if($health_facility_structure == "hospital"){ ?>
    $("#select-lab-to-use-card").show();
    $("#select-lab-to-use-card-2").show();
    <?php }else{ ?>
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_labs_and_health_facilities'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-lab-card .card-body").html(response.messages);
          $("#select-lab-card").show();
          $("#facilities-table").DataTable();
          LetterAvatar.transform();
        }else{
          $.notify({
          message:"No Records To Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 
    <?php } ?>
  }

  function goBackFromSelectPharmacyToUse (elem,evt) {
    $("#select-pharmacy-to-use-card").hide();
    $("#new-consultation-form").show();
  }
  
  function selectPharmacyBtn (elem,evt) {
    evt.preventDefault();
    $("#new-consultation-form").hide();
    $("#select-pharmacy-to-use-card").show();
  }

  function checkBoxEvt (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    var price = elem.attr("data-price");
    var brand_name = elem.attr("data-brand-name");
    var generic_name = elem.attr("data-generic-name");
    var formulation = elem.attr("data-formulation");
    var strength = elem.attr("data-strength");
    var strength_unit = elem.attr("data-strength-unit");
    var main_store_quantity = elem.attr("data-main-store-quantity");
    var dispensary_quantity = elem.attr("data-dispensary-quantity");
    var is_poison = elem.attr("data-is-poison");
    var unit = elem.attr("data-unit");
    

    var data = {
      id : id,
      price : price,
      brand_name : brand_name,
      generic_name : generic_name,
      formulation : formulation,
      strength : strength,
      strength_unit : strength_unit,
      main_store_quantity : main_store_quantity,
      dispensary_quantity : dispensary_quantity,
      unit : unit,
      is_poison : is_poison
    };
    if(elem.prop('checked')){
      isChecked = true;
    }else{
      isChecked = false;
    }

    if(isChecked){
      selected_drugs.push(data);
    }else{
      var index = selected_drugs.map(function(obj, index) {
          if(obj.id === id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        selected_drugs.splice(index, 1);
      }
    }
  }

  function calculatePrescription (dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i) {
    // console.log(main_store_quantity + " : " + dispensary_quantity);
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;

      quantity = Math.round(quantity * 100) / 100;



      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price = Math.round(price * 100) / 100;
        price = parseFloat(price).toFixed(2);
        if(price != null){
          var total_price = price * quantity;
          var html = "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
          html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
          html += "<p>Total Price: " + addCommas(total_price) +"</p>";
          if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
            var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
            console.log(total_store_quantity)
            if(quantity > total_store_quantity){
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ".</span>";
            }
          }
          $("#prescription-data-div .form-row").eq(i).find(".display-div").html(html);
        }
      }
    }
  }

  function calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,i) {
    // console.log(dosage)
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      var ret = {};
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;
      quantity = Math.round(quantity * 100) / 100;

      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price =  Math.round(price * 100) / 100;
        if(price != null){
          var total_price = price * quantity;
          ret = {
            'quantity' : quantity,
            'total_price' : total_price
          };
        }
      }

      return ret;
    }
  }

  function dosageEvent(elem,event,i,type = ""){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      
    }
  }

  function frequencyEvent1(elem,event,i,type = ""){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      
    }
  }

  function frequencyEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
  }

  function durationEvent1(elem,event,i,type = ""){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      
    }
  }

  function durationEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);   
  }

  function goBackSelectDrugs (elem,evt) {
    user_info = [];
    selected_drugs = [];
    $("#select-drugs-card .card-title").html("");
    
    
    $("#select-pharmacy-to-use-card").show();
    
    $("#select-drugs-card").hide();
    $("#select-drugs-proceed-btn").hide("fast");
  }

  function selectThisFacilityPharmacy(elem,evt){
    evt.preventDefault();
     
    var tr = elem.parentElement.parentElement;
    var facility_id = tr.getAttribute("data-id");
    var facility_name = tr.getAttribute("data-facility-name");
    var facility_slug = tr.getAttribute("data-slug");
   
    var get_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug. '/') ?>" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
      
    $(".spinner-overlay").show();
    $.ajax({
      url : get_tests_url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : {
        consultation_id : consultation_id,
        host_facility_id : facility_id
      },
      success : function (response) {
        $(".spinner-overlay").hide();
        var messages = response.messages;
        console.log(messages)
        if(response.success && response.messages != "" && response.user_info != ""){
          var messages = response.messages;
          var patient_name = response.patient_name;
          user_info = response.user_info;

          $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
          $("#select-drugs-card .card-body").html(messages);
          $("#select-drugs-card").attr("data-facility-id",facility_id);
          $("#select-pharmacy-card").hide();
          $("#select-drugs-card #select-drugs-table").DataTable();
          $("#select-drugs-card").show();
          $("#select-drugs-proceed-btn").show("fast");
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

  function selectThisPharmacy(elem,evt){
     evt.preventDefault();
     
     var tr = elem.parentElement.parentElement;
     var facility_id = <?php echo $health_facility_id; ?>;
     var facility_name = "<?php echo $health_facility_name; ?>";
     var facility_slug = "<?php echo $health_facility_slug; ?>";
     
     var patient_name = document.getElementById("name").innerHTML;
      // var patient_user_name = document.getElementById("user_name").innerHTML;
     var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
    
      
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          consultation_id : consultation_id,
          host_facility_id : <?php echo $health_facility_id; ?>
        },
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          var messages = response.messages;
          
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card .card-body").html(messages);
            $("#select-drugs-card").attr("data-facility-id",facility_id);
            $("#select-pharmacy-to-use-card").hide();
            $("#select-drugs-card #select-drugs-table").DataTable();
            $("#select-drugs-card").show();
            $("#select-drugs-proceed-btn").show("fast");
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

  function selectDrugsProceed2 () {
    var proceed = false;
    
    var facility_id = $("#select-drugs-card").attr("data-facility-id");

    for(var i = 0; i < selected_drugs.length; i++) { 
      var is_poison = selected_drugs[i].is_poison; 
      if(is_poison == 1){ 
        proceed = false;
        break; 
      }else{
        proceed = true;
      }
    }

    if(!proceed){
      if(user_info.clinician !== ""){
        proceed = true;
      }
    }

    console.log(proceed)
    
    // evt.preventDefault();
    if(proceed){
      var me = $("#prescription-data-div");
      var form_data = {
        drugs_info : []
      };
      
      var drugs_info = [];
      var num = 0;
      var sum = 0;
      var total_quantity = 0;
      me.find(".form-row").each(function(index, el) {
        var el = $(el);
        var id = el.attr("data-id");
        var price = selected_drugs[index].price;
        var dosage = el.find(".dosage").val();
        var frequency_num = el.find(".frequency_num").val();
        var frequency_time = el.find(".frequency_time").val();
        var duration_num = el.find(".duration_num").val();
        var duration_time = el.find(".duration_time").val();

        var obj = {
          id : id,
          frequency_num : frequency_num,
          frequency_time : frequency_time,
          duration_num : duration_num,
          duration_time : duration_time,
          dosage : dosage,
          price : price
        };

        drugs_info[index] = obj;

        if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
          num++;
          var prescription_obj = calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
          // console.log(prescription_obj)
          if(prescription_obj != {}){
            var quantity = prescription_obj.quantity;
            var total_price = prescription_obj.total_price;

            total_quantity += quantity;
            sum += total_price;
          }
        }

      });

      form_data['drugs_info'] = drugs_info;
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
      form_data["clinic"] = true;

      form_data["consultation_id"] = consultation_id;
      form_data['host_facility_id'] = facility_id;


      console.log(form_data);
      // console.log(JSON.stringify(form_data))

      if(num > 0){
        swal({
          title: 'Proceed?',
          text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Proceed?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_drugs_selected_pharmacy'); ?>";
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
                message:"Drugs Selected Successfully"
                },{
                  type : "success"  
                });
                $("#new-consultation-form").show();
                selected_drugs = [];
                user_info = [];
                $("#selected-drugs-info-card").hide();
                $("#select-drugs-proceed-btn-2").hide("fast");
                
              }else{
                swal({
                 title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
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
      }else{
        swal({
          title: 'Warning! ',
          text: "You Must Enter Complete Prescription Info For At Least One Selected Drug To Proceed",
          type: 'error'
        })
      }
    }else{
      $("#enter-clinician-name-modal").modal("show");
    }
  }

  function selectDrugsProceed(elem,evt) {
    console.log(selected_drugs);
    console.log(user_info);
    console.log(selected_drugs.length)
    if(selected_drugs.length > 0){
      console.log("baba")
      var selected_drugs_info_html = "<div class='container-fluid'>";
      var j = 0;
      
      selected_drugs_info_html += "<div id='prescription-data-div'>";

      for(var i = 0; i < selected_drugs.length; i++){
        var id = selected_drugs[i].id;
        var price = selected_drugs[i].price;
        var brand_name = selected_drugs[i].brand_name;
        var generic_name = selected_drugs[i].generic_name;
        var formulation = selected_drugs[i].formulation;
        var strength = selected_drugs[i].strength;
        var strength_unit = selected_drugs[i].strength_unit;
        var main_store_quantity = selected_drugs[i].main_store_quantity;
        var dispensary_quantity = selected_drugs[i].dispensary_quantity;
        var unit = selected_drugs[i].unit;
        

        j++;
        selected_drugs_info_html += j + ". ";
        
        
        selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";
        selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
        selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
        selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
        selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
        selected_drugs_info_html += "<input class='form-control dosage' type='number' onkeyup='dosageEvent(this,event,"+i+")'>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
        selected_drugs_info_html += "<input class='form-control frequency_num' type='number' onkeyup='frequencyEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "<option value='minutely' selected>Minutely</option>";
        selected_drugs_info_html += "<option value='hourly'>Hourly</option>";
        selected_drugs_info_html += "<option value='daily'>Daily</option>";
        selected_drugs_info_html += "<option value='weekly'>Weekly</option>";
        selected_drugs_info_html += "<option value='monthly'>Monthly</option>";
        selected_drugs_info_html += "<option value='yearly'>Yearly</option>";
        selected_drugs_info_html += "<option value='nocte'>Nocte</option>";
        selected_drugs_info_html += "<option value='stat'>Stat</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
        selected_drugs_info_html += "<input class='form-control duration_num' type='number' onkeyup='durationEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "option value='minutes' selected>Minutes</option>";
        selected_drugs_info_html += "<option value='hours'>Hours</option>";
        selected_drugs_info_html += "<option value='days'>Days</option>";
        selected_drugs_info_html += "<option value='weeks'>Weeks</option>";
        selected_drugs_info_html += "<option value='months'>Months</option>";
        selected_drugs_info_html += "<option value='years'>Years</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "</div>";
        
      }
      selected_drugs_info_html += "</div>";
      console.log(selected_drugs_info_html)

      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
      $("#select-drugs-proceed-btn-2").show("fast");
      $("#selected-drugs-info-card .card-title").html("Enter prescription Details For: " + user_info.full_name);
      $("#selected-drugs-info-card .card-body").html(selected_drugs_info_html);
      $("#selected-drugs-info-card").show();
      selected_drugs_info_html += "</div>";
    }else{
      swal({
       title: 'Sorry',
        text: "You Must Select At Least One Drug To Proceed",
        type: 'error'
      })
    }
  }

  function goBackSelectPharmacies (elem,evt) {
    $("#select-pharmacy-to-use-card").show();
    $("#select-pharmacy-card").hide();
  }

  function selectAnotherPharmacy (elem,evt) {
    $(".spinner-overlay").show();
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_pharmacies_and_health_facilities'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-pharmacy-to-use-card").hide();
          $("#select-pharmacy-card .card-body").html(response.messages);
          $("#select-pharmacy-card").show();
          $("#facilities-pharmacies-table").DataTable();
          LetterAvatar.transform();
        }else{
          $.notify({
          message:"No Records To Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 
  }

  function goBackSelectLabs(elem,evt){
    evt.preventDefault();
    $("#select-lab-card").hide();
    <?php if($health_facility_structure == "hospital"){ ?>
    $("#select-lab-to-use-card").show();
    <?php }else{ ?>  
    $("#new-consultation-form").show();
    <?php } ?>
  }

  function displayPreviousConsultations (elem,evt) {
    evt.preventDefault();
  }

  function goBackFromNewConsultationForm (elem,evt) {
    evt.preventDefault();
    $("#drs-functions-card").show();
    // $("#drs-functions-card1").hide();
    $("#new-consultation-form").hide();
  }

  function startNewConsultation(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_previous_consultation_details_for_patient'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true){
          if(response.messages.length != 0){
            var messages = response.messages;
            for (const [key, value] of Object.entries(messages)) {
              $("#new-consultation-form #" +key).val(value);
              if(key == "examination_findings"){
                $("#new-consultation-form #examination-findings").val(value);
              }else if(key == "advice_given"){
                $("#new-consultation-form #advice").val(value);
              }
            }
          }
          $("#drs-functions-card").hide();
          $("#new-consultation-form").show();
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
    
    
  }

  function displayPreviousConsultations (elem,evt) {
    evt.preventDefault();
    $("#drs-functions-card").hide();
    // $("#drs-functions-card1").hide();
    $("#choose-consultation-to-display-card").show();
  }

  function goBackFromChooseConsultationToDisplayCard (elem,evt) {
    $("#drs-functions-card").show();
    // $("#drs-functions-card1").hide();
    $("#choose-consultation-to-display-card").hide();
  }


  function viewPreviousClinicConsultations (elem,evt) {
    console.log(patient_hospital_number)
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_prvevious_clinic_consultations_for_patient'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&hospital_number="+patient_hospital_number,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#choose-consultation-to-display-card").hide();
          $("#previous-clinic-consultations-card .card-body").html(messages);
          $("#previous-clinic-consultations-card #previous-clinic-consultations-table").DataTable();
          $("#previous-clinic-consultations-card").show();
        }else if(response.no_records){
          $.notify({
          message:"No Records To Display For This Patient Here"
          },{
            type : "warning"  
          });
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

  function goBackFromPreviousClinicConsultationsCard (elem,evt) {
    $("#choose-consultation-to-display-card").show();
    
    $("#previous-clinic-consultations-card").hide();
  }

  function loadPatientsPreviousClinicConsultationInfo (elem,evt,record_id) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previous_clinic_consultation_info'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+record_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #" +key).val(value);
            
            if(key == "advice_given"){
              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #advice").val(value);
            }else if(key == "examination_findings"){
              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #examination-findings").val(value);
            }else if(key == "id"){
              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form .record_id").val(value);
              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #view-selected-tests-previous-clinic-consultations").attr("onclick","viewSelectedTestsPreviousClinicConsultations(this,event,"+value+")");

              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #view-selected-drugs-previous-clinic-consultations").attr("onclick","viewPreviouslySelectedDrugsPreviousClinicConsultations(this,event,"+value+")");
            }else if(key == "pr" || key == "rr" || key == "bp" || key == "temperature" || key == "waist_circumference" || key == "hip_circumference"){
              $("#edit-previous-clinic-consultation-card #edit-previous-clinic-consultation-form #" +key).html(value);
            }
          }
          $("#previous-clinic-consultations-card").hide();
          $("#edit-previous-clinic-consultation-card").show();
        }else{
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

  function viewSelectedTestsPreviousClinicConsultations(elem,evt,record_id) {
    evt.preventDefault();
    
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_tests_by_record_id_previous_clinic_consultations'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+record_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#selected-tests-previous-clinic-consultations-card .card-body").html(messages);
          $("#selected-tests-previous-clinic-consultations-card").show();
          $(".tests-table").DataTable();
          $("#edit-previous-clinic-consultation-card").hide();
        }else{
           $.notify({
          message: "No Data To Display"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
  }

  function goBackFromPreviousClinicConsultationsSelectedTestsCard (elem,evt) {
    
    $("#selected-tests-previous-clinic-consultations-card").hide();
    
    $("#edit-previous-clinic-consultation-card").show();
  }

  function goBackFromEditPreviousClinicConsultationForm (elem,evt) {
    $("#previous-clinic-consultations-card").show();
    $("#off-appointment-patient-card").hide();
  }

  function viewSubTests1PreviousConsultation(elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    
    evt.preventDefault()
    // console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_sub_tests_clinic_previous_consultations'); ?>";

    var form_data = {
      "show_records" : true,
      "record_id" : record_id,
      "initiation_code" : initiation_code,
      "lab_id" : lab_id,
      "health_facility_id" : health_facility_id,
      "main_test_id" : main_test_id,
      "sub_dept_id" : sub_dept_id
    }
    if(isward && type != null){
      form_data.type = "ward1";
    }else if(isward){
      form_data.type = "ward";
    }

    console.log(form_data)
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : form_data,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.main_test_name != ""){
          var messages = response.messages;
          var main_test_name = response.main_test_name;

          
          var card_name = "selected-sub-tests-previous-clinic-consultations-card";
          
          

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Selected Sub Tests Under " +main_test_name);
          $("#selected-tests-previous-clinic-consultations-card").hide();
          
          $("#"+card_name).show();
          
          $(".tests-table").DataTable();
          
        }else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
    
  }

  function goBackFromPreviousClinicConsultationsSelectedSubTestsCard (elem,evt) {
    var card_name = "selected-sub-tests-previous-clinic-consultations-card";
    $("#selected-tests-previous-clinic-consultations-card").show();
    
    $("#"+card_name).hide();
  }

  function viewTestResultsPreviousConsultation (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          
          var card_name = "view-results-previous-clinic-consultations-card"; 
          
          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Ready Result For " +test_name);
          $("#selected-tests-previous-clinic-consultations-card").hide();
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromPreviousClinicConsultationsViewResultsCard (elem,evt) {
    var card_name = "view-results-previous-clinic-consultations-card"; 
          
    
    $("#selected-tests-previous-clinic-consultations-card").show();
    $("#"+card_name).hide();
  }

  function viewTestResultImagesPreviousConsultation (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          
          var card_name = "view-results-images-previous-clinic-consultations-card"; 
          
          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Result Images For " +test_name);
          $("#selected-tests-previous-clinic-consultations-card").hide();
          
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromPreviousClinicConsultationsViewResultsImagesCard (elem,evt) {
    var card_name = "view-results-images-previous-clinic-consultations-card"; 
          
    
    $("#selected-tests-previous-clinic-consultations-card").show();
    
    $("#"+card_name).hide();
          
  }

  function viewTestResultsSubPreviousConsultations (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          
          var card_name = "view-tests-results-previous-clinic-consultations-card"; 

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Ready Result For " +test_name);
          $("#selected-sub-tests-previous-clinic-consultations-card").hide();
          
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromPreviousClinicConsultationsViewTestsResultsCard (elem,evt) {
    var card_name = "view-tests-results-previous-clinic-consultations-card"; 

    
    $("#selected-sub-tests-previous-clinic-consultations-card").show();
    
    $("#"+card_name).hide();
  }

  function viewTestResultImagesSubPreviousConsultations (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    if(isward){
      var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    }else{
      var record_id = $(".record_id").val();
    }
    console.log(record_id)
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        "record_id" : record_id,
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          
          var card_name = "view-tests-results-images-previous-clinic-consultations-card"; 
          

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Result Images For " +test_name);
          $("#selected-sub-tests-previous-clinic-consultations-card").hide();
          
          $("#"+card_name).show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromPreviousClinicConsultationsViewTestsResultsImagesCard (elem,evt) {
    var card_name = "view-tests-results-images-previous-clinic-consultations-card"; 
    
    
    $("#selected-sub-tests-previous-clinic-consultations-card").show();
    
    $("#"+card_name).hide();
  }

  function viewPreviouslySelectedDrugsPreviousClinicConsultations (elem,evt,record_id) {
    $(".spinner-overlay").show(); 
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_clinic_previous_consultation'); ?>";
    $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "record_id="+record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#edit-previous-clinic-consultation-form").hide();
            $("#selected-drugs-previous-clinic-consultations-card .card-body").html(messages);
            $("#selected-drugs-previous-clinic-consultations-card .selected-drugs-table").DataTable();
            $("#selected-drugs-previous-clinic-consultations-card").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Sorry Something Went Wrong",
              type: 'error'
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

  function goBackFromPreviousClinicConsultationsSelectedDrugsCard (elem,evt) {
    $("#edit-previous-clinic-consultation-form").show();
    $("#selected-drugs-previous-clinic-consultations-card").hide();
  }

  function viewPreviousWardConsultations (elem,evt) {
    console.log(patient_hospital_number)
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_prvevious_ward_consultations_for_patient'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&hospital_number="+patient_hospital_number,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#choose-consultation-to-display-card").hide();
          $("#previous-ward-consultations-card .card-body").html(messages);
          $("#previous-ward-consultations-card #previous-ward-consultations-table").DataTable();
          $("#previous-ward-consultations-card").show();
        }else if(response.no_records){
          $.notify({
          message:"No Records To Display For This Patient Here"
          },{
            type : "warning"  
          });
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

  function goBackFromPreviousWardConsultationsCard (elem,evt) {
    $("#choose-consultation-to-display-card").show();
    
    $("#previous-ward-consultations-card").hide();
  }

  function loadPatientsPreviousWardConsultation1(elem,evt,ward_record_id){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_prvevious_ward_consultations_for_patient1'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&ward_record_id="+ward_record_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;
          $("#previous-ward-consultations-card").hide();
          $("#previous-ward-consultations-info-card .card-body").html(messages);
          $("#previous-ward-consultations-info-card #previous-ward-consultations-table").DataTable();
          $("#previous-ward-consultations-info-card").show();
        }else if(response.no_records){
          $.notify({
          message:"No Records To Display For This Patient Here"
          },{
            type : "warning"  
          });
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

  function goBackFromPreviousWardConsultationsInfoCard (elme,evt) {
    $("#previous-ward-consultations-card").show();
   
    $("#previous-ward-consultations-info-card").hide();
  }

  function loadPatientsPreviousWardConsultationInfo(elem,event,id){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_prvevious_ward_consultations_for_patient_info'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&id="+id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-previous-ward-consultation-card #edit-previous-ward-consultation-form #" +key).val(value);
          }
          $("#edit-previous-ward-consultation-card #edit-previous-ward-consultation-form").attr("data-id",id);
          $("#previous-ward-consultations-info-card").hide();
          $("#edit-previous-ward-consultation-card").show();
        }else if(response.no_records){
          $.notify({
          message:"No Records To Display For This Patient Here"
          },{
            type : "warning"  
          });
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

  function goBackFromEditPreviousWardConsultationForm (elemm,evt) {
    $("#previous-ward-consultations-info-card").show();
    $("#edit-previous-ward-consultation-card").hide();
  }

  function goDefault(){
    document.location.reload();
  }

  function goBackEditBio() {
    $("#edit-patient-bio-data-card").hide();
    $("#previously-registered-patients-card").show();
  }

  function loadPatientBioDataEdit(id) {
    $(".spinner-overlay").show();
    consultation_id = id;
    // console.log(consultation_id)
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          patient_user_id = response.patient_user_id;
          patient_full_name = response.patient_full_name;
          var registration_num = response.registration_num;
          $("#off-appointment-patient-card .card-title-1").html("Perform Functions On Patient <br>Patient Name: <em class='text-primary'>"+patient_full_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>");

          $("#off-appointment-patient-card .vital-signs-form input").val("");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-patient-activity-records-edit'); ?>";
    
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "show_records=true&consultation_id="+consultation_id,
            success : function (response1) {
              if(response1.success == true && response1.messages.length != 0){
                var messages = response1.messages;
                for (const [key, value] of Object.entries(messages)) {
                  $("#off-appointment-patient-card #vital-signs-form #" +key).val(value);
                }
              }
            },
            error : function () {
              $.notify({
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });
            }
          });
          $("#off-appointment-patient-card #go-back").attr("onclick","goBackOffAppointmentPatientCardOnAppointment(this,event)");
          $("#off-appointment-patient-card").show();
          $("#new-patients-card").hide();
          $("#off-appointments-card").hide();
          $("#on-appointment-card").hide();
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

  function goBackOffAppointmentPatientCardOnAppointment (elem,evt) {
    $("#off-appointment-patient-card").hide();
    $("#on-appointment-card").show();
  }

  function loadPreviousTransactions(elem) {
    $("#carryOutTransaction").modal("hide");
    $("#main-card").hide();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get-initiation-codes') ?>";
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
        $("#initiation-codes-card .card-body").append(response);
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

  function deleteTest (elem) {
    var td = elem.parentElement;
    var tr = td.parentElement;
    var tr_id = tr.getAttribute("id");
    var table = document.getElementById("example");
    console.log(tr_id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/remove-test') ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "remove_test=true&id="+tr_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.values_messed == true){
          $.notify({
          message:"Please Do Not Mess With This Page"
          },{
            type : "warning"  
          });
        }else if (response.error == true) {
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          });
        }else if(response.success == true){
         tr.remove();
         // table.draw();
         var val = tr.querySelector(".test-cost").innerHTML;
         var old_total = document.getElementById("total-main").innerHTML;
        
         var new_total = old_total - val;
         document.getElementById("total-main").innerHTML = new_total;
         new_total = addCommas(new_total);

         document.getElementById("total-display").innerHTML = "₦ " + new_total;

        }
      },
      error : function () {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function proceed (status,patient_user_name,patient_name1) {

    patientCheckBoxEvt();
    
    var total = $("#select-test-card input[type=checkbox]:checked").length;
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    
    $("#select-test-card input[type=checkbox]:checked").each(function(){
      sum += parseInt($(this).attr("rel"));
    });

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
          var submit_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug.'/pathology-laboratory-services/'.$sub_dept_slug.'/receptionist/submit_tests2') ?>";
          
          var clinic_slug = "<?php echo $addition; ?>";
          $("#select-test-card input[type=checkbox]:checked").each(function(){
            var main_test_id = $(this).attr("data-main-test-id");
            var test_id = $(this).attr("data-testid");
            var test_name = $(this).attr("data-testname");
            var test_cost = $(this).attr("rel");
            var ta_time = $(this).attr("data-testta");
            var initiation_code = $("#var-dump").html();
            var patient_name = $("#patient-name").html();
            
            patient_name = $.trim(patient_name);
            if(status == "new_patient"){    
              var value = {
                "status" : "new_patient",
                "main_test_id" : main_test_id,
                "test_id": test_id,
                "test_name":test_name,
                "test_cost" : test_cost,
                "ta_time" : ta_time,
                "initiation_code" : initiation_code,
                "patient_name" : patient_name,
                "sub_dept_id" : sub_dept_id,
                "clinic_slug" : clinic_slug
              };
            } 
            else if(status == "registered_patient"){
              if(patient_user_name !== ""){
                 var value = {
                  "status" : "registered_patient",
                  "patient_user_name" : patient_user_name,
                  "main_test_id" : main_test_id,
                  "test_id": test_id,
                  "test_name":test_name,
                  "test_cost" : test_cost,
                  "ta_time" : ta_time,
                  "initiation_code" : initiation_code,
                  "patient_name" : patient_name1,
                  "sub_dept_id" : sub_dept_id,
                  "clinic_slug" : clinic_slug
                };
              }
            }

            $(".spinner-overlay").show();
            $.ajax({
              url : submit_tests_url,
              type : "POST",
              responseType : "text",
              dataType : "text",
              data : {data:value},
              success : function (response) {
                console.log(response)
               if(response == 1){
                $(".spinner-overlay").hide();
                swal({
                  type: 'success',
                  title: 'Successful',
                   allowOutsideClick : false,
                      allowEscapeKey :false,
                  text: 'The Tests Have Been Added Successfully. Your initiation code is <b>' + initiation_code +'</b>. Please Copy It Down.'
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
            // $("#submit-form").append("<input type='hidden' name='test[]' value='"+ value + "'>");
            // $("#submit-form").submit();
          });  
          
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

  function onlinePayment(elem,total_amount,sub_total_amount,facility_slug,initiation_code) {
    console.log(initiation_code)
    swal({
      title: 'Continue?',
      text: "<p class='text-primary' id='num-tests-para'>Amount For Tests: ₦" + addCommas(total_amount) +".</p>" +
        "<p class='text-primary'>Vat: 7%</p>" +
        "<p class='text-primary'>Sub Total: ₦"+addCommas(sub_total_amount)+"</p>" +
       " Do Want To Continue To Payment?",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      // setCookie("initiation_code",initiation_code,5);
      $(".spinner-overlay").show();
      // console.log(facility_slug)
     window.location.assign("<?php echo site_url('onehealth/index/'); ?>"+facility_slug+"/test_online_payment/clinic/"+"<?php echo $addition .'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/'; ?>"+initiation_code);
    }); 
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

  function goBackFromSelectedDrugs (elem,evt) {
    $("#new-consultation-form").show();
    
    $("#selected-drugs-card").hide();
  }

  function viewPreviouslySelectedDrugs (elem,evt) {
    $(".spinner-overlay").show(); 
    var record_id = $("#off-appointment-patient-card .record_id").val(); 
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_clinic'); ?>";
    $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "consultation_id="+consultation_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#new-consultation-form").hide();
            $("#selected-drugs-card .card-body").html(messages);
            $("#selected-drugs-card .selected-drugs-table").DataTable();
            $("#selected-drugs-card").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Sorry Something Went Wrong",
              type: 'error'
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
    var health_facility_id = elem.getAttribute("data-facility-id");
    var sub_dept_slug = elem.getAttribute("data-sub-dept-slug");
    var dept_slug = elem.getAttribute("data-dept_slug");
  
    var value = {
      "dept_slug" : dept_slug,
      "sub_dept_slug" : sub_dept_slug,
      "facility_id" : health_facility_id,
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


  function proceed1 (status,patient_user_name,patient_name1,record_id,type = null) {

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
        var i = 0;
        var form_data = [];
        
        var patient_name = $("#patient-name").val();

        
        patient_name = $.trim(patient_name);  
          
          var health_facility_slug = tests_selected_obj[0].facility_slug;
          var dept_slug = tests_selected_obj[0].dept_slug;
          var sub_dept_slug = tests_selected_obj[0].sub_dept_slug;
          var submit_tests_url = "<?php echo site_url('onehealth/index/') ?>" + health_facility_slug + '/' + dept_slug + '/' + sub_dept_slug + '/' +'receptionist/submit_tests2';
          var clinic_slug = "<?php echo $addition; ?>";
          for(var q = 0; q > tests_selected_obj.length; q++){
            tests_selected_obj[q].clinic_slug = clinic_slug;
          }
          $(".spinner-overlay").show(); 

          var obj = {
            data : tests_selected_obj
          }
          console.log(obj)
          
          $.ajax({
              url : submit_tests_url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : obj,
              success : function (response) {
                console.log(response)
               if(response.success && response.initiation_code != ""){
                var initiation_code = response.initiation_code;
                var form_data = {
                  'facility_slug' : tests_selected_obj[0].facility_slug,
                  'initiation_code' : response.initiation_code,
                  'record_id' : tests_selected_obj[0].record_id,
                  'patient_user_name' : tests_selected_obj[0].patient_user_name
                };
                if(type == "ward"){
                  var ward_id = $("#choose-action-patient-modal").attr("data-id");
                  if(ward_id !== ""){
                    $(".spinner-overlay").show();

                    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
                    form_data.view_records = true;
                    form_data.type = "save_admission_tests";
                    form_data.id = ward_id;

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
                          $(".spinner-overlay").hide();
                          swal({
                            type: 'success',
                            title: 'Successful',
                            allowOutsideClick : false,
                            allowEscapeKey :false,
                            text: 'The Tests Have Been Added Successfully. Initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + initiation_code+ '\')">' + initiation_code +'</b>. Click Initiation Code To Copy. Initiation Code Has Been Sent To The Associated User With Tests Selected And Initiation Code To Proceed To Selected Facility For Payment.'
                            // footer: '<a href>Why do I have this issue?</a>'
                          }).then((result) => {
                            document.location.reload();
                          });
                        }else if(response.invalid_id){
                          swal({
                            title: 'Ooops',
                            text: "Something Went Wrong",
                            type: 'error'
                          })
                        }else if(response.no_records){
                          
                          $("#wards-patients-card").hide();
                          $("#choose-action-patient-modal").modal("hide");
                          $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
                          $("#clinical-notes-card").show();
                          $("#add-clinical-note-btn").show("fast");
                        }else{
                          swal({
                            title: 'Ooops',
                            text: "Something Went Wrong",
                            type: 'warning'
                          })
                        }
                      },error : function () {
                        $(".spinner-overlay").hide();
                        swal({
                          title: 'Ooops',
                          text: "Sorry Something Went Wrong",
                          type: 'error'
                        })
                      } 
                    });   
                  }else{
                    swal({
                      title: 'Ooops',
                      text: "Sorry Something Went Wrong",
                      type: 'error'
                    })
                  }
                }else{
                  var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_tests_selected_by_dr') ?>"
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    // async : false,
                    data : form_data,
                    success : function (response) { 
                      if(response.success){
                        $(".spinner-overlay").hide();
                        swal({
                          type: 'success',
                          title: 'Successful',
                          allowOutsideClick : false,
                          allowEscapeKey :false,
                          text: 'The Tests Have Been Added Successfully. Initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + initiation_code+ '\')">' + initiation_code +'</b>. Click Initiation Code To Copy. Initiation Code Has Been Sent To The Associated User With Tests Selected And Initiation Code To Proceed To Selected Facility For Payment.'
                          // footer: '<a href>Why do I have this issue?</a>'
                        }).then((result) => {
                          $("#select-test-card").hide();
                          $("#other-facility-select-test-card").hide();
                          // $("#drs-functions-card").show();
                          $("#new-consultation-form").show();
                          $.notify({
                          message:"Tests Selected Successfully"
                          },{
                            type : "success"  
                          });
                        }); 
                      } 
                      
                    },error : function () {
                       $(".spinner-overlay").hide();
                    }
                  }); 
                }
                
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
          $("#off-appointment-patient-card").hide();
          
          $("#sub-tests-card").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function goBackSubTests1Ward (elem,evt) {
    $("#sub-tests-card-ward .card-title").html("");
    $("#sub-tests-card-ward .card-body").html("");

    $("#other-facility-select-test-card-ward").show();
    
    $("#sub-tests-card-ward").hide();
  }

  function viewTestSubTestsWard (elem,e,url) {
    
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
          $("#sub-tests-card-ward .card-title").html(card_header_str);
          $("#sub-tests-card-ward .card-body").html(messages);

          $("#sub-tests-table").DataTable();
          $("#other-facility-select-test-card-ward").hide();
          
          $("#sub-tests-card-ward").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function selectThisFacility(elem,evt,type){
     evt.preventDefault();
     console.log(consultation_id)
     
     var facility_slug = "<?php echo $addition; ?>";
     
      // var patient_user_name = document.getElementById("user_name").innerHTML;
     var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests";
      patient_name = $.trim(patient_name);
      
     
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others&consultation_id="+consultation_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(messages !== ""){  
            $("#select-lab-card").hide(); 
            $("#select-lab-to-use-card").hide();
            $("#select-lab-to-use-card-2").hide();   
            $("#other-facility-select-test-card .card-body").html(messages);
            $("#other-facility-select-test-card").show();
            $(".select-test-table").DataTable();
            $("#proceed-from-tests-selection-btn").show("fast");
          }

          
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

  function selectThisFacilityWard(elem,evt){
    evt.preventDefault();
    
    if(ward_record_id !== ""){
      
      var facility_id = "<?php echo $health_facility_id; ?>";
      var facility_name = "<?php echo $health_facility_name; ?>";
      var facility_slug = "<?php echo $health_facility_slug; ?>";
     
      var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests";
      
      if(patient_name != ""){
      
        $(".spinner-overlay").show();

        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "type=others&consultation_id="+consultation_id+"&ward=true",
          success : function (response) {
            $(".spinner-overlay").hide();
            var messages = response.messages;
            console.log(messages)
            if(messages !== ""){  
              $("#select-lab-to-use-card-ward").hide();
              $("#other-facility-select-test-card-ward .card-body").html(messages);
              $("#other-facility-select-test-card-ward").show();
              $("#other-facility-select-test-card-ward .table").DataTable();
              $("#proceed-from-tests-selection-btn-ward").show("fast");
            }
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
      }else{
        swal({
          title: 'Ooops',
          text: "Sorry Something Went Wrong",
          type: 'error'
        })
      }
         
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function proceedFromTestsSelectionWard (elem,evt) {

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
        $("#other-facility-select-test-card-ward").hide();
        $("#additional-information-on-tests-selected-card-ward").show("fast");
        $("#proceed-from-tests-selection-btn-ward").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn-ward").show("fast");
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

  function goBackFromAdditionalInformationOnTestsSelectedCardWard (elem,evt) {
    $("#other-facility-select-test-card-ward").show();
    $("#additional-information-on-tests-selected-card-ward").hide("fast");
    $("#proceed-from-tests-selection-btn-ward").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn-ward").hide("fast");
  }

  function proceedFromAdditionalTestsSelectedWard(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-card-ward #additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_ward_dr') ?>";
    

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      consultation_id : consultation_id,
      ward_record_id : ward_record_id
    }
    console.log(obj)

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn-ward").hide("fast");
              $("#additional-information-on-tests-selected-card-ward").hide();
              currentTests(this,event)
              window.scrollTo(0,document.body.scrollHeight);
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

  function submitPatientNameForm1 (status,sub_dept_slug) {
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
    if(status == "new_patient"){
      var patient_name = $("#patient-name").val();
    }else if(status == "registered_patient"){
      var patient_name = document.getElementById("name").innerHTML;
      var patient_user_name = document.getElementById("user_name").innerHTML;
    }
    var facility_slug = getCookie("facility_slug");
    var get_tests_url = "<?php echo site_url('onehealth/index/')?>"+facility_slug+"/pathology-laboratory-services/" + sub_dept_slug + "/receptionist/get_tests2";
    patient_name = $.trim(patient_name);
    
    
      if(format.test(patient_name)){
        $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 
      }else{
        $(".spinner-overlay").show();

        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_tests=true",
          success : function (response) {
           console.log(response)
            $(".sub-dept-tabs").show();
            $(".spinner-overlay").hide();
            $("#other-facility-select-test-card #"+sub_dept_slug+ "1 .row").html(response)
            $("#initiate-patient-modal").modal('hide');
            $("#other-facility-select-test-card .welcome-heading").html("Select Required Tests For " + patient_name); 
            $("#other-facility-select-test-card #" + sub_dept_slug + "1 .pills").after("<p style='font-style:italic;' class='text-primary col-sm-12' id='info'>Select Required Tests And Click Proceed To Continue.</p>")             
           

            if(status == "registered_patient"){
              $("#other-facility-select-test-card .proceed").attr('onclick' , "proceed('registered_patient','"+patient_user_name+"','"+patient_name+"')")
            }else if(status == "new_patient"){
              $("#other-facility-select-test-card .proceed").attr('onclick' , "proceed('new_patient','','')")
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

  function submitPatientNameForm (status,sub_dept_slug) {
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
    if(status == "new_patient"){
      var patient_name = $("#patient-name").val();
    }else if(status == "registered_patient"){
      var patient_name = document.getElementById("name").innerHTML;
      var patient_user_name = document.getElementById("user_name").innerHTML;
    }
    var get_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug.'/pathology-laboratory-services/'); ?>" + sub_dept_slug + "/receptionist/get_tests2";
    patient_name = $.trim(patient_name);
    
    
      if(format.test(patient_name)){
        $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 
      }else{
        $(".spinner-overlay").show();

        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_tests=true",
          success : function (response) {
           
            $(".sub-dept-tabs").show();
            $(".spinner-overlay").hide();
            $("#"+sub_dept_slug+ " .row").html(response)
            $("#initiate-patient-modal").modal('hide');
            $(".welcome-heading").html("Select Required Tests For " + patient_name); 
            $("#" + sub_dept_slug + " .pills").after("<p style='font-style:italic;' class='text-primary col-sm-12' id='info'>Select Required Tests And Click Proceed To Continue.</p>")             
           

            if(status == "registered_patient"){
              $("#select-test-card .proceed").attr('onclick' , "proceed('registered_patient','"+patient_user_name+"','"+patient_name+"')")
            }else if(status == "new_patient"){
              $("#select-test-card .proceed").attr('onclick' , "proceed('new_patient','','')")
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

  function openPatientBioDataForm(elem,evt) {
    evt.preventDefault();
    $("#main-card").hide();
    $("#patient-bio-data").show();
  }

  function inputVitalSigns(elem,evt) {
    $("#main-card").hide();
    $("#choose-action-card").show();
  }

  function offAppointment(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_off_appointments_clinic_doctor'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){
          $("#off-appointments-card .card-body").html(response.messages);
          $("#off-appointments-card #off-appointment-patients-table").DataTable();
          $("#choose-action-card").hide();
          $("#off-appointments-card").show();
        }
        else{
         $.notify({
          message: "Sorry Something Went Wrong"
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

  function goBackFromOffAppointmentsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#off-appointments-card").hide();
  }

  function newPatients(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_new_patients_records_doctor'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){
          $("#new-patients-card .card-body").html(response.messages);
          $("#new-patients-card #new-patients-table").DataTable();
          $("#choose-action-card").hide();
          $("#new-patients-card").show();
        }
        else{
         $.notify({
          message: "Sorry Something Went Wrong"
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

  function goBackFromNewPatientsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#new-patients-card").hide();
  }

  function selectedWard (elem,evt,id) {
    console.log(id)
    swal({
      title: 'Warning',
      text: "Are You Sure You Want To Admit Patient In This Ward?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      $(".spinner-overlay").show();
          
      var url = $("#new-consultation-form").attr("action");
      var values = $("#new-consultation-form").serializeArray();
      values = values.concat({
        "name" : "consultation_id",
        "value" : consultation_id
      })
      values = values.concat({
        "name" : "admit_to_ward",
        "value" : true
      })
      values = values.concat({
        "name" : "ward_id",
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
          if(response.success == true && response.ward_success == true){
            $.notify({
            message:"Patient Has Been Successfully Admitted To The Ward"
            },{
              type : "success"  
            });
            $(".form-error").html("");
            setTimeout(goDefault,1000);
          }else if(response.success == true && response.ward_success == false){
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
            message:"Valid Values Need To Be Entered In Consultation Fields To Proceed"
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
    });
  }

  function goBackAdmitToWard (elem,evt) {
    elem = $(elem);
    elem.hide();
    $("#admit-to-ward").show();
    
    $("#select-ward-div").hide();
    
  }

  function admitToWard (elem,evt) {
    elem = $(elem);
    elem.hide();
    $("#go-back-admit-to-back").show();
    
    $("#select-ward-div").show();
    $("#new-consultation-form #future-appointment").val("");
  }

  function goBackReferOrConsult (elem,evt) {
    $(elem).hide();
    $("#refer-consult-btn").show();
    
    $("#select-facility-for-referral-div").hide();
  }

  function selectThisClinicForReferral (elem,evt,id) {
    var clinic_name = $(elem).attr("data-name");
    var facility_name = $(elem).attr("data-facility-name");
    var facility_id = $(elem).attr("data-facility-id");

    $("#reason-for-referral-form").attr("data-clinic-name",clinic_name);
    $("#reason-for-referral-form").attr("data-facility-name",facility_name);
    $("#reason-for-referral-form").attr("data-facility-id",facility_id);
    $("#reason-for-referral-form").attr("data-clinic-id",id);
    $("#enter-reason-for-referral-modal").modal("show");
  }



  function referOrConsult(elem,evt){
    <?php if($clinic_structure == "standard"){ ?>
    swal({
      title: 'Choose Action',
      text: "Do You Want To Perform: ",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Referral',
      cancelButtonText: 'Consult'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_other_registered_hospitals'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $(elem).hide();
            $("#go-back-refer-consult").show();
            $("#select-facility-for-referral-div").html(messages);
            $("#select-facility-for-referral-div #other-facilities-for-referral-table").DataTable();
            $("#select-facility-for-referral-div").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinics_in_facility_consult'); ?>";
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "",
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $(elem).hide();
              // $("#go-back-refer-consult").show();
              
              
              // $("#select-facility-for-referral-div").hide();
              $("#select-clinic-for-consult-div").html('');
              $("#select-clinic-for-consult-div").append('<button id="go-back-select-clnic-for-consult" onclick="goBackSelectClinicForConsult(this,event)" type="button" class="btn btn-warning">Go Back</button>');
              $("#select-clinic-for-consult-div").append('<h5>Select Clinic For Consult</h5>');
              $("#select-clinic-for-consult-div").append(messages);
              
              $("#select-clinic-for-consult-div #other-facilities-clinics-for-consult-table").DataTable({
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
              });
              $("#select-clinic-for-consult-div").show();
            }else{
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
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
    });
    <?php }else{ ?>
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_other_registered_hospitals'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $(elem).hide();
          $("#go-back-refer-consult").show();
          $("#select-facility-for-referral-div").html(messages);
          $("#select-facility-for-referral-div #other-facilities-for-referral-table").DataTable();
          $("#select-facility-for-referral-div").show();
        }else{
          swal({
            title: 'Ooops',
            text: "Something Went Wrong",
            type: 'warning'
          })
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
    <?php } ?>
  }

  function goBackSelectClinicForConsult (elem,evt) {
    $(elem).hide();
    $("#select-clinic-for-consult-div").html('');
    $("#select-clinic-for-consult-div").hide();
    $("#refer-consult-btn").show();
  }

  function loadFacilityClinicsForReferral(elem,evt,id){
    var facility_name = $(elem).attr("data-name");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinics_in_facility'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "facility_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#go-back-refer-consult").hide();
            $("#select-facility-for-referral-div").hide();
            $("#select-clinic-for-referral-div").html('');
            $("#select-clinic-for-referral-div").append('<button id="go-back-select-clnic-for-referral" onclick="goBackSelectClinicForReferral(this,event)" type="button" class="btn btn-warning">Go Back</button>');
            $("#select-clinic-for-referral-div").append('<h5>Select Clinic From '+facility_name+' For Referral</h5>');
            $("#select-clinic-for-referral-div").append(messages);
            
            $("#select-clinic-for-referral-div #other-facilities-clinics-for-referral-table").DataTable({
              aLengthMenu: [
                  [25, 50, 100, 200, -1],
                  [25, 50, 100, 200, "All"]
              ],
              iDisplayLength: -1
            });
            $("#select-clinic-for-referral-div").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
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

  function goBackSelectClinicForReferral (elem,evt) {
    $(elem).hide();
    $("#go-back-refer-consult").show();
    $("#select-facility-for-referral-div").show();
    $("#select-clinic-for-referral-div").html('');
    
    $("#select-clinic-for-referral-div").hide();
  }

  function openPatientsInWard(elem,evt){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_patients_in_ward'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#wards-with-patients-card .card-body").html(messages);
          $("#choose-action-card").hide();
          $("#wards-with-patients-card .card-title").html("Wards With Patients In Them");
          $("#wards-with-patients-card").show();
          $("#wards-with-patients-card .table-test").DataTable();
        }else{
          $.notify({
          message:"No Patient In The Ward"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackFromWardsWithPatientsCard (elem,evt) {
    $("#choose-action-card").show();
          
    $("#wards-with-patients-card").hide();
  }

  function openWardPatients(elem,evt,ward_id) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_patients_in_one_ward'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "ward_id="+ward_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#wards-patients-card .card-body").html(messages);
          $("#wards-with-patients-card").hide();
          $("#wards-patients-card").show();
          $("#wards-patients-card .table-test").DataTable();
        }else if(!response.no_patient){
          $.notify({
          message:"No Patient In This Ward"
          },{
            type : "warning"  
          });
        }else{
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackFromWardPatientsCard (elem,evt) {
    $("#wards-with-patients-card").show();
    $("#wards-patients-card").hide();
  }

  function goBackMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#medication-chart-card").hide();
  }

  function onAdmissionDrugs (elem,evt) {
    if(ward_record_id != "" && consultation_id != ""){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_clinic'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "consultation_id="+consultation_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#on-admission-drugs-card .card-body").html(messages);
            $("#on-admission-drugs-card").show();
            $("#on-admission-drugs-card .selected-drugs-table").DataTable();
            $("#medication-chart-card").hide();
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOnAdmissionDrugs (elem,evt) {
    $("#on-admission-drugs-card").hide();
    $("#medication-chart-card").show();
  }

  function currentDrugs (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_ward'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "ward_record_id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#current-drugs-card .card-body").html(messages);
            $("#current-drugs-card").show();
            $("#current-drugs-card .selected-drugs-table").DataTable();
            $("#medication-chart-card").hide();
            $("#request-new-drugs-btn").show("fast");
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadCurrentDrugsInfo (elem,evt) {
    var ward_record_id = $("#choose-action-patient-modal").attr("data-id");
    var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code !== ""){
      $(".spinner-overlay").show();
        
       
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_drug_selection_by_init_code_ward'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "ward_record_id="+ward_record_id+"&initiation_code="+initiation_code,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#current-drugs-card-info .card-body").html(messages);
            $("#current-drugs-card-info").show();
            $("#current-drugs-card-info .selected-drugs-table").DataTable();
            $("#current-drugs-card").hide();
            $("#request-new-drugs-btn").hide("fast");
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackCurrentDrugsInfo (elem,evt) {
    $("#current-drugs-card-info").hide();
    $("#current-drugs-card").show();
    $("#request-new-drugs-btn").show("fast");
  }

  function goBackCurrentDrugs (elem,evt) {
    $("#current-drugs-card").hide();
    $("#medication-chart-card").show();
    $("#request-new-drugs-btn").hide("fast");
  }

  function requestNewDrugs(elem,evt) {
    $("#current-drugs-card").hide();
    $("#select-pharmacy-to-use-card-ward").show();
    $("#request-new-drugs-btn").hide("fast");
  }

  function goBackFromSelectPharmacyToUseWard (elem,evt) {
    $("#current-drugs-card").show();
    $("#select-pharmacy-to-use-card-ward").hide();
    $("#request-new-drugs-btn").show("fast");
  }

  function goBackSelectDrugsWard (elem,evt) {
    user_info = [];
    selected_drugs = [];
    $("#select-drugs-card-ward .card-title").html("");
    
    
    $("#select-pharmacy-to-use-card-ward").show();
    
    $("#select-drugs-card-ward").hide();
    $("#select-drugs-proceed-btn-ward").hide("fast");
  }

  function selectThisPharmacyWard(elem,evt){
    evt.preventDefault();
    if(ward_record_id != ""){
     
      var tr = elem.parentElement.parentElement;
      var facility_id = <?php echo $health_facility_id; ?>;
      var facility_name = "<?php echo $health_facility_name; ?>";
      var facility_slug = "<?php echo $health_facility_slug; ?>";
     
      var patient_name = document.getElementById("name").innerHTML;
      // var patient_user_name = document.getElementById("user_name").innerHTML;
      var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
      
     
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          ward_record_id : ward_record_id,
          host_facility_id : <?php echo $health_facility_id; ?>
        },
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card-ward .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card-ward .card-body").html(messages);
            $("#select-drugs-card-ward").attr("data-facility-id",facility_id);
            $("#select-pharmacy-to-use-card-ward").hide();
            $("#select-drugs-card-ward #select-drugs-table").DataTable();
            $("#select-drugs-card-ward").show();
            $("#select-drugs-proceed-btn-ward").show("fast");
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

  function selectDrugsProceedWard(elem,evt) {
    console.log(selected_drugs);
    console.log(user_info);
    if(selected_drugs.length > 0){
      var selected_drugs_info_html = "<div class='container-fluid'>";
      var j = 0;
      <?php 
        $attr = array('id' => 'prescription-data-form');
      ?>
      selected_drugs_info_html += "<div id='prescription-data-div'>";
      for(var i = 0; i < selected_drugs.length; i++){
        var id = selected_drugs[i].id;
        var price = selected_drugs[i].price;
        var brand_name = selected_drugs[i].brand_name;
        var generic_name = selected_drugs[i].generic_name;
        var formulation = selected_drugs[i].formulation;
        var strength = selected_drugs[i].strength;
        var strength_unit = selected_drugs[i].strength_unit;
        var main_store_quantity = selected_drugs[i].main_store_quantity;
        var dispensary_quantity = selected_drugs[i].dispensary_quantity;
        var unit = selected_drugs[i].unit;

        j++;
        selected_drugs_info_html += j + ". ";
        
        
        selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";
        selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
        selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
        selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
        selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
        selected_drugs_info_html += "<input class='form-control dosage' type='number' onkeyup='dosageEvent(this,event,"+i+",1)'>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
        selected_drugs_info_html += "<input class='form-control frequency_num' type='number' onkeyup='frequencyEvent1(this,event,"+i+",1)'>";
        selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "<option value='minutely' selected>Minutely</option>";
        selected_drugs_info_html += "<option value='hourly'>Hourly</option>";
        selected_drugs_info_html += "<option value='daily'>Daily</option>";
        selected_drugs_info_html += "<option value='weekly'>Weekly</option>";
        selected_drugs_info_html += "<option value='monthly'>Monthly</option>";
        selected_drugs_info_html += "<option value='yearly'>Yearly</option>";
        selected_drugs_info_html += "<option value='nocte'>Nocte</option>";
        selected_drugs_info_html += "<option value='stat'>Stat</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
        selected_drugs_info_html += "<input class='form-control duration_num' type='number' onkeyup='durationEvent1(this,event,"+i+",1)'>";
        selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "option value='minutes' selected>Minutes</option>";
        selected_drugs_info_html += "<option value='hours'>Hours</option>";
        selected_drugs_info_html += "<option value='days'>Days</option>";
        selected_drugs_info_html += "<option value='weeks'>Weeks</option>";
        selected_drugs_info_html += "<option value='months'>Months</option>";
        selected_drugs_info_html += "<option value='years'>Years</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "</div>";
        
      }
      selected_drugs_info_html += "</div>";

      $("#select-drugs-card-ward").hide();
      $("#select-drugs-proceed-btn-ward").hide("fast");
      $("#select-drugs-proceed-btn-2-ward").show("fast");
      $("#selected-drugs-info-card-ward .card-title").html("Enter prescription Details For: " + user_info.full_name);
      $("#selected-drugs-info-card-ward .card-body").html(selected_drugs_info_html);
      $("#selected-drugs-info-card-ward").show();
      selected_drugs_info_html += "</div>";
    }else{
      swal({
       title: 'Sorry',
        text: "You Must Select At Least One Drug To Proceed",
        type: 'error'
      })
    }
  }

  function goBackSelectedInfoDrugsWard (elem,evt) {
    $("#select-drugs-card-ward").show();
    $("#select-drugs-proceed-btn-ward").show("fast");
    $("#select-drugs-proceed-btn-2-ward").hide("fast");
    
    $("#selected-drugs-info-card-ward").hide();
  }

  function selectDrugsProceed2Ward (elem,evt) {
    var proceed = false;
    
    var facility_id = $("#select-drugs-card-ward").attr("data-facility-id");

    for(var i = 0; i < selected_drugs.length; i++) { 
      var is_poison = selected_drugs[i].is_poison; 
      if(is_poison == 1){ 
        proceed = false;
        break; 
      }else{
        proceed = true;
      }
    }

    if(!proceed){
      if(user_info.clinician !== ""){
        proceed = true;
      }
    }

    console.log(proceed)
    
    // evt.preventDefault();
    if(proceed){
      var me = $("#prescription-data-div");
      var form_data = {
        drugs_info : []
      };
      
      var drugs_info = [];
      var num = 0;
      var sum = 0;
      var total_quantity = 0;
      me.find(".form-row").each(function(index, el) {
        var el = $(el);
        var id = el.attr("data-id");
        var price = selected_drugs[index].price;
        var dosage = el.find(".dosage").val();
        var frequency_num = el.find(".frequency_num").val();
        var frequency_time = el.find(".frequency_time").val();
        var duration_num = el.find(".duration_num").val();
        var duration_time = el.find(".duration_time").val();

        var obj = {
          id : id,
          frequency_num : frequency_num,
          frequency_time : frequency_time,
          duration_num : duration_num,
          duration_time : duration_time,
          dosage : dosage,
          price : price
        };

        drugs_info[index] = obj;

        if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
          num++;
          var prescription_obj = calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
          // console.log(prescription_obj)
          if(prescription_obj != {}){
            var quantity = prescription_obj.quantity;
            var total_price = prescription_obj.total_price;

            total_quantity += quantity;
            sum += total_price;
          }
        }

      });

      form_data['drugs_info'] = drugs_info;
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
      form_data["ward"] = true;

      form_data["ward_record_id"] = ward_record_id;
      form_data['host_facility_id'] = facility_id;
      


      console.log(form_data);
      // console.log(JSON.stringify(form_data))

      if(num > 0){
        swal({
          title: 'Proceed?',
          text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Proceed?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_drugs_selected_pharmacy'); ?>";
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
                message:"Drugs Selected Successfully"
                },{
                  type : "success"  
                });
                $("#selected-drugs-info-card-ward").hide();
                $("#select-drugs-proceed-btn-2-ward").hide("fast");
                
                selected_drugs = [];
                user_info = [];
                currentDrugs(this,event);
              }else{
                swal({
                 title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
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
      }else{
        swal({
          title: 'Warning! ',
          text: "You Must Enter Complete Prescription Info For At Least One Selected Drug To Proceed",
          type: 'error'
        })
      }
    }else{
      $("#enter-clinician-name-modal").modal("show");
    }
  }

  function goBackSelectPharmaciesWard (elem,evt) {
    $("#select-pharmacy-to-use-card-ward").show();
    $("#select-pharmacy-card-ward").hide();
  }

  function selectAnotherPharmacyWard (elem,evt) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_pharmacies_and_health_facilities'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&ward=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-pharmacy-to-use-card-ward").hide();
          $("#select-pharmacy-card-ward .card-body").html(response.messages);
          $("#select-pharmacy-card-ward").show();
          $("#select-pharmacy-card-ward #facilities-pharmacies-table").DataTable();
          LetterAvatar.transform();
        }else{
          $.notify({
          message:"No Records To Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    }); 
  }

  function selectThisFacilityPharmacyWard(elem,evt){
    evt.preventDefault();
    if(ward_record_id != ""){
      var tr = elem.parentElement.parentElement;
      var facility_id = tr.getAttribute("data-id");
      var facility_name = tr.getAttribute("data-facility-name");
      var facility_slug = tr.getAttribute("data-slug");
      
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug. '/') ?>" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
        
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "ward_record_id="+ward_record_id+"&host_facility_id="+facility_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(response)
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card-ward .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card-ward .card-body").html(messages);
            $("#select-drugs-card-ward").attr("data-facility-id",facility_id);
            $("#select-pharmacy-card-ward").hide();
            $("#select-drugs-card-ward #select-drugs-table").DataTable();
            $("#select-drugs-card-ward").show();
            $("#select-drugs-proceed-btn-ward").show("fast");
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

  function loadMedicationChartForDrug (elem,evt,id) {
    var status  = $(elem).attr("data-status");
    if(status != ""){
      if(status == "dispatched"){
        $(".spinner-overlay").show();
        
        var initiation_code = $(elem).attr("data-initiation-code");
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_medication_chart_for_drug'); ?>";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true&ward_record_id="+ward_record_id+"&drug_selected_id="+id+"&initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.messages !== ""){
              $("#current-drugs-card-info").hide();
              $("#medication-chart-for-drug-card .card-body").html(response.messages);
              $("#medication-chart-for-drug-card").show();
              $("#medication-chart-for-drug-card #medication-chart-table").DataTable({
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
              });
              
            }else{
              $.notify({
              message:"No Records To Display"
              },{
                type : "warning"  
              });
            }
          },error :function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Please Check Your Internet Connection And Try Again"
            },{
              type : "danger"  
            });
          }
        });
      }else{
        swal({
          title: 'Warning',
          text: "This Drug Has To Be Dispensed And Dispatched By Pharmacy To View Medication Chart",
          type: 'error',
          
        })
      }
    } 
  }

  function goBackMedicationChartForDrug (elem,evt) {
    $("#current-drugs-card-info").show();
    $("#medication-chart-for-drug-card").hide();
  }

  function checkDrugAsGiven(elem,evt) {
    var id = $(elem).attr("data-id");
    console.log(id)
    swal({
      title: 'Warning',
      text: "You Are About To Mark This Drug As Given. Do You Want To Proceed?",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Proceed!',
      cancelButtonText : "Cancel"
    }).then(function(){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/check_drug_as_given'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success == true && response.date_given != "" && response.time_given != "" && response.personnel_link != ""){
            var date_given = response.date_given;
            var time_given = response.time_given;
            var personnel_link = response.personnel_link;

            $(elem).parents("tr").find(".date_given").html(date_given + " " + time_given);
            $(elem).parents("tr").find(".personnel_link").html(personnel_link);
            $(elem).parents("tr").find(".check").html("<em class='text-success'>Given!</em>");
            
           
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
            });
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(elem).prop('checked', false);
      }
    }); 
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
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_referrals_to_your_clinic_doctor'); ?>";
      
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
        swal({
          title: 'Choose Action',
          text: "Do You Want To View: ",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Clinic Consults',
          cancelButtonText: 'Ward Consults'
        }).then(function(){
          $(".spinner-overlay").show();
              
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_consult_to_your_clinic_doctor'); ?>";
          
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
            viewWardConsults();
          }
        });  
      }
    });
  }


  function viewWardConsults(){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_patients_in_ward_consults'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#wards-with-patients-card .card-body").html(messages);
          $("#choose-action-card").hide();
          $("#wards-with-patients-card .card-title").html("Consults Wards");
          $("#wards-with-patients-card").show();
          $("#wards-with-patients-card .table-test").DataTable();
        }else{
          $.notify({
          message:"No Data To Display Here"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function openWardPatientsConsults(elem,evt,ward_id) {
    console.log(ward_id);
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_patients_in_one_ward_consults'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "ward_id="+ward_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#wards-patients-card .card-body").html(messages);
          $("#wards-with-patients-card").hide();
          $("#wards-patients-card").show();
          $("#wards-patients-card .table-test").DataTable();
        }else if(!response.no_patient){
          $.notify({
          message:"No Patient In This Ward"
          },{
            type : "warning"  
          });
        }else{
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackReferralsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#referrals-card").hide();
  }

  function goBackPreviousConsultationsReferralsCard (elem,evt) {
    
    $("#referrals-card").show();
    $("#previous-consultations-referrals-card").hide();
    
  }

  function goBackMakeNewConsultationCardReferral (elem,evt) {
    
    $("#referrals-card").show();
    $("#make-new-consultation-card-referral").hide();
  }

  function goBackFromFirstClinicConsultationReferralCard (elem,evt) {
    $("#referrals-card").show();
    $("#first-clinic-consultation-referral-card").hide();
  }

  function viewReferralInfo (elem,evt,id,user_id) {
    var patient_user_name = $(elem).attr("data-patient-username");
    var patient_name = $(elem).attr("data-patient-name");
    referral_id = id;
    patient_user_id = user_id;
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Previous Consultations',
      cancelButtonText: 'Make Consultation'
    }).then(function(){
      $(".spinner-overlay").show();
      setTimeout(function () {
        $(".spinner-overlay").hide();
        swal({
          title: 'Choose Action',
          text: "Do You Want To: ",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'View Previous Referral Consultations',
          cancelButtonText: 'View First Clinic Consultation'
        }).then(function(){
          $(".spinner-overlay").show();
              
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previous_consultations_referrral'); ?>";
          
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
                $("#previous-consultations-referrals-card .card-body").html(response.messages);
                $("#referrals-card").hide();
                $("#previous-consultations-referrals-card").show();
                $("#previous-consultations-referrals-card #referrals-table").DataTable();
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
              
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_first_clinic_consultation_by_referral_id'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "show_records=true&referral_id="+id,
              success : function (response) {
                console.log(response)
                $(".spinner-overlay").hide();
                if(response.success == true){
                  $("#first-clinic-consultation-referral-card .card-body").html(response.messages);
                  $("#referrals-card").hide();
                  $("#first-clinic-consultation-referral-card").show();
                  
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
      }, 1500);
      
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
            
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_referral_info_doctor'); ?>";
        var facility_id = <?php echo $health_facility_id; ?>;
        
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
              $("#make-new-consultation-card-referral .bio-data").html(response.messages);
              $("#referrals-card").hide();
              $("#make-new-consultation-card-referral").show();
              $("#make-new-consultation-card-referral #selected-tests-referrals").attr("data-id",id);
              $("#make-new-consultation-card-referral #use-yours-btn-referrals").attr("onclick",`selectThisFacilityReferrals(this,event,'${id}','${patient_user_name}','${patient_name}','other' )`)

              $("#make-new-consultation-card-referral #select-another-lab-referral").attr("onclick",`selectAnotherLabReferrals(this,event,'${id}')`)
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-id",id);
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-patient-username",patient_user_name);
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-patient-name",patient_name);
              $("#make-new-consultation-card-referral #make-new-consultation-form-referral").attr("data-facility-id",facility_id);
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


  function viewConsultInfo (elem,evt,id,user_id) {
    var patient_user_name = $(elem).attr("data-patient-username");
    var patient_name = $(elem).attr("data-patient-name");
    referral_id = id;
    patient_user_id = user_id;
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Previous Consultations',
      cancelButtonText: 'Make Consultation'
    }).then(function(){

      $(".spinner-overlay").show();
      setTimeout(function () {
        $(".spinner-overlay").hide();
        swal({
          title: 'Choose Action',
          text: "Do You Want To: ",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'View Previous Consultations',
          cancelButtonText: 'View First Clinic Consultation'
        }).then(function(){
          $(".spinner-overlay").show();
              
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previous_consultations_consult'); ?>";
          
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
                $("#previous-consultations-referrals-card .card-body").html(response.messages);
                $("#referrals-card").hide();
                $("#previous-consultations-referrals-card").show();
                $("#previous-consultations-referrals-card #referrals-table").DataTable();
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
              
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_first_clinic_consultation_by_referral_id'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "show_records=true&referral_id="+id,
              success : function (response) {
                console.log(response)
                $(".spinner-overlay").hide();
                if(response.success == true){
                  $("#first-clinic-consultation-referral-card .card-body").html(response.messages);
                  $("#referrals-card").hide();
                  $("#first-clinic-consultation-referral-card").show();
                  
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
      });
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
            
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_referral_info_doctor'); ?>";
        var facility_id = <?php echo $health_facility_id; ?>;
        
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
              $("#make-new-consultation-card-referral .bio-data").html(response.messages);
              $("#referrals-card").hide();
              $("#make-new-consultation-card-referral").show();
              $("#make-new-consultation-card-referral #selected-tests-referrals").attr("data-id",id);
              $("#make-new-consultation-card-referral #use-yours-btn-referrals").attr("onclick",`selectThisFacilityReferrals(this,event,'${id}','${patient_user_name}','${patient_name}','other' )`)

              $("#make-new-consultation-card-referral #select-another-lab-referral").attr("onclick",`selectAnotherLabReferrals(this,event,'${id}')`)
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-id",id);
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-patient-username",patient_user_name);
              $("#make-new-consultation-card-referral #use-yours-btn-referral").attr("data-patient-name",patient_name);
              $("#make-new-consultation-card-referral #make-new-consultation-form-referral").attr("data-facility-id",facility_id);
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

  function submitEditVitalSignsFormReferral (elem,evt) {
    evt.preventDefault();
    var me = $(elem);
    var url = me.attr("action");
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
          $.notify({
          message:"Vital Signs Edited Successfully"
          },{
            type : "success"  
          });
        }
        else{
         $.each(response.messages, function (key,value) {

          var element = $('#edit-vital-signs-form-referral #'+key);
          
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
  }


  function goBackSelectedReferralTestsCard (elem,evt) {
    $("#selected-referral-tests-card").hide();
    $("#selected-tests-referrals").show();    
  } 


  function selectTestsBtnReferrals (elem,evt) {
    evt.preventDefault();
    $(elem).hide();
    $("#select-lab-to-use-card-referrals").show();
  } 

  function goBackFromSelectLabToUseReferrals (elem,evt) {
    evt.preventDefault();
    $("#select-tests-referrals").show();
    $("#select-lab-to-use-card-referrals").hide();
  }

  function selectThisFacilityReferrals(elem,evt){
    evt.preventDefault();
    var facility_slug = "<?php echo $addition; ?>";
    var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_tests_referrals_doctor";
      
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
        console.log(messages)
        if(messages !== ""){  
          $("#select-lab-to-use-card-referrals").hide();
          $("#other-facility-select-test-card-referrals .card-body").html(messages);
          $("#other-facility-select-test-card-referrals .table").DataTable();
          $("#other-facility-select-test-card-referrals").show();
          $("#proceed-from-tests-selection-btn-referrals").show("fast");
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

  function goBackFromSelectOtherLabReferrals (elem,evt) {
    $("#select-lab-to-use-card-referrals").show();
    $("#other-facility-select-test-card-referrals").hide();
    $("#proceed-from-tests-selection-btn-referrals").hide("fast");
  }

  function proceedFromTestsSelectionReferrals (elem,evt) {

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
        $("#other-facility-select-test-card-referrals").hide();
        $("#additional-information-on-tests-selected-card-referrals").show("fast");
        $("#proceed-from-tests-selection-btn-referrals").hide("fast");
        $("#proceed-from-additional-info-tests-selected-btn-referrals").show("fast");
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

  function goBackFromAdditionalInformationOnTestsSelectedCardReferrals (elem,evt) {
    $("#other-facility-select-test-card-referrals").show();
    $("#additional-information-on-tests-selected-card-referrals").hide("fast");
    $("#proceed-from-tests-selection-btn-referrals").show("fast");
    $("#proceed-from-additional-info-tests-selected-btn-referrals").hide("fast");
  }

  function proceedFromAdditionalTestsSelectedReferrals(elem,evt){
    
    
    // console.log(additional_patient_test_info)
    var i = 0;
    
    
    var height = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #height").val();
    var weight = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #weight").val();
    var fasting_yes = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #fasting #fasting_yes").prop("checked");
    var fasting_no = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #fasting #fasting_no").prop("checked");
    var present_medications = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #edit_present_medications").val();
    var lmp = $("#additional-information-on-tests-selected-card-referrals #additional-information-on-tests-selected-form #lmp").val();
    if(fasting_yes){
      var fasting = 1;
    }else{
      var fasting = 0;
    }
    var form_data = [
      {
        name : 'height',
        value : height
      },
      {
        name : 'weight',
        value : weight
      },
      {
        name : 'fasting',
        value : fasting
      },
      {
        name : 'present_medications',
        value : present_medications
      },
      {
        name : 'lmp',
        value : lmp
      }
    ];
    additional_patient_test_info = form_data;     
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition . '/submit_tests_selected_referrals_dr') ?>";
    

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_user_id : patient_user_id,
      referral_id : referral_id
    }
    console.log(obj)

    $(".spinner-overlay").show(); 
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
              tests_selected_obj = [];
              $("#proceed-from-additional-info-tests-selected-btn-referrals").hide("fast");
              $("#additional-information-on-tests-selected-card-referrals").hide();
              $("#select-lab-to-use-card-referrals").show();
            }); 
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

  function viewTestSubTestsReferrals (elem,e,url) {
    
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
          
          var messages = response.messages;
          var test_name = response.test_name;
          var card_header_str = "Sub Tests Of " + test_name;
          $("#sub-tests-card-referrals .card-title").html(card_header_str);
          $("#sub-tests-card-referrals .card-body").html(messages);

          $("#sub-tests-table").DataTable();
          $("#other-facility-select-test-card-referrals").hide();
          
          $("#sub-tests-card-referrals").show();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function goBackSubTests1Referrals (elem,evt) {
    evt.preventDefault();
    $("#other-facility-select-test-card-referrals").show();
    
    $("#sub-tests-card-referrals").hide();
  }

  function goBackFromSelectOtherLabReferrals (elem,evt) {
    evt.preventDefault();
    tests_selected_obj = [];

    $("#select-lab-to-use-card-referrals").show();
    $("#proceed-from-tests-selection-btn").hide("fast");
    $("#other-facility-select-test-card-referrals").hide();
  }

  function viewSubTests1Referral(elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    
    evt.preventDefault()
    // console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_sub_tests_clinic_referral'); ?>";

    var form_data = {
      "show_records" : true,
      
      "initiation_code" : initiation_code,
      "lab_id" : lab_id,
      "health_facility_id" : health_facility_id,
      "main_test_id" : main_test_id,
      "sub_dept_id" : sub_dept_id
    }
    if(isward && type != null){
      form_data.type = "ward1";
    }else if(isward){
      form_data.type = "ward";
    }

    console.log(form_data)
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : form_data,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.main_test_name != ""){
          var messages = response.messages;
          var main_test_name = response.main_test_name;
          

          $("#selected-sub-tests-card-referral .card-body").html(messages);
          $("#selected-sub-tests-card-referral .card-title").html("Selected Sub Tests Under " +main_test_name);
          $("#selected-referral-tests-card").hide();
          
          $("#selected-sub-tests-card-referral").show();
          
          $(".tests-table").DataTable();
          
        }else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });   
    
  }

  function goBackFromSelectedSubTestsReferral (elem,evt) {
    evt.preventDefault();
    $("#selected-referral-tests-card").show();
    
    $("#selected-sub-tests-card-referral").hide();
  }

  function viewTestResultsReferred (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-card-referred .card-body").html(messages);
          $("#view-results-card-referred .card-title").html("Ready Result For " +test_name);
          $("#selected-referral-tests-card").hide();
          
          $("#view-results-card-referred").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsCardReferred (elem,evt) {
    evt.preventDefault();

    $("#selected-referral-tests-card").show();
    
    $("#view-results-card-referred").hide();
  }

  function viewTestResultImagesReferred (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    

    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;


          $("#view-results-images-card-referral .card-body").html(messages);
          $("#view-results-images-card-referral .card-title").html("Result Images For " +test_name);
          $("#selected-referral-tests-card").hide();
          
          $("#view-results-images-card-referral").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsImagesCardReferral (elem,evt) {
    evt.preventDefault();
   
    $("#selected-referral-tests-card").show();
    
    $("#view-results-images-card-referral").hide();
  }


  function viewTestResultsSubReferral (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-card-sub-referral .card-body").html(messages);
          $("#view-results-card-sub-referral .card-title").html("Ready Result For " +test_name);
          $("#selected-sub-tests-card-referral").hide();
          
          $("#view-results-card-sub-referral").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackViewResultsCardSubReferrals (elem,evt) {
    evt.preventDefault();

    $("#selected-sub-tests-card-referral").show();
    
    $("#view-results-card-sub-referral").hide();
  }


  function viewTestResultImagesSubReferrals (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-images-card-sub-referral .card-body").html(messages);
          $("#view-results-images-card-sub-referral .card-title").html("Result Images For " +test_name);
          $("#selected-sub-tests-card-referral").hide();
         
          $("#view-results-images-card-sub-referral").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsImagesCardSubReferral (elem,evt) {
    evt.preventDefault();

    $("#selected-sub-tests-card-referral").show();
   
    $("#view-results-images-card-sub-referral").hide();
  }

  

  function selectPharmacyBtnReferrals (elem,evt) {
    evt.preventDefault();
    $(elem).hide();
    $("#selected-drugs-referral").hide();
    $("#select-pharmacy-to-use-card-referral").show();
  }

  function goBackFromSelectPharmacyToUseReferral (elem,evt) {
    evt.preventDefault();
    $("#select-pharmacy-referral").show();
    $("#selected-drugs-referral").show();
    $("#select-pharmacy-to-use-card-referral").hide();
  }

  function selectThisPharmacyReferral(elem,evt){
    evt.preventDefault();
    if(referral_id != ""){
      var tr = elem.parentElement.parentElement;
      var facility_id = <?php echo $health_facility_id; ?>;
      var facility_name = "<?php echo $health_facility_name; ?>";
      var facility_slug = "<?php echo $health_facility_slug; ?>";
      
      var get_tests_url = "<?php echo site_url('onehealth/index/') ?>" + facility_slug + "/" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
      
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          referral_id : referral_id,
          host_facility_id : <?php echo $health_facility_id; ?>
        },
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card-referral .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card-referral .card-body").html(messages);
            $("#select-drugs-card-referral").attr("data-facility-id",facility_id);
            $("#select-pharmacy-to-use-card-referral").hide();
            $("#select-drugs-card-referral #select-drugs-table").DataTable();
            $("#select-drugs-card-referral").show();
            $("#select-drugs-proceed-btn-referral").show("fast");
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

  function goBackSelectDrugsReferral (elem,evt) {
    evt.preventDefault();
    user_info = [];
    selected_drugs = [];
    $("#select-pharmacy-to-use-card-referral").show();
    $("#select-drugs-card-referral").hide();
    $("#select-drugs-proceed-btn-referral").hide("fast");
  }

  function selectDrugsProceedReferral(elem,evt) {
    evt.preventDefault()
    console.log(selected_drugs);
    console.log(user_info);
    if(selected_drugs.length > 0){
      var selected_drugs_info_html = "<div class='container-fluid'>";
      var j = 0;
      <?php 
        $attr = array('id' => 'prescription-data-form');
      ?>
      selected_drugs_info_html += "<div id='prescription-data-div'>";
      for(var i = 0; i < selected_drugs.length; i++){
        var id = selected_drugs[i].id;
        var price = selected_drugs[i].price;
        var brand_name = selected_drugs[i].brand_name;
        var generic_name = selected_drugs[i].generic_name;
        var formulation = selected_drugs[i].formulation;
        var strength = selected_drugs[i].strength;
        var strength_unit = selected_drugs[i].strength_unit;
        var main_store_quantity = selected_drugs[i].main_store_quantity;
        var dispensary_quantity = selected_drugs[i].dispensary_quantity;
        var unit = selected_drugs[i].unit;

        j++;
        selected_drugs_info_html += j + ". ";
        
        
        selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";
        selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
        selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
        selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
        selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
        selected_drugs_info_html += "<input class='form-control dosage' type='number' onkeyup='dosageEvent(this,event,"+i+",0)'>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
        selected_drugs_info_html += "<input class='form-control frequency_num' type='number' onkeyup='frequencyEvent1(this,event,"+i+",0)'>";
        selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "<option value='minutely' selected>Minutely</option>";
        selected_drugs_info_html += "<option value='hourly'>Hourly</option>";
        selected_drugs_info_html += "<option value='daily'>Daily</option>";
        selected_drugs_info_html += "<option value='weekly'>Weekly</option>";
        selected_drugs_info_html += "<option value='monthly'>Monthly</option>";
        selected_drugs_info_html += "<option value='yearly'>Yearly</option>";
        selected_drugs_info_html += "<option value='nocte'>Nocte</option>";
        selected_drugs_info_html += "<option value='stat'>Stat</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
        selected_drugs_info_html += "<input class='form-control duration_num' type='number' onkeyup='durationEvent1(this,event,"+i+",0)'>";
        selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "option value='minutes' selected>Minutes</option>";
        selected_drugs_info_html += "<option value='hours'>Hours</option>";
        selected_drugs_info_html += "<option value='days'>Days</option>";
        selected_drugs_info_html += "<option value='weeks'>Weeks</option>";
        selected_drugs_info_html += "<option value='months'>Months</option>";
        selected_drugs_info_html += "<option value='years'>Years</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "</div>";
        
      }
      selected_drugs_info_html += "</div>";

      $("#select-drugs-card-referral").hide();
      $("#select-drugs-proceed-btn-referral").hide("fast");
      $("#select-drugs-proceed-btn-2-referral").show("fast");
      $("#selected-drugs-info-card-referral .card-title").html("Enter prescription Details For: " + user_info.full_name);
      $("#selected-drugs-info-card-referral .card-body").html(selected_drugs_info_html);
      $("#selected-drugs-info-card-referral").show();
      selected_drugs_info_html += "</div>";
    }else{
      swal({
       title: 'Sorry',
        text: "You Must Select At Least One Drug To Proceed",
        type: 'error'
      })
    }
  }

  function goBackSelectedInfoDrugsReferral (elem,evt) {
    evt.preventDefault();
    $("#select-drugs-card-referral").show();
    $("#select-drugs-proceed-btn-referral").show("fast");
    $("#select-drugs-proceed-btn-2-referral").hide("fast");
    
    $("#selected-drugs-info-card-referral").hide();
  }


  function selectDrugsProceed2Referral (elem,evt) {
    var proceed = false;
    
    var facility_id = $("#select-drugs-card-referral").attr("data-facility-id");

    for(var i = 0; i < selected_drugs.length; i++) { 
      var is_poison = selected_drugs[i].is_poison; 
      if(is_poison == 1){ 
        proceed = false;
        break; 
      }else{
        proceed = true;
      }
    }

    if(!proceed){
      if(user_info.clinician !== ""){
        proceed = true;
      }
    }

    console.log(proceed)
    
    // evt.preventDefault();
    if(proceed){
      var me = $("#prescription-data-div");
      var form_data = {
        drugs_info : []
      };
      
      var drugs_info = [];
      var num = 0;
      var sum = 0;
      var total_quantity = 0;
      me.find(".form-row").each(function(index, el) {
        var el = $(el);
        var id = el.attr("data-id");
        var price = selected_drugs[index].price;
        var dosage = el.find(".dosage").val();
        var frequency_num = el.find(".frequency_num").val();
        var frequency_time = el.find(".frequency_time").val();
        var duration_num = el.find(".duration_num").val();
        var duration_time = el.find(".duration_time").val();

        var obj = {
          id : id,
          frequency_num : frequency_num,
          frequency_time : frequency_time,
          duration_num : duration_num,
          duration_time : duration_time,
          dosage : dosage,
          price : price
        };

        drugs_info[index] = obj;

        if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
          num++;
          var prescription_obj = calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
          // console.log(prescription_obj)
          if(prescription_obj != {}){
            var quantity = prescription_obj.quantity;
            var total_price = prescription_obj.total_price;

            total_quantity += quantity;
            sum += total_price;
          }
        }

      });

      form_data['drugs_info'] = drugs_info;
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
      form_data["referral"] = true;

      form_data["referral_id"] = referral_id;
      form_data['host_facility_id'] = facility_id;
      


      console.log(form_data);
      // console.log(JSON.stringify(form_data))

      if(num > 0){
        swal({
          title: 'Proceed?',
          text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Proceed?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_drugs_selected_pharmacy'); ?>";
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
                message:"Drugs Selected Successfully"
                },{
                  type : "success"  
                });
                $("#selected-drugs-info-card-referral").hide();
                $("#select-pharmacy-referral").show();
                $("#selected-drugs-referral").show();
                selected_drugs = [];
                user_info = [];
                $("#select-drugs-proceed-btn-2-referral").hide("fast");
              }else{
                swal({
                 title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
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
      }else{
        swal({
          title: 'Warning! ',
          text: "You Must Enter Complete Prescription Info For At Least One Selected Drug To Proceed",
          type: 'error'
        })
      }
    }else{
      $("#enter-clinician-name-modal").modal("show");
    }
  }


  function selectAnotherPharmacyReferral  (elem,evt) {
    evt.preventDefault();
    if(referral_id != ""){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_pharmacies_and_health_facilities'); ?>";
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&referral=true&referral_id="+referral_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.messages !== ""){
            $("#select-pharmacy-to-use-card-referral").hide();
            $("#select-pharmacy-card-referral .card-body").html(response.messages);
            $("#select-pharmacy-card-referral").show();
            $("#select-pharmacy-card-referral #facilities-pharmacies-table").DataTable();
            LetterAvatar.transform();
          }else{
            $.notify({
            message:"No Records To Display"
            },{
              type : "warning"  
            });
          }
        },error :function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    } 
  }

  function goBackSelectPharmaciesReferral (elem,evt) {
    evt.preventDefault();
    $("#select-pharmacy-to-use-card-referral").show();
    
    $("#select-pharmacy-card-referral").hide();
    
  }

  function selectThisFacilityPharmacyReferral(elem,evt){
    evt.preventDefault();
    if(referral_id != ""){
      var tr = elem.parentElement.parentElement;
      var facility_id = tr.getAttribute("data-id");
      var facility_name = tr.getAttribute("data-facility-name");
      var facility_slug = tr.getAttribute("data-slug");
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$health_facility_slug. '/') ?>" + "pathology-laboratory-services" + "/get_all_facility_drugs_select";
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          referral_id : referral_id,
          host_facility_id : facility_id
        },
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          console.log(messages)
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card-referral .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card-referral .card-body").html(messages);
            $("#select-drugs-card-referral").attr("data-facility-id",facility_id);
            $("#select-pharmacy-card-referral").hide();
            $("#select-drugs-card-referral #select-drugs-table").DataTable();
            $("#select-drugs-card-referral").show();
            $("#select-drugs-proceed-btn-referral").show("fast");
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


  function viewPreviouslySelectedDrugsReferral (elem,evt) {
    $(".spinner-overlay").show(); 
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_referrals'); ?>";
    $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "referral_id="+referral_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $(elem).hide();
            $("#select-pharmacy-referral").hide();
            $("#selected-drugs-card-referrals .card-body").html(messages);
            $("#selected-drugs-card-referrals .selected-drugs-table").DataTable();
            $("#selected-drugs-card-referrals").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Sorry Something Went Wrong",
              type: 'error'
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

  function goBackFromSelectedDrugsReferrals (elem,evt) {
    evt.preventDefault();
    $("#selected-drugs-referral").show();
    $("#select-pharmacy-referral").show();
    
    $("#selected-drugs-card-referrals").hide();
  }

  function referOrConsultReferral(elem,evt){
    
    <?php if($clinic_structure == "standard"){ ?>
    swal({
      title: 'Choose Action',
      text: "Do You Want To Perform: ",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Referral',
      cancelButtonText: 'Consult'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_other_registered_hospitals_referral'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $(elem).hide();
            $("#go-back-refer-consult-referral").show();
            $("#select-facility-for-referral-div-referral").html(messages);
            $("#select-facility-for-referral-div-referral #other-facilities-for-referral-table").DataTable();
            $("#select-facility-for-referral-div-referral").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinics_in_facility_consult_referral'); ?>";
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "",
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $(elem).hide();
              // $("#go-back-refer-consult").show();
              
              
              // $("#select-facility-for-referral-div").hide();
              $("#select-clinic-for-consult-div-referral").html('');
              $("#select-clinic-for-consult-div-referral").append('<button id="go-back-select-clnic-for-consult-referral" onclick="goBackSelectClinicForConsultReferral(this,event)" type="button" class="btn btn-warning">Go Back</button>');
              $("#select-clinic-for-consult-div-referral").append('<h5>Select Clinic For Consult</h5>');
              $("#select-clinic-for-consult-div-referral").append(messages);
              
              $("#select-clinic-for-consult-div-referral #other-facilities-clinics-for-consult-table").DataTable({
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
              });
              $("#select-clinic-for-consult-div-referral").show();
            }else{
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
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
    });
    <?php }else{ ?>
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_other_registered_hospitals_referral'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $(elem).hide();
          $("#go-back-refer-consult-referral").show();
          $("#select-facility-for-referral-div-referral").html(messages);
          $("#select-facility-for-referral-div-referral #other-facilities-for-referral-table").DataTable();
          $("#select-facility-for-referral-div-referral").show();
        }else{
          swal({
            title: 'Ooops',
            text: "Something Went Wrong",
            type: 'warning'
          })
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
    <?php } ?>
  }

  function goBackReferOrConsultReferral (elem,evt) {
    $(elem).hide();
    $("#refer-consult-btn-referral").show();
    
    $("#select-facility-for-referral-div-referral").hide();
  }

  function loadFacilityClinicsForReferralReferral(elem,evt,id){
    var facility_name = $(elem).attr("data-name");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinics_in_facility_referral'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "facility_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#go-back-refer-consult-referral").hide();
            $("#select-facility-for-referral-div-referral").hide();
            $("#select-clinic-for-referral-div-referral").html('');
            $("#select-clinic-for-referral-div-referral").append('<button id="go-back-select-clnic-for-referral-referral" onclick="goBackSelectClinicForReferralReferral(this,event)" type="button" class="btn btn-warning">Go Back</button>');
            $("#select-clinic-for-referral-div-referral").append('<h5>Select Clinic From '+facility_name+' For Referral</h5>');
            $("#select-clinic-for-referral-div-referral").append(messages);
            
            $("#select-clinic-for-referral-div #other-facilities-clinics-for-referral-table").DataTable({
              aLengthMenu: [
                  [25, 50, 100, 200, -1],
                  [25, 50, 100, 200, "All"]
              ],
              iDisplayLength: -1
            });
            $("#select-clinic-for-referral-div-referral").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
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

  function selectThisClinicForReferralReferral (elem,evt,id) {
    var clinic_name = $(elem).attr("data-name");
    var facility_name = $(elem).attr("data-facility-name");
    var facility_id = $(elem).attr("data-facility-id");

    $("#reason-for-referral-form-referral").attr("data-clinic-name",clinic_name);
    $("#reason-for-referral-form-referral").attr("data-facility-name",facility_name);
    $("#reason-for-referral-form-referral").attr("data-facility-id",facility_id);
    $("#reason-for-referral-form-referral").attr("data-clinic-id",id);
    $("#enter-reason-for-referral-modal-referral").modal("show");
  }

  function goBackSelectClinicForReferralReferral (elem,evt) {
    $(elem).hide();
    $("#go-back-refer-consult-referral").show();
    $("#select-facility-for-referral-div-referral").show();
    $("#select-clinic-for-referral-div-referral").html('');
    
    $("#select-clinic-for-referral-div-referral").hide();
  }

  function goBackSelectClinicForConsultReferral (elem,evt) {
    $(elem).hide();
    $("#select-clinic-for-consult-div-referral").html('');
    $("#select-clinic-for-consult-div-referral").hide();
    $("#refer-consult-btn-referral").show();
  }

  function viewPreviousReferralConsultationInfo (elem,evt,referral_id) {
    if(referral_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previous_referral_consultation_info'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "referral_id="+referral_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;

            $("#previous-consultations-referrals-card").hide();
            $("#previous-consultations-referral-info-card .card-body").html(messages);
            $("#previous-consultations-referral-info-card .table").DataTable();
            $("#previous-consultations-referral-info-card").show();
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
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


  function goBackPreviousConsultationsReferralInfoCard (elem,evt) {
    $("#previous-consultations-referrals-card").show();
    
    $("#previous-consultations-referral-info-card").hide();
  }

  function viewSubTests1ReferralPrevious(elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    
    evt.preventDefault()
    // console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_sub_tests_clinic_referral_previous'); ?>";

    var form_data = {
      "show_records" : true,
      
      "initiation_code" : initiation_code,
      "lab_id" : lab_id,
      "health_facility_id" : health_facility_id,
      "main_test_id" : main_test_id,
      "sub_dept_id" : sub_dept_id
    }
    if(isward && type != null){
      form_data.type = "ward1";
    }else if(isward){
      form_data.type = "ward";
    }

    console.log(form_data)
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : form_data,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.main_test_name != ""){
          var messages = response.messages;
          var main_test_name = response.main_test_name;
          

          $("#selected-sub-tests-card-referral-previous .card-body").html(messages);
          $("#selected-sub-tests-card-referral-previous .card-title").html("Selected Sub Tests Under " +main_test_name);
          $("#previous-consultations-referral-info-card").hide();
          
          $("#selected-sub-tests-card-referral-previous").show();
          
          $(".tests-table").DataTable();
          
        }else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });    
  }

  function goBackFromSelectedSubTestsReferralPrevious  (elem,evt) {
      
    $("#previous-consultations-referral-info-card").show();
    
    $("#selected-sub-tests-card-referral-previous").hide();
  }

  function viewTestResultsReferredPrevious (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-card-referred-previous .card-body").html(messages);
          $("#view-results-card-referred-previous .card-title").html("Ready Result For " +test_name);
          

          $("#previous-consultations-referral-info-card").hide();
          
          $("#view-results-card-referred-previous").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsCardReferredPrevious (elem,evt) {
    $("#previous-consultations-referral-info-card").show();
    
    $("#view-results-card-referred-previous").hide();
  }

  function viewTestResultImagesReferredPrevious (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    

    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;


          $("#view-results-images-card-referral-previous .card-body").html(messages);
          $("#view-results-images-card-referral-previous .card-title").html("Result Images For " +test_name);
          $("#previous-consultations-referral-info-card").hide();
          
          $("#view-results-images-card-referral-previous").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsImagesCardReferralPrevious (elem,evt) {
    
    $("#previous-consultations-referral-info-card").show();
    
    $("#view-results-images-card-referral-previous").hide();
          
  }

  function viewTestResultsSubReferralPrevious (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_results_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-card-sub-referral-previous .card-body").html(messages);
          $("#view-results-card-sub-referral-previous .card-title").html("Ready Result For " +test_name);
          $("#selected-sub-tests-card-referral-previous").hide();
          
          $("#view-results-card-sub-referral-previous").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }else if(!response.test_ready){
          swal({
            title: 'You Cannot View This Result',
            text: "This Result Is Not Ready Yet",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackViewResultsCardSubReferralsPrevious (elem,evt) {
    
    $("#selected-sub-tests-card-referral-previous").show();
    
    $("#view-results-card-sub-referral-previous").hide();
  }

  function viewTestResultImagesSubReferralsPrevious (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward) {
    evt.preventDefault();
    console.log(main_test_id + " : " + initiation_code + " : " + health_facility_id + " : " +lab_id+" : " +sub_dept_id)
    
    
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tests_clinic'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      
      data : {
        "show_records" : true,
        
        "initiation_code" : initiation_code,
        "lab_id" : lab_id,
        "health_facility_id" : health_facility_id,
        "main_test_id" : main_test_id,
        "sub_dept_id" : sub_dept_id
      },
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== "" && response.test_name != ""){
          var messages = response.messages;
          var test_name = response.test_name;

          $("#view-results-images-card-sub-referral-previous .card-body").html(messages);
          $("#view-results-images-card-sub-referral-previous .card-title").html("Result Images For " +test_name);
          $("#selected-sub-tests-card-referral-previous").hide();
         
          $("#view-results-images-card-sub-referral-previous").show();
          
        }else if(!response.paid){
          swal({
            title: 'You Cannot View This Result',
            text: "This User Has Not Finished Payment For This Test",
            type: 'error'
          })
        }
        else{
           $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message: "Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
  }

  function goBackFromViewResultsImagesCardSubReferralPrevious (elem,evt) {
    $("#selected-sub-tests-card-referral-previous").show();
   
    $("#view-results-images-card-sub-referral-previous").hide();
  }

  function selectMortuaryBtn (elem,evt) {
    evt.preventDefault();
    
    $("#new-consultation-form").hide();
    
    $("#select-mortuary-to-use-card").show();
    
  }

  function goBackFromSelectMortuaryToUse (elem,evt) {

    $("#new-consultation-form").show();
    
    $("#select-mortuary-to-use-card").hide();
  }

  function mortuaryDetailsModalClosed(elem,event){
    $.notify({
    message:"Consultation Successful"
    },{
      type : "success"  
    });
    setTimeout(goDefault, 1000);
  }

  function processPatientToMortuary (mortuary_id) {
    $("#enter-mortuary-details-form #time_of_death").datetimepicker({
      widgetPositioning : {
        vertical: 'bottom'
      }
    })
    
    $("#enter-mortuary-details-form").attr("data-mortuary-id",mortuary_id);
    $("#enter-mortuary-details-modal").modal("show");
  }

  function selectThisFacilityMortuary(elem,evt,type){
     evt.preventDefault();
     if(type == "yours"){
       var tr = elem.parentElement.parentElement;
       var facility_id = tr.getAttribute("data-id");
       
     }else{
      var facility_id = <?php echo $health_facility_id; ?>;
     }
      var url = $("#new-consultation-form").attr("action");
      var values = $("#new-consultation-form").serializeArray();
      values = values.concat({
        "name" : "consultation_id",
        "value" : consultation_id
      })
      console.log(values)
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
            swal({
              title: 'Choose Action',
              text: "Are You Sure You Want To Confirm This Patient As Dead",
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No'
            }).then(function(){
                processPatientToMortuary(facility_id);
            }, function(dismiss){
              if(dismiss == 'cancel'){
                $.notify({
                message:"Consultation Successful"
                },{
                  type : "success"  
                });
                setTimeout(goDefault, 1000);
              }
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
            message:"Valid Values Need To Be Entered In Consultation Fields To Proceed. Please Go Back And Enter Valid Values"
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
  }


  function selectAnotherMortuary(elem,evt){
    evt.preventDefault();
    
    $(".spinner-overlay").show();
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_mortuaries_and_health_facilities'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-mortuary-card .card-body").html(response.messages);
          $("#select-mortuary-to-use-card").hide();
          $("#select-mortuary-card").show();
          $("#facilities-table").DataTable();
          LetterAvatar.transform();
        }else{
          $.notify({
          message:"No Records To Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackSelectMortuaryCard (elem,evt) {
    $("#new-consultation-form").show();
    
    $("#select-mortuary-card").hide();
    
  }

  function dischargePatient (elem,evt) {
    swal({
      title: 'Warning',
      text: "Are You Sure You Want To Discharge Patient",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(function(){
      swal({
        title: 'Choose Action',
        text: "Do You Want To Discharge Patient: ",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Home',
        cancelButtonText: 'Mortuary'
      }).then(function(){
        $(".spinner-overlay").show();
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/discharge_ward_patient'); ?>";
        
        var form_data = {
          "ward_record_id" : ward_record_id
        };
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
              message:"Patient Discharged Successfully"
              },{
                type : "success"  
              });
              setTimeout(goDefault,1000);
            }else{
              $.notify({
              message:"Something Went Wrong"
              },{
                type : "warning"  
              });
            }
          },
          error : function () {
            $(".spinner-overlay").hide();
            swal({
              title: 'Error',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        });   
      }, function(dismiss){
        if(dismiss == 'cancel'){
          $("#select-mortuary-to-use-card-ward").show();
          $("#choose-action-patient-modal").modal("hide");
          $("#wards-patients-card").hide();
        }
      });
    });
  }

  function goBackFromSelectMortuaryToUseWard (elem,evt) {
    $("#select-mortuary-to-use-card-ward").hide();
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
  }


  function selectThisFacilityMortuaryWard(elem,evt,type){
    
    
    if(type == "yours"){
      var tr = elem.parentElement.parentElement;
      var facility_id = tr.getAttribute("data-id");
       
    }else{
      var facility_id = <?php echo $health_facility_id; ?>;
    }
      
    swal({
      title: 'Choose Action',
      text: "Are You Sure You Want To Confirm This Patient As Dead",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(function(){
        processPatientToMortuaryWard(facility_id);
    });
          
           
  }

  function processPatientToMortuaryWard (mortuary_id) {
    $("#enter-mortuary-details-form-ward #time_of_death").datetimepicker({
      widgetPositioning : {
        vertical: 'bottom'
      }
    })
    
    $("#enter-mortuary-details-form-ward").attr("data-mortuary-id",mortuary_id);
    $("#enter-mortuary-details-modal-ward").modal("show");
  }

  function selectAnotherMortuaryWard(elem,evt){
    evt.preventDefault();
    
    $(".spinner-overlay").show();
    var record_id = $("#off-appointment-patient-card .record_id").val();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get_all_registered_mortuaries_and_health_facilities_ward'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+record_id,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.messages !== ""){
          $("#select-mortuary-card-ward .card-body").html(response.messages);
          $("#select-mortuary-to-use-card-ward").hide();
          $("#select-mortuary-card-ward").show();
          $("#facilities-table").DataTable();
          LetterAvatar.transform();
        }else{
          $.notify({
          message:"No Records To Display"
          },{
            type : "warning"  
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });  
  }

  function goBackFromSelectMortuaryWard (elem,evt) {
    $("#select-mortuary-to-use-card-ward").show();
    $("#select-mortuary-card-ward").hide();
    
  }

  function performLabFunctions(elem,evt,base_url,str,dept_id,sub_dept_id){
    lab_base_url = base_url;
    lab_dept_id = dept_id;
    lab_sub_dept_id = sub_dept_id;
    console.log(lab_sub_dept_id);
    $("#carry-actions-card .card-title").html(str);
    $("#carry-actions-card").show();
    $("#choose-action-card").hide();
  }

  function goBackFromPerformLabFunctionsCard (elem,evt) {
    
    $("#carry-actions-card").hide();
    $("#choose-action-card").show();
  }

  function displayResultAwaitingComment () {
    $("#carry-actions-card").hide();
    $("#results-awaiting-comments-card").show();
  }

  function goBackFromResultsAwaitingCommentsCard (elem,evt) {
    $("#carry-actions-card").show();
    $("#results-awaiting-comments-card").hide(); 
  }

  function loadPatient (lab_id) {  
    $(".spinner-overlay").show();
    lab_id = String(lab_id);
    var get_patients_tests = lab_base_url + "/get_patients_tests_pathologist";
    $.ajax({
      url : get_patients_tests,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "get_patients_tests=true&lab_id="+lab_id,
      success : function (response) {  
        //Note Return Form Where Control Values Are Inputed
        var get_patients_tests = lab_base_url + "/get_patient_bio_data_display";
        $.ajax({
          url : get_patients_tests,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "get_patient_bio_data=true&lab_id="+lab_id,
          success : function (response1) {
            
            $(".spinner-overlay").hide();
            var first_name = response1.first_name;
            var last_name = response1.last_name;
            var address = response1.address;
            var clinical_summary = response1.clinical_summary;
            var consultant = response1.consultant;
            var consultant_email = response1.consultant_email;
            var consultant_mobile = response1.consultant_mobile;
            var dob = response1.dob;
            var email = response1.email;
            var fasting = response1.fasting;
            var height = response1.height1;
            var sample = response1.sample;
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
            
            var pathologist = response1.pathologist;
            var pathologist_email = response1.pathologist_email;
            var pathologist_mobile = response1.pathologist_mobile;
            var sampling_time = response1.sampling_time;
            var separation_time = response1.separation_time;
            var observation = response1.observation;
            $("#view_patient_info .welcome-heading").html(first_name + " " + last_name + "'s test result info")
            $("#first_name").html(first_name);
            $("#last_name").html(last_name);
            $("#dob").html(dob);
            $("#age").html(age + " " + age_unit + " old");
            $("#sex").html(sex);
            $("#race").html(race);
            $("#mobile").html(mobile);
            $("#email").html(email);
            $("#height").html(height + "m");
            $("#weight").html(weight + "kg");
            $("#fasting").html(fasting);
            $("#present_medications").html(present_medications);
            $("#lmp").html(lmp);
            $("#clinical_summary").html(clinical_summary);
            $("#sample").html(sample);
            $("#referring_dr").html(referring_dr);
            $("#consultant").html(consultant);
            $("#consultant_mobile").html(consultant_mobile);
            $("#consultant_email").html(consultant_email);
            $("#pathologist").html(pathologist);
            $("#pathologist_mobile").html(pathologist_mobile);
            $("#pathologist_email").html(pathologist_email);
            $("#address").html(address);
            $("#separation_time").val(separation_time);
            $("#sampling_time").val(sampling_time);
            $("#observation").val(observation);
            $("#test-result-form").append(response);
            $("#data-form").attr('data-lab-id',lab_id);
            $('#process-sample-modal').modal('hide');
            $("#process-sample-card").hide();
            $("#view_patient_info").show();
             var table = $('#example1').DataTable();
             $("#test-result-form").attr('data-lab-id',lab_id);
             $("#results-awaiting-comments-card").hide();
             $("#all-results-card").hide();

          },
          error : function () {
            $(".spinner-overlay").hide();
            
          }
        });
      }
    });
  }

  function goBackFromViewPatientInfoCard(elem,evt){
    // $('#process-sample-modal').modal('show');
    goDefault();
  }

  function printResult (lab_id) {
    swal({
      title: 'Note',
      text: "Please Submit Result Values To View Changes Made To This Patient's Folder In Final Result",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Results This Way',
      cancelButtonText : 'I Want To Submit Results'
    }).then((result) => {
      var pdf_url = "<?php echo $health_facility_slug; ?>" + "_"+lab_id+"_result.html";
      document.location.assign("<?php echo base_url('assets/images/'); ?>" + pdf_url);
    });
  }

  function submitSignatureForm(elem,evt) {
    var me = $(elem);
    evt.preventDefault();
    var file_input = elem.querySelector("#signature_file");

    var files = file_input.files;
    console.log(files)
    var form_data = new FormData();
    var error = '';
    
    form_data.append("signature_file",files[0]);
    // form_data.append("officer","pathologist");
        
    $(".spinner-overlay").show();
    $.ajax({
      url : me.attr("action"),
      type : "POST",
      cache: false,
      dataType : "json",
      contentType: false,
      processData: false,
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.wrong_extension){
          $.notify({
            message:"The File Uploaded Is Not A Valid Image Format"
            },{
              type : "warning"  
          });
        }else if(response.too_large){
          $.notify({
            message:"The File Upladed Is Too Large Max Is 200 KB"
            },{
              type : "warning"  
          });
        }else if(response.not_really_json){
          $.notify({
            message:"This File Format Is Not Really An Image File"
            },{
              type : "warning"  
          });
        }else if(response.success && response.image_name != ""){
          var image_name = response.image_name;
          $.notify({
            message:"Upload Of Signature Image Successful. Please Submit The Tests Values Form So The Uploaded Signature Can Reflect In The Final Results."
            },{
              type : "success" ,
              timer: 10000 
          });
          $("#signature_image").attr("src",image_name);
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

  function submitPathologistComment(elem,evt) {
    evt.preventDefault();
    var lab_id = elem.getAttribute("data-lab-id");
    lab_id = String(lab_id);
    var comment_elem = elem.querySelector("#comment");
    var comment_val = comment_elem.value;
    var comment_error = comment_elem.nextElementSibling;
    if(comment_val == ""){
      comment_error.innerHTML = "This Field Cannot Be Empty";
    }else{
      var url = lab_base_url + "/submit_pathologist_comment";

      $.ajax({
        type : "POST",
        url : url,
        dataType : "text",
        responseType : "text",
        data : "submit_pathologist_comment=true&comment="+comment_val+"&lab_id="+lab_id,
        success : function (response) {
          console.log(response)
          if(response == 1){
            $.notify({
            message:"Successful"
            },{
              type : "success"  
            });
            comment_error.innerHTML = "";
          }else if(response == 0){
            $.notify({
            message:"Please Enter Valid Input"
            },{
              type : "warning"  
            });
            comment_error.innerHTML = ""
          }else{
            $.notify({
            message:"Please Enter Valid Input"
            },{
              type : "warning"  
            });
            comment_error.innerHTML = response;
          }
        },
        error : function (jqXhr,err) {
          $.notify({
            message:"Sorry Something Went Wrong"
            },{
              type : "danger"  
            });
          console.log(err)
        }
      });
    }
  }
  function submitImage (elem,evt,id) {
    evt.preventDefault()
    var file_input = elem.parentElement.previousElementSibling.querySelector("input");
    var viewImagesBtn = elem.nextElementSibling;
    var file_name = file_input.getAttribute("name");
    var files = file_input.files;
    var form_data = new FormData();
    var error = '';

    if(file_input.value !== ""){
      for(var i=0; i < files.length; i++){
        var file = files[i];
        var name = files[i].name;
        var pos = i + 1;
        var extension = name.split('.').pop().toLowerCase();
        if(jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1){
          error += "<span style='font-style: italic;' class='text-danger'>Invalid Image File Selected At Position " + pos + "<br></span>";
        }else{
          
          form_data.append("image[]",files[i]);
        }
      }

      if(error == ''){
        var get_patients_tests = lab_base_url + "/test_test/"+id;
        $(".spinner-overlay").show();
          $.ajax({
            url : get_patients_tests,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : form_data,
            contentType : false,
            cache : false,
            processData : false,
            success : function (response) {             
              $(".spinner-overlay").hide();
              if(response == Number(response)){
                $.notify({
                message:"Image Upload Successful"
                },{
                  type : "success"  
                });
                file_input.value == "";
                viewImagesBtn.setAttribute("class","btn btn-info");
              }else{
                swal({
                title: 'Error!',
                text: response,
                type: 'error'         
              })
              }
            },
            error : function () {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Something Went Wrong When Trying To Upload Your Images"
                },{
                  type : "danger"  
                });
            } 
          });
      }else{
        swal({
            title: 'Error!',
            text: error,
            type: 'error'         
          })
      }

    }else{
      swal({
        title: 'Warning?',
        text: "Sorry No Images Selected.",
        type: 'error'         
      })
    }
           
  }
 
 function viewImages (elem,evt,id) {
    evt.preventDefault();
    var get_test_images = lab_base_url + "/view_images";

    $(".spinner-overlay").show();
      $.ajax({
        url : get_test_images,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : "id="+id+"&platform=supervisor",
        
        success : function (response) {  
                 
          $(".spinner-overlay").hide();
          $("#modal").modal("show"); 
          $("#gallery").html(response); 
        },
        error : function () {
          $(".spinner-overlay").hide();
        } 
      });     
  }

  function deleteImage (elem,evt,index,image_name,id) {
    evt.preventDefault();
    swal({
      title: 'Warning?',
      text: "Are You Sure You Want To Delete This Image?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
      var delete_test_images = lab_base_url + "/delete_image";
      $(".spinner-overlay").show();
      $.ajax({
        url : delete_test_images,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : "id="+id+"&image_name="+image_name+"&index="+index,
        
        success : function (response) {   
                       
          if(response == 1){
            $("#modal").modal("hide");
            $(".spinner-overlay").show();
            var get_test_images = lab_base_url + "/view_images";

            $.ajax({
              url : get_test_images,
              type : "POST",
              responseType : "text",
              dataType : "text",
              data : "id="+id,
              
              success : function (response) {  
                       
                $(".spinner-overlay").hide();
                $("#modal").modal("show"); 
                $("#gallery").html(response); 
                $.notify({
                message:"Image Deleted Successfully"
                },{
                  type : "success"  
                });
              },
              error : function () {
                $(".spinner-overlay").hide();
              } 
            }); 
          }else{
            $(".spinner-overlay").hide();
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
        } 
      });
    });   
  }

  function rateValue(elem,range_higher,range_lower){
    var value = elem.value;
    document.querySelectorAll(".form-error").innerHTML = "";
     var parent = elem.parentElement;
      
      var child = parent.querySelector(".flag");
    if(value !== ""){
      if(value > range_higher){
        child.innerHTML = "H";
      }else if(value < range_lower){
        child.innerHTML = "L";
      }else{
         child.innerHTML = "";
      }
    }else{
       child.innerHTML = "";
    }   
  }

  function rateValue1(elem,desirable_value){
    var invalid_desirable = false;
    var value = elem.value;
    var desirable_first_char = desirable_value.charAt(0);
    var desirable_last_chars1 = desirable_value.substring(1);
    if(desirable_first_char != ">"){   
      if(isNaN(desirable_last_chars1)){         
        invalid_desirable = true;  
      }                          
    }

    if(desirable_first_char != "<"){
      if(isNaN(desirable_last_chars1)){
        invalid_desirable = true; 
      }
    }
    if(!invalid_desirable){
      document.querySelectorAll(".form-error").innerHTML = "";
      var parent = elem.parentElement;
      
      var child = parent.querySelector(".flag");
      if(value !== ""){
        desirable_last_chars1 = Number(desirable_last_chars1)
        console.log(desirable_last_chars1)
        if(desirable_first_char == ">"){
          if(value <= desirable_last_chars1){
            child.innerHTML = "L";
          }else{
            child.innerHTML = "";
          }
        }else{
          if(value >= desirable_last_chars1){
            child.innerHTML = "H";
          }else{
            child.innerHTML = "";
          }
        }

      }else{
         child.innerHTML = "";
      } 
    }  
  }

  function checkIfImageIsSelected (elem) {
    var btn = elem.parentElement.nextElementSibling.querySelector(".btn-primary");
    if(elem.value !== ""){
      btn.setAttribute("class", "btn btn-primary");
    }else{
      btn.setAttribute("class", "btn btn-primary disabled");
    }
  }

  function addComment () {
    $("#select-options-table-2").hide();
    $(".pathologists-comment-div").show(); 
  }

  function zipResults(lab_id) {
    $(".spinner-overlay").show();
    var lab_id = String(lab_id);
    var url = lab_base_url + "/get_if_pathologist_has_added_comment";
    //Check If Comment Has Been Added
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "check_if=true&lab_id="+lab_id,
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.successful == true){
          swal({
            title: 'Warning?',
            text: "Are You Sure You Want To Zip This Result? No One Else Can Edit This If You Proceed",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
          }).then((result) => {
            var url = lab_base_url +  "/zip_result";
            //Check If Comment Has Been Added
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "zip_result=true&lab_id="+lab_id,
              success : function(response){
                if(response.successful == true){
                  
                  $.notify({
                  message:"Result Zipped Successfully"
                  },{
                    type : "success"  
                  });
                  $("#test-result-form").html("");
                  loadPatient(lab_id);
                }else if(response.zipped == true){
                  $.notify({
                  message:"Sorry This Result Has Been Zipped Before"
                  },{
                    type : "warning"  
                  });
                }else if(response.comment_added == false){
                  $.notify({
                  message:"Sorry Your Comments Have Not Been Added To This Result"
                  },{
                    type : "danger"  
                  });
                }else if(response.unsuccessful == true){
                  $.notify({
                  message:"Sorry Something Went Wrong"
                  },{
                    type : "warning"  
                  });
                }
              },
              error : function () {
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              } 
            });  
          })
        }else if(response.unsuccessful == true){
          swal({
            title: 'Error!',
            text: "You Have To Add Your Comments To This Result To Proceed",
            type: 'error'           
          })
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
  }

  function unzipResults(lab_id) {
    $(".spinner-overlay").show();
    var lab_id = String(lab_id);
    var url = lab_base_url +  "/get_if_pathologist_has_added_comment";
    //Check If Comment Has Been Added
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "check_if=true&lab_id="+lab_id,
      success : function(response){
        $(".spinner-overlay").hide();
        if(response.successful == true){
          swal({
            title: 'Warning?',
            text: "Do You Want To Proceed",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
          }).then((result) => {
            var url = lab_base_url + "/unzip_result";
            //Check If Comment Has Been Added
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "unzip_result=true&lab_id="+lab_id,
              success : function(response){
                if(response.successful == true){
                  $.notify({
                  message:"Result Unzipped Successfully"
                  },{
                    type : "success"  
                  });
                  $("#test-result-form").html("");
                  loadPatient(lab_id);
                }else if(response.unzipped == true){
                  $.notify({
                  message:"Sorry This Result Has Not Been Zipped"
                  },{
                    type : "warning"  
                  });
                }else if(response.comment_added == false){
                  $.notify({
                  message:"Sorry Your Comments Have Not Been Added To This Result"
                  },{
                    type : "danger"  
                  });
                }else if(response.unsuccessful == true){
                  $.notify({
                  message:"Sorry Something Went Wrong"
                  },{
                    type : "warning"  
                  });
                }
              },
              error : function () {
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              } 
            });  
          })
        }else if(response.unsuccessful == true){
          swal({
            title: 'Error!',
            text: "You Have To Add Your Comments To This Result To Proceed",
            type: 'error'           
          })
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
  }

  function goBackSupervisor(elem,evt) {
    evt.preventDefault();
    var parent = elem.parentElement;
    parent.setAttribute('style', 'display: none;');
    $("#select-options-table-2").show();
  }

  function allResults() {
    $("#carry-actions-card").hide();
    $("#all-results-card").show();
  }

  function goBackFromAllResultsCard(elem,evt){
    $("#carry-actions-card").show();
    $("#all-results-card").hide();
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
            <span style="display: none;" id="user_name"><?php echo $user_name; ?></span>
            <span style="display: none;" id="name"></span>
            <div class="col-sm-12">

              <div class="card" id="view-results-images-card-sub-referral-previous" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromViewResultsImagesCardSubReferralPrevious(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Result</h3>
                  
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-results-card-sub-referral-previous" style="display: none;">
                <div class="card-header">
                  
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackViewResultsCardSubReferralsPrevious(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">
                                    
                </div> 
              </div>


              <div class="card" id="view-results-images-card-referral-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Results</h3>
                  <button onclick="goBackFromViewResultsImagesCardReferralPrevious(this,event)" class="btn btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-results-card-referred-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Results</h3>
                  <button onclick="goBackFromViewResultsCardReferredPrevious(this,event)" class="btn btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="selected-sub-tests-card-referral-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Selected Sub Tests</h3>
                  <button onclick="goBackFromSelectedSubTestsReferralPrevious(this,event)" class="btn btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="make-new-consultation-card-referral" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMakeNewConsultationCardReferral(this,event)" class="btn btn-warning">Go Back</button>
                  <!-- <h3 class="card-title">Begin New Consultation</h3> -->
                </div>
                <div class="card-body">

                  <div class="bio-data">
                    
                  </div>

                  <div class="card" id="selected-tests-card-referral" style="display: none;">
                    <div class="card-header">
                      <h2 class="card-title">Selected Tests</h2>
                      <button onclick="goBackFromSelectedTestsReferral(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="view-patient-results-images-card-referral" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
                      <button onclick="goBackFromViewPatientsResultsImagesCardReferral(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="view-patient-results-card-referral" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
                      <button onclick="goBackFromViewPatientsResultsCardReferral(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="view-sub-tests-results-card-referral" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" style="text-transform: capitalize;"></h3>
                      <button onclick="goBackFromViewSubTestsResultsCardReferral(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
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

                  <div class="card" id="view-sub-tests-results-and-images-card-referrals" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
                      <button onclick="goBackFromViewSubTestsResultsAndImagesCardReferrals(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

 
                  <?php  
                    $attr = array('id' => 'make-new-consultation-form-referral');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/make_new_consultation_form_referral',$attr);
                  ?>
                    <h3 class="text-center" style="margin-top: 40px;">Begin New Consultation</h3>
                    
                    <h4 class="form-sub-heading">Complaints</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                          <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">History</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                          <textarea name="history" id="history" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div> 

                    <h4 class="form-sub-heading">Examination Findings</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                          <textarea name="examination-findings" id="examination-findings" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>  

                    <h4 class="form-sub-heading">Diagnosis</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                          <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">Advice Given</h4>
                    <div class="wrap">
                      <div class="form-row">
                        
                        <div class="form-group col-sm-12">
                          <label for="advice" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                          <textarea name="advice" id="advice" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                        
                      </div>
                    </div>

                    <h4 class="form-sub-heading">Management</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <h5>Lab Tests: </h5>
                          <div class="select-tests-div">
                            <button id="select-tests-referrals" onclick="selectTestsBtnReferrals(this,event)" class="btn btn-info">Select Tests</button>


                            <div class="card" id="select-lab-to-use-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                                <button class="btn btn-warning" onclick="goBackFromSelectLabToUseReferrals(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                                <button onclick="selectThisFacilityReferrals(this,event)" id="use-yours-btn-referrals" class="btn btn-primary">Use Yours</button>
                                <button id="select-another-lab-referral" onclick="selectAnotherLabReferrals(this,event)" class="btn btn-info">Select Another Lab</button>
                              </div>
                            </div>

                            

                            <div class="card" id="view-results-images-card-sub-referral" style="display: none;">
                              <div class="card-header">
                                <button onclick="goBackFromViewResultsImagesCardSubReferral(this,event)" class="btn btn-warning">Go Back</button>
                                <h3 class="card-title">Result</h3>
                                
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>


                            <div class="card" id="select-lab-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                                <button type="button" onclick="goBackSelectLabsReferrals(this,event)" class="btn btn-warning">Go Back</button>
                              </div>
                              <div class="card-body">
                                
                              </div>
                            </div>

                            <div class="card" id="select-test-card-another-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Select Tests</h3>
                                <button type="button" onclick="goBackFromSelectTestCardAnotherReferrals(this,event)" class="btn btn-warning btn-round">Go Back</button>
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            <div class="card" id="sub-tests-card-referrals-another" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" style="text-transform: capitalize;"></h3>
                                <button  type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1ReferralsAnother(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                                  
                              </div> 
                            </div>

                            <div class="card" id="additional-information-on-tests-selected-card-another-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Enter Additional Patient Information</h3>
                                <button onclick="goBackFromAdditionalInformationOnTestsSelectedCardAnotherReferrals(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                                
                                <p><em class="text-primary">Note: No Field Is Required. Click The Proceed Button To Continue.</em></p>
                              </div>
                              <div class="card-body">

                                <div id="additional-information-on-tests-selected-form">
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


                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>


                            <div class="card" id="selected-sub-tests-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Selected Sub Tests Referral</h3>
                                <button onclick="goBackFromSelectedSubTestsReferral(this,event)" class="btn btn-warning">Go Back</button>
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            

                            <div class="card" id="view-results-card-referred" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Results</h3>
                                <button onclick="goBackFromViewResultsCardReferred(this,event)" class="btn btn-warning">Go Back</button>
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            <div class="card" id="view-results-images-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Results</h3>
                                <button onclick="goBackFromViewResultsImagesCardReferral(this,event)" class="btn btn-warning">Go Back</button>
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            <div class="card" id="additional-information-on-tests-selected-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Enter Additional Patient Information</h3>
                                <button onclick="goBackFromAdditionalInformationOnTestsSelectedCardReferrals(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                                
                                <p><em class="text-primary">Note: No Field Is Required. Click The Proceed Button To Continue.</em></p>
                              </div>
                              <div class="card-body">
                                
                                
                                <div id="additional-information-on-tests-selected-form">
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


                                  </div>
                                </div>
                                </div>
                              </div>
                            </div>

                            <div class="card" id="other-facility-select-test-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h2 class="card-title">Select Tests</h2>
                                <button onclick="goBackFromSelectOtherLabReferrals(this,event)" class="btn btn-warning">Go Back</button>
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            

                            <div class="card" id="sub-tests-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" style="text-transform: capitalize;"></h3>
                                <button  type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1Referrals(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                                  
                              </div> 
                            </div>

                            <div class="card" id="view-results-card-sub-referral" style="display: none;">
                              <div class="card-header">
                                
                                <button  type="button" class="btn btn-round btn-warning" onclick="goBackViewResultsCardSubReferrals(this,event)">Go Back</button>
                                <h3 class="card-title" style="text-transform: capitalize;"></h3>
                              </div>
                              <div class="card-body">
                                                  
                              </div> 
                            </div>

  

                            <p><a href="" id="selected-tests-referrals" onclick="selectedTestsReferrals(this,event)">View Previously Selected Tests</a></p>
                            <div class="card" id="selected-referral-tests-card" style="display: none;">
                              <div class="card-header">
                                <button  type="button" class="btn btn-round btn-warning" onclick="goBackSelectedReferralTestsCard(this,event)">Go Back</button>
                                <h3 class="card-title" style="text-transform: capitalize;">Selected Tests</h3>
                                
                              </div>
                              <div class="card-body">
                                                  
                              </div> 
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group col-sm-12">
                          <h5>Pharmacy: </h5>
                          <div class="select-pharmacy-div">
                            <button id="select-pharmacy-referral" onclick="selectPharmacyBtnReferrals(this,event)" class="btn btn-info">Pharmacy</button>
                            <p id="selected-drugs-referral" style="cursor: pointer;" onclick="viewPreviouslySelectedDrugsReferral(this,event)" class="text-primary">View Previously Selected Drugs</p>


                            <div class="card" id="select-pharmacy-to-use-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading">Select Pharmacy</h3>
                                <button class="btn btn-warning" onclick="goBackFromSelectPharmacyToUseReferral(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                                <button onclick="selectThisPharmacyReferral(this,event)" id="use-yours-btn-referral" class="btn btn-primary">Use Yours</button>
                                <button id="use-another-btn-referral" onclick="selectAnotherPharmacyReferral(this,event)" class="btn btn-info">Select Another Pharmacy</button>
                              </div>
                            </div>


                            <div class="card" id="selected-drugs-card-referrals" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title">Drugs Selected For Patient Previously</h3>
                                <button onclick="goBackFromSelectedDrugsReferrals(this,event)" class="btn btn-warning">Go Back</button>
                                
                              </div>
                              <div class="card-body">

                              </div>
                            </div>

                            <div class="card" id="select-pharmacy-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading">Select Pharmacy To Use</h3>
                                <button onclick="goBackSelectPharmaciesReferral(this,event)" class="btn btn-warning">Go Back</button>
                              </div>
                              <div class="card-body">
                                
                              </div>
                            </div>

                            <div class="card" id="select-drugs-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading"></h3>
                                <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectDrugsReferral(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                
                              </div>
                            </div>

                            <div class="card" id="selected-drugs-info-card-referral" style="display: none;">
                              <div class="card-header">
                                <h3 class="card-title" id="welcome-heading"></h3>
                                <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugsReferral(this,event)">Go Back</button>
                              </div>
                              <div class="card-body">
                                
                              </div>
                            </div>

                          </div>
                        </div>

                        <div class="form-group col-sm-12">
                          <?php  
                          if($clinic_structure == "standard"){
                          ?>
                          <h5>Referral / Consult</h5>
                          <?php }else{ ?>
                          <h5>Referral</h5> 
                          <?php } ?>
                          <div class="referral-consult-div">
                            
                            <?php
                              
                              if($clinic_structure == "standard"){
                            ?>
                            <button id="refer-consult-btn-referral" onclick="referOrConsultReferral(this,event)" type="button" class="btn btn-info">Refer / Consult</button>
                            <?php }else{ ?>
                            <button id="refer-consult-btn-referral" onclick="referOrConsultReferral(this,event)" type="button" class="btn btn-info">Make Referral</button>
                            <?php } ?>
                            <button id="go-back-refer-consult-referral" style="display: none;" onclick="goBackReferOrConsultReferral(this,event)" type="button" class="btn btn-warning">Go Back</button>
                            <div id="select-facility-for-referral-div-referral" style="display: none;">
                              
                            </div>

                            <div id="select-clinic-for-referral-div-referral" style="display: none;">
                              
                            </div>

                            <div id="select-clinic-for-consult-div-referral" style="display: none;">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> 

                    <input type="submit" class="btn btn-info">
                  </form>
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


              <div class="card" id="vital-signs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackVitalSigns(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Vital Signs Entered</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="vital-signs-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackVitalSignsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Vital Signs For</h3>
                </div>
                <div class="card-body">
                  
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
              
              <div class="card" id="previous-consultations-referrals-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviousConsultationsReferralsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Referrals /  Consults</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="first-clinic-consultation-referral-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromFirstClinicConsultationReferralCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">First Clinic Consultation</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="selected-drugs-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h2 class="card-title">Selected Drugs</h2>
                  <button onclick="goBackFromSelectedDrugsReferralFirstConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="selected-tests-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h2 class="card-title">Selected Tests</h2>
                  <button onclick="goBackFromSelectedTestsReferralFirstConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardReferralFirstConsultation(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardReferralFirstconsultation(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardReferralFirstConsultation(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardReferralFirstConsultation(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-referral-first-consultation" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardReferralFirstConsultation(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="previous-consultations-referral-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviousConsultationsReferralInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Referral Consultation Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-previously-selected-referral-tests-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviouslySelectedReferralTestsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <!-- <h3 class="card-title" id="welcome-heading">Selected Tests</h3> -->
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-previously-selected-referral-drugs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviouslySelectedReferralDrugsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <!-- <h3 class="card-title" id="welcome-heading">Selected Tests</h3> -->
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-referral-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardReferralPrevious(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-referral-previous" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientResultsCardReferralPrevious(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Tests Selected</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-referral-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardReferralPrevious(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-referrals-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardReferralsPrevious(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-referrals-previous" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardReferralsPrevious(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="add-new-consultation-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnNewConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Begin New Consultation</h3>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'make-new-consultation-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/make_new_consultation_ward',$attr);
                  ?>
                    
                    <h4 class="form-sub-heading">Complaints</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                          <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">History</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                          <textarea name="history" id="history" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div> 

                    <h4 class="form-sub-heading">Examination Findings</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                          <textarea name="examination-findings" id="examination-findings" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>  

                    <h4 class="form-sub-heading">Diagnosis</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                          <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">Advice Given</h4>
                    <div class="wrap">
                      <div class="form-row">
                        
                        <div class="form-group col-sm-12">
                          <label for="advice" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                          <textarea name="advice" id="advice" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                        
                      </div>
                    </div> 

                    <input type="submit" class="btn btn-info">
                  </form>
                </div>
              </div>

              <div class="card" id="edit-consultation-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnEditConsultation(this,event)" id="go-back" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Edit Your Consultation</h3>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'edit-consultation-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_consultation_ward',$attr);
                  ?>
                    
                    <h4 class="form-sub-heading">Complaints</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                          <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">History</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                          <textarea name="history" id="history" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div> 

                    <h4 class="form-sub-heading">Examination Findings</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                          <textarea name="examination-findings" id="examination-findings" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>  

                    <h4 class="form-sub-heading">Diagnosis</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                          <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">Advice Given</h4>
                    <div class="wrap">
                      <div class="form-row">
                        
                        <div class="form-group col-sm-12">
                          <label for="advice" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                          <textarea name="advice" id="advice" cols="10" rows="10" class="form-control" required></textarea>
                          <span class="form-error"></span>
                        </div> 
                        
                      </div>
                    </div> 

                    <input type="submit" class="btn btn-info">
                  </form>
                </div>
              </div>  

              <div class="card" id="patient-admission-data-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Patient On Admission Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="patient-bio-data-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackBioData(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Patient Bio Data</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="drs-current-consultations" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentConsultations(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Consultations</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="consults-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromConsultsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Consults</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-consultation-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Consultations</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

  
              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <h3 style="margin-bottom: 40px;" id="quest">Choose Action: </h3>
                  <button onclick="inputVitalSigns(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="on-appointment-card" style="display: none;">
                <div class="card-header">
                  <h4 class="card-title">All Patients With Appointments Today</h4>
                  <button class="btn btn-warning" onclick="goBackFromOnAppointmentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <!-- <button  class="btn btn-primary">New Patients</button>
                  <button  class="btn btn-info">Patients On Appointments Today</button>
                  <button  class="btn btn-success"></button> -->
                  <div class="table-responsive">
      
                    <table class="table table-test table-striped table-bordered  nowrap hover display" id="select-options-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="newPatients(this,event)">New Patients</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="onAppointment(this,event)">Patients On Appointments Today</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="offAppointment(this,event)">Patients Off Appointments</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td onclick="openPatientsInWard(this,event)">Patients In Ward</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td onclick="viewReferralsOrConsults(this,event)">View Referrals Or Consults</td>
                        </tr>
                        <?php 
                        $str = "Perform " ;
                        $lab_slug = $this->onehealth_model->getDeptParamById("slug",1);
                        if($sub_dept_id == 13){
                          $str .= "Chemical Pathologists";
                          $lab_sub_dept_id = 1;
                          $lab_sub_dept_slug = $this->onehealth_model->getSubDeptParamById("slug",$lab_sub_dept_id);
                          $lab_personnel_slug = $this->onehealth_model->getPersonnelParamById("slug",8);
                          $url = site_url("onehealth/index/".$addition . "/".$lab_slug."/".$lab_sub_dept_slug."/".$lab_personnel_slug);
                        }else if($sub_dept_id == 14){
                          $str .= "Haematologists";
                          $lab_sub_dept_id = 3;
                          $lab_sub_dept_slug = $this->onehealth_model->getSubDeptParamById("slug",$lab_sub_dept_id);
                          $lab_personnel_slug = $this->onehealth_model->getPersonnelParamById("slug",29);
                          $url = site_url("onehealth/index/".$addition . "/".$lab_slug."/".$lab_sub_dept_slug."/".$lab_personnel_slug);
                        }else if($sub_dept_id == 16){
                          $str .= "Microbiologists";
                          $lab_sub_dept_id = 2;
                          $lab_sub_dept_slug = $this->onehealth_model->getSubDeptParamById("slug",$lab_sub_dept_id);
                          $lab_personnel_slug = $this->onehealth_model->getPersonnelParamById("slug",19);
                          $url = site_url("onehealth/index/".$addition . "/".$lab_slug."/".$lab_sub_dept_slug."/".$lab_personnel_slug);
                        }else if($sub_dept_id == 19){
                          $str .= "Histopathologists";
                          $lab_sub_dept_id = 7;
                          $lab_sub_dept_slug = $this->onehealth_model->getSubDeptParamById("slug",$lab_sub_dept_id);
                          $lab_personnel_slug = $this->onehealth_model->getPersonnelParamById("slug",56);
                          $url = site_url("onehealth/index/".$addition . "/".$lab_slug."/".$lab_sub_dept_slug."/".$lab_personnel_slug);
                        }
                        $str .= " Lab Functions";

                        if($str != "Perform  Lab Functions"){
                        ?>
                        <tr>
                          <td>6</td>
                          <td onclick="performLabFunctions(this,event,'<?php echo $url ?>','<?php echo $str ?>',1,<?php echo $lab_sub_dept_id; ?>)"><?php echo $str; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>

              <div class="card" id="all-results-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Results (Verified/Unverified)</h3>
                  <button type="button" class="btn btn-warning" onclick="goBackFromAllResultsCard(this,event)">Go Back</button>
                </div>
                
              </div>

              <div class="card" id="view_patient_info" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title"></h3>
                  <button type="button" class="btn btn-warning" onclick="goBackFromViewPatientInfoCard(this,event)">Go Back</button>
                  <a href="#select-options-table-2" class="btn btn-info">Select Option</a>
                </div>
                <div class="card-body">
                  <div class="patient-info-div">
                    <div class="wrap">
                      <h3>Personal Information</h3>
                      <p class="text-capital">FirstName: <span id="first_name"></span></p>
                      <p class="text-capital">LastName: <span id="last_name"></span></p>
                      <p class="text-capital">Date Of Birth: <span id="dob"></span></p>
                      <p class="text-capital">Age: <span id="age"></span></p>
                      <p class="text-capital">Gender: <span id="sex"></span></p>
                      <p class="text-capital">Race/Tribe: <span id="race"></span></p>
                      <p class="text-capital">Mobile No.:  <span id="mobile"></span></p>
                      <p class="text-capital">Email: <span id="email"></span></p>
                    </div>
                    <div class="wrap">
                      <h3>Medical Information</h3>
                      <p class="text-capital">Height: <span id="height"></span></p>
                      <p class="text-capital">Weight: <span id="weight"></span></p>
                      <p class="text-capital">Fasting: <span id="fasting"></span></p>
                      <p class="text-capital">Present Medications: <span id="present_medications"></span></p>
                      <p class="text-capital">LMP: <span id="lmp"></span></p>
                      <p class="text-capital">Clinical Summary/Diagnosis: <span id="clinical_summary"></span></p>
                      <p class="text-capital">Samples:  <span id="sample"></span></p>
                      <p class="text-capital">Referring Dr: <span id="referring_dr"></span></p>
                      <p class="text-capital">Consultant Name:  <span id="consultant"></span></p>
                      <p class="text-capital">Consultant Email:  <span id="consultant_email"></span></p>
                      <p class="text-capital">Consultant Mobile No.:  <span id="consultant_mobile"></span></p>
                      <p class="text-capital">Pathologist Name:  <span id="pathologist"></span></p>
                      <p class="text-capital">Pathologist Email:  <span id="pathologist_email"></span></p>
                      <p class="text-capital">Pathologist Mobile No.:  <span id="pathologist_mobile"></span></p>
                      <p class="text-capital">Address:  <span id="address"></span></p>
                    </div>
                    <div id="test-result-div">
                      <?php
                        $attr = array('id' => 'test-result-form');
                        echo form_open_multipart('',$attr);
                      ?>

                      </form>
                    </div>
                    
                  </div>
                </div> 
              </div> 

              <div class="card" id="results-awaiting-comments-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Results Awaiting Your Comments</h3>
                  <button type="button" class="btn btn-warning" onclick="goBackFromResultsAwaitingCommentsCard(this,event)">Go Back</button>
                </div>
                
              </div> 

              <div class="card" id="carry-actions-card" style="display: none;">
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title"> </h3>
                  <button type="button" class="btn btn-warning" onclick="goBackFromPerformLabFunctionsCard(this,event)">Go Back</button>                
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-test table-striped table-bordered nowrap hover display" id="select-options-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr onclick="displayResultAwaitingComment()">
                          <td>1</td>
                          
                          <td>Result Awaiting Pathologists Comment</td>
                          
                        </tr>
                        <tr onclick="allResults()">
                          <td>2</td>
                          <td>All Results (Verified/Unverified)</td>
                        </tr>
                        <!-- <tr onclick="displayPrintedResults()">
                          <td>3</td>
                          <td>All Printed Results</td>
                        </tr> -->
                        <!-- <tr onclick="displayYetToPrintResults()">
                          <td>4</td>
                          <td>All Results Yet To Be Printed</td>
                        </tr> -->
                        <!-- <tr onclick="printResult()">
                          <td>5</td>
                          <td>Printing Result</td>
                        </tr> -->
                      </tbody>
                    </table>
                  </div>
                </div> 
              </div> 

              <div class="card" id="input-output-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackInputOutputInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Vital Signs For</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="input-output-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackInputOutput(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Input Output Chart</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="previously-registered-patients-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>
                  
                  <h4 style="margin-bottom: 40px;" id="quest">All Registered Patients</h4>
                  <p>Click To Input Values</p>
                </div>
              </div>

              <div class="card" id="wards-with-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromWardsWithPatientsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Wards With Patients In Them</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="request-lab-tests-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Choose Action</h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" onclick="onAdmissionTests(this,event)">View Tests Selected On Admission</button>
                  <button class="btn btn-info" onclick="currentTests(this,event)">View Tests Selected During Admission</button>
                </div>
              </div>

              <div class="card" id="medication-chart-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMedicationChart(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Choose Action</h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" onclick="onAdmissionDrugs(this,event)">View Drugs Selected On Admission</button>
                  <button class="btn btn-info" onclick="currentDrugs(this,event)">View Drugs Selected During Admission</button>
                </div>
              </div>

              <div class="card" id="on-admission-drugs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionDrugs(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Drugs Selected On Admission</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="current-drugs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentDrugs(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Drugs Selected During Admission</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="current-drugs-card-info" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentDrugsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Drugs</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="medication-chart-for-drug-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMedicationChartForDrug(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="selected-drugs-info-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugsWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="select-pharmacy-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Select Pharmacy To Use</h3>
                  <button onclick="goBackSelectPharmaciesWard(this,event)" class="btn btn-warning">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


               <div class="card" id="select-drugs-card-ward" style="display: none;">
                  <div class="card-header">
                    <h3 class="card-title" id="welcome-heading"></h3>
                    <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectDrugsWard(this,event)">Go Back</button>
                  </div>
                  <div class="card-body">
                    
                  </div>
                </div>

              <div class="card" id="select-pharmacy-to-use-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Select Pharmacy</h3>
                  <button class="btn btn-warning" onclick="goBackFromSelectPharmacyToUseWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="selectThisPharmacyWard(this,event)" id="use-yours-btn" class="btn btn-primary">Use Yours</button>
                  <button onclick="selectAnotherPharmacyWard(this,event)" class="btn btn-info">Select Another Pharmacy</button>
                </div>
              </div>

              <div class="card" id="on-admission-selected-tests" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Tests On Admission</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              

              <div class="card" id="current-selected-tests" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Tests Selected During Admission</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardWardDuringAdmisssion(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>


  
              <div class="card" id="wards-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromWardPatientsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="select-mortuary-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Select Mortuary</h3>
                  <button class="btn btn-warning" onclick="goBackFromSelectMortuaryWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

    
              <div class="card" id="select-mortuary-to-use-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Select Mortuary</h3>
                  <button class="btn btn-warning" onclick="goBackFromSelectMortuaryToUseWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="selectThisFacilityMortuaryWard(this,event,'other')" id="use-yours-btn-mortuary" class="btn btn-primary">Use Yours</button>
                  <button onclick="selectAnotherMortuaryWard(this,event)" class="btn btn-info">Select Another Mortuary</button>
                </div>
              </div>

              <div class="card" id="off-appointment-patient-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOffAppointmentPatientCard(this)" class="btn btn-warning btn-round" id="go-back">Go Back</button>
                  <a href="#drs-functions-card" class="btn btn-info btn-round">Start Consultation</a>
                  <h3 class="card-title card-title-1" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">
                  <h4 class="text-center">Vital Signs</h4>
                  <?php 
                  $attr = array('id' => 'vital-signs-form');
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
                  
                  <div id="drs-functions">
                    <div class="card" id="drs-functions-card">
                      <div class="card-header">
                        <h4 class="card-title" id="welcome-heading">Dr's Functions</h4>
                      </div>
                      <div class="card-body">
                        <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                        <button onclick="startNewConsultation(this,event)" class="btn btn-primary">Start New Consultation</button>
                        <button onclick="displayPreviousConsultations(this,event)" class="btn btn-info">Display Previous Consultations</button>
                      </div>
                    </div>
                  <?php if($health_facility_structure == "hospital"){ ?>

                    <div class="card" id="select-mortuary-to-use-card" style="display: none;">
                      <div class="card-header">
                        <h3 class="card-title" id="welcome-heading">Select Mortuary</h3>
                        <button class="btn btn-warning" onclick="goBackFromSelectMortuaryToUse(this,event)">Go Back</button>
                      </div>
                      <div class="card-body">
                        <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                        <button onclick="selectThisFacilityMortuary(this,event,'other')" id="use-yours-btn-mortuary" class="btn btn-primary">Use Yours</button>
                        <button onclick="selectAnotherMortuary(this,event)" class="btn btn-info">Select Another Mortuary</button>
                      </div>
                    </div>
  
                    <div class="card" id="select-lab-to-use-card" style="display: none;">
                      <div class="card-header">
                        <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                        <button class="btn btn-warning" onclick="goBackFromSelectLabToUse(this,event)">Go Back</button>
                      </div>
                      <div class="card-body">
                        <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                        <button onclick="selectThisFacility(this,event,'yours')" id="use-yours-btn" class="btn btn-primary">Use Yours</button>
                        <button onclick="selectAnotherLab(this,event)" class="btn btn-info">Select Another Lab</button>
                      </div>
                    </div>

                    <div class="card" id="select-pharmacy-to-use-card" style="display: none;">
                      <div class="card-header">
                        <h3 class="card-title" id="welcome-heading">Select Pharmacy</h3>
                        <button class="btn btn-warning" onclick="goBackFromSelectPharmacyToUse(this,event)">Go Back</button>
                      </div>
                      <div class="card-body">
                        <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                        <button onclick="selectThisPharmacy(this,event)" id="use-yours-btn" class="btn btn-primary">Use Yours</button>
                        <button onclick="selectAnotherPharmacy(this,event)" class="btn btn-info">Select Another Pharmacy</button>
                      </div>
                    </div>
                  <?php } ?>

                  <div class="card" id="select-drugs-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" id="welcome-heading"></h3>
                      <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectDrugs(this,event)">Go Back</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  

  
                  <div class="card" id="selected-drugs-info-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" id="welcome-heading"></h3>
                      <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugs(this,event)">Go Back</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>


                  <div class="card" id="select-mortuary-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" id="welcome-heading">Select Mortuary</h3>
                      <button onclick="goBackSelectMortuaryCard(this,event)" class="btn btn-warning">Go Back</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>
  
                  
                  <div class="card" id="select-lab-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                      <button onclick="goBackSelectLabs(this,event)" class="btn btn-warning">Go Back</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="select-pharmacy-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" id="welcome-heading">Select Pharmacy To Use</h3>
                      <button onclick="goBackSelectPharmacies(this,event)" class="btn btn-warning">Go Back</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="choose-consultation-to-display-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromChooseConsultationToDisplayCard(this,event)">Go Back</button>
                      <h3 class="card-title">Choose Previous Consultation To Display</h3>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
      
                        <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-3" cellspacing="0" width="100%" style="width:100%">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Option</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td onclick="viewPreviousClinicConsultations(this,event)">Clinic Consultations</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td onclick="viewPreviousWardConsultations(this,event)">Ward Consultations</td>
                            </tr>
                            <!-- <tr>
                              <td>3</td>
                              <td onclick="viewPreviousConsultConsultations(this,event)">Consult Consultations</td>
                            </tr>

                            <tr>
                              <td>4</td>
                              <td onclick="viewPreviousReferralConsultations(this,event)">Referral Consultations</td>
                            </tr> -->
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="card" id="previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Previous Clinic Consultations</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>


                  <div class="card" id="previous-ward-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousWardConsultationsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Previous Ward Records</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="previous-ward-consultations-info-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousWardConsultationsInfoCard(this,event)">Go Back</button>
                      <h3 class="card-title">Previous Ward Records</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="edit-previous-ward-consultation-card" style="display: none;">
                    <div class="card-header">
                      <button style="" class="btn btn-warning" type="button" onclick="goBackFromEditPreviousWardConsultationForm(this,event)">Go Back</button>
                      <h3 class="card-title">Edit This Consultation</h3>
                    </div>
                    <div class="card-body">
                      <?php  
                          $attr = array('id' => 'edit-previous-ward-consultation-form');
                          echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_previous_ward_consultation_form',$attr);
                        ?>
                          
                          <h4 class="form-sub-heading">Complaints</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                                <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>

                          <h4 class="form-sub-heading">History</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                                <textarea name="history" id="history" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div> 

                          <h4 class="form-sub-heading">Examination Findings</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                                <textarea name="examination_findings" id="examination_findings" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>  

                          <h4 class="form-sub-heading">Diagnosis</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                                <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>

                          <h4 class="form-sub-heading">Advice Given</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                                <textarea name="advice_given" id="advice_given" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>

                          <input type="submit" class="btn btn-primary">
                        </form>

                    </div>
                  </div>

                  <div class="card" id="edit-previous-clinic-consultation-card" style="display: none;">
                    <div class="card-header">
                      <button style="" class="btn btn-warning" type="button" onclick="goBackFromEditPreviousClinicConsultationForm(this,event)">Go Back</button>
                      <h3 class="card-title">Edit This Consultation</h3>
                    </div>
                    <div class="card-body">
                      <?php  
                          $attr = array('id' => 'edit-previous-clinic-consultation-form');
                          echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_previous_clinic_consultation_form',$attr);
                        ?>
                          
                          <h4 class="form-sub-heading text-center">Vital Signs</h4>
                          <div class="wrap">
                            <div class="container">
                              <div class="row">
                                <h5 class="col-sm-6" style="font-weight: bold;">Pulse Rate (b/min): </h5>
                                <h6 class="col-sm-6 text-primary" id="pr" style="font-style: italic;"></h6>

                                <h5 class="col-sm-6" style="font-weight: bold;">Respiratory Rate (c/min): </h5>
                                <h6 class="col-sm-6 text-primary" id="rr" style="font-style: italic;"></h6>

                                <h5 class="col-sm-6" style="font-weight: bold;">Blood Pressure (mmHg): </h5>
                                <h6 class="col-sm-6 text-primary" id="bp" style="font-style: italic;"></h6>

                                <h5 class="col-sm-6" style="font-weight: bold;">Temperature (&deg; C): </h5>
                                <h6 class="col-sm-6 text-primary" id="temperature" style="font-style: italic;"></h6>


                                <h5 class="col-sm-6" style="font-weight: bold;">Waist Circumference (cm): </h5>
                                <h6 class="col-sm-6 text-primary" id="waist_circumference" style="font-style: italic;"></h6>

                                <h5 class="col-sm-6" style="font-weight: bold;">Hip Circumference (cm): </h5>
                                <h6 class="col-sm-6 text-primary" id="hip_circumference" style="font-style: italic;"></h6>
                              </div>
                            </div>
                          </div>

                          <h4 class="form-sub-heading">Complaints</h4>
                          <div class="wrap">
                            <input type="hidden" name="record_id" class="record_id">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                                <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>

                          <h4 class="form-sub-heading">History</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                                <textarea name="history" id="history" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div> 

                          <h4 class="form-sub-heading">Examination Findings</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                                <textarea name="examination-findings" id="examination-findings" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>  

                          <h4 class="form-sub-heading">Diagnosis</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                                <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                            </div>
                          </div>

                          <h4 class="form-sub-heading">Management</h4>
                          <div class="wrap">
                            <div class="form-row">
                              <div class="form-group col-sm-12">
                                <h5>Lab Tests: </h5>
                                <div class="">
                                  
                                  <p id=""><a href="#" id="view-selected-tests-previous-clinic-consultations" onclick="viewSelectedTestsPreviousClinicConsultations(this,event)">View Previously Selected Tests</a></p>
                                </div>
                              </div>
                              <div class="form-group col-sm-12">
                                <label for="advice" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                                <textarea name="advice" id="advice" cols="10" rows="10" class="form-control"></textarea>
                                <span class="form-error"></span>
                              </div> 
                              <div class="form-group col-sm-12">
                                <h5>Pharmacy: </h5>
                                <div class="">
                                  <p id="view-selected-drugs-previous-clinic-consultations" style="cursor: pointer;" onclick="viewPreviouslySelectedDrugsPreviousClinicConsultations(this,event)" class="text-primary">View Previously Selected Drugs</p>
                                </div>
                              </div>
                            </div>
                          </div> 
                          <input type="submit" class="btn btn-primary">
                        </form>

                    </div>
                  </div>


                   <div class="card" id="selected-drugs-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsSelectedDrugsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Drugs Selected For Patient Previously</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="selected-tests-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsSelectedTestsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Selected Tests</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>



                  <div class="card" id="view-results-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsViewResultsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Test Results</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>

                  <div class="card" id="view-results-images-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsViewResultsImagesCard(this,event)">Go Back</button>
                      <h3 class="card-title">Test Results</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>


                  <div class="card" id="view-tests-results-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsViewTestsResultsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Selected Tests</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>
  

                  <div class="card" id="view-tests-results-images-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsViewTestsResultsImagesCard(this,event)">Go Back</button>
                      <h3 class="card-title">Selected Tests</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>


                  <div class="card" id="selected-sub-tests-previous-clinic-consultations-card" style="display: none;">
                    <div class="card-header">
                      <button class="btn btn-warning" onclick="goBackFromPreviousClinicConsultationsSelectedSubTestsCard(this,event)">Go Back</button>
                      <h3 class="card-title">Selected Tests</h3>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>
  

                  <?php  
                    $attr = array('id' => 'new-consultation-form','style' => 'display : none; margin-top: 40px;');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit-dr-input-form',$attr);
                  ?>
                  
                    <h3 class="text-center">Begin New Consultation</h3>
                    <button style="" class="btn btn-warning" type="button" onclick="goBackFromNewConsultationForm(this,event)">Go Back</button>
                    <h4 class="form-sub-heading">Complaints</h4>
                    <div class="wrap">
                      
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="complaints" class="label-control"><span class="form-error1">* </span> Complaints: </label>
                          <textarea name="complaints" id="complaints" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">History</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="history" class="label-control"><span class="form-error1">* </span> History: </label>
                          <textarea name="history" id="history" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div> 

                    <h4 class="form-sub-heading">Examination Findings</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="examination-findings" class="label-control"><span class="form-error1">* </span> Examination Findings: </label>
                          <textarea name="examination-findings" id="examination-findings" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>  

                    <h4 class="form-sub-heading">Diagnosis</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="diagnosis" class="label-control"><span class="form-error1">* </span> Diagnosis: </label>
                          <textarea name="diagnosis" id="diagnosis" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <h4 class="form-sub-heading">Management</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <h5>Lab Tests: </h5>
                          <div class="select-tests-div">
                            <button id="select-tests" onclick="selectTestsBtn(this,event)" class="btn btn-info">Select Tests</button>
                            <p id="selected-tests"><a href="" onclick="selectedTests(this,event)">View Previously Selected Tests</a></p>
                          </div>
                        </div>
                        <div class="form-group col-sm-12">
                          <label for="advice" class="label-control"><span class="form-error1">* </span> Advice Given: </label>
                          <textarea name="advice" id="advice" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div> 
                        <div class="form-group col-sm-12">
                          <h5>Pharmacy: </h5>
                          <div class="select-pharmacy-div">
                            <button id="select-tests" onclick="selectPharmacyBtn(this,event)" class="btn btn-info">Pharmacy</button>
                            <p id="selected-drugs" style="cursor: pointer;" onclick="viewPreviouslySelectedDrugs(this,event)" class="text-primary">View Previously Selected Drugs</p>
                          </div>
                        </div>

                        <div class="form-group col-sm-12">
                          <h5>Admit Patient To Ward: </h5>
                          <div class="admit-to-ward-div">
                            <button id="admit-to-ward" onclick="admitToWard(this,event)" type="button" class="btn btn-info">Admit</button>
                            <button id="go-back-admit-to-back" style="display: none;" onclick="goBackAdmitToWard(this,event)" type="button" class="btn btn-warning">Go Back</button>
                            <div id="select-ward-div" style="display: none;">
                              <?php 
                                $wards = $this->onehealth_model->displayAllWards($health_facility_id);
                                if(is_array($wards) && count($wards) > 0){
                                  $q = 0;
                              ?>
                              <h6>Click Ward To Select It</h6>
                              <div class="table-responsive">
                                <table class="table table-striped table-bordered nowrap hover display"  cellspacing="0" id="select-wards-table" width="100%" style="width:100%">
                                  <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Ward Name</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    foreach($wards as $row){ 
                                      $q++;
                                      $id = $row->id;
                                      $name = $row->name;
                                    ?>
                                    <tr onclick="selectedWard(this,event,<?php echo $id; ?>)">
                                      <td><?php echo $q; ?></td>
                                      <td style="text-transform: capitalize;"><?php echo $name; ?></td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>

                        <div class="form-group col-sm-12">
                          <?php if($clinic_structure == "standard"){ ?>
                          <h5>Referral / Consult</h5>
                          <?php }else{ ?>
                          <h5>Referral</h5>
                          <?php } ?>
                          <div class="referral-consult-div">
                            
                            <?php if($clinic_structure == "standard"){ ?>
                            <button id="refer-consult-btn" onclick="referOrConsult(this,event)" type="button" class="btn btn-info">Refer / Consult</button>
                            <?php }else{ ?>
                            <button id="refer-consult-btn" onclick="referOrConsult(this,event)" type="button" class="btn btn-info">Make Referrral</button>
                            <?php } ?>
                            <button id="go-back-refer-consult" style="display: none;" onclick="goBackReferOrConsult(this,event)" type="button" class="btn btn-warning">Go Back</button>
                            <div id="select-facility-for-referral-div" style="display: none;">
                              
                            </div>

                            <div id="select-clinic-for-referral-div" style="display: none;">
                              
                            </div>

                            <div id="select-clinic-for-consult-div" style="display: none;">
                              
                            </div>
                          </div>
                        </div>

                        <div class="form-group col-sm-12">
                          <h5>Mortuary: </h5>
                          <div id="select-mortuary-div">
                            <button id="select-tests" onclick="selectMortuaryBtn(this,event)" class="btn btn-info">Select Mortuary</button>
                            
                          </div>
                        </div>
                      </div>
                    </div> 

                    <h4 class="form-sub-heading">Future Appointments (Optional)</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="future-appointment">Select Date For Future Appointment</label>
                          <input type="date" name="future-appointment" id="future-appointment" class="form-control">
                        </div>
                      </div>
                    </div>    
                    <input type="submit" class="btn btn-info">
                  </form>
                  <?php if($health_facility_structure == "hospital"){ ?>
  
                  <div class="card" id="select-test-card" style="display: none;">
                    <div class="card-header">
                      <h2 class="card-title">Select Tests</h2>
                      <button onclick="goBackFromSelectLab(this,event)" class="btn btn-warning">Go Back</button>
                      <button class="btn btn-info proceed">Proceed</button>
                    </div>
                    <div class="card-body">
                      
                    </div>
                  </div>
                  <?php } ?>

                  <div class="card" id="selected-tests-card" style="display: none;">
                    <div class="card-header">
                      <h2 class="card-title">Selected Tests</h2>
                      <button onclick="goBackFromSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                      
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

                  <div class="card" id="view-patient-results-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title"></h3>
                      <button onclick="goBackFromViewPatientsResultsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
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

                  <div class="card" id="view-sub-tests-results-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title" style="text-transform: capitalize;"></h3>
                      <button onclick="goBackFromViewSubTestsResultsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                      
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

                  <div class="card" id="selected-sub-tests-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Selected Sub Tests</h3>
                      <button onclick="goBackFromSelectedSubTests(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  

                  <div class="card" id="view-results-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Test Results</h3>
                      <button onclick="goBackFromViewResults(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="view-results-images-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Test Results</h3>
                      <button onclick="goBackFromViewResultsImages(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="selected-drugs-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Drugs Selected For Patient Previously</h3>
                      <button onclick="goBackFromSelectedDrugs(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>


                  <div class="card" id="view-results-card-sub" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Test Results</h3>
                      <button onclick="goBackFromViewResultsSub(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="view-results-images-card-sub" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Test Results</h3>
                      <button onclick="goBackFromViewResultsImagesSub(this,event)" class="btn btn-warning">Go Back</button>
                      
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                  <div class="card" id="additional-information-on-tests-selected-card-another" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Enter Additional Patient Information</h3>
                      <button onclick="goBackFromAdditionalInformationOnTestsSelectedCardAnother(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                      
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


                        </div>
                      </div>
                      </form>
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


                        </div>
                      </div>
                      </form>
                    </div>
                  </div>

                  <div class="card" id="select-test-card-another" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Select Tests</h3>
                      <button onclick="goBackFromSelectTestCardAnother(this,event)" class="btn btn-warning btn-round">Go Back</button>
                    </div>
                    <div class="card-body">

                    </div>
                  </div>
    
                  <div class="card" id="other-facility-select-test-card" style="display: none;">
                    <div class="card-header">
                      <h3 class="card-title">Select Tests</h3>
                      <button onclick="goBackFromSelectOtherLab(this,event)" class="btn btn-warning btn-round">Go Back</button>
                    </div>
                    <div class="card-body">

                    </div>
                  </div>

                </div>
                </div>
              </div>

              <div class="card" id="new-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromNewPatientsCard(this,event)" class="btn btn-warning btn-round">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">New Patients</h3>
                  
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="off-appointments-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromOffAppointmentsCard(this,event)" class="btn btn-warning btn-round">Go Back</button>
                  <h3 class="card-title">All Patients Off Appointments</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

            </div>

            <div class="card" id="clinical-notes-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackClinicalNotes(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">Previous Clinical Notes</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="view-clinical-note-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackViewClinicalNote(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">Clinical Note</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="request-services-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackRequestServices(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">All Services</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="select-lab-to-use-card-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                <button class="btn btn-warning" onclick="goBackFromSelectLabToUseWard(this,event)">Go Back</button>
              </div>
              <div class="card-body">
                <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                <?php if($health_facility_structure == "hospital"){ ?>
                <button onclick="selectThisFacilityWard(this,event,'other')" id="use-yours-btn" class="btn btn-primary">Use Yours</button>
                <?php } ?>
                <button onclick="selectAnotherLabWard(this,event)" class="btn btn-info">Select Lab</button>
              </div>
            </div>

            <div class="card" id="select-lab-card-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title" id="welcome-heading">Select Lab</h3>
                <button onclick="goBackSelectLabsWard(this,event)" class="btn btn-warning">Go Back</button>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="select-test-card-another-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Select Tests</h3>
                <button onclick="goBackFromSelectTestCardAnotherWard(this,event)" class="btn btn-warning btn-round">Go Back</button>
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="additional-information-on-tests-selected-card-another-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Enter Additional Patient Information</h3>
                <button onclick="goBackFromAdditionalInformationOnTestsSelectedCardAnotherWard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                
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


                  </div>
                </div>
                </form>
              </div>
            </div>
    

            <div class="card" id="requested-services-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackRequestedServices(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">Previous Requests Of Service</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="view-other-charts-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackViewOtherCharts(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">Other Charts In This Facility</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="other-charts-info-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackOtherChartsInfo(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading"></h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="other-charts-info-card1" style="display: none;">
              <div class="card-header">
                <button onclick="goBackOtherChartsInfo1(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading"></h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="report-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackReportCard(this,event)" class="btn btn-warning">Go Back</button>
                <h3 class="card-title" id="welcome-heading">Previous Reports</h3>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="report-info-card" style="display: none;">
              <div class="card-header">
                <button onclick="goBackReportInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                <h2 class="card-title" id="welcome-heading"></h2>
              </div>
              <div class="card-body">
                
              </div>
            </div>

            <div class="card" id="ward-selected-sub-tests-card" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Selected Sub Tests</h3>
                <button onclick="goBackFromWardSelectedSubTests(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="ward-selected-sub-tests-card1" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Selected Sub Tests</h3>
                <button onclick="goBackFromWardSelectedSubTests1(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>


            <div class="card" id="ward-view-results-card" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResults(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="ward-view-results-card1" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResults1(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

  
            <div class="card" id="ward-view-results-images-card" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsImages(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="ward-view-results-images-card1" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsImages1(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="ward-view-results-card-sub" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsSub(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>


            <div class="card" id="ward-view-results-card-sub1" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsSub1(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>


            <div class="card" id="ward-view-results-images-card-sub" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsImagesSub(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="ward-view-results-images-card-sub1" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Test Results</h3>
                <button onclick="goBackFromWardViewResultsImagesSub1(this,event)" class="btn btn-warning">Go Back</button>
                
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="other-facility-select-test-card-ward" style="display: none;">
              <div class="card-header">
                <h2 class="card-title">Select Tests</h2>
                <button onclick="goBackFromSelectOtherLabWard(this,event)" class="btn btn-warning btn-round">Go Back</button>
              </div>
              <div class="card-body">

              </div>
            </div>

            <div class="card" id="additional-information-on-tests-selected-card-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Enter Additional Patient Information</h3>
                <button onclick="goBackFromAdditionalInformationOnTestsSelectedCardWard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                
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


                  </div>
                </div>
                </form>
              </div>
            </div>

            <div class="card" id="sub-tests-card-ward" style="display: none;">
              <div class="card-header">
                <h3 class="card-title" style="text-transform: capitalize;"></h3>
                <button  type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1Ward()">Go Back</button>
              </div>
              <div class="card-body">
                                  
              </div> 
            </div>

            <div class="card" id="sub-tests-card-ward-another" style="display: none;">
              <div class="card-header">
                <h3 class="card-title" style="text-transform: capitalize;"></h3>
                <button  type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1WardAnother(this,event)">Go Back</button>
              </div>
              <div class="card-body">
                                  
              </div> 
            </div>

            <div class="modal fade" data-backdrop="static" id="enter-mortuary-details-modal-ward" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Enter Details To Be Sent To The Mortuary</h4>
                    
                  </div>


                  <div class="modal-body" id="modal-body">
                    <?php
                      $attr = array('id' => 'enter-mortuary-details-form-ward');
                     echo form_open('',$attr);
                    ?>
                      <div class="form-group">
                        <label for="time_of_death"><span class="form-error1">*</span> Enter Time Of Death</label>
                        <input type="text" name="time_of_death" id="time_of_death" class="form-control" required>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group">
                        <p class="label"><span class="form-error1">*</span> Request Autopsy: </p>
                        <div id="request_autopsy">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="request_autopsy" value="1" id="1" checked> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="request_autopsy" value="0" id="0"> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                      <input type="submit" class="btn btn-success" value="PROCEED">
        
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="mortuaryDetailsModalClosed(this,event)" data-dismiss="modal" >Close</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" data-backdrop="static" id="enter-mortuary-details-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Enter Details To Be Sent To The Mortuary</h4>
                    
                  </div>


                  <div class="modal-body" id="modal-body">
                    <?php
                      $attr = array('id' => 'enter-mortuary-details-form');
                     echo form_open('',$attr);
                    ?>
                      <div class="form-group">
                        <label for="time_of_death"><span class="form-error1">*</span> Enter Time Of Death</label>
                        <input type="text" name="time_of_death" id="time_of_death" class="form-control" required>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group">
                        <p class="label"><span class="form-error1">*</span> Request Autopsy: </p>
                        <div id="request_autopsy">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="request_autopsy" value="1" id="1" checked> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="request_autopsy" value="0" id="0"> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                      <input type="submit" class="btn btn-success" value="PROCEED">
        
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="mortuaryDetailsModalClosed(this,event)" data-dismiss="modal" >Close</button>
                  </div>
                </div>
              </div>
            </div> 

            <div id="proceed-from-additional-info-tests-selected-btn-another-referrals" onclick="proceedFromAdditionalTestsSelectedAnotherReferrals(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn-another-referrals" onclick="proceedFromTestsSelectionAnotherReferrals(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-additional-info-tests-selected-btn-referrals" onclick="proceedFromAdditionalTestsSelectedReferrals(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn-referrals" onclick="proceedFromTestsSelectionReferrals(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-additional-info-tests-selected-btn-another-ward" onclick="proceedFromAdditionalTestsSelectedAnotherWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn-another-ward" onclick="proceedFromTestsSelectionAnotherWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-additional-info-tests-selected-btn-ward" onclick="proceedFromAdditionalTestsSelectedWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn-ward" onclick="proceedFromTestsSelectionWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>


            <div id="proceed-from-additional-info-tests-selected-btn-another" onclick="proceedFromAdditionalTestsSelectedAnother(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn-another" onclick="proceedFromTestsSelectionAnother(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-tests-selection-btn" onclick="proceedFromTestsSelection(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="proceed-from-additional-info-tests-selected-btn" onclick="proceedFromAdditionalTestsSelected(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="select-drugs-proceed-btn-2-referral" onclick="selectDrugsProceed2Referral(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="select-drugs-proceed-btn-referral" onclick="selectDrugsProceedReferral(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="select-drugs-proceed-btn-2-ward" onclick="selectDrugsProceed2Ward(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="select-drugs-proceed-btn-ward" onclick="selectDrugsProceedWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>

            <div id="request-new-drugs-btn" onclick="requestNewDrugs(this,event)" rel="tooltip" data-toggle="tooltip" title="Request Drugs For This Patient" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="select-drugs-proceed-btn-2" onclick="selectDrugsProceed2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>


            <div id="request-new-tests-btn" onclick="requestNewTests(this,event)" rel="tooltip" data-toggle="tooltip" title="Request New Tests" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>


            <div id="select-drugs-proceed-btn" onclick="selectDrugsProceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
              </div>
            </div>



    
            <div id="add-consultation-btn" onclick="addWardConsultation(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Make New Consultation" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>


            <div id="add-consult-btn" onclick="addNewConsult(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Write Consults" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="edit-consultation-btn" onclick="editWardConsultation(this,event)" rel="tooltip" data-toggle="tooltip" title="Edit This Consultation" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div class="modal fade" data-backdrop="static" id="enter-reason-for-referral-modal-referral" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Enter Reason For Referral / Consult</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <?php
                      $attr = array('id' => 'reason-for-referral-form-referral');
                      echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit-dr-input-form-referral',$attr);
                    ?>
                    <div class="form-group">
                      <label for="referral-reason" class="label-control">Reason: </label>
                      <textarea name="referral-reason" id="referral-reason" cols="30" rows="10" required class="form-control"></textarea>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" value="Submit" class="btn btn-primary">
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" data-backdrop="static" id="enter-reason-for-referral-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Enter Reason For Referral / Consult</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <?php
                      $attr = array('id' => 'reason-for-referral-form');
                      echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit-dr-input-form',$attr);
                    ?>
                    <div class="form-group">
                      <label for="referral-reason" class="label-control">Reason: </label>
                      <textarea name="referral-reason" id="referral-reason" cols="30" rows="10" required class="form-control"></textarea>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" value="Submit" class="btn btn-primary">
                    </form>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


            <div class="modal fade" data-backdrop="static" id="choose-action-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" style="text-transform: capitalize;"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <p id="expiry-info" style="font-size: 11px; text-transform: capitalize;" class="text-center"></p>
                    <div class="table-responsive">
      
                      <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-3" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Option</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td onclick="patientBioData(this,event)">View Patients Bio Data</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td onclick="patientAdmissionRecords(this,event)">View Consultation Records On Admission</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td onclick="viewWardConsultations(this,event)">View Previous Consultations</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td onclick="writeConsults(this,event)">Write Consults</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td onclick="requestLabTests(this,event)">Request Lab Tests</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td onclick="viewMedicationChart(this,event)">View Medication Chart/ Request Pharmaceuticals</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td onclick="viewVitalSigns(this,event)">View Vital Signs</td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td onclick="writeReport(this,event)">View Patient Reports</td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td onclick="inputOutputChart(this,event)">View Patient Input And Output Chart</td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td onclick="otherCharts(this,event)">View Other Patient Charts</td>
                          </tr>
                          <tr>
                            <td>11</td>
                            <td onclick="viewClinicalNotes(this,event)">View Patients Clinical Notes</td>
                          </tr>
                          <tr>
                            <td>12</td>
                            <td onclick="requestServices(this,event)">View Requested Services For Patient</td>
                          </tr>
                          <tr>
                            <td>13</td>
                            <td onclick="dischargePatient(this,event)">Discharge Patient</td>
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

            <div class="modal fade" data-backdrop="static" id="choose-clinic-for-ward-consult-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Select Clinic For Consult</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                   
                    <div class="table-responsive">

                      <?php  
                      $clinics = $this->onehealth_model->getAllClinics();
                      if(is_array($clinics)){
                        $i = 0;
                      ?>
          
                      <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-3" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Option</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php 
                          foreach($clinics as $row){
                            $i++;
                            $id = $row->id;
                            $name = $row->name;
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td style="text-transform: capitalize;" onclick="selectThisClinicForWardConsult(this,event,<?php echo $id; ?>)"><?php echo $name; ?></td>
                          </tr>

                          <?php } ?>
                          
                          
                        </tbody>
                      </table>
                      <?php } ?>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
  <?php
    $code_date = date("j");
    $code_time = date("h");
    $initiation_code = substr(bin2hex($this->encryption->create_key(8)),4). '-' . $code_date .'-' . $code_time;
  ?>
  <p id="var-dump" style="display: none;"><?php echo $initiation_code; ?></p>
</body>
<script>
    $(document).ready(function() {

      $("[data-toggle='tooltip'").tooltip();

       $("#new-consultation-form").submit(function (evt) {
          
          evt.preventDefault();
          // console.log(test)
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = me.attr("action");
          var values = me.serializeArray();
          values = values.concat({
            "name" : "consultation_id",
            "value" : consultation_id
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
              if(response.success){
                $.notify({
                message:"Consultation Complete"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault,1000);
              }else if(response.invalid_date == true){
                $.notify({
                message:"Future Appointment Date Incorrect. Date Entered Can Only Be In The Future."
                },{
                  type : "warning"  
                });
              }
              else if(response.messages != {}){
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
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });
              $(".form-error").html();
            }
          });  
      });

      $("#select-time-range").change(function (evt) {
        var time_range = $("#select-time-range").val();
        var range = time_range;
        var url = lab_base_url + "/change_range_pathologist"
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "change_range=true&range="+range,
          success : function(response){
            
            $(".spinner-overlay").hide();
            // $("#display-funds-table").html("");
            $("#select-patient-table").html(response);
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
      });

      $("#test-result-form").submit(function (evt) {
        evt.preventDefault();
          var health_facility_logo = $("#facility_img");
          var logo_src = health_facility_logo.attr("src");
          // console.log(logo_src)
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
        var lab_id = $(this).attr('data-lab-id');  
          var me = $(this);
          var form_data = me.serializeArray();
          $(".form-row").each(function () {
            console.log($(this).attr("data-main-test"))
            if($(this).attr("data-main-test") == 0){
              var id = $(this).attr("id");
              form_data = form_data.concat(
                {"name": "id[]", "value": id}
              )
            }           
          });  
          form_data = form_data.concat({"name" :"lab_id", "value" : lab_id}) 
          console.log(form_data)
          
          $(".spinner-overlay").show();
          
          var submit_patients_tests = lab_base_url + "/submit_patients_result";

          $.ajax({
            url : submit_patients_tests,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) { 
              
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){ 
                var get_pdf_tests_url = lab_base_url + "/get_pdf_tests_result";
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : get_pdf_tests_url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_pdf_tests=true&lab_id="+lab_id,
                    success : function (response) {
                      console.log(response)
                      $(".spinner-overlay").hide();
                      var facility_name = response.facility_name;
                      var rows = response.row_array;
                      var initiation_code = response.initiation_code;
                      var lab_id = response.lab_id;
                      var patient_name = response.patient_name;
                      var facility_state = response.facility_state;
                      var facility_country = response.facility_country;
                      var date = response.date;
                      var receptionist = response.receptionist;
                      var teller = response.teller;
                      var clerk = response.clerk;
                      var lab_three = response.lab_three;
                      var lab_two = response.lab_two;
                      var supervisor = response.supervisor;
                      var pathologist = response.pathologist;
                      var images = response.images;
                      var pathologists_comment = response.pathologists_comment;
                      var bio_data = response.bio_data;
                      var comment = response.comment;
                      // if(images.length > 0){
                      //   for(var i = 0; i < images.length; i++){
                      //     var image_name = images[i].src;
                      //     var src = '<?php echo base_url('assets/images/'); ?>' + image_name;
                      //     var img_elem_str = "<img style='display:none;' id='"+ image_name +"' src='"+src+"'></img>";
                      //     $("body").append(img_elem_str);
                      //     var elem = document.getElementById(image_name);
                          
                      //     var data_url = getDataUrl(elem);
                      //     console.log(data_url);
                      //     images[i].src = data_url;
                      //     console.log(images);
                      //   }
                      // }
                      var pdf_data =  {
                        'pathologists_comment' : pathologists_comment,
                        'logo' : company_logo,
                        'color' : <?php echo $color; ?>,
                        'tests' :  rows,
                        'facility_name' : facility_name,
                        'initiation_code' : initiation_code,
                        'lab_id' : lab_id,
                        'patient_name' : patient_name,
                        'facility_state' : facility_state,
                        'facility_country' : facility_country,
                        'facility_address' : "<?php echo $health_facility_address; ?>",
                        'date' :   date,
                        'receptionist' : receptionist,
                        'teller' : teller,
                        'clerk' : clerk,
                        'lab_three' : lab_three,
                        'lab_two' : lab_two,
                        "facility_id" : "<?php echo $health_facility_id; ?>",
                        'supervisor' : supervisor,
                        'pathologist' :  pathologist,
                        'images' : images,
                        'bio_data' : bio_data,
                        'comment' : comment
                      };

                      // console.log(pdf_data)
                  
                      var url = lab_base_url + "/save_result";
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
                            var pdf_url = "<?php echo base_url('assets/images/') ?>" + lab_id + '_result.html';
                            $.notify({
                              message:"Successful"
                              },{
                                type : "success"  
                            });
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
              
               $(".form-error").html("");
               $("#test-result-form").html("");
               loadPatient(lab_id);

            }else if(response.zipped == true){
               swal({
                title: 'Error!',
                text: "This Results Have Been Zipped By Pathologist. No One Can Edit It",
                type: 'error'           
              })
            }
            else if(response.success == true && response.successful == false){
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
            } 
            },
            error : function () {
              console.log('error')
              $(".spinner-overlay").hide();
             
            }
          }); 

      });

      $("#edit-previous-ward-consultation-form").submit(function (evt) {
        evt.preventDefault();
          // console.log(test)
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = me.attr("action");
          var values = me.serializeArray();
          var id = me.attr("data-id");
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
              if(response.success == true){
                $.notify({
                message:"Consultation Records Edited Successfully"
                },{
                  type : "success"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-previous-ward-consultation-form #'+key);
                
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

      $("#edit-previous-clinic-consultation-form").submit(function (evt) {
        evt.preventDefault();
          // console.log(test)
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = me.attr("action");
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
              if(response.success == true){
                $.notify({
                message:"Consultation Records Edited Successfully"
                },{
                  type : "success"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-previous-clinic-consultation-form #'+key);
                
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

      $("#enter-mortuary-details-form-ward").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/send_patient_to_the_mortuary'); ?>";
        
        
        var mortuary_id = me.attr("data-mortuary-id");
        var time_of_death = me.find("#time_of_death").val();
        var autopsy_yes = me.find("#1").prop("checked");
        var autopsy_no = me.find("#0").prop("checked");
        console.log(time_of_death);
        console.log(autopsy_yes)
        console.log(autopsy_no)
        if(time_of_death != "" && (!autopsy_yes || !autopsy_no)){
          if(autopsy_yes){
            autopsy = 1;
          }else if(autopsy_no){
            autopsy = 0;
          }


          var form_data = {
            'type' : 'ward',
            
            'ward_record_id' : ward_record_id,
            'mortuary_id' : mortuary_id,
            'time_of_death' : time_of_death,
            'autopsy' : autopsy
          };

          console.log(form_data)

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
                message:"Body Successfully Moved To Mortuary"
                },{
                  type : "success"  
                });
                setTimeout(goDefault,1000);
              }else{
                
                $.notify({
                message:"Something Went Wrong"
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
        }else{
          swal({
            title: 'Ooops',
            text: "All Fields Are Required",
            type: 'error'
          })
        }
      })

      $("#enter-mortuary-details-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/send_patient_to_the_mortuary'); ?>";
        
        var mortuary_id = me.attr("data-mortuary-id");
        var time_of_death = me.find("#time_of_death").val();
        var autopsy_yes = me.find("#1").prop("checked");
        var autopsy_no = me.find("#0").prop("checked");
        console.log(time_of_death);
        console.log(autopsy_yes)
        console.log(autopsy_no)
        if(time_of_death != "" && (!autopsy_yes || !autopsy_no)){
          if(autopsy_yes){
            autopsy = 1;
          }else if(autopsy_no){
            autopsy = 0;
          }


          var form_data = {
            'type' : 'clinic',
            'consultation_id' : consultation_id,
            'mortuary_id' : mortuary_id,
            'time_of_death' : time_of_death,
            'autopsy' : autopsy
          };

          console.log(form_data)

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
                message:"Body Successfully Moved To Mortuary"
                },{
                  type : "success"  
                });
                setTimeout(goDefault,1000);
              }else{
                
                $.notify({
                message:"Something Went Wrong"
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
        }else{
          swal({
            title: 'Ooops',
            text: "All Fields Are Required",
            type: 'error'
          })
        }
      })

      $("#make-new-consultation-form-referral").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var referral_id = $("#use-yours-btn-referral").attr('data-id');
          
        $(".spinner-overlay").show();
            
        var url = me.attr("action");
        var values = me.serializeArray();
        values = values.concat({
          "name" : "referral",
          "value" : true
        })
        
        values = values.concat({
          "name" : "referral_id",
          "value" : referral_id
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
            if(response.success == true){
              if(!response.patient_in_yours){
                swal({
                  title: 'Choose Action',
                  text: "<h3>Consultation Successful</h3><p>Do You Want To Add This Patient In Your Facility?</p> <p><em class='text-primary'>Note: This Would Appear On Records Page For Registration.</em></p>",
                  type: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'No'
                }).then(function(){
                  $(".spinner-overlay").show();
                      
                  var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_user_pending_registration_referral'); ?>";
                  
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "referral_id="+referral_id,
                    success : function (response) {
                      console.log(response)
                      $(".spinner-overlay").hide();
                      if(response.success == true){
                        $.notify({
                        message:"Successful"
                        },{
                          type : "success"  
                        });
                        $(".form-error").html("");
                        setTimeout(goDefault,1000);
                      }
                      else{

                       $.notify({
                        message:"Something Went Wrong"
                        },{
                          type : "warning"  
                        });
                       setTimeout(goDefault,1000);
                      }
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                      $(".spinner-overlay").hide();
                      $.notify({
                      message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
                      },{
                        type : "danger"  
                      });
                      setTimeout(goDefault,1000);
                    }
                  });
                }, function(dismiss){
                  if(dismiss == 'cancel'){
                    $.notify({
                    message:"Patient Has Been Successfully Referred"
                    },{
                      type : "success"  
                    });
                    $(".form-error").html("");
                    setTimeout(goDefault,1000);
                  }
                });
              }else{
                $.notify({
                message:"Patient Has Been Successfully Referred"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault,1000);
              }    
            }else if(response.success == true && response.patient_referred == false){
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
              message:"Valid Values Need To Be Entered In Consultation Fields To Proceed"
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

      $("#reason-for-referral-form-referral").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var reason = me.find("#referral-reason").val();
        if(reason.length > 0){

          var clinic_name = me.attr("data-clinic-name");
          var facility_name =  me.attr("data-facility-name");
          var facility_id = me.attr("data-facility-id");
          var clinic_id = me.attr("data-clinic-id");
          var referral_id = $("#use-yours-btn-referral").attr('data-id');
          swal({
            title: 'Warning',
            text: "Are You Sure You Want To Refer This Patient To " + clinic_name + " In " + facility_name + " ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
          }).then((result) => {
            $(".spinner-overlay").show();
                
            var url = me.attr("action");
            var values = $("#make-new-consultation-card-referral #make-new-consultation-form-referral").serializeArray();
            values = values.concat({
              "name" : "referral",
              "value" : true
            })
            values = values.concat({
              "name" : "facility_id",
              "value" : facility_id
            })
            values = values.concat({
              "name" : "clinic_id",
              "value" : clinic_id
            })
            values = values.concat({
              "name" : "reason",
              "value" : reason
            })
            values = values.concat({
              "name" : "referral_id",
              "value" : referral_id
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
                if(response.success == true && response.patient_referred == true){
                  if(!response.patient_in_yours){
                    swal({
                      title: 'Choose Action',
                      text: "<h3>Patient Referred Successfully</h3><p>Do You Want To Add This Patient In Your Facility?</p> <p><em class='text-primary'>Note: This Would Appear On Records Page For Registration.</em></p>",
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes',
                      cancelButtonText: 'No'
                    }).then(function(){
                      $(".spinner-overlay").show();
                          
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_user_pending_registration_referral'); ?>";
                      
                      $.ajax({
                        url : url,
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : "referral_id="+referral_id,
                        success : function (response) {
                          console.log(response)
                          $(".spinner-overlay").hide();
                          if(response.success == true){
                            $.notify({
                            message:"Successful"
                            },{
                              type : "success"  
                            });
                            $(".form-error").html("");
                            setTimeout(goDefault,1000);
                          }
                          else{

                           $.notify({
                            message:"Something Went Wrong"
                            },{
                              type : "warning"  
                            });
                           setTimeout(goDefault,1000);
                          }
                        },
                        error: function (jqXHR,textStatus,errorThrown) {
                          $(".spinner-overlay").hide();
                          $.notify({
                          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
                          },{
                            type : "danger"  
                          });
                          setTimeout(goDefault,1000);
                        }
                      });
                    }, function(dismiss){
                      if(dismiss == 'cancel'){
                        $.notify({
                        message:"Patient Has Been Successfully Referred"
                        },{
                          type : "success"  
                        });
                        $(".form-error").html("");
                        setTimeout(goDefault,1000);
                      }
                    });
                  }else{
                    $.notify({
                    message:"Patient Has Been Successfully Referred"
                    },{
                      type : "success"  
                    });
                    $(".form-error").html("");
                    setTimeout(goDefault,1000);
                  }    
                }else if(response.success == true && response.patient_referred == false){
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
                  message:"Valid Values Need To Be Entered In Consultation Fields To Proceed"
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
          });
        }else{
          swal({
            title: 'Ooops',
            text: "The Reason For Referral Field Is Required",
            type: 'error'
          })
        }

      })

      $("#reason-for-referral-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var reason = me.find("#referral-reason").val();
        if(reason.length > 0){

          var clinic_name = me.attr("data-clinic-name");
          var facility_name =  me.attr("data-facility-name");
          var facility_id = me.attr("data-facility-id");
          var clinic_id = me.attr("data-clinic-id");
          swal({
            title: 'Warning',
            text: "Are You Sure You Want To Refer This Patient To " + clinic_name + " In " + facility_name + " ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
          }).then((result) => {
            $(".spinner-overlay").show();
                
            var url = $("#new-consultation-form").attr("action");
            var values = $("#new-consultation-form").serializeArray();
            values = values.concat({
              "name" : "consultation_id",
              "value" : consultation_id
            })
            values = values.concat({
              "name" : "referral",
              "value" : true
            })
            values = values.concat({
              "name" : "facility_id",
              "value" : facility_id
            })
            values = values.concat({
              "name" : "clinic_id",
              "value" : clinic_id
            })
            values = values.concat({
              "name" : "reason",
              "value" : reason
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
                if(response.success == true && response.patient_referred == true){
                  $.notify({
                  message:"Patient Has Been Successfully Referred"
                  },{
                    type : "success"  
                  });
                  $(".form-error").html("");
                  setTimeout(goDefault,1000);
                }else if(response.success == true && response.patient_referred == false){
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
                  message:"Valid Values Need To Be Entered In Consultation Fields To Proceed"
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
          });
        }else{
          swal({
            title: 'Ooops',
            text: "The Reason For Referral Field Is Required",
            type: 'error'
          })
        }

      })

      $("#select-options-table-3").DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false
            
      })



      $(".table-test").DataTable();
      $("#select-wards-table").DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false
            
      })

      $("#edit-consultation-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
      
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "ward_record_id",
          "value" : ward_record_id
        })

        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })
        console.log(url)
        console.log(form_data)
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
              message:"Edited Successfully"
              },{
                type : "success"  
              });
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#edit-consultation-form #'+key);
              
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

      $("#make-new-consultation-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "ward_record_id",
          "value" : ward_record_id
        })
        
        console.log(form_data)
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
              $("#add-new-consultation-card").hide();
              viewWardConsultations(this,event);
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#make-new-consultation-form #'+key);
              
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

      <?php if($this->session->prescription_success){ ?>
       $.notify({
        message:"Prescription Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      
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


      $("#vital-signs-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_clinic_doctor'); ?>";
        var values = me.serializeArray();
        
        values = values.concat({
          "name" : "consultation_id",
          "value" : consultation_id
        })

        console.log(values)

        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : values,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success){
              $.notify({
              message:"Patient Vital Signs Inputed Successfully"
              },{
                type : "success"  
              });
              $(".form-error").html("");
            }
            else if(response.messages != {}){
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

      if(getCookie("online-payment-dr") == true &&  getCookie("online-payment-patient-name") !== ""){
        patient_name = getCookie("online-payment-patient-name");
         swal({
            type: 'success',
            title: 'Successful',
            allowOutsideClick : false,
            allowEscapeKey :false,
            text: 'The Tests Of User '+ patient_name + ' Have Been Paid For Successfully Paid For. Please Navigate To The Users Folder To Enter Consultation Details'
            // footer: '<a href>Why do I have this issue?</a>'
          }).then((result) => {
            setCookie("online-payment-dr","",-90);
            setCookie("online-payment-patient-name","",-90);
          });
      }

      <?php if($this->session->consultation_complete){ ?>
         $.notify({
          message:"Consultation Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->ward_tests_selected){ ?>
         $.notify({
          message:"Ward Tests Selected Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

    });



</script>
