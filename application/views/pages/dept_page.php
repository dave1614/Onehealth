
    <?php
      if(is_array($curr_health_facility_arr)){
        $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
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
          $health_facility_lab_structure = $row->lab_structure;
          $health_facility_clinic_structure = $row->clinic_structure;
          $health_facility_pharmacy_structure = $row->pharmacy_structure;
          $clinic_registration_fee = $row->clinic_registration_fee;
          $clinic_consultation_fee = $row->clinic_consultation_fee;
          $ward_ids = $row->ward_ids;
          $ward_admission_fee = $row->ward_admission_fee;
          $ward_admission_duration = $row->ward_admission_duration;
          $ward_admission_grace_days = $row->ward_admission_grace_days;
        }
      }
    ?>

    <style>
      tr {
        cursor: pointer;
      }
    </style>

    <script>
      var quill;
      

      function viewSections (elem,evt) {
        $("#main-card").hide();
        $("#dept-sections-card").show();
      }

      function goBackFromViewSectionsCard(elem,evt) {
        $("#main-card").show();
        $("#dept-sections-card").hide();
      }

      function viewSettingsCard(elem,evt){
        $("#main-card").hide();
        $("#settings-card").show();
        $("#submit-settings").show("fast");
      }

      function goBackFromSettingsCard(elem,evt){
        $("#main-card").show();
        $("#settings-card").hide();
        $("#submit-settings").hide("fast"); 
      }

      function viewEditDrugsCard(elem,evt){
        $("#main-card").hide();
        $("#manage-drugs-card").show();
      }

      function goBackFromEditDrugsStoreCard (elem,evt) {
        $("#manage-drugs-card").hide();
        $("#main-card").show();
      }

      function viewLabPersonnel(elem,evt){
        $("#main-card").hide();
        $("#lab-personnel-card").show();
      }


      function goBackFromLabPersonnelCard(elem,evt){
        $("#main-card").show();
        $("#lab-personnel-card").hide();
      }

      function changeCurrentLabStructure(elem,evt) {
        swal({
          title: 'Proceed?',
          text: "Note: When Changing Your Lab Structure Make Sure That All Tests Currently Being Worked With Have Been Cleared",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText : "No"
        }).then(function(){
          $("#change-lab-structure-modal").modal("show");
        });
      }

      function changeCurrentClinicStructure(elem,evt) {
        
        $("#change-clinic-structure-modal").modal("show");
        
      }

      function changeCurrentPharmacyStructure(elem,evt) {
        
        $("#change-pharmacy-structure-modal").modal("show");
        
      }

      function changeLabToLabDiscount(elem,evt) {
        
        $("#change-lab-to-lab-discount-modal").modal("show");
        
      }

      function changeLabReferralDrPercentage(elem,evt) {
        
        $("#change-lab-referral-percentage-modal").modal("show");
        
      }

      function editTestInformation(elem,evt){
        var url = '<?php echo site_url("onehealth/index/".$addition. "/" . $second_addition ."/view_about_tests") ?>';
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "show_records=true",
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#settings-card").hide();
              $("#tests-information-card .card-body").html(messages);
              $("#tests-information-card .table").DataTable();
              $("#tests-information-card").show();
              $("#add-new-test-information-btn").show("fast");
              $("#submit-settings").hide("fast");
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      }

      function goBackFromTestsInformationCard (elem,evt) {
        $("#settings-card").show();
              
        $("#tests-information-card").hide();
        $("#add-new-test-information-btn").hide("fast");
        $("#submit-settings").show("fast");
      }

      function addNewTestInformation (elem,evt) {

        var url = '<?php echo site_url("onehealth/index/".$addition. "/" . $second_addition ."/get_add_new_test_information_form") ?>';
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "show_records=true",
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#add-new-test-information-btn").hide("fast");
              $("#tests-information-card").hide();
              $("#add-new-test-information-card .card-body").html(messages);
              quill = new Quill('#add-new-test-information-form #editor', {
                  theme : 'snow'
              });
              $("#add-new-test-information-card").show();
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
        

      }

      function goBackFromAddNewTestInformationCard(elem,evt){
        $("#add-new-test-information-btn").show("fast");
        $("#tests-information-card").show();
        $("#add-new-test-information-card").hide();
      }

      function submitAddNewTestInformation (elem,evt) {
        elem = $(elem);
        evt.preventDefault();
        // console.log(quill)
        var me = elem;
        var test_id = me.find("#test_id").val();
        var about_test = JSON.stringify(quill.getContents());
        var url = me.attr("action");

        var form_data = {
          test_id : test_id,
          about_test : about_test
        }
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : form_data,
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Test Information Set Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else if(response.invalid_test_id){
              swal({
                  title: 'Error',
                  text: "No Test With This Id",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      }

      function editTestInformationEdit (elem,evt) {
        elem = $(elem);
        var test_id = elem.attr("data-testid");

        if(test_id != ""){

          var url = '<?php echo site_url("onehealth/index/".$addition. "/" . $second_addition ."/get_add_new_test_information_form_edit") ?>';
          
          $(".spinner-overlay").show();
          $.ajax({
            type : "POST",
            dataType : "json",
            responseType : "json",
            url : url,
            data : "test_id="+test_id,
            success : function (response) {
              console.log(response);
              $(".spinner-overlay").hide();
              if(response.success == true && response.messages != "" && response.about_test != ""){
                var messages = response.messages;
                $("#add-new-test-information-btn").hide("fast");
                $("#tests-information-card").hide();
                $("#add-new-test-information-card .card-body").html(messages);
                quill = new Quill('#add-new-test-information-form #editor', {
                    theme : 'snow'
                });
                quill.setContents(JSON.parse(response.about_test))
                $("#add-new-test-information-card").show();
              }else if(response.invalid_test_id){
                swal({
                    title: 'Error',
                    text: "No Test With This Id",
                    type: 'error'
                });
              }else{
                $.notify({
                  message:"Something Went Wrong"
                },{
                    type : "warning"  
                });
              }
            },error : function(){
              $(".spinner-overlay").hide();
              $.notify({
                message:"Something Went Wrong Check Your Internet Connection"
              },{
                  type : "danger"  
              });
            }
          }); 
        }
      }

      function changeClinicRegistrationFee(elem,evt) {
        
        $("#change-clinic-registration-fee-modal").modal("show");
        
      }

      function changeClinicConsultationFee(elem,evt) {
        
        $("#change-clinic-consultation-fee-modal").modal("show");
        
      }

      function changeWardsAdmissionFee(elem,evt) {
        
        $("#change-wards-admission-fee-modal").modal("show");
        
      }

      function selectWards (elem,evt) {
        var url = '<?php echo site_url("onehealth/index/".$addition. "/" . $second_addition ."/get_wards_selectable_form") ?>';
          
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "show_records=true",
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#select-wards-modal .modal-body").html(messages);
              $("#select-wards-modal").modal("show");
              $("#proceed-from-select-wards-btn").show();
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      }

      function goBackFromSelectWardsModal (elem,evt) {
        $("#proceed-from-select-wards-btn").hide();
      }

      function saveWardSelection (elem,evt) {
        var wards_selected_obj = [];

        $("#select-wards-modal input[type=checkbox]").each(function(index, el) {
          var id = $(this).attr("data-id");
          if($(this).prop("checked") == true){
            wards_selected_obj.push(id);
          }
        });

        
        var wards_str = wards_selected_obj.join(",");
        if(wards_str == ""){
          wards_str = "k";
        }
        swal({
          title: 'Warning?',
          text: "Are You Sure You Want To Proceed With Your Curent Ward Selection?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText : "No"
        }).then(function(){
          var url = '<?php echo site_url("onehealth/index/".$addition. "/" . $second_addition ."/submit_wards_selected") ?>';
          
          $(".spinner-overlay").show();
          $.ajax({
            type : "POST",
            dataType : "json",
            responseType : "json",
            url : url,
            data : "wards_str="+wards_str,
            success : function (response) {
              console.log(response);
              $(".spinner-overlay").hide();
              if(response.success){
                $.notify({
                  message:"Ward Selection Saved Successfully"
                },{
                    type : "success"  
                });

                setTimeout(function () {
                  document.location.reload();
                }, 1500);
              }else{
                $.notify({
                  message:"Something Went Wrong"
                },{
                    type : "warning"  
                });
              }
            },error : function(){
              $(".spinner-overlay").hide();
              $.notify({
                message:"Something Went Wrong Check Your Internet Connection"
              },{
                  type : "danger"  
              });
            }
          }); 
        });
      }
    </script>

      <!-- End Navbar -->
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <h2><?php echo $health_facility_name; ?></h2>
          <?php if($this->onehealth_model->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/admin') ?>">Home</a>&nbsp;&nbsp; > >  &nbsp;&nbsp;<?php echo $dept_name; ?></span>
        <?php } ?>
          <div class="row">
            <div class="col-sm-12">
              
              <div class="card" id="add-new-test-information-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromAddNewTestInformationCard(this,event)">Go Back</button>
                  <h3 class="card-title">Add New Test Information</h3>

                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="tests-information-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromTestsInformationCard(this,event)">Go Back</button>
                  <h3 class="card-title">Edit Tests Information</h3>

                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">                  
                  <h3 class="card-title">Choose Action</h3>
                </div>

                <div class="card-body" style="margin-top: 50px;">
                  <?php if($dept_id == 1){ ?>
                  <button class="btn btn-success" onclick="viewLabPersonnel(this,event)" >View Officers Here</button>
                  <?php } ?>
                  <button class="btn btn-primary" onclick="viewSections(this,event)" >View Sections</button>
                  <?php if($dept_id == 1 || $dept_id == 3 || $dept_id == 5 || $dept_id == 6){ ?>
                  <button class="btn btn-info" onclick="viewSettingsCard(this,event)">Edit Settings</button>
                  <?php }else{ ?>
                  <!-- <button class="btn btn-info" onclick="viewEditDrugsCard(this,event)">Manage Drugs Store</button>  -->
                  <?php } ?>
                </div>
              </div>
              
              <?php if($dept_id == 1 || $dept_id == 3 || $dept_id == 5 || $dept_id == 6){ ?>
              <div class="card" style="display: none;" id="settings-card">
                <div class="card-header">
                  <h3 class="card-title">Edit Settings</h3>
                  <!-- <h6>View Notifications From Users You Are following</h6> -->
                  <button class="btn btn-warning btn-round" onclick="goBackFromSettingsCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                    <table class="table table-test dt-responsive nowrap hover display settings-table" cellspacing="0" width="100%" style="width:100%">
                      <thead style="display: none;">
                        <tr>
                          <th>Setting</th>
                          <th>CheckBox</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($dept_id == 1){ ?>
                        <tr>
                          <td>Print Lab Results With Letter Heading</td>
                          <td>
                            <div class="form-check text-right">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="letter_heading" id="letter_heading" <?php if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($health_facility_id)){ echo "checked"; } ?> type="checkbox" value="">
                                    <!-- Option one is this and that&mdash;be sure to include why it's great -->
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                          </td>
                        </tr>
                        <?php } ?>

                        <?php if($dept_id == 1){ ?>
                          <tr onclick="changeCurrentLabStructure(this,event)">
                            <td>Lab Structure</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo $this->onehealth_model->getCurrentLabStructureForFacility($health_facility_id); ?></span>
                              </div>
                            </td>
                          </tr>

                          <tr onclick="changeLabToLabDiscount(this,event)">
                            <td>Lab To Lab Referral Discount</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo $this->onehealth_model->getCurrentLabToLabDiscountForFacility($health_facility_id); ?> %</span>
                              </div>
                            </td>
                          </tr>

                          <tr onclick="changeLabReferralDrPercentage(this,event)">
                            <td>Lab Doctor Commission Percentage</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo $this->onehealth_model->getHealthFacilityParamById("lab_referral_dr_percentage",$health_facility_id); ?> %</span>
                              </div>
                            </td>
                          </tr>
                          <tr onclick="editTestInformation(this,event)">
                            <td>Edit Test Information</td>
                            <td>
                             
                            </td>
                          </tr>
                        <?php } ?>

                        <?php if($dept_id == 3){ ?>
                          <tr onclick="changeCurrentClinicStructure(this,event)">
                            <td>Clinic Structure</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo $this->onehealth_model->getCurrentClinicStructureForFacility($health_facility_id); ?></span>
                              </div>
                            </td>
                          </tr>

                          <?php if($health_facility_clinic_structure == "standard"){ ?>
                          <tr onclick="changeClinicRegistrationFee(this,event)">
                            <td>Clinic Registration Fee</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo number_format($clinic_registration_fee,2); ?></span>
                              </div>
                            </td>
                          </tr>

                          <tr onclick="changeClinicConsultationFee(this,event)">
                            <td>Clinic Consultation Fee</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo number_format($clinic_consultation_fee,2); ?></span>
                              </div>
                            </td>
                          </tr>
                          <?php } ?>

                          <tr onclick="changeWardsAdmissionFee(this,event)">
                            <td>Change Wards Admission Info</td>
                            <td>
                              
                            </td>
                          </tr>

                          <tr onclick="selectWards(this,event)">
                            <td>Select Wards</td>
                            <td>
                              
                            </td>
                          </tr>
                        <?php } ?>

                        <?php if($dept_id == 6){ ?>
                          <tr onclick="changeCurrentPharmacyStructure(this,event)">
                            <td>Pharmacy Structure</td>
                            <td>
                              <div class="text-right">
                                <span style="text-transform: uppercase;" class="text-primary"><?php echo $this->onehealth_model->getCurrentPharmacyStructureForFacility($health_facility_id); ?></span>
                              </div>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>  
                </div>
              </div>
              <?php }else{ ?>
              <div class="card" style="display: none;" id="manage-drugs-card">
                <div class="card-header">
                  <h3 class="card-title">Manage Drugs Store </h3>
                  <!-- <h6>View Notifications From Users You Are following</h6> -->
                  <button class="btn btn-warning btn-round" onclick="goBackFromEditDrugsStoreCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>
              <?php } ?>
              
              <div class="card" style="display: none;" id="lab-personnel-card">
                <div class="card-header">
                  
                  <h4 class="card-title" style="text-transform: capitalize;">
                    <?php echo $dept_name; ?>'s Officers
                  </h4>
                  <button class="btn btn-warning btn-round" onclick="goBackFromLabPersonnelCard(this,event)">Go Back</button>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th class="text-center">No Of Personnel</th>

                          <th class="text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $officers_arr = $this->onehealth_model->getLabFirstOfficers();
                        if(is_array($officers_arr)){
                          $num = 0;
                          foreach($officers_arr as $officers){
                            $num++;
                            $lab_officer_id = $officers->id;
                            $lab_officer_name = $officers->name;
                            $lab_officer_slug = $officers->slug;
                            $lab_officer_num = $this->onehealth_model->getNumberOfLabFirstOfficersPersonnel($health_facility_id,$lab_officer_id);
                            if($lab_officer_num == 1){
                              $lab_officer_personnel_user_id = $this->onehealth_model->getLabFirstOfficersPersonnelUserid($health_facility_id,$lab_officer_id);
                              $lab_officer_personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$lab_officer_personnel_user_id);
                              $lab_officer_personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$lab_officer_personnel_user_id);
                            }
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$lab_officer_slug.'/lab_officer'); ?>"><?php echo $lab_officer_name; ?></a></td>
                          <?php if($lab_officer_num == 1){ ?>
                          <td  class="text-center"><a target="_blank" href="<?php echo site_url('onehealth/'.$lab_officer_personnel_user_slug) ?>"><?php echo $lab_officer_personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td class="text-center" style="text-transform: capitalize;"><a class="text-blue" ><?php echo $lab_officer_num; ?></a></td>
                          <?php } ?>
                          <?php if($lab_officer_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$lab_officer_slug.'/lab_officer/add_admin'); ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$lab_officer_slug.'/lab_officer/view_personnels'); ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$lab_officer_slug.'/lab_officer/add_admin'); ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php } ?> 
                        </tr>
                        <?php      
                            }
                          }
                        
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="card" style="display: none;" id="dept-sections-card" >
                <div class="card-header">
                  
                  <h4 class="card-title" style="text-transform: capitalize;">
                    <?php echo $dept_name; ?>'s sections
                  </h4>
                  <button class="btn btn-warning btn-round" onclick="goBackFromViewSectionsCard(this,event)">Go Back</button>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th class="text-center">No Of Sub-Admins</th>

                          <th class="text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $depts_arr = $this->onehealth_model->getDeptById($dept_id);
                        if(is_array($depts_arr)){
                            foreach($depts_arr as $dept){
                              $dept_name = $dept->name;
                              $dept_slug = $dept->slug;        
                            } 
                            $sub_depts = $this->onehealth_model->getSubDeptsByDeptId($dept_id); 
                            $num = 0;
                              if(is_array($sub_depts)){
                                foreach($sub_depts as $sub_dept){
                                  $sub_dept_id = $sub_dept->id;
                                  $sub_dept_name = $sub_dept->name;
                                  $sub_dept_slug = $sub_dept->slug;
                                  $sub_admins_num = $this->onehealth_model->getSubAdminsNum($health_facility_id,$sub_dept_id);
                                  if($sub_admins_num == 1){
                                    
                                    $sub_admin_user_id = $this->onehealth_model->getFirstSubAdminUserid($health_facility_id,$sub_dept_id);
                                    $sub_admin_user_name = $this->onehealth_model->getUserParamById("user_name",$sub_admin_user_id);
                                    $sub_admin_user_slug = $this->onehealth_model->getUserParamById("slug",$sub_admin_user_id);
                            
                                  }
                                  
                                  if($dept_id == 3 && $health_facility_clinic_structure == "mini"){
                                    if($sub_dept_id == 55){
                                      $num++;
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin'); ?>"><?php echo $sub_dept_name; ?></a></td>
                          <?php if($sub_admins_num == 1){ ?>
                          <td style="text-transform: capitalize;" class="text-center"><a target="_blank" href="<?php echo site_url('onehealth/'.$sub_admin_user_slug) ?>"><?php echo $sub_admin_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td class="text-center" style="text-transform: capitalize;"><a class="text-blue" ><?php echo $sub_admins_num; ?></a></td>
                          <?php } ?>
                          <?php if($sub_admins_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/sub_admins'); ?>" rel="tooltip" data-toggle="tooltip" title="View SubAdmins" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                             
                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                                    }else{ ?>
                
                                   <?php }
                                  }else if($dept_id == 5){

                                    $ward_ids_arr = explode(",", $ward_ids);
                                    if(in_array($sub_dept_id, $ward_ids_arr)){
                                      $num++;
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin'); ?>"><?php echo $sub_dept_name; ?></a></td>
                          <?php if($sub_admins_num == 1){ ?>
                          <td style="text-transform: capitalize;" class="text-center"><a target="_blank" href="<?php echo site_url('onehealth/'.$sub_admin_user_slug) ?>"><?php echo $sub_admin_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td class="text-center" style="text-transform: capitalize;"><a class="text-blue" ><?php echo $sub_admins_num; ?></a></td>
                          <?php } ?>
                          <?php if($sub_admins_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/sub_admins'); ?>" rel="tooltip" data-toggle="tooltip" title="View SubAdmins" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                             
                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                                    }
                                  } else{
                                    $num++;

                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin'); ?>"><?php echo $sub_dept_name; ?></a></td>
                          <?php if($sub_admins_num == 1){ ?>
                          <td style="text-transform: capitalize;" class="text-center"><a target="_blank" href="<?php echo site_url('onehealth/'.$sub_admin_user_slug) ?>"><?php echo $sub_admin_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td class="text-center" style="text-transform: capitalize;"><a class="text-blue" ><?php echo $sub_admins_num; ?></a></td>
                          <?php } ?>
                          <?php if($sub_admins_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/sub_admins'); ?>" rel="tooltip" data-toggle="tooltip" title="View SubAdmins" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Admin" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                             
                            </td>

                          <?php } ?> 
                        </tr>
                        <?php 
                              }     
                            }
                          }
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php if($dept_id == 1){ ?>
          <div rel="tooltip" data-toggle="tooltip" title="Save Settings" id="submit-settings" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; display: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fas fa-save" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
            </div>
          </div>
          <?php } ?>

          <div rel="tooltip" data-toggle="tooltip" title="Save Ward Selection" id="proceed-from-select-wards-btn" onclick="saveWardSelection(this,event)" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; display: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fas fa-save" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-pharmacy-structure-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Change Clinic Structure</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-pharmacy-structure-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_pharmacy_structure",$attr);
              ?>
                <div class="form-group">
                  <label for="pharmacy_structure">Select Pharmacy Structure: </label>
                  <select name="pharmacy_structure" id="pharmacy_structure" class="form-control selectpicker" data-style="btn btn-link" required style="margin-bottom: 35px;">
                    <option value="mini" <?php if($health_facility_pharmacy_structure == "mini"){ echo "selected"; } ?>>Mini</option>
                    <option value="standard" <?php if($health_facility_pharmacy_structure == "standard"){ echo "selected"; } ?>>Standard</option>
                    
                  </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-wards-admission-fee-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Change Wards Admission Info</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-wards-admission-fee-fee-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_wards_admission_fee",$attr);
              ?>
                <div class="form-group">
                  <label for="ward_admission_fee">Enter Wards Admission Fee: </label>
                  <input type="number" step="any" class="form-control" id="ward_admission_fee" name="ward_admission_fee" value="<?php echo $ward_admission_fee; ?>" required>
                </div>

                <div class="form-group">
                  <label for="ward_admission_duration">Enter Duration (days): </label>
                  <input type="number" class="form-control" id="ward_admission_duration" name="ward_admission_duration" value="<?php echo $ward_admission_duration; ?>" required>
                </div>

                <div class="form-group">
                  <label for="ward_admission_grace_days">Enter Grace Duration (days): </label>
                  <input type="number" class="form-control" id="ward_admission_grace_days" name="ward_admission_grace_days" value="<?php echo $ward_admission_grace_days; ?>" required>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="select-wards-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Select Wards</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="goBackFromSelectWardsModal(this,event)">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="goBackFromSelectWardsModal(this,event)">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-clinic-consultation-fee-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Change Clinic Consultation Fee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-clinic-consultation-fee-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_clinic_consultation_fee",$attr);
              ?>
                <div class="form-group">
                  <label for="clinic_consultation_fee">Enter Clinic Consultation Fee: </label>
                  <input type="number" step="any" class="form-control" id="clinic_consultation_fee" name="clinic_consultation_fee" value="<?php echo $clinic_consultation_fee; ?>">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-clinic-registration-fee-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Change Clinic Registration Fee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-clinic-registration-fee-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_clinic_registration_fee",$attr);
              ?>
                <div class="form-group">
                  <label for="clinic_registration_fee">Enter Clinic Registration Fee: </label>
                  <input type="number" step="any" class="form-control" id="clinic_registration_fee" name="clinic_registration_fee" value="<?php echo $clinic_registration_fee; ?>">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-lab-referral-percentage-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Change Lab Referral Percentage</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-lab-referral-percentage-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_lab_referral_percentage",$attr);
              ?>
                <div class="form-group">
                  <label for="lab_referral_dr_percentage">Enter Percentage Commission: </label>
                  <input type="number" max="100" class="form-control" id="lab_referral_dr_percentage" name="lab_referral_dr_percentage" value="<?php echo $this->onehealth_model->getHealthFacilityParamById("lab_referral_dr_percentage",$health_facility_id); ?>">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-lab-to-lab-discount-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center">Change Lab To Lab Referral Discount</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-lab-to-lab-discount-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_lab_to_lab_discount",$attr);
              ?>
                <div class="form-group">
                  <label for="lab_to_lab_discount">Enter Discount: </label>
                  <input type="number" max="100" class="form-control" id="lab_to_lab_discount" name="lab_to_lab_discount" value="<?php echo $this->onehealth_model->getCurrentLabToLabDiscountForFacility($health_facility_id); ?>">
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-clinic-structure-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Change Clinic Structure</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-clinic-structure-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_clinic_structure",$attr);
              ?>
                <div class="form-group">
                  <label for="clinic_structure">Select Clinic Structure: </label>
                  <select name="clinic_structure" id="clinic_structure" class="form-control selectpicker" data-style="btn btn-link" required style="margin-bottom: 35px;">
                    <option value="mini" <?php if($health_facility_clinic_structure == "mini"){ echo "selected"; } ?>>Mini</option>
                    <option value="standard" <?php if($health_facility_clinic_structure == "standard"){ echo "selected"; } ?>>Standard</option>
                    
                  </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="change-lab-structure-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Change Lab Structure</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php  
              $attr = array('id' => 'change-lab-structure-form');
              echo form_open("onehealth/index/".$addition. "/" . $second_addition ."/change_lab_structure",$attr);
              ?>
                <div class="form-group">
                  <label for="lab_structure">Select Lab Structure: </label>
                  <select name="lab_structure" id="lab_structure" class="form-control selectpicker" data-style="btn btn-link" required style="margin-bottom: 35px;">
                    <option value="mini" <?php if($health_facility_lab_structure == "mini"){ echo "selected"; } ?>>Mini</option>
                    <option value="standard" <?php if($health_facility_lab_structure == "standard"){ echo "selected"; } ?>>Standard</option>
                    <option value="maximum" <?php if($health_facility_lab_structure == "maximum"){ echo "selected"; } ?>>Maximum</option>
                  </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit" style="width: 100%;">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div id="add-new-test-information-btn" onclick="addNewTestInformation(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Information For Test" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="fas fa-plus-square" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
  </div>
  <!--   Core JS Files   -->
  

<script>
    $(document).ready(function() {

      $("#change-pharmacy-structure-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var pharmacy_structure = me.find("#pharmacy_structure").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "pharmacy_structure=" + pharmacy_structure,
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Pharmacy Structure Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-wards-admission-fee-fee-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : me.serializeArray(),
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Wards Admission Fee Changed Successfully"
              },{
                type : "success"  
              });
              
            }else if(response.not_a_number){
              swal({
                  title: 'Error',
                  text: "The Wards Admission Fee Field Must Be A Valid Number",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-clinic-consultation-fee-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_structure = me.find("#clinic_consultation_fee").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : me.serializeArray(),
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Clinic Consultation Fee Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else if(response.not_a_number){
              swal({
                  title: 'Error',
                  text: "The Clinic Consultation Fee Field Must Be A Valid Number",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-clinic-registration-fee-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_structure = me.find("#clinic_registration_fee").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : me.serializeArray(),
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Clinic Registration Fee Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else if(response.not_a_number){
              swal({
                  title: 'Error',
                  text: "The Clinic Registration Fee Field Must Be A Valid Number",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-clinic-structure-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var clinic_structure = me.find("#clinic_structure").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "clinic_structure=" + clinic_structure,
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:" Clinic Structure Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });
      
      $("#change-lab-referral-percentage-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_structure = me.find("#lab_referral_dr_percentage").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : me.serializeArray(),
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:" Lab Referral Percentage Commission Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else if(response.not_a_number){
              swal({
                  title: 'Error',
                  text: "The Percentage Commission Field Must Be A Valid Number",
                  type: 'error'
              });
            }else if(response.invalid_range){
              swal({
                  title: 'Error',
                  text: "Lab Referral Percentage Commission Must Be Betwen 0 - 100",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-lab-to-lab-discount-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_structure = me.find("#lab_to_lab_discount").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : me.serializeArray(),
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:"Lab To Lab Discount Percentage Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else if(response.not_a_number){
              swal({
                  title: 'Error',
                  text: "The Discount Field Must Be A Valid Number",
                  type: 'error'
              });
            }else if(response.invalid_range){
              swal({
                  title: 'Error',
                  text: "Lab To Lab Discount Percentage Must Be Betwen 0 - 100",
                  type: 'error'
              });
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $("#change-lab-structure-form").submit(function(evt) {
        evt.preventDefault();
        var me = $(this);
        var lab_structure = me.find("#lab_structure").val();
        var url = me.attr("action");
        
        $(".spinner-overlay").show();
        $.ajax({
          type : "POST",
          dataType : "json",
          responseType : "json",
          url : url,
          data : "lab_structure=" + lab_structure,
          success : function (response) {
            console.log(response);
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
                message:" Lab Structure Changed Successfully"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              },1500);
            }else{
              $.notify({
                message:"Something Went Wrong"
              },{
                  type : "warning"  
              });
            }
          },error : function(){
            $(".spinner-overlay").hide();
            $.notify({
              message:"Something Went Wrong Check Your Internet Connection"
            },{
                type : "danger"  
            });
          }
        }); 
      });

      $(".settings-table tbody tr").css({
        "cursor" : "pointer"
      });

      $(".settings-table tbody tr").click(function(){
        var checkBox = $(this).find('input');
        if(checkBox.is(':checked')){
          checkBox.prop('checked', false);
        }else{
         checkBox.prop('checked', true); 
        }
      });
      $("#submit-settings").click(function(event) {
        swal({
            title: 'Choose Action',
            html: "Are You Sure You Want To Proceed?",
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
        }).then(function(){
          var form_data = {};
          var checkedState = false;
          var name = '';

          $(".settings-table").each(function(index, el) {
            // console.log($(this).find('tbody tr').length)
            $(this).find('tbody tr input').each(function(index, el) {
              if($(this).is(':checked')){
                checkedState = true;
              }else{
                checkedState = false;
              }
              name = $(this).attr('name');
              form_data[name] = checkedState;
            });
          });
          
          console.log(form_data);
          var url = "<?php echo site_url('onehealth/index/' . $addition . '/' . $second_addition. '/save_settings') ?>";
          // console.log(form_data);
          $(".spinner-overlay").show();
          $.ajax({
            type : "POST",
            dataType : "json",
            responseType : "json",
            url : url,
            data : form_data,
            success : function (response) {
              console.log(response);
              $(".spinner-overlay").hide();
              if(response.success == true){
                $.notify({
                  message:"Settings Changed Successfully"
                },{
                    type : "success"  
                });
              }else{
                $.notify({
                  message:"Something Went Wrong"
                },{
                    type : "warning"  
                });
              }
            },error : function(){
              $(".spinner-overlay").hide();
              $.notify({
                message:"Something Went Wrong Check Your Internet Connection"
              },{
                  type : "danger"  
              });
            }
          }); 
        });
      });
      <?php
        // if($no_sub_admin == true){
      ?>
        // swal({
        //   title: 'Warning?',
        //   text: "You do not currently have any subadmins in this section. Dou Want To Add One?",
        //   type: 'warning',
        //   showCancelButton: true,
        //   confirmButtonColor: '#3085d6',
        //   cancelButtonColor: '#d33',
        //   confirmButtonText: 'Yes, add!'
        // }).then((result) => {
        //   // if (result.value) {
        //     console.log('dldl')
        //     window.location.assign("<?php echo site_url('onehealth/index'.$health_facility_slug.'/'.$dept_slug.'/add_sub_admin') ?>")
        //   // }
        // })
      <?php
        // }
      ?>
     
      <?php if($this->session->add_admin){ ?>
         $.notify({
          message:"Admin Added Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>
    });
  </script>
