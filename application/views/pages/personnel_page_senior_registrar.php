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
  ?>   
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
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_pathologist_comment'); ?>";

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
        var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/test_test/') ?>"+id;
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
    var get_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_images') ?>";

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
      var delete_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/delete_image') ?>";
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
            var get_test_images = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_images') ?>";

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

  function loadPatient (lab_id) {  
    $(".spinner-overlay").show();
    lab_id = String(lab_id);
    var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_tests_pathologist') ?>";
    $.ajax({
      url : get_patients_tests,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "get_patients_tests=true&lab_id="+lab_id,
      success : function (response) {  
        //Note Return Form Where Control Values Are Inputed
        var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_bio_data_display') ?>";
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

  function carryFunctions (elem) {
    $("#main-card").hide();
    $("#carry-actions-card").show();
  }


  function displayResultAwaitingComment () {
    $("#carry-actions-card").hide();
    $("#results-awaiting-comments-card").show();
  }

   function displayPrintedResults () {
    $("#carry-actions-card").hide();
    $("#display-printed-results-card").show();
  }
  
  function displayYetToPrintResults() {
    $("#carry-actions-card").hide();
    $("#display-yet-printed-results-card").show();
  }

  function addComment () {
    $("#select-options-table-2").hide();
    $(".pathologists-comment-div").show(); 
  }

  function zipResults(lab_id) {
    $(".spinner-overlay").show();
    var lab_id = String(lab_id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_if_pathologist_has_added_comment') ?>";
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
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/zip_result') ?>";
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
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_if_pathologist_has_added_comment') ?>";
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
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/unzip_result') ?>";
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

  function allResults() {
    $("#carry-actions-card").hide();
    $("#all-results-card").show();
  }
</script>
      <!-- End Navbar -->
       <?php
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
            <div class="col-sm-10">

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body" style="margin-top: 50px;">
                  <button class="btn btn-primary btn-action" onclick="carryFunctions(this)">Carry Out Functions</button>
                 
                </div>
              </div>

              <div class="card" id="carry-actions-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">Select Action: </h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>                
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
                          <?php if($sub_dept_id != 6){ ?>
                          <td>Result Awaiting Pathologists Comment</td>
                          <?php }else{ ?>
                            <td>Result Awaiting Radiologists Comment</td>
                          <?php } ?>
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
  

              <div class="card" id="results-awaiting-comments-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Results Awaiting Your Comments</h3>
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
                        echo "<p>Click Patient Name To View Personal Information And All Test Results</p>";
                    ?>
                    
                    <div class="table-responsive">
                    <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Lab Id</th>
                          <th>Patient Name</th>
                          <th>Last Data Entry Date</th>
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
                            $date_diff = date_diff($curr_date,$date_of_verification2);
                            $date_diff = $date_diff->format("%d");
                            // if($date_diff <= 1)  {

                        ?>
                        <tr onclick="return loadPatient('<?php echo $lab_id; ?>')">
                          <td><?php echo $i; ?></td>
                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $first_name . " " . $last_name; ?></td>
                          <td><?php echo $date_of_verification; ?></td>
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

              <div class="card" id="display-printed-results-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Printed Results</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                </div>
                <div class="card-body">
                   <?php  
                      $health_facility_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
                          $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                          $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                          $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $form_array = array(
                        'printed' => 1,
                        'verified' => 1
                      );
                      $all_patients = $this->onehealth_model->getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id);
                      if(is_array($all_patients)){
                        $all_patients = array_reverse($all_patients);
                        echo "<p>Click Patient Name To View Personal Information And All Test Results</p>";
                    ?>
                      
                    <div class="table-responsive">
                    <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Lab Id</th>
                          <th>Patient Name</th>
                          <th>Last Data Entry Date</th>
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
                            $date_diff = date_diff($curr_date,$date_of_verification2);
                            $date_diff = $date_diff->format("%d");
                            
                            if($date_diff <= 1)  {

                        ?>
                        <tr onclick="return loadPatient('<?php echo $lab_id; ?>')">
                          <td><?php echo $i; ?></td>
                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $first_name . " " . $last_name; ?></td>
                          <td><?php echo $date_of_verification; ?></td>
                        </tr>
                      <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                  <?php }else{ ?>
                    <span class="text-warning">No Verified Test Result To Display.</span>
                  <?php } ?>
                </div> 
              </div> 
              

              <div class="card" id="display-yet-printed-results-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Yet To Be Printed Results</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                </div>
                <div class="card-body">
                   <?php  
                      $health_facility_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
                          $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                          $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                          $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $form_array = array(
                        'printed' => 0,
                        'verified' => 1
                      );
                      $all_patients = $this->onehealth_model->getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id);
                      if(is_array($all_patients)){
                        $all_patients = array_reverse($all_patients);
                        echo "<p>Click Patient Name To View Personal Information And All Test Results</p>";
                    ?>
                    
                    <div class="table-responsive">
                    <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Lab Id</th>
                          <th>Patient Name</th>
                          <th>Last Data Entry Date</th>
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
                            $date_diff = date_diff($curr_date,$date_of_verification2);
                            $date_diff = $date_diff->format("%d");
                            
                            if($date_diff <= 1)  {

                        ?>
                        <tr onclick="return loadPatient('<?php echo $lab_id; ?>')">
                          <td><?php echo $i; ?></td>
                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $first_name . " " . $last_name; ?></td>
                          <td><?php echo $date_of_verification; ?></td>
                        </tr>
                      <?php } } ?>
                      </tbody>
                    </table>
                  </div>
                  <?php }else{ ?>
                    <span class="text-warning">No Verified Test Result To Display.</span>
                  <?php } ?>
                </div> 
              </div>

              <div class="card" id="all-results-card" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">All Results (Verified/Unverified)</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                </div>
                <div class="card-body">
                   <?php  
                      $health_facility_test_result_table = $this->onehealth_model->createTestResultMainTableHeaderString($health_facility_id,$health_facility_name);
                          $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                          $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                          $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $form_array = array(
                        'result_entered' => 1
                      );
                      $all_patients = $this->onehealth_model->getPatientsTests($health_facility_patient_db_table,$form_array,$sub_dept_id);
                      if(is_array($all_patients)){
                        $all_patients = array_reverse($all_patients);
                        echo "<p>Click Patient Name To View Personal Information And All Test Results</p>";
                    ?>
                    
                    <div class="table-responsive">
                      <table id="select-patient-table" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                              <th>#</th>
                              <!-- <th>Verify</th> -->
                              <th>Lab Id</th>
                              <th>Patient Name</th>
                              <th>Last Data Entry Date</th>
                              <th>Verification Status</th>
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
                            $curr_date = date("j M Y");
                            // echo $curr_date;
                            $date_diff = $curr_date - $verification_date;
                            
                            // if($date_diff <= 1)  {
                          ?>
                          <tr onclick="return loadPatient('<?php echo $lab_id; ?>')">
                            <td><?php echo $i; ?></td>
                            <!-- <td><input type="checkbox" name="patient_checkboxes" id="<?php echo $id; ?>" <?php if($verified == 1){ echo "checked"; } ?>></td> -->
                            <td><?php echo $lab_id ?></td>
                            <td><?php echo $first_name . ' ' . $last_name; ?></td>
                            <td><?php echo $verification_date; ?></td>
                            <td><?php if($verified == 1){ echo "Verified"; } else{ echo "Awaiting Verification"; } ?></td>
                          </tr>
                        <?php  } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php }else{ ?>
                    <span class="text-warning">No Verified Test Result To Display.</span>
                  <?php } ?>
                </div> 
              </div>

              <div class="card" id="view_patient_info" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title"></h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
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
  
  
</body>
<script>
    $(document).ready(function() {
      var table = $('.table').DataTable();
      $('.table').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
      } ); 

       
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
      
      $("#select-time-range").change(function (evt) {
        var time_range = $("#select-time-range").val();
        var range = time_range;
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/change_range_pathologist'); ?>"
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
    });



</script>
