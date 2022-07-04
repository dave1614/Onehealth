    <?php
      if(is_array($curr_health_facility_arr)){
        foreach($curr_health_facility_arr as $row){
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
      <script>
        <?php if(!is_null($seventh_addition)){ ?>
        function payInFacility (elem,evt) {
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/check_initiation_code_paid_status') ?>";
           
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+"<?php echo $seventh_addition; ?>",
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.invalid_initiation){
                swal({
                  title: 'Error!',
                  html: "Initiation Code Is Invalid",
                  type: 'error'
                  
                })
              }else if(response.paid_for){
                swal({
                  title: 'OK!',
                  html: "All Tests Associated With This Initiation Code Have Been Paid For. No Need To Pay Again.",
                  type: 'success'
                })
              }else if(response.info != ""){
                var info = response.info;
                console.log(info.health_facility_address);
                console.log(info.sub_depts);
                console.log(info.initiation_code)
                swal({
                  title: 'Pay In Facility',
                  html: "To Pay In Facility, Proceed To Facility Address: <a style='font-style: italic;' class='text-primary'>" + info.health_facility_address + ".</a> Proceed To: <a class='text-primary' style='font-style: italic;'>" + info.sub_depts + " Department(s).</a> And Give Your Initiation Code: <a class='text-primary' style='font-style: italic;'>" + info.initiation_code + "</a> To The Teller.",
                  type: 'success'
                  
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
        }

        function onlinePayment (elem,evt) {
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/check_initiation_code_paid_status') ?>";
           
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+"<?php echo $seventh_addition; ?>",
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.invalid_initiation){
                swal({
                  title: 'Error!',
                  html: "Initiation Code Is Invalid",
                  type: 'error'
                  
                })
              }else if(response.paid_for){
                swal({
                  title: 'OK!',
                  html: "All Tests Associated With This Initiation Code Have Been Paid For. No Need To Pay Again.",
                  type: 'success'
                })
              }else if(response.tests != ""){
                var tests = response.tests;
                $("#outstanding-tests-card .card-body").html(tests);
                $(".table").DataTable();
                $("#main-card").hide("fast");
                $("#outstanding-tests-card").show("fast");
                $("#mark-tests-as-paid").show("fast");
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

        function goBackFromOutstandingTestsCard(elem,evt){
          $("#main-card").show("fast");
          $("#outstanding-tests-card").hide("fast"); 
          $("#mark-tests-as-paid").hide("fast");
        }
        <?php } ?>
      </script>
      <!-- End Navbar -->
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <h3>Welcome <?php echo $user_name; ?></h3>
          
          
          
          <div class="row">
            <div class="col-sm-12">
              <div class="card" id="main-card">
                <div class="card-header card-header-blue card-header-icon">
                  <!-- <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div> -->
                  <div class="card-title">
                    <h5>Process Payment For Initiation Code: <?php echo "<a class='text-primary' style='font-style: italic;'>" . $seventh_addition . "</a>" ; ?></h5>
                  </div>
                </div>
                <div class="card-body">
                  <p>Choose Payment Method: </p>
                  <button class="btn btn-primary" onclick="onlinePayment(this,event)">Online Payment</button>
                  <button class="btn btn-success" onclick="payInFacility(this,event)">Pay In Facility</button>
                </div>
              </div>

              <div class="card" id="outstanding-tests-card" style="display: none;">
                <div class="card-header card-header-blue card-header-icon">
                  <!-- <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div> -->
                  <button class="btn btn-warning" onclick="goBackFromOutstandingTestsCard(this,event)">Go Back</button>
                  <div class="card-title">
                    <h3>Outstanding Tests For Initiation Code: <?php echo "<a class='text-primary' style='font-style: italic;'>" . $seventh_addition . "</a>" ; ?></h3>
                  </div>
                </div>
                <div class="card-body">
                  
                </div>
              </div>
            </div>
          </div>
          <div rel="tooltip" data-toggle="tooltip" title="Mark All Tests As Paid" id="mark-tests-as-paid" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #9124a3; border-radius: 50%; cursor: pointer; fill: #fff; height: 56px; outline: none; display: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fas fa-check-circle" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
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
      <?php if(!is_null($seventh_addition)){ ?>
        
        $("#mark-tests-as-paid").click(function (evt) {
          console.log('clicked')
          swal({
              title: 'Proceed?',
              html: "Are You Sure You Want To Proceed With Payment?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText : "No"
          }).then(function(){
            var url = "<?php echo site_url('onehealth/index/') . $health_facility_slug . "/test_online_payment/clinic/" . '/' . $third_addition .'/'.$fourth_addition.'/'.$fivth_addition.'/'.$sixth_addition.'/'.$seventh_addition; ?>";
            window.location.assign(url);
          });
        });
      <?php } ?>
    });
  </script>
