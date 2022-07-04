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
          if(is_null($health_facility_logo)){
            $no_logo = true;
            
            $data_url_img = "<img style='display:none;' id='facility_img' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$health_facility_name."' col='".$color."'>";
            
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
?>
<style>
  tr{
    cursor: pointer;
  }
</style>
<script>
  var lab_base_url = "";
  var lab_dept_id = "";
  var lab_sub_dept_id = "";

  function goDefault() {
    
    document.location.reload();
  }

  function performFunctions (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_registrations_pathologist') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#all-registered-bodies-card .card-body").html(messages);
          $("#all-registered-bodies-table").DataTable();
          $("#main-card").hide();
          $("#all-registered-bodies-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    });
  }

  function goBackAllRegisteredBodiesCard (elem,evt) {
    $("#main-card").show();
    $("#all-registered-bodies-card").hide();
  }

  function performActionsOnBody (elem,evt,id) {
    var name = $(elem).attr("data-name");
    var autopsy_requested = $(elem).attr("data-autopsy");
    if(name != ""){
      $("#perform-action-modal .modal-title").html("Choose Action To Be Performed On " + name);
    }

    if(autopsy_requested == 1){
      $("#perform-action-modal #autopsy_status").html("Autopsy Was Requested By Referring Dr.");
    }else{
      $("#perform-action-modal #autopsy_status").html("");
    }
    $("#perform-action-modal").attr("data-id",id);
    $("#perform-action-modal").modal("show");
    
    
  }

  function inputAutopsyFindings (elem,evt) {
    var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/input_autopsy_findings_histopathologist') ?>";
    var form_data = {
      "mortuary_record_id" : mortuary_record_id
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success && response.messages.length != 0){
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_body_by_id') ?>";
          var form_data = {
            "show_records" : true,
            'id' : mortuary_record_id
          }
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response1) {
              $(".spinner-overlay").hide();
              if(response1.success == true && response1.messages != ""){
                for (const [key, value] of Object.entries(response.messages)) {
                    $("#input-autopsy-findings-form #" +key).val(value);
                }
                $("#input-autopsy-findings-form").before(response1.messages);
                $("#input-autopsy-findings-card").show();
                $("#perform-action-modal").modal("hide");
                $("#all-registered-bodies-card").hide();
              }else{
                $.notify({
                message:"No Record To Display"
                },{
                  type : "warning"  
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
          }); 
        }else if(!response.success && response.messages != ""){
          $.notify({
          message:response.messages
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
        message:"Sorry Something Went Wrong"
        },{
          type : "danger"  
        });
      } 
    });
    
  }

  function goBackInputAutopsyFindingsCard (elem,evt) {
   $("#input-autopsy-findings-card .row").remove();
    $("#all-registered-bodies-card").show();
    $("#perform-action-modal").modal("show");
    
    $("#input-autopsy-findings-card").hide();
  }

  function checkIfImageIsSelected (elem,evt) {
    elem = $(elem);
    var btn = elem.next(".btn-info");
    if(elem.val() !== ""){
      btn.removeClass('disabled');
    }else{
      btn.addClass('disabled');
    }
  }

  function submitImage (elem,evt) {
    evt.preventDefault()
    var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    
    var file_input = elem.previousElementSibling;
    
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
          var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/upload_autopsy_images/') ?>"+mortuary_record_id;
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
                console.log(response)
                if(response == Number(response)){
                  $.notify({
                  message:"Image Upload Successful"
                  },{
                    type : "success"  
                  });
                  file_input.value == "";
                  
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

  function viewPreviousImages (elem,evt) {
    evt.preventDefault();
     var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    var get_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_autopsy_images') ?>";

    $(".spinner-overlay").show();
      $.ajax({
        url : get_test_images,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : "mortuary_record_id="+mortuary_record_id,
        
        success : function (response) {  
          $(".spinner-overlay").hide();
          $("#show-images-modal .modal-body").html(response); 
          $("#show-images-modal").modal("show"); 
          
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
      var delete_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/delete_autopsy_image') ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : delete_test_images,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : "id="+id+"&image_name="+image_name+"&index="+index,
        
        success : function (response) {
          $(".spinner-overlay").hide();   
          console.log(response)
          if(response == 1){
            $.notify({
            message:"Image Deleted Successfully"
            },{
              type : "success"  
            });
            $("#show-images-modal").modal("hide");
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
        } 
      });
    });   
  }

  function deathCertificate (elem,evt) {
    evt.preventDefault();
    var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/check_if_cause_of_death_was_inputted_mortuary') ?>";
    var form_data = {
      "show_records" : true,
      "mortuary_record_id" : mortuary_record_id
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true){
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/check_if_personnel_has_name_inputed') ?>";
          var form_data = {
            "show_records" : true,
            "mortuary_record_id" : mortuary_record_id
          }
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.success == true){
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/check_if_personnel_has_signature_inputed') ?>";
                var form_data = {
                  "show_records" : true,
                  "mortuary_record_id" : mortuary_record_id
                }
                $(".spinner-overlay").show();
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : form_data,
                  success : function (response) {
                    $(".spinner-overlay").hide();
                    console.log(response)
                    if(response.success == true && response.image_url){
                      var image_url = response.image_url;
                      
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
                      
                      $(".spinner-overlay").show();
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/update_mortuary_death_certificate'); ?>";
                      $.ajax({
                        url : url,
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : {
                          "mortuary_record_id" : mortuary_record_id
                        },
                        success : function (response) {
                          console.log(response)
                          $(".spinner-overlay").hide();
                          if(response.success){
                            var certificate_file = response.certificate_file;
                            var patient_name = response.patient_name;
                            var certificate_number = response.certificate_number;
                            var facility_state = '<?php echo $health_facility_state; ?>';
                            var facility_country = '<?php echo $health_facility_country; ?>';
                            var facility_address = response.facility_address;
                            var facility_name = '<?php echo $health_facility_name; ?>';
                            var date = response.date;
                            var hospital_number = response.hospital_number;
                            var address = response.address;
                            var time_of_death = response.time_of_death;
                            var sex = response.sex;
                            var referring_facility = response.referring_facility;
                            var referring_facility_id = response.referring_facility_id;
                            var record_id = response.record_id;
                            var mortuary_record_id = response.mortuary_record_id;
                            var autopsy_done_str = response.autopsy_done_str;
                            var cause_of_death = response.cause_of_death;
                            var age = response.age;
                            var age_unit = response.age_unit;
                            var date_received = response.date_received;
                            var pathologist_name = response.pathologist_name;
                            var pathologist_qualification = response.pathologist_qualification;
                            var certificate_file = response.certificate_file;
                            var signature_url = response.signature_url;
                            
                            var pdf_data = {
                              'logo' : company_logo,
                              'color' : <?php echo $color; ?>,
                              
                              "patient_name" : patient_name,
                              "certificate_number" : certificate_number,
                              "facility_state" : facility_state,
                              'facility_id' : "<?php echo $health_facility_id; ?>",
                              "facility_country" : facility_country,
                              "facility_name" : facility_name,
                              "hospital_number" : hospital_number,
                              "facility_address" : facility_address,
                              "date" : date,
                              "certificate_file" : certificate_file,
                              'address' : address,
                              'time_of_death' : time_of_death,
                              'sex' : sex,
                              'referring_facility' : referring_facility,
                              'referring_facility_id' : referring_facility_id,
                              'mortuary_record_id' : mortuary_record_id,
                              'autopsy_done_str' : autopsy_done_str,
                              'cause_of_death' : cause_of_death,
                              'age' : age,
                              'age_unit' : age_unit,
                              'date_received' : date_received,
                              'pathologist_name' :pathologist_name,
                              'pathologist_qualification' : pathologist_qualification,
                              'signature_url' : signature_url,
                              'date' : date,
                              'certificate_file' : certificate_file
                            };
                            $(".spinner-overlay").show();
                            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_death_certificate') ?>";
                            $.ajax({
                              url : url,
                              type : "POST",
                              responseType : "json",
                              dataType : "json",
                              data : pdf_data,
                              
                              success : function (response) {
                                console.log(response)
                                $(".spinner-overlay").hide();
                                if(response.success == true){
                                  var pdf_url = "<?php echo base_url('assets/images/'); ?>" + certificate_file;
                                  window.location.assign(pdf_url);
                                }else{
                                  console.log('false')
                                }
                              },
                              error : function () {
                                $(".spinner-overlay").hide();
                                
                              }
                            })
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
                          message:"Sorry Something Went Wrong."
                          },{
                            type : "danger"  
                          });
                        }
                      });
                          
                           
                    }else{
                      swal({
                        title: 'Warning?',
                        text: "Finally, We Noticed That You Do Not Have A Signature Uploaded. Do You Want To Upload Now?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!',
                        cancelButtonText : 'No'
                      }).then((result) => {
                        $("#perform-action-modal").modal("hide");
                        $("#upload-signature-modal").modal("show");
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
                });  
              }else{
                swal({
                  title: 'Warning?',
                  text: "We Noticed That Your Name, Title And Qualification Have Not Been Entered. Do You Want To Enter Them Now?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes!',
                  cancelButtonText : 'No'
                }).then((result) => {
                  $("#perform-action-modal").modal("hide");
                  $("#enter-name-title-modal").modal("show");
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
          });   
          
        }else{
          swal({
            title: 'Warning?',
            text: "To Proceed, Cause Of Death Has To Be Entered. Do You Want To Enter It Now?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            cancelButtonText : 'No'
          }).then((result) => {
            $("#perform-action-modal").modal("hide");
            $("#enter-cause-of-death-modal").modal("show");
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
          $("#upload-signature-modal").modal("hide");
          $("#change-signature-modal").modal("hide");
          $.notify({
          message:"Your Signature Has Been Successfully Uploaded. You Can Now Request Death Certificate"
          },{
            type : "success"  
          });
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

  
  function goBackFromPerformLabFunctionsCard (elem,evt) {
    
    $("#carry-actions-card").hide();
    $("#main-card").show();
  }

  function displayResultAwaitingComment () {
    $("#carry-actions-card").hide();
    $("#results-awaiting-comments-card #select-patient-table").DataTable();
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
              <?php
               if(is_null($health_facility_logo)){
                echo $data_url_img; 
               }else{ 
                ?> 
              <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
              <?php } ?>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button style="margin-top: 50px;" class="btn btn-primary" onclick="performFunctions(this,event)">Perform Functions</button>

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

            

              <div class="card" id="all-registered-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackAllRegisteredBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Registered Bodies </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="input-autopsy-findings-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackInputAutopsyFindingsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Input Autopsy Findings</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array("id" => "input-autopsy-findings-form");
                    echo form_open("",$attr);
                  ?>
                    <h4 class="form-sub-heading">Organ Weights</h4>
                    <div class="wrap">
                      <div class="form-row">
                        
                        <div class="form-group col-sm-6">
                            <label for="brain" class="label-control"> Brain: </label>
                            <input type="text" class="form-control" id="brain" name="brain">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="heart" class="label-control"> Heart: </label>
                            <input type="text" class="form-control" id="heart" name="heart">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="rt_lung" class="label-control"> Rt. Lung: </label>
                            <input type="text" class="form-control" id="rt_lung" name="rt_lung">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lt_lung" class="label-control"> Lt. Lung: </label>
                            <input type="text" class="form-control" id="lt_lung" name="lt_lung">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="body_length" class="label-control"> Body Length: </label>
                            <input type="text" class="form-control" id="body_length" name="body_length">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="liver" class="label-control"> Liver: </label>
                            <input type="text" class="form-control" id="liver" name="liver">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="spleen" class="label-control"> Spleen: </label>
                            <input type="text" class="form-control" id="spleen" name="spleen">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lt_kidney" class="label-control"> Lt Kidney: </label>
                            <input type="text" class="form-control" id="lt_kidney" name="lt_kidney">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="rt_kidney" class="label-control"> Rt Kidney: </label>
                            <input type="text" class="form-control" id="rt_kidney" name="rt_kidney">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="others" class="label-control"> Others: </label>
                            <input type="text" class="form-control" id="others" name="others">
                            <span class="form-error"></span>
                        </div>
                      </div>  
                    </div>

                    <div class="form-row" style="margin-top: 50px;">
                      <div class="form-group col-sm-6">
                          <label for="pathological_anatomical_summary" class="label-control"> Pathological Anatomical Summary: </label>
                          <input type="text" class="form-control" id="pathological_anatomical_summary" name="pathological_anatomical_summary">
                          <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                          <label for="primary_cause_of_death" class="label-control"> Primary Cause Of Death: </label>
                          <input type="text" class="form-control" id="primary_cause_of_death" name="primary_cause_of_death">
                          <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                          <label for="secondary_cause_of_death" class="label-control"> Secondary Cause Of Death: </label>
                          <input type="text" class="form-control" id="secondary_cause_of_death" name="secondary_cause_of_death">
                          <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                          <label for="external_description" class="label-control"> External Description: </label>
                          <input type="text" class="form-control" id="external_description" name="external_description">
                          <span class="form-error"></span>
                      </div>
                    </div>  


                    <h4 class="form-sub-heading">Examination of Internal Organs</h4>
                    <div class="wrap">
                      <div class="form-row">
                        
                        <div class="form-group col-sm-6">
                            <label for="thoracic" class="label-control"> Thoracic, Abdominal and Pelvic Organs In-situ: </label>
                            <input type="text" class="form-control" id="thoracic" name="thoracic">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="tongue" class="label-control"> Tongue, Pharynx, Tonsils and Glands: </label>
                            <input type="text" class="form-control" id="tongue" name="tongue">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="thymus" class="label-control"> Thymus: </label>
                            <input type="text" class="form-control" id="thymus" name="thymus">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="thyroid" class="label-control"> Thyroid: </label>
                            <input type="text" class="form-control" id="thyroid" name="thyroid">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="trachea" class="label-control"> Trachea, Bronchi, Lungs and Pleura: </label>
                            <input type="text" class="form-control" id="trachea" name="trachea">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="heart1" class="label-control"> Heart, Pericardium and Great Vessels: </label>
                            <input type="text" class="form-control" id="heart1" name="heart1">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="oesophagus" class="label-control"> Oesophagus, Stomach and Intestines: </label>
                            <input type="text" class="form-control" id="oesophagus" name="oesophagus">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="liver1" class="label-control"> Liver and Gall Bladder: </label>
                            <input type="text" class="form-control" id="liver1" name="liver1">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="spleen1" class="label-control"> Spleen: </label>
                            <input type="text" class="form-control" id="spleen1" name="spleen1">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="pancreas" class="label-control"> Pancreas: </label>
                            <input type="text" class="form-control" id="pancreas" name="pancreas">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="adrenals" class="label-control"> Adrenals: </label>
                            <input type="text" class="form-control" id="adrenals" name="adrenals">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="kidneys" class="label-control"> Kidneys, Ureters and Bladder: </label>
                            <input type="text" class="form-control" id="kidneys" name="kidneys">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="uterus" class="label-control"> Uterus, Ovaries Fallopian tubes, Vagina or Prostate/Seminal Vesicles: </label>
                            <input type="text" class="form-control" id="uterus" name="uterus">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="cranial_cavity" class="label-control"> Cranial cavity/Brain: </label>
                            <input type="text" class="form-control" id="cranial_cavity" name="cranial_cavity">
                            <span class="form-error"></span>
                        </div>
                      </div>  
                    </div>

                    <div class="form-row" style="margin-top: 50px;">
                      <div class="form-group col-sm-6">
                          <label for="clinical_notes" class="label-control"> Clinical Notes: </label>
                          <input type="text" class="form-control" id="clinical_notes" name="clinical_notes">
                          <span class="form-error"></span>
                      </div>
                    </div>


                    <h4 class="form-sub-heading">Consent and Identification of body by</h4>
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="label-control"> Name: </label>
                            <input type="text" class="form-control" id="name" name="name">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="address" class="label-control"> Address: </label>
                            <input type="text" class="form-control" id="address" name="address">
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="relationship" class="label-control"> Relationship: </label>
                            <input type="text" class="form-control" id="relationship" name="relationship">
                            <span class="form-error"></span>
                        </div>
                      </div>
                    </div>

                    

                    <div class="form-row" style="margin-top: 50px;">
                      <div class="form-group col-sm-6">
                          <label for="other_doctors" class="label-control"> Other Doctors: </label>
                          <input type="text" class="form-control" id="other_doctors" name="other_doctors">
                          <span class="form-error"></span>
                      </div>
                    </div>

                    </div>
                    <input type="submit" class="btn btn-primary">
                  </form>



                  <div class="wrap" style="margin-top: 40px;">
                    <h3>Images</h3>
                    <input type="file" multiple="" name="image" class="inputFileVisible" accept="image/*" onchange="checkIfImageIsSelected(this,event)" style="opacity: 10; position: static;">
                    <button class="btn btn-info disabled" rel="tooltip" data-toggle="tooltip" title="Submit Images" onclick="submitImage(this,event)">Upload Images Selected</button>
                    <p class="text-primary" style="cursor: pointer;" onclick="viewPreviousImages(this,event)">View Previously Uploaded Images</p>
                  </div>
                </div>
              </div>


            </div>




          </div>

          <div class="modal fade" data-backdrop="static" id="change-signature-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Change Your Signature</h3>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                

                <div class="modal-body" id="modal-body">
                  <h4>Current Signature</h4>
  
                  <img src="" alt="" id="current-signature" style="width: 100px; height: 100px;">
                  <?php
                    
                    $attr = array('id' => 'upload-signature-form','onsubmit' => 'submitSignatureForm(this,event)');
                   echo form_open_multipart('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/upload_signature_image_later',$attr); 
                  ?>
                    <h5>Note: </h5>
                    <p>(1): Only Image File Formats Are Allowed (JPG,JPEG,PNG).</p>
                    <p>(2): Max Image Size is 200 KB.</p>
                    <p>(3): Recommended Dimensions (100px by 100px).</p>
                    
                    

                    <div class="">
                      <input class="" type="file" name="signature_file" id="signature_file" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
  
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>


          <div class="modal fade" data-backdrop="static" id="upload-signature-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Upload Your Signature</h4>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                

                <div class="modal-body" id="modal-body">
                  <?php
                    
                    $attr = array('id' => 'upload-signature-form','onsubmit' => 'submitSignatureForm(this,event)');
                   echo form_open_multipart('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/upload_signature_image_later',$attr); 
                  ?>
                    <h5>Note: </h5>
                    <p>(1): Only Image File Formats Are Allowed (JPG,JPEG,PNG).</p>
                    <p>(2): Max Image Size is 200 KB.</p>
                    <p>(3): Recommended Dimensions (100px by 100px).</p>
                    
                    

                    <div class="">
                      <input class="" type="file" name="signature_file" id="signature_file" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
  
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>
  

          <div class="modal fade" data-backdrop="static" id="enter-name-title-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Your Title, Full Name And Qualification</h4>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                

                <div class="modal-body" id="modal-body">
                  <?php  
                    $attr = array('id' => 'enter-name-title-form');
                    echo form_open("",$attr);
                  ?>
                  <div class="form-group">
                    <label for="title" class="label-control">Title: </label>
                    <input type="text" class="form-control" name="title" id="title">
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                    <label for="full_name" class="label-control">Full Name: </label>
                    <input type="text" class="form-control" name="full_name" id="full_name">
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                    <label for="qualification" class="label-control">Qualification: </label>
                    <input type="text" class="form-control" name="qualification" id="qualification">
                    <span class="form-error"></span>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>



  
          <div class="modal fade" data-backdrop="static" id="enter-cause-of-death-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Cause Of Death</h4>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                


                <div class="modal-body" id="modal-body">
                  <?php  
                    $attr = array('id' => 'enter-cause-of-death-form');
                    echo form_open("",$attr);
                  ?>
                  <div class="form-group">
                    <input type="text" class="form-control" name="primary_cause_of_death" id="primary_cause_of_death">
                    <span class="form-error"></span>
                  </div>
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="show-images-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Previously Selected Images</h4>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                


                <div class="modal-body" id="modal-body">
                  
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="perform-action-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Choose Action</h4>
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <p class="text-center"><em class="text-danger" id="autopsy_status"></em></p>


                <div class="modal-body" id="modal-body">
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
                        <td onclick="inputAutopsyFindings(this,event)">Input Autopsy Findings</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td onclick="deathCertificate(this,event)">Request Death Certificate</td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved </footer> -->
        </div>
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {

    

      $("#enter-name-title-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_personnel_name_title_qualification') ?>";
        var mortuary_record_id = $("#perform-action-modal").attr("data-id");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "mortuary_record_id",
          "value" : mortuary_record_id
        })
        console.log(form_data)
        $(".spinner-overlay").show();
        if(mortuary_record_id != ""){
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
                $("#enter-name-title-modal").modal("hide");
                $.notify({
                message:"Your Bio Data Has Been Successfully Entered. You Can Now Request Death Certificate"
                },{
                  type : "success"  
                });
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#enter-name-title-form #'+key);
                
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

      $("#enter-cause-of-death-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_cause_of_death') ?>";
        var mortuary_record_id = $("#perform-action-modal").attr("data-id");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "mortuary_record_id",
          "value" : mortuary_record_id
        })
        console.log(form_data)
        $(".spinner-overlay").show();
        if(mortuary_record_id != ""){
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
                $("#enter-cause-of-death-modal").modal("hide");
                $.notify({
                message:"Cause Of Death Successfully Entered. You Can Now Request Death Certificate"
                },{
                  type : "success"  
                });
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#enter-cause-of-death-form #'+key);
                
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

      $("#input-autopsy-findings-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_autopsy_report') ?>";
        var mortuary_record_id = $("#perform-action-modal").attr("data-id");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "mortuary_record_id",
          "value" : mortuary_record_id
        })
        console.log(form_data)
        $(".spinner-overlay").show();
        if(mortuary_record_id != ""){
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
                message:"Autopsy Records Inputed Successfully"
                },{
                  type : "success"  
                });
                $("#input-autopsy-findings-form .form-error").html("");
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#input-autopsy-findings-form #'+key);
                
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

    });

  </script>
