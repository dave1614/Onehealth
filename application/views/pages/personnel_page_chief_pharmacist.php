<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>
<script>
  var user_info = [];
  var selected_drugs = [];
  var patient_facility_id = "";
  var registered_patient = false;

  String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return (this.length > n) ? this.substr(0, n-1) + '&hellip;' : this;
      };

  function goBackSelectDrugs (elem,evt) {
    user_info = [];
    selected_drugs = [];

    if(!registered_patient){
      $("#select-drugs-card .card-title").html("");
      
      $("#enter-patient-data-modal").modal("show");
      $("#perform-functions-card").show();
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }else{
      $("#select-drugs-card .card-title").html("");
      
      $("#perform-action-on-patient-modal").modal("hide");
      $("#registered-patients-card").show();
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }
  }



  function goBackPerformFunctions(elem,evt){
    $("#main-card").show();
    $("#perform-functions-card").hide();
  }

  function goBackCounsellingFunctions (elem,evt) {
    $("#perform-functions-card").show();
    $("#perform-counselling-functions-card").hide();
  }

  function goBackAwaitingDispense (elem,evt) {
    $("#dispense-drugs-card").show();
    $("#all-awaiting-dispense-card").hide();
  }

  function goBackAwaitingDispenseInfo (argument) {
    $("#all-awaiting-dispense-card").show();
    $("#all-awaiting-dispense-card-info").hide();
  }

  function goBackEditErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-btn").show("fast");
    
    $("#edit-new-error-card").hide();
  }


  function loadYetToBeDispPatientInfo(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card").hide();
              $("#all-awaiting-dispense-card-info").show();
              $("#all-awaiting-dispense-card-info .card-body").html(messages);
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

  function goBackDispatchDrugs (elem,evt) {
    $("#dispense-drugs-card").hide();
    $("#perform-functions-card").show();
  }

  function goBackMainStoreCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#main-store-card").hide();
  }

  function goBackViewDrugMainStoreCard () {
    $("#main-store-card").show("slow");
    
    $("#view-drug-main-store-card").hide("slow");
    
  }

  function goBackReportCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#report-card").hide();
    $("#add-new-report-btn").hide("fast");
  }

  function goBackAddNewReport (elem,evt) {
    $("#report-card").show();
    $("#add-new-report-btn").show("fast");
    $("#add-new-report-card").hide("fast");
  }

  function goBackEditReport (elem,evt) {
    $("#edit-report-card").hide();
    $("#report-card").show();
    $("#add-new-report-btn").show("fast");
  }

  function goBackViewReport (elem,evt) {
    $("#view-report-card").hide();
    $("#report-card").show();
    $("#add-new-report-btn").show("fast");
  }

  function goBackAntiBioticsPatternCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#antibiotics-pattern-card").hide();
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

  function goBackOtherRegisters (elem,evt) {
    $("#perform-functions-card").show();
    
    $("#other-registers-card").hide();
  }

  function goBackAddNewRegister (elem,evt) {
    $("#perform-functions-card").show();
    $("#add-new-register-card").hide();
  }

  function goBackOtherRegisterValuesCard (elem,evt) {
    $("#other-registers-card").show();
    $("#other-registers-values-card").hide();
    $("#add-new-record-to-register-btn").hide("fast");
  }

  function goBackAddNewRecordToRegister (elem,evt) {
    $("#other-registers-values-card").show();
    $("#add-new-record-to-register-btn").show("fast");
    $("#add-new-record-to-register-card .form-group").hide();
    $("#add-new-record-to-register-card label").html("");
    $("#add-new-record-to-register-card textarea").val("");
    $("#add-new-record-to-register-card").hide();


  }

  function goBackOtherRegistersRecordsByDay (elem,evt) {
    $("#other-registers-values-card").show();
    $("#add-new-record-to-register-btn").show("fast");
    
    $("#other-registers-records-by-day-card").hide();
  }

  function goBackEditRecordRegister (elem,evt) {
    
    $("#other-registers-records-by-day-card").show();
    $("#edit-record-register-form label").html("");
    $("#edit-record-register-form textarea").val("");
    $("#edit-record-register-form").attr("data-register-id","");
    $("#edit-record-register-form").attr("data-register-val-id","");

    $("#edit-record-register-card").hide();

  }

  function goBackDisplayRecordRegister (elem,evt) {
    $("#other-registers-records-by-day-card").show();
            
    
    $("#display-record-register-card h4").html("");
    $("#display-record-register-card p").html("");
    $("#display-record-register-card .form-group").hide();
    $("#display-record-register-card").hide();

  }

  function loadOtherRegisterRecordInfo(elem,evt,register_id,register_value_id){
    if(register_id != "" && register_value_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_other_register_parameters_and_values'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "register_id="+register_id+"&register_value_id="+register_value_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.parameters != ""){
            var parameters = response.parameters;

            $("#other-registers-records-by-day-card").hide();
            
            if(parameters.parameter_1 != false){
              var parameter_1 = parameters.parameter_1;
              var parameter_1_val = parameters.parameter_1_val;
              $("#display-record-register-card #parameter_1").parent(".form-group").find("h4").html(" "+parameter_1 + ": ");
              $("#display-record-register-card #parameter_1").html(parameter_1_val);
              $("#display-record-register-card #parameter_1").parent(".form-group").show();

            }

            if(parameters.parameter_2 != false){
              var parameter_2 = parameters.parameter_2;
              var parameter_2_val = parameters.parameter_2_val;
              $("#display-record-register-card #parameter_2").parent(".form-group").find("h4").html(" "+parameter_2 + ": ");
              $("#display-record-register-card #parameter_2").html(parameter_2_val);
              $("#display-record-register-card #parameter_2").parent(".form-group").show();

            }

            if(parameters.parameter_3 != false){
              var parameter_3 = parameters.parameter_3;
              var parameter_3_val = parameters.parameter_3_val;
              $("#display-record-register-card #parameter_3").parent(".form-group").find("h4").html(" "+parameter_3 + ": ");
              $("#display-record-register-card #parameter_3").html(parameter_3_val);
              $("#display-record-register-card #parameter_3").parent(".form-group").show();

            }

            if(parameters.parameter_4 != false){
              var parameter_4 = parameters.parameter_4;
              var parameter_4_val = parameters.parameter_4_val;
              $("#display-record-register-card #parameter_4").parent(".form-group").find("h4").html(" "+parameter_4 + ": ");
              $("#display-record-register-card #parameter_4").html(parameter_4_val);
              $("#display-record-register-card #parameter_4").parent(".form-group").show();

            }

            if(parameters.parameter_5 != false){
              var parameter_5 = parameters.parameter_5;
              var parameter_5_val = parameters.parameter_5_val;
              $("#display-record-register-card #parameter_5").parent(".form-group").find("h4").html(" "+parameter_5 + ": ");
              $("#display-record-register-card #parameter_5").html(parameter_5_val);
              $("#display-record-register-card #parameter_5").parent(".form-group").show();

            }
            

            $("#display-record-register-card").show();

            
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
    }
  }

  function loadOtherRegisterRecordInfoEdut(elem,evt,register_id,register_value_id){
    if(register_id != "" && register_value_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_other_register_parameters_and_values'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "register_id="+register_id+"&register_value_id="+register_value_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.parameters != ""){
            var parameters = response.parameters;

            $("#other-registers-records-by-day-card").hide();
            
            if(parameters.parameter_1 != false){
              var parameter_1 = parameters.parameter_1;
              var parameter_1_val = parameters.parameter_1_val;
              $("#edit-record-register-form #parameter_1").parent(".form-group").find("label").html(" "+parameter_1 + ": ");
              $("#edit-record-register-form #parameter_1").val(parameter_1_val);
              $("#edit-record-register-form #parameter_1").parent(".form-group").show();

            }

            if(parameters.parameter_2 != false){
              var parameter_2 = parameters.parameter_2;
              var parameter_2_val = parameters.parameter_2_val;
              $("#edit-record-register-form #parameter_2").parent(".form-group").find("label").html(" "+parameter_2 + ": ");
              $("#edit-record-register-form #parameter_2").val(parameter_2_val);
              $("#edit-record-register-form #parameter_2").parent(".form-group").show();

            }

            if(parameters.parameter_3 != false){
              var parameter_3 = parameters.parameter_3;
              var parameter_3_val = parameters.parameter_3_val;
              $("#edit-record-register-form #parameter_3").parent(".form-group").find("label").html(" "+parameter_3 + ": ");
              $("#edit-record-register-form #parameter_3").val(parameter_3_val);
              $("#edit-record-register-form #parameter_3").parent(".form-group").show();

            }

            if(parameters.parameter_4 != false){
              var parameter_4 = parameters.parameter_4;
              var parameter_4_val = parameters.parameter_4_val;
              $("#edit-record-register-form #parameter_4").parent(".form-group").find("label").html(" "+parameter_4 + ": ");
              $("#edit-record-register-form #parameter_4").val(parameter_4_val);
              $("#edit-record-register-form #parameter_4").parent(".form-group").show();

            }

            if(parameters.parameter_5 != false){
              var parameter_5 = parameters.parameter_5;
              var parameter_5_val = parameters.parameter_5_val;
              $("#edit-record-register-form #parameter_5").parent(".form-group").find("label").html(" "+parameter_5 + ": ");
              $("#edit-record-register-form #parameter_5").val(parameter_5_val);
              $("#edit-record-register-form #parameter_5").parent(".form-group").show();

            }
            $("#edit-record-register-form").attr("data-register-id",register_id);
            $("#edit-record-register-form").attr("data-register-val-id",register_value_id);

            $("#edit-record-register-card").show();

            
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
    }
  }

  function loadOtherRegisterRecordsByDate(elem,evt,register_id){
    var date = $(elem).attr("data-date");
    if(date != "" && register_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_other_register_records_for_particular_day'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "register_id="+register_id+"&date="+date,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;

            $("#other-registers-values-card").hide();
            $("#add-new-record-to-register-btn").hide("fast");
            $("#other-registers-records-by-day-card .card-title").html("Records Entered On: " + date);
            $("#other-registers-records-by-day-card .card-body").html(messages);
            $("#other-registers-records-by-day-card #other-registers-records-by-day-table").DataTable();
            $("#other-registers-records-by-day-card").show();

            
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
    }
  }

  function addNewRecordToOtherRegister(elem,evt){
    var id = $(elem).attr("data-id");
    if(id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_other_register_parameters'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.parameters != ""){
            var parameters = response.parameters;

            $("#other-registers-values-card").hide();
            $("#add-new-record-to-register-btn").hide("fast");
            if(parameters.parameter_1 != false){
              var parameter_1 = parameters.parameter_1;
              $("#add-new-record-register-form #parameter_1").parent(".form-group").find("label").html(" "+parameter_1 + ": ");
              $("#add-new-record-register-form #parameter_1").parent(".form-group").show();

            }

            if(parameters.parameter_2 != false){
              var parameter_2 = parameters.parameter_2;
              $("#add-new-record-register-form #parameter_2").parent(".form-group").find("label").html(" "+parameter_2 + ": ");
              $("#add-new-record-register-form #parameter_2").parent(".form-group").show();

            }

            if(parameters.parameter_3 != false){
              var parameter_3 = parameters.parameter_3;
              $("#add-new-record-register-form #parameter_3").parent(".form-group").find("label").html(" "+parameter_3 + ": ");
              $("#add-new-record-register-form #parameter_3").parent(".form-group").show();

            }

            if(parameters.parameter_4 != false){
              var parameter_4 = parameters.parameter_4;
              $("#add-new-record-register-form #parameter_4").parent(".form-group").find("label").html(" "+parameter_4 + ": ");
              $("#add-new-record-register-form #parameter_4").parent(".form-group").show();

            }

            if(parameters.parameter_5 != false){
              var parameter_5 = parameters.parameter_5;
              $("#add-new-record-register-form #parameter_5").parent(".form-group").find("label").html(" "+parameter_5 + ": ");
              $("#add-new-record-register-form #parameter_5").parent(".form-group").show();

            }
            $("#add-new-record-register-form").attr("data-id",id);

            $("#add-new-record-to-register-card").show();

            
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
    }
  }

  function loadOtherRegisterInfo (elem,evt,id) {
    if(id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_register_records_pharmacy'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#other-registers-card").hide();
            $("#other-registers-values-card .card-body").html(messages)
            $("#other-registers-values-card #other-register-values-table").DataTable();
            $("#other-registers-values-card").show();
            $("#add-new-record-to-register-btn").show("fast");
            $("#add-new-record-to-register-btn").attr("data-id",id);
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
    }
  }

  function viewAndCreateRegister (elem,evt) {
    swal({
      title: 'Choose Action',
      text: "Do You Want To:",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Other Registers',
      cancelButtonText: 'Create New Register'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_other_registers_pharmacy'); ?>";
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
            $("#perform-functions-card").hide();
            $("#other-registers-card .card-body").html(messages)
            $("#other-registers-card #other-register-table").DataTable();
            $("#other-registers-card").show();
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $("#perform-functions-card").hide();
        $("#add-new-register-card").show();
      }
    });  
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

  function antibioticsPattern(elem,evt){
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_antibiotics_pattern'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#antibiotics-pattern-card .card-body").html(messages);
          $("#antibiotics-pattern-card").show();
          $("#antibiotics-pattern-card #antibiotics-pattern-table").DataTable();
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

  function loadPharmacyReportInfo(elem,evt,id) {
    var elem = $(elem);
    var protocols = elem.attr("data-protocols");
    var events = elem.attr("data-events");
    var way_forward = elem.attr("data-way-forward");
    var conclusion = elem.attr("data-conclusion");

    if(id != "" && protocols != "" && events != "" && way_forward != "" && conclusion != ""){
      $("#view-report-card #protocols").html(protocols);
      $("#view-report-card #events").html(events);
      $("#view-report-card #way_forward").html(way_forward);
      $("#view-report-card #conclusion").html(conclusion);
      $("#view-report-card").show();
      $("#report-card").hide();
      $("#add-new-report-btn").hide("fast");
    }else{
      swal({
        title: 'Error!',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadPharmacyReportInfoEdit(elem,evt,id) {
    var elem = $(elem);
    var protocols = elem.attr("data-protocols");
    var events = elem.attr("data-events");
    var way_forward = elem.attr("data-way-forward");
    var conclusion = elem.attr("data-conclusion");

    if(id != "" && protocols != "" && events != "" && way_forward != "" && conclusion != ""){
      $("#edit-report-form #protocols").val(protocols);
      $("#edit-report-form #events").val(events);
      $("#edit-report-form #way_forward").val(way_forward);
      $("#edit-report-form #conclusion").val(conclusion);
      $("#edit-report-form").attr("data-id",id);
      $("#edit-report-card").show();
      $("#report-card").hide();
      $("#add-new-report-btn").hide("fast");
    }else{
      swal({
        title: 'Error!',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function addNewReport (elem,evt) {
    $("#report-card").hide();
    $("#add-new-report-btn").hide("fast");
    $("#add-new-report-card").show("fast");

  }

  function writeReport (elem,evt) {  
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_pharmacy_reports'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#report-card .card-body").html(messages);
          $("#report-card #report-table").DataTable();
          $("#perform-functions-card").hide();
          $("#report-card").show();
          $("#add-new-report-btn").show("fast");
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

  function viewDrugMainStore (elem,event,id) {
    if(id != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_drug_main_store'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#main-store-card").hide();
            $("#view-drug-main-store-card .card-body").html(messages);
            $("#view-drug-main-store-card").show();
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

  function viewStoreRecords (elem,evt) {
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_main_store'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#main-store-card .card-body").html(messages);
          $("#main-store-card #main-store-table").DataTable();
          $("#main-store-card").show();
          
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

  function dispensePatientDrugs(elem,evt,id){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != "" && id != ""){
      swal({
        title: 'Choose Action',
        text: "You Are About To Mark This Drug As Dispensed. Are You Sure You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText : "No"
      }).then(function(){
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_patient_as_dipensed_otc_patients_pharmacy'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code+"&id="+id,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true){
              $(elem).hide();
              $.notify({
              message:"Successfully Marked As Dispensed"
              },{
                type : "success"  
              });
            }else if(response.already_dispensed){
              swal({
                title: 'Ooops!',
                text: "Sorry This Drug Has Already Been Dispensed",
                type: 'error'
              })
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

  function dispatchPatientDrugs(elem,evt,id){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != "" && id != ""){
      swal({
        title: 'Choose Action',
        text: "You Are About To Mark This Patient's Drugs As Dispatched. Are You Sure You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText : "No"
      }).then(function(){
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_patient_as_dispatched_otc_patients_pharmacy'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code+"&id="+id,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
              message:"Drug Successfully Marked As Dispatched"
              },{
                type : "success"  
              });
              $(elem).before('<span class="text-success" style="font-style:italic;">Dispatched!</span>');
              $(elem).hide();
            }else if(response.already_dispatched){
              swal({
                title: 'Ooops!',
                text: "Sorry This Drug Has Already Been Dispatched",
                type: 'error'
              })
            }else if(response.not_dispensed){
              swal({
                title: 'Ooops!',
                text: "Sorry This Patient's Drugs Have Not Yet Been Dispensed. You Must Dispense Before Dispatching",
                type: 'error'
              })
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

  function otcPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card .card-body").html(messages);
          $("#all-awaiting-dispense-card #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card").show();
          
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

   function selectDrugsProceed2 () {
    var proceed = false;

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

      if(registered_patient){
        user_info.patient_facility_id = patient_facility_id;
      }
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
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
                var text = "Prescription Made Successfully"
                if(response.nfp){
                  text += ". This Patient Is A None Fee Paying Patient. Proceed To Dispensing And Dispatchig Officer."
                }
                
                $.notify({
                message : text
                },{
                  type : "success"  
                });

                document.location.reload();
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

  function dosageEvent(elem,event,i){
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
      selectDrugsProceed2()
    }
  }

  function frequencyEvent1(elem,event,i){
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
      selectDrugsProceed2()
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

  function durationEvent1(elem,event,i){
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
      selectDrugsProceed2()
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


  function selectDrugsProceed(elem,evt) {
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


  function goBackSelectedInfoDrugs(elem,evt){
    $("#select-drugs-card").show();
    $("#select-drugs-proceed-btn").show("fast");
    $("#select-drugs-proceed-btn-2").hide("fast");
    $("#selected-drugs-info-card").hide();    
  }

  function transcribePrescriptions(elem,evt){
    $("#enter-patient-data-modal").modal("show");
  }

  function performCounsellingPharmacistsFunction (elem,evt) {
    $("#perform-functions-card").hide();
    $("#perform-counselling-functions-card").show();
  }

  function performFunctions(elem,evt){
    $("#main-card").hide();
    $("#perform-functions-card").show();
  }

  function performDispatchFunctions(elem,evt){
    $("#perform-functions-card").hide();
    $("#dispense-drugs-card").show();
  }

  function wardPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy_ward'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card-ward .card-body").html(messages);
          $("#all-awaiting-dispense-card-ward #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card-ward").show();
          
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

  function loadYetToBeDispPatientInfoWard(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card-ward").hide();
              $("#all-awaiting-dispense-card-info-ward").show();
              $("#all-awaiting-dispense-card-info-ward .card-body").html(messages);
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

  function goBackAwaitingDispenseWard (elem,evt) {
    $("#dispense-drugs-card").show();
    
    $("#all-awaiting-dispense-card-ward").hide();
  }

  function goBackAwaitingDispenseInfoWard (elem,evt) {
    $("#all-awaiting-dispense-card-ward").show();
    $("#all-awaiting-dispense-card-info-ward").hide();
    
  }

  function clinicPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy_clinic'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card-clinic .card-body").html(messages);
          $("#all-awaiting-dispense-card-clinic #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card-clinic").show();
          
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

  function goBackAwaitingDispenseClinic (elem,evt) {
    $("#dispense-drugs-card").show();
    
    $("#all-awaiting-dispense-card-clinic").hide();
  }

  function loadYetToBeDispPatientInfoClinic(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card-clinic").hide();
              $("#all-awaiting-dispense-card-info-clinic").show();
              $("#all-awaiting-dispense-card-info-clinic .card-body").html(messages);
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

  function goBackAwaitingDispenseInfoClinic (elem,evt) {
    $("#all-awaiting-dispense-card-clinic").show();
    $("#all-awaiting-dispense-card-info-clinic").hide();
    
  }

  function performStoreManagersFunction (elem,evt) {
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

  function transcribePrescriptionsHospital(elem,evt){
    swal({
      title: 'Choose Action',
      text: "Do You Want Select Drugs For: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Registered Patients',
      cancelButtonText: 'Over The Counter Patients'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_all_registered_patients_pharmacy'); ?>";
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
            $("#perform-counselling-functions-card").hide("fast");
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        registered_patient = false;
        $("#enter-patient-data-modal").modal("show");
      }
    });  
  }

  function goBackRegisteredPatientsCard (elem,evt) {
    $("#perform-counselling-functions-card").show("fast");
    $("#registered-patients-card").hide("fast");
  }

   function performActionOnPatient (elem,evt,id) {
    elem = $(elem);
    if(id != ""){
      patient_full_name = elem.attr("data-patient-name");
      patient_facility_id = id;
      $("#perform-action-on-patient-modal .modal-title").html("Choose Action To Perform On " + patient_full_name + ":")
      $("#perform-action-on-patient-modal").modal("show");
    }
  }

  function editPatientInfo(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/'.$fourth_addition.'/load_edit_patient_info_form') ?>";
    
   
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
            $("#edit-patient-info-card .my-select").selectpicker();
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

  function userTypeChangedEdit (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val);
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
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_edit_patint_info_form') ?>";

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

  function selectDrugsForPatient (elem,evt) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_info_for_drugs_selection_pharmacy') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&patient_facility_id="+patient_facility_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){ 
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy') ?>";
          var form_data = response.messages;
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
              if(response.success && response.messages != "" && response.user_info != ""){
                var messages = response.messages;
                var patient_name = response.patient_name;
                user_info = response.user_info;
                registered_patient = true;

                $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
                $("#select-drugs-card .card-body").html(messages);
                $("#perform-action-on-patient-modal").modal("hide");
                $("#registered-patients-card").hide();
                $("#select-drugs-card #select-drugs-table").DataTable();
                $("#select-drugs-card").show();
                $("#select-drugs-proceed-btn").show("fast");
              }else if(response.expired){
                $.notify({
                message:"Date Entered Must Be In The In The Future"
                },{
                  type : "warning"  
                });
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
                text: "Something Went Wrong. Please Check Your Internet Connection",
                type: 'error'
              })
            } 
          });
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
        message:"Sorry something went wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
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

              <div class="card" id="edit-patient-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Change Patient Type</h3>
                  <button onclick="goBackFromEditPatientInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="registered-patients-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackRegisteredPatientsCard(this,event)">Go Back</button>
                  <h3 class="card-title"></h3>
                  
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="performFunctions(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfoClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfoWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
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
                          <td onclick="performCounsellingPharmacistsFunction(this,event)">Perform Counselling Pharmacist's Functions</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="performDispatchFunctions(this,event)">Perform Dispatching And Dispensing Functions</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="viewStoreRecords(this,event)">View Store Records</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td onclick="performStoreManagersFunction(this,event)" style="text-transform: capitalize;">Perform Store Managers Functions</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td onclick="writeReport(this,event)" style="text-transform: capitalize;">Write Pharmacy Report</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td onclick="poisonRegister(this,event)" style="text-transform: capitalize;">View Poison Register</td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td onclick="errorRegister(this,event)" style="text-transform: capitalize;">View And Update Error And Occurence Register</td>
                        </tr>

                        
                        <tr>
                          <td>8</td>
                          <td onclick="antibioticsPattern(this,event)" style="text-transform: capitalize;">View Antibiotics Pattern</td>
                        </tr>

                        <tr>
                          <td>9</td>
                          <td onclick="viewAndCreateRegister(this,event)" style="text-transform: capitalize;">View And Create New Registers</td>
                        </tr>
                        
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>11</td>
                          <td onclick="viewClinicPatientsRecords(this,event)" style="text-transform: capitalize;">View Clinic Patients Records</td>
                        </tr> -->
                        <?php } ?>
                        
                      </tbody>
                    </table>
                  </div> 
                </div>
              </div>

              <div class="card" id="perform-counselling-functions-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Counselling Pharmacist's Functions</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackCounsellingFunctions(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-2" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($health_facility_structure == "pharmacy"){ ?>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptions(this,event)">Transcribe / Make Prescription</td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptionsHospital(this,event)">Transcribe / Make Prescription</td>
                        </tr>
                        <?php } ?>
                        <!-- <tr>
                          <td>2</td>
                          <td onclick="viewWaitingPatients(this,event)">View Waiting Patients</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="onlineRequests(this,event)">View Online Requests</td>
                        </tr> -->
                        
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>4</td>
                          <td onclick="viewWardPatients(this,event)" style="text-transform: capitalize;">View Ward Patients</td>
                        </tr> -->
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>              
                </div> 
              </div>

              <div class="card" id="dispense-drugs-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackDispatchDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Dispense / Dispatch For: </h4>
                  <button onclick="wardPatients(this,event)" class="btn btn-primary">Ward Patients</button>
                  <button onclick="clinicPatients(this,event)" class="btn btn-info">Clinic Patients</button>
                  <button onclick="otcPatients(this,event)" class="btn btn-success">Over The Counter Patients</button>
                </div>
              </div>

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

              <div class="card" id="other-registers-records-by-day-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOtherRegistersRecordsByDay(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispense(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="other-registers-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Other Registers: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOtherRegisters(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfo(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Main Store </h3>
                </div>
                <div class="card-body">
                              
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

              <div class="card" id="report-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackReportCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">All Pharmacy Reports</h3>
                </div>
                <div class="card-body">
                              
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

              <div class="card" id="other-registers-values-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOtherRegisterValuesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Days Records Where Entered In This Register</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="antibiotics-pattern-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAntiBioticsPatternCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Antibiotics Pattern</h3>
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

              <div class="card" id="add-new-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Pharmacy Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-register-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_pharmacy_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="name" class="label-control"> <span class="form-error1">*</span> Name: </label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="parameter_1" class="label-control"> <span class="form-error1">*</span> Parameter 1: </label>
                        <input type="text" name="parameter_1" id="parameter_1" class="form-control">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-6">
                        <label for="parameter_2" class="label-control"> Parameter 2: </label>
                        <input type="text" name="parameter_2" id="parameter_2" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="parameter_3" class="label-control"> Parameter 3: </label>
                        <input type="text" name="parameter_3" id="parameter_3" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="parameter_4" class="label-control"> Parameter 4: </label>
                        <input type="text" name="parameter_4" id="parameter_4" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="parameter_5" class="label-control"> Parameter 5: </label>
                        <input type="text" name="parameter_5" id="parameter_5" class="form-control">
                        <span class="form-error"></span>
                      </div>

                      
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="display-record-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackDisplayRecordRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <div class="row">
                    
                    <div class="form-group col-sm-12" style="display: none;">
                      <h4 class="label-control"> Parameter 1: </h4>
                      <p id="parameter_1" class="form-control"></p>
                      
                    </div> 

                    <div class="form-group col-sm-12" style="display: none;">
                      <h4 class="label-control"> Parameter 2: </h4>
                      <p id="parameter_2" class="form-control"></p>
                      
                    </div> 

                    <div class="form-group col-sm-12" style="display: none;">
                      <h4 class="label-control"> Parameter 3: </h4>
                      <p id="parameter_3" class="form-control"></p>
                      
                    </div> 

                    <div class="form-group col-sm-12" style="display: none;">
                      <h4 class="label-control"> Parameter 4: </h4>
                      <p id="parameter_4" class="form-control"></p>
                      
                    </div> 

                    <div class="form-group col-sm-12" style="display: none;">
                      <h4 class="label-control"> Parameter 5: </h4>
                      <p id="parameter_5" class="form-control"></p>
                      
                    </div> 
                  </div>
                   
                </div>
              </div>

              <div class="card" id="edit-record-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit This Record</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditRecordRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'edit-record-register-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_pharmacy_other_register_record',$attr);
                  ?>
                    
                    <div class="form-row">
                      
                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_1" class="label-control"> Parameter 1: </label>
                        <textarea cols="10" rows="10" name="parameter_1" id="parameter_1" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_2" class="label-control"> Parameter 2: </label>
                        <textarea cols="10" rows="10" name="parameter_2" id="parameter_2" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_3" class="label-control"> Parameter 3: </label>
                        <textarea cols="10" rows="10" name="parameter_3" id="parameter_3" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_4" class="label-control"> Parameter 4: </label>
                        <textarea cols="10" rows="10" name="parameter_4" id="parameter_4" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_5" class="label-control"> Parameter 5: </label>
                        <textarea cols="10" rows="10" name="parameter_5" id="parameter_5" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="add-new-record-to-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Record To This Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewRecordToRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-record-register-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_record_to_pharmacy_register',$attr);
                  ?>
                    
                    <div class="form-row">
                      
                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_1" class="label-control"> Parameter 1: </label>
                        <textarea cols="10" rows="10" name="parameter_1" id="parameter_1" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_2" class="label-control"> Parameter 2: </label>
                        <textarea cols="10" rows="10" name="parameter_2" id="parameter_2" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_3" class="label-control"> Parameter 3: </label>
                        <textarea cols="10" rows="10" name="parameter_3" id="parameter_3" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_4" class="label-control"> Parameter 4: </label>
                        <textarea cols="10" rows="10" name="parameter_4" id="parameter_4" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12" style="display: none;">
                        <label for="parameter_5" class="label-control"> Parameter 5: </label>
                        <textarea cols="10" rows="10" name="parameter_5" id="parameter_5" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="add-new-report-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Pharmacy Report</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewReport(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-report-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_new_pharmacy_report',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="protocols" class="label-control"> <span class="form-error1">*</span> Protocols: </label>
                        <textarea name="protocols" id="protocols" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12">
                        <label for="events" class="label-control"> <span class="form-error1">*</span> Events: </label>
                        <textarea name="events" id="events" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-12">
                        <label for="way_forward" class="label-control"> <span class="form-error1">*</span> Way Forward: </label>
                        <textarea name="way_forward" id="way_forward" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12">
                        <label for="conclusion" class="label-control"> <span class="form-error1">*</span> Conclusion: </label>
                        <textarea name="conclusion" id="conclusion" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="edit-report-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit This Pharmacy Report</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditReport(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'edit-report-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_pharmacy_report',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="protocols" class="label-control"> <span class="form-error1">*</span> Protocols: </label>
                        <textarea name="protocols" id="protocols" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12">
                        <label for="events" class="label-control"> <span class="form-error1">*</span> Events: </label>
                        <textarea name="events" id="events" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-12">
                        <label for="way_forward" class="label-control"> <span class="form-error1">*</span> Way Forward: </label>
                        <textarea name="way_forward" id="way_forward" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-12">
                        <label for="conclusion" class="label-control"> <span class="form-error1">*</span> Conclusion: </label>
                        <textarea name="conclusion" id="conclusion" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="view-report-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Pharmacy Report Info</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackViewReport(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <h4> Protocols: </h4>
                      <p id="protocols"></p>
                     
                    </div>

                   <div class="col-sm-12">
                      <h4> Events: </h4>
                      <p id="events"></p>
                     
                    </div>

                    <div class="col-sm-12">
                      <h4> Way Forward: </h4>
                      <p id="way_forward"></p>
                    </div>

                    <div class="col-sm-12">
                      <h4> Conclusion: </h4>
                      <p id="conclusion"></p>
                     
                    </div> 
                  </div>  
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
                        <td onclick="selectDrugsForPatient(this,event)" class="text-primary">Select Drugs For This Patient</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td onclick="editPatientInfo(this,event)" class="text-primary">Edit Patient Info</td>
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

        <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-patient-data-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" style="text-transform: capitalize;">Enter Patient Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <?php 
                  $attr = array('id' => 'enter-patient-data-form');
                  echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy',$attr);
                ?>
                <span class="form-error1">*</span> : Required
                <div class="wrap">
                  <div class="form-row">                 
                    <div class="form-group col-md-12">
                      <label for="full_name" class="label-control"><span class="form-error1">*</span> FullName: </label>
                      <input type="text" class="form-control" id="full_name" name="full_name" value="">
                      <span class="form-error"></span>
                    </div>
                    

                    <div class="form-group col-md-12">
                      <label for="dob" class="label-control">  Date Of Birth: </label>
                      <input type="date" class="form-control" name="dob" id="dob">
                      <span class="form-error"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                      <label for="age" class="label-control">  Age: </label>
                      <input type="number" class="form-control" name="age" id="age">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-md-12">
                      <div id="age_unit">
                        <label for="age_unit" class="label-control">  Age Unit: </label>
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

                    <div class="form-group col-md-12">
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

                    <div class="form-group col-md-12">
                      <label for="clinician" class="label-control">  Clinician: </label>
                      <input type="text" class="form-control" name="clinician" id="clinician">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-md-12">
                      <label for="race" class="label-control">  Race/Tribe: </label>
                      <input type="text" class="form-control" id="race" name="race">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-md-12">
                      <label for="mobile" class="label-control">  Mobile No: </label>
                      <input type="number" class="form-control" id="mobile" name="mobile">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-md-12">
                      <label for="email" class="label-control">  Email: </label>
                      <input type="email" class="form-control" id="email" name="email">
                      <span class="form-error"></span>
                    </div>
                    <div class="form-group col-md-12">
                      <label for="address" class="label-control">  Address: </label>
                      <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                      <span class="form-error"></span>
                    </div>

                  </div>
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
          </div>
        </div>

        <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-clinician-name-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title text-primary" style="text-transform: capitalize;">One Or More Poisons Selected.</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p>You Need To Enter Clinician's Name To Proceed You Need To Enter Clinician's Name To Proceed</p>
              <?php 
                $attr = array('id' => 'enter-clinician-name-form');
                echo form_open('',$attr);
              ?>
              <div class="form-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="Clinician's Name">
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
        
        <div id="add-new-record-to-register-btn" onclick="addNewRecordToOtherRegister(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Record To This Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
          <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
            <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

          </div>
        </div>


        <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
          <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
            <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

          </div>
        </div>

        <div id="add-new-report-btn" onclick="addNewReport(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Pharmacy Report" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
          <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
            <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

          </div>
        </div>

        <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

            </div>
          </div>


          <div id="select-drugs-proceed-btn-2" onclick="selectDrugsProceed2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>
          
          <div id="select-drugs-proceed-btn" onclick="selectDrugsProceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
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
    $("#edit-record-register-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      var register_id = me.attr("data-register-id");
      var register_value_id = me.attr("data-register-val-id");
      form_data = form_data.concat({
        "name" : "register_id",
        "value" : register_id
      })

      form_data = form_data.concat({
        "name" : "register_value_id",
        "value" : register_value_id
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
            $.notify({
            message:"Successfully Edited"
            },{
              type : "success"  
            });
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#edit-record-register-form #'+key);
            
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

    

    $("#add-new-record-register-form").submit(function (evt) {
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
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#add-new-record-register-form #'+key);
            
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

    $("#add-new-register-form").submit(function (evt) {
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
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#add-new-register-form #'+key);
            
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

    $("#edit-report-form").submit(function (evt) {
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
    });

    $("#add-new-report-form").submit(function (evt) {
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
            document.location.reload();
          }else{
            $.each(response.messages, function (key,value) {

            var element = $('#add-new-report-form #'+key);
            
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

    $("#enter-clinician-name-form").submit(function (evt) {
      evt.preventDefault();
      var name = $(this).find("#name").val();
      console.log(name)
      console.log(name.length)
      if(name !== ""){
        if(name.length >= 10 && name.length <= 100){
          user_info.clinician = name;
          $("#enter-clinician-name-modal").modal("hide");
          selectDrugsProceed2();
        }else{
          $.notify({
          message:"This Field Must Be Between 10 To 100 Characters"
          },{
            type : "warning"  
          });
        }
      }else{
        $.notify({
        message:"This Field Is Required"
        },{
          type : "warning"  
        });
      }
    })

    $("#enter-patient-data-form").submit(function (evt) {
      evt.preventDefault();
      registered_patient = false;
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
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card .card-body").html(messages);
            $("#enter-patient-data-modal").modal("hide");
            $("#perform-functions-card").hide();
            $("#select-drugs-card #select-drugs-table").DataTable();
            $("#select-drugs-card").show();
            $("#select-drugs-proceed-btn").show("fast");
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#enter-patient-data-form #'+key);
            
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
    

    <?php if($this->session->register_record_created){ ?>
     $.notify({
      message:"Pharmacy Record Created Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->register_created){ ?>
     $.notify({
      message:"Pharmacy Register Created Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->report_created){ ?>
     $.notify({
      message:"Pharmacy Report Made Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->prescription_success){ ?>
     $.notify({
      message:"Prescription Made Successfully"
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
