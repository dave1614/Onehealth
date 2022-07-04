         <!-- End Navbar -->
      <script>
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

          function withdrawFunds(elem,evt) {
            $("#withdraw-funds-modal").modal({
              "show" : true,
              "backdrop" : false,
              "keyboard" : false
            })
          }

          function reloadPage (elem) {
            document.location.reload(); 
          }

          function clearAllRecords(elem) {
            swal({
              title: 'Warning',
              text: "Are You Sure You Want To Delete All Your Facilities Payment Records?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText : "No"
            }).then(function(){
             
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/delete_all_payment_history'); ?>";
              $(".spinner-overlay").show();
              $.ajax({
                type : "POST",
                dataType : "json",
                responseType : "json",
                url : url,
                data : "delete_payment_history=true",
                success : function (response) {
                  $(".spinner-overlay").hide();
                  if(response.success){
                    reloadPage();
                  }
                },error : function () {
                  $(".spinner-overlay").hide();
                  $.notify({
                  message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                  },{
                    type : "danger"  
                  });

                } 
              }); 
            });
          }

          function deleteHistory(elem) {
            var tr = elem.parentElement.parentElement;
            var id = tr.getAttribute("data-id");
            console.log(id);
            swal({
              title: 'Warning',
              text: "Are You Sure You Want To Delete This Record?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText : "No"
            }).then(function(){
              var table = $("#payment-history-table").DataTable();
              
              // table.draw();
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/delete_payment_history'); ?>";
              $(".spinner-overlay").show();
              $.ajax({
                type : "POST",
                dataType : "json",
                responseType : "json",
                url : url,
                data : "delete_payment_history=true&id="+id,
                success : function (response) {
                  $(".spinner-overlay").hide();
                  if(response.success){
                    tr.style.display = 'none';
                  }
                },error : function () {
                  $(".spinner-overlay").hide();
                  $.notify({
                  message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                  },{
                    type : "danger"  
                  });

                } 
              }); 
            });
          }

          function viewPaymentHistory(elem){
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/view_payment_history'); ?>";
            $(".spinner-overlay").show();
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : "view_payment_history=true",
              success : function (response) {
                $(".spinner-overlay").hide();
                if(response.success && response.messages != ""){
                  $("#main-card").hide("fast");
                  $("#payment-history-card .card-body").html(response.messages);
                  $("#payment-history-card #payment-history-table").DataTable();
                  $("#payment-history-card").show('fast');
                }else{
                  swal({
                    title: 'Ooops',
                    text: "Sorry You Do Not Have Any Payment History",
                    type: 'error',
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              }
            });
            
            
          }

          function makeWithdrawal (elem,evt) {
            $("#main-card").hide();
            $("#make-withdrawal-card").show();
          }

      </script>
      <div class="content">
        <div class="container-fluid">
          <div class="spinner-overlay" style="display: none;">
            <div class="spinner-well">
              <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading..." style="">
            </div>
          </div>
          <h2>Finances</h2>
          <div class="row ">
            <div class="col-sm-10">


              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title">Choose Action: </h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" style="margin-top: 50px;" onclick="viewPaymentHistory(this)">View Payment History</button>

                  <button class="btn btn-info" style="margin-top: 50px;" onclick="makeWithdrawal(this)">Make Withdrawal</button>
                </div>
              </div>



              <div class="card" style="display: none;" id="payment-history-card">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="reloadPage()">Go Back</button>
                  <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                  
                  
                </div>
              </div>

              <div class="card" style="display: none;" id="make-withdrawal-card">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="reloadPage()">Go Back</button>
                  <h3 class="card-title">Make Withdrawal</h3>
                </div>
                <div class="card-body">
                  <div class="container">
                    <?php
                    $grand_total_facility_earnings = $this->onehealth_model->getFacilitiesGrandTotalEarnings($health_facility_id);
                    $grand_total_facility_withdrawn = $this->onehealth_model->getFacilityParamById("withdrawn",$health_facility_id);

                    $withdrawable_income = $grand_total_facility_earnings - $grand_total_facility_withdrawn;
                    ?>
                    <h4>Grand Total Amount Earned: <em class="text-primary"><?php echo number_format($grand_total_facility_earnings,2); ?> </em></h4>

                    <h4>Grand Total Withdrawn Amount: <em class="text-primary"><?php echo number_format($grand_total_facility_withdrawn,2); ?></em></h4>

                    <h4>Grand Total Withdrawable Inconme: <em class="text-primary"><?php echo number_format($withdrawable_income,2); ?></em></h4>

                    <button class="btn btn-primary" onclick="withdrawFunds(this,event)">Withdraw Funds</button>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
     
           <div class="modal fade" data-backdrop="static" id="withdraw-funds-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Enter Bank Details</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body" id="modal-body">
                  <em class="text-primary" id="modal-display">Verify Bank Details And Proceed</em>
                  <?php 
                    $attr = array('id' => 'enter-otp-form','style' => 'display:none;');
                    echo form_open("",$attr);
                  ?>
                    
                    <div class="form-group">
                      <input type="text" class="form-control" id="final_otp" name="final_otp" placeholder="Enter Otp" required>
                    </div>
                    <input type="submit" class="btn btn-success">
                  </form>

                  <?php 
                    $attr = array('id' => 'withdraw-funds-form','style' => 'display:none;');
                    echo form_open("",$attr);
                  ?>
                    <div class="form-group">
                      <input type="number" class="form-control" id="withdraw_funds" name="withdraw_funds" placeholder="Enter Amount" max="<?php ?>" min="100" required>
                    </div>
                    <input type="submit" class="btn btn-success">
                  </form>


                  <?php 
                    $attr = array('id' => 'bank-details-form','data-amount' => '');
                    echo form_open("",$attr);
                  ?>
                    <div class="form-group col-sm-12">
                      <label for="bank_name">Select Bank Name: </label>
                      <select name="bank_name" id="bank_name" class="form-control selectpicker" data-style="btn btn-link" required>
                        <?php
                          $banks_arr = $this->paystack->curl("https://api.paystack.co/bank",FALSE);
                          $banks_arr = json_decode($banks_arr);
                          // var_dump($banks_arr);
                          // print_r($banks_arr);
                          // if(is_array($banks_arr)){
                            if($banks_arr->status && $banks_arr->message == "Banks retrieved"){
                              $bank_names = $banks_arr->data;
                              foreach($bank_names as $row){
                                $bank_name = $row->name;
                                $code = $row->code;
                                $long_code = $row->longcode;
                                $active = $row->active;
                                $is_deleted = $row->is_deleted;
                                $id = $row->id;
                        ?>
                        <option value="<?php echo $code; ?>" <?php if($code == $bank_name){ echo "selected"; } ?>><?php echo $bank_name; ?></option>
                        <?php } }  ?>
                      </select>
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-12">
                      <label for="account_number">Enter Account Number: </label>
                      <input type="number" class="form-control" name="account_number" id="account_number" value="<?php if($account_number !== 0){ echo $account_number; } ?>" required>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" class="btn btn-success">
    
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="reloadPage(this)">Close</button>
                </div>
              </div>
            </div>
          </div>

        
      <footer class="footer">
        <div class="container-fluid">
          <footer></footer>
        </div>
      </footer>
      
      <script>
        $(document).ready(function () {
          $("#earnings-table").DataTable();
          
          $("#withdraw-funds-modal #enter-otp-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);
            var amount = $("#withdraw-funds-modal #withdraw_funds").val();
            var otp_val = me.find("#final_otp").val();
            $(".spinner-overlay").show();
            var url = "<?php echo site_url('onehealth/index/'. $addition .'/finalize_transfer'); ?>";
            var form_data = "amount="+amount+"&type=withdraw&otp="+otp_val;
            
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : form_data,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success == true && response.transfer_code != ""){
                  var transfer_code = response.transfer_code;
                  $("#withdraw-funds-modal #modal-display").html("");
                  me.hide();
                  $("#withdraw-funds-modal #enter-otp-form").show();
                  $("#withdraw-funds-modal .modal-title").html("Enter The OTP Gotten From Admin Of Onehealthissues.com To Proceed With Withdrawal");
                }else if(response.bouyant == false){
                  swal({
                    title: 'Error',
                    text: "You Do Not Have Enough Funds In Your Facility Account To Withdraw This Amount.",
                    type: 'error'               
                  });
                }else{
                  swal({
                    title: 'Error',
                    text: "Something Went Wrong",
                    type: 'error'                 
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              } 
            }); 
          })

          $("#withdraw-funds-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);
            var amount = me.find("#withdraw_funds").val();
            $(".spinner-overlay").show();
            var url = "<?php echo site_url('onehealth/index/'. $addition .'/transfer_funds'); ?>";
            var form_data = "amount="+amount+"&type=withdraw";
            
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : form_data,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success == true && response.transfer_code != ""){
                  var transfer_code = response.transfer_code;
                  $("#withdraw-funds-modal #modal-display").html("");
                  me.hide();
                  $("#withdraw-funds-modal #enter-otp-form").show();
                  $("#withdraw-funds-modal .modal-title").html("Enter The OTP Gotten From Admin Of Onehealthissues.com To Proceed With Withdrawal");
                }else if(response.bouyant == false){
                  swal({
                    title: 'Error',
                    text: "You Do Not Have Enough Funds In Your Facility Account To Withdraw This Amount.",
                    type: 'error'               
                  });
                }else{
                  swal({
                    title: 'Error',
                    text: "Something Went Wrong",
                    type: 'error'                 
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              } 
            }); 
          });

          $("#bank-details-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);
            var form_data = $(this).serializeArray();
            // var amount = $(this).attr("data_amount");
            // form_data.push({'name' : 'amount','value' : amount});
            console.log(form_data)
            var url = "<?php echo site_url('onehealth/index/'. $addition .'/withdraw_funds_cont'); ?>";
            $(".spinner-overlay").show();
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : form_data,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success == true && response.account_name !== ""){
                  swal({
                    title: 'Proceed With Withdrawal?',
                    text: "Is This Your Account Name <span class='text-primary' style='font-style: italic;'>" + response.account_name + "</span>?",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText : "No"
                  }).then(function(){
                    $("#withdraw-funds-modal #modal-display").html("");
                    me.hide();
                    $("#withdraw-funds-modal #withdraw-funds-form").show();
                    $("#withdraw-funds-modal .modal-title").html("Enter Withdrawal Amount To Proceed");
                  }, function(dismiss){
                     if(dismiss == 'cancel'){
                         // function when cancel button is clicked
                         console.log('cancelled');
                     }
                  });    
                }else if(response.invalid_account == true){
                  swal({
                    title: 'Invalid Account Details',
                    text: "Sorry These Account Details Are Not Linked To Any Account",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else if(response.messages !== ""){
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
                }else if(response.bouyant == false){
                   swal({
                    title: 'Insuffecient Balance',
                    text: "Sorry You Do Not Have Enough Funds In Your Account To Complete This Transaction",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else if(response.no_refer == true){
                   swal({
                    title: 'No Referrals',
                    text: "You Need To Sponsor At Least One Ambassador Or One Great Ambassador To Be Eligible For Your First Withdrawal. Each Sponsorship Earns You ₦400 Commission",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else{
                  $.notify({
                  message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                  },{
                    type : "danger"  
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              } 
            });
          })

           $("#process-otp-form").submit(function (evt) {
            evt.preventDefault();
            var form_data = $(this).serializeArray();
            var amount = $(this).attr("data-amount");
            form_data.push({
                "name" : "amount", "value" : amount
            })
            console.log(form_data)
            var url = "<?php echo site_url('onehealth/process_withdraw_otp'); ?>";
            $(".spinner-overlay").show();
            $.ajax({
              type : "POST",
              dataType : "json",
              responseType : "json",
              url : url,
              data : form_data,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success == true){
                  var amount = response.amount;
                  $("#process-otp-form").hide();
                  $("#bank-details-form").attr("data_amount",amount);
                  $("#bank-details-form").show();
                }else if(response.bouyant == false){
                   swal({
                    title: 'Insuffecient Balance',
                    text: "Sorry You Do Not Have Enough Funds In Your Account To Complete This Transaction",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else if(response.no_refer == true){
                   swal({
                    title: 'No Referrals',
                    text: "Sorry You Have Not Referred Anyone. You Must Refer Someone To Withdraw",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else if(response.expired == true){
                   swal({
                    title: 'Error',
                    text: "Sorry These Details Have Expired",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else if(response.incorrect == true){
                   swal({
                    title: 'Error',
                    text: "Sorry These Details Are Incorrect",
                    type: 'error',
                    confirmButtonColor: '#3085d6',                    
                    confirmButtonText: 'Ok'                   
                  });
                }else{
                  $.notify({
                  message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                  },{
                    type : "danger"  
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              } 
            });  
          });

        <?php
         if($this->session->withdrawal_success && $this->session->withdrawal_success == true){ 
          $amount = $this->session->amount;
          unset($_SESSION['withdrawal_success']);
          unset($_SESSION['amount']);
          ?>
          $.notify({
          message:"You Account Has Been Successfully Debited Of ₦<?php echo number_format($amount); ?>"
          },{
            type : "success"  
          });
        <?php } ?>


        <?php 
          if($this->session->all_cleared && $this->session->all_cleared == true){
        ?>
          $.notify({
          message:"All Payment History Cleared Successfully"
          },{
            type : "success"  
          });
        <?php 
          }
        ?>
        })
        
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 