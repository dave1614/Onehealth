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
        
        function payInFacility (elem,evt) {
          swal({
            title: 'Pay In Facility',
            html: "To Pay In Facility, Proceed To Facility Address: <a style='font-style: italic;' class='text-primary'><?php echo $health_facility_address; ?>.</a> Proceed To Any Hospital Teller And Complete Payment",
            type: 'success'
            
          })
             
        }

        function onlinePayment (elem,evt) {
          var url = "<?php echo site_url('onehealth/index/general_online_payment/outstanding_bills_payment/'.$second_addition); ?>";
           
          document.location.assign(url)    
        }

        function goBackFromOutstandingTestsCard(elem,evt){
          $("#main-card").show("fast");
          $("#outstanding-tests-card").hide("fast"); 
          $("#mark-tests-as-paid").hide("fast");
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
                    <h4>Process Payment For Your Oustanding Bill With Details: </h4>
                    <?php echo $bills_info; ?>
                  </div>
                </div>
                <div class="card-body">
                  <h5>Choose Payment Method: </h5>
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
      <?php if($this->session->error){ ?>
       $.notify({
        message:"Sorry Something Went Wrong"
        },{
          type : "error"  
        });
      <?php } ?>

      <?php if($this->session->internet_error){ ?>
       $.notify({
        message:"Sorry Something Went Wrong"
        },{
          type : "error"  
        });
      <?php } ?>
    });
  </script>
