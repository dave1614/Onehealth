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
<style>
  tr{
    cursor: pointer;
  }
</style>
<script>

  function performActions (elem,evt) {
    $("#main-card").hide();
    $("#choose-action-card").show();
  }

  function goBackFromChooseActionCard (elem,evt) {
    $("#main-card").show();
    $("#choose-action-card").hide(); 
  }

  function viewHospitalTeller (elem,evt) {
    evt.preventDefault();
    $("#choose-action-card").hide();
    $("#hospital-teller-card").show();
  }

  function goBackHospitalTellerCard (elem,evt) {
    $("#choose-action-card").show();
    $("#hospital-teller-card").hide();
  }

  function viewRegistrationFees (elem,evt) {
    $("#choose-patient-type-registration").modal("show");
  }

  function viewConsultationFees (elem,evt) {
    $("#choose-patient-type-consultation").modal("show");
  }

  function viewAdmissionFees (elem,evt) {
    $("#choose-patient-type-admission").modal("show");
  }

  function viewWardServices (elem,evt) {
    $("#choose-patient-type-services").modal("show");
  }

  function viewOutstandingBillsCollected (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_outstanding_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&type=fp",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#hospital-teller-card").hide();
          $("#view-outstanding-payments-card .card-title").html("Outstanding Payments");
          $("#view-outstanding-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#outstanding-table").DataTable();
          $("#view-outstanding-payments-card").show();
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

  function selectTimeRangeRegistrationChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_registration_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-registration-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
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

  function viewFullPayingRegistrationPayments (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_registration_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-registration").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-registration-payments-card .card-title").html("Registration Payments For Full Payment Patients");
          $("#view-registration-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
          $("#view-registration-payments-card").show();
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

  function goBackFromViewRegistrationPaymentsCard (elem,evt) {
    $("#choose-patient-type-registration").modal("show");
    $("#hospital-teller-card").show();
    $("#view-registration-payments-card").hide();
  }

    
  function viewPartPayingRegistrationPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_registration_fees'); ?>";
    
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
          $("#choose-patient-type-registration").modal("hide");
          $("#hospital-teller-card").hide();
          $("#clinic-registration-companies-card .card-title").html("Insurance And Retainership Clinic Registration Bills(Part Fee Paying)");
          $("#clinic-registration-companies-card .card-body").html(messages);
          
          $("#clinic-registration-companies-card #clinic-registration-companies-table").DataTable();
          $("#clinic-registration-companies-card").show();
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

  function goBackFromClinicRegistrationCompaniesCard (elem,evt) {
    $("#choose-patient-type-registration").modal("show");
    $("#hospital-teller-card").show();
    
    $("#clinic-registration-companies-card").hide();
  }

  function viewPartPayingRegistrationPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_registrations_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#clinic-registration-companies-card").hide();
          $("#view-registration-payments-hmo-card .card-title").html("Clinic Registration Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-registration-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
          $("#view-registration-payments-hmo-card").show();
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

  function selectTimeRangeRegistrationChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_registrations_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-registration-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
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

  function goBackFromViewRegistrationPaymentsHmoCard (elem,evt) {
    $("#clinic-registration-companies-card").show();
    
    $("#view-registration-payments-hmo-card").hide();
  }

  function viewNonePayingRegistrationPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_payment_registration_fees'); ?>";
    
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
          $("#choose-patient-type-registration").modal("hide");
          $("#hospital-teller-card").hide();
          $("#clinic-registration-companies-card .card-title").html("Insurance And Retainership Clinic Registration Bills(None Fee Paying)");
          $("#clinic-registration-companies-card .card-body").html(messages);
          
          $("#clinic-registration-companies-card #clinic-registration-companies-table").DataTable();
          $("#clinic-registration-companies-card").show();
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

  function viewNoneFeePayingRegistrationPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_registrations_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#clinic-registration-companies-card").hide();
          $("#view-registration-payments-hmo-card .card-title").html("Clinic Registration Payments For None Fee Paying Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-registration-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
          $("#view-registration-payments-hmo-card").show();
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

  function selectTimeRangeRegistrationChangedNoneFeePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_registrations_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-registration-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
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

  function viewFullPayingConsultationPayments (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_consultation_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-consultation").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-consultation-payments-card .card-title").html("Consultation Payments For Full Paying Patients");
          $("#view-consultation-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
          $("#view-consultation-payments-card").show();
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

  function selectTimeRangeConsultationChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_consultation_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-consultation-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#registration-table").DataTable();
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

  function goBackFromViewConsultationPaymentsCard (elem,evt) {
    $("#choose-patient-type-consultation").modal("show");
    $("#hospital-teller-card").show();
    $("#view-consultation-payments-card").hide();
  }

  function viewPartPayingConsultationPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_consultation_fees'); ?>";
    
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
          $("#choose-patient-type-consultation").modal("hide");
          $("#hospital-teller-card").hide();
          $("#clinic-consultation-companies-card .card-title").html("Insurance And Retainership Clinic Consultation Bills(Part Fee Paying)");
          $("#clinic-consultation-companies-card .card-body").html(messages);
          
          $("#clinic-consultation-companies-card #clinic-consultation-companies-table").DataTable();
          $("#clinic-consultation-companies-card").show();
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

  function viewPartPayingConsultationPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_consultations_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#clinic-consultation-companies-card").hide();
          $("#view-consultation-payments-hmo-card .card-title").html("Clinic Consultation Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-consultation-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#consultation-table").DataTable();
          $("#view-consultation-payments-hmo-card").show();
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

  function goBackFromViewConsultationPaymentsHmoCard (elem,evt) {
    $("#clinic-consultation-companies-card").show();
         
    $("#view-consultation-payments-hmo-card").hide();
  }

  function selectTimeRangeConsultationChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_consultations_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-consultation-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#consultation-table").DataTable();
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

  function goBackFromClinicConsultationCompaniesCard (elem,evt) {
    $("#choose-patient-type-consultation").modal("show");
    $("#hospital-teller-card").show();
    $("#clinic-consultation-companies-card").hide();
  }

  function viewNonePayingConsultationPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_payment_consultation_fees'); ?>";
    
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
          $("#choose-patient-type-consultation").modal("hide");
          $("#hospital-teller-card").hide();
          $("#clinic-consultation-companies-card .card-title").html("Insurance And Retainership Clinic Consultation Bills(None Fee Paying)");
          $("#clinic-consultation-companies-card .card-body").html(messages);
          
          $("#clinic-consultation-companies-card #clinic-consultation-companies-table").DataTable();
          $("#clinic-consultation-companies-card").show();
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


  function viewNoneFeePayingConsultationPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_consultations_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#clinic-consultation-companies-card").hide();
          $("#view-consultation-payments-hmo-card .card-title").html("Clinic Consultation Payments For None Fee Paying Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-consultation-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#consultation-table").DataTable();
          $("#view-consultation-payments-hmo-card").show();
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


  function selectTimeRangeConsultationChangedNoneFeePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_clinic_consultations_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-consultation-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#consultation-table").DataTable();
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


  function viewFullPayingAdmissionPayments (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_admission_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-admission").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-admission-payments-card .card-title").html("Ward Admission Payments For Full Paying Patients");
          $("#view-admission-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
          $("#view-admission-payments-card").show();
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

  function selectTimeRangeWardAdmissionChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_admission_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-admission-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
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


  function selectTimeRangeAdmissionChanged(elem,event){
    var days_num = $(elem).val();
    var type = $(elem).attr("data-type");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_admission_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&type="+type,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-admission-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
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

  function goBackFromViewAdmissionPaymentsCard (elem,evt) {
    $("#choose-patient-type-admission").modal("show");
    $("#hospital-teller-card").show();
    $("#view-admission-payments-card").hide();
  }

  function viewPartPayingAdmissionPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_ward_admission_fees'); ?>";
    
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
          $("#choose-patient-type-admission").modal("hide");
          $("#hospital-teller-card").hide();
          $("#ward-admission-companies-card .card-title").html("Insurance And Retainership Ward Admission Bills(Part Fee Paying)");
          $("#ward-admission-companies-card .card-body").html(messages);
          
          $("#ward-admission-companies-card #ward-admission-companies-table").DataTable();
          $("#ward-admission-companies-card").show();
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

  function viewPartPayingAdmisssionPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_admission_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#ward-admission-companies-card").hide();
          $("#view-admission-payments-hmo-card .card-title").html("Ward Admission Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-admission-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
          $("#view-admission-payments-hmo-card").show();
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

  function selectTimeRangeWardAdmissionChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_admission_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-admission-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
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

  function goBackFromViewAdmissionPaymentsHmoCard (elem,evt) {
    $("#ward-admission-companies-card").show();
          
    $("#view-admission-payments-hmo-card").hide();
  }

  function goBackFromWardAdmissionCompaniesCard (elem,evt) {
    $("#choose-patient-type-admission").modal("show");
    $("#hospital-teller-card").show();
    
    $("#ward-admission-companies-card").hide();
  }

  function viewNonePayingAdmissionPayments (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_payment_ward_admission_fees'); ?>";
    
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
          $("#choose-patient-type-admission").modal("hide");
          $("#hospital-teller-card").hide();
          $("#ward-admission-companies-card .card-title").html("Insurance And Retainership Ward Admission Bills(None Fee Paying)");
          $("#ward-admission-companies-card .card-body").html(messages);
          
          $("#ward-admission-companies-card #ward-admission-companies-table").DataTable();
          $("#ward-admission-companies-card").show();
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

  function viewNonePayingAdmissionPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_admission_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#ward-admission-companies-card").hide();
          $("#view-admission-payments-hmo-card .card-title").html("Ward Admission Payments For None Fee Paying Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-admission-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
          $("#view-admission-payments-hmo-card").show();
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

  function selectTimeRangeAdmissionChangedNoneFeePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_admission_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-admission-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#admission-table").DataTable();
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

  function viewFullPayingWardServices (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-services").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-services-payments-card .card-title").html("Ward Service Payments For Full Paying Patients");
          $("#view-services-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
          $("#view-services-payments-card").show();
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

  function selectTimeRangeWardServicesChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-services-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
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

  function goBackFromViewServicesPaymentsCard (elem,evt) {
    $("#choose-patient-type-services").modal("show");
    $("#hospital-teller-card").show();
    $("#view-services-payments-card").hide();
  }

  function viewPartPayingWardServices (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_ward_services'); ?>";
    
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
          $("#choose-patient-type-services").modal("hide");
          $("#hospital-teller-card").hide();
          $("#ward-services-companies-card .card-title").html("Insurance And Retainership Ward Services Bills(Part Fee Paying)");
          $("#ward-services-companies-card .card-body").html(messages);
          
          $("#ward-services-companies-card #ward-services-companies-table").DataTable();
          $("#ward-services-companies-card").show();
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

  function goBackFromWardServicesCompaniesCard (elem,evt) {
    $("#choose-patient-type-services").modal("show");
    $("#hospital-teller-card").show();
    
    $("#ward-services-companies-card").hide();
  }

  function viewPartPayingWardServicesPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#ward-services-companies-card").hide();
          $("#view-services-payments-hmo-card .card-title").html("Ward Services Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-services-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
          $("#view-services-payments-hmo-card").show();
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

  function selectTimeRangeWardServicesChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-services-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
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

  function goBackFromViewServicesPaymentsHmoCard (elem,evt) {
    $("#ward-services-companies-card").show();
    $("#view-services-payments-hmo-card").hide();
  }

  function viewNonePayingWardServices (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_payment_wards_services_fees'); ?>";
    
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
          $("#choose-patient-type-services").modal("hide");
          $("#hospital-teller-card").hide();
          $("#ward-services-companies-card .card-title").html("Insurance And Retainership Ward Services Bills(None Fee Paying)");
          $("#ward-services-companies-card .card-body").html(messages);
          
          $("#ward-services-companies-card #ward-services-companies-table").DataTable();
          $("#ward-services-companies-card").show();
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

   function viewNoneFeePayingWardServicesPaymentsInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#ward-services-companies-card").hide();
          $("#view-services-payments-hmo-card .card-title").html("Ward Services Payments For None Fee Paying Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-services-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
          $("#view-services-payments-hmo-card").show();
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

  function selectTimeRangeWardServicesChangedNoneFeePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_ward_services_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-services-payments-hmo-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#services-table").DataTable();
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


  function viewFullPayingOutstanding (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_outstanding_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#hospital-teller-card").hide();
          $("#view-outstanding-payments-card .card-title").html("Outstanding Payments For Full Payment Patients");
          $("#view-outstanding-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#outstanding-table").DataTable();
          $("#view-outstanding-payments-card").show();
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

  function goBackFromViewOutstandingPaymentsCard (elem,evt) {
    
    $("#hospital-teller-card").show();
    $("#view-outstanding-payments-card").hide();
  }

  function selectTimeRangeOutstandingChanged(elem,event){
    var days_num = $(elem).val();
  
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_outstanding_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-outstanding-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#outstanding-table").DataTable();
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


  function viewPartPayingOutstanding (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_outstanding_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&type=pfp",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-outstanding").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-outstanding-payments-card .card-title").html("Outstanding Payments For Part Payment Patients");
          $("#view-outstanding-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#outstanding-table").DataTable();
          $("#view-outstanding-payments-card").show();
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

  function viewNonePayingOutstanding (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_outstanding_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&type=nfp",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-outstanding").modal("hide");
          $("#hospital-teller-card").hide();
          $("#view-outstanding-payments-card .card-title").html("Outstanding Payments For Non Fee Payment Patients");
          $("#view-outstanding-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#outstanding-table").DataTable();
          $("#view-outstanding-payments-card").show();
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

  function goBackFromViewOverTheCounterPharmacyPaymentsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#view-over-the-counter-pharmacy-payments-card").hide();
  }

  function selectTimeRangePharmacyOverTheCounterChanged(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_over_the_counter_pharmacy_payments'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-over-the-counter-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#view-over-the-counter-pharmacy-payments-card #laboratory-table").DataTable();
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

  function viewPharmacy (elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To View: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Over The Counter Payments',
      cancelButtonText : "Registered Patients Payments"
    }).then(function(){
      $(".spinner-overlay").show();
        
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_over_the_counter_pharmacy_payments'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&days_num=1",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            
            $("#choose-action-card").hide();
            $("#view-over-the-counter-pharmacy-payments-card .card-title").html("Over The Counter Payments For Pharmacy");
            $("#view-over-the-counter-pharmacy-payments-card .card-body").html(messages);
            $('.my-select').selectpicker();
            $("#view-over-the-counter-pharmacy-payments-card #laboratory-table").DataTable();
            $("#view-over-the-counter-pharmacy-payments-card").show();
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
        $("#choose-patient-type-pharmacy").modal("show");
      }
    });
  }

  

  function selectTimeRangePharmacyChanged(elem,event){
    var days_num = $(elem).val();
    var type = $(elem).attr("data-type");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_default'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&type="+type,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
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

  function viewFullPayingPharmacy (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-pharmacy").modal("hide");
          $("#choose-action-card").hide();
          $("#view-pharmacy-payments-card .card-title").html("Pharmacy Payments For Full Payment Patients");
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
          $("#view-pharmacy-payments-card").show();
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

  function selectTimeRangePharmacyChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
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

  function goBackFromViewPharmacyPaymentsCard (elem,evt) {
    $("#pharmacy-companies-card").show();
    $("#view-pharmacy-payments-card").hide();
  }

  function viewLaboratory (elem,evt) {
    evt.preventDefault();
    $("#choose-patient-type-laboratory").modal("show");
  }

  function viewFullPayingLaboratory (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-laboratory").modal("hide");
          $("#choose-action-card").hide();
          $("#view-laboratory-payments-card .card-title").html("Laboratory Payments For Full Payment Patients");
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
          $("#view-laboratory-payments-card").show();
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

  function selectTimeRangeLaboratoryChangedFullPaying(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_full_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
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

  function goBackFromViewLaboraroryPaymentsCard (elem,evt) {
    $("#lab-companies-card").show();
   
    $("#view-laboratory-payments-card").hide();
  }

  function loadLaboratoryPaymentInfo (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var name = elem.attr("data-name");
    console.log(initiation_code);
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_history_of_payment_laboratory_finance_records'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&initiation_code="+initiation_code,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#payment-history-lab-card .card-body").html(messages);
          $("#payment-history-lab-card .card-title").html("Payment History Of " + name);
          $("#payment-info-lab-table").DataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: -1
          });
          $("#view-laboratory-payments-card").hide();
          $("#payment-history-lab-card").show();
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


  function goBackFromPaymentHistoryLabCard (elem,evt) {
    $("#payment-history-lab-card").hide();
    $("#view-laboratory-payments-card").show();
  }

  function viewPartPayingLaboratory(elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_lab'); ?>";
    
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
          $("#choose-patient-type-laboratory").modal("hide");
          $("#choose-action-card").hide();
          $("#lab-companies-card .card-title").html("Insurance And Retainership Laboratory Bills(Part Fee Paying)");
          $("#lab-companies-card .card-body").html(messages);
          
          $("#lab-companies-card #lab-companies-table").DataTable();
          $("#lab-companies-card").show();
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

  function viewPartPayingPharmacy (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_part_payment_pharmacy'); ?>";
    
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
          $("#choose-patient-type-pharmacy").modal("hide");
          $("#choose-action-card").hide();
          $("#pharmacy-companies-card .card-title").html("Insurance And Retainership Pharmacy Bills(Part Fee Paying)");
          $("#pharmacy-companies-card .card-body").html(messages);
          
          $("#pharmacy-companies-card #pharmacy-companies-table").DataTable();
          $("#pharmacy-companies-card").show();
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

  function goBackFromPharmacyCompaniesCard (elem,evt) {
    $("#choose-patient-type-pharmacy").modal("show");
    $("#choose-action-card").show();
    $("#pharmacy-companies-card").hide();
  }

  function viewPartPayingPharmacyInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#pharmacy-companies-card").hide();
          $("#view-pharmacy-payments-card .card-title").html("Pharmacy Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
          $("#view-pharmacy-payments-card").show();
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

  function selectTimeRangePharmacyChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
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

  function viewNonePayingPharmacy (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_paying_pharmacy'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&type=nfp",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-pharmacy").modal("hide");
          $("#choose-action-card").hide();
          $("#pharmacy-companies-card .card-title").html("Insurance And Retainership Pharmacy Bills(None Fee Paying)");
          $("#pharmacy-companies-card .card-body").html(messages);
          
          $("#pharmacy-companies-card #pharmacy-companies-table").DataTable();
          $("#pharmacy-companies-card").show();
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

  function viewNoneFeePayingPharmacyInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#pharmacy-companies-card").hide();
          $("#view-laboratory-payments-card .card-title").html("Pharmacy Payments For None Fee Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
          $("#view-pharmacy-payments-card").show();
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

  function selectTimeRangePharmacyChangedNonePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_pharmacy_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-pharmacy-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#pharmacy-table").DataTable();
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

  function viewNonePayingLaboratory(elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_companies_with_none_fee_paying_lab'); ?>";
    
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
          $("#choose-patient-type-laboratory").modal("hide");
          $("#choose-action-card").hide();
          $("#lab-companies-card .card-title").html("Insurance And Retainership Laboratory Bills(None Fee Paying)");
          $("#lab-companies-card .card-body").html(messages);
          
          $("#lab-companies-card #lab-companies-table").DataTable();
          $("#lab-companies-card").show();
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

  function viewPartPayingLaboratoryInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#lab-companies-card").hide();
          $("#view-laboratory-payments-card .card-title").html("Laboratory Payments For Part Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
          $("#view-laboratory-payments-card").show();
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

  function viewNoneFeePayingLaboratoryInfo (elem,evt) {
    elem = $(elem);
    $(".spinner-overlay").show();
    var company_id = elem.attr("data-company-id");
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#lab-companies-card").hide();
          $("#view-laboratory-payments-card .card-title").html("Laboratory Payments For None Fee Payment Patients<br>Company Id: <em class='text-primary'>"+company_id+"</em>");
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
          $("#view-laboratory-payments-card").show();
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
  

  function goBackFromLabCompaniesCard (elem,evt) {
    $("#choose-patient-type-laboratory").modal("show");
    $("#choose-action-card").show();
   
    $("#lab-companies-card").hide();
  }

  function selectTimeRangeLaboratoryChangedPartPaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_by_company_id_part_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
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

  


  function selectTimeRangeLaboratoryChangedNonePaying(elem,event){
    var days_num = $(elem).val();
    var company_id = $(elem).attr("data-company-id");
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_by_company_id_none_fee_paying'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num+"&company_id="+company_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
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

  function viewReferralsLaboratory (elem,evt) {
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_referrals'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#choose-patient-type-laboratory").modal("hide");
          $("#choose-action-card").hide();
          $("#view-laboratory-payments-card .card-title").html("Laboratory Payments For Referral Patients");
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
          $("#view-laboratory-payments-card").show();
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

  function selectTimeRangeLaboratoryChangedReferral(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_laboratory_fees_referrals'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-laboratory-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#laboratory-table").DataTable();
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

  function viewMortuary (elem,evt) {
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_mortuary_payments'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num=1",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          
          $("#choose-action-card").hide();
          $("#view-mortuary-payments-card .card-title").html("Mortuary Payments");
          $("#view-mortuary-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#mortuary-table").DataTable();
          $("#view-mortuary-payments-card").show();
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

  function selectTimeRangeMortuaryChanged(elem,event){
    var days_num = $(elem).val();
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_mortuary_payments'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&days_num="+days_num,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#view-mortuary-payments-card .card-body").html(messages);
          $('.my-select').selectpicker();
          $("#mortuary-table").DataTable();
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

  function goBackFromViewMortuaryPaymentsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#view-mortuary-payments-card").hide();
  }

  function loadMortuaryPaymentInfo(elem,evt,mortuary_record_id){
    if(mortuary_record_id != ""){
      $(".spinner-overlay").show();
        
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patients_mortuary_payment_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&mortuary_record_id="+mortuary_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#view-mortuary-payments-card").hide();
            $("#view-mortuary-payments-info-card .card-title").html("Mortuary Record Payments");
            $("#view-mortuary-payments-info-card .card-body").html(messages);
            $("#mortuary-info-table").DataTable();
            $("#view-mortuary-payments-info-card").show();
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

  function goBackFromViewMortuaryPaymentsInfoCard (elem,evt) {
    $("#view-mortuary-payments-card").show();
    $("#view-mortuary-payments-info-card").hide();
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

              <div class="card" id="view-mortuary-payments-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewMortuaryPaymentsInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-mortuary-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewMortuaryPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="pharmacy-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromPharmacyCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="view-over-the-counter-pharmacy-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewOverTheCounterPharmacyPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-services-payments-hmo-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewServicesPaymentsHmoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="ward-services-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromWardServicesCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-admission-payments-hmo-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewAdmissionPaymentsHmoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="ward-admission-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromWardAdmissionCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="view-consultation-payments-hmo-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewConsultationPaymentsHmoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="clinic-consultation-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromClinicConsultationCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-registration-payments-hmo-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewRegistrationPaymentsHmoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="clinic-registration-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromClinicRegistrationCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="lab-companies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromLabCompaniesCard(this,event)">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">

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

              <div class="card" id="hospital-teller-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackHospitalTellerCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Hospital Teller's Payments</h3>
                </div>
                <div class="card-body">
                  <table class="table">
                    <tbody>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="viewRegistrationFees(this,event)">Registration Fees</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="viewConsultationFees(this,event)">Consultation Fees</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>3</td>
                        <td><a href="#" onclick="viewAdmissionFees(this,event)">Wards Admission Fees</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="viewWardServices(this,event)">Wards Services</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>5</td>
                        <td><a href="#" onclick="viewOutstandingBillsCollected(this,event)">Outstanding Bills Collected</a></td>
                      </tr>
                      
                     
                      
                    </tbody>
                  </table>
                </div>
              </div>

              

              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromChooseActionCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                  <table class="table">
                    <tbody>
                      <?php if($health_facility_structure == "hospital"){ ?>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="viewHospitalTeller(this,event)">Hospital Teller</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="viewPharmacy(this,event)">Pharmacy</a></td>
                      </tr>
                      
                      <tr class="pointer-cursor">
                        <td>3</td>
                        <td><a href="#" onclick="viewLaboratory(this,event)">Laboratory</a></td>
                      </tr>

                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="viewMortuary(this,event)">Mortuary</a></td>
                      </tr>

                      <?php }else if($health_facility_structure == "pharmacy"){ ?>
                       <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="viewPharmacy(this,event)">Pharmacy</a></td>
                      </tr>
                      <?php }else if($health_facility_structure == "laboratory"){ ?>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="viewLaboratory(this,event)">Laboratory</a></td>
                      </tr>
                      <?php }else if($health_facility_structure == "mortuary"){ ?>
                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="viewMortuary(this,event)">Mortuary</a></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="view-registration-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewRegistrationPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="payment-history-lab-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromPaymentHistoryLabCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">Payment History Of: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-admission-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewAdmissionPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-services-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewServicesPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-outstanding-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewOutstandingPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-pharmacy-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewPharmacyPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-laboratory-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewLaboraroryPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-consultation-payments-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackFromViewConsultationPaymentsCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;">View Payments In: </h3>
                </div>
                <div class="card-body">

                </div>
              </div>





            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-laboratory" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Laboratory Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingLaboratory(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingLaboratory(this,event)">Part Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-warning" onclick="viewNonePayingLaboratory(this,event)">None Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewReferralsLaboratory(this,event)">Referrals</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

  
          <div class="modal fade" data-backdrop="static" id="choose-patient-type-pharmacy" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Pharmacy Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingPharmacy(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingPharmacy(this,event)">Part Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingPharmacy(this,event)">None Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-outstanding" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Outstanding Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingOutstanding(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingOutstanding(this,event)">Part Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingOutstanding(this,event)">None Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-services" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Ward Services Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingWardServices(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingWardServices(this,event)">Part Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingWardServices(this,event)">None Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-admission" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Ward Admission Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingAdmissionPayments(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingAdmissionPayments(this,event)">Part Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingAdmissionPayments(this,event)">None Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-consultation" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Consultation Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingConsultationPayments(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingConsultationPayments(this,event)">Part Fee Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingConsultationPayments(this,event)">None Fee Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="choose-patient-type-registration" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Registration Payments Of Patients That Are: </h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <p>
                    <button class="btn btn-primary" onclick="viewFullPayingRegistrationPayments(this,event)">Full Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-info" onclick="viewPartPayingRegistrationPayments(this,event)">Part Fee Paying</button>
                  </p>
                  <p>
                    <button class="btn btn-success" onclick="viewNonePayingRegistrationPayments(this,event)">None Fee Paying</button>
                  </p>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                </div>
              </div>
            </div>
          </div>

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

          <div id="proceed-referral-to-nurse" onclick="proceedReferralToNurse(this,event)" rel="tooltip" data-toggle="tooltip" title="Push This Referral To Nurse For Inputing Of Vital Signs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>

          <div id="mark-this-patient-registration-as-paid-btn" onclick="markThisPatientsRegistrationAsPaid(this,event)" rel="tooltip" data-toggle="tooltip" title="Mark This Patient Registration As Paid" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
             <i class="fas fa-check-double" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>
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

      
    });

  </script>
