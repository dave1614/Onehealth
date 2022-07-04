  <style>
    #select-sub-admin-card{
      min-height: 350px;
    }
    #select-sub-admin-form{
      margin-top: 30px;
    }
  </style>

      <!-- End Navbar -->
      <script>
        function addUserAsSubAdmin(elem,evt,user_id) {
          evt.preventDefault();
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_sub_admin'); ?>",
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "add_sub_admin=true&user_id="+user_id,
            success : function(response){
              $(".spinner-overlay").hide();
              console.log(response);
              if(response == 1){
                document.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/admin'); ?>")
              }
            },
            error :function(){
              $(".spinner-overlay").hide();
              $.notify({
              message:"Something Went Wrong"
              },{
                type : "danger"  
              });
            }
          })
      
        }

        function goDefault() {
          $("#choose-action-card").show("slow");
          $("#add-new-sub-admin-card").hide("slow");
          $("#select-sub-admin-card").hide("slow");
          $(".go-back").remove();
        }

        function addNewSubAdmin(elem,evt) {
          swal({
            title: 'Choose Action',
            text: "Do You Want To: ",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#4caf50',
            confirmButtonText: 'Create New User',
            cancelButtonText: 'Select An Already Existing User'
          }).then(function(){
            $("#choose-action-card").hide("slow");
            $("#add-new-sub-admin-card").show("slow");
            $("#add-new-sub-admin-card").before("<button class='btn btn-warning go-back' onclick='goDefault()'>Go Back</button>");
          }, function(dismiss){
            if(dismiss == 'cancel'){
              $("#choose-action-card").hide("slow");
              $("#select-sub-admin-card").show("slow");
              $("#select-sub-admin-card").before("<button class='btn btn-warning' onclick='goBackFromSelectPersonnelCard(this,event)'>Go Back</button>");
            }
          });  
        }

        function goBackFromProperlySelectPersonnelDiv (elem,evt) {
          $("#select-sub-admin-form").show("slow");

          $("#select-sub-admin-card .properly-select-personnel").hide("slow")
        }

        function selectThisUserAsPersonnel (elem,evt) {
          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Proceed? ",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
          }).then(function(){
            $(".spinner-overlay").show();
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/select_already_existing_user_as_personnel') ?>"
            var user_name = $("#select-sub-admin-form").find('#user_name').val();
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : {
                user_name : user_name
              },
              success : function (response) {
                console.log(response)
                $(".spinner-overlay").hide();
                if(response.success == true){
                  swal({
                    title: 'Successful',
                    text: "<em class='text-primary'>" + user_name + "</em> Successfully Added As Sub Admin",
                    type: 'success',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                    
                  }).then(function(){
                    document.location.reload();
                  });
                }else if(response.wrong_username){
                  swal({
                    title: 'Error!',
                    text: "Sorry This Username Does Not Exist. Try Another.",
                    type: 'error',
                  })
                }else if(response.user_already_has_functionality){
                  swal({
                    title: 'Error!',
                    text: "This User Already Has This Functionality. Try Another.",
                    type: 'error',
                  })
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
                  message:"Sorry Something Went Wrong. Check Your Internet Connection"
                  },{
                    type : "danger"  
                  });
                $(".form-error").html();
              }
            }); 
          });
        }
      </script>
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
          }
        }
      ?>
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <?php
            $depts_arr = $this->onehealth_model->getDeptById($dept_id);
            if(is_array($depts_arr)){
                foreach($depts_arr as $dept){
                  $dept_name = $dept->name;
                  $dept_slug = $dept->slug;        
                } 
              $sub_dept_info = $this->onehealth_model->getSubDeptBySlug($third_addition);
              if(is_array($sub_dept_info)){
                foreach($sub_dept_info as $sub_dept){
                  $sub_dept_id = $sub_dept->id;
                  $sub_dept_name = $sub_dept->name;
                }
              }
            } 
          ?>
          <?php if($this->onehealth_model->checkIfUserIsAdminOfFacility1($health_facility_id,$user_id)){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-primary" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/admin') ?>">Home</a>&nbsp;&nbsp; > >  </span>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-primary" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a>&nbsp;&nbsp; > >  <?php echo $sub_dept_name; ?> </span>
          <?php  } ?>
          <h3 style="text-transform: capitalize;" class="text-center"><?php echo $sub_dept_name; ?></h3>
           <h3>Welcome <?php echo $user_name; ?></h3>
          <?php
            
          ?>
          
          
          <div class="row">
            <div class="col-sm-8 col-center">
              <div class="card" id="choose-action-card">
               <div class="card-header ">
                
                 <div class="card-title">
                   <h3 style="">Choose Your Action</h3>
                 </div>
               </div>
               <div class="card-body">
                
                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>
                  
                  <button class="btn btn-info btn-action" onclick="addNewSubAdmin(this,event)">Add New Sub Admin</button>
               </div>
             </div>

             <div class="card" style="display: none;" id="select-sub-admin-card">
               <div class="card-header">
                 <div class="card-title">
                   <h4 style="">Select Already Registered User</h4>
                 </div>
               </div>
               <div class="card-body">
                <?php
                  $attr = array('id' => 'select-sub-admin-form');
                  echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/verify_user_name_add_new_sub_admin',$attr);
                ?>
                
                  <div class="form-group">
                    <label for="user_name">Enter Username: </label>
                    <input type="text" id="user_name" name="user_name" class="form-control" required="">
                    <span class="form-error"></span>
                  </div>

                  <input type="submit" class="btn btn-info">

                </form>

                <div class="properly-select-personnel container-fluid" style="display: none;">
                  
                </div>
               </div>
             </div> 

             <div class="card" style="display: none;" id="add-new-sub-admin-card">
               <div class="card-header card-header-blue card-header-icon">
                 <div class="card-icon">
                   <i class="material-icons">contacts</i>
                 </div>
                 <div class="card-title">
                   <h4 style="">Add Admin Login Info</h4>
                 </div>
               </div>
               <div class="card-body">
                 <?php $attr = array('id' => 'add-new-sub-admin-form' ) ?>
                 <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/process_add_sub_admin',$attr); ?>
                 <div class="form-group bmd-form-group">
                    <label for="title" class="bmd-label-floating text-blue">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"  required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-5">
                      <label for="phone_code">Phone Code:  </label>
                      <select name="phone_code" id="phone_code" class="col-12">
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
                    <div class="form-group bmd-form-group col-7">
                      <label for="phone_number" class="">Mobile Number</label>
                      <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="e.g 08127027321" required>
                      <span class="form-error"></span>
                    </div>
                  </div>
                  <div class="form-group bmd-form-group">
                    <label for="title" class="bmd-label-floating text-blue">Title</label>
                    <input type="text" id="title" name="title" class="form-control"  required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group bmd-form-group">
                    <label for="full_name" class="bmd-label-floating text-blue">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"  required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group bmd-form-group">
                    <label for="qualification" class="bmd-label-floating text-blue">Qualification(s)</label>
                    <input type="text" id="qualification" name="qualification" class="form-control"  required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group bmd-form-group">
                    <label for="user_name" class="bmd-label-floating text-blue">User name</label>
                    <input type="text" id="user_name" name="user_name" class="form-control" required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group bmd-form-group" style="margin-top: 15px;">
                    <label for="password" class="bmd-label-floating text-blue">Password</label>
                    <input type="text" id="password" name="password" class="form-control" required>
                    <span class="form-error"></span>
                  </div>
                  <input type="submit" name="submit" class="btn btn-info btn-blue" style="margin-top: 30px;">
                  <input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>'>
                 <?php echo form_close(); ?>
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
      $("table").DataTable();


      $("#select-sub-admin-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var values = me.serializeArray();
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
            if(response.success == true && response.messages){
              var messages = response.messages;
              me.hide("slow");

              $("#select-sub-admin-card .properly-select-personnel").html(messages);
              $("#select-sub-admin-card .properly-select-personnel").show("slow")
            }else if(response.wrong_username){
              swal({
                title: 'Error!',
                text: "Sorry This Username Does Not Exist. Try Another.",
                type: 'error',
              })
            }else if(response.user_already_has_functionality){
              swal({
                title: 'Error!',
                text: "This User Already Has This Functionality. Try Another.",
                type: 'error',
              })
            }else{
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

      $("#add-new-sub-admin-form").submit(function (evt) {
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
              var user_name = response.user_name;
              var password = response.password;
              var email = response.email;
              var title = response.title;
              var full_name = response.full_name;
              var qualification = response.qualification;
              var mobile = response.mobile;
              var text_val = "<h4 style='text-transform: capitalize;'>You Have Successfully Added A Personnel As <?php echo $sub_dept_name ?> with the following details</h4>";
              text_val += "<div class='row'>";
              text_val += "<h5 class='col-sm-6'>User Name: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+user_name+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Password: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+password+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Mobile Number: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+mobile+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Email Address: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+email+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Title: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+title+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Full Name: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+full_name+"</em></h6>";

              text_val += "<h5 class='col-sm-6'>Qualifications: </h5>";
              text_val += "<h6 class='col-sm-6 text-primary'><em>"+qualification+"</em></h6>";


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

    });
  </script>
