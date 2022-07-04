
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
<script>
  String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return (this.length > n) ? this.substr(0, n-1) + '&hellip;' : this;
      };
  var tests_selected_obj = [];
  var user_info = [];
  var selected_drugs = [];

  var ward_record_id = "";
  var consultation_id = "";
  var patient_name = "";
  var patient_user_id = "";

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



  function goBackCurrentSelectedTests (elem,evt) {
    $("#current-selected-tests").hide();
    $("#request-lab-tests-card").show();
    $("#request-new-tests-btn").hide("fast");
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

  function goBackFromWardViewResults(elem,evt){
    $("#ward-view-results-card").hide();
    $("#on-admission-selected-tests").show();
  }

  function goBackFromWardViewResults1(elem,evt){
    $("#ward-view-results-card1").hide();
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
  }

  function goBackFromWardViewResultsImagesSub(elem,evt){
    $("#ward-view-results-images-card-sub").hide();
    $("#ward-selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsImagesSub1(elem,evt){
    $("#ward-view-results-images-card-sub1").hide();
    $("#ward-selected-sub-tests-card1").show();
  }
  
  function goBackFromWardViewResultsSub(elem,evt){
    $("#ward-view-results-card-sub").hide();
    $("#ward-selected-sub-tests-card").show();
  }

  function goBackFromWardViewResultsSub1(elem,evt){
    $("#ward-view-results-card-sub1").hide();
    $("#ward-selected-sub-tests-card1").show();
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

  function goBackOnAdmissionSelectedTests (elem,evt) {
    
    $("#on-admission-selected-tests").hide();
    
    $("#request-lab-tests-card").show();
  }
  
  function goBackRequestTests (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#request-lab-tests-card").hide();
  }

  function goBackFromWardsPatientsCard (elem,evt) {
    $("#wards-patients-card").hide();
    $("#main-card").show();
  }

  function goBackInputOutputInfo (elem,evt) {
    $("#input-output-card").show();
    $("#add-input-output-btn").show("fast");
    $("#input-output-info-card").hide(); 
  }

  function goBackVitalSignsInfo(elem,evt){
    $("#vital-signs-card").show();
    $("#add-vital-signs-btn").show("fast");
    $("#vital-signs-info-card").hide();
  }


  function goBackCurrentConsultations(elem,evt){
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

  function goBackVitalSigns (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#vital-signs-card").hide();
    
    $("#add-vital-signs-btn").hide("fast");
  }

  function goBackAddVitalSigns(elem,evt){
    $("#vital-signs-card").show();
    $("#add-vital-signs-card").hide();
    $("#add-vital-signs-btn").show("fast");
  }


  function goBackViewConsultation (elem,evt) {
    $("#drs-current-consultations").show();
    
    $("#view-consultation-card").hide();
    $("#add-consultation-btn").show("fast");
    
    $("#edit-consultation-btn").attr("onclick","editWardConsultation(this,event)");
    $("#edit-consultation-btn").hide("fast");

  }

  function addVitalSigns(elem,evt) {
    $("#vital-signs-card").hide();
    $("#add-vital-signs-card").show();
    $("#add-vital-signs-btn").hide("fast");
  }

  function addInputOutput (elem,evt) {
    $("#input-output-card").hide();
    $("#add-input-output-card").show();
    $("#add-input-output-btn").hide("fast");
  }

  function goBackAddOtherChartData (elem,evt) {
    $("#other-charts-info-card").show();
    $("#add-other-chart-data-card").hide();
    $("#add-other-charts-info-btn").show("fast");
  }

  function addOtherChartsInfo(elem,evt){
    $("#other-charts-info-card").hide();
    $("#add-other-chart-data-card").show();
    $("#add-other-charts-info-btn").hide("fast");
    var other_chart_id = $("#add-other-chart-data-form").attr("data-id");
    
    if(ward_record_id !== "" || other_chart_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=load_add_other_chart_data_form&id="+ward_record_id+"&other_chart_id="+other_chart_id,
        success : function (response) {
          console.log(response);
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            var chart_name = response.chart_name;
            $("#other-charts-info-card").hide();
            $("#add-other-chart-data-card .card-title").html("Add Data To " + chart_name);
            $("#add-other-chart-data-form").html(messages);
            $("#add-other-chart-data-card").show();
            $("#add-other-charts-info-btn").hide("fast");
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

  function goBackInputOutput (elenm,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#input-output-card").hide();
    $("#add-input-output-btn").hide("fast");
  }

  function goBackAddInputOutput(elem,evt) {
    $("#input-output-card").show();
    $("#add-input-output-card").hide();
    $("#add-input-output-btn").show("fast");
  }

  function goBackOtherChartsInfo (elem,event) {
    $("#view-other-charts-card").show();
    $("#other-charts-info-card").hide();
    $("#add-other-charts-btn").show("fast");
    $("#add-other-charts-info-btn").hide("fast");
  }

  function goBackClinicalNotes (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#clinical-notes-card").hide();
    $("#add-clinical-note-btn").hide("fast");
    
  }

  function addClinicalNote (elem,evt) {
    $("#clinical-notes-card").hide();
    $("#add-new-clinical-note-card").show();
    $("#add-clinical-note-btn").hide("fast");
  }

  function goBackOnNewClinicalNote(elem,evt){
    $("#clinical-notes-card").show();
    $("#add-new-clinical-note-card").hide();
    $("#add-clinical-note-btn").show("fast");
  }

  function goBackViewClinicalNote (elem,evt) {
    $("#clinical-notes-card").show();
    
    $("#view-clinical-note-card").hide();
    $("#add-clinical-note-btn").show("fast");
    
    $("#edit-clinical-note-btn").attr("onclick","editClinicalNote(this,event)");
    $("#edit-clinical-note-btn").hide("fast");
  }

  function goBackOnEditClinicalNote (elem,evt,id,ward_id) {
    $("#edit-clinical-note-card").hide();
    $("#view-clinical-note-card").show();
    $("#edit-clinical-note-btn").attr("onclick","editWardConsultation(this,event,"+id+","+ward_id+")");
    $("#edit-clinical-note-btn").show("fast");
  }

  function goBackReportCard (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#report-card").hide();
    $("#add-report-btn").hide("fast");
  }

  function goBackAddReportCard (elem,evt) {
    $("#report-card").show();
    $("#add-report-btn").show("fast");
    
    $("#add-report-card").hide();
  }

  function goBackReportInfoCard (elem,evt) {
    $("#report-card").show();
    $("#add-report-btn").show("fast");
    $("#report-info-card").hide();
    $("#edit-report-btn").hide("fast");
  }

  function goBackEditReportCard (elem,evt) {
    $("#report-info-card").show();
    $("#edit-report-btn").show("fast");
    $("#edit-report-form #summary").val("");
    $("#edit-report-form #services").val("");
    $("#edit-report-form #outstanding_services").val("");
    $("#edit-report-form #note").val("");
    $("#edit-report-card").hide();

  }

  function goBackRequestServices (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#request-services-card").hide();
  }
  function goBackRequestService(elem,evt)  {
    $("#request-services-card").show();
    $("#request-service-form #quantity-form-group").hide();
    $("#request-service-form #amount-form-group").hide();
    $("#request-service-form #quantity").val("");
    $("#request-service-form #amount").val("");
    $("#request-service-form #quantity").attr("onkeyup","");
    $("#request-service-card").hide();
  }

  function amountKeyUp(elem,evt,price){
    elem = $(elem);
    var val = elem.val();
    if(val !== "" && price !== ""){
      var final_price = val * price;
      $("#request-service-form #amount").val(final_price);
    }
    
  }

  function goBackRequestedServices (elem,evt) {
    $("#request-services-card").show();
    $("#requested-services-card").hide();
  }

  function requestWardService(elem,evt,id,name,type,price){
    
    if(ward_record_id !== ""){
     swal({
        title: 'Choose Action',
        text: "Do You Want To? ",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Request This Service',
        cancelButtonText : "View Previous Requests"
      }).then(function(){
        swal({
          title: 'Proceed?',
          text: "Are You Sure You Want To Proceed? ",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes Proceed',
          cancelButtonText : "Cancel"
        }).then(function(){
          $("#request-services-card").hide();
          $("#request-service-card .card-title").html("Request "+name+" Service");
          $("#request-service-form #amount-form-group").show();
          if(type == "rate"){
            $("#request-service-form").attr("data-type","rate");
            $("#request-service-form #quantity-form-group").show();
            $("#request-service-form #quantity").attr("onkeyup","amountKeyUp(this,event,"+price+")");
            $("#request-service-form").attr("data-id",id);
            $("#request-service-card").show();
          }else{
            var url = $("#request-service-form").attr("action");
            
            var service_id = id;
            var form_data = [];

            form_data = form_data.concat({
              "name" : "ward_record_id",
              "value" : ward_record_id
            })

            form_data = form_data.concat({
              "name" : "service_id",
              "value" : service_id
            })

            form_data = form_data.concat({
              "name" : "type",
              "value" : "fixed"
            })
            // console.log(url)
            // console.log(form_data)
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
                if(response.success && response.user_type != ""){
                  var user_type = response.user_type;

                  if(user_type == "nfp"){
                    var text = "Service Successfully Requested";
                  }else{
                    var text = "Service Successfully Requested. Please Direct Patient To Hospital Teller For Payment.";
                  }

                  $.notify({
                  message: text
                  },{
                    type : "success"  
                  });
                  $("#request-service-card").hide();
                  requestServices(this,event);
                }else if(response.invalid_id){
                  swal({
                    title: 'Ooops',
                    text: "Something Went Wrong",
                    type: 'warning'
                  })
                }else{
                  $.each(response.messages, function (key,value) {

                  var element = $('#request-service-form #'+key);
                  
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
        });
      },function(dismiss){
        if(dismiss == 'cancel'){
          $(".spinner-overlay").show();
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
          
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "view_records=true&type=requested_services&id="+ward_record_id+"&service_id="+id,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success && response.messages != ""){
                var messages = response.messages;
                $("#request-services-card").hide();
                $("#requested-services-card .card-body").html(messages);
                $("#requested-services-card").show();
                $("#requested-services-card #requested-services-table").DataTable();
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
        data : "view_records=true&type=request_services&id="+ward_record_id,
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

  function editReport(elem,evt,id){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=report_info_edit&id="+ward_record_id+"&report_id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#report-info-card").hide();
            $("#edit-report-btn").hide("fast");
            var summary = response.messages.summary;
            var services = response.messages.services;
            var outstanding_services = response.messages.outstanding_services;
            var note = response.messages.note;
            $("#edit-report-form #summary").val(summary);
            $("#edit-report-form #services").val(services);
            $("#edit-report-form #outstanding_services").val(outstanding_services);
            $("#edit-report-form #note").val(note);
            $("#edit-report-form").attr("data-id",id);
            $("#edit-report-card").show();

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
            
            $("#add-report-btn").hide("fast");
            $("#report-info-card").show();
            if(response.editable){
              $("#edit-report-btn").show("fast");
              $("#edit-report-btn").attr("onclick","editReport(this,event,"+id+")")
            }

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

  function addNewReport (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=get_last_report_row&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#report-card").hide();
            $("#add-report-btn").hide("fast");
            var summary = response.messages.summary;
            var services = response.messages.services;
            var outstanding_services = response.messages.outstanding_services;
            var note = response.messages.note;
            $("#add-report-form #summary").val(summary);
            $("#add-report-form #services").val(services);
            $("#add-report-form #outstanding_services").val(outstanding_services);
            $("#add-report-form #note").val(note);
            $("#add-report-card").show();
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

  function editClinicalNote(elem,evt,id,ward_id){
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
        data : "view_records=true&type=clincal_note_info&id="+ward_id+"&clinical_note_id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.consultation_info != ""){
            var consultation_info = response.consultation_info;
            var advice_given = consultation_info.advice_given;
            var complaints = consultation_info.complaints;
            var examination_findings = consultation_info.examination_findings;
            var diagnosis = consultation_info.diagnosis;
            var history = consultation_info.history; 

            $("#edit-clinical-note-form #complaints").val(complaints);
            $("#edit-clinical-note-form #advice").val(advice_given);
            $("#edit-clinical-note-form #examination-findings").val(examination_findings)
            $("#edit-clinical-note-form #diagnosis").val(diagnosis);
            $("#edit-clinical-note-form #history").val(history);
            $("#view-clinical-note-card").hide();
            $("#edit-clinical-note-btn").attr("onclick","editClinicalNote(this,event)");
            $("#edit-clinical-note-btn").hide("fast");
            $("#edit-clinical-note-card #go-back").attr("onclick","goBackOnEditClinicalNote(this,event,"+id+","+ward_id+")");
            $("#edit-clinical-note-card #edit-clinical-note-form").attr('data-id', id);
            $("#edit-clinical-note-card").show();
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
            $("#add-clinical-note-btn").hide("fast");
            if(response.consultation_editable){
              $("#edit-clinical-note-btn").attr("onclick","editClinicalNote(this,event,"+id+","+ward_id+")");
              $("#edit-clinical-note-btn").show("fast");
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
            $("#add-clinical-note-btn").show("fast");
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
            $("#add-other-chart-data-card #add-other-chart-data-form").attr("data-id",other_chart_id);
            $("#other-charts-info-card").show();
            $("#add-other-charts-btn").hide("fast");
            $("#add-other-charts-info-btn").show("fast");
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

  function goBackViewOtherCharts(elem,evt){
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#view-other-charts-card").hide();
    $("#add-other-charts-btn").hide("fast"); 
  }

  function goBackAddOtherCharts (elem,evt) {
    $("#view-other-charts-card").show();
    $("#add-other-charts-btn").show("fast"); 
    $("#add-other-charts-card").hide();
  }

  function addOtherCharts (elem,evt) {
    $("#view-other-charts-card").hide();
    $("#add-other-charts-btn").hide("fast"); 
    $("#add-other-charts-card").show();
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
            $("#add-other-charts-btn").show("fast");
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
            $("#add-report-btn").show("fast");
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

  function goBackOtherChartsInfo1 (elem,evt) {
    $("#other-charts-info-card").show();
    
    $("#add-other-charts-info-btn").show("fast");
    $("#other-charts-info-card1").hide();
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
            $("#add-other-charts-info-btn").hide("fast");
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
            $("#vital-signs-info-card .table-test").DataTable();
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

  function patientCurrentDrRecords (elem,evt) {
    
    
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
            $("#drs-current-consultations .table").DataTable();
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

  function openChoosePatientAction(elem,evt,id,name,const_id,patient_user_name,user_id) {

    if(id !== "" && patient_user_name != "" && const_id != "" && name != ""){
      $(".spinner-overlay").show();
      $("#user_name").html(patient_user_name);
      patient_name = name;
      ward_record_id = id;
      consultation_id = const_id;
      patient_user_id = user_id;
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
              var str = patient_name +"'s Admission Payment Will Expire "+expiry_date;
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

  function viewPatientsInWard(elem,evt){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_patients_in_your_ward'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "view_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#wards-patients-card .card-body").html(messages);
          $("#main-card").hide();
          $("#wards-patients-card").show();
          $("#wards-patients-card .table-test").DataTable();
        }else if(response.no_patient){
          $.notify({
          message:"No Patient In The Ward"
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

  function viewTestsRequestedByDoctor (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#request-lab-tests-card").show();
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

  function viewTestResults (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
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

          if(isward && type != null){
            var card_name = "ward-view-results-card1";
          }else if(isward){
            var card_name = "ward-view-results-card";
          }else{
            var card_name = "view-results-card"; 
          }

          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Ready Result For " +test_name);
          $("#selected-tests-card").hide();
          $("#current-selected-tests").hide();
          $("#on-admission-selected-tests").hide();
          $("#request-new-tests-btn").hide("fast");
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

  function viewTestResultImages (elem,evt,main_test_id,initiation_code,health_facility_id,lab_id,sub_dept_id,isward,type = null) {
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

          if(isward && type != null){
            var card_name = "ward-view-results-images-card1";
          }else if(isward){
            var card_name = "ward-view-results-images-card";
          }else{
            var card_name = "view-results-images-card"; 
          }


          $("#"+card_name+ " .card-body").html(messages);
          $("#"+card_name+ " .card-title").html("Result Images For " +test_name);
          $("#selected-tests-card").hide();
          $("#current-selected-tests").hide();
          $("#on-admission-selected-tests").hide();
          $("#request-new-tests-btn").hide("fast");
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
            $("#current-selected-tests .table").DataTable();
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

  function goDefault(){
    document.location.reload();
  }

  function goBackMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#medication-chart-card").hide();
  }

  function viewMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#medication-chart-card").show();
  }

  function onAdmissionDrugs (elem,evt) {
    
    if(ward_record_id != "" && consultation_id != ""){
      $(".spinner-overlay").show();
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

  function selectThisPharmacyWard(elem,evt){
    evt.preventDefault();
    if(ward_record_id != ""){
     
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

  function goBackSelectDrugsWard (elem,evt) {
    user_info = [];
    selected_drugs = [];
    $("#select-drugs-card-ward .card-title").html("");
    
    
    $("#select-pharmacy-to-use-card-ward").show();
    
    $("#select-drugs-card-ward").hide();
    $("#select-drugs-proceed-btn-ward").hide("fast");
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
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
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
      if(type !== ""){
        selectDrugsProceed2Ward();
      }else{
        selectDrugsProceed2();
      }
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
      if(type !== ""){
        selectDrugsProceed2Ward();
      }else{
        selectDrugsProceed2();
      }
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
      if(type !== ""){
        selectDrugsProceed2Ward();
      }else{
        selectDrugsProceed2();
      }
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

  function goBackSelectedInfoDrugsWard (elem,evt) {
    $("#select-drugs-card-ward").show();
    $("#select-drugs-proceed-btn-ward").show("fast");
    $("#select-drugs-proceed-btn-2-ward").hide("fast");
    
    $("#selected-drugs-info-card-ward").hide();
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
        data : {
          ward_record_id : ward_record_id,
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
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" style="margin-top: 50px;" onclick="viewPatientsInWard(this,event)">View Patients In Ward</button>
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

              <div class="card" id="medication-chart-for-drug-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMedicationChartForDrug(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
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

              <div class="card" id="selected-drugs-info-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugsWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
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

              <div class="card" id="current-drugs-card-info" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentDrugsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Drugs</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>
              
              <div class="card" id="wards-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromWardsPatientsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                  
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
              
              <div class="card" id="view-consultation-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Consultations</h3>
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

              <div class="card" id="drs-current-consultations" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentConsultations(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Dr's Consultations</h3>
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

              <div class="card" id="view-other-charts-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewOtherCharts(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Other Charts In This Facility</h3>
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

              <div class="card" id="clinical-notes-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackClinicalNotes(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Clinical Notes</h3>
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

              <div class="card" id="requested-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestedServices(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Requests Of Service</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="request-service-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestService(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Request This Service</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'request-service-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_new_ward_service',$attr);
                  ?>
                    
                    <div class="wrap">
                      <h4 id="price-info"></h4>
                      <div class="form-row">
                        <div class="form-group col-sm-12" style="display: none;" id="quantity-form-group">
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Enter Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" onkeyup="" type="number" />
                          <span class="form-error"></span>
                        </div>
                      </div>
                      <input type="submit" class="btn btn-primary">
                    </div>
                  </form>

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

              <div class="card" id="add-new-clinical-note-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnNewClinicalNote(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Add Clinical Note</h3>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'add-new-clinical-note-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_clinical_note',$attr);
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


              <div class="card" id="edit-clinical-note-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnEditClinicalNote(this,event)" id="go-back" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Edit Clinical Note</h3>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'edit-clinical-note-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_clinical_note',$attr);
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

              <div class="card" id="view-sub-tests-results-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
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

              <div class="card" id="view-patient-results-sub-test-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>



              <div class="card" id="add-vital-signs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAddVitalSigns(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Enter Patient Vital Signs</h3>
                </div>
                <div class="card-body">
                  <?php 
                  $attr = array('id' => 'add-vital-signs-form');
                  echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_vital_signs_ward',$attr);
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

                      
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
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


              <div class="card" id="add-input-output-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAddInputOutput(this,event)" class="btn btn-warning">Go Back</button>
                  <h4 class="card-title" id="welcome-heading">Add Input / Output Record</h4>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'add-input-output-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_input_output_record',$attr);
                  ?>
                    
                    <h3 class="form-sub-heading text-center">Input</h3>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="oral" class="label-control"> Oral: </label>
                          <input type="number" class="form-control" id="oral" name="oral">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="iv_drug" class="label-control"> Iv (Drug): </label>
                          <input type="number" class="form-control" id="iv_drug" name="iv_drug">
                          
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          <label for="iv_fluid" class="label-control"> Iv (Fluid): </label>
                          <input type="number" class="form-control" id="iv_fluid" name="iv_fluid">
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <h3 class="form-sub-heading text-center">Output</h3>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="abdominal_drain" class="label-control"> Abdominal Drain: </label>
                          <input type="number" class="form-control" id="abdominal_drain" name="abdominal_drain">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <label for="joint_drain" class="label-control"> Joint Drain: </label>
                          <input type="number" class="form-control" id="joint_drain" name="joint_drain">
                          
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          <label for="chest_drain" class="label-control"> Chest Drain: </label>
                          <input type="number" class="form-control" id="chest_drain" name="chest_drain">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          <label for="csf_drain" class="label-control"> CSF Drain: </label>
                          <input type="number" class="form-control" id="csf_drain" name="csf_drain">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          <label for="thigh_drain" class="label-control"> Thigh Drain: </label>
                          <input type="number" class="form-control" id="thigh_drain" name="thigh_drain">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-4">
                          <label for="other_drain" class="label-control"> Other Drain: </label>
                          <input type="number" class="form-control" id="other_drain" name="other_drain">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          <label for="ng_tubes" class="label-control"> Ng Tubes: </label>
                          <input type="number" class="form-control" id="ng_tubes" name="ng_tubes">
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>
                    <input type="submit" class="btn btn-info">
                  </form>
                </div>
              </div> 

              <div class="card" id="add-other-chart-data-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAddOtherChartData(this,event)" class="btn btn-warning">Go Back</button>
                  <h4 class="card-title" id="welcome-heading">Add Data To This Chart</h4>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'add-other-chart-data-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_other_chart_record',$attr);
                  ?>
                    
                  </form>
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


              <div class="card" id="add-other-charts-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAddOtherCharts(this,event)" class="btn btn-warning">Go Back</button>
                  <h4 class="card-title" id="welcome-heading">Add New Chart In This Facility</h4>
                </div>
                <div class="card-body">
 
                  <?php  
                    $attr = array('id' => 'add-other-charts-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_other_chart_ward',$attr);
                  ?>
                    
                    
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="chart_name" class="label-control"> Chart Name: </label>
                          <input type="text" class="form-control" id="chart_name" name="chart_name" required>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>

                    <div class="wrap">
                      <h3 class="text-center">Parameters</h3>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="parameter_1" class="label-control"> Parameter 1: </label>
                          <input type="text" class="form-control" id="parameter_1" name="parameter_1">
                          <span class="form-error"></span>
                        </div> 
                        
                        <div class="form-group col-sm-6">
                          <label for="parameter_2" class="label-control"> Parameter 2: </label>
                          <input type="text" class="form-control" id="parameter_2" name="parameter_2">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="parameter_3" class="label-control"> Parameter 3: </label>
                          <input type="text" class="form-control" id="parameter_3" name="parameter_3">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="parameter_4" class="label-control"> Parameter 4: </label>
                          <input type="text" class="form-control" id="parameter_4" name="parameter_4">
                          <span class="form-error"></span>
                        </div> 

                      </div>
                    </div>

                    <input type="submit" class="btn btn-info">
                  </form>
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


              <div class="card" id="report-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReportInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h2 class="card-title" id="welcome-heading"></h2>
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

              <div class="card" id="add-report-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAddReportCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Write Patient Report</h3>
                </div>
                <div class="card-body">
                  <?php 
                  $attr = array('id' => 'add-report-form');
                  echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_report_ward',$attr);
                  ?>
                  <h4 class="form-sub-heading">Patient Summary</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="summary" class="label-control"><span class="form-error1">* </span> Patient Summary: </label>
                        <textarea name="summary" id="summary" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Services Offered To Patient</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="services" class="label-control"><span class="form-error1">* </span> Services Offered To Patient: </label>
                        <textarea name="services" id="services" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Outstanding Services Handed Over</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="outstanding_services" class="label-control"><span class="form-error1">* </span> Outstanding Services Handed Over: </label>
                        <textarea name="outstanding_services" id="outstanding_services" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Things To Note</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="note" class="label-control"><span class="form-error1">* </span> Things To Note: </label>
                        <textarea name="note" id="note" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>
              </div>

              <div class="card" id="edit-report-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackEditReportCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Edit Patient Report</h3>
                </div>
                <div class="card-body">
                  <?php 
                  $attr = array('id' => 'edit-report-form');
                  echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_report_ward',$attr);
                  ?>
                  <h4 class="form-sub-heading">Patient Summary</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="summary" class="label-control"><span class="form-error1">* </span> Patient Summary: </label>
                        <textarea name="summary" id="summary" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Services Offered To Patient</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="services" class="label-control"><span class="form-error1">* </span> Services Offered To Patient: </label>
                        <textarea name="services" id="services" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Outstanding Services Handed Over</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="outstanding_services" class="label-control"><span class="form-error1">* </span> Outstanding Services Handed Over: </label>
                        <textarea name="outstanding_services" id="outstanding_services" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>

                  <h4 class="form-sub-heading">Things To Note</h4>
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="note" class="label-control"><span class="form-error1">* </span> Things To Note: </label>
                        <textarea name="note" id="note" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 
                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
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



              <div class="card" id="on-admission-selected-tests" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Tests On Admission</h3>
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

              <div class="card" id="view-patient-results-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
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

              <div class="card" id="view-sub-tests-results-and-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
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

            <div id="edit-report-btn" onclick="editReport(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Edit This Report" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>  

  
            <div id="add-report-btn" onclick="addNewReport(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add New Patient Report" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>
   
    

            <div id="add-clinical-note-btn" onclick="addClinicalNote(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add Clinical Note" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="edit-clinical-note-btn" onclick="" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Edit This Clinical Note" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>
  
  
            <div id="add-other-charts-btn" onclick="addOtherCharts(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add Another Charts" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="add-other-charts-info-btn" onclick="addOtherChartsInfo(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add Data To This Chart" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>
            
            <div id="add-vital-signs-btn" onclick="addVitalSigns(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add Vital Signs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="add-input-output-btn" onclick="addInputOutput(this,event)" rel="tooltip" data-toggle="modal" data-toggle="tooltip" title="Add Input / Output Record" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

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
                            <td onclick="patientBioData(this,event)">View Patients Bio Data</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td onclick="patientAdmissionRecords(this,event)">View Consultation Records On Admission</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td onclick="patientCurrentDrRecords(this,event)">View Dr's Current Consultations</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td onclick="viewMedicationChart(this,event)">View Medication Chart/ Request Pharmaceuticals</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td onclick="viewVitalSigns(this,event)">View And Update Vital Signs</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td onclick="inputOutputChart(this,event)">View And Update Patient Input And Output Chart</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td onclick="otherCharts(this,event)">View And Add Other Charts</td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td onclick="writeReport(this,event)">Write And View Previous Reports On Patient</td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td onclick="viewClinicalNotes(this,event)">View And Update Patients Clinical Notes</td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td onclick="requestServices(this,event)">Request Services For Patient</td>
                          </tr>
                          <tr>
                            <td>11</td>
                            <td onclick="viewTestsRequestedByDoctor(this,event)">View Tests Requested By Doctor</td>
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
          </div>
          

        </div>
      </div>
      
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer> -->
        </div>
       
      </footer>
      <span id="user_name" style="display: none;"></span>
  </div>
  
</body>
<script>
    $(document).ready(function() {


      $("#request-service-form").submit(function (evt) {
        
        evt.preventDefault();
        
        var me  = $(this);
        
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var service_id = me.attr("data-id");
        var type = me.attr("data-type");

        form_data = form_data.concat({
          "name" : "ward_record_id",
          "value" : ward_record_id
        })

        form_data = form_data.concat({
          "name" : "service_id",
          "value" : service_id
        })

        form_data = form_data.concat({
          "name" : "type",
          "value" : type
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
            if(response.success && response.user_type != ""){
              var user_type = response.user_type;

              if(user_type == "nfp"){
                var text = "Service Successfully Requested";
              }else{
                var text = "Service Successfully Requested. Please Direct Patient To Hospital Teller For Payment.";
              }

              $.notify({
              message: text
              },{
                type : "success"  
              });
              $("#request-service-card").hide();
              requestServices(this,event);
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#request-service-form #'+key);
              
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

      $("#edit-report-form").submit(function (evt) {
        
        evt.preventDefault();
        var me  = $(this);
        var valid = false;
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var report_id = me.attr("data-id");
        var id = me.attr("data-id");

        for(var i = 0; i < form_data.length; i++){
          var obj = form_data[i];
          var value = obj.value;
          if(value !== ""){
            valid = true;
            break;
          }
        }

        if(valid){
          form_data = form_data.concat({
            "name" : "ward_record_id",
            "value" : ward_record_id
          })

          form_data = form_data.concat({
            "name" : "report_id",
            "value" : report_id
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
                message:"Report Edited Successfully"
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

                var element = $('#edit-report-form #'+key);
                
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
        }else{
          swal({
            title: 'Ooops',
            text: "Value Must Be Entered In At Least One Field",
            type: 'error'
          })
        }    
      })

      $("#add-report-form").submit(function (evt) {
        
        evt.preventDefault();
        var me  = $(this);
        var valid = false;
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var id = me.attr("data-id");

        for(var i = 0; i < form_data.length; i++){
          var obj = form_data[i];
          var value = obj.value;
          if(value !== ""){
            valid = true;
            break;
          }
        }

        if(valid){
          form_data = form_data.concat({
            "name" : "ward_record_id",
            "value" : ward_record_id
          })
          // console.log(url)
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
                $("#add-report-card").hide();
                writeReport(this,event);
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#add-report-form #'+key);
                
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
        }else{
          swal({
            title: 'Ooops',
            text: "Value Must Be Entered In At Least One Field",
            type: 'error'
          })
        }    
      })

      $("#edit-clinical-note-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        var ward_record_id = $("#choose-action-patient-modal").attr('data-id');
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

              var element = $('#edit-clinical-note-form #'+key);
              
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
      $("#add-new-clinical-note-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "ward_record_id",
          "value" : ward_record_id
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
              $("#add-new-clinical-note-card").hide();
              viewClinicalNotes(this,event)
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#add-new-clinical-note-form #'+key);
              
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

      $("#add-other-chart-data-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var valid = false;
        
        var other_chart_id = me.attr("data-id");
        var form_data = me.serializeArray();
        
        for(var i = 0; i < form_data.length; i++){
          var obj = form_data[i];
          var value = obj.value;
          if(value !== ""){
            valid = true;
            break;
          }
        }

        if(valid){
          form_data = form_data.concat({
            "name" : "ward_record_id",
            "value" : ward_record_id
          })

          form_data = form_data.concat({
            "name" : "other_chart_id",
            "value" : other_chart_id
          })
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
                $("#add-other-chart-data-card").hide();
                loadOtherChartInfo(this,event,other_chart_id);
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#add-other-chart-data-form #'+key);
                
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
        }else{
          swal({
            title: 'Ooops',
            text: "At Least One Parameter Must Be Entered To Proceed",
            type: 'error'
          })
        }
      })

      $("#add-other-charts-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var valid = false;
        var form_data = me.serializeArray();
        form_data.splice(0,1);
        for(var i = 0; i < form_data.length; i++){
          var obj = form_data[i];
          var value = obj.value;
          if(value !== ""){
            valid = true;
            break;
          }
        }
        // console.log(me.serializeArray())
        if(valid){
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : me.serializeArray(),
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success){
                $("#add-other-charts-card").hide();
                otherCharts(this,event);
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#add-other-charts-form #'+key);
                
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
        }else{
          swal({
            title: 'Ooops',
            text: "At Least One Parameter Must Be Given To Proceed",
            type: 'error'
          })
        }

          
      });

      $("#add-input-output-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr('action');
        var form_data = me.serializeArray();
        console.log(url)
        
        var valid = false;
        for(var i = 0; i < form_data.length; i++){
          var obj = form_data[i];
          var value = obj.value;
          if(value !== ""){
            valid = true;
            break;
          }
        }

        if(valid){
          
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
                $("#add-input-output-card").hide();
                inputOutputChart(this,event)
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#add-input-output-form #'+key);
                
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
        }else{
          swal({
            title: 'Ooops',
            text: "Value Must Be Entered In At Least One Field",
            type: 'error'
          })
        }
      })

      $("#add-vital-signs-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "ward_record_id",
          "value" : ward_record_id
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
              $("#add-vital-signs-card").hide();
              viewVitalSigns(this,event);
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#add-vital-signs-form #'+key);
              
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

      

      <?php if($this->session->other_chart_added){ ?>
         $.notify({
          message:"New Chart Created Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->vital_signs_added){ ?>
         $.notify({
          message:"Vital Signs Added Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->input_output_done){ ?>
         $.notify({
          message:"Successful"
        },{
          type : "success"  
        });
      <?php }  ?>

      
      <?php if($this->session->other_chart_data_added){ ?>
         $.notify({
          message:"Successful"
        },{
          type : "success"  
        });
      <?php }  ?>
      

      <?php if($this->session->clinic_note_added){ ?>
         $.notify({
          message:"Clinical Note Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->report_successful){ ?>
         $.notify({
          message:"Report Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->service_requested){ ?>
         $.notify({
          message:"Service Request Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->service_pay_later_successful){ ?>
         $.notify({
          message:"Service Request Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>
      
    });



</script>
