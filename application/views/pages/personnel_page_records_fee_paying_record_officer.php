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

<style>
  tr{
    cursor: pointer;
  }
</style>

<script>
  

  function goDefault() {
    
    document.location.reload();
  }

  function performActions (elem,evt) {
    $("#main-card").hide();
    $("#choose-action-card").show();
  }

  function goBackFromChooseActionCard (elem,evt) {
    $("#main-card").show();
    $("#choose-action-card").hide();
  }

  function inputNewLabToLabCodes (elem,evt) {
    evt.preventDefault();
    $("#choose-action-card").hide();
    $("#input-new-lab-to-lab-codes-card").show();
  }

  function inputNewRegistrationCodes (elem,evt) {
    evt.preventDefault();
    $("#choose-action-card").hide();
    $("#input-new-codes-card").show();
  }

  function goBackFromInputRegistrationCodesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#input-new-codes-card").hide();
  }
  function inputCodesInfoBtn(elem,evt){
    evt.preventDefault();
    $("#choose-action-card").hide();
    $("#input-new-lab-to-lab-codes-card").show();
  }

  function goBackFromInputLabToLabCodeCard (elem,evt) {
    $("#choose-action-card").show();
    $("#input-new-lab-to-lab-codes-card").hide();
  }

  function goBackFromUsedLabToLabCodesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#used-lab-to-lab-codes-card").hide();
  }

  function viewAndUpdateLabToLabCodes (elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To View: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Used Codes',
      cancelButtonText: 'Unused Codes'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_used_codes_lab_to_lab_referrals') ?>";
      // var pdf = btoa(doc.output());
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#used-lab-to-lab-codes-card .card-body").html(messages);
            $("#used-lab-to-lab-codes-card #used-lab-to-lab-codes-table").DataTable();
            $("#choose-action-card").hide();
            $("#used-lab-to-lab-codes-card").show();
          }else{
            $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      })
    }, function(dismiss){
      if(dismiss == 'cancel'){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_unused_codes_lab_to_lab_referrals') ?>";
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true",
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#unused-lab-to-lab-codes-card .card-body").html(messages);
              $("#unused-lab-to-lab-codes-card #unused-lab-to-lab-codes-table").DataTable();
              $("#choose-action-card").hide();
              $("#unused-lab-to-lab-codes-card").show();
            }else{
              $.notify({
              message:"No Record To Display"
              },{
                type : "warning"  
              });
            }
          },
          error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Something Went Wrong"
            },{
              type : "danger"  
            });
          }
        })
      }
    });
  }

  function goBackFromLabToLabUnUsedCodesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#unused-lab-to-lab-codes-card").hide();
  }

  function viewAndUpdateRegistrationCodes(elem,evt){
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To View: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Used Codes',
      cancelButtonText: 'Unused Codes'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_used_codes_records') ?>";
      // var pdf = btoa(doc.output());
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#used-codes-card .card-body").html(messages);
            $("#used-codes-table").DataTable();
            $("#choose-action-card").hide();
            $("#used-codes-card").show();
          }else{
            $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      })
    }, function(dismiss){
      if(dismiss == 'cancel'){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_unused_codes_records') ?>";
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true",
          
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#unused-codes-card .card-body").html(messages);
              $("#unused-codes-table").DataTable();
              $("#choose-action-card").hide();
              $("#unused-codes-card").show();
            }else{
              $.notify({
              message:"No Record To Display"
              },{
                type : "warning"  
              });
            }
          },
          error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Something Went Wrong"
            },{
              type : "danger"  
            });
          }
        })
      }
    });
  }

  function goBackFromUsedCodesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#used-codes-card").hide();
  }

  function goBackFromUnUsedCodesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#unused-codes-card").hide();
  }

  function viewUnusedCodesRecord(elem,evt,id){
    console.log(id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_unused_codes_record_info') ?>";
      // var pdf = btoa(doc.output());
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages.length != 0){
            var messages = response.messages;
            var id = messages.id;
            var code = messages.code;
            var company_id = messages.company_id;
            $("#edit-code-form").attr("data-id",id);
            $("#unused-code-info-card #code").html(code);
            $("#unused-code-info-card #company_id").html(company_id);
            for (const [key, value] of Object.entries(messages)) {
              $("#unused-code-info-card #" +key).val(value);
              if(key == "name_code_authentication" || key == "type"){
                $("#unused-code-info-card #" +value).prop("checked",true);
              }
            }

            $("#unused-code-info-card").show();  
            $("#unused-codes-card").hide();  
          }else{
            $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      })
  }

  function goBackFromUnUsedCodeInfoCard (elem,evt) {
    $("#unused-code-info-card").hide();  
    $("#unused-codes-card").show();  
  }

  function viewUnusedCodesLabToLabReferrals(elem,evt,id){
    console.log(id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_unused_codes_lab_to_lab_referral') ?>";
      // var pdf = btoa(doc.output());
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages.length != 0){
            var messages = response.messages;
            var id = messages.id;
            var code = messages.code;
            $("#unused-lab-to-lab-code-info-card #unused-lab-to-lab-code-info-form").attr("data-id",id);
            $("#unused-lab-to-lab-code-info-card #code").html(code);
            for (const [key, value] of Object.entries(messages)) {
              $("#unused-lab-to-lab-code-info-card #" +key).val(value);
              if(key == "name_code_authentication" || key == "type"){
                $("#unused-lab-to-lab-code-info-card #" +value).prop("checked",true);
              }
            }

            $("#unused-lab-to-lab-code-info-card").show();  
            $("#unused-lab-to-lab-codes-card").hide();  
          }else{
            $.notify({
            message:"Something Went Wrong"
            },{
              type : "danger"  
            });
          }
        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      })
  }

  function goBackFromUnUsedLabToLabCodeInfoCard (elem,evt) {
    $("#unused-lab-to-lab-code-info-card").hide();  
    $("#unused-lab-to-lab-codes-card").show();  
  }

  function inputPercentagesForPartFeePaying (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_percentages_for_part_paying_record_officer') ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#part-fee-paying-percentages-card .card-body").html(messages);
          $("#part-fee-paying-percentages-card #part-fee-paying-percentages-table").DataTable();
          $("#choose-action-card").hide();
          $("#part-fee-paying-percentages-card").show();
          $("#add-new-part-payment-discount-btn").show("fast");
        }else{
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },
      error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Something Went Wrong"
        },{
          type : "danger"  
        });
      }
    })
  }

  function goBackFromPartFeepayingPercentagesCard (elem,evt) {
    $("#choose-action-card").show();
    $("#part-fee-paying-percentages-card").hide();
    $("#add-new-part-payment-discount-btn").hide("fast"); 
  }

  function addNewPartPaymentDiscount (elem,evt) {
    $("#part-fee-paying-percentages-card").hide();
    $("#add-new-part-payment-discount-btn").hide("fast"); 
    $("#add-new-part-payment-discount-modal").modal("show"); 
  }

  function goBackFromOpenAddNewPartPaymentDiscountModal (elem,evt) {
    $("#part-fee-paying-percentages-card").show();
    $("#add-new-part-payment-discount-btn").show("fast"); 
    $("#add-new-part-payment-discount-modal").modal("hide"); 
  }

  function editPartFeePayingPercentageCode (elem,evt,id) {
    elem = $(elem);
    var code_identity = elem.attr("data-code");
    var percentage_discount = elem.attr("data-percentage-discount");
    if(id != "" && code_identity != "" && percentage_discount != ""){
      $("#edit-part-payment-discount-modal").modal("show");
      $("#edit-part-payment-discount-modal #edit-part-payment-discount-form").attr("data-id",id);
      $("#edit-part-payment-discount-modal #edit-part-payment-discount-form #code-identity").text(code_identity);
      $("#edit-part-payment-discount-modal #edit-part-payment-discount-form #percentage_discount").val(percentage_discount);
    }
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

              <div class="card" id="part-fee-paying-percentages-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromPartFeepayingPercentagesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Percentages For Part Fee Paying Codes</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="unused-lab-to-lab-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromLabToLabUnUsedCodesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Unused Lab To Lab Referral Payment Codes </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="unused-lab-to-lab-code-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromUnUsedLabToLabCodeInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Edit This Unused Lab To Lab Referral Payment Code </h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'unused-lab-to-lab-code-info-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <div class="wrap">
                    <div class="form-row">             
                      <div class="form-group col-sm-6">
                        <label for="firstname" class="label-control"><span class="form-error1">*</span>  First Name: </label>
                        <input type="text" class="form-control" id="firstname" name="firstname">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="lastname" class="label-control"><span class="form-error1">*</span> Last Name: </label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                        <span class="form-error"></span>
                      </div>


                      <h5>Code: </h5>
                      <p class="col-sm-6" id="code"></p>


                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Name Code Authentication: <a href="#" onclick="inputCodesInfoBtn(this,event)" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="Here you determine if you want the code inputted by the front desk Record officers to match code only or name and names, before authentication and subsequent authorisation to be registered as non-fee-paying or part-fee-paying. Check the box to make your choice.">?</a></p>
                        <div id="code_authentication">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="1" id="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="0" id="0" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                </form>
                </div>
              </div>

              <div class="card" id="input-new-lab-to-lab-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromInputLabToLabCodeCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Input New Lab To Lab Referral Code</h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'input-new-lab-to-lab-codes-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <div class="wrap">
                    <div class="form-row">             
                      <div class="form-group col-sm-6">
                        <label for="firstname" class="label-control"><span class="form-error1">*</span>  First Name: </label>
                        <input type="text" class="form-control" id="firstname" name="firstname">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="lastname" class="label-control"><span class="form-error1">*</span> Last Name: </label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="code" class="label-control"><span class="form-error1">*</span> Code: </label>
                        <input type="text" class="form-control" id="code" name="code">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Name Code Authentication: <a href="#" onclick="inputCodesInfoBtn(this,event)" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="Here you determine if you want the code inputted by the front desk Record officers to match code only or name and names, before authentication and subsequent authorisation to be registered as non-fee-paying or part-fee-paying. Check the box to make your choice.">?</a></p>
                        <div id="code_authentication">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="1" id="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="0" id="0" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                </form>
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button style="margin-top: 50px;" onclick="performActions(this,event)" class="btn btn-info">Perform Actions</button>
                </div>
              </div>

              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromChooseActionCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Choose Action: </h3>
                </div>
                <div class="card-body">

                  <table class="table">
                    <tbody>
                      <?php if($health_facility_structure == "hospital" || $health_facility_structure == "laboratory"){ ?>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="inputNewRegistrationCodes(this,event)">Input New Registration Codes</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="viewAndUpdateRegistrationCodes(this,event)">View And Update Registration Codes</a></td>
                      </tr>

                      <tr class="pointer-cursor">
                        <td>3</td>
                        <td><a href="#" onclick="inputPercentagesForPartFeePaying(this,event)">Input Percentages For part Fee Paying Codes</a></td>
                      </tr>


                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="inputNewLabToLabCodes(this,event)">Input New Lab To Lab Referral Payment Codes</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>5</td>
                        <td><a href="#" onclick="viewAndUpdateLabToLabCodes(this,event)">View And Update Lab To Lab Referral Payment Codes</a></td>
                      </tr>
                      <?php  } ?>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="used-lab-to-lab-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromUsedLabToLabCodesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Used Lab To Lab referral Payment Codes </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="used-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromUsedCodesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Used Codes </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="unused-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromUnUsedCodesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Unused Codes </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="unused-code-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromUnUsedCodeInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Edit This Unused Code </h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'edit-code-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <div class="wrap">
                    <div class="form-row">             
                      <div class="form-group col-sm-6">
                        <label for="firstname" class="label-control"><span class="form-error1">*</span>  First Name: </label>
                        <input type="text" class="form-control" id="firstname" name="firstname">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="lastname" class="label-control"><span class="form-error1">*</span> Last Name: </label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                        <span class="form-error"></span>
                      </div>


                      <h5>Code: </h5>
                      <p class="col-sm-6" id="code"></p>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Client Stratification: </p>
                        <div id="stratification">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="stratification" value="pfp" id="pfp" checked> Part Fee Paying
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="stratification" value="nfp" id="nfp"> None Fee Paying
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Name Code Authentication: <a href="#" onclick="inputCodesInfoBtn(this,event)" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="Here you determine if you want the code inputted by the front desk Record officers to match code only or name and names, before authentication and subsequent authorisation to be registered as non-fee-paying or part-fee-paying. Check the box to make your choice.">?</a></p>
                        <div id="code_authentication">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="1" id="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="0" id="0" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>

                        <span class="form-error"></span>
                      </div>

                      
                    </div>
                    <div class="col-sm-6">
                        <h5>Company Id: </h5>
                        <p id="company_id"></p>
                      </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                </form>
                </div>
              </div>



              <div class="card" id="input-new-codes-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromInputRegistrationCodesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Input New Registration Code</h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'input-code-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <div class="wrap">
                    <div class="form-row">             
                      <div class="form-group col-sm-6">
                        <label for="firstname" class="label-control"><span class="form-error1">*</span>  First Name: </label>
                        <input type="text" class="form-control" id="firstname" name="firstname">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="lastname" class="label-control"><span class="form-error1">*</span> Last Name: </label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="code" class="label-control"><span class="form-error1">*</span> Code: </label>
                        <input type="text" class="form-control" id="code" name="code">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Client Stratification: </p>
                        <div id="stratification">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="stratification" value="pfp" id="pfp" checked> Part Fee Paying
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="stratification" value="nfp" id="nfp"> None Fee Paying
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label"><span class="form-error1">*</span>Name Code Authentication: <a href="#" onclick="inputCodesInfoBtn(this,event)" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="Here you determine if you want the code inputted by the front desk Record officers to match code only or name and names, before authentication and subsequent authorisation to be registered as non-fee-paying or part-fee-paying. Check the box to make your choice.">?</a></p>
                        <div id="code_authentication">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="1" id="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="code_authentication" value="0" id="0" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="company_id" class="label-control"><span class="form-error1">*</span> Company Id: </label>
                        <input type="text" class="form-control" id="company_id" name="company_id">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  <input type="submit" class="btn btn-primary">
                </form>
                </div>
              </div>
                
             
            </div>
          </div>
        </div>
      </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="edit-part-payment-discount-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Edit Part Fee Paying Percentage Discount</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'edit-part-payment-discount-form');
                echo form_open('',$attr);
              ?>
                <div>
                  <p>Code Identity: <em class="text-primary" id="code-identity"></em></p>
                  
                </div>
                <div class="form-group">
                  <label for="percentage_discount">Enter Percentage Discount: </label>
                  <input type="number" max="100" min="0" class="form-control" id="percentage_discount" name="percentage_discount" required>
                  <span class="form-error"></span>
                </div>

                <input type="submit" value="Submit" class="btn btn-primary">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="add-new-part-payment-discount-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Add New Part Fee Paying Percentage Discount</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="goBackFromOpenAddNewPartPaymentDiscountModal(this,event)">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'add-new-part-payment-discount-form');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="code">Enter Code Identity: </label>
                  <input type="text" class="form-control" id="code" name="code" required>
                  <span class="form-error"></span>
                </div>
                <div class="form-group">
                  <label for="percentage_discount">Enter Percentage Discount: </label>
                  <input type="number" max="100" min="0" class="form-control" id="percentage_discount" name="percentage_discount" required>
                  <span class="form-error"></span>
                </div>

                <input type="submit" value="Submit" class="btn btn-primary">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="goBackFromOpenAddNewPartPaymentDiscountModal(this,event)">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div id="add-new-part-payment-discount-btn" onclick="addNewPartPaymentDiscount(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Part Fee Paying Percentage Discount" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
         <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
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

      $("#edit-part-payment-discount-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_part_paying_percentage'); ?>";
        var values = me.serializeArray();
        var id = me.attr("data-id");
        
        values = values.concat({
          "name" : "id",
          "value" : id
        })
        console.log(values)
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
            if(response.success){
              $.notify({
              message:"Part Fee Paying Percentage Discount Edited Succesfully"
              },{
                type : "success"  
              });

              setTimeout(function () {
                document.location.reload();
              },1500);
              
            }else if(response.invalid_id){
              swal({
                title: 'Error',
                text: "Something Went Wrong. Try Again",
                type: 'error'
              })
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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

      $("#add-new-part-payment-discount-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_new_part_paying_percentage'); ?>";
        var values = me.serializeArray();
        
        // console.log(values)
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
            if(response.success){
              $.notify({
              message:"Part Fee Paying Percentage Discount Added Succesfully"
              },{
                type : "success"  
              });

              setTimeout(function () {
                document.location.reload();
              },1500);
              
            }else if(response.already_used){
              swal({
                title: 'Error',
                text: "This Code Has Already Been Used In This Facility. Enter Another One.",
                type: 'error'
              })
            }else if(response.invalid_code){
              swal({
                title: 'Error',
                text: "This Code Has Does Not Match Any Part Fee Paying Registration Code Previously Entered.",
                type: 'error'
              })
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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

      $("#unused-lab-to-lab-code-info-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var id= me.attr("data-id");

        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_lab_to_lab_code_edit'); ?>";
        var values = me.serializeArray();
        values = values.concat({
          "name" : "id",
          "value" : id
        })
        
        console.log(values)
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
            if(response.success == true){
              $.notify({
              message:"Edited Succesfully"
              },{
                type : "success"  
              });
              
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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

      $("#input-new-lab-to-lab-codes-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_lab_to_lab_referral_code'); ?>";
        var values = me.serializeArray();

        
        console.log(values)
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
            if(response.success == true){
              $.notify({
              message:"Code Added Succesfully"
              },{
                type : "success"  
              });
              setTimeout(goDefault, 1000);
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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

      $("#edit-code-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var id= me.attr("data-id");

        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_pfp_nfp_verification_code_edit'); ?>";
        var values = me.serializeArray();
        values = values.concat({
          "name" : "id",
          "value" : id
        })
        
        console.log(values)
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
            if(response.success == true){
              $.notify({
              message:"Edited Succesfully"
              },{
                type : "success"  
              });
              
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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

      $("#input-code-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_pfp_nfp_verification_code'); ?>";
        var values = me.serializeArray();

        
        console.log(values)
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
            if(response.success == true){
              $.notify({
              message:"Code Added Succesfully"
              },{
                type : "success"  
              });
              setTimeout(goDefault, 1000);
              
            }
            else{
             $.each(response.messages, function (key,value) {

              var element =  me.find('#'+key);
              
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
