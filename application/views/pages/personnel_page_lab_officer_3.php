<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>
<script>
  
  

  function searchByPatientName(elem){
    $("#lab_id_form").hide();
    $("#patient_name_form").show();
    $("#lab-id-btn").show();
    $("#patient-name-btn").hide();
  }

  function searchByLabId(elem){
    $("#lab_id_form").show();
    $("#patient_name_form").hide();
    $("#lab-id-btn").hide();
    $("#patient-name-btn").show();
  }

  function loadPatientInfo1 (lab_id) {
    $(".spinner-overlay").show();
    var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_tests') ?>";
    $.ajax({
      url : get_patients_tests,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "get_patients_tests=true&lab_id="+lab_id,
      success : function (response) {  
        var get_patients_tests = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_bio_data_display_officer_3') ?>";
        $.ajax({
          url : get_patients_tests,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "get_patient_bio_data=true&lab_id="+lab_id,
          success : function (response1) {
            
            $(".spinner-overlay").hide();
            $("#process-sample-card").hide();
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
            height = Number(height);
            var sample = response1.sample;
            var weight = response1.weight;
            var mobile = response1.mobile_no;
            var sample_replaced = response1.sample_replaced;
            var sample_replaced_date = response1.sample_replaced_date;
            var sample_replaced_time = response1.sample_replaced_time;
            var sample_rejected = response1.sample_rejected;
            var sample_rejected_date = response1.sample_rejected_date;
            var sample_rejected_time = response1.sample_rejected_time;
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
            var sampled = response1.sampled;
            $("#view_patient_info .welcome-heading").html(first_name + " " + last_name + "'s info")
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
            var bmi = calculateBmi (weight,height);
            $("#bmi").html(bmi + "kg/m&sup2;");
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
           
            $(".required-tests").append(response);
            $("#data-form").attr('data-lab-id',lab_id);
            $('#process-sample-modal').modal('hide');
            $("#main-card").hide();
            $("#view_patient_info").show();
            if(sample_rejected == 1){
              $("#observation-form-group").hide();
              $("#range #sample_rejected").prop("checked",true);
              addClass(document.querySelector(".form-check #sample_accepted").parentElement.parentElement,"hide");
              removeClass(document.querySelector(".form-check #sample_replaced").parentElement.parentElement,"hide");
            }
            if(sampled == 1){
              $("#data-form").hide();
              $("#sampling-time-display").show();
              $("#sampling-time").html(sampling_time);
              $("#seperation-time").html(separation_time);
              $("#input-data-button").html("View Seperation And Seperation Time");
              $("#input-data-button").attr("href","#sampling-time-display");
              $("#observation-text").html(observation);
            }
            
          },
          error : function () {
            $(".spinner-overlay").hide();
            
          }
        });
      }
    });
  }        

  function radioFiredOther(elem,event) {
    // event.preventDefault();
    $("#observation-form-group").hide();
  }

  function radioFiredAccept(elem,event) {
    // event.preventDefault();
    $("#observation-form-group").show();
  }

  function submitByPatientName (evt,elem) {
    evt.preventDefault();
    var patient_name = document.querySelector("#patient_name_input").value;
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
    var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_names'); ?>";
    patient_name = $.trim(patient_name);
    if(patient_name !== ""){
      if(format.test(patient_name)){
        $(".form-error").html("The Patient Name Field Cannot Contain Illegal Characters"); 
      }else{
        $(".spinner-overlay").show();
        
      }    
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
            <div class="col-sm-10">

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">

                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>
                  <button class="btn btn-primary btn-action" id="process-sample-btn">Process Sample</button>
                  
                </div>
              </div>

              <div class="card" id="process-sample-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Process Sample</h3>
                </div>
                <div class="card-body">
                  <?php
                  $health_facility_patient_db_table = $this->onehealth_model->createTestPatientTableHeaderString($health_facility_id,$health_facility_name);
                  $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                  $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $patient_info = $this->onehealth_model->getPatientInfo($health_facility_patient_db_table,$sub_dept_id);
                  if(is_array($patient_info)){
                  ?>
                  <div class="table-responsive">
                  <table id="example" class="table table-test table-striped table-bordered nowrap hover display" cellspacing="0" width="100%" style="width:100%">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Lab Id</th>
                        <th>Data Entered Date</th>
                      </tr>
                    </thead>
                    <tbody>
                  
                  <?php
                    $i = 0;
                    $patient_info = array_reverse($patient_info);
                    foreach($patient_info as $row){
                      $i++;
                      $first_name = $row->firstname;
                      $last_name = $row->surname;
                      $lab_id = $row->lab_id;
                      $age = $row->age;
                      $age_unit = $row->age_unit;
                      $date_created = $row->date_created;
                      $time_created = $row->time_created;
                  ?>
                    <tr onclick="loadPatientInfo1('<?php echo $lab_id; ?>')">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $first_name . " " .$last_name; ?></td>
                      <td><?php echo $age . " ". $age_unit; ?></td>
                      <td><?php echo $lab_id; ?></td>
                      <td><?php echo $date_created . " " .$time_created; ?></td>
                    </tr>
                  <?php    
                    }
                  ?>
                  </tbody>
                  </table>
                </div>
                    <?php
                  }else{
                    echo "<h4 class='text-warning'>No Outstanding Patients To Work On</h4>";
                  }

                  ?>
                </div>
              </div>

              <div class="card" id="view_patient_info" style="display: none;">
                
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title">David Nwogo's Info</h3>
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  <a href="#data-form" id="input-data-button" class="btn btn-info">Input Data</a>
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
                      <p class="text-capital">BMI: <span id="bmi"></span></p>
                      
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
                    <div class="required-tests table-responsive">
                      
                    </div>
                    <?php
                      $attr = array('id' => 'data-form');
                      echo form_open('',$attr);
                    ?>
                    <h3 class="text-center" style="margin-bottom: 30px;">Input Data</h3>
                    <div class="form-row">
                      <div class="form-group">
                        <p class="label">Sample: </p>
                        <div id="range">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="sample" id="sample_accepted" value="accepted" checked onclick="return radioFiredAccept(this,event)"> Accepted                                 
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">                                  
                              <input type="radio" class="form-check-input" name="sample" id="sample_rejected" value="rejected" onclick="radioFiredOther(this,event)">Rejected 
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline hide">
                            <label class="form-check-label">                                  
                              <input type="radio" class="form-check-input" name="sample" id="sample_replaced" value="replaced" onclick="radioFiredAccept(this,event)">Replaced
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                        </div> 
                        <span class="form-error"></span> 
                      </div>
                      <div class="form-group col-sm-12" id="observation-form-group">
                        <label for="observation">Observation Before Or After Separation: </label>
                        <textarea name="observation" id="observation" class="form-control" cols="30" rows="10"></textarea>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-submit">
                    </form>

                    <div id="sampling-time-display" class="text-center" style="display: none;">
                      <h3 class="text-bold">Details</h3>
                      <p class="text-capital">Sampling Time:  <span id="sampling-time"></span></p>
                      <p class="text-capital">Separation Time: <span id="seperation-time"></span></p>
                      <p class="text-capital">Observation: <span id="observation-text"></span></p>
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
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
       
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {
      $("#process-sample-btn").click(function(event) {
        $("#main-card").hide();
        $("#process-sample-card #example").DataTable();
        $("#process-sample-card").show();
      });
      $("#test-result-form").submit(function (evt) {
        evt.preventDefault();
          var health_facility_logo = $("#facility_img");
          var logo_src = health_facility_logo.attr("src");
          // console.log(logo_src)
          var logo_src_substr = logo_src.substring(0,4);
          if(logo_src_substr !== "data"){
            var img_data_url = getDataUrl(document.getElementById("facility_img"));
            var company_logo = {
             src:img_data_url,
              w: 80,
              h: 50
            };
          }else{
            var img_data_url = $("#facility_img").attr("src");
            var company_logo = {
             src:img_data_url,
              w: 80,
              h: 50
            };
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
                      var pdf =  btoa(generate_cutomPDFResult(company_logo,rows,facility_name,initiation_code,lab_id,patient_name,facility_state,facility_country,date,receptionist,teller,clerk,lab_three,lab_two,supervisor,pathologist,images));
                  
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_result') ?>";
                      // var pdf = btoa(doc.output());
                      $.ajax({
                        url : url,
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : {
                          data:pdf,
                          lab_id : lab_id
                        },
                        success : function (response) {
                          console.log(response)
                          if(response.success == true){
                            var pdf_url = "<?php echo base_url('assets/images/') ?>" + lab_id + '_result.pdf';
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
      
      $("#data-form").submit(function (evt) {
       evt.preventDefault();
        var me = $(this);
        $(".spinner-overlay").show();
        var lab_id = me.attr("data-lab-id");
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form/') ?>"+lab_id;
        var values = me.serializeArray();
        console.log(values)
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : values,
          success : function (response) {  
            
            $(".spinner-overlay").hide();
            if(response.success == true && response.successful == true){  
              if(response.accepted && response.sample_date !== "" && response.sample_time !== ""){    
                var sampling_time = response.sample_date + " " + response.sample_time;
                var observation = response.observation;
                $.notify({
                  message : "Records Successfully Added"
                },{
                  type : "success"  
                });
                $("#data-form").hide();
                $("#sampling-time-display").show();
                $("#sampling-time").html(sampling_time);
                $("#seperation-time").html(sampling_time);
                $("#input-data-button").html("View Seperation And Seperation Time");
                $("#input-data-button").attr("href","#sampling-time-display");
                $("#observation-text").html(observation);
              }else if(response.rejected){
                $.notify({
                  message : "Sample Rejected Successfully"
                },{
                  type : "success"  
                });
                document.location.reload();
              }else if(response.replaced  && response.sample_date !== "" && response.sample_time !== ""){    
                var sampling_time = response.sample_date + " " + response.sample_time;
                var observation = response.observation;
                $.notify({
                  message : "Records Successfully Added"
                },{
                  type : "success"  
                });
                $("#data-form").hide();
                $("#sampling-time-display").show();
                $("#sampling-time").html(sampling_time);
                $("#seperation-time").html(sampling_time);
                $("#input-data-button").html("View Seperation And Seperation Time");
                $("#input-data-button").attr("href","#sampling-time-display");
                $("#observation-text").html(observation);
               $(".form-error").html("");
              }
            }else if(response.success == true && response.successful == false){
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
              message:"Some Values Where Not Valid. Please Enter Valid Values"
              },{
                type : "warning"  
              });
            }
          },
          error : function () {
            
          } 
        });
      });     
     
       var currentValue = 0;
       

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
