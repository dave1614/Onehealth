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
            
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
      ?>

      <script>

        function loadPatientInitiationCodeTable(initiation_code,type) {
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get-tests-initiation-code-teller'); ?>"
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+initiation_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response.success && response.messages != "" ){
                var patient_name = response.patient_name;
                $("#initiation-code-card .card-title").html("Tests Selected For : <span class='text-primary' style='font-style: italic;'>"+patient_name+"<span>");
                if(type == 2){
                  $("#initiation-code-card #go-back").attr('onclick', 'goBackInitiationCodeCard1(this,event)');
                }else if(type == 1){
                  $("#initiation-code-card #go-back").attr('onclick', 'goBackInitiationCodeCard(this,event)');
                }
                $("#initiation-code-card .card-body").html(response.messages);
                $("#initiation-codes-card").hide("fast");
                $("#initiation-code-table").DataTable();
                $("#payment-history-table").DataTable();
                
                $("#initiation-code-card").show("fast");
                
              }else{
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
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

        function goBackInitiationCodesCard(elem,evt){
          $("#initiation-codes-card").hide();
          $("#inpute-patient-code-modal").modal("show");
          $("#main-card").show();
        }

        function goBackInitiationCodeCard(elem,evt){
          $("#initiation-code-card").hide("fast");
          $("#initiation-codes-card").show("fast");
        }

        function goBackInitiationCodeCard1(elem,evt){
          $("#initiation-code-card").hide("fast");
          $("#inpute-patient-code-modal").modal("show");
        }

        function submitMakePaymentsForm(elem,evt) {
          evt.preventDefault();
          var health_facility_logo = $("#facility_img");
          var logo_src = health_facility_logo.attr("src");
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
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit-test-payment-form'); ?>";
          
          var amount = elem.querySelector("#make-payments-input").value;
          amount = Number(amount);
          var initiation_code = elem.getAttribute("data-initiation-code");
          
            var max = Number(elem.querySelector("#make-payments-input").getAttribute("max"));
            
            if(amount > max){
              swal({
                title: 'Error',
                text: "Amount Entered Here Cannot Be Greater Than "+addCommas(max),
                type: 'warning',
                
              })
            }else{
              swal({
                title: 'Warning',
                text: "You Are About To Make Payment For This Patient, Do You Want To Proceed?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Proceed'
              }).then((result) => {
                $(".spinner-overlay").show();
                $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "amount="+amount+"&initiation_code="+initiation_code,
                    success : function(response){
                      $(".spinner-overlay").hide();
                      if(response.success && response.records != ""){
                        response = response.records
                        var lab_id = response.lab_id;
                        
                        var patient_name = response.patient_name;
                        var receipt_number = response.receipt_number;
                        var facility_state = response.facility_state;
                        var facility_country = response.facility_country;
                        var facility_address = response.facility_address;
                        var receipt_file = response.receipt_file;
                        var facility_name = '<?php echo $health_facility_name; ?>';
                        var date = response.date;
                        var total_price = response.total_price;
                        sum = response.amount;
                        var balance = response.balance;
                        balance = balance - sum;
                        
                        console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
                        $(".spinner-overlay").hide();
                        var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pdf_tests') ?>";
                        $(".spinner-overlay").show();
                        $.ajax({
                          url : get_pdf_tests_url,
                          type : "POST",
                          responseType : "json",
                          dataType : "json",
                          data : "get_pdf_tests=true&lab_id="+lab_id,
                          success : function (response) {
                            $(".spinner-overlay").hide();
                            var pdf_data =  {
                              'logo' : company_logo,
                              'color' : <?php echo $color; ?>,
                              'tests' : response,
                              'sum' : String(addCommas(sum)),
                              'facility_name' : facility_name,
                              'facility_id' : "<?php echo $health_facility_id; ?>",
                              'initiation_code' : initiation_code,
                              'lab_id' : lab_id,
                              'mod' : 'teller',
                              'facility_address' : "<?php echo $health_facility_address; ?>",
                              'patient_name' : patient_name,
                              'receipt_number' : receipt_number,
                              'facility_state' : facility_state,
                              'facility_country' : facility_country,
                              'date' : date,
                              'receipt_file' : receipt_file,
                              'is_teller' : 1,
                              'total_price' : String(addCommas(total_price)),
                              'balance' : String(addCommas(balance))
                            };
                            console.log(JSON.stringify(pdf_data));
                        
                            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt') ?>";
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
                                  var pdf_url = "<?php echo base_url('assets/images/')?>" +receipt_file;
                                  window.location.assign(pdf_url);
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
                        
                      }else if(response.excess){
                        swal({
                          title: 'Error',
                          text: "Amount Entered Here Cannot Be Greater Than "+addCommas(max),
                          type: 'warning',
                          
                        })
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
              });
            }
        }

        function checkAll () {
          $("input[type=checkbox]:visible").prop('checked', true);
        }

        function goDefault(){
          // $("#display-funds-card").hide();
          // $("#mark-tests-form").children().remove();
          // $("#mark-tests-form").append("<button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>");
          // $("#welcome-heading").html("Welcome");
          // $("#main-card-body").append('<h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4><div class="btn-grp"><button class="btn btn-primary btn-action" data-toggle="modal" data-target="#inpute-patient-code-modal">Collect Payment</button><button class="btn btn-info btn-action" id="refund-payment">Refund Payment</button></div><button class="btn btn-success btn-action" data-toggle="modal" data-target="#display-funds-modal">Display Available Funds</button>');
          // $("#mark-tests-form").hide();
          // $("#main-card").show();
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
        function testPaid (id,patient_name,patient_code) {
          swal({
            title: 'Warning',
            text: "You are about to mark " + patient_name + " with code " + patient_code + " as paid. Do Want To Continue?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark!'
          }).then((result) => {
            
             $(".spinner-overlay").show();
             $.ajax({
                url : get_code_data_url,
                type : "POST",
                responseType : "text",
                dataType : "text",
                data : "edit_tests=true&code="+patient_code,
                success : function(response){
                  $(".spinner-overlay").hide();
                },
                error: function () {
                  
                }
              
            })
          });   
        }

       

         function patientCheckBoxEvt() {
          var total=$('input[type=checkbox]:checked').length;
          // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
          var sum = 0;
          // var selectedRows = client_table.rows({ selected: true }).length;
          $("input[type=checkbox]:checked").each(function(){
            sum += parseInt($(this).attr("data-cost"));
          });
          if(total > 0){
            
            if(!$("#num-tests-para")){
              $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " test selected with total sum of ₦" + addCommas(sum) + ".</p>");
            }else{
              $("#num-tests-para").remove();
              $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>");
            }
          }else{
            $("#num-tests-para").html("");
          }
        }

        function loadPreviousTransactions(elem) {
          $("#inpute-patient-code-modal").modal("hide");
          $("#main-card").hide();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get-initiation-codes-teller'); ?>"
          
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "text",
            dataType : "text",
            data : "get-initiation-codes=true",
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              $("#initiation-codes-card .card-body").html(response);
              var table = $("#initiation-codes-table").DataTable();
              $('#initiation-codes-table tbody').on('click', 'tr', function () {
                  if ( $(this).hasClass('selected') ) {
                      $(this).removeClass('selected');
                  }
                  else {
                      table.$('tr.selected').removeClass('selected');
                      $(this).addClass('selected');
                  }
              } ); 
              $("#initiation-codes-card").show();

            },
            error : function () {
              $(".spinner-overlay").hide();
            }
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
                  <button id='go-back' style="display: none;" class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  <?php 
                    $attr = array('id' => 'mark-tests-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                  <h4 style="margin-bottom: 40px;" id="quest">Do You Want To: </h4>
                  <div class="btn-grp">
                    <button class="btn btn-primary btn-action" data-toggle="modal" data-target="#inpute-patient-code-modal">Receive Payment</button>
                    <button class="btn btn-info btn-action" id="refund-payment">Refund Payment</button>
                    <?php if($this->onehealth_model->checkIfUserIsAnAdminBool($health_facility_table_name,$user_name)){ ?>
                      
                    <?php } ?>
                    <button class="btn btn-success btn-action" id="display-funds">Display Available Funds</button>
                  </div>
                  
                </div>
              </div>

              <div class="card" id="initiation-codes-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Previously Initiated Patients</h3>
                  <button class='btn btn-warning' onclick='goBackInitiationCodesCard(this,event)'>Go Back</button>
                </div>
                <div class="card-body">
                  
                  
                </div>
              </div>
              
              <div class="card" id="refund-payment-card" style="display: none;">
                <div class="card-header">
                   <?php
                   if($this->onehealth_model->checkIfUserIsMainAdminBool($health_facility_table_name,$user_name)){
                      $admin = true;
                   } 
                   ?>
                  <h3 class="card-title" id="welcome-heading">Refund Payment</h3>
                  <button id='go-back' class='btn btn-warning' onclick='goDefault()'>Go Back</button>
                  <?php 
                    $attr = array('id' => 'refund-payment-form','style' => 'display: none;');
                    echo form_open('',$attr);
                    ?>
                    
                  </form>
                </div>
                <div class="card-body">
                
                </div>
                  
                </div>
              </div>

              <div class="card" id="initiation-code-card" style="display: none;">
                <div class="card-header">
                   
                  <h3 class="card-title"></h3>
                  <button id='go-back' class='btn btn-warning' onclick=''>Go Back</button>
                  
                </div>
                <div class="card-body">
                
                </div>
              </div>
  
              <div class="card" id="display-funds-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Transaction History</h3>
                </div>
                <div class="card-body" id="main-card-body">
                  <button type="button" class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  <?php 
                      $health_facility_test_result_table_name = $this->onehealth_model->createTestResultTableHeaderString($health_facility_id,$health_facility_name);
                      $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
                      $sub_dept_id = $this->onehealth_model->getSubDeptIdBySlugAndDeptId($third_addition,$dept_id);
                      $paid_tests = $this->onehealth_model->getAllPaidTests($health_facility_test_result_table_name,$sub_dept_id);
                      if(is_array($paid_tests)){
                    ?>
                    <div class="form-group">
                    <select name="" id="select-time-range" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Time Range" data-size="7">
                      <option value="1-day" selected>24 Hours</option>
                      <option value="1-week">1 Week</option>
                      <option value="1-month">1 Month</option>
                      <option value="1-year">1 Year</option>
                      <option value="1-decade">1 Decade</option>
                    </select>
                  </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="display-funds-table">
                      <thead>
                        <tr>
                          <th class="text-primary">#</th>
                          <th class="text-primary">Lab Id</th>
                          <th class="text-primary">Patient Name</th>
                          <th class="text-primary">Test Name</th>
                          <th class="text-primary">Amount Paid</th>
                          <th class="text-primary">Date Paid</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $total_price = 0;
                        $i = 0;
                          foreach($paid_tests as $row){
                            
                            $id = $row->id;
                            $lab_id = $row->lab_id;
                            $patient_name = $row->patient_name;
                            $test_name = $row->test_name;
                            $date_paid1 = $row->date_paid;
                            $date_paid2 = date_create($row->date_paid);
                            $curr_date = date_create(date("j M Y"));
                            $curr_time = date("h:i:sa"); 
                            $time_paid = $row->time_paid;
                            $price = $row->price;
                            $refund_requested = $row->refund_requested;
                            $patient_username = $row->patient_username;
                            $date_diff = date_diff($curr_date,$date_paid2);
                            $date_diff = $date_diff->format("%d");

                            if($date_diff <= 1)  {
                              $total_price = $price + $total_price;
                              $i++;
                              
                        ?>
                        <tr>
                          <td class="text-left"><?php echo $i; ?><?php if($refund_requested == 1){ ?>
                            <span class="text-warning" style="font-size: 12px;">Refund Requested</span>
                          <?php } ?></td>

                          <td><?php echo $lab_id; ?></td>
                          <td><?php echo $patient_name; ?></td>
                          <td><?php echo $this->onehealth_model->custom_echo($test_name,50); ?></td>
                          <td><?php echo number_format($price); ?></td>
                          <td><?php echo $date_paid1 .' ' . $time_paid; ?></td>
                        </tr>

                        <?php
                            }
                          }
                        ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td>Total Amount: <?php echo number_format($total_price); ?></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                    <?php
                      }
                    ?>
                </div>
                  
                </div>
              </div>
             
            </div>
            <div class="" style="display: none;">
              <div id="content">
                  <h3 style="color: red; font-weight: bold;">Sample h3 tag</h3>
                  <p>Sample pararaph</p>
              </div>
            </div>
            <div class="modal fade" data-backdrop="static" id="inpute-patient-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <?php $attributes = array('class' => '','id' => 'patient-code-form') ?>
                    <?php echo form_open('',$attributes); ?>
                      <div class="form-group">
                        <label for="patient-name">Enter Patient Initiation Code: </label>
                        <input type="text" id="patient-code" class="form-control" name="patient-code">
                        <span class="form-error"></span>
                      </div>
                      <input type="submit" class="btn btn-success" value="REQUEST" name="submit">
                    <?php echo form_close(); ?>
                    <h4 style="font-weight: bold;">OR</h4>
                    <button class="btn btn-primary" onclick="loadPreviousTransactions(this)">View Initiated Patients</button>
                  
                    
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved </footer> -->
        </div>
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {


      $("#select-time-range").change(function (evt) {
        var time_range = $("#select-time-range").val();
        var range = time_range;
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/change_funds_range'); ?>"
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "change_range=true&range="+range,
          success : function(response){
            
            $(".spinner-overlay").hide();
            $("#display-funds-table").html("");
            $("#display-funds-table").html(response);
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

      $("#display-funds").click(function (evt) {
       
        $("#main-card").hide();
        $("#display-funds-card").show();
      })

      $("#refund-payment").click(function (evt) {
       
        $("#main-card").hide();
        $("#refund-payment-card").show();
        
        $(".spinner-overlay").show();
        var url =  "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_tests'); ?>";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "text",
          dataType : "text",
          data : "view_tests=true",
          success : function(response){
            $(".spinner-overlay").hide();
            
            $("#refund-payment-form").append("<h4>Select Paid Tests To Refund</h4>")
            $("#refund-payment-form").append(response);
            $("#refund-payment-form").show();
             var table = $('#example1').DataTable();

            $('#example1 tbody').on('click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } ); 
            
          },
          error : function () {
            
          }
        });

           

      })

      //Submit function for refund payment form
      $("#refund-payment-form").submit(function (evt) {
        
          evt.preventDefault();
          var table = $('#example1').DataTable();
          table.search("").draw();
          patientCheckBoxEvt();
          var total = $('input[type=checkbox]:checked').length;
          // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
          var sum = 0;
          // var selectedRows = client_table.rows({ selected: true }).length;
          var patient_names = ",";
          var test_names = ",";
          $("input[type=checkbox]:checked").each(function(){
            sum += parseInt($(this).attr("data-cost"));
            patient_names += $(this).attr("data-patient-name");
            test_names += $(this).attr("test-name");
          });

          if(total > 0){
            <?php if($admin == true){ ?>
            swal({
              title: 'Continue?',
              text: "<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + " Are You Sure You Want To Refund Payment?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Refund'
            }).then((result) => {
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/refund_paid_tests') ?>";
                
                  var me = $(this);
                  var values = me.serializeArray();
                  values = values.concat(
                  jQuery('#myform input[type=checkbox]:not(:checked)').map(
                          function() {
                              return {"name": this.name, "value": this.value}
                          }).get()
                  );
                 
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "text",
                    dataType : "text",
                    data : values,
                    success : function (response) {
                      
                     if(response !== 0){
                      var lab_id = response;
                      $(".spinner-overlay").hide();
                      swal({
                        type: 'success',
                        title: 'Successful',
                        allowOutsideClick : false,
                        allowEscapeKey :false,
                        text: 'Funds Refunded Successfully',
                        
                        confirmButtonColor: '#3085d6',
                        
                        confirmButtonText: 'OK'
                        // footer: '<a href>Why do I have this issue?</a>'
                      }).then(
                      value => {
                       document.location.reload();
                      }
                    ).catch(swal.noop)
                     }
                     else{
                      $(".spinner-overlay").hide();
                      swal({
                        type: 'error',
                        title: 'Oops.....',
                        text: 'Sorry, something went wrong. Please try again!'
                        // footer: '<a href>Why do I have this issue?</a>'
                      })
                     }
                    },
                    error : function(){
                      $(".spinner-overlay").hide();
                      swal({
                        type: 'error',
                        title: 'Oops.....',
                        text: 'Sorry, something went wrong. Please try again!'
                        // footer: '<a href>Why do I have this issue?</a>'
                      })
                    }
                  })
                  // $("#submit-form").append("<input type='hidden' name='test[]' value='"+ value + "'>");
                  // $("#submit-form").submit();
                });  
              <?php } else{ ?>
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/set_refunds_session_data') ?>";
                var request_successful = 0;
                $("input[type=checkbox]:checked").each(function(){
                  
                  var lab_id = $(this).attr("data-lab-id");
                  var date_paid = $(this).attr("data-date-paid");
                  var test_name = $(this).attr("data-test-name");
                  var cost = $(this).attr("data-cost");
                  var id = $(this).val();
                  var initiation_code = $(this).attr("data-initiation-code");
                  var patient_name = $(this).attr("data-patient-name");
                  patient_name = $.trim(patient_name);
                    
                  var value = {
                    "test_name" : test_name,
                    "id" : id,
                    "lab_id" : lab_id,
                    "date_paid": date_paid,
                    "cost":cost,
                    "initiation_code" : initiation_code,
                    "patient_name" : patient_name
                  };
                  
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "text",
                    dataType : "text",
                    async : false,
                    data : {data:value},
                    success : function (response) {
                      $(".spinner-overlay").hide();
                      console.log(response)
                      if(response == 1){
                        request_successful = 1; 
                      }else{
                        $.notify({
                        message:"Sorry Something Went Wrong"
                        },{
                          type : "danger"  
                        });
                      }
                    },
                    error :function () {
                      $(".spinner-overlay").hide();
                      $.notify({
                      message:"Sorry Something Went Wrong"
                      },{
                        type : "danger"  
                      });
                    }
                        
                  });
                });
                console.log(request_successful)
                if(request_successful == 1){
                  $(".spinner-overlay").show();
                  var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/notify_admin_refunds') ?>"
                  $.ajax({
                      url : url,
                      type : "POST",
                      responseType : "text",
                      dataType : "text",
                      data : "notify_admin=true&personnel="+"<?php echo $user_name ?>",

                      success : function (response) {
                        $(".spinner-overlay").hide();
                        
                        if(response == 1){
                          swal({
                            title: 'Ooops!',
                            text: "<p class='text-primary'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + "<span class='text-danger' style='font-style: italic;'> But You Do Not Have The Authorization To Refund Payment. Admin has been contacted. If Approved, funds will be refunded and you would be duly notified</span>",
                            type: 'error',
                            allowOutsideClick : false,
                            allowEscapeKey :false,
                            
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                          }).then((result) => {
                        
                            document.location.reload();
                          })
                        }else{
                          $.notify({
                          message:"Sorry Something Went Wrong"
                          },{
                            type : "danger"  
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
                  })

                  
                }
              <?php } ?>
              
          }else{
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Sorry, you have not selected any tests. Please Select To Continue'
              // footer: '<a href>Why do I have this issue?</a>'
            })
          }
          return false;
      }) 
      //Submit Function For Patient Code Form
      $("#patient-code-form").submit(function (evt) {
        evt.preventDefault();
        var patient_code = $("#patient-code").val();
        var get_code_data_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_tests_2'); ?>";
        patient_code = $.trim(patient_code);
        if(patient_code == ""){
          $(".form-error").html("This Field Cannot Be Empty");
        }else{
          $(".spinner-overlay").show();
         $.ajax({
            url : get_code_data_url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "edit_tests=true&code="+patient_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response.success == true){
                $("#main-card").hide();
                $("#inpute-patient-code-modal").modal("hide")
                loadPatientInitiationCodeTable(patient_code,2)
              }else{
                swal({
                  type: 'error',
                  title: 'Oops.....',
                  text: 'Sorry, no record with this initiation code. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
                })
             }  
            },
           error : function(){
              $(".spinner-overlay").hide();
              swal({
                type: 'error',
                title: 'Oops.....',
                text: 'Sorry, something went wrong. Please try again!'
                // footer: '<a href>Why do I have this issue?</a>'
              })
            }
          }); 
        }    
      });
      
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
            window.location.assign("<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/add_admin') ?>")
            
          // }
        })
      <?php
        }
      ?>

      

      $("#mark-tests-form").submit(function (evt) {
          evt.preventDefault();
          var table = $('#example').DataTable();
          
          // var selectedRows = client_table.rows({ selected: true }).length;
          
          $("input[type=checkbox]:checked").each(function(){
            sum += parseInt($(this).attr("data-cost"));
          });

          if(total > 0){
            
            swal({
              title: 'Continue?',
              text: "<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>" + " Do Want To Mark As Paid?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, mark!'
            }).then((result) => {
                var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_paid_tests') ?>"
                
                  var me = $(this);
                  var values = me.serializeArray();
                  values = values.concat(
                  jQuery('#myform input[type=checkbox]:not(:checked)').map(
                          function() {
                              return {"name": this.name, "value": this.value}
                          }).get()
                  );
                  
                 var initiation_code = $("#example").attr('data-ini-code');
                  $(".spinner-overlay").show();
                  console.log(values)
                  $.ajax({
                    url : submit_tests_url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : values,
                    success : function (response) {
                      // console.log(response)
                      
                    },
                    error : function(){
                      $(".spinner-overlay").hide();
                      swal({
                        type: 'error',
                        title: 'Oops.....',
                        text: 'Sorry, something went wrong. Please try again!'
                        // footer: '<a href>Why do I have this issue?</a>'
                      })
                    }
                  })
                  // $("#submit-form").append("<input type='hidden' name='test[]' value='"+ value + "'>");
                  // $("#submit-form").submit();
                });  
                
          }else{
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Sorry, you have not selected any tests. Please Select To Continue'
              // footer: '<a href>Why do I have this issue?</a>'
            })
          }
          return false;
      })

    });

  </script>
