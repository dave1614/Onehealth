<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>
      <!-- End Navbar -->
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
            
          }else{
            $data_url_img = '<img src="'.base_url('assets/images/'.$health_facility_logo).'" style="display: none;" alt="" id="facility_img">';
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
        echo $data_url_img;
      ?>
<script> 

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
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_drugs_as_paid'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+initiation_code+"&amount="+amount,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success){
                var receipt_file = response.receipt_file;
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var discount = response.discount;
                var drugs = response.drugs;
                var sum = response.sum;
                var balance = response.balance;
                var part_fee_paying_percentage_discount = response.part_fee_paying_percentage_discount;
                
                var pdf_data = {
                  'logo' : company_logo,
                  'color' : <?php echo $color; ?>,
                  'sum' : sum,
                  "patient_name" : patient_name,
                  "receipt_number" : receipt_number,
                  "facility_state" : facility_state,
                  'facility_id' : "<?php echo $health_facility_id; ?>",
                  "facility_country" : facility_country,
                  "facility_name" : facility_name,
                  
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true,
                  'drugs' : drugs,
                  'discount' : discount,
                  "balance" : balance,
                  "part_fee_paying_percentage_discount" : part_fee_paying_percentage_discount
                };

                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_pharmacy') ?>";
                $(".spinner-overlay").show();
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : pdf_data,
                  
                  success : function (response) {
                    console.log(response)
                    $(".spinner-overlay").hide();
                    if(response.success == true){
                      var pdf_url = "<?php echo base_url('assets/images/'); ?>" + receipt_file;
                      window.location.assign(pdf_url);
                    }else{
                      console.log('false')
                    }
                  },
                  error : function () {
                    $(".spinner-overlay").hide();
                    
                  }
                })
                
              }else if(response.no_balance){
                swal({
                  title: 'Ooops!',
                  text: "You Do Not Have Any Balance On These Selected Drugs",
                  type: 'error'
                })
              }else if(response.excess){
                swal({
                  title: 'Ooops!',
                  text: "You Cannot Exceed The Balance For These Drugs",
                  type: 'error'
                })
              }else{
                swal({
                  title: 'Ooops!',
                  text: "Sorry Something Went Wrong. Please Try Again",
                  type: 'error'
                  
                })
              }
            },error :function () {
              $(".spinner-overlay").hide();
               swal({
                title: 'Error!',
                text: "Sorry Something Went Wrong",
                type: 'error'
                
              })
            }
          });
        });
      }
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
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_discount_percentage_for_patients_initiated_drugs_teller'); ?>"
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
                    loadOutstandingPaymentPatientInfo(this,event,initiation_code)
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
                  }else if(response.discount_percent_already_set){
                    swal({
                      type: 'error',
                      title: 'Error!',
                      text: 'The Discount Percentage Has Already Been Set'
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


  function goCollectPayment(elem,evt){
    $("#main-card").show();
    $("#collect-payment-card").hide();
  }

  function collectPayment (elem,evt) {
    $("#main-card").hide();
    $("#collect-payment-card").show();
  }

  function goBackOutstandingPayments (elem,evt) {
    $("#main-card").show();
   
    $("#outstanding-payments-card").hide();
  }

  function goBackMakePayment (elem,evt) {
    $("#outstanding-payments-card").show();
    
    $("#make-payment-card").hide();
  }

  function markDrugsPaid (elem,evt) {
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

    var num = $("#make-payment-card #discount").attr("data-num");
    var discount = $("#make-payment-card #discount").val();
    var total_price = $("#make-payment-card #discount").attr("data-total-price");
    var initiation_code =  $("#make-payment-card #discount").attr("data-initiation-code");

    var text = "";

    console.log(num + " : " + discount + " : " + total_price)
    if(!isNaN(discount) && !isNaN(total_price) && num != 0){
      
      if(discount != ""){
        if(discount > 0 && discount <= 100){
          if(discount != 0){
            total_price = total_price - (( discount / 100 ) * total_price);
            text = "You Are About To Mark " + num + " Drugs With Discount Of <span class='text-primary' style='font-style: italic;'>" + discount + "% </span> Which Is Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
          }
        }else{
          $.notify({
          message:"A Value Between 1 And 100 Must Be Entered In The Discount Field"
          },{
            type : "warning"  
          });
        } 
      }else{
        discount = "none";
        text = "You Are About To Mark " + num + " Drugs With Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
      } 

      if(text != "" && initiation_code != ""){
        swal({
          title: 'Warning',
          text: text,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_drugs_as_paid'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+initiation_code+"&discount="+discount,
            success : function (response) {
              
              if(response.success == true ){

                var receipt_file = response.receipt_file;
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var discount = response.discount;
                var drugs = response.drugs;
                var sum = response.sum;
                
                var pdf_data = {
                  'logo' : company_logo,
                  'color' : <?php echo $color; ?>,
                  'sum' : sum,
                  "patient_name" : patient_name,
                  "receipt_number" : receipt_number,
                  "facility_state" : facility_state,
                  'facility_id' : "<?php echo $health_facility_id; ?>",
                  "facility_country" : facility_country,
                  "facility_name" : facility_name,
                  
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true,
                  'drugs' : drugs,
                  'discount' : discount
                };
                $(".spinner-overlay").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_pharmacy') ?>";
                // var pdf = btoa(doc.output());
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : pdf_data,
                  
                  success : function (response) {
                    console.log(response)
                    $(".spinner-overlay").hide();
                    if(response.success == true){
                      var pdf_url = "<?php echo base_url('assets/images/'); ?>" + receipt_file;
                      window.location.assign(pdf_url);
                    }else{
                      console.log('false')
                    }
                  },
                  error : function () {
                    $(".spinner-overlay").hide();
                    
                  }
                })
                
              }else{
                swal({
                  title: 'Ooops!',
                  text: "Sorry Something Went Wrong. Please Try Again",
                  type: 'warning'
                  
                })
              }
            },error :function () {
              $(".spinner-overlay").hide();
               swal({
                title: 'Error!',
                text: "Sorry Something Went Wrong",
                type: 'error'
                
              })
            }
          });
        });
      }
    }
  }

  function markDrugsPaidClinic (elem,evt) {
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

    var num = $("#make-payment-card-clinic #discount").attr("data-num");
    var discount = $("#make-payment-card-clinic #discount").val();
    var total_price = $("#make-payment-card-clinic #discount").attr("data-total-price");
    var initiation_code =  $("#make-payment-card-clinic #discount").attr("data-initiation-code");

    var text = "";

    console.log(num + " : " + discount + " : " + total_price)
    if(!isNaN(discount) && !isNaN(total_price) && num != 0){
      
      if(discount != ""){
        if(discount > 0 && discount <= 100){
          if(discount != 0){
            total_price = total_price - (( discount / 100 ) * total_price);
            text = "You Are About To Mark " + num + " Drugs With Discount Of <span class='text-primary' style='font-style: italic;'>" + discount + "% </span> Which Is Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
          }
        }else{
          $.notify({
          message:"A Value Between 1 And 100 Must Be Entered In The Discount Field"
          },{
            type : "warning"  
          });
        } 
      }else{
        discount = "none";
        text = "You Are About To Mark " + num + " Drugs With Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
      } 

      if(text != "" && initiation_code != ""){
        swal({
          title: 'Warning',
          text: text,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          $(".spinner-overlay").show();
          
        });
      }
    }
  }

  function markDrugsPaidWard (elem,evt) {
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

    var num = $("#make-payment-card-ward #discount").attr("data-num");
    var discount = $("#make-payment-card-ward #discount").val();
    var total_price = $("#make-payment-card-ward #discount").attr("data-total-price");
    var initiation_code =  $("#make-payment-card-ward #discount").attr("data-initiation-code");

    var text = "";

    console.log(num + " : " + discount + " : " + total_price)
    if(!isNaN(discount) && !isNaN(total_price) && num != 0){
      
      if(discount != ""){
        if(discount > 0 && discount <= 100){
          if(discount != 0){
            total_price = total_price - (( discount / 100 ) * total_price);
            text = "You Are About To Mark " + num + " Drugs With Discount Of <span class='text-primary' style='font-style: italic;'>" + discount + "% </span> Which Is Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
          }
        }else{
          $.notify({
          message:"A Value Between 1 And 100 Must Be Entered In The Discount Field"
          },{
            type : "warning"  
          });
        } 
      }else{
        discount = "none";
        text = "You Are About To Mark " + num + " Drugs With Total Sum Of <span class='text-primary' style='font-style: italic;'>" +addCommas(total_price) + "</span> . Are You Sure You Want To Proceed?";
      } 

      if(text != "" && initiation_code != ""){
        swal({
          title: 'Warning',
          text: text,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_drugs_as_paid'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "initiation_code="+initiation_code+"&discount="+discount,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success == true ){

                var receipt_file = response.receipt_file;
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var discount = response.discount;
                var drugs = response.drugs;
                var sum = response.sum;
                
                var pdf_data = {
                  'logo' : company_logo,
                  'color' : <?php echo $color; ?>,
                  'sum' : sum,
                  "patient_name" : patient_name,
                  "receipt_number" : receipt_number,
                  "facility_state" : facility_state,
                  'facility_id' : "<?php echo $health_facility_id; ?>",
                  "facility_country" : facility_country,
                  "facility_name" : facility_name,
                  
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true,
                  'drugs' : drugs,
                  'discount' : discount
                };

                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_clinic') ?>";
                // var pdf = btoa(doc.output());
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : pdf_data,
                  
                  success : function (response) {
                    console.log(response)
                    $(".spinner-overlay").hide();
                    if(response.success == true){
                      var pdf_url = "<?php echo base_url('assets/images/'); ?>" + receipt_file;
                      window.location.assign(pdf_url);
                    }else{
                      console.log('false')
                    }
                  },
                  error : function () {
                    $(".spinner-overlay").hide();
                    
                  }
                })
                
              }else{
                swal({
                  title: 'Ooops!',
                  text: "Sorry Something Went Wrong. Please Try Again",
                  type: 'warning'
                  
                })
              }
            },error :function () {
              $(".spinner-overlay").hide();
               swal({
                title: 'Error!',
                text: "Sorry Something Went Wrong",
                type: 'error'
                
              })
            }
          });
        });
      }
    }
  }

  function loadOutstandingPaymentPatientInfo(elem,evt,initiation_code){
    if(initiation_code != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_outstanding_payment_patient_info_pharmacy'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#outstanding-payments-card").hide();
            $("#make-payment-card .card-body").html(messages);
            $("#make-payment-card table").DataTable();
            $("#make-payment-card").show();
            
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function goBackMakePaymentClinic (elem,evt) {
    $("#outstanding-payments-card-clinic").show();
    
    $("#mark-drugs-paid-btn-clinic").hide("fast");
    $("#make-payment-card-clinic").hide();
  }

  function loadOutstandingPaymentPatientInfoClinic(elem,evt,initiation_code){
    if(initiation_code != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_outstanding_payment_patient_info_pharmacy_clinic'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#outstanding-payments-card-clinic").hide();
            $("#make-payment-card-clinic .card-body").html(messages);
            $("#mark-drugs-paid-btn-clinic").show("fast");
            $("#make-payment-card-clinic").show();
            
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function goBackPaymentHistoryOTC (elem,evt) {
    $("#collect-payment-card").show();
    $("#payment-history-otc-card").hide();
  }

  function otcPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_payment_otc_patients_pharmacy'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#main-card").hide();
          $("#outstanding-payments-card .card-body").html(messages);
          $("#outstanding-payments-card #outstanding-payments-table").DataTable();
          $("#outstanding-payments-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
    
    
  }

  function goBackOutstandingPaymentsClinic (elem,evt) {
    $("#main-card").show();
    
    $("#outstanding-payments-card-clinic").hide();
  }

  function goBackPaymentHistoryClinic (elem,evt) {
    $("#collect-payment-card").show();
    $("#payment-history-otc-card-clinic").hide();
  }

  function clinicPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_payment_clinic_patients_pharmacy'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#collect-payment-card").hide();
          $("#outstanding-payments-card-clinic .card-body").html(messages);
          $("#outstanding-payments-card-clinic #outstanding-payments-table").DataTable();
          $("#outstanding-payments-card-clinic").show();
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
          });
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
  }

  function goBackOutstandingPaymentsWard (elem,evt) {
    $("#collect-payment-card").show();
    $("#outstanding-payments-card-ward").hide();
  }

  function goBackMakePaymentWard (elem,evt) {
    $("#outstanding-payments-card-ward").show();
    $("#mark-drugs-paid-btn-ward").hide("fast");
    $("#make-payment-card-ward").hide();
  }

  function loadOutstandingPaymentPatientInfoWard(elem,evt,initiation_code){
    if(initiation_code != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_outstanding_payment_patient_info_pharmacy_clinic'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#outstanding-payments-card-ward").hide();
            $("#make-payment-card-ward .card-body").html(messages);
            $("#mark-drugs-paid-btn-ward").show("fast");
            $("#make-payment-card-ward").show();
            
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function goBackPaymentHistoryWard (elem,evt) {
    $("#collect-payment-card").show();
    
    $("#payment-history-otc-card-ward").hide();
  }

  function wardPatients(elem,evt){
    swal({
      title: 'Choose Action',
      text: "Do You Want To?",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'View Outstanding Payments',
      cancelButtonText : "View Payment History"
    }).then(function(){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_payment_ward_patients_pharmacy'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "",
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#collect-payment-card").hide();
            $("#outstanding-payments-card-ward .card-body").html(messages);
            $("#outstanding-payments-card-ward #outstanding-payments-table").DataTable();
            $("#outstanding-payments-card-ward").show();
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
            });
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_payment_history_otc_patients_pharmacy_ward'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "",
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages != ""){
              var messages = response.messages;
              $("#collect-payment-card").hide();
              $("#payment-history-otc-card-ward .card-body").html(messages);
              $("#payment-history-otc-card-ward #payment-history-table").DataTable();
              $("#payment-history-otc-card-ward").show();
              
            }else{
              swal({
                title: 'Ooops!',
                text: "Sorry Something Went Wrong. Please Try Again",
                type: 'warning'
                
              })
            }
          },error :function () {
            $(".spinner-overlay").hide();
             swal({
              title: 'Error!',
              text: "Sorry Something Went Wrong",
              type: 'error'
              
            })
          }
        });
      }
    }); 
    
  }

