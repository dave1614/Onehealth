         <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <h2>Welcome <?php echo $user_name; ?></h2>
          <div class="row">
            <div class="col-sm-8">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Welcome</h4>
                </div>
                <div class="card-body">
                  <?php
                   
                  ?>
                  <p>Welcome <?php echo $user_name; ?> To Your Administrative Panel. Please Use "Affiliated Facilities" To Access Other Features.</p>
                  
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
      
      <script>
        $(document).ready(function() {
          
        <?php
         if($this->session->password_changed){ 
          unset($_SESSION['password_changed'])
          ?>
          $.notify({
          message:"Password Changed Successfully"
          },{
            type : "success"  
          });
        <?php } ?>  
        <?php
         if($this->session->refundrequestapproved){ 
          unset($_SESSION['refundrequestapproved'])
          ?>
          $.notify({
          message:"Payment Has Been Refunded Successfully"
          },{
            type : "success"  
          });
        <?php } ?>  
        <?php
        if($this->session->refundrequestdeclined){ 
          unset($_SESSION['refundrequestdeclined'])
          ?>
          $.notify({
          message:"Refund Request Has Been Declined Successfully"
          },{
            type : "success"  
          });
        <?php } ?>  
        <?php
        if($this->session->account_created_success){ 
          unset($_SESSION['account_created_success'])
          ?>
          $.notify({
          message:"Your Account Has Been Successfully Created"
          },{
            type : "success"  
          });
        <?php } ?> 

        <?php
         if($this->session->paid_successfully){ 
          unset($_SESSION['paid_successfully'])
          ?>
          $.notify({
          message:"Payment Successful. A Notification Has Been Sent To You. You Would Find Your Receipt Attached"
          },{
            type : "success"  
          });
        <?php } ?>
      });
        
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 