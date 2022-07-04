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
            $clinic_structure = $row->clinic_structure;
            $ward_admission_fee = $row->ward_admission_fee;
            $ward_admission_duration = $row->ward_admission_duration;
            $ward_admission_grace_days = $row->ward_admission_grace_days;
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
  var selected_rows = [];
  var mortuary_selected_rows = [];
  var patient_facility_id = "";
  var patient_full_name = "";
  var consultation_id = "";
  function performFunctions (elem,evt) {
    $("#main-card").hide();
    $("#choose-action-card").show();
  }

  function goBackChooseAction (elem,evt) {
    $("#main-card").show();
    $("#choose-action-card").hide(); 
  }

  function goBackPayRegistrationFee(elem,evt) {
    $("#main-card").show();
    $("#register-patient-card").hide();
  }

  function goBackPatientHistoryInfoCard (elem,evt) {
    $("#payment-history-card").show();
    $("#payment-history-info-card").hide();
    
  }

  function submitRegistrationAmountForm (elem,evt,id,patient_name,hospital_number,receipt_number,receipt_file) {
    evt.preventDefault();
    // console.log(elem.serializeArray());
    swal({
      title: 'Warning?',
      text: "Do You Want To Mark This Patient As Paid?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      var registration_amt = elem.querySelector("#registration_amt").value;
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
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_patient_as_paid'); ?>";
      
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

  function collectRegistrationFee (elem,evt) {
    
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
          $("#register-patient-card .card-body").html(response.messages);

          $("#choose-action-card").hide();
          $("#register-patient-card").show();
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

  function goBackOwingPatientsWards (elem,evt) {
    $("#choose-action-card").show();
    $("#owing-patients-wards-card").hide();
    
  }

  function goBackPayOwingPatientsWards (elem,evt) {
    var str = "";
    $("#owing-patients-wards-card").show();
    $("#pay-owing-patients-wards-form").attr("data-id","");
    $("#pay-owing-patients-wards-form #ward-payment-info").html(str);
    $("#pay-owing-patients-wards-form #amount").val("");
    $("#pay-owing-patients-wards-card").hide();
  }

  function goBackOustandingBillsCard (elem,evt) {
    
    $("#choose-action-card").show();
    $("#outstanding-bills-card").hide();
    
  }

  function goBackPatientsOustandingBillsCard (elem,evt) {
    selected_rows = [];
    $("#outstanding-bills-card").show();
    $("#patients-outstanding-bills-card").hide();
    $("#proceed-bills-selection-btn").hide("fast");
  }

  function goBackPatientHistoryCard (elem,evt) {
    $("#choose-action-card").show();
    $("#payment-history-card").hide();
    
  }

  function checkAll(elem,evt){
    elem = $(elem);
    selected_rows = [];
    if(elem.prop('checked')){
      console.log(elem.parents("table").find('tbody input[type=checkbox]').length);
      elem.parents("table").find('tbody input[type=checkbox]').each(function(index, el) {
        var el = elem.parents("table").find('tbody input[type=checkbox]').eq(index);
        el.prop('checked', true);
        var id = el.attr("data-id");
        var amount = el.attr("data-amount");
        var data = {
          "id" : id,
          "amount" : amount
        };
        selected_rows.push(data);
      });
    }else{
      elem.parents("table").find('tbody input[type=checkbox]').each(function(index, el) {
        var el = elem.parents("table").find('tbody input[type=checkbox]').eq(index);
        el.prop('checked', false);
      });  
    }
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

  function proceedBillsSelection(elem,evt) {
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
    console.log(selected_rows)
    var num = selected_rows.length;
    if(num > 0){
      var sum = 0.0;
      var id_arr = [];
      for(var i = 0; i < num; i++){
        var amount = parseFloat(selected_rows[i].amount);
        sum += amount;
        sum = Math.round(sum * 1e12) / 1e12;
        id = selected_rows[i].id;
        id_arr.push(id);
      }
      
      swal({
        title: 'Success',
        text: "<p><span class='text-primary' style='font-style: italic;'>"+addCommas(num) +"</span> Record(s) Selected With Total Sum Of "+"<span class='text-primary' style='font-style: italic;'>"+addCommas(sum)+"</span>. Are You Sure You Want To Mark As Paid?</p>",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Proceed',
        cancelButtonText : "No"
      }).then(function(){
        if(id_arr != []){
          $(".spinner-overlay").show();
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_clinic_patient_outstanding_as_paid'); ?>";
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : {
              "ids" : id_arr
            },
            success : function (response) {
              console.log(response)
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
                var hospital_number = response.hospital_number;
                var sum = response.sum;
                var balance = response.balance;

                var registration_num = response.registration_num;
                var reason = response.reason;
                var discount = response.discount;
                var teller_full_name = response.teller_full_name;
                
                
                var pdf_data = {
                  'balance' : balance,
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
                  "clinic" : true,
                  "registration_num" : registration_num,
                  "reason" : reason,
                  "discount" : discount,
                  "teller_full_name" : teller_full_name
                };
                $(".spinner-overlay").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_clinic') ?>";
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
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            }
          });
        }
      });
    }else{
      swal({
        title: 'Error',
        text: "At Least One CheckBox Must Be Selected To Proceed",
        type: 'error'
      })
    }
  }

  function checkBoxEvt (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    var amount = elem.attr("data-amount");
    var data = {
      "id" : id,
      "amount" : amount
    };
    var isChecked = false;

    if(elem.prop('checked')){
      isChecked = true;
    }

    if(isChecked){
      selected_rows.push(data);
    }else{
      var index = selected_rows.map(function(obj, index) {
          if(obj.id === id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        selected_rows.splice(index, 1);
      }
    }

    // console.log(selected_rows)


  }

  function viewOutstandingPayments(elem,evt,patient_id){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_clinic_patient_outstanding_bills'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&patient_id="+patient_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#patients-outstanding-bills-card .card-body").html(response.messages);

          $("#outstanding-bills-card").hide();
          $("#patients-outstanding-bills-card").show();
          $("#patients-outstanding-bills-table").DataTable({
            "paging":   false,
            "info":     false
          });
          $("#proceed-bills-selection-btn").show("fast");
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function collectOutstandingBills (elem,evt) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_clinic_patients_outstanding_bills'); ?>";
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
          $("#outstanding-bills-card .card-body").html(response.messages);

          $("#choose-action-card").hide();
          $("#outstanding-bills-card").show();
          $("#outstanding-bills-card #outstanding-bills-table").DataTable();
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function payAdmissionFee(elem,evt,id){
    elem = $(elem);
    var patient_name = elem.attr("data-patient-name");
    if(patient_name != ""){
      $("#enter-ward-admission-info-card .card-title").html("Enter Admission Info For <em class='text-primary'>" + patient_name + "</em>");
      $("#enter-ward-admission-info-card #enter-ward-admission-info-form").attr("data-id",id);
      $("#enter-ward-admission-info-card").show();
      $("#owing-patients-wards-card").hide();
    }
  }

  function submitEnterWardAdmissionInfoForm (elem,evt) {
    elem = $(elem);
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
    var ward_record_id = elem.attr("data-id");
    if(ward_record_id != ""){
      var admission_duration = elem.find('#ward_admission_duration').val();
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_ward_admission_info'); ?>";
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.ward_admission_fee != "" && response.ward_admission_duration != "" && response.ward_admission_grace_days){
            var ward_admission_fee = response.ward_admission_fee;
            var ward_admission_duration = response.ward_admission_duration;
            var ward_admission_grace_days = response.ward_admission_grace_days;

            var amount_for_a_day = ward_admission_fee / ward_admission_duration;

            var amount_to_be_paid_by_user = parseFloat((amount_for_a_day * admission_duration).toFixed(2));

            var text = "<h4 style='text-transform:capitalize;'>Ward Admission Fee For <em class='text-primary'>" + admission_duration + "</em> day(s): <em class='text-primary'>" + addCommas(amount_to_be_paid_by_user) + "</em>"+" With "+ward_admission_grace_days+" Grace Days.</h4>";
            text += "<p>Choose Payment Option: </p>";
            swal({
              title: 'Choose Action',
              text: text,
              type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Pay Now',
              cancelButtonText : "Pay Later"
            }).then(function(){
              $(".spinner-overlay").show();
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_current_user_type_for_ward_admission_payment_pay_now'); ?>";
              $.ajax({
                url : url,
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : "ward_record_id="+ward_record_id,
                success : function (response) {
                  $(".spinner-overlay").hide();
                  if(response.success && response.user_type != ""){
                    var user_type = response.user_type;
                    if(user_type == "pfp"){
                      var part_payment_discount_percentage = response.part_payment_discount_percentage;
                      var amount = amount_to_be_paid_by_user - ((part_payment_discount_percentage / 100) * amount_to_be_paid_by_user);
                      amount = parseFloat(amount.toFixed(2));
                      var text = "<h4>Part Payment Discount Percentage: <em class='text-primary'>" + part_payment_discount_percentage + "%</em> <br> Amount Due: <em class='text-primary'>" + addCommas(amount) + "</em></h4>";
                      text += "<p>Are You Sure You Want To Proceed?</p>";
                      swal({
                        title: 'Proceed?',
                        text: text,
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes Proceed',
                        cancelButtonText : "Cancel"
                      }).then(function(){
                        proceedWithWardsAdmissionPaymentPayNow(ward_record_id,admission_duration)
                      });

                    }else{
                      proceedWithWardsAdmissionPaymentPayNow(ward_record_id,admission_duration)
                    }
                  }else{
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "warning"  
                    });
                  }
                },error : function () {
                    $(".spinner-overlay").hide();
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "danger"  
                    });
                  }
                });
            },function(dismiss){
              if(dismiss == 'cancel'){
                $(".spinner-overlay").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/pay_owing_patient_admission_pay_later'); ?>";
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "id="+ward_record_id+"&admission_duration="+admission_duration,
                  success : function (response) {
                    $(".spinner-overlay").hide();
                    if(response.success){
                      $.notify({
                      message:"Admission Fees Marked As Paid Successfully"
                      },{
                        type : "success"  
                      });
                      setTimeout(function () {
                        document.location.reload();
                      }, 1500);
                    }else{
                      $.notify({
                      message:"Sorry Something Went Wrong."
                      },{
                        type : "warning"  
                      });
                    }
                  },error : function () {
                    $(".spinner-overlay").hide();
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "danger"  
                    });
                  } 
                });   
              }
            });
          }else{
            $.notify({
            message:"Sorry Something Went Wrong."
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong."
          },{
            type : "danger"  
          });
        }
      });  
    }
  }

  function proceedWithWardsAdmissionPaymentPayNow (ward_record_id,admission_duration) {
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
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/pay_owing_patient_admission_pay_now'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "id="+ward_record_id+"&admission_duration="+admission_duration,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true){
          var patient_name = response.patient_name;
          var receipt_number = response.receipt_number;
          var facility_state = '<?php echo $health_facility_state; ?>';
          var facility_country = '<?php echo $health_facility_country; ?>';
          var facility_address = response.facility_address;
          var facility_name = '<?php echo $health_facility_name; ?>';
          var date = response.date;
          var registration_num = response.registration_num;
          var sum = response.sum;
          var receipt_file = response.receipt_file;
          var teller_full_name = response.teller_full_name;
          var discount = response.discount;
          
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
            "registration_num" : registration_num,
            "facility_address" : facility_address,
            "date" : date,
            'mod' : 'teller',
            "receipt_file" : receipt_file,
            "clinic" : true,
            'teller_full_name' : teller_full_name,
            'reason' : "Payment For Ward Admission Fees",
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
          $.notify({
          message:"Sorry Something Went Wrong."
          },{
            type : "warning"  
          });
        }
      },error : function () {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    }); 
  }

  function goBackEnterWardAdmissionInfoCard (elem,evt) {
    $("#enter-ward-admission-info-card").hide();
    $("#owing-patients-wards-card").show();
  }

  function collectAdmissionFee (elem,evt) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_ward_patients_owing_admission_fee'); ?>";
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
          $("#owing-patients-wards-card .card-body").html(response.messages);

          $("#choose-action-card").hide();
          $("#owing-patients-wards-card").show();
          $("#owing-patients-table").DataTable();
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function viewPaymentHistory (elem,evt) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_clinic_payment_history'); ?>";
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
          $("#payment-history-card .card-body").html(response.messages);

          $("#choose-action-card").hide();
          $("#payment-history-card").show();
          $("#payment-history-table").DataTable();
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function loadPaymentsForDate(elem,evt,date) {
    console.log(date);

    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_clinic_payment_history_by_day'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&date="+date,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#payment-history-info-card .card-title").html("Payments Made On " + date);
          $("#payment-history-info-card .card-body").html(response.messages);
          $("#payment-history-card").hide();
          $("#payment-history-info-card").show();
          $("#payment-history-info-table").DataTable();
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function viewPatientsAwaitingRegistrationPayment (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_awaiting_registration_payment_clinic'); ?>";
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
          $("#choose-action-card").hide("fast");
          $("#patients-awaiting-registration-payment-card .card-body").html(messages);
          $("#patients-awaiting-registration-payment-card #registered-patients-table").DataTable();
          $("#patients-awaiting-registration-payment-card").show("fast");
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
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

  function goBackPatientsAwaitinRegistrationPaymentCard (elem,evt) {
    $("#choose-action-card").show("fast");
    
    $("#patients-awaiting-registration-payment-card").hide("fast");
  }

  function performPaymentOnPatientMini (elem,evt,id) {
    elem = $(elem);
    var patient_name = elem.attr("data-patient-name");
    if(id != ""){
      patient_facility_id = id;
      patient_full_name = patient_name;
      $("#process-registration-payment-for-patient-modal .modal-title").html("Process Registration Payment For " + patient_name);
      $("#process-registration-payment-for-patient-modal").modal("show");
    }
  }

  function submitRegistrationAmountForm (elem,evt) {
    evt.preventDefault();
    elem = $(elem);
    // console.log(elem.serializeArray());
    var registration_amount = elem.find("#registration_amount").val();
    swal({
      title: 'Warning?',
      text: "Do You Want To Pay "+ addCommas(registration_amount) + " For " + patient_full_name + "'s' As Registration Fee?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      
      console.log(registration_amount);
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
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_registration_payment_mini'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "registration_amount="+registration_amount + "&patient_facility_id="+patient_facility_id,
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
            var registration_num = response.registration_num;
            var sum = response.sum;
            var receipt_file = response.receipt_file;
            var teller_full_name = response.teller_full_name;
            
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
              "registration_num" : registration_num,
              "facility_address" : facility_address,
              "date" : date,
              'mod' : 'teller',
              "receipt_file" : receipt_file,
              "clinic" : true,
              'teller_full_name' : teller_full_name,
              'reason' : "Payment For Clinic Registration Fees",
              'discount' : 0
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
          }else if(response.not_registered){
            swal({
              title: 'Error',
              text: "This Patient Is Not Registered In This Facility",
              type: 'error'
              
            })
          }else if(response.already_paid){
            swal({
              title: 'Error',
              text: "This Patient Has Already Paid Registration Fees",
              type: 'error'
              
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

  function performPaymentOnPatientStandard (elem,evt,id) {
    elem = $(elem);
    var patient_name = elem.attr("data-patient-name");
    if(id != ""){
      patient_facility_id = id;
      patient_full_name = patient_name;
      swal({
        title: 'Proceed?',
        text: "<p style='text-transform:capitalize;'>Are You Sure You Want To Mark " + patient_full_name + " As Paid?</p>",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText : "No"
      }).then(function(){
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
            
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_registration_payment_standard'); ?>";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "patient_facility_id="+patient_facility_id,
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
              var registration_num = response.registration_num;
              var sum = response.sum;
              var receipt_file = response.receipt_file;
              var teller_full_name = response.teller_full_name;
              
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
                "registration_num" : registration_num,
                "facility_address" : facility_address,
                "date" : date,
                'mod' : 'teller',
                "receipt_file" : receipt_file,
                "clinic" : true,
                'teller_full_name' : teller_full_name,
                'reason' : "Payment For Clinic Registration Fees",
                'discount' : 0
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
            }else if(response.not_registered){
              swal({
                title: 'Error',
                text: "This Patient Is Not Registered In This Facility",
                type: 'error'
                
              })
            }else if(response.already_paid){
              swal({
                title: 'Error',
                text: "This Patient Has Already Paid Registration Fees",
                type: 'error'
                
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
      });
    }
  }

  function collectConsultationFee (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_awaiting_consultation_payment_clinic'); ?>";
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
          $("#choose-action-card").hide("fast");
          $("#patients-awaiting-consultation-payment-card .card-body").html(messages);
          $("#patients-awaiting-consultation-payment-card #registered-patients-table").DataTable();
          $("#patients-awaiting-consultation-payment-card").show("fast");
        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
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

  function goBackPatientsAwaitinconsultationPaymentCard (elem,evt) {
    $("#choose-action-card").show("fast");
    
    $("#patients-awaiting-consultation-payment-card").hide("fast");
  }

  function performConsultationPaymentOnPatientMini (elem,evt,id) {
    elem = $(elem);
    if(id != "" && elem.attr("data-patient-name") != "" && elem.attr("data-user-type") != "" && elem.attr("data-consultation-fee") != ""){
      patient_full_name = elem.attr("data-patient-name");
      consultation_id = id;
      var user_type = elem.attr("data-user-type");
      var consultation_fee = elem.attr("data-consultation-fee");
      var part_fee_paying_percentage_discount = elem.attr("data-part-fee-paying-percentage-discount");
      $("#enter-consultation-fee-mini-modal .modal-title").html("Enter Consultation Fee For " + patient_full_name);
      $("#enter-consultation-fee-mini-modal #enter-consultation-fee-mini-form").attr("data-user-type",user_type);
      $("#enter-consultation-fee-mini-modal #enter-consultation-fee-mini-form").attr("data-part-fee-paying-percentage-discount",part_fee_paying_percentage_discount);
      $("#enter-consultation-fee-mini-modal").modal("show");
    }
  }

  function submitConsultationAmountForm (elem,evt) {
    elem = $(elem);
    evt.preventDefault();

    var consultation_amount = elem.find('#consultation_amount').val();
    var user_type = elem.attr("data-user-type");
    var part_fee_paying_percentage_discount = elem.attr("data-part-fee-paying-percentage-discount");
    if(consultation_amount != "" && user_type != ""){
      swal({
        title: 'Choose Action',
        text: "<h4>Consultation Fee: <em class='text-primary'>"+addCommas(consultation_amount)+"</em></h4><p style='text-transform:capitalize;'>Choose Payment Option For "+patient_full_name+"'s  Consultation Fees : </p>",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Pay Now',
        cancelButtonText : "Pay Later"
      }).then(function(){

        if(user_type == "pfp" && part_fee_paying_percentage_discount != 0){
          var amount_to_pay = consultation_amount - ((part_fee_paying_percentage_discount / 100) * consultation_amount);
          swal({
            title: 'Proceed?',
            text: "<h4><p>Part Fee Paying Percentage Discount: <em class='text-primary'>"+part_fee_paying_percentage_discount+"%</em></p> <p> <p>Amount To Pay: <em class='text-primary'>"+addCommas(amount_to_pay)+"</em></p> </p></h4>Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
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
                
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_consultation_payment_mini'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_amount="+consultation_amount + "&consultation_id="+consultation_id,
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
                  var registration_num = response.registration_num;
                  var sum = response.sum;
                  var receipt_file = response.receipt_file;
                  var teller_full_name = response.teller_full_name;
                  var discount = response.discount;
                  
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
                    "registration_num" : registration_num,
                    "facility_address" : facility_address,
                    "date" : date,
                    'mod' : 'teller',
                    "receipt_file" : receipt_file,
                    "clinic" : true,
                    'teller_full_name' : teller_full_name,
                    'reason' : "Payment For Clinic Consultation Fees",
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
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error',
                    text: "This Consultation Is Invalid",
                    type: 'error'
                  })
                }else if(response.already_paid){
                  swal({
                    title: 'Error',
                    text: "This Patient Has Already Paid Consultation Fees",
                    type: 'error'
                    
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
        }else{

          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
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
                
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_consultation_payment_mini'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_amount="+consultation_amount + "&consultation_id="+consultation_id,
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
                  var registration_num = response.registration_num;
                  var sum = response.sum;
                  var receipt_file = response.receipt_file;
                  var teller_full_name = response.teller_full_name;
                  var discount = response.discount;
                  
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
                    "registration_num" : registration_num,
                    "facility_address" : facility_address,
                    "date" : date,
                    'mod' : 'teller',
                    "receipt_file" : receipt_file,
                    "clinic" : true,
                    'teller_full_name' : teller_full_name,
                    'reason' : "Payment For Clinic Consultation Fees",
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
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error',
                    text: "This Consultation Is Invalid",
                    type: 'error'
                  })
                }else if(response.already_paid){
                  swal({
                    title: 'Error',
                    text: "This Patient Has Already Paid Consultation Fees",
                    type: 'error'
                    
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

      },function(dismiss){
        if(dismiss == 'cancel'){
          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/mark_patient_consultation_fee_as_paid_pay_later_mini'); ?>";
            $(".spinner-overlay").show();
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_id="+consultation_id+"&consultation_amount="+consultation_amount,
              success : function (response) {
                console.log(response)
                $(".spinner-overlay").hide();
                if(response.success){
                  $.notify({
                  message:"Consultation Fee Marked As Paid. Patient Bill Has Been Added To His Outstanding Bills"
                  },{
                    type : "success"  
                  });
                  setTimeout(function () {
                    document.location.reload();
                  },1500);
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error!',
                    text: "Invalid Consultation",
                    type: 'error'
                  })
                }else if(response.consultation_already_paid){
                  swal({
                    title: 'Error!',
                    text: "This Consultation Has Already Been Paid For",
                    type: 'error'
                  })
                }else{
                  $.notify({
                  message:"Sorry Something Went Wrong."
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
          });
        }
      });
    }
  }

  function performConsultationPaymentOnPatientStandard (elem,evt,id) {
    elem = $(elem);
    if(id != "" && elem.attr("data-patient-name") != "" && elem.attr("data-user-type") != "" && elem.attr("data-consultation-fee") != ""){
      patient_full_name = elem.attr("data-patient-name");
      consultation_id = id;
      var user_type = elem.attr("data-user-type");
      var consultation_fee = elem.attr("data-consultation-fee");
      
      var part_fee_paying_percentage_discount = elem.attr("data-part-fee-paying-percentage-discount");

      swal({
        title: 'Choose Action',
        text: "<h4>Consultation Fee: <em class='text-primary'>"+addCommas(consultation_fee)+"</em></h4><p style='text-transform:capitalize;'>Choose Payment Option For "+patient_full_name+"'s  Consultation Fees : </p>",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Pay Now',
        cancelButtonText : "Pay Later"
      }).then(function(){
        if(user_type == "pfp" && part_fee_paying_percentage_discount != 0){
          var amount_to_pay = consultation_fee - ((part_fee_paying_percentage_discount / 100) * consultation_fee);
          swal({
            title: 'Proceed?',
            text: "<h4><p>Part Fee Paying Percentage Discount: <em class='text-primary'>"+part_fee_paying_percentage_discount+"%</em></p> <p> <p>Amount To Pay: <em class='text-primary'>"+addCommas(amount_to_pay)+"</em></p> </p></h4>Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
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
                
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_consultation_payment_standard'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_id="+consultation_id,
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
                  var registration_num = response.registration_num;
                  var sum = response.sum;
                  var receipt_file = response.receipt_file;
                  var teller_full_name = response.teller_full_name;
                  var discount = response.discount;
                  
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
                    "registration_num" : registration_num,
                    "facility_address" : facility_address,
                    "date" : date,
                    'mod' : 'teller',
                    "receipt_file" : receipt_file,
                    "clinic" : true,
                    'teller_full_name' : teller_full_name,
                    'reason' : "Payment For Clinic Consultation Fees",
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
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error',
                    text: "This Consultation Is Invalid",
                    type: 'error'
                  })
                }else if(response.already_paid){
                  swal({
                    title: 'Error',
                    text: "This Patient Has Already Paid Consultation Fees",
                    type: 'error'
                    
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
        }else{
          
          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
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
                
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/process_clinic_consultation_payment_standard'); ?>";
            
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_id="+consultation_id,
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
                  var registration_num = response.registration_num;
                  var sum = response.sum;
                  var receipt_file = response.receipt_file;
                  var teller_full_name = response.teller_full_name;
                  var discount = response.discount;
                  
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
                    "registration_num" : registration_num,
                    "facility_address" : facility_address,
                    "date" : date,
                    'mod' : 'teller',
                    "receipt_file" : receipt_file,
                    "clinic" : true,
                    'teller_full_name' : teller_full_name,
                    'reason' : "Payment For Clinic Consultation Fees",
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
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error',
                    text: "This Consultation Is Invalid",
                    type: 'error'
                  })
                }else if(response.already_paid){
                  swal({
                    title: 'Error',
                    text: "This Patient Has Already Paid Consultation Fees",
                    type: 'error'
                    
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
      },function(dismiss){
        if(dismiss == 'cancel'){
          swal({
            title: 'Proceed?',
            text: "Are You Sure You Want To Proceed?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText : "No"
          }).then(function(){
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/mark_patient_consultation_fee_as_paid_pay_later_standard'); ?>";
            $(".spinner-overlay").show();
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "consultation_id="+consultation_id,
              success : function (response) {
                console.log(response)
                $(".spinner-overlay").hide();
                if(response.success){
                  $.notify({
                  message:"Consultation Fee Marked As Paid. Patient Bill Has Been Added To His Outstanding Bills"
                  },{
                    type : "success"  
                  });
                  setTimeout(function () {
                    document.location.reload();
                  },1500);
                }else if(response.invalid_consultation){
                  swal({
                    title: 'Error!',
                    text: "Invalid Consultation",
                    type: 'error'
                  })
                }else if(response.consultation_already_paid){
                  swal({
                    title: 'Error!',
                    text: "This Consultation Has Already Been Paid For",
                    type: 'error'
                  })
                }else{
                  $.notify({
                  message:"Sorry Something Went Wrong."
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
          });
        }
      });
    }
  }

  function collectPaymentForWardServices (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patients_awaiting_ward_services_payment'); ?>";
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
          $("#patients-awaiting-ward-services-payment-card .card-body").html(messages);
          $("#patients-awaiting-ward-services-payment-card #patients-awaiting-ward-services-payment-table").DataTable();
          $("#choose-action-card").hide();
          $("#patients-awaiting-ward-services-payment-card").show();

        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
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

  function goBackPatientsAwaitingWardServicesPaymentCard (elem,evt) {
    $("#choose-action-card").show();
    $("#patients-awaiting-ward-services-payment-card").hide();
  }

  function viewPatientAwaitingWardServicesPaymnet(elem,evt,patient_user_id) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_patient_awaiting_ward_services_payment'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&patient_user_id="+patient_user_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != "" && response.patient_full_name != "" && response.registration_num != ""){
          var messages = response.messages;
          var patient_full_name = response.patient_full_name;
          var registration_num = response.registration_num;

          var text = "All Requested Services<br>Patient Name: " + patient_full_name + "<br> Registration Num: <em class='text-primary'>"+registration_num+"</em>";

          $("#patient-awaiting-ward-services-payment-card .card-title").html(text);
          $("#patient-awaiting-ward-services-payment-card .card-body").html(messages);
          $("#patient-awaiting-ward-services-payment-card #patient-awaiting-ward-services-payment-table").DataTable();
          $("#patients-awaiting-ward-services-payment-card").hide();
          $("#patient-awaiting-ward-services-payment-card").show();

        }else{
          $.notify({
          message:"Sorry Something Went Wrong."
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

  function goBackPatientAwaitingWardServicesPaymentCard (elem,evt) {
    $("#patients-awaiting-ward-services-payment-card").show();
    $("#patient-awaiting-ward-services-payment-card").hide();
  }

  function proceedWithWardServicePayment(elem,evt,patient_user_id){
    elem = $(elem);
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
    var id = elem.attr("data-id");
    var user_type = elem.attr("data-user-type");
    var part_payment_discount_percentage = elem.attr("data-part-payment-discount-percentage");
    var amount = elem.attr("data-amount");

    if(patient_user_id != "" && id != "" && user_type != "" && part_payment_discount_percentage != "" && amount != ""){
      swal({
        title: 'Choose Action',
        text: 'Choose Payment Option: ',
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Pay Now',
        cancelButtonText : "Pay Later"
      }).then(function(){
        if(user_type == "pfp"){
          sub_total_amount_due = parseFloat((amount - ((part_payment_discount_percentage / 100) * amount)).toFixed(2));
          var text = "Amount Due: <em class='text-primary'>" + addCommas(amount) + "</em><br> Part Payment Discount Percentage: " + part_payment_discount_percentage + "% <br>Sub Total Amount Due: <em class='text-primary'>" +  addCommas(sub_total_amount_due) + "</em><br> Are You Sure You Want To Proceed?";
        }else{
          var text = "Sub Total Amount Due: <em class='text-primary'>" +  addCommas(amount) + "</em><br> Are You Sure You Want To Proceed?";

        }

        swal({
          title: 'Proceed?',
          text: text,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes Proceed!',
          cancelButtonText : "Cancel"
        }).then(function(){
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/pay_owing_ward_service_pay_now'); ?>";
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "id="+id,
            success : function (response) {
              
              if(response.success){
                
                var patient_name = response.patient_name;
                var receipt_number = response.receipt_number;
                var facility_state = '<?php echo $health_facility_state; ?>';
                var facility_country = '<?php echo $health_facility_country; ?>';
                var facility_address = response.facility_address;
                var facility_name = '<?php echo $health_facility_name; ?>';
                var date = response.date;
                var registration_num = response.registration_num;
                var sum = response.sum;
                var receipt_file = response.receipt_file;
                var teller_full_name = response.teller_full_name;
                var discount = response.discount;
                var reason = response.reason;
                
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
                  "registration_num" : registration_num,
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true,
                  'teller_full_name' : teller_full_name,
                  'reason' : reason,
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
                    $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "danger"  
                    });
                  }
                });
              }else{
                $.notify({
                message:"Sorry Something Went Wrong."
                },{
                  type : "warning"  
                });
              }
            },error : function () {
              $(".spinner-overlay").hide();
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            } 
          });
        });

      },function(dismiss){
        if(dismiss == 'cancel'){
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/pay_owing_ward_service_pay_later'); ?>";
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "id="+id,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success){
                $.notify({
                message:"Service Fees Marked As Paid For Successfully"
                },{
                  type : "success"  
                });
                $("#patient-awaiting-ward-services-payment-card").hide();
                viewPatientAwaitingWardServicesPaymnet(this,event,patient_user_id)
              }else{
                $.notify({
                message:"Sorry Something Went Wrong."
                },{
                  type : "warning"  
                });
              }
            },error : function () {
              $(".spinner-overlay").hide();
              $.notify({
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            } 
          });
        }
      });
    }

  }

  function collectPharmacyPayments (elem,evt) {
    $("#choose-action-card").hide();
    $("#collect-payment-card").show();
  }

  function goCollectPayment(elem,evt){
    $("#choose-action-card").show();
    $("#collect-payment-card").hide();
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
          $("#choose-action-card").hide();
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

  function goBackOutstandingPayments (elem,evt) {
    $("#choose-action-card").show();
   
    $("#outstanding-payments-card").hide();
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

  function goBackMakePayment (elem,evt) {
    $("#outstanding-payments-card").show();
    
    $("#make-payment-card").hide();
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

  function collectMortuaryPayments (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_patients_outstanding_bills'); ?>";
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
          $("#outstanding-mortuary-bills-card .card-body").html(response.messages);
          $("#choose-action-card").hide();
          $("#outstanding-mortuary-bills-card").show();
          $("#outstanding-mortuary-bills-card #outstanding-bills-table").DataTable();
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
        message:"Sorry Something Went Wrong."
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackOustandingMortuaryBillsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#outstanding-mortuary-bills-card").hide();
  }

  function loadMortuaryOutstandingPaymentInfo(elem,evt,mortuary_record_id) {
    evt.preventDefault();
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_patient_outstanding_bills'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&mortuary_record_id="+mortuary_record_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $("#outstanding-bills-info-card .card-body").html(response.messages);
          $("#outstanding-mortuary-bills-card").hide();
          $("#outstanding-bills-info-card").show();
          $("#mortuary-outstanding-bills-table").DataTable();
          $("#proceed-mortuary-bills-selection-btn").show("fast");
        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "warning"  
          });
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection."
        },{
          type : "danger"  
        });
      }
    });
  }

  function goBackOustandingBillsInfoCard (elem,evt) {
    mortuary_selected_rows = [];
    $("#outstanding-mortuary-bills-card").show();
    $("#outstanding-bills-info-card").hide();
    $("#proceed-mortuary-bills-selection-btn").hide("fast");
  }

  function proceedMortuaryBillsSelection(elem,evt) {
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
    console.log(mortuary_selected_rows)
    var num = mortuary_selected_rows.length;
    if(num > 0){
      var sum = 0.0;
      var id_arr = [];
      for(var i = 0; i < num; i++){
        var amount = parseFloat(mortuary_selected_rows[i].amount);
        sum += amount;
        sum = Math.round(sum * 1e12) / 1e12;
        id = mortuary_selected_rows[i].id;
        id_arr.push(id);
      }
      console.log(id_arr)
      swal({
        title: 'Success',
        text: "<p><span class='text-primary' style='font-style: italic;'>"+addCommas(num) +"</span> Record(s) Selected With Total Sum Of "+"<span class='text-primary' style='font-style: italic;'>"+addCommas(sum)+"</span>. Are You Sure You Want To Mark As Paid?</p>",
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Proceed',
        cancelButtonText : "No"
      }).then(function(){
        if(id_arr != []){
          $(".spinner-overlay").show();
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_mortuary_patient_outstanding_as_paid'); ?>";
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : {
              "ids" : id_arr
            },
            success : function (response) {
              console.log(response)
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
                var mortuary_number = response.mortuary_number;
                var sum = response.sum;
                var balance = response.balance;

                var registration_num = response.registration_num;
                var reason = "Payment Of Outstanding Mortuary Services";
                var discount = response.discount;
                var teller_full_name = response.teller_full_name;
                
                
                var pdf_data = {
                  'balance' : balance,
                  'logo' : company_logo,
                  'color' : <?php echo $color; ?>,
                  'sum' : sum,
                  "patient_name" : patient_name,
                  "receipt_number" : receipt_number,
                  "facility_state" : facility_state,
                  'facility_id' : "<?php echo $health_facility_id; ?>",
                  "facility_country" : facility_country,
                  "facility_name" : facility_name,
                  "mortuary_number" : mortuary_number,
                  "facility_address" : facility_address,
                  "date" : date,
                  'mod' : 'teller',
                  "receipt_file" : receipt_file,
                  "clinic" : true,
                  "registration_num" : registration_num,
                  "reason" : reason,
                  "discount" : discount,
                  "teller_full_name" : teller_full_name
                };
                $(".spinner-overlay").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_clinic') ?>";
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
              message:"Sorry Something Went Wrong."
              },{
                type : "danger"  
              });
            }
          });
        }
      });
    }else{
      swal({
        title: 'Error',
        text: "At Least One CheckBox Must Be Selected To Proceed",
        type: 'error'
      })
    }
  }

  function checkAllMortuary(elem,evt){
    elem = $(elem);
    mortuary_selected_rows = [];
    if(elem.prop('checked')){
      console.log(elem.parents("table").find('tbody input[type=checkbox]').length);
      elem.parents("table").find('tbody input[type=checkbox]').each(function(index, el) {
        var el = elem.parents("table").find('tbody input[type=checkbox]').eq(index);
        el.prop('checked', true);
        var id = el.attr("data-id");
        var amount = el.attr("data-amount");
        var data = {
          "id" : id,
          "amount" : amount
        };
        mortuary_selected_rows.push(data);
      });
    }else{
      elem.parents("table").find('tbody input[type=checkbox]').each(function(index, el) {
        var el = elem.parents("table").find('tbody input[type=checkbox]').eq(index);
        el.prop('checked', false);
      });  
    }
  }

  function checkBoxEvtMortuary (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    var amount = elem.attr("data-amount");
    var data = {
      "id" : id,
      "amount" : amount
    };
    var isChecked = false;

    if(elem.prop('checked')){
      isChecked = true;
    }

    if(isChecked){
      mortuary_selected_rows.push(data);
    }else{
      var index = mortuary_selected_rows.map(function(obj, index) {
          if(obj.id === id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        mortuary_selected_rows.splice(index, 1);
      }
    }
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

              <div class="card" id="outstanding-bills-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOustandingBillsInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Mortuary Patients Outstanding Bills</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="outstanding-mortuary-bills-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOustandingMortuaryBillsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">All Oustanding Mortuaary Bills</h3>
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

              <div class="card" id="outstanding-payments-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Awaiting Payment: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackOutstandingPayments(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
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

              <div class="card" id="patients-awaiting-ward-services-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Patients Awaiting Payment For Ward Services</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPatientsAwaitingWardServicesPaymentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="patient-awaiting-ward-services-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Requested Services</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPatientAwaitingWardServicesPaymentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="enter-ward-admission-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Enter Admission Info For </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEnterWardAdmissionInfoCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php
                  $attr = array('id' => 'enter-ward-admission-info-form','onsubmit' => 'submitEnterWardAdmissionInfoForm(this,event)');
                  echo form_open('',$attr);
                  ?>

                    <div class="form-group">
                      <label for="ward_admission_duration">Enter Admission Duration (days): </label>
                      <input type="number" class="form-control" name="ward_admission_duration" id="ward_admission_duration">
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
                </div> 
              </div>

              <div class="card" id="patients-awaiting-consultation-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Patients Awaiting consultation Payment</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPatientsAwaitinconsultationPaymentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="patients-awaiting-registration-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Patients Awaiting Registration Payment</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPatientsAwaitinRegistrationPaymentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 70px;" id="quest">Choose Action: </h4>
                  <button onclick="performFunctions(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="register-patient-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button onclick="goBackPayRegistrationFee(this,event)" class="btn btn-warning">Go Back</button>

                  <h4 style="margin-bottom: 40px;" id="quest">Registered Patients With Payment Pending</h4>
                  
                </div>
              </div>


              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning" onclick="goBackChooseAction(this,event)">Go Back</button>
                  <h4 class="card-title">Choose Action: </h4>
                </div>
                <div class="card-body">
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
                            <td onclick="viewPatientsAwaitingRegistrationPayment(this,event)">Collect Registration Fee</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td onclick="collectConsultationFee(this,event)">Collect Consultation Fee</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td onclick="collectOutstandingBills(this,event)">Collect Outstanding Bills</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td onclick="collectAdmissionFee(this,event)">Collect Wards Admission Fee</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td onclick="collectPaymentForWardServices(this,event)">Collect Payment For Wards Services</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td onclick="otcPatients(this,event)">Collect Pharmacy Payments</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td onclick="collectMortuaryPayments(this,event)">Collect Mortuary Payments</td>
                          </tr>
                          
                        </tbody>
                      </table>
                  </div>
                </div>
              </div>

              <div class="card" id="owing-patients-wards-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOwingPatientsWards(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">All Patients Owing Admission Fees</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="outstanding-bills-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOustandingBillsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">All Patients With Oustanding Bills</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="patients-outstanding-bills-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPatientsOustandingBillsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Patients Oustanding Bills</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              

              <div class="card" id="payment-history-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPatientHistoryCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="payment-history-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPatientHistoryInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="pay-owing-patients-wards-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPayOwingPatientsWards(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Enter Amount To Pay</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'pay-owing-patients-wards-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/pay_owing_patient_admission',$attr);
                  ?>

                  <div id="ward-payment-info" style="margin-bottom: 50px;"></div>
                  <div class="form-group">
                    <label for="amount">Amount: </label>
                    <input type="number" class="form-control" id="amount" name="amount" >
                  </div>

                  <input type="submit" class="btn btn-primary">

                  </form>
                </div>
              </div>

            </div>
          </div>

          <div id="proceed-mortuary-bills-selection-btn" onclick="proceedMortuaryBillsSelection(this,event)" rel="tooltip"  data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

            </div>
          </div>

          <div id="proceed-bills-selection-btn" onclick="proceedBillsSelection(this,event)" rel="tooltip"  data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

            </div>
          </div>
        </div>
      </div>
      
      </div>
      
      <div class="modal fade" data-backdrop="static" id="enter-consultation-fee-mini-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Enter Consultation Fee For  </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'enter-consultation-fee-mini-form','onsubmit' => 'submitConsultationAmountForm(this,event)');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="consultation_amount">Consultation Amount: </label>
                  <input type="number" class="form-control" name="consultation_amount" id="consultation_amount" required>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="process-registration-payment-for-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Process Registration Payment For </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
                $attr = array('id' => 'process-registration-payment-for-patient-form','onsubmit' => 'submitRegistrationAmountForm(this,event)');
                echo form_open('',$attr);
              ?>
                <div class="form-group">
                  <label for="registration_amount">Registration Amount: </label>
                  <input type="number" class="form-control" name="registration_amount" id="registration_amount" required>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                <input type="number" name="registration_amt" id="registration_amt" class="form-control">
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
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
       
      </footer>
  </div>
  
</body>
<script>
    $(document).ready(function() {
      $("#pay-owing-patients-wards-form").submit(function(evt) {
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
        var me  = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })

        form_data = form_data.concat({
          "name" : "type",
          "value" : "normal"
        })

        console.log(url)
        console.log(form_data)
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
              var receipt_file = response.receipt_file;
              var patient_name = response.patient_name;
              var receipt_number = response.receipt_number;
              var facility_state = '<?php echo $health_facility_state; ?>';
              var facility_country = '<?php echo $health_facility_country; ?>';
              var facility_address = response.facility_address;
              var facility_name = '<?php echo $health_facility_name; ?>';
              var date = response.date;
              var hospital_number = response.hospital_number;
              var sum = response.sum;
              var balance = response.balance;
              
              var pdf_data = {
                'balance' : balance,
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
              $(".spinner-overlay").show();
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/save_receipt_clinic') ?>";
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
            }else if(response.amount_too_big && response.admission_fee != ""){
              $.notify({
              message:"The Amount Entered Is Too Large. Admission Fee Is "+response.admission_fee
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
         
      });

      <?php if($this->session->admission_pay_later_successful){ ?>
       $.notify({
        message:"Patient Bill Has Been Successfully Added To His Outstanding Bills"
        },{
          type : "success"  
        });
     <?php } ?>
      
    });


</script>