</script>    
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
              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="otcPatients(this,event)" class="btn btn-primary">Collect Payment</button>
                </div>
              </div>

              <div class="card" id="collect-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goCollectPayment(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Collect Payment For: </h4>
                  <button onclick="wardPatients(this,event)" class="btn btn-primary">Ward Patients</button>
                  <button onclick="clinicPatients(this,event)" class="btn btn-info">Clinic Patients</button>
                  <button onclick="otcPatients(this,event)" class="btn btn-success">Normal Patients</button>
                </div>
              </div>

              <div class="card" id="outstanding-payments-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Awaiting Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOutstandingPayments(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="outstanding-payments-card-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Awaiting Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOutstandingPaymentsClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="outstanding-payments-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Awaiting Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOutstandingPaymentsWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="payment-history-otc-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Payment History: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPaymentHistoryOTC(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

               <div class="card" id="payment-history-otc-card-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Payment History: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPaymentHistoryClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="payment-history-otc-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Payment History: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPaymentHistoryWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="make-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Make Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMakePayment(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="make-payment-card-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Make Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMakePaymentClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="make-payment-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Make Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMakePaymentWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


            </div>
          </div>

          <div id="mark-drugs-paid-btn-ward" onclick="markDrugsPaidWard(this,event)" rel="tooltip" data-toggle="tooltip" title="Mark These Drugs As Paid" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

            </div>
          </div>
          
          <div id="mark-drugs-paid-btn-clinic" onclick="markDrugsPaidClinic(this,event)" rel="tooltip" data-toggle="tooltip" title="Mark These Drugs As Paid" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

            </div>
          </div>

          <div id="mark-drugs-paid-btn" onclick="markDrugsPaid(this,event)" rel="tooltip" data-toggle="tooltip" title="Mark These Drugs As Paid" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

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

    });



</script>
