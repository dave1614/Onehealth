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
  function goDefault() {
    
    document.location.reload();
  }

  function performFunctions (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_registrations_mortician') ?>";
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

  function dailyMaintenance (elem,evt) {
    var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    swal({
      title: 'Choose Action',
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Verify Daily Maintenance',
      cancelButtonText: 'View Previously Verified Daily Maintenance'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/verify_daily_maintenance') ?>";
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
          console.log(response)
          if(response.success){
            $.notify({
            message:"Daily Maintenance Verified Successfully"
            },{
              type : "success"  
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
    }, function(dismiss){
      if(dismiss == "cancel"){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_daily_maintenance_mortuary') ?>";
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
            console.log(response)
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#daily-maintenance-card .card-body").html(messages);
              $("#daily-maintenance-table").DataTable();
              $("#all-registered-bodies-card").hide();
              $("#perform-action-modal").modal("hide");

              $("#daily-maintenance-card").show();
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
    });
  }

  function goBackDailyMaintenanceCard (elem,evt) {
   
    $("#all-registered-bodies-card").show();
    $("#perform-action-modal").modal("show");
    
    $("#daily-maintenance-card").hide();
  }

  function requestAutopsy (elem,evt) {
    var mortuary_record_id = $("#perform-action-modal").attr("data-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_autopsy_mortician') ?>";
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
        console.log(response)
        if(response.success){
          $.notify({
          message:"Request For Autopsy Has Been Sent To Histopathologist Successfully"
          },{
            type : "success"  
          });
        }else{
          $.notify({
          message:response.messages
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

  function dischargeBody (elem,evt) {
    swal({
      title: 'Choose Action',
      text: "Are You Sure You Want To Discharge Body? ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then(function(){
      var mortuary_record_id = $("#perform-action-modal").attr("data-id");
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/discharge_body') ?>";
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
          console.log(response)
          if(response.success){
            $.notify({
            message:"Body Discharged Successfully"
            },{
              type : "success"  
            });
            setTimeout(goDefault, 1000);
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
          message:"Sorry Something Went Wrong"
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


              <div class="card" id="all-registered-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackAllRegisteredBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Registered Bodies </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="daily-maintenance-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackDailyMaintenanceCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Daily Maintenance Records</h3>
                </div>
                <div class="card-body">

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
                        <td onclick="dailyMaintenance(this,event)">Daily Maintenance</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td onclick="requestAutopsy(this,event)">Request Autopsy</td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td onclick="dischargeBody(this,event)">Discharge Body For Burial</td>
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

    

    });

  </script>
