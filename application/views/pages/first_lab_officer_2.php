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

  .spinner{
    position: absolute; 
    right: 25px; 
    top: 13px;
    width: 20px;
    height: 20px;
    display: none;
  }
</style>
<script>
  var tests_selected_obj = [];
  var patient_facility_id = "";
  var additional_patient_test_info = [];
  var balance = 0;
  var sub_total_balance = 0;
 
  function copyText(text) {
    /* Get the text field */
    var elem = document.createElement("textarea");
    elem.value = text;
    document.body.append(elem);

    /* Select the text field */
    elem.select();
    /* Copy the text inside the text field */
    if(document.execCommand("copy")){
      $.notify({
      message:"Copied!"
      },{
        type : "success"  
      });
    }

    document.body.removeChild(elem);

    /* Alert the copied text */
  }
  
  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  
  function reloadPage() {
    document.location.reload();
  }
  
  function goDefault() {
    
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

  function collectPayment (elem,evt) {
    $("#input-patient-code-modal").modal("show");
  }
    

  function loadPatientInitiationCodeTable(initiation_code,type) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_tests_initiation_code_teller'); ?>"
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

   function goBackInitiationCodeCard(elem,evt){
      $("#initiation-code-card").hide("fast");
      $("#initiation-codes-card").show("fast");
    }

    function goBackInitiationCodeCard1(elem,evt){
      $("#initiation-code-card").hide("fast");
      $("#input-patient-code-modal").modal("show");
      $("#main-card").show("fast");
    }

    function submitAddPartPaymentPercentageForm (elem,evt) {

      elem = $(elem);
      evt.preventDefault();
      var val = elem.find('#part_payment_percentage').val();
      
      var initiation_code = elem.attr("data-initiation-code");
      if(initiation_code != ""){
        if(!isNaN(val)){
          if(val <= 100){
            if(val > 0){
              swal({
                title: 'Proceed?',
                text: "Are You Really Sure You Want To Proceed?<br> <b>Note:</b> This Value Cannot Be Changed For This Transaction.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#4caf50',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
              }).then(function(){
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_part_payment_percentage_for_patients_initiated_tests_teller'); ?>"
                $(".spinner-overlay").show();
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "initiation_code="+initiation_code + "&val="+val,
                  success : function(response){
                    console.log(response)
                    $(".spinner-overlay").hide();
                    if(response.success){
                      $.notify({
                      message:"Patient Part Fee Paying Percentage Discount Entered Succesfully"
                      },{
                        type : "success"  
                      });
                      loadPatientInitiationCodeTable(initiation_code,2)
                    }else if(response.not_valid_number){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Be A Valid Number'
                      })
                    }else if(response.number_greater_than_hundred){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Not Be Greater Than 100%'
                      })
                    }else if(response.negative_number){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Not Be A Negative Number'
                      })
                    }else if(response.initiation_code_invalid){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Initiation Code Is Valid'
                      })
                    }else if(response.patient_not_in_your_facility){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Patient Is No Longer In Your Facility'
                      })
                    }else if(response.part_payment_percent_already_set){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'The Part Payment Percentage Has Already Been Set By ' + response.part_payment_percentage_val
                      })
                    }else if(response.patient_is_not_part_payment){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Patient Is Not Registered As A Part Paying Patient'
                      })
                    }else{
                      swal({
                        type: 'error',
                        title: 'Oops.....',
                        text: 'Sorry, something went wrong. Please try again!'
                        // footer: '<a href>Why do I have this issue?</a>'
                      })
                    }
                  },
                  error : function () {
                    swal({
                      type: 'error',
                      title: 'Oops.....',
                      text: 'Sorry, something went wrong. Please Check Your Internet Connection try again!'
                      // footer: '<a href>Why do I have this issue?</a>'
                    })
                  }
                }); 
              });   
            }else{
              swal({
                type: 'error',
                title: 'Error!',
                text: 'Percentage Value Entered Must Not Be A Negative Number'
              })
            }
          }else{
            swal({
              type: 'error',
              title: 'Error!',
              text: 'Percentage Value Entered Must Not Be Greater Than 100%'
            })
          }
        }else{
          swal({
            type: 'error',
            title: 'Error!',
            text: 'Percentage Value Entered Must Be A Valid Number'
          })
        }
      }
    }

    function openEnterDiscountForm (elem,evt) {
      $("#choose-action-add-discount-div").hide("slow");
      $("#add-discount-form").show("slow");
    }


    function openEnterAmountPaidForm (elem,evt) {
      $("#choose-action-add-discount-div").hide("slow");
      $("#make-payments-form").show("slow");
    }

    function gobackFromAddDiscountForm (elem,evt) {
      $("#choose-action-add-discount-div").show("slow");
      $("#add-discount-form").hide("slow"); 
    }

    function gobackFromMakePaymentsForm (elem,evt) {
      $("#choose-action-add-discount-div").show("slow");
      $("#make-payments-form").hide("slow");
    }

    function submitDiscountPercentageForm (elem,evt) {

      elem = $(elem);
      evt.preventDefault();
      var val = elem.find('#discount_percentage').val();
      
      var initiation_code = elem.attr("data-initiation-code");
      if(initiation_code != ""){
        if(!isNaN(val)){
          if(val <= 100){
            if(val > 0){
              swal({
                title: 'Proceed?',
                text: "Are You Really Sure You Want To Proceed?<br> <b>Note:</b> This Value Cannot Be Changed For This Transaction.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#4caf50',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
              }).then(function(){
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_discount_percentage_for_patients_initiated_tests_teller'); ?>"
                $(".spinner-overlay").show();
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "initiation_code="+initiation_code + "&val="+val,
                  success : function(response){
                    console.log(response)
                    $(".spinner-overlay").hide();
                    if(response.success){
                      $.notify({
                      message:"Percentage Discount Set Succesfully"
                      },{
                        type : "success"  
                      });
                      loadPatientInitiationCodeTable(initiation_code,2)
                    }else if(response.not_valid_number){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Be A Valid Number'
                      })
                    }else if(response.number_greater_than_hundred){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Not Be Greater Than 100%'
                      })
                    }else if(response.negative_number){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Percentage Value Entered Must Not Be A Negative Number'
                      })
                    }else if(response.initiation_code_invalid){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Initiation Code Is Valid'
                      })
                    }else if(response.patient_not_in_your_facility){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Patient Is No Longer In Your Facility'
                      })
                    }else if(response.discount_percent_already_set){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'The Discount Percentage Has Already Been Set By ' + response.discount_percentage_val
                      })
                    }else if(response.patient_is_none_fee_paying){
                      swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'This Patient Is Registered As A None Fee Paying Patient And Is Therefore Not Eligible For Discount'
                      })
                    }else{
                      swal({
                        type: 'error',
                        title: 'Oops.....',
                        text: 'Sorry, something went wrong. Please try again!'
                        // footer: '<a href>Why do I have this issue?</a>'
                      })
                    }
                  },
                  error : function () {
                    $(".spinner-overlay").hide();
                    swal({
                      type: 'error',
                      title: 'Oops.....',
                      text: 'Sorry, something went wrong. Please Check Your Internet Connection try again!'
                      // footer: '<a href>Why do I have this issue?</a>'
                    })
                  }
                }); 
              });   
            }else{
              swal({
                type: 'error',
                title: 'Error!',
                text: 'Percentage Value Entered Must Not Be A Negative Number'
              })
            }
          }else{
            swal({
              type: 'error',
              title: 'Error!',
              text: 'Percentage Value Entered Must Not Be Greater Than 100%'
            })
          }
        }else{
          swal({
            type: 'error',
            title: 'Error!',
            text: 'Percentage Value Entered Must Be A Valid Number'
          })
        }
      }
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
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_test_payment_form'); ?>";
      
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
                  
                  
                  console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
                  $(".spinner-overlay").hide();
                  var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_pdf_tests') ?>";
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
                      $(".spinner-overlay").show();
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/save_receipt') ?>";
                      // var pdf = btoa(doc.output());
                      $.ajax({
                        url : url,
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : pdf_data,
                        
                        success : function (response) {
                          $(".spinner-overlay").hide();
                          console.log(response)
                          if(response.success == true){
                            var pdf_url = "<?php echo base_url('assets/images/')?>" + "<?php echo $health_facility_slug; ?>" + "_" +pdf_data.receipt_number + "_" +lab_id + ".html";;
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
                    text: "This Patient Has Finished Payment For This Initiation. No Need To Pay Again",
                    type: 'warning',
                    
                  })
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

    function loadPreviousTransactions(elem) {
      $("#input-patient-code-modal").modal("hide");
      $("#main-card").hide();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_initiation_codes_teller'); ?>"
      
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

    function goBackInitiationCodesCard(elem,evt){
      $("#initiation-codes-card").hide();
      $("#input-patient-code-modal").modal("show");
      $("#main-card").show();
    }

    function collectPaymentReferrals (elem,evt) {
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_initiation_codes_teller_referrals_from_your_facility'); ?>"
      
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
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#unpaid-referrals-card .card-body").html(messages);
            $("#unpaid-referrals-card #unpaid-referrals-table").DataTable();
            $("#main-card").hide();
            $("#unpaid-referrals-card").show();
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }

        },
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
          },{
            type : "danger"  
          });
        }
      }); 
    }

    function goBackFromUnpaidReferralsCard (elem,evt) {
      $("#main-card").show();
      $("#unpaid-referrals-card").hide();
    }

    function loadUnpaidReferralTestInfo(elem,evt){
      elem = $(elem);
      var initiation_code = elem.attr("data-initiation-code");
      if(initiation_code != ""){
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_unpaid_tests_referral_payment_info'); ?>"
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
              var referring_facility_name = response.referring_facility_name;
              balance = response.balance;
              sub_total_balance = response.sub_total_balance;

              $("#unpaid-referral-info-card .card-title").html("Test Selected For " + patient_name + " From " +referring_facility_name)
              $("#unpaid-referral-info-card .card-body").html(response.messages)
              $("#unpaid-referral-info-card #unpaid-referral-tests-table").DataTable();
              $("#unpaid-referrals-card").hide();
              $("#unpaid-referral-info-card").show();
              $("#proceed-to-payment-btn").show("fast");
              $("#pay-with-referral-code-form").attr("data-initiation-code",initiation_code);
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
    }

    function goBackFromUnpaidReferralInfoCard (elem,evt) {
      $("#unpaid-referrals-card").show();
      $("#unpaid-referral-info-card").hide();
      $("#proceed-to-payment-btn").hide("fast");
    }

    function proceedToPaymentBtn (elem,evt) {
      swal({
        title: 'Choose Payment Option: ',
        text: "Do You Want To Pay By",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#4caf50',
        confirmButtonText: 'Entering Referral Payment Code',
        cancelButtonText: 'Online Payment'
      }).then(function(){
        $("#pay-with-referral-code-modal").modal("show");
      }, function(dismiss){
        if(dismiss == 'cancel'){
          swal({
            title: 'Warning',
            text: "Note That Extra Charge Of 7% VAT Occurs When Using Online Payment." +
            "<p class='text-primary' id='num-tests-para'>Amount For Tests: ₦" + addCommas(balance) +".</p>" +
            "<p class='text-primary'>Vat: 7%</p>" +
            "<p class='text-primary'>Sub Total: ₦"+addCommas(sub_total_balance)+"</p>" +
           " Do Want To Continue To Payment?",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes Proceed',
            cancelButtonText: 'No'
          }).then(function(){
            var initiation_code = $("#pay-with-referral-code-form").attr("data-initiation-code");
            window.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/test_online_payment_referral/') ?>"+initiation_code);
          });  
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
            
          ?>
          <?php
           if($user_position == "admin"){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  }?>
           
          <h3 class="text-center" style="text-transform: capitalize;"><?php echo $personnel_name; ?></h3>
          <?php if($user_position == "admin"){ ?>
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

              <div class="card" id="unpaid-referral-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Test Selected For From</h3>
                  <button class='btn btn-warning' onclick='goBackFromUnpaidReferralInfoCard(this,event)'>Go Back</button>
                </div>
                <div class="card-body">
                  
                  
                </div>
              </div>

              <div class="card" id="unpaid-referrals-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">All Referrals Awaiting Payment</h3>
                  <button class='btn btn-warning' onclick='goBackFromUnpaidReferralsCard(this,event)'>Go Back</button>
                </div>
                <div class="card-body">
                  
                  
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

              <div class="card" id="initiation-code-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button id='go-back' class='btn btn-warning' onclick=''>Go Back</button>
                  
                </div>
                <div class="card-body">
                
                </div>
              </div>


              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title">Choose Action: </h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
      
                    <table class="table table-hover" id="choose-action-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="collectPayment(this,event)" class="text-primary">Collect Payment</td>
                        </tr>

                        <tr>
                          <td>2</td>
                          <td onclick="collectPaymentReferrals(this,event)" class="text-primary">Collect Payment For Referrals</td>
                        </tr>
                        
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


              <div class="card" id="sub-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackFromSelectSubTestsCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="patient-list-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Registered Patients</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 style="text-transform: capitalize;" class="welcome-heading card-title"></h3>
                </div>
                <div class="card-body">
              
                </div> 
              </div> 
            </div>
          </div>
          
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="pay-with-referral-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Pay With Lab To Lab Referral Code</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <p><em class="text-primary">Enter A Referral Code From This Facility.</em></p>
              <?php $attributes = array('class' => '','id' => 'pay-with-referral-code-form') ?>
              <?php echo form_open('',$attributes); ?>
                <div class="form-group">
                  <label for="referral_code">Referral Code: </label>
                  <input type="text" id="referral_code" class="form-control" name="referral_code">
                  <span class="form-error"></span>
                </div>
                <input type="submit" class="btn btn-success" value="Submit" name="submit">
              <?php echo form_close(); ?>
            
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="input-patient-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body">
              <?php $attributes = array('class' => '','id' => 'input-patient-code-form') ?>
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
      
      <div class="modal fade" data-backdrop="static" id="perform-action-on-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Choose Action To Perform On: </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <div class="table-responsive">
      
                <table class="table table-hover" id="perform-action-on-patient-modal" cellspacing="0" width="100%" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Option</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td onclick="initiatePatient(this,event)" class="text-primary">Initiate Patient</td>
                    </tr>

                    <tr>
                      <td>2</td>
                      <td onclick="editPatientInfo(this,event)" class="text-primary">Edit Patient Info</td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td onclick="viewPatientsRecords(this,event)" class="text-primary">View Patient's Records</td>
                    </tr>
                    
                    
                  </tbody>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      
      <div id="proceed-to-payment-btn" onclick="proceedToPaymentBtn(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed To Payment For These Tests" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>

      <div id="proceed-from-additional-info-tests-selected-btn" onclick="proceedFromAdditionalTestsSelected(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>

      <div id="proceed-btn" onclick="proceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
        <?php
          $code_date = date("j");
          $code_time = date("h");
          $initiation_code = substr(bin2hex($this->encryption->create_key(8)),4). '-' . $code_date .'-' . $code_time;
        ?>
        <p id="var-dump" style="display: none;"><?php echo $initiation_code; ?></p>
      </footer>
  </div>
  
  
</body>
<script>
  $(document).ready(function() {
    $("#pay-with-referral-code-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
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
      var form_data = me.serializeArray();
      var initiation_code = me.attr("data-initiation-code");
      form_data = form_data.concat({
        "name" : "initiation_code",
        "value" : initiation_code
      })
      console.log(form_data)
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/pay_for_referral_test_with_code'); ?>";

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
            
            
            console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
            $(".spinner-overlay").hide();
            var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_pdf_tests_referral') ?>";
            $(".spinner-overlay").show();
            $.ajax({
              url : get_pdf_tests_url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "get_pdf_tests=true&initiation_code="+initiation_code,
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
                $(".spinner-overlay").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/save_receipt_referral') ?>";
                // var pdf = btoa(doc.output());
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : pdf_data,
                  
                  success : function (response) {
                    $(".spinner-overlay").hide();
                    console.log(response)
                    if(response.success == true){
                      var pdf_url = "<?php echo base_url('assets/images/')?>" + "<?php echo $health_facility_slug; ?>" + "_" +pdf_data.receipt_number + "_" +lab_id + ".html";;
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
          }else if(response.invalid_activity){
            swal({
              type: 'error',
              title: 'Error',
              text: 'Sorry Something Went Wrong'
            })
          }else if(response.invalid_code){
            swal({
              type: 'error',
              title: 'Error',
              text: 'Invalid Code Entered. Please Enter A Valid Code.'
            })
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
    })

    $("#input-patient-code-form").submit(function (evt) {
        evt.preventDefault();
        var patient_code = $("#patient-code").val();
        var get_code_data_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_initiated_patient_by_initiation_code_for_payment'); ?>";
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
            data : "show_records=true&initiation_code="+patient_code,
            success : function(response){
              $(".spinner-overlay").hide();
              if(response.success == true){
                $("#main-card").hide();
                $("#input-patient-code-modal").modal("hide")
                loadPatientInitiationCodeTable(patient_code,2);
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


    <?php if($this->session->internet_error){ ?>
      $.notify({
      message:"Something Went Wrong. Please Check Your Internet Connection And Try Again."
      },{
        type : "warning"  
      });
    <?php } ?>
  });
</script>
