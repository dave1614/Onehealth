<script>
  function insuranceModalClose(elem,evt){
    swal({
      title: 'Success',
      text: "You Have Been Successfully Registered In This Facility",
      type: 'success'             
    })
  }

  function registerPatient (elem) {
    elem = $(elem);
    var facility_id = elem.attr("data-facility-id");
    var form_data = {
      health_facility_id : facility_id
    };
    var url = "<?php echo site_url('onehealth/register_patient') ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function(response){
        $(".spinner-overlay").hide();
       if(response.success){
        var tr = elem.parent().parent();
        var td = elem.parent();
        td.remove();
        tr.append('<td><em class="text-primary">Registered</em></td>')
        swal({
          title: 'Choose Action',
          text: "Do You Have An Insurance Code From This Facility?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#4caf50',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText : "No"
        }).then(function(){
          $("#enter-insurance-code-modal #enter-insurance-code-form").attr("data-facility-id",facility_id);
          $("#enter-insurance-code-modal").modal("show");
        }, function(dismiss){
          if(dismiss == 'cancel'){
            swal({
              title: 'Success',
              text: "You Have Been Successfully Registered In This Facility",
              type: 'success'             
            })
          }
        });

       }else if(response.incomplete_details){
        swal({
          title: 'Error!',
          text: "We Have Noticed That Your Patient Information Is Incomplete. Please Use The 'Edit Your Patient Information Tab' On The Sidebar To Enter Your Information",
          type: 'error'
        });
       }else if(response.already_registered){
        swal({
          title: 'Error!',
          text: "You're Already Registered In This Facility",
          type: 'error'
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

</script>

<div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">
        <span id="search-val-con" style="display: none;"><?php echo $search_val; ?></span>
        <div class="container-fluid">
          <h2 class="text-center">Search Results For '<?php echo urldecode($search_val); ?>': </h2>
          <div class="row">
            <div class="col-sm-10">
              <div class="card">

                <div class="card-header card-header-tabs card-header-warning">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <h4 class="nav-tabs-title"></h4>
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a href="#all" class="nav-link active show" id="all-link" data-toggle="tab">                            
                            <h5>All</h5>
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#health-facilities" id="health-facilities-link" class="nav-link" data-toggle="tab">
                            <i class="fas fa-hospital-alt" style="font-size: 20px;"></i>
                            Health Facilities
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#users" id="users-link" class="nav-link" data-toggle="tab">
                            <i class="fas fa-users" style="font-size: 20px;"></i>
                            Users
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#patients" id="patients-link" class="nav-link" data-toggle="tab">
                            <i class="fas fa-procedures" style="font-size: 20px;"></i>
                            Patients
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="all">
                      <?php

                         $first_facilities = $this->onehealth_model->getFirstHealthFacilities($search_val);
                            if(is_array($first_facilities)){
                              // print_r($first_facilities);
                              $i = 0;
                              // $show_more = false;
                      ?>
                      <h3 class="text-center">Health Facilities</h3>
                      <div class="table-responsive">
                      <table class="table table-test table-striped table-bordered nowrap hover display" id="first-facilities-table" cellspacing="0" width="100%" style="width:100%">
                        
                          <thead style="display: none;">
                            <tr>
                              <th>#</th>
                              <th>Logo</th>
                              <th>Facility Name</th>
                              <th>Location</th>
                              <th>Facility Structure</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                            foreach($first_facilities as $row){
                              $i++;
                               $hospital_id = $row->id;
                                $hospital_name = $row->name;
                                $hospital_logo = $row->logo;
                                $hopsital_email = $row->email;
                                $hospital_phone = $row->phone;
                                $hospital_country = $this->onehealth_model->getCountryById($row->country);
                                $hospital_state = $this->onehealth_model->getStateById($row->state);
                                $hospital_address = $row->address;
                                $hospital_slug = $row->slug;
                                $hospital_table_name = $row->table_name;
                                $facility_structure = $row->facility_structure;
                                $color = $row->color;
                                if($this->onehealth_model->checkIfUserIsRegisteredOnThisFacility($hospital_id,$user_id)){
                                  $registered = true;
                                }else{
                                  $registered = false;
                                }
                                if(is_null($hospital_logo)){
                                  $hospital_logo = "<img width='30' style='width:30px; height:30px;' class='round' avatar='".$hospital_name."' col='".$color."'>";
                                }else{
                                  $logo_url = base_url('assets/images/'.$hospital_logo);
                                  $hospital_logo = '<img src="'.$logo_url.'" style="width:30px; height:30px;" alt="" width="30" class="img-round">';
                                }
                          ?>
                            <tr>
                              <td><?php echo $i; ?>.</td>
                              <td><?php echo $hospital_logo; ?></td>
                              <td><a href="<?php echo site_url('onehealth/'.$hospital_slug) ?>" id="edit-test-card-link" class="text-primary list-group-item list-group-item-action"><?php echo $this->onehealth_model->custom_echo($hospital_name,35); ?></a></td>
                              <td><?php echo $hospital_state.', '.$hospital_country; ?></td>
                              <td class="text-capital1"><?php echo $facility_structure; ?></td>
                              <?php if(!$registered){ ?>
                              <td class="td-actions text-right">
                                <button class="btn btn-primary btn-link" rel="tooltip" data-facility-id="<?php echo $hospital_id;?>" onclick="return registerPatient(this)">
                                  <i class="fas fa-user-plus font-awesome"></i>
                                </button>
                              </td>
                              <?php }else{ ?>
                              <td>
                                <em class="text-primary">Registered</em>
                              </td>
                              <?php } ?>
                            </tr>
                          
                          <?php    
                            
                          }
                         
                          ?>
                        </tbody>
                       
                      </table>
                    </div>
                      <?php } ?>


                      <?php
                         $first_users = $this->onehealth_model->getFirstUsers($search_val);
                            if(is_array($first_users)){
                              $i = 0;
                              
                      ?>
                      <h3 class="text-center">Users</h3>
                      <div class="table-responsive">
                      <table class="table table-test table-striped table-bordered nowrap hover display" id="user-results-table" cellspacing="0" width="100%" style="width:100%">
                        <thead style="display: none;">
                          <tr>
                            <th>#</th>
                            <th>Profile Picture</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Position</th>
                            <th>Num Of Affiliated Facilities</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($first_users as $user){
                              $i++;
                              
                                $user_name = $user->user_name;
                                $email = $user->email;
                                $phone = $user->phone;
                               $hospital_country = $this->onehealth_model->getCountryById($user->country_id);
                                $hospital_state = $this->onehealth_model->getStateById($user->state_id);
                                $address = $user->address;
                                $user_slug = $user->slug;
                                $date = $user->date;
                                $time = $user->time;
                                $logo = $user->logo;
                                $is_patient = $user->is_patient;
                                $is_admin = $user->is_admin;
                                $affiliated_facilities = $user->affiliated_facilities;
                                $real_affiliated_facilities = $this->onehealth_model->custom_echo($this->onehealth_model->getRealAffiliatedFacilities($affiliated_facilities),100);
                                if($affiliated_facilities !== ""){
                                  $affiliated_facilities_num = explode(",", $affiliated_facilities);
                                  $affiliated_facilities_num = count($affiliated_facilities_num);
                                }
                                if(is_null($logo)){
                                  $logo = "avatar.jpg";
                                }
                          ?>
                            <tr>
                              <td><?php echo $i; ?>.</td>
                              <td><img src="<?php echo base_url('assets/images/'.$logo); ?>" alt="" height="30" style="width: 30px; height: 30px;" width="30" class='img-round'></td>
                              <td><a href="<?php echo site_url('onehealth/'.$user_slug) ?>" id="edit-test-card-link" class="text-primary list-group-item list-group-item-action"><?php echo $this->onehealth_model->custom_echo($user_name,35); ?></a></td>
                              <td><?php echo $hospital_state.', '.$hospital_country; ?></td>
                              <td class="text-capital1"><?php if($is_patient == 1){ echo "patient"; } elseif($is_admin == 1){ echo "admin"; }  ?></td>
                              <td class="text-capital1 pointer-cursor" <?php if($is_patient == 1 && $is_admin == 0 && $affiliated_facilities !== ""){ echo 'rel="tooltip" data-original-title="'.$real_affiliated_facilities.'"'; } ?> >
                                <?php if($is_patient == 1 && $affiliated_facilities !== ""){ echo $affiliated_facilities_num . " affiliated facilities" ; } ?>
                              </td>
                            </tr>
                          
                          <?php    
                            
                          }
                         ?>
                        </tbody>
                        
                       
                      </table>
                    </div>
                      <?php } ?>        
                    </div>
                    <div class="tab-pane" id="health-facilities">
                      
                    </div>

                    <div class="tab-pane" id="users">
                      
                    </div>

                    <div class="tab-pane" id="patients">
                      
                    </div>
                  </div>
                </div>
              </div>
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
              echo form_open("onehealth/submit_insurance_code_patient_registration",$attr);
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
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
    </div>
  </div>
  <script>
    $(document).ready(function () {
      // $("#enter-insurance-code-modal").modal("show");

      $("#enter-insurance-code-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var form_data = me.serializeArray();

        var facility_id = me.attr("data-facility-id");
        form_data = form_data.concat({
          "name" : "health_facility_id",
          "value" : facility_id
        })
        var url = me.attr("action");

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
                text: "You Have Been Successfully Registered In This Facility",
                type: 'success'             
              })
            }else if(response.not_registered){
              $.notify({
              message:"You Are Currently Not Registered With This Facility"
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

      var first_facilities_table = $("#first-facilities-table").DataTable();
      var user_result_table = $("#user-results-table").DataTable();
      var patients_results_table = $("#patients-results-table").DataTable();

      $("#all-link").click(function () {
        var url = "<?php echo site_url('onehealth/get_all_tab') ?>";
        var search_val = $("#search-val-con").html();
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_all_tab=true&search_val="+search_val,
          // data : {
          //   "get_health_facility_tab" : true,
          //   "search_val" : search_val,
          //   "offset" : 0
          // },
          success : function(response){
            $(".spinner-overlay").hide();
            if(response !== ""){
              $("#all").html(response);
              var first_facilities_table = $("#first-facilities-table").DataTable();
              var user_result_table = $("#user-results-table").DataTable();
              var patients_results_table = $("#patients-results-table").DataTable();
              LetterAvatar.transform();
            }else{
              var response = "<h3 class='text-warning'>Your Search Doesn't Match With Any Results</h3>";
              $("#all").html(response);
            }  
          },
          error : function () {
            $(".spinner-overlay").hide();
            var response = "<h3 class='text-warning'>Unable To Connect. Please Check Your Connection.</h3>";
            $("#all").html(response);
          }
        });  

      });

      $("#health-facilities-link").click(function () {
        var url = "<?php echo site_url('onehealth/get_health_facility_tab') ?>";
        var search_val = $("#search-val-con").html();
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_health_facility_tab=true&search_val="+search_val,
          // data : {
          //   "get_health_facility_tab" : true,
          //   "search_val" : search_val,
          //   "offset" : 0
          // },
          success : function(response){
            $(".spinner-overlay").hide();
            if(response !== ""){
              $("#health-facilities").html(response);
              $("#full-facilities-table").DataTable();
              LetterAvatar.transform();
            }else{
              var response = "<h3 class='text-warning'>Your Search Doesn't Match With Any Results</h3>";
              $("#health-facilities").html(response);
            }  
          },
          error : function () {
            $(".spinner-overlay").hide();
            var response = "<h3 class='text-warning'>Unable To Connect. Please Check Your Connection.</h3>";
            $("#health-facilities").html(response);
          }
        });  

      });

       $("#users-link").click(function () {
        var url = "<?php echo site_url('onehealth/get_users_tab') ?>";
        var search_val = $("#search-val-con").html();
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "get_users_tab=true&search_val="+search_val,
          // data : {
          //   "get_health_facility_tab" : true,
          //   "search_val" : search_val,
          //   "offset" : 0
          // },
          success : function(response){
            $(".spinner-overlay").hide();
            if(response !== ""){
              $("#users").html(response);
              $("#full-user-results-table").DataTable();
            }else{
              var response = "<h3 class='text-warning'>Your Search Doesn't Match With Any Results</h3>";
              $("#users").html(response);
            }  
          },
          error : function () {
            $(".spinner-overlay").hide();
            var response = "<h3 class='text-warning'>Unable To Connect. Please Check Your Connection.</h3>";
            $("#users").html(response);
          }
        }); 
      });   

        $("#patients-link").click(function () {
          var url = "<?php echo site_url('onehealth/get_patients_tab') ?>";
          var search_val = $("#search-val-con").html();
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get_patients_tab=true&search_val="+search_val,
            // data : {
            //   "get_health_facility_tab" : true,
            //   "search_val" : search_val,
            //   "offset" : 0
            // },
            success : function(response){
              $(".spinner-overlay").hide();
              if(response !== ""){
                $("#patients").html(response);
                $("#full-patients-results-table").DataTable();
              }else{
                var response = "<h3 class='text-warning'>Your Search Doesn't Match With Any Results</h3>";
                $("#patients").html(response);
              }  
            },
            error : function () {
              $(".spinner-overlay").hide();
              var response = "<h3 class='text-warning'>Unable To Connect. Please Check Your Connection.</h3>";
              $("#patients").html(response);
            }
          });  
         }); 


      });
  </script>