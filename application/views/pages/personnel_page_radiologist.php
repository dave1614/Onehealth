<style>
  tr{
    cursor: pointer;
  }
  body {
  
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}
input[type=text] {
  /*background-color: #f1f1f1;*/
  /*width: 100%;*/
}
input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}

</style>
<script>
  var patient_main_test_id = "";
  var patient_lab_id = "";
  var view_mode = "enter";
  var quill = "";

  function processSample () {
    $("#main-card").hide();
    $("#process-sample-card").show();
  }

  // function submitResultForm(elem,evt) {
  //   evt.preventDefault();
  //   var me = $(this);
  //   $(".form-row").each(function () {
  //     if($(this).attr("data-main-test") == 1){
  //       $("")
  //     }

  //   })
  //   console.log(me.serializeArray());
  // }

  
  
  function rateValue(elem,range_higher,range_lower){
    elem = $(elem);
    var value = elem.val();
    // $("#enter-results-main-test-form .text-danger").html("")
    
    var child = elem.parent().find(".text-danger");
    if(value !== ""){
      if(value > range_higher){
        child.html("H");
      }else if(value < range_lower){
        child.html("L");
      }else{
         child.html("");
      }
    }else{
       child.html("");
    }   
  }

  function rateValue1(elem,desirable_value){
    elem = $(elem);
    var invalid_desirable = false;
    var value = elem.val();
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
      // $("#enter-results-main-test-form .text-danger").html("")
    
      var child = elem.parent().find(".text-danger");
      if(value !== ""){
        desirable_last_chars1 = Number(desirable_last_chars1)
        // console.log(desirable_last_chars1)
        if(desirable_first_char == ">"){
          if(value <= desirable_last_chars1){
            child.html("L");
          }else{
            child.html("");
          }
        }else{
          if(value >= desirable_last_chars1){
            child.html("H");
          }else{
            child.html("");
          }
        }

      }else{
         child.html("");
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

  function deleteImage (elem,evt,lab_id,main_test_id,image_name) {
    console.log(lab_id + " " + main_test_id + " " + image_name)
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
      var delete_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/delete_test_result_image_standard') ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : delete_test_images,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          lab_id : lab_id,
          main_test_id : main_test_id,
          image_name : image_name
        },
        success : function (response) {   
          $(".spinner-overlay").hide();
          if(response.success){
            $("#modal").modal("hide");
            viewImages (elem,evt,lab_id,main_test_id)
          }else{
            
            $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "warning" 
            });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger" 
          });
        } 
      });
    });   
  }

  function viewImages (elem,evt,lab_id,main_test_id) {
    elem = $(elem);
    var get_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_images_uploaded_for_test_radiographer') ?>";

    $(".spinner-overlay").show();
      $.ajax({
        url : get_test_images,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "lab_id="+lab_id+"&main_test_id="+main_test_id,
        
        success : function (response) {
        $(".spinner-overlay").hide();  
          if(response.success){
            
            $("#view-images-modal").modal("show"); 
            $("#view-images-modal .modal-body").html(response.messages); 
          }else if(response.no_images){
            $("#view-images-modal").modal("hide"); 
            $.notify({
              message:"No Images Have Been Uploaded Here Yet"
              },{
                type : "warning"  
            });
          }
        },
        error : function () {
           $(".spinner-overlay").hide();
          $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger" 
          });
        } 
      });     
  }

  function submitImage (elem,evt) {
    var form_data = new FormData();
    var lab_id = elem.getAttribute("data-lab-id");
    var main_test_id = elem.getAttribute("data-main-test-id");
    
    var image = elem.parentElement.previousElementSibling.querySelector("input");
    console.log(typeof image.files)
    if (typeof image.files !== 'undefined') {
      var img_count = image.files.length;
      console.log(img_count)
    }

    form_data.append('lab_id',lab_id);
    form_data.append('main_test_id',main_test_id);
    
    if (typeof image.files !== 'undefined') {
      console.log(image.files[0])
      form_data.append('image',image.files[0]);
      
    
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_images_for_test_radiology'); ?>"
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        cache: false,
        dataType : "json",
        contentType: false,
        processData: false,
        data : form_data,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success){
            $.notify({
              message:"Image Upload Successful"
              },{
                type : "success"  
            });
            image.value = "";
          }else if(response.no_image){
            swal({
              title: 'Ooops!',
              text: "You Did Not Select An Image. Select One And Proceed",
              type: 'error'         
            })
          }else if(response.multiple_images){
            swal({
              title: 'Ooops!',
              text: "You Can Only Select One Image",
              type: 'error'         
            })
          }else if(response.max_reached){
            swal({
              title: 'Ooops!',
              text: "You Have Reached Your Limit. You Can Only Upload 5 Images",
              type: 'error'         
            })
          }else if(response.ci_image_upload_err != ""){
            var text = "<em class='text-primary'>An Error Occured When Uploading Your new Image.</em>";
            text += response.ci_image_upload_err;
            swal({
              title: 'Ooops!',
              text: text,
              type: 'error'         
            })
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

  function openUploadResultsCard(){
    $("#main-card").hide();
    $("#upload-results-card").show();
  }

  function viewTestsAwaitingRadiologist (elem,evt,enter = true) {

    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_awaiting_radiologist'); ?>";
    var form_data = {};
    view_mode = "enter";

    if(!enter){
      form_data = {
        "edit" : true
      }
      view_mode = "edit";
    }
      
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
        if(response.success && response.messages != ""){
          $("#patients-awaiting-cardiologist-card .card-body").html(response.messages);
          $("#patients-awaiting-cardiologist-card #input-results-table").DataTable();
          $("#main-card").hide();
          
          $("#patients-awaiting-cardiologist-card").show();
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
          },{
            type : "warning"  
          });
        }

      },
      error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackFromPatientsAwaitingCardiologistCard (elem,evt) {
    $("#main-card").show();
    $("#patients-awaiting-cardiologist-card").hide();
  }

  function  loadTestsAndPatientInfo(elem,evt,lab_id,scroll_down = false,enter = true){
    console.log(lab_id)
    if(lab_id != ""){
      patient_lab_id = lab_id;
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patient_info_for_radiologist'); ?>"
      var form_data = {
        show_records : true,
        lab_id : lab_id
      }
      if(!enter){
        form_data = {
          show_records : true,
          lab_id : lab_id,
          edit : true
        }
      }

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
          if(response.success && response.messages != ""){
            $("#patient-info-card .card-body").html(response.messages);
            $("#patient-info-card #patient-tests-table").DataTable();
            $("#patients-awaiting-cardiologist-card").hide();
            $("#patient-info-card").show();
            if(scroll_down){
              window.scrollTo(0,document.body.scrollHeight);
            }
          }else if(response.no_data_to_be_worked_on){
            $("#enter-sub-test-result-card").hide();
            $("#enter-main-test-result-card").hide();
            viewTestsAwaitingRadiologist(elem,evt);
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function goBackFromPatientInfoCard (elem,evt) {
    viewTestsAwaitingRadiologist(elem,evt);
    $("#patient-info-card").hide(); 
  }

  function enterResultOfTest(elem,evt,main_test_id,lab_id,enter = true){
    elem = $(elem);
    // console.log(enter)
    // console.log(main_test_id)
    var test_name = elem.attr("data-test-name");
    if(main_test_id != "" && lab_id != ""){
      patient_lab_id = lab_id;
      patient_main_test_id = main_test_id;
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/load_edit_result_form_for_radiologist'); ?>";
      var form_data = {
        show_records : true,
        main_test_id : main_test_id,
        lab_id : lab_id
      }

      if(!enter){
        form_data = {
          show_records : true,
          main_test_id : main_test_id,
          lab_id : lab_id,
          edit : true
        }
      }

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
          if(response.success && response.messages != ""){
            
            $("#enter-main-test-result-card .card-body").html(response.messages);
            
            $("#enter-main-test-result-card .card-title").html("Review And Edit Comments For " + test_name);

            quill = new Quill('#enter-main-test-result-card .card-body #editor', {
              theme : 'snow'
            });

            if(response.last_comment_entered_for_this_test != ""){
              var last_comment_entered_for_this_test = response.last_comment_entered_for_this_test;
              quill.setContents(JSON.parse(last_comment_entered_for_this_test));
            }
            
            $("#enter-main-test-result-card #upload-images-table").DataTable();
            $("#patient-info-card").hide();
            
            $("#enter-main-test-result-card").show();
            window.scrollTo(0,0);
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  

  function submitRadiologistsComments(elem,evt){
    elem = $(elem);
    var lab_id = elem.attr("data-lab-id");
    var main_test_id = elem.attr("data-main-test-id");
    
    var comments_length = quill.getLength();
    if(comments_length >= 2){
      var comments = quill.getContents();
      comments = JSON.stringify(comments);

      
      if(lab_id != "" && main_test_id != "" && comments != ""){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_test_result_form_radiologist'); ?>";

        var form_data = {
          comments : comments,
          lab_id : lab_id,
          main_test_id : main_test_id
        }

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
              message:"Results Successfully Inputed And Marked As Completed And Can Be Printed At The Dispatch Officers Page"
              },{
                type : "success"  
              });
              
              
              $("#enter-main-test-result-card").hide();
              loadTestsAndPatientInfo(elem,evt,lab_id,true);

            }else if(response.test_not_requested_by_patient){
              $.notify({
              message:"This Test Was Not Requested By This Patient"
              },{
                type : "warning"  
              });
            }else if(response.test_does_not_exist){
              $.notify({
              message:"This Test Has Been Deleted From The Database Of This Facility"
              },{
                type : "warning"  
              });
            }else{
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "warning"  
              });
             
            }

          },
          error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger"  
            });
          }
        });
      } 
      

    }else{
      swal({
        title: 'Ooops!',
        text: "The Comment Field Is Required",
        type: 'error'         
      })
    }
  }

  function enterResultOfTest1(elem,evt,main_test_id,lab_id,enter = false){
    elem = $(elem);
    console.log(main_test_id)
    var test_name = elem.attr("data-test-name");
    if(main_test_id != "" && lab_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/load_edit_result_form_for_tests_awaiting_verification'); ?>"
        
      var form_data = {
        show_records : true,
        lab_id : lab_id,
        main_test_id : main_test_id
      };

      if(!enter){
        form_data = {
          show_records : true,
          main_test_id : main_test_id,
          lab_id : lab_id,
          edit : true
        }
      }

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
          if(response.success && response.messages != ""){

            $("#enter-sub-test-result-card .card-body").html(response.messages);
            if(!enter){
              $("#enter-sub-test-result-card .card-title").html("Edit Results And Comments For " + test_name);
            }else{
              $("#enter-sub-test-result-card .card-title").html("Edit And Enter Comments For " + test_name);
            }
            
            $("#enter-sub-test-result-card #upload-images-sub-tests-table").DataTable();
            $("#enter-comments-btn").attr("data-main-test-id",main_test_id);
            $("#enter-comments-btn").attr("data-lab-id",lab_id);
            if(!enter){
              $("#enter-comments-modal #enter-comments-form #comments").val(response.comments)
              $("#enter-comments-modal .modal-title").html("Edit Comments For This Test");
            }else{
              $("#enter-comments-modal #enter-comments-form #comments").val("");
              $("#enter-comments-modal .modal-title").html("Enter Comments For This Test");
            }
            $("#enter-comments-btn").show("fast");
            $("#patient-info-card").hide();
            $("#enter-sub-test-result-card").show();
            window.scrollTo(0,0);
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function enterComments (elem,evt) {
    elem = $(elem);
    var main_test_id = elem.attr("data-main-test-id")
    var lab_id = elem.attr("data-lab-id")

    if(main_test_id != "" && lab_id != ""){
      $("#enter-comments-modal #enter-comments-form").attr("data-main-test-id",main_test_id)
      $("#enter-comments-modal #enter-comments-form").attr("data-lab-id",lab_id)
      $("#enter-comments-modal").modal("show");
    }
  }

  function goBackFromEnterSubTestResultCard (elem,evt) {
    if(view_mode == "enter"){
      loadTestsAndPatientInfo(elem,evt,patient_lab_id,true);
    }else{
      loadTestsAndPatientInfo(elem,evt,patient_lab_id,true,false);
    }
    patient_main_test_id = "";
    $("#enter-comments-btn").hide("fast");
    $("#enter-sub-test-result-card").hide();
  }

  function goBackFromEnterMainTestResultCard (elem,evt) {
    $("#enter-main-test-result-card").hide();
    $("#enter-comments-btn").hide("fast");
    if(view_mode == "enter"){
      loadTestsAndPatientInfo(elem,evt,patient_lab_id,true)
    }else{
      loadTestsAndPatientInfo(elem,evt,patient_lab_id,true,false);
    }
    
    patient_main_test_id = "";
    
  }

  function goBackFromEditMainTestResultCard (elem,evt) {
    $("#edit-main-test-result-card").hide();
    loadTestsAndPatientInfoEdit(elem,evt,patient_lab_id,true)
   patient_main_test_id = "";
    
  }

  function submitEnterResultsMainTest (elem,evt) {
    elem = $(elem);
    evt.preventDefault();
    var lab_id = elem.attr("data-lab-id");
    var main_test_id = elem.attr("data-main-test-id");
    if(lab_id != "" && main_test_id != ""){
      var form_data = elem.serializeArray();
      form_data = form_data.concat({
        "name" : "lab_id",
        "value" : lab_id
      })


      form_data = form_data.concat({
        "name" : "main_test_id",
        "value" : main_test_id
      })

      console.log(form_data);

      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_test_result_form_main_test_only_standard'); ?>";

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
            message:"Results Successfully Inputed"
            },{
              type : "success"  
            });
            $("#enter-main-test-result-card").hide();
            loadTestsAndPatientInfo(elem,evt,lab_id,true);

          }else if(response.test_not_requested_by_patient){
            $.notify({
            message:"This Test Was Not Requested By This Patient"
            },{
              type : "warning"  
            });
          }else if(response.test_does_not_exist){
            $.notify({
            message:"This Test Has Been Deleted From The Database Of This Facility"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.messages, function (key,value) {

            var element = elem.find('#'+key);
            
            element.closest('div.form-group')
                    
                    .find('.form-error').remove();
            element.after(value);
            
           });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });

    }
  }

  function viewPreviousResults (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_ready_for_editing_of_results_mini'); ?>"
      
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
          $("#edit-results-card .card-body").html(response.messages);
          $("#edit-results-card #edit-results-table").DataTable();
          $("#main-card").hide();
          $("#edit-results-card").show();
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
          },{
            type : "warning"  
          });
        }

      },
      error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackFromEditResultsCard (elem,evt) {
    $("#main-card").show();
    $("#edit-results-card").hide();
  }

  function submitEnterResultsTestsWithSubTests (elem,evt) {
    elem = $(elem);
    evt.preventDefault();
    var lab_id = elem.attr("data-lab-id");
    var main_test_id = elem.attr("data-main-test-id");
    if(lab_id != "" && main_test_id != ""){
      var form_data = elem.serializeArray();
      form_data = form_data.concat({
        "name" : "lab_id",
        "value" : lab_id
      })


      form_data = form_data.concat({
        "name" : "main_test_id",
        "value" : main_test_id
      })

      console.log(form_data);

      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_test_result_form_sub_test_only_standard'); ?>";

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
            message:"Results Successfully Inputed"
            },{
              type : "success"  
            });
            $("#enter-main-test-result-card").hide();
            loadTestsAndPatientInfo(elem,evt,lab_id,true);

          }else if(response.test_not_requested_by_patient){
            $.notify({
            message:"This Test Was Not Requested By This Patient"
            },{
              type : "warning"  
            });
          }else if(response.test_does_not_exist){
            $.notify({
            message:"This Test Has Been Deleted From The Database Of This Facility"
            },{
              type : "warning"  
            });
          }else if(response.all_test_results_already_entered){

            $.notify({
            message:"All Test Results Here Have Been Entered Before"
            },{
              type : "warning"  
            });
          }else if(response.value_must_be_entered_in_one_field){
            elem.find(".form-error").html("");
            $.notify({
            message:"You Must Enter Result For At Least One Test To Proceed"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.messages, function (key,value) {

            var element = elem.find('#'+key);
            
            element.closest('div.form-group')
                    
                    .find('.form-error').remove();
            element.after(value);
            
           });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });

    }
  }

  function submitImageSubTest (elem,evt) {
    var form_data = new FormData();
    var lab_id = elem.getAttribute("data-lab-id");
    var main_test_id = elem.getAttribute("data-main-test-id");
    var sub_test_id = elem.getAttribute("data-sub-test-id");

    var image = elem.parentElement.previousElementSibling.querySelector("input");
    console.log(typeof image.files)
    if (typeof image.files !== 'undefined') {
      var img_count = image.files.length;
      console.log(img_count)
    }

    form_data.append('lab_id',lab_id);
    form_data.append('main_test_id',main_test_id);
    form_data.append('sub_test_id',sub_test_id);
    
    if (typeof image.files !== 'undefined') {
      console.log(image.files[0])
      form_data.append('image',image.files[0]);
      
    
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_images_for_sub_test_standard'); ?>"
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        cache: false,
        dataType : "json",
        contentType: false,
        processData: false,
        data : form_data,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success){
            $.notify({
              message:"Image Upload Successful"
              },{
                type : "success"  
            });
            image.value = "";
          }else if(response.no_image){
            swal({
              title: 'Ooops!',
              text: "You Did Not Select An Image. Select One And Proceed",
              type: 'error'         
            })
          }else if(response.multiple_images){
            swal({
              title: 'Ooops!',
              text: "You Can Only Select One Image",
              type: 'error'         
            })
          }else if(response.max_reached){
            swal({
              title: 'Ooops!',
              text: "You Have Reached Your Limit. You Can Only Upload 5 Images",
              type: 'error'         
            })
          }else if(response.ci_image_upload_err != ""){
            var text = "<em class='text-primary'>An Error Occured When Uploading Your new Image.</em>";
            text += response.ci_image_upload_err;
            swal({
              title: 'Ooops!',
              text: text,
              type: 'error'         
            })
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
  }

  function viewImagesSubTest (elem,evt,lab_id,main_test_id,sub_test_id) {
    elem = $(elem);
    var get_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_images_uploaded_for_sub_test_standard') ?>";

    $(".spinner-overlay").show();
      $.ajax({
        url : get_test_images,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "lab_id="+lab_id+"&main_test_id="+main_test_id+"&sub_test_id="+sub_test_id,
        
        success : function (response) {
        $(".spinner-overlay").hide();  
          if(response.success){
            
            $("#view-images-modal").modal("show"); 
            $("#view-images-modal .modal-body").html(response.messages); 
          }else if(response.no_images){
            $("#view-images-modal").modal("hide"); 
            $.notify({
              message:"No Images Have Been Uploaded Here Yet"
              },{
                type : "warning"  
            });
          }
        },
        error : function () {
           $(".spinner-overlay").hide();
          $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger" 
          });
        } 
      });     
  }

  function deleteImageSubTest (elem,evt,lab_id,main_test_id,sub_test_id,image_name) {
    console.log(lab_id + " " + main_test_id + " " + sub_test_id + " " + image_name)
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
      var delete_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/delete_test_result_image_sub_test_standard') ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : delete_test_images,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          lab_id : lab_id,
          main_test_id : main_test_id,
          sub_test_id : sub_test_id,
          image_name : image_name
        },
        success : function (response) {   
          $(".spinner-overlay").hide();
          if(response.success){
            $("#modal").modal("hide");
            viewImagesSubTest (elem,evt,lab_id,main_test_id,sub_test_id)
          }else{
            
            $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "warning" 
            });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger" 
          });
        } 
      });
    });   
  }


  function viewTestsAwaitingRadiologistEdit (elem,evt) {

    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_awaiting_radiologist_edit'); ?>";
    var form_data = {};
      
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
        if(response.success && response.messages != ""){
          $("#patients-awaiting-cardiologist-card-edit .card-body").html(response.messages);
          $("#patients-awaiting-cardiologist-card-edit #input-results-table").DataTable();
          $("#main-card").hide();
          
          $("#patients-awaiting-cardiologist-card-edit").show();
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
          },{
            type : "warning"  
          });
        }

      },
      error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackFromPatientsAwaitingCardiologistCardEdit (elem,evt) {
    $("#main-card").show();
    $("#patients-awaiting-cardiologist-card-edit").hide();
  }
  
  function loadTestsAndPatientInfoEdit(elem,evt,lab_id,scroll_down = false,enter = true){
    console.log(lab_id)
    if(lab_id != ""){
      patient_lab_id = lab_id;
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patient_info_for_radiologist_edit'); ?>"
      var form_data = {
        show_records : true,
        lab_id : lab_id
      }
      if(!enter){
        form_data = {
          show_records : true,
          lab_id : lab_id,
          edit : true
        }
      }

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
          if(response.success && response.messages != ""){
            $("#patient-info-card-edit .card-body").html(response.messages);
            $("#patient-info-card-edit #patient-tests-table").DataTable();
            $("#patients-awaiting-cardiologist-card-edit").hide();
            $("#patient-info-card-edit").show();
            if(scroll_down){
              window.scrollTo(0,document.body.scrollHeight);
            }
          }else if(response.no_data_to_be_worked_on){
            $("#enter-sub-test-result-card-edit").hide();
            $("#enter-main-test-result-card-edit").hide();
            viewTestsAwaitingRadiologistEdit(elem,evt);
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });
    }
  }


   function enterResultOfTestEdit(elem,evt,main_test_id,lab_id,enter = true){
    elem = $(elem);
    // console.log(enter)
    // console.log(main_test_id)
    var test_name = elem.attr("data-test-name");
    if(main_test_id != "" && lab_id != ""){
      patient_lab_id = lab_id;
      patient_main_test_id = main_test_id;
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/load_edit_result_form_for_radiologist_edit'); ?>";
      var form_data = {
        show_records : true,
        main_test_id : main_test_id,
        lab_id : lab_id
      }

      if(!enter){
        form_data = {
          show_records : true,
          main_test_id : main_test_id,
          lab_id : lab_id,
          edit : true
        }
      }

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
          if(response.success && response.messages != ""){
            
            $("#enter-main-test-result-card-edit .card-body").html(response.messages);
            
            $("#enter-main-test-result-card-edit .card-title").html("Edit Comments Entered For " + test_name);

            quill = new Quill('#enter-main-test-result-card-edit .card-body #editor', {
              theme : 'snow'
            });

            if(response.last_comment_entered_for_this_test != ""){
              var last_comment_entered_for_this_test = response.last_comment_entered_for_this_test;
              quill.setContents(JSON.parse(last_comment_entered_for_this_test));
            }
            
            $("#enter-main-test-result-card-edit #upload-images-table").DataTable();
            $("#patient-info-card-edit").hide();
            
            $("#enter-main-test-result-card-edit").show();
            window.scrollTo(0,0);
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function goBackFromEnterMainTestResultCardEdit (elem,evt) {
    $("#enter-main-test-result-card-edit").hide();
    loadTestsAndPatientInfoEdit(elem,evt,patient_lab_id,true)
    patient_main_test_id = "";
  }

  function submitRadiologistCommentsEdit(elem,evt){
    elem = $(elem);
    var lab_id = elem.attr("data-lab-id");
    var main_test_id = elem.attr("data-main-test-id");
    
    var comments_length = quill.getLength();
    if(comments_length >= 2){
      var comments = quill.getContents();
      comments = JSON.stringify(comments);

      
      if(lab_id != "" && main_test_id != "" && comments != ""){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/submit_test_result_form_radiologist'); ?>";

        var form_data = {
          comments : comments,
          lab_id : lab_id,
          main_test_id : main_test_id
        }

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
              message:"Results Successfully Inputed And Marked As Completed And Can Be Printed At The Dispatch Officers Page"
              },{
                type : "success"  
              });

            }else if(response.test_not_requested_by_patient){
              $.notify({
              message:"This Test Was Not Requested By This Patient"
              },{
                type : "warning"  
              });
            }else if(response.test_does_not_exist){
              $.notify({
              message:"This Test Has Been Deleted From The Database Of This Facility"
              },{
                type : "warning"  
              });
            }else{
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "warning"  
              });
             
            }

          },
          error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
            },{
              type : "danger"  
            });
          }
        });
      } 
      

    }else{
      swal({
        title: 'Ooops!',
        text: "The Comment Field Is Required",
        type: 'error'         
      })
    }
  }

  function goBackFromPatientInfoCardEdit(elem,evt) {
    viewTestsAwaitingRadiologistEdit(elem,evt);
    $("#patient-info-card-edit").hide(); 
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

              <div class="card" id="edit-sub-test-result-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromEditSubTestResultCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Edit Result Of</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

  
              <div class="card" id="enter-sub-test-result-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromEnterSubTestResultCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Enter Result Of</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="edit-results-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromEditResultsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Patient's Already Entered Results</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <!-- <h1>remember to ask for signature and qualifications if not available and build the page</h1> -->
              <div class="row justify-content-center">
                <div class="card col-sm-6" id="enter-main-test-result-card-edit" style="display: none;">
                  <div class="card-header">
                    <button onclick="goBackFromEnterMainTestResultCardEdit(this,event)" class="btn btn-warning">Go Back</button>
                    <h3 class="card-title">Enter Result Of</h3>
                  </div>
                  <div class="card-body">

                  </div>
                </div>

                <div class="card col-sm-6" id="enter-main-test-result-card" style="display: none;">
                  <div class="card-header">
                    <button onclick="goBackFromEnterMainTestResultCard(this,event)" class="btn btn-warning">Go Back</button>
                    <h3 class="card-title">Enter Result Of</h3>
                  </div>
                  <div class="card-body">

                  </div>
                </div>

                <div class="card col-sm-6" id="edit-main-test-result-card" style="display: none;">
                  <div class="card-header">
                    <button onclick="goBackFromEditMainTestResultCard(this,event)" class="btn btn-warning">Go Back</button>
                    <h3 class="card-title">Enter Result Of</h3>
                  </div>
                  <div class="card-body">

                  </div>
                </div>
              </div>

              <div class="card" id="patient-info-card-edit" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientInfoCardEdit(this,event)" class="btn btn-warning">Go Back</button>
                  <a href="#tests-table-enter" class="btn btn-info">Edit Results</a>
                  <h3 class="card-title">Patient Info</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="patient-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <a href="#tests-table-enter" class="btn btn-info">Perform Function</a>
                  <h3 class="card-title">Patient Info</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="patients-awaiting-radiographer-card-edit" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientsAwaitingRadiographerCardEdit(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Patient's Awaiting You</h3>
                </div>
                <div class="card-body">

                </div>
              </div>
              
              <div class="card" id="patients-awaiting-cardiologist-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientsAwaitingCardiologistCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Patient's Awaiting Radiologist</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="patients-awaiting-cardiologist-card-edit" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromPatientsAwaitingCardiologistCardEdit(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Previously Processed Patients</h3>
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
                          <td onclick="viewTestsAwaitingRadiologist(this,event)" class="text-primary">View Patients Awaiting Radiologist</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="viewTestsAwaitingRadiologistEdit(this,event,false)" class="text-primary">View Previously Processed Patients</td>
                        </tr>
                        
                        
                      </tbody>
                    </table>
                  </div>
                  
                </div>
              </div>

              <div class="card" id="upload-results-card" style="display: none;">
                
                <div class="card-header">
                  <h4 style="text-transform: capitalize;" class="welcome-heading card-title">Select File To Upload For Multiple Patient's Results</h4>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>                
                </div>
                <div class="card-body">
                  <?php
                    $attr = array('id' => 'upload-results-form');
                   echo form_open_multipart('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/upload_result_file',$attr); 
                  ?>
                    <h5>Note: </h5>
                    <p>(1): Only Json File Format Is Allowed.</p>
                    <p>(2): Max Size is 10 MB.</p>
                    <p>(3): Test Names In This File Must Match Test Names Saved By Admin.</p>
                    <p>(4): Any Unrecognized Test Or User Will Be Skipped!</p>

                    <div class="">
                      <input class="" type="file" name="json_file" id="json_file" accept=".json" required="required">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
  
                 </form>
                </div> 
              </div> 

            </div>
          </div>
          
        </div>
      </div>
      </div>

      <div id="enter-comments-btn" onclick="enterComments(this,event)" rel="tooltip" data-toggle="tooltip" title="Comment On This Test" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="choose-action-radiographer-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title">Choose Action: </h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body" style="">
              <h4>Redirect To? </h4>
              <div class="" style="margin-top: 40px;">
                <button class="btn btn-primary" onclick="proceedSubmitCommentsRadiographer(this,event)" data-type="cardiologist">Cardiologist</button>
                <button class="btn btn-success" onclick="proceedSubmitCommentsRadiographer(this,event)" data-type="radiologist">Radiologist</button>
                <button class="btn btn-info" onclick="proceedSubmitCommentsRadiographer(this,event)" data-type="none">None</button>
              </div>
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="enter-comments-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Enter Comments For This Test.</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
                $attr = array('id' => 'enter-comments-form');
                echo form_open("",$attr);
              ?>

                <div class="form-group">
                  <label for="comments" class="label-control"> Comments: </label>
                  <textarea name="comments" id="comments" cols="10" rows="10" class="form-control" required></textarea>
                </div>
                <input type="submit" class="btn btn-primary">
              </form>
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="view-images-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Images Uploaded For This Test.</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
  
  
</body>
<script>
    $(document).ready(function() {

      // $("#choose-action-radiographer-modal").modal("show");

      $("#enter-comments-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var main_test_id = me.attr("data-main-test-id");
        var lab_id = me.attr("data-lab-id");
        if(main_test_id != "" && main_test_id != ""){
          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Add This Comment?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Add!'
          }).then((result) => {
            
            var form_data = me.serializeArray();

            form_data = form_data.concat({
              "name" : "main_test_id",
              "value" : main_test_id
            })

            form_data = form_data.concat({
              "name" : "lab_id",
              "value" : lab_id
            })

            console.log(form_data)
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/enter_pathologists_comments'); ?>"
      
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
                if(response.success && response.messages != ""){
                  $.notify({
                  message:"Comments Entered Successfully"
                  },{
                    type : "success"  
                  });
                  $("#enter-sub-test-result-card").hide();
                  $("#enter-main-test-result-card").hide();
                  $("#enter-comments-modal").modal("hide");
                  $("#enter-comments-btn").hide("fast");
                  if(view_mode == "enter"){
                    loadTestsAndPatientInfo(me,evt,lab_id,true)
                  }else{
                    loadTestsAndPatientInfo(me,evt,lab_id,true,false)
                  }
                }else if(response.no_value_entered){
                  swal({
                    title: 'Error!',
                    text: "The Comments Field Is Required",
                    type: 'error'           
                  })
                }else{
                  $.notify({
                  message:"Sorry Something Went Wrong."
                  },{
                    type : "warning"  
                  });
                }

              },
              error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              }
            });
          });
        }
      })


      $("#upload-results-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var form_data = new FormData(this);
        
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
                message:"The File Uploaded Is Not Json. Please Reselect A Json File And Upload"
                },{
                  type : "warning"  
              });
            }else if(response.too_large){
              $.notify({
                message:"The File Upladed Is Too Large Max Is 10 MB"
                },{
                  type : "warning"  
              });
            }else if(response.not_really_json){
              $.notify({
                message:"This File Format Is Not Really Json"
                },{
                  type : "warning"  
              });
            }else if(response.success){
              $.notify({
                message:"Upload Of Results Successful, They Can Now Be Viewed From The Supervisor's Panel. Please Check Records To Confirm Upload. You Can Still Upload New Sets To Update Previously Entered Results"
                },{
                  type : "success" ,
                  timer: 10000 
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
      });


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
     $("#test-result-form").submit(function (evt) {
        evt.preventDefault();
          var health_facility_logo = $("#facility_img");
          var logo_src = health_facility_logo.attr("src");
          console.log(logo_src)
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
              console.log("kklal");
              var id = $(this).attr("id");
              form_data = form_data.concat(
                {"name": "id[]", "value": id}
              )
            }           
          });  
          form_data = form_data.concat({"name" :"lab_id", "value" : lab_id}) 
          console.log(form_data)
          
          $(".spinner-overlay").show();
          
          var submit_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patients_result') ?>";

          $.ajax({
            url : submit_patients_tests,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) { 
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){ 
                var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pdf_tests_result') ?>";
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
                        "facility_id" : "<?php echo $health_facility_id; ?>",
                        'facility_state' : facility_state,
                        'facility_country' : facility_country,
                        'facility_address' : "<?php echo $health_facility_address; ?>",
                        'date' :   date,
                        'receptionist' : receptionist,
                        'teller' : teller,
                        'clerk' : clerk,
                        'lab_three' : lab_three,
                        'lab_two' : lab_two,
                        'supervisor' : supervisor,
                        'pathologist' :  pathologist,
                        'images' : images,
                        'bio_data' : bio_data,
                        'comment' : comment
                      };
                      console.log(pdf_data)    
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_result') ?>";
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
                            console.log(pdf_url)
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
              
              $(".spinner-overlay").hide();
             
            }
          }); 

      });

    <?php if(!$this->onehealth_model->checkIfPersonnelInfoIsComplete()){ ?>
      swal({
        type: 'info',
        title: 'Warning',
        allowOutsideClick : false,
        allowEscapeKey :false,
        text: "We've Noticed That Your Personnel Details Are Not Complete. Please Click On The Button Below Or Use The Edit Personnel Details Tab On The Sidebar To Complete These Details"
        
      }).then((result) => {
        window.location.assign("<?php echo site_url('onehealth/edit_personnel_info'); ?>");
      });
    <?php } ?>

    });



</script>
