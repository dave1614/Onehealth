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
            
          }else{
            $data_url_img = '<img src="'.base_url('assets/images/'.$health_facility_logo).'" style="display: none;" alt="" id="facility_img">';
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
        echo $data_url_img;
      ?>
<script> 
  function goBackDispatchDrugs(elem,evt){
    $("#main-card").show();
    $("#dispense-drugs-card").hide();
  }

  function dispatchDrugs (elem,evt) {
    $("#main-card").hide();
    $("#dispense-drugs-card").show();
  }

  function goBackOutstandingPayments (elem,evt) {
    $("#collect-payment-card").show();
   
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
            $("#mark-drugs-paid-btn").show("fast");
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

  function goBackPaymentHistoryOTC (elem,evt) {
    $("#collect-payment-card").show();
    $("#payment-history-otc-card").hide();
  }

  function goBackAwaitingDispense (elem,evt) {
    $("#dispense-drugs-card").show();
    $("#all-awaiting-dispense-card").hide();
  }

  function goBackAwaitingDispenseInfo (argument) {
    $("#all-awaiting-dispense-card").show();
    $("#all-awaiting-dispense-card-info").hide();
  }

  function loadYetToBeDispPatientInfo(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card").hide();
              $("#all-awaiting-dispense-card-info").show();
              $("#all-awaiting-dispense-card-info .card-body").html(messages);
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

  function goBackAwaitingDispenseInfoClinic (elem,evt) {
    $("#all-awaiting-dispense-card-clinic").show();
    $("#all-awaiting-dispense-card-info-clinic").hide();
    
  }

  function loadYetToBeDispPatientInfoClinic(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card-clinic").hide();
              $("#all-awaiting-dispense-card-info-clinic").show();
              $("#all-awaiting-dispense-card-info-clinic .card-body").html(messages);
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

  function dispensePatientDrugs(elem,evt,id){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != "" && id != ""){
      swal({
        title: 'Choose Action',
        text: "You Are About To Mark This Drug As Dispensed. Are You Sure You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText : "No"
      }).then(function(){
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_patient_as_dipensed_otc_patients_pharmacy'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code+"&id="+id,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true){
              $(elem).hide();
              $.notify({
              message:"Successfully Marked As Dispensed"
              },{
                type : "success"  
              });
            }else if(response.already_dispensed){
              swal({
                title: 'Ooops!',
                text: "Sorry This Drug Has Already Been Dispensed",
                type: 'error'
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

  function dispatchPatientDrugs(elem,evt,id){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != "" && id != ""){
      swal({
        title: 'Choose Action',
        text: "You Are About To Mark This Patient's Drugs As Dispatched. Are You Sure You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText : "No"
      }).then(function(){
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/mark_patient_as_dispatched_otc_patients_pharmacy'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code+"&id="+id,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success == true){
              $.notify({
              message:"Drug Successfully Marked As Dispatched"
              },{
                type : "success"  
              });
              $(elem).before('<span class="text-success" style="font-style:italic;">Dispatched!</span>');
              $(elem).hide();
            }else if(response.already_dispatched){
              swal({
                title: 'Ooops!',
                text: "Sorry This Drug Has Already Been Dispatched",
                type: 'error'
              })
            }else if(response.not_dispensed){
              swal({
                title: 'Ooops!',
                text: "Sorry This Patient's Drugs Have Not Yet Been Dispensed. You Must Dispense Before Dispatching",
                type: 'error'
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

  function otcPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card .card-body").html(messages);
          $("#all-awaiting-dispense-card #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card").show();
          
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

  function goBackPoisonRegisterCard (elem,evt) {
    $("#main-card").show();
    $("#poison-register-card").hide();
  }

  function poisonRegister(elem,evt){
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_pharmacy_poison_register'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#main-card").hide();
          $("#poison-register-card .card-body").html(messages);
          $("#poison-register-card #poison-register-table").DataTable();
          $("#poison-register-card").show();
          
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

  function goBackAwaitingDispenseClinic (elem,evt) {
    $("#dispense-drugs-card").show();
    
    $("#all-awaiting-dispense-card-clinic").hide();
  }

  function clinicPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy_clinic'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card-clinic .card-body").html(messages);
          $("#all-awaiting-dispense-card-clinic #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card-clinic").show();
          
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

  function wardPatients(elem,evt){
    
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_patients_for_dipensing_otc_patients_pharmacy_ward'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#dispense-drugs-card").hide();
          $("#all-awaiting-dispense-card-ward .card-body").html(messages);
          $("#all-awaiting-dispense-card-ward #all-awaiting-dispense-table").DataTable();
          $("#all-awaiting-dispense-card-ward").show();
          
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

  function goBackAwaitingDispenseWard (elem,evt) {
    $("#dispense-drugs-card").show();
    
    $("#all-awaiting-dispense-card-ward").hide();
  }

  function loadYetToBeDispPatientInfoWard(elem,evt,initiation_code){
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code != ""){
      $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/load_yet_to_be_disp_patients_drugs'); ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success && response.messages != ""){
              var messages = response.messages;
              $("#all-awaiting-dispense-card-ward").hide();
              $("#all-awaiting-dispense-card-info-ward").show();
              $("#all-awaiting-dispense-card-info-ward .card-body").html(messages);
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

  function goBackAwaitingDispenseInfoWard (elem,evt) {
    $("#all-awaiting-dispense-card-ward").show();
    $("#all-awaiting-dispense-card-info-ward").hide();
    
  }


  function errorRegister (elem,evt) {
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "get_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#main-card").hide();
          $("#error-register-card .card-body").html(messages)
          $("#error-register-card").show();
          $("#add-new-error-btn").show("fast");
          $("#error-register-table").DataTable();
        }
      },error : function () {
        $(".spinner-overlay").hide();
        swal({
         title: 'Ooops',
          text: "Something Went Wrong",
          type: 'error'
        })
      }
    });    
  }

  function goBackErrorRegister (elem,evt) {
    $("#main-card").show();
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
  }

  function addNewError (elem,evt) {
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
    $("#add-new-error-card").show();
  }

  function goBackAddNewErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-card").hide();
    $("#add-new-error-btn").show("fast");

  }

  function loadErrorRegisterInfo(elem,evt,id){
    if(id !== ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register_info_for_edit'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "get_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#error-register-card").hide();
            $("#add-new-error-btn").hide("fast");
            $("#edit-new-error-form").attr("data-id",id);
            var event = response.messages.event;
            var action = response.messages.action;
            var remedied = response.messages.remedied;

            $("#edit-new-error-card #event").val(event);
            $("#edit-new-error-card #action").val(action);
            if(remedied == 1){
              $("#edit-new-error-card #yes").prop("checked",true);
            }else{
              $("#edit-new-error-card #yes").prop("checked",false);
            }
            
            $("#edit-new-error-card").show();
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        }
      });   
    }
  }

  function goBackEditErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-btn").show("fast");
    
    $("#edit-new-error-card").hide();
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
              
              <div class="card" id="error-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Error Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="edit-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit Data</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'edit-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="add-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Data To Error / Occurrence Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Your Functions</h3>
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  
                  <div class="table-responsive">
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
                          <td onclick="dispatchDrugs(this,event)">Dispense / Dispatch Drugs</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="errorRegister(this,event)" style="text-transform: capitalize;">View & Update error/occurrence register</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="poisonRegister(this,event)" style="text-transform: capitalize;">View Poison register</td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div> 
                </div>
              </div>

              <div class="card" id="dispense-drugs-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackDispatchDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Dispense / Dispatch For: </h4>
                  <button onclick="wardPatients(this,event)" class="btn btn-primary">Ward Patients</button>
                  <button onclick="clinicPatients(this,event)" class="btn btn-info">Clinic Patients</button>
                  <button onclick="otcPatients(this,event)" class="btn btn-success">Normal Patients</button>
                </div>
              </div>

              <div class="card" id="poison-register-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPoisonRegisterCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Poison Register</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispense(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispensing / Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfo(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info-clinic" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfoClinic(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispense-card-info-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfoWard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-awaiting-dispatch-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Patients With Drugs Awaiting Dispatching: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispatch(this,event)">Go Back</button>
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

              <div class="card" id="make-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Make Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMakePayment(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>
            </div>
          </div>

          <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

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
    $("#edit-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      var id = me.attr("data-id");
      form_data = form_data.concat({
        "name" : "id",
        "value" : id
      })
      console.log(form_data);
      if(id !== ""){
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success){
              $.notify({
              message:"This Register Has Been Edited Successfully"
              },{
                type : "success"  
              });
            }else if(response.expired){
              $.notify({
              message:"Date Entered Must Be In The In The Future"
              },{
                type : "warning"  
              });
            }else{
              $.each(response.error_messages, function (key,value) {

              var element = $('#edit-new-error-form #'+key);
              
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
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
             title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        }); 
      }
    })
    
    $("#add-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      console.log(form_data);
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : form_data,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success){
            document.location.reload()
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#add-new-error-form #'+key);
            
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
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    })

    <?php if($this->session->prescription_success){ ?>
     $.notify({
      message:"Prescription Made Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->error_register_data_entered){ ?>
     $.notify({
      message:"Data Has Been Added To The Error / Occurrence Register Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>
  });
</script>
