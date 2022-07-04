<style>
  tr{
    cursor: pointer;
  }
  body {
    
  }
  .spinner{
    position: absolute; 
    right: 25px; 
    top: 13px;
    width: 20px;
      height: 20px;
      display: none;
  }

  .card-title{
    text-transform: capitalize;
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
        $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
       $data_url_img = "<img style='display:none;' id='facility_img' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$health_facility_name."' col='".$color."'>";
      }
    ?>
<script> 
  var clinic_base_url = "";
  var selected_clinic_name = "";

  function goBackOnAppointmentsBioDataCard (elem,evt) {
    $("#view-todays-appointments-card").show();
    $("#on-appointments-bio-data-card").hide();
  }
  
  function loadPatientBioOnApp (bio_id,record_id) {
    $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view-registered-patients-records-edit";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+bio_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          var id = messages.id;
          for (const [key, value] of Object.entries(messages)) {
            $("#on-appointments-bio-data-card #" +key).val(value);
            if(key == "sex" || key == "user_type"){
              $("#on-appointments-bio-data-card #" +value).prop("checked",true);
            }
          }
          var hospital_number = messages.hospital_number;
          var patient_user_name = messages.user_name;
          $("#view-todays-appointments-card").hide();
          $("#on-appointments-bio-data-card").show();
          $("#on-appointments-bio-data-card .hospital_number").val(hospital_number);
          $("#on-appointments-bio-data-card #on-appointments-bio-data-form").attr("data-id",record_id);

        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function referPatientForFurtherConsult(elem,evt,id,hospital_number,patient_name) {
    evt.preventDefault();
    swal({
      title: 'Warning?',
      text: "Are You Sure You Want To Push This Patient To Nurse As Off Appointment In "+selected_clinic_name+"?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      processPatientConsultationFee("off_appointment",id,hospital_number,patient_name);
    });
    
  }
  
  function submitRegistrationAmountForm (elem,evt,id,patient_name,hospital_number,receipt_number,receipt_file) {
    evt.preventDefault();
    // console.log(elem.serializeArray());
    var registration_amt = elem.querySelector("#registration_amt").value;
    swal({
      title: 'Warning?',
      text: "Do You Want To Pay "+ addCommas(registration_amt) + " For " + patient_name + "'s' As Registration Fee?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      
      console.log(registration_amt);
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
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/mark_patient_as_paid";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "registration_amt="+registration_amt + "&id="+id+"&receipt_file="+receipt_file,
        success : function (response) {
          console.log(response)
          
          if(response.success == true){
            var patient_name = response.patient_name;
            var receipt_number = response.receipt_number;
            var facility_state = '<?php echo $health_facility_state; ?>';
            var facility_country = '<?php echo $health_facility_country; ?>';
            var facility_address = response.facility_address;
            var facility_name = '<?php echo $health_facility_name; ?>';
            var date = response.date;
            var hospital_number = response.hospital_number;
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
              "hospital_number" : hospital_number,
              "facility_address" : facility_address,
              "date" : date,
              'mod' : 'teller',
              "receipt_file" : receipt_file,
              "clinic" : true
            };

            var url = clinic_base_url + "/save_receipt_clinic";
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
                  
                  swal({
                    title: 'Choose Action',
                    text: "Do You Want To: ",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Push ' + patient_name + ' To Nurse For Inputing Of Vital Signs',
                    cancelButtonText: 'Cancel'
                  }).then(function(){
                      $("#mark-paid-patients-modal").modal("hide");
                      processPatientConsultationFee("new_patient",id,hospital_number,patient_name);
                  }, function(dismiss){
                    if(dismiss == 'cancel'){
                      window.location.assign(pdf_url);
                    }
                  });
                }else{
                  console.log('false')
                }
              },
              error : function () {
                $(".spinner-overlay").hide();
                
              }
            })
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
    
  }
  
  function markAsPaid(elem,evt,id) {
    evt.preventDefault();
    var tr = elem.parentElement.parentElement;
    var patient_name = tr.getAttribute("data-name");
    var hospital_number = tr.getAttribute("data-hospital-num");
    var receipt_number = tr.getAttribute("data-receipt-number");
    var receipt_file = tr.getAttribute("data-receipt-file");
    $("#mark-paid-patients-modal .modal-title").html("Mark " + patient_name +" As Paid");
    document.querySelector("#mark-paid-patients-modal .modal-body #registration-amount-form").setAttribute(`onsubmit`,`submitRegistrationAmountForm(this,event,'${id}','${patient_name}','${hospital_number}','${receipt_number}','${receipt_file}')`);
    $("#mark-paid-patients-modal").modal("show");
  }

  function openTellerPage(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-unpaid'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#previously-registered-patients-card-unpaid .card-body").append(response.messages);

          $("#main-card").hide();
          $("#previously-registered-patients-card-unpaid").show();
          $("#registered-patients-unpaid-table").DataTable();
        }else if(response.nodata = true){
          swal({
            title: 'Sorry',
            text: "<p>You Have No Records To Display Here</p>",
            type: 'warning'
          }).then((result) => {
            // document.location.reload();
          })
        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goDefault() {
    
    document.location.reload();
  }

  function goBackEditBio() {
    $("#mark-this-patient-registration-as-paid-btn").hide("fast");
    $("#edit-patient-bio-data-card").hide();
    $("#previously-registered-patients-card").show();
  }

  function loadPatientBioDataEdit(id) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&record_id="+id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages.length != 0){
          var messages = response.messages;
          var id = messages.id;
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-patient-bio-data-card #" +key).val(value);
            if(key == "sex"){
              $("#edit-patient-bio-data-card #" +value).prop("checked",true);
            }
          }

          var hospital_number = messages.hospital_number;
          var patient_name = messages.firstname + " " + messages.lastname;
          var receipt_number = messages.receipt.receipt_number;
          var receipt_file_receipt = messages.receipt.receipt_file; 
          var receipt_file = messages.receipt_file; 

          if(messages.paid == 1){
            var receipt_file = "<?php echo base_url('assets/images/') ?>"+receipt_file
            $("#edit-patient-bio-data-card #go-back-edit-bio").after(`<button onclick="referPatientForFurtherConsult(this,event,'${id}','${hospital_number}','${patient_name}')" class="btn btn-info">Push Patient As Off Appointment In ${selected_clinic_name}</button> <p><a target="_blank" href="${receipt_file}">View Registration Payment Receipt</a></p>`);
          }else{
            $("#mark-this-patient-registration-as-paid-btn").attr("data-id",id);
            $("#mark-this-patient-registration-as-paid-btn").attr("data-hospital-num",hospital_number);
            $("#mark-this-patient-registration-as-paid-btn").attr("data-name",patient_name);
            $("#mark-this-patient-registration-as-paid-btn").attr("data-receipt-number",receipt_number);
            $("#mark-this-patient-registration-as-paid-btn").attr("data-receipt-file",receipt_file_receipt);
            $("#mark-this-patient-registration-as-paid-btn").show("fast");
          }
          
          $("#edit-bio-data-form").attr("data-id",id);
          $("#previously-registered-patients-card").hide();
          $("#edit-patient-bio-data-card").show();
          
        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function openPatientBioDataForm(elem,evt) {
    evt.preventDefault();
    $("#main-card").hide();
    $("#patient-bio-data").show();
  }

  function viewTodaysAppointments(elem,evt) {
    evt.preventDefault();
     $(".spinner-overlay").show();
          
    var url = clinic_base_url + "/view_on_appointments_clinic_records";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#view-todays-appointments-card .card-body").html(response.messages);
          $("#view-todays-appointments-card .card-title").html("Patients With Appointments Today In " +selected_clinic_name);
          $("#view-todays-appointments-card #registered-patients-table").DataTable();
          $("#main-card").hide();
          $("#view-todays-appointments-card").show();
          
        }
        else{
         $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackFromTodaysAppointmentsCard (elem,evt) {
   
    $("#main-card").show();
    $("#view-todays-appointments-card").hide();
  }

  function viewRegisteredPatients(elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#previously-registered-patients-card .card-body").append(response.messages);
          $("#main-card").hide();
          $("#previously-registered-patients-card").show();
          $("#registered-patients-table").DataTable();
        }
        else{
         $.notify({
          message:"No Data To Display"
          },{
            type : "danger"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "warning"  
        });
      }
    }); 
  }

  function goBackReferralsCard (elem,evt) {
    $("#main-card").show();
    $("#referrals-card").hide();
  }

  function viewReferralsOrConsults(elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action In ' +selected_clinic_name,
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Referrals',
      cancelButtonText: 'View Consults'
    }).then(function(){
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/view_referrals_to_your_clinic";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            $("#referrals-card .card-title").html("All Referrals In "+selected_clinic_name);
            $("#referrals-card .card-body").html(response.messages);
            $("#main-card").hide();
            $("#referrals-card").show();
            $("#referrals-card #referrals-table").DataTable();
          }
          else{
           $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
            
        var url = clinic_base_url + "/view_consults_to_your_clinic";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true",
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true){
              $("#referrals-card .card-title").html("All Consults In "+selected_clinic_name);
              $("#referrals-card .card-body").html(response.messages);
              $("#main-card").hide();
              $("#referrals-card").show();
              $("#referrals-card #referrals-table").DataTable();
            }
            else{
             $.notify({
              message:"No Record To Display"
              },{
                type : "warning"  
              });
            }
          },
          error: function (jqXHR,textStatus,errorThrown) {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
            },{
              type : "danger"  
            });
          }
        });
      }
    });
    
     
  }

  function goBackReferralCard (elem,evt) {
    $("#referrals-card").show();
    $("#referral-card").hide();
    $("#proceed-referral-to-nurse").hide("fast");
  }

  function proceedReferralToNurse (elem,evt) {
    var id = $(elem).attr("data-id");
    if(id != ""){
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/proceed_referral_to_nurse";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            document.location.reload();
          }
          else{
           $.notify({
            message:"Something Went Wrong"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function viewReferralInfo (elem,evt,id) {
    if(id != ""){
      $(".spinner-overlay").show();
          
      var url = clinic_base_url + "/view_referral_info";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            $("#referral-card .card-body").html(response.messages);
            $("#referrals-card").hide();
            $("#referral-card").show();
            $("#proceed-referral-to-nurse").attr("data-id",id);
            $("#proceed-referral-to-nurse").show("fast");
          }
          else{
           $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function viewReferralsAwaitingRegistration(elem,evt) {
    $(".spinner-overlay").show();
        
    var url = clinic_base_url + "/view_referrals_awaiting_registration_clinic";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#referrals-awaiting-registration-card .card-body").html(response.messages);
          $("#main-card").hide();
          $("#referrals-awaiting-registration-card").show();
          $("#referrals-awaiting-registration-card #referrals-table").DataTable();
        }
        else{
         $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackReferralsAwaitingRegistrationCard (elem,evt) {
    $("#main-card").show();
    $("#referrals-awaiting-registration-card").hide();
  }

  function registerReferredUser (elem,evt,id) {
    var patient_username = $(elem).attr("data-user-name");
    console.log(patient_username)
    
    $(".spinner-overlay").show();
        
    var url = clinic_base_url + "/get_patient_bio_data_info";
    
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
          for (const [key, value] of Object.entries(messages)) {
              $("#enter-bio-data-awaiting-registration-card #" +key).val(value);
              if(key == "sex"){
                $("#enter-bio-data-awaiting-registration-card #" +value).prop("checked",true);
              }
          }
          $("#enter-bio-data-awaiting-registration-form").attr("data-id",id);
          $("#enter-bio-data-awaiting-registration-card").show();
          $("#referrals-awaiting-registration-card").hide();
        }
        else{
         $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackFromEnterBioDataAwaitingRegistration (elem,evt) {
    $("#enter-bio-data-awaiting-registration-card").hide();
    $("#referrals-awaiting-registration-card").show();
  }

  function markThisPatientsRegistrationAsPaid (elem,evt) {
    evt.preventDefault();
    var id = $(elem).attr("data-id");
    var patient_name = $(elem).attr("data-name");
    var hospital_number = $(elem).attr("data-hospital-num");
    var receipt_number = $(elem).attr("data-receipt-number");
    var receipt_file = $(elem).attr("data-receipt-file");
    $("#mark-paid-patients-modal .modal-title").html("Mark " + patient_name +" As Paid");
    document.querySelector("#mark-paid-patients-modal .modal-body #registration-amount-form").setAttribute(`onsubmit`,`submitRegistrationAmountForm(this,event,'${id}','${patient_name}','${hospital_number}','${receipt_number}','${receipt_file}')`);
    $("#mark-paid-patients-modal").modal("show");
  }

  function processPatientConsultationFee(type,id,hospital_number,patient_name){
    
    if(type == "new_patient"){
      $("#consultation-fee-payment-modal .modal-title").html("Mark "+ patient_name +"'s Consultation Fee As Paid");
      $("#consultation-fee-payment-modal #consultation-fee-payment-form").attr("data-id",id);
      $("#consultation-fee-payment-modal #consultation-fee-payment-form").attr("data-type",type);
      $("#consultation-fee-payment-modal #consultation-fee-payment-form").attr("data-name",patient_name);
      $("#consultation-fee-payment-modal #consultation-fee-payment-form").attr("data-hospital-num",hospital_number);
      $("#consultation-fee-payment-modal").modal("show");
    }else if(type == "off_appointment"){
      $("#consultation-fee-payment-modal-off-appointment .modal-title").html("Mark "+ patient_name +"'s Consultation Fee As Paid");
      $("#consultation-fee-payment-modal-off-appointment #consultation-fee-payment-form-off-appointment").attr("data-id",id);
      $("#consultation-fee-payment-modal-off-appointment #consultation-fee-payment-form-off-appointment").attr("data-type",type);
      $("#consultation-fee-payment-modal-off-appointment #consultation-fee-payment-form-off-appointment").attr("data-name",patient_name);
      $("#consultation-fee-payment-modal-off-appointment #consultation-fee-payment-form-off-appointment").attr("data-hospital-num",hospital_number);
      $("#consultation-fee-payment-modal-off-appointment").modal("show");
    }
  }

  function userTypeChanged (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    if(val == "fp"){
      $("#enter-bio-data-form #code_div").hide();
    }else if(val == "pfp"){
      $("#enter-bio-data-form #code_div").show();
    }else if(val == "nfp"){
      $("#enter-bio-data-form #code_div").show();
    }
  }

  function codeEntered (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val)
    if($("#enter-bio-data-form #user_type #fp").prop("checked")){
      var user_type = "fp";
    }else if($("#enter-bio-data-form #user_type #pfp").prop("checked")){
      var user_type = "pfp";
    }else if($("#enter-bio-data-form #user_type #nfp").prop("checked")){
      var user_type = "nfp";
    }

    var firstname = $("#enter-bio-data-form #firstname").val();
    var lastname = $("#enter-bio-data-form #lastname").val();
    var spinner = $("#enter-bio-data-form .spinner");
    var code_status = $("#enter-bio-data-form #code_status");
    
    if(val != ""){
      spinner.show();
      elem.addClass('disabled');
      console.log(val + " : " + user_type + " : " + firstname + " : " + lastname)
        
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/check_if_code_entered_is_correct'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&val="+val+"&user_type="+user_type+"&firstname="+firstname+"&lastname="+lastname,
        success : function (response) {
          console.log(response)
          spinner.hide();
          elem.removeClass('disabled');

          var messages = response.messages;
          if(response.success == true){
            code_status.html("<em class='text-success'>"+messages+"</em>");
          }else{
            code_status.html("<em class='text-danger'>"+messages+"</em>");
          }
          
        },
        error: function (jqXHR,textStatus,errorThrown) {
          spinner.hide();
          elem.removeClass('disabled');
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function userTypeChanged1 (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    if(val == "fp"){
      $("#enter-bio-data-awaiting-registration-form #code_div").hide();
    }else if(val == "pfp"){
      $("#enter-bio-data-awaiting-registration-form #code_div").show();
    }else if(val == "nfp"){
      $("#enter-bio-data-awaiting-registration-form #code_div").show();
    }
  }

  function codeEntered1 (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val)
    if($("#enter-bio-data-awaiting-registration-form #user_type #fp").prop("checked")){
      var user_type = "fp";
    }else if($("#enter-bio-data-awaiting-registration-form #user_type #pfp").prop("checked")){
      var user_type = "pfp";
    }else if($("#enter-bio-data-awaiting-registration-form #user_type #nfp").prop("checked")){
      var user_type = "nfp";
    }

    var firstname = $("#enter-bio-data-awaiting-registration-form #firstname").val();
    var lastname = $("#enter-bio-data-awaiting-registration-form #lastname").val();
    var spinner = $("#enter-bio-data-awaiting-registration-form .spinner");
    var code_status = $("#enter-bio-data-awaiting-registration-form #code_status");
    
    if(val != ""){
      spinner.show();
      elem.addClass('disabled');
      console.log(val + " : " + user_type + " : " + firstname + " : " + lastname)
        
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/check_if_code_entered_is_correct'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&val="+val+"&user_type="+user_type+"&firstname="+firstname+"&lastname="+lastname,
        success : function (response) {
          console.log(response)
          spinner.hide();
          elem.removeClass('disabled');

          var messages = response.messages;
          if(response.success == true){
            code_status.html("<em class='text-success'>"+messages+"</em>");
          }else{
            code_status.html("<em class='text-danger'>"+messages+"</em>");
          }
          
        },
        error: function (jqXHR,textStatus,errorThrown) {
          spinner.hide();
          elem.removeClass('disabled');
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }
  }

  function performFunctions(elem,evt){
    $("#perform-functions-card").hide();
    $("#choose-clinic-to-perform-functions-card").show();
  }

  function goBackFromChooseClinicToPerformFunctionsCard(elem,evt){
    $("#perform-functions-card").show();
    $("#choose-clinic-to-perform-functions-card").hide();
  }

  function chooseThisClinicToPerformFunction(elem,evt,url,clinic_name){
    evt.preventDefault();
    clinic_base_url = url;
    selected_clinic_name = clinic_name;
    $("#main-card .card-title").html("Perform Action In " + clinic_name);
    $("#main-card").show();
    $("#choose-clinic-to-perform-functions-card").hide();
  }

  function goBackMainCard (elem,evt) {
    $("#main-card").hide();
    $("#choose-clinic-to-perform-functions-card").show();
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
            $user_position = $this->onehealth_model->getUserPosition($health_facility_table_name,$user_id);
            $personnel_info = $this->onehealth_model->getPersonnelBySlug($fourth_addition);
            if(is_array($personnel_info)){
              
              foreach($personnel_info as $personnel){
                $personnel_id = $personnel->id;
                $spersonnel_dept_name = $personnel->name;
                $personnel_slug = $personnel->slug;
                $personnel_sub_dept = $personnel->sub_dept_id;
                $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_table_name,$second_addition,$third_addition,$personnel_slug);
                
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
           if(is_null($health_facility_logo)){
            echo $data_url_img; 
           }else{ 
            ?> 
          <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
          <?php } ?>
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

              <div class="card" id="main-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackMainCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                </div>
                <div class="card-body">

                  <table class="table">
                    <tbody>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="openPatientBioDataForm(this,event)">Enter New Patient Bio Data</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="viewRegisteredPatients(this,event)">View Previously Registered Patients</a></td>
                      </tr>
                      

                      <tr class="pointer-cursor">
                        <td>3</td>
                        <td><a href="#" onclick="viewTodaysAppointments(this,event)">View Patients With Appointments Today</a></td>
                      </tr>

                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="viewReferralsOrConsults(this,event)">View Referrals Or Consults</a></td>
                      </tr>

                      <tr class="pointer-cursor">
                        <td>5</td>
                        <td><a href="#" onclick="viewReferralsAwaitingRegistration(this,event)">View Referrals Awaiting Registration</a></td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="perform-functions-card">
                <div class="card-header">
                  <h3 class="card-title">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button style="margin-top: 50px;" class="btn btn-primary" onclick="performFunctions(this,event)">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="choose-clinic-to-perform-functions-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromChooseClinicToPerformFunctionsCard(this,event)">Go Back</button>
                  <h3 class="card-title">Choose Clinic To Perform Function</h3>
                </div>
                <div class="card-body">
                  <?php
                  $all_clinics = $this->onehealth_model->getSubDeptsByDeptId(3);
                  if(is_array($all_clinics)){
                  ?>
                  <table class="table">
                    <tbody>
                      <?php 
                      $i = 0;
                      foreach($all_clinics as $row){
                        $i++;
                        $sub_dept_id = $row->id;
                        $name = $row->name;
                        $dept_slug = $this->onehealth_model->getDeptParamById("slug",3);
                        $sub_dept_slug = $row->slug;
                        $personnel_slug = "multitasking-records-officer";
                        
                        $url = site_url("onehealth/index/".$addition . "/".$dept_slug."/".$sub_dept_slug."/".$personnel_slug);
                      ?>
                      <tr class="pointer-cursor">
                        <td><?php echo $i; ?>.</td>
                        <td><a style="text-transform: capitalize;" href="#" onclick="chooseThisClinicToPerformFunction(this,event,'<?php echo $url; ?>','<?php echo $name; ?>')"><?php echo $name; ?></a></td>
                      </tr>

                      <?php } ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>


              <div class="card" id="enter-bio-data-awaiting-registration-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackFromEnterBioDataAwaitingRegistration(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Enter Patient Bio Data</h3>
                </div>
                <div class="card-body">
                  
                  
                  <?php $attr = array('id' => 'enter-bio-data-awaiting-registration-form') ?>
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    <span class="form-error" style="display: block;">Note : password defaults to hospital number</span>
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
                        <div class="form-group col-sm-4">
                          <label for="user_name" class="label-control"><span class="form-error1">*</span>  Patient UserName: </label>
                          <input type="text" class="form-control" id="user_name" name="user_name">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="firstname" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group col-sm-4">
                            <label for="lastname" class="label-control"><span class="form-error1">*</span> LastName: </label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                            <input type="date" class="form-control" name="dob" id="dob">
                            <span class="form-error"></span>
                          </div>
                          
                          <div class="form-group col-sm-3">
                            <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                            <input type="number" class="form-control" name="age" id="age">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <div id="age_unit">
                              <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                              <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
                                <option value="minutes">Minutes</option>
                                <option value="hours">Hours</option>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="years" selected>Years</option>

                              </select>
                            </div>
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <p class="label"><span class="form-error1">*</span> Gender: </p>
                            <div id="sex">
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                            </div>
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group col-sm-6">
    
                            <div id="user_type" class="col-sm-12">
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="user_type" value="fp" id="fp" onchange="userTypeChanged1(this,event)" checked> Full Payment
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="user_type" value="pfp" id="pfp" onchange="userTypeChanged1(this,event)"> Part Fee Payment
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                              <div class="form-check form-check-radio form-check-inline">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="user_type" value="nfp" id="nfp" onchange="userTypeChanged1(this,event)"> None Fee Payment
                                  <span class="circle">
                                      <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group col-sm-12" id="code_div" style="display: none;">
                              
                              <input type="text" class="form-control" placeholder="Enter Code" name="code" id="code_input" name="code_input" onkeyup="codeEntered1(this,event)" /><img src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>"  class="spinner">
                              <span id="code_status"></span>
                            </div>
                          </div>


                          <div class="form-group col-sm-6">
                            <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                            <input type="text" class="form-control" id="race" name="race">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                            <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                            <input type="text" class="form-control" id="religion" name="religion">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                            <input type="text" class="form-control" id="occupation" name="occupation">
                            <span class="form-error"></span>
                          </div>


                          <div class="form-group col-sm-6">
                            <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                            <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                            <input type="email" class="form-control" id="email" name="email">
                            <span class="form-error"></span>
                          </div>


                          <div class="form-group col-sm-12">
                            <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                            <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                            <span class="form-error"></span>
                          </div>
                        </div>
                      </div>

                      <div class="wrap">
                        <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                        <div class="form-row">
                          <div class="form-group col-sm-6">
                            <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                            <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                            <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                            <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>
                        </div>
                      </div>

                      
                      
                      
                    <input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
                    <input type="submit" class="btn btn-primary">
                  </form>

                </div>
              </div>

              <div class="card" id="previously-registered-patients-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>

                  <h4 style="margin-bottom: 40px;" id="quest">Previously registered Users</h4>
                  
                </div>
              </div>

              <div class="card" id="view-todays-appointments-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackFromTodaysAppointmentsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Patients With Appointments Today</h3>
                </div>
                <div class="card-body">
                  

                  
                </div>
              </div>

              <div class="card" id="referrals-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Referrals</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="referrals-awaiting-registration-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralsAwaitingRegistrationCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Referrals Awaiting Registration</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="referral-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Referral Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="on-appointment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>

                  <h4 style="margin-bottom: 40px;" id="quest">All Patients With Appointments Today</h4>
                  
                </div>
              </div>

              <div class="card" id="previously-registered-patients-card-unpaid" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button onclick="goDefault()" class="btn btn-warning">Go Back</button>

                  <h4 style="margin-bottom: 40px;" id="quest">Registered Patients With Payment Pending</h4>
                  
                </div>
              </div>

              <div class="card" id="on-appointments-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goBackOnAppointmentsBioDataCard(this,event)">Go Back</button>
                  
                  <h4 style="margin-bottom: 40px;" id="quest">Verify Patient Bio Data. Submit To Forward To The Next Officer </h4>
                  <?php $attr = array('id' => 'on-appointments-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                        <div class="form-group col-sm-6">
                          <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
                              <option value="minutes">Minutes</option>
                              <option value="hours">Hours</option>
                              <option value="days">Days</option>
                              <option value="weeks">Weeks</option>
                              <option value="months">Months</option>
                              <option value="years" selected>Years</option>

                            </select>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <p class="label"><span class="form-error1">*</span> Gender: </p>
                          <div id="sex">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        

                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>



                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div> 
                    <input type="submit" class="btn btn-primary">
                  </form>


                </div>
              </div>
              
              <div class="card" id="patient-bio-data" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  
                  <h4 style="margin-bottom: 40px;" id="quest">Enter Patient Bio Data: </h4>
                  <?php $attr = array('id' => 'enter-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  <span class="form-error" style="display: block;">Note : password defaults to hospital number</span>
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">             
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
                      <div class="form-group col-sm-4">
                        <label for="user_name" class="label-control"><span class="form-error1">*</span>  Patient UserName: </label>
                        <input type="text" class="form-control" id="user_name" name="user_name">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-4">
                          <label for="firstname" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="lastname" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
                              <option value="minutes">Minutes</option>
                              <option value="hours">Hours</option>
                              <option value="days">Days</option>
                              <option value="weeks">Weeks</option>
                              <option value="months">Months</option>
                              <option value="years" selected>Years</option>

                            </select>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <p class="label"><span class="form-error1">*</span> Gender: </p>
                          <div id="sex">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
  
                          <div id="user_type" class="col-sm-12">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="user_type" value="fp" id="fp" onchange="userTypeChanged(this,event)" checked> Full Payment
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="user_type" value="pfp" id="pfp" onchange="userTypeChanged(this,event)"> Part Fee Payment
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="user_type" value="nfp" id="nfp" onchange="userTypeChanged(this,event)"> None Fee Payment
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <div class="form-group col-sm-12" id="code_div" style="display: none;">
                            
                            <input type="text" class="form-control" placeholder="Enter Code" name="code" id="code_input" name="code_input" onkeyup="codeEntered(this,event)" /><img src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>"  class="spinner">
                            <span id="code_status"></span>
                          </div>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                    
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>
                    
                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                      </div>
  

                    </div>  
                    
                    
                  <input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
                  <input type="submit" class="btn btn-primary">
                </form>


                </div>
              </div>

              <div class="card" id="edit-patient-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" id="go-back-edit-bio" onclick="goDefault()">Go Back</button>

                  
                  <h4 style="margin-bottom: 40px;" id="quest">Edit Patient Bio Data: </h4>
                  <?php $attr = array('id' => 'edit-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                    <span class="form-error text-right">* </span>: required
                    
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row">             
                        <div class="form-group col-sm-6">
                          <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                          <input type="text" class="form-control" id="firstname" name="firstname">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                          <input type="text" class="form-control" id="lastname" name="lastname">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                          <input type="date" class="form-control" name="dob" id="dob">
                          <span class="form-error"></span>
                        </div>
                        
                        <div class="form-group col-sm-3">
                          <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                          <input type="number" class="form-control" name="age" id="age">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <div id="age_unit">
                            <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
                            <select name="age_unit" id="age_unit" class="form-control" data-style="btn btn-link">
                              <option value="minutes">Minutes</option>
                              <option value="hours">Hours</option>
                              <option value="days">Days</option>
                              <option value="weeks">Weeks</option>
                              <option value="months">Months</option>
                              <option value="years" selected>Years</option>

                            </select>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <p class="label"><span class="form-error1">*</span> Gender: </p>
                          <div id="sex">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <span class="form-error"></span>
                        </div>

                        

                        <div class="form-group col-sm-6">
                          <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                          <input type="text" class="form-control" id="race" name="race">
                          <span class="form-error"></span>
                        </div>



                        <div class="form-group col-sm-6">
                          <label for="nationality" class="label-control"><span class="form-error1">*</span> Nationality: </label>
                          <input type="text" class="form-control" id="nationality" name="nationality">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="state_of_origin" class="label-control"><span class="form-error1">*</span> State Of Origin: </label>
                          <input type="text" class="form-control" id="state_of_origin" name="state_of_origin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="religion" class="label-control"><span class="form-error1">*</span> Religion: </label>
                          <input type="text" class="form-control" id="religion" name="religion">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="occupation" class="label-control"><span class="form-error1">*</span> Occupation: </label>
                          <input type="text" class="form-control" id="occupation" name="occupation">
                          <span class="form-error"></span>
                        </div>


                        <div class="form-group col-sm-6">
                          <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                          <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                          <input type="email" class="form-control" id="email" name="email">
                          <span class="form-error"></span>
                        </div>

                      </div>
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                          <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="name_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Name Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="address_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Address Of Next Of Kin: </label>
                          <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="mobile_no_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Mobile No. Of  Next Of Kin: </label>
                          <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                          <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="relationship_of_next_of_kin" class="label-control"><span class="form-error1">*</span> Relationship With Next Of Kin: </label>
                          <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
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



          <div id="mark-this-patient-registration-as-paid-btn" onclick="markThisPatientsRegistrationAsPaid(this,event)" rel="tooltip" data-toggle="tooltip" title="Mark This Patient Registration As Paid" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
             <i class="fas fa-check-double" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
            </div>
          </div>

          <div id="proceed-referral-to-nurse" onclick="proceedReferralToNurse(this,event)" rel="tooltip" data-toggle="tooltip" title="Push This Referral To Nurse For Inputing Of Vital Signs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>
          
          <!-- Modals -->

          <div class="modal fade" data-backdrop="static" id="consultation-fee-payment-modal-off-appointment" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Mark This Patient Consultation Fee As Paid</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body" id="modal-body">
                  <?php
                    $attr = array('id' => 'consultation-fee-payment-form-off-appointment');
                   echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_consultation_fee_paymemt_off_appointment',$attr);
                  ?>
                    <div class="form-group">
                      <label for="consultation_amt">Enter Consultation Fee</label>
                      <input type="number" name="consultation_amt" id="consultation_amt" class="form-control" required>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" class="btn btn-success" value="PROCEED">
      
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="consultation-fee-payment-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Mark This Patient Consultation Fee As Paid</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body" id="modal-body">
                  <?php
                    $attr = array('id' => 'consultation-fee-payment-form');
                   echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_consultation_fee_paymemt',$attr);
                  ?>
                    <div class="form-group">
                      <label for="consultation_amt">Enter Consultation Fee</label>
                      <input type="number" name="consultation_amt" id="consultation_amt" class="form-control" required>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" class="btn btn-success" value="PROCEED">
      
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>  

          <div class="modal fade" data-backdrop="static" id="mark-paid-patients-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Mark This Patient As Paid</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body" id="modal-body">
                  <?php
                    $attr = array('id' => 'registration-amount-form');
                   echo form_open('',$attr);
                  ?>
                    <div class="form-group">
                      <label for="registration_amt">Enter Registration Price</label>
                      <input type="number" name="registration_amt" id="registration_amt" class="form-control" required>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" class="btn btn-success" value="PROCEED">
      
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>  



        </div>
      </div>
      <p id="hn" style="display: none;"></p>
      </div>
      <footer class="footer">
        
       
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {
      $("#consultation-fee-payment-form-off-appointment").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = clinic_base_url + "/submit_consultation_fee_paymemt_off_appointment";
        var consultation_amt = me.find("#consultation_amt").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var patient_name =  $(this).attr("data-name");
        var hospital_number =  $(this).attr("data-hospital-num");
        var form_data = {
          id : id,
          type : type,
          patient_name : patient_name,
          hospital_number : hospital_number,
          consultation_amt : consultation_amt
        };

        swal({
          title: 'Warning?',
          text: "Do You Want To Pay "+ addCommas(consultation_amt) + " For " + patient_name + " As Consultation Fee In " +selected_clinic_name+ "?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
      
          console.log(consultation_amt);
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
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              console.log(response)
              
              if(response.success == true){
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var hospital_number = response.hospital_number;
                var sum = response.sum;
                var receipt_file = response.receipt_file;
                
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
                  "hospital_number" : hospital_number,
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true
                };

                var url = clinic_base_url + "/save_receipt_clinic";
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
                    }
                  },error : function () {
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "danger"  
                    });
                  }
                });
              }
            },error : function () {
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            }
          });
        });
      })

      $("#consultation-fee-payment-modal #consultation-fee-payment-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = clinic_base_url + "/submit_consultation_fee_paymemt";
        var consultation_amt = me.find("#consultation_amt").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var patient_name =  $(this).attr("data-name");
        var hospital_number =  $(this).attr("data-hospital-num");
        var form_data = {
          id : id,
          type : type,
          patient_name : patient_name,
          hospital_number : hospital_number,
          consultation_amt : consultation_amt
        };

        swal({
          title: 'Warning?',
          text: "Do You Want To Pay "+ addCommas(consultation_amt) + " For " + patient_name + " As Consultation Fee?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
      
          console.log(consultation_amt);
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
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              console.log(response)
              
              if(response.success == true){
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var hospital_number = response.hospital_number;
                var sum = response.sum;
                var receipt_file = response.receipt_file;
                
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
                  "hospital_number" : hospital_number,
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true
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
                    }
                  },error : function () {
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "danger"  
                    });
                  }
                });
              }
            },error : function () {
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            }
          });
        });
      })

      $("#enter-bio-data-awaiting-registration-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          var id = me.attr("data-id");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit-patient-bio-data-clinic-referred'); ?>";
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
              if(response.success == true && response.successful == true){
                $.notify({
                message:"User Has Been Successfully Registered In Your Facility"
                },{
                  type : "success"  
                });
                setTimeout(goDefault, 1000);
                
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#enter-bio-data-awaiting-registration-form #'+key);
                
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

      $("#on-appointments-bio-data-form").submit(function (evt) {
        evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          var record_id = me.attr("data-id");
          
          var url = clinic_base_url + "/submit-on-appointment-patient-bio-data";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "record_id",
            "value" : record_id
          })
          
          console.log(values)
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : values,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){
                $.notify({
                message:"Patient Successfully Forwarded To Next Officer"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                setTimeout(goDefault, 2000);
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
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

      <?php if($this->session->referred && $this->session->patient_name){ ?>
        var patient_name = "<?php echo $this->session->patient_name; ?>";
        $.notify({
        message:patient_name + " Successfully Referred For Consultation"
        },{
          type : "success"  
        });
      <?php } ?>

      <?php if($this->session->added_successfully && $this->session->hospital_number && $this->session->patient_username){ ?>
        var hospital_number = '<?php echo $this->session->hospital_number; ?>';
        var patient_username = '<?php echo $this->session->patient_username ?>';
        swal({
          title: 'Success',
          text: "<p>Patient Account Successfully Created.</p><p>Hospital Number: <span class='text-primary'>"+hospital_number+"</span></p><p>User Name: <span class='text-primary'>"+patient_username+"</span></p><p>Password: <span class='text-primary'>"+hospital_number+"</span></p>",
          type: 'success'
        }).then((result) => {
          document.location.reload();
        })
      <?php } ?>  

      <?php if($this->session->mark_successful){ ?>
        var hospital_number = '<?php echo $this->session->hospital_number; ?>';
        swal({
          title: 'Success',
          text: "<p>Patient Marked As Paid Successfully.</p><p>Hospital Number: <span class='text-primary'>"+hospital_number+"</span></p>",
          type: 'success'
        }).then((result) => {
          // document.location.reload();
        })
      <?php } ?>

      <?php if($this->session->nurse_proceeded){ ?>
        
        $.notify({
        message:" Successfully Moved To Nurse's Platform"
        },{
          type : "success"  
        });
      <?php } ?>  


      $("#enter-bio-data-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          
          var url = clinic_base_url + "/submit-patient-bio-data-clinic";
          var values = me.serializeArray();
          console.log(values)
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : values,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){
                
                document.location.reload();
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#enter-bio-data-form #'+key);
                
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

      $("#edit-bio-data-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
          $(".spinner-overlay").show();
          var id = me.attr("data-id");
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit-patient-bio-data-clinic-edit'); ?>";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "id",
            "value" : id
          })
          console.log(values)
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : values,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){
                $.notify({
                message:"Patient Bio Data Edited Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
               $.each(response.messages, function (key,value) {

                var element = $('#edit-bio-data-form #'+key);
                
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
