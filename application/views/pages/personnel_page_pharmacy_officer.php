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

  String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return (this.length > n) ? this.substr(0, n-1) + '&hellip;' : this;
      };
  var user_info = [];
  var selected_drugs = [];
  var patient_facility_id = "";
  var registered_patient = false;

  function goBackSelectDrugs (elem,evt) {
    user_info = [];
    selected_drugs = [];

    if(!registered_patient){
      $("#select-drugs-card .card-title").html("");
      
      $("#enter-patient-data-modal").modal("show");
      $("#perform-functions-card").show();
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }else{
      $("#select-drugs-card .card-title").html("");
      
      $("#perform-action-on-patient-modal").modal("hide");
      $("#registered-patients-card").show();
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }
  }

  function selectDrugsForPatient (elem,evt) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_info_for_drugs_selection_pharmacy') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&patient_facility_id="+patient_facility_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){ 
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy') ?>";
          var form_data = response.messages;
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
              if(response.success && response.messages != "" && response.user_info != ""){
                var messages = response.messages;
                var patient_name = response.patient_name;
                user_info = response.user_info;
                registered_patient = true;

                $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
                $("#select-drugs-card .card-body").html(messages);
                $("#perform-action-on-patient-modal").modal("hide");
                $("#registered-patients-card").hide();
                $("#select-drugs-card #select-drugs-table").DataTable();
                $("#select-drugs-card").show();
                $("#select-drugs-proceed-btn").show("fast");
              }else if(response.expired){
                $.notify({
                message:"Date Entered Must Be In The In The Future"
                },{
                  type : "warning"  
                });
              }else{
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
              }
            },error : function () {
              $(".spinner-overlay").hide();
              swal({
               title: 'Ooops',
                text: "Something Went Wrong. Please Check Your Internet Connection",
                type: 'error'
              })
            } 
          });
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function userTypeChangedEdit (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val);
    if(val == "fp"){
      $("#edit-patient-info-card #code-form-group").hide();
    }else if(val == "pfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }else if(val == "nfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }
  }

  function submitEditPatientInfoForm(elem,evt){
    elem = $(elem);
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_edit_patint_info_form') ?>";

    var form_data = elem.serializeArray();
    form_data = form_data.concat({
      "name" : "patient_facility_id",
      "value" : patient_facility_id
    })

    console.log(form_data)

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success){  
          $.notify({
          message:"Patient Type Edited Successfully"
          },{
            type : "success"  
          });
          setTimeout(function () {
            document.location.reload();
          }, 1000);
        }else if(response.invalid_code){
          $.notify({
          message:"Invalid Code Entered"
          },{
            type : "warning"  
          }); 
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

        // $('.table').DataTable();
      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
  }


  function goBackFromEditPatientInfoCard (elem,evt) {
    $("#registered-patients-card").show();
    $("#perform-action-on-patient-modal").modal("show"); 
    $("#edit-patient-info-card").hide();
  }

  function editPatientInfo(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/'.$fourth_addition.'/load_edit_patient_info_form') ?>";
    
   
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "patient_facility_id="+patient_facility_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          // console.log(messages)
          if(messages !== ""){  
            $("#registered-patients-card").hide();
            $("#perform-action-on-patient-modal").modal("hide"); 
            $("#edit-patient-info-card .card-body").html(messages);
            $("#edit-patient-info-card .my-select").selectpicker();
            $("#edit-patient-info-card").show();
          }

          // $('.table').DataTable();
        },
        error : function () {
          $(".spinner-overlay").hide();
          
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "danger"  
          });
        } 
      }); 
     
    }
  }

  function performActionOnPatient (elem,evt,id) {
    elem = $(elem);
    if(id != ""){
      patient_full_name = elem.attr("data-patient-name");
      patient_facility_id = id;
      $("#perform-action-on-patient-modal .modal-title").html("Choose Action To Perform On " + patient_full_name + ":")
      $("#perform-action-on-patient-modal").modal("show");
    }
  }

  function goBackRegisteredPatientsCard (elem,evt) {
    $("#perform-functions-card").show("fast");
    $("#registered-patients-card").hide("fast");
  }

  function transcribePrescriptionsHospital(elem,evt){
    swal({
      title: 'Choose Action',
      text: "Do You Want Select Drugs For: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Registered Patients',
      cancelButtonText: 'Over The Counter Patients'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_all_registered_patients_pharmacy'); ?>";
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
            $("#perform-functions-card").hide("fast");
            $("#registered-patients-card .card-body").html(messages);
            $("#registered-patients-card #registered-patients-table").DataTable();
            $("#registered-patients-card").show("fast");
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
    }, function(dismiss){
      if(dismiss == 'cancel'){
        registered_patient = false;
        $("#enter-patient-data-modal").modal("show");
      }
    });  
  }
  
  function goBackPerformFunctions (elem,evt) {
    $("#main-card").show();
    $("#perform-functions-card").hide();
  }

  

  function goBackSelectedInfoDrugs(elem,evt){
    $("#select-drugs-card").show();
    $("#select-drugs-proceed-btn").show("fast");
    $("#select-drugs-proceed-btn-2").hide("fast");
    $("#selected-drugs-info-card").hide();    
  }



  function calculatePrescription (dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i) {
    // console.log(main_store_quantity + " : " + dispensary_quantity);
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;

      quantity = Math.round(quantity * 100) / 100;



      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price = Math.round(price * 100) / 100;
        price = parseFloat(price).toFixed(2);
        if(price != null){
          var total_price = price * quantity;
          var html = "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
          html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
          html += "<p>Total Price: " + addCommas(total_price) +"</p>";
          if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
            var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
            console.log(total_store_quantity)
            if(quantity > total_store_quantity){
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
            }
          }
          $("#prescription-data-div .form-row").eq(i).find(".display-div").html(html);
        }
      }
    }
  }

  function calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,i) {
    // console.log(dosage)
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      var ret = {};
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;
      quantity = Math.round(quantity * 100) / 100;

      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price =  Math.round(price * 100) / 100;
        if(price != null){
          var total_price = price * quantity;
          ret = {
            'quantity' : quantity,
            'total_price' : total_price
          };
        }
      }

      return ret;
    }
  }

  function selectDrugsProceed2 () {
    var proceed = false;

    for(var i = 0; i < selected_drugs.length; i++) { 
      var is_poison = selected_drugs[i].is_poison; 
      if(is_poison == 1){ 
        proceed = false;
        break; 
      }else{
        proceed = true;
      }
    }

    if(!proceed){
      if(user_info.clinician !== ""){
        proceed = true;
      }
    }

    console.log(proceed)
    
    // evt.preventDefault();
    if(proceed){
      var me = $("#prescription-data-div");
      var form_data = {
        drugs_info : []
      };
      
      var drugs_info = [];
      var num = 0;
      var sum = 0;
      var total_quantity = 0;
      me.find(".form-row").each(function(index, el) {
        var el = $(el);
        var id = el.attr("data-id");
        var price = selected_drugs[index].price;
        var dosage = el.find(".dosage").val();
        var frequency_num = el.find(".frequency_num").val();
        var frequency_time = el.find(".frequency_time").val();
        var duration_num = el.find(".duration_num").val();
        var duration_time = el.find(".duration_time").val();

        var obj = {
          id : id,
          frequency_num : frequency_num,
          frequency_time : frequency_time,
          duration_num : duration_num,
          duration_time : duration_time,
          dosage : dosage,
          price : price
        };

        drugs_info[index] = obj;

        if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
          num++;
          var prescription_obj = calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
          // console.log(prescription_obj)
          if(prescription_obj != {}){
            var quantity = prescription_obj.quantity;
            var total_price = prescription_obj.total_price;

            total_quantity += quantity;
            sum += total_price;
          }
        }

      });

      form_data['drugs_info'] = drugs_info;

      if(registered_patient){
        user_info.patient_facility_id = patient_facility_id;
      }
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
      console.log(form_data);
      // console.log(JSON.stringify(form_data))

      if(num > 0){
        swal({
          title: 'Proceed?',
          text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Proceed?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_drugs_selected_pharmacy'); ?>";
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
                var text = "Prescription Made Successfully"
                if(response.nfp){
                  text += ". This Patient Is A None Fee Paying Patient. Proceed To Dispensing And Dispatchig Officer."
                }
                
                $.notify({
                message : text
                },{
                  type : "success"  
                });

                document.location.reload();
              }else{
                swal({
                 title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
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
      }else{
        swal({
          title: 'Warning! ',
          text: "You Must Enter Complete Prescription Info For At Least One Selected Drug To Proceed",
          type: 'error'
        })
      }
    }else{
      $("#enter-clinician-name-modal").modal("show");
    }
  }

  function dosageEvent(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function frequencyEvent1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function frequencyEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
  }

  function durationEvent1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function durationEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);   
  }

  function selectDrugsProceed(elem,evt) {
    console.log(selected_drugs);
    console.log(user_info);
    if(selected_drugs.length > 0){
      var selected_drugs_info_html = "<div class='container-fluid'>";
      var j = 0;
      <?php 
        $attr = array('id' => 'prescription-data-form');
      ?>
      selected_drugs_info_html += "<div id='prescription-data-div'>";
      for(var i = 0; i < selected_drugs.length; i++){
        var id = selected_drugs[i].id;
        var price = selected_drugs[i].price;
        var brand_name = selected_drugs[i].brand_name;
        var generic_name = selected_drugs[i].generic_name;
        var formulation = selected_drugs[i].formulation;
        var strength = selected_drugs[i].strength;
        var strength_unit = selected_drugs[i].strength_unit;
        var main_store_quantity = selected_drugs[i].main_store_quantity;
        var dispensary_quantity = selected_drugs[i].dispensary_quantity;
        var unit = selected_drugs[i].unit;

        j++;
        selected_drugs_info_html += j + ". ";
        
        
        selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";
        selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
        selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
        selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
        selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
        selected_drugs_info_html += "<input class='form-control dosage' type='number' onkeyup='dosageEvent(this,event,"+i+")'>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
        selected_drugs_info_html += "<input class='form-control frequency_num' type='number' onkeyup='frequencyEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "<option value='minutely' selected>Minutely</option>";
        selected_drugs_info_html += "<option value='hourly'>Hourly</option>";
        selected_drugs_info_html += "<option value='daily'>Daily</option>";
        selected_drugs_info_html += "<option value='weekly'>Weekly</option>";
        selected_drugs_info_html += "<option value='monthly'>Monthly</option>";
        selected_drugs_info_html += "<option value='yearly'>Yearly</option>";
        selected_drugs_info_html += "<option value='nocte'>Nocte</option>";
        selected_drugs_info_html += "<option value='stat'>Stat</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
        selected_drugs_info_html += "<input class='form-control duration_num' type='number' onkeyup='durationEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "option value='minutes' selected>Minutes</option>";
        selected_drugs_info_html += "<option value='hours'>Hours</option>";
        selected_drugs_info_html += "<option value='days'>Days</option>";
        selected_drugs_info_html += "<option value='weeks'>Weeks</option>";
        selected_drugs_info_html += "<option value='months'>Months</option>";
        selected_drugs_info_html += "<option value='years'>Years</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "</div>";
        
      }
      selected_drugs_info_html += "</div>";

      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
      $("#select-drugs-proceed-btn-2").show("fast");
      $("#selected-drugs-info-card .card-title").html("Enter prescription Details For: " + user_info.full_name);
      $("#selected-drugs-info-card .card-body").html(selected_drugs_info_html);
      $("#selected-drugs-info-card").show();
      selected_drugs_info_html += "</div>";
    }else{
      swal({
       title: 'Sorry',
        text: "You Must Select At Least One Drug To Proceed",
        type: 'error'
      })
    }
  }

  function checkBoxEvt (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    var price = elem.attr("data-price");
    var brand_name = elem.attr("data-brand-name");
    var generic_name = elem.attr("data-generic-name");
    var formulation = elem.attr("data-formulation");
    var strength = elem.attr("data-strength");
    var strength_unit = elem.attr("data-strength-unit");
    var main_store_quantity = elem.attr("data-main-store-quantity");
    var dispensary_quantity = elem.attr("data-dispensary-quantity");
    var is_poison = elem.attr("data-is-poison");
    var unit = elem.attr("data-unit");
    

    var data = {
      id : id,
      price : price,
      brand_name : brand_name,
      generic_name : generic_name,
      formulation : formulation,
      strength : strength,
      strength_unit : strength_unit,
      main_store_quantity : main_store_quantity,
      dispensary_quantity : dispensary_quantity,
      unit : unit,
      is_poison : is_poison
    };
    if(elem.prop('checked')){
      isChecked = true;
    }else{
      isChecked = false;
    }

    if(isChecked){
      selected_drugs.push(data);
    }else{
      var index = selected_drugs.map(function(obj, index) {
          if(obj.id === id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        selected_drugs.splice(index, 1);
      }
    }
  }

  function transcribePrescriptions(elem,evt){
    $("#enter-patient-data-modal").modal("show");
  }

  function performFunctions (elem,evt) {
    $("#main-card").hide();
    $("#perform-functions-card").show();
  }

  function goBackErrorRegister (elem,evt) {
    $("#perform-functions-card").show();
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
  }

  function goBackAddNewErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-card").hide();
    $("#add-new-error-btn").show("fast");

  }

  function addNewError (elem,evt) {
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
    $("#add-new-error-card").show();
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
          $("#perform-functions-card").hide();
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

  function goBackEditErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-btn").show("fast");
    
    $("#edit-new-error-card").hide();
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

  function goBackMainStoreCard () {
    $("#perform-functions-card").show("slow");
    $("#main-store-card").hide("slow");
  }

  function goBackViewDrugMainStoreCard () {
    $("#main-store-card").show("slow");
    
    $("#view-drug-main-store-card").hide("slow");
    
  }

  function viewDrugMainStore (elem,event,id) {
    if(id != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_drug_main_store'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#main-store-card").hide();
            $("#view-drug-main-store-card .card-body").html(messages);
            $("#view-drug-main-store-card").show();
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

  function viewStoreRecords (elem,evt) {
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_main_store'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#main-store-card .card-body").html(messages);
          $("#main-store-card #main-store-table").DataTable();
          $("#main-store-card").show();
          
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
          $("#perform-functions-card").hide();
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

  function goBackPoisonRegisterCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#poison-register-card").hide();
  }

  function collectPharmacyPayments (elem,evt) {
    $("#perform-functions-card").hide();
    $("#collect-payment-card").show();
  }

  function goCollectPayment(elem,evt){
    $("#perform-functions-card").show();
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
          $("#perform-functions-card").hide();
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
    $("#perform-functions-card").show();
   
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

  function goBackDispatchDrugs(elem,evt){
    $("#perform-functions-card").show();
    $("#dispense-drugs-card").hide();
  }

  function dispatchDrugs (elem,evt) {
    $("#perform-functions-card").hide();
    $("#dispense-drugs-card").show();
  }

  function otcPatientsDispatch(elem,evt){
    
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
          $("#perform-functions-card").hide();
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

  function goBackAwaitingDispense (elem,evt) {
    $("#perform-functions-card").show();
    $("#all-awaiting-dispense-card").hide();
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

  function goBackAwaitingDispenseInfo (argument) {
    $("#all-awaiting-dispense-card").show();
    $("#all-awaiting-dispense-card-info").hide();
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
              
            <div class="col-md-12">

              <div class="card" id="all-awaiting-dispense-card-info" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Drugs Selected By This Patient </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAwaitingDispenseInfo(this,event)">Go Back</button>
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

              <div class="card" id="dispense-drugs-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Choose Action: </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackDispatchDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Dispense / Dispatch For: </h4>
                  <button onclick="wardPatients(this,event)" class="btn btn-primary">Ward Patients</button>
                  <button onclick="clinicPatients(this,event)" class="btn btn-info">Clinic Patients</button>
                  <button onclick="otcPatientsDispatch(this,event)" class="btn btn-success">Normal Patients</button>
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

              <div class="card" id="edit-patient-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Change Patient Type</h3>
                  <button onclick="goBackFromEditPatientInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="registered-patients-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackRegisteredPatientsCard(this,event)">Go Back</button>
                  <h3 class="card-title"></h3>
                  
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="performFunctions(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="select-drugs-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
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

              <div class="card" id="selected-drugs-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Main Store </h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="view-drug-main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackViewDrugMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="error-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Error Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
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

              

              <div class="card" id="perform-functions-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Your Functions</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPerformFunctions(this,event)">Go Back</button>
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

                      <?php if($health_facility_structure == "pharmacy"){ ?>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptions(this,event)">Transcribe / Make Prescription</td>
                        </tr>
                        
                        <tr>
                          <td>2</td>
                          <td onclick="otcPatients(this,event)" style="text-transform: capitalize;">Collect Payments</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="otcPatientsDispatch(this,event)" style="text-transform: capitalize;">Dispense & Dispatch Drugs</td>
                        </tr>
                        
                        <tr>
                          <td>4</td>
                          <td onclick="viewStoreRecords(this,event)" style="text-transform: capitalize;">View Store Records</td>
                        </tr>
                        <!-- <tr>
                          <td>8</td>
                          <td onclick="viewAndCreateRecords(this,event)" style="text-transform: capitalize;">View And Create Records</td>
                        </tr> -->
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>9</td>
                          <td onclick="viewWardPatients(this,event)" style="text-transform: capitalize;">View Ward Patients</td>
                        </tr >-->
                        <?php } ?>
                      </tbody>

                    <?php }else if($health_facility_structure == "hospital"){ ?>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptionsHospital(this,event)">Transcribe / Make Prescription</td>
                        </tr>
                        
                        <tr>
                          <td>2</td>
                          <td onclick="otcPatients(this,event)" style="text-transform: capitalize;">Collect Payments</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="otcPatientsDispatch(this,event)" style="text-transform: capitalize;">Dispense & Dispatch Drugs</td>
                        </tr>
                        
                        <tr>
                          <td>4</td>
                          <td onclick="viewStoreRecords(this,event)" style="text-transform: capitalize;">View Store Records</td>
                        </tr>
                        <!-- <tr>
                          <td>8</td>
                          <td onclick="viewAndCreateRecords(this,event)" style="text-transform: capitalize;">View And Create Records</td>
                        </tr> -->
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>9</td>
                          <td onclick="viewWardPatients(this,event)" style="text-transform: capitalize;">View Ward Patients</td>
                        </tr >-->
                        <?php } ?>
                      </tbody>
                    <?php } ?>
                    </table>
                  </div>              
                </div> 
              </div>


            </div>
          </div>


          <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

            </div>
          </div>


          <div id="select-drugs-proceed-btn-2" onclick="selectDrugsProceed2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>
          
          <div id="select-drugs-proceed-btn" onclick="selectDrugsProceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
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
                      <td onclick="selectDrugsForPatient(this,event)" class="text-primary">Select Drugs For This Patient</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td onclick="editPatientInfo(this,event)" class="text-primary">Edit Patient Info</td>
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

      <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-patient-data-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="text-transform: capitalize;">Enter Patient Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <?php 
                $attr = array('id' => 'enter-patient-data-form');
                echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy',$attr);
              ?>
              <span class="form-error1">*</span> : Required
              <div class="wrap">
                <div class="form-row">                 
                  <div class="form-group col-md-12">
                    <label for="full_name" class="label-control"><span class="form-error1">*</span> FullName: </label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="">
                    <span class="form-error"></span>
                  </div>
                  

                  <div class="form-group col-md-12">
                    <label for="dob" class="label-control">  Date Of Birth: </label>
                    <input type="date" class="form-control" name="dob" id="dob">
                    <span class="form-error"></span>
                  </div>
                  
                  <div class="form-group col-md-12">
                    <label for="age" class="label-control">  Age: </label>
                    <input type="number" class="form-control" name="age" id="age">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <div id="age_unit">
                      <label for="age_unit" class="label-control">  Age Unit: </label>
                      <select name="age_unit" id="age_unit" class="form-control selectpicker" data-style="btn btn-link">
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

                  <div class="form-group col-md-12">
                    <p class="label"><span class="form-error1">*</span>  Gender: </p>
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

                  <div class="form-group col-md-12">
                    <label for="clinician" class="label-control">  Clinician: </label>
                    <input type="text" class="form-control" name="clinician" id="clinician">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="race" class="label-control">  Race/Tribe: </label>
                    <input type="text" class="form-control" id="race" name="race">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="mobile" class="label-control">  Mobile No: </label>
                    <input type="number" class="form-control" id="mobile" name="mobile">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="email" class="label-control">  Email: </label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="address" class="label-control">  Address: </label>
                    <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                    <span class="form-error"></span>
                  </div>

                </div>
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


      <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-clinician-name-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title text-primary" style="text-transform: capitalize;">One Or More Poisons Selected.</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p>You Need To Enter Clinician's Name To Proceed You Need To Enter Clinician's Name To Proceed</p>
              <?php 
                $attr = array('id' => 'enter-clinician-name-form');
                echo form_open('',$attr);
              ?>
              <div class="form-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="Clinician's Name">
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
      
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer> -->
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
    
    $("#enter-clinician-name-form").submit(function (evt) {
      evt.preventDefault();
      var name = $(this).find("#name").val();
      console.log(name)
      console.log(name.length)
      if(name !== ""){
        if(name.length >= 10 && name.length <= 100){
          user_info.clinician = name;
          $("#enter-clinician-name-modal").modal("hide");
          selectDrugsProceed2();
        }else{
          $.notify({
          message:"This Field Must Be Between 10 To 100 Characters"
          },{
            type : "warning"  
          });
        }
      }else{
        $.notify({
        message:"This Field Is Required"
        },{
          type : "warning"  
        });
      }
    })

    $("#enter-patient-data-form").submit(function (evt) {
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
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card .card-body").html(messages);
            $("#enter-patient-data-modal").modal("hide");
            $("#perform-functions-card").hide();
            $("#select-drugs-card #select-drugs-table").DataTable();
            $("#select-drugs-card").show();
            $("#select-drugs-proceed-btn").show("fast");
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#enter-patient-data-form #'+key);
            
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
    });

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
