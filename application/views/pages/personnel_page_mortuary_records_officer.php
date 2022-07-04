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
</style>
<script>

  function goDefault() {
    
    document.location.reload();
  }
  function registerBody (elem,evt) {
    evt.preventDefault();
    $("#main-card").hide();
    $("#register-body-card").show();
  }

  function goBackRegisterBodyCard (elem,evt) {
    $("#main-card").show();
    $("#register-body-card").hide();
  }

  function registerInternalBody (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_internal') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#internal-bodies-card .card-body").html(messages);
          $("#mortuary-internal-records").DataTable();
          $("#register-body-card").hide();
          $("#internal-bodies-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    }); 
  }

  function goBackInternalBodiesCard (elem,evt) {
    $("#register-body-card").show();
    $("#internal-bodies-card").hide();
  }

  function loadInternalMortuaryRecords(elem,evt,id){
    console.log(id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_bodies_internal_by_id') ?>";
    var form_data = {
      "show_records" : true,
      'id' : id
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#internal-mortuary-record-info-card .card-body").html(messages);
          $("#register-body-internal-btn").attr("data-id",id);
          $("#register-body-internal-btn").show("fast");
          $("#internal-bodies-card").hide();
          $("#internal-mortuary-record-info-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    }); 
  }

  function goBackInternalMortuaryRecordInfoCard (elem,evt) {
    $("#internal-bodies-card").show();
    $("#internal-mortuary-record-info-card").hide();
    $("#register-body-internal-btn").hide("fast");
  }

  

  function registerBodyInternal (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    $("#body-received-date-form").attr("data-id",id);
    $("#body-received-date-modal").modal("show");

  }

  function goBackLinkedExternalBodiesCard (elem,evt) {
    $("#register-body-card").show();
    $("#linked-external-bodies-card").hide();
  }

  function registerExternalBodies(elem,evt){
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To Register: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Linked Facilities',
      cancelButtonText: 'Unlinked Facilities'
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_external_linked') ?>";
      var form_data = {
        "show_records" : true
      }
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : form_data,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#linked-external-bodies-card .card-body").html(messages);
            $("#linked-external-bodies-table").DataTable();
            $("#register-body-card").hide();
            $("#linked-external-bodies-card").show();
          }else{
            $.notify({
            message:"No Record To Display"
            },{
              type : "warning"  
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
      }); 
    }, function(dismiss){
      if(dismiss == 'cancel'){
        $("#register-body-card").hide();
        $("#enter-external-unlinked-mortuary-records-card #time_of_death").datetimepicker({
          showClose : true,
          showClear : true,
          widgetPositioning : {
            vertical: 'bottom'
          },icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          }
          
        })
        $("#enter-external-unlinked-mortuary-records-card #time_of_death").val("");
        $("#enter-external-unlinked-mortuary-records-card").show();
      }
    });
      
  }

  function goBackEnterExternalUnLinkedMortuaryRecordCard (elem,evt) {
    $("#register-body-card").show();
    $("#enter-external-unlinked-mortuary-records-card").hide();
  }

  function loadExternalMortuaryRecords(elem,evt,id){
    console.log(id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_bodies_external_linked_by_id') ?>";
    var form_data = {
      "show_records" : true,
      'id' : id
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#external-linked-mortuary-record-info-card .card-body").html(messages);
          $("#register-body-external-linked-btn").attr("data-id",id);
          $("#register-body-external-linked-btn").show("fast");
          $("#linked-external-bodies-card").hide();
          $("#external-linked-mortuary-record-info-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    }); 
  }

  function goBackExternalLinkedMortuaryRecordInfoCard (elem,evt) {
    $("#register-body-external-linked-btn").hide("fast");
    $("#linked-external-bodies-card").show();
    $("#external-linked-mortuary-record-info-card").hide();
  }

  function registerBodyExternalLinked (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    $("#body-received-date-form-linked-external").attr("data-id",id);
    $("#body-received-date-modal-linked-external").modal("show");

  }

  function editExternalRegistrations (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_external_unlinked') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#external-unlinked-bodies-card .card-body").html(messages);
          $("#mortuary-external-records-unlinked-table").DataTable();
          $("#main-card").hide();
          $("#external-unlinked-bodies-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    });
  }

  function goBackExternalUnlinkedBodiesCard (elem,evt) {
    $("#main-card").show();
    $("#external-unlinked-bodies-card").hide();
  }

  function loadExternalUnlinkedMortuaryRecordEdit (elem,evt,id) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_external_unlinked_body_data_edit'); ?>";
    
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
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-external-unlinked-mortuary-records-form #" +key).val(value);
            if(key == "sex"){
              $("#edit-external-unlinked-mortuary-records-form #" +value).prop("checked",true);
            }
            if(key == "time_of_death"){
              $("#edit-external-unlinked-mortuary-records-form #time_of_death").datetimepicker({
                  defaultDate : value,
                  showClose : true,
                  showClear : true,
                  widgetPositioning : {
                    vertical: 'bottom'
                  },icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-screenshot',
                    clear: 'fa fa-trash',
                    close: 'fa fa-remove'
                }
              }); 
            } 
            if(key == "doctors_name"){
              $("#edit-external-unlinked-mortuary-records-form #referring_dr").val(value);
            }
          }
          $("#edit-external-unlinked-mortuary-records-form .form-error").html("");
          $("#edit-external-unlinked-mortuary-records-form").attr("data-id",id);
          $("#external-unlinked-bodies-card").hide();
          $("#edit-external-unlinked-mortuary-records-card").show();
          
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

  function goBackEditExternalUnLinkedMortuaryRecordCard (elem,evt) {
    $("#edit-external-unlinked-mortuary-records-card").hide();
    editExternalRegistrations(this,event);
  }

  function viewAndEditRegistrations (elem,evt) {
    
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_all_mortuary_bodies_registrations') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#all-registered-bodies-card .card-body").html(messages);
          $("#all-registered-bodies-table").DataTable();
          $("#main-card").hide();
          $("#all-registered-bodies-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    });
  }

  function goBackAllRegisteredBodiesCard (elem,evt) {
    $("#main-card").show();
    $("#all-registered-bodies-card").hide();
  }

  function loadExternalUnlinkedMortuaryRecordEdit1 (elem,evt,id) {
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_external_unlinked_body_data_edit'); ?>";
    
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
          for (const [key, value] of Object.entries(messages)) {
            $("#edit-external-unlinked-mortuary-records-form-1 #" +key).val(value);
            if(key == "sex"){
              $("#edit-external-unlinked-mortuary-records-form-1 #" +value).prop("checked",true);
            }
            if(key == "time_of_death"){
              $("#edit-external-unlinked-mortuary-records-form-1 #time_of_death").datetimepicker({
                  defaultDate : value,
                  showClose : true,
                  showClear : true,
                  widgetPositioning : {
                    vertical: 'bottom'
                  },icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-screenshot',
                    clear: 'fa fa-trash',
                    close: 'fa fa-remove'
                }
              }); 
            } 
            if(key == "doctors_name"){
              $("#edit-external-unlinked-mortuary-records-form-1 #referring_dr").val(value);
            }
          }
          $("#edit-external-unlinked-mortuary-records-form-1 .form-error").html("");
          $("#edit-external-unlinked-mortuary-records-form-1").attr("data-id",id);
          $("#all-registered-bodies-card").hide();
          $("#edit-external-unlinked-mortuary-records-card-1").show();
          
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

  function goBackEditExternalUnLinkedMortuaryRecordCard1 (elem,evt) {
    $("#edit-external-unlinked-mortuary-records-card-1").hide();
    viewAndEditRegistrations(this,event)
  }

  function loadMortuaryRecords1(elem,evt,id){
    console.log(id);
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_mortuary_body_by_id') ?>";
    var form_data = {
      "show_records" : true,
      'id' : id
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#mortuary-record-info-card .card-body").html(messages);
          $("#all-registered-bodies-card").hide();
          $("#mortuary-record-info-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    }); 
  }

  function goBackMortuaryRecordInfoCard (elem,evt) {
    $("#all-registered-bodies-card").show();
    $("#mortuary-record-info-card").hide();
  }

  function manageServices (elem,evt) {
    evt.preventDefault()
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_mortuary_services'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "view_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          $(".spinner-overlay").hide();
          var messages = response.messages;
          $("#main-card").hide();
          $("#mortuary-services-card .card-body").html(messages);
          $("#mortuary-services-card").show();
          $("#add-mortuary-services-btn").show("fast");
          $("#mortuary-services-table").DataTable();
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });
  }

  function goBackMortuaryServices (elem,evt) {
    $("#main-card").show();
    $("#mortuary-services-card").hide();
    $("#add-mortuary-services-btn").hide("fast");
  }

  function addNewMortuaryService (elem,evt) {
    $("#add-mortuary-services-card").show();
    $("#mortuary-services-card").hide();
    $("#add-mortuary-services-btn").hide("fast"); 
  }

  function goBackAddNewMortuaryService (elem,evt) {
    $("#add-mortuary-services-card").hide();
    $("#mortuary-services-card").show();
    $("#add-mortuary-services-btn").show("fast"); 
  }

  function serviceTypeFired(elem,evt){
    elem = $(elem);
    var id = elem.attr("id");
    if(id == "fixed" && elem.val() == 1){
      $("#add-mortuary-services-form #mortuary-price-div").show();
      $("#add-mortuary-services-form #mortuary-ppq-div").hide();
      $("#add-mortuary-services-form #mortuary-quantity-div").hide();
    }else if(id == "rate" && elem.val() == 0){
      $("#add-mortuary-services-form #mortuary-price-div").hide();
      $("#add-mortuary-services-form #mortuary-ppq-div").show();
      $("#add-mortuary-services-form #mortuary-quantity-div").show();
    }
  }

  function editMortuaryService(elem,evt,id){
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_mortuary_service_info'); ?>";
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "view_records=true&id="+id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          
          var messages = response.messages;
          var name = messages.name;
          var price = messages.price;
          var quantity = messages.quantity;
          var type = messages.type;
          var ppq = messages.ppq;

          $("#edit-mortuary-services-form #name").val(name);
          if(type == "fixed"){
            $("#edit-mortuary-services-form #fixed").prop('checked', true);
            $("#edit-mortuary-services-form #price").val(price);
            $("#edit-mortuary-services-form #mortuary-price-div").show();
            $("#edit-mortuary-services-form #mortuary-ppq-div").hide();
            $("#edit-mortuary-services-form #mortuary-quantity-div").hide();
          }else if(type == "rate"){
            $("#edit-mortuary-services-form #rate").prop("checked",true);
            $("#edit-mortuary-services-form #ppq").val(ppq)
            $("#edit-mortuary-services-form #quantity").val(quantity);
            $("#edit-mortuary-services-form #mortuary-price-div").hide();
            $("#edit-mortuary-services-form #mortuary-ppq-div").show();
            $("#edit-mortuary-services-form #mortuary-quantity-div").show();
          }
          $("#edit-mortuary-services-form").attr('data-id', id);
          $("#edit-mortuary-services-card").show();
          $("#mortuary-services-card").hide();
          $("#add-mortuary-services-btn").hide("fast");
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });
  }

  function goBackEditMortuaryService (elem,evt) {
    $("#edit-mortuary-services-card").hide();
    manageServices(this,event);
  }

  function serviceTypeFired1(elem,evt){
    elem = $(elem);
    var id = elem.attr("id");
    if(id == "fixed" && elem.val() == 1){
      $("#edit-mortuary-services-form #mortuary-price-div").show();
      $("#edit-mortuary-services-form #mortuary-ppq-div").hide();
      $("#edit-mortuary-services-form #mortuary-quantity-div").hide();
    }else if(id == "rate" && elem.val() == 0){
      $("#edit-mortuary-services-form #mortuary-price-div").hide();
      $("#edit-mortuary-services-form #mortuary-ppq-div").show();
      $("#edit-mortuary-services-form #mortuary-quantity-div").show();
    }
  }

  function requestForServices (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_current_mortuary_bodies_registrations_for_requesting_service') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#bodies-for-services-card .card-body").html(messages);
          $("#bodies-for-services-table").DataTable();
          $("#main-card").hide();
          $("#bodies-for-services-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    }); 
  }

  function requestServiceForBody (elem,evt,mortuary_record_id) {
    swal({
      title: 'Choose Action',
      text: "Do You Want To? ",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Request Services',
      cancelButtonText : "View Previous Requests"
    }).then(function(){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_service_for_body_mortuary'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+mortuary_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#bodies-for-services-card").hide();
            $("#request-services-card .card-body").html(messages);
            $("#request-services-table").DataTable();
            $("#request-services-card").show();
            
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    },function(dismiss){
      if(dismiss == 'cancel'){
        $(".spinner-overlay").show();
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previously_requested_services_for_patient'); ?>";
        
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
              $("#bodies-for-services-card").hide();
              $("#requested-services-card .card-body").html(messages);
              $("#requested-services-card").show();
              $("#requested-services-table").DataTable();
            }else{
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
              title: 'Ooops',
              text: "Sorry Something Went Wrong",
              type: 'error'
            })
          } 
        });    
      }
    });
  }

  function goBackRequestedServices (elem,evt) {
    $("#bodies-for-services-card").show();
    $("#requested-services-card").hide();
  }

  function goBackRequestServicesCard (elem,evt) {
    $("#request-services-card").hide();
    requestForServices(this,event);
  }

  function requestMortuaryService(elem,evt,mortuary_record_id,id,name,type,price){
    swal({
      title: 'Choose Action',
      text: "Do You Want To? ",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Pay Now',
      cancelButtonText : "Pay Later"
    }).then(function(){
      $("#request-services-card").hide();
      $("#request-service-card .card-title").html("Request "+name+" Service");
      $("#request-service-form #amount-form-group").show();
      if(type == "rate"){
        $("#request-service-form").attr("data-type","pay_now_rate");
        $("#request-service-form #quantity-form-group").show();
        $("#request-service-form #quantity").attr("onkeyup","amountKeyUp(this,event,"+price+")");
        
      }else{
        $("#request-service-form #amount").val(price);
        $("#request-service-form").attr("data-type","pay_now_fixed");
      }
      $("#request-service-form").attr("data-id",id);
      $("#request-service-form").attr("data-mortuary-record-id",mortuary_record_id);
      $("#request-service-card").show();
    },function(dismiss){
      if(dismiss == 'cancel'){
        
        if(type == "rate"){
          $("#request-services-card").hide();
          $("#request-service-form #quantity-form-group").show();
          $("#request-service-form").attr("data-id",id);
          $("#request-service-form #price-info").html("Price Per Quantity "+price);
          $("#request-service-card").show();
          $("#request-service-card .card-title").html("Request "+name+" Service");
          $("#request-service-form").attr("data-mortuary-record-id",mortuary_record_id);
          $("#request-service-form").attr("data-type","pay_later_rate");
        }else{
          var form_data = {
            "mortuary_record_id" : mortuary_record_id,
            "service_id" : id,
            "type" : "pay_later_fixed"
          }
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_new_mortuary_service') ?>";
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
              if(response.success && !response.pay_now){
                document.location.reload();
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
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
        }
        
      }
    });  
  }

  function goBodyForServiceCard  (elem,evt) {
    $("#main-card").show();
    $("#bodies-for-services-card").hide();
  }

  function amountKeyUp(elem,evt,price){
    elem = $(elem);
    var val = elem.val();
    if(val !== "" && price !== ""){
      var final_price = val * price;
      $("#request-service-form #amount").val(final_price);
    }
    
  }

  function goBackRequestService (elem,evt) {
    $("#request-services-card").show();
    $("#request-service-card").hide();
  }

  function openDeathCertificate (elem,evt) {

    var certificate = $(elem).attr("data-certificate");
    window.location.assign(certificate);
  }

  function printDeathCertificates (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_death_certificates_for_printing') ?>";
    var form_data = {
      "show_records" : true
    }
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response)
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#death-certificate-card .card-body").html(messages);
          $("#bodies-for-services-table").DataTable();
          $("#main-card").hide();
          $("#death-certificate-card").show();
        }else{
          $.notify({
          message:"No Record To Display"
          },{
            type : "warning"  
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
    });
  }

  function goBackDeathCertificateCard (elem,evt) {
    $("#main-card").show();
    $("#death-certificate-card").hide();
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

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $user_name; ?></h3>
                </div>
                <div class="card-body">

                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <table class="table">
                    <tbody>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="registerBody(this,event)">Register Body</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="manageServices(this,event)">Manage Services</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>3</td>
                        <td><a href="#" onclick="requestForServices(this,event)">Request For Services</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>4</td>
                        <td><a href="#" onclick="editExternalRegistrations(this,event)">Edit Previous External Unlinked Registrations</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>5</td>
                        <td><a href="#" onclick="viewAndEditRegistrations(this,event)">View And Edit Previous Registrations</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>6</td>
                        <td><a href="#" onclick="printDeathCertificates(this,event)">Print Death Certificates</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="request-service-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestService(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Request This Service</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'request-service-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_new_mortuary_service',$attr);
                  ?>
                    
                    <div class="wrap">
                      <h4 id="price-info"></h4>
                      <div class="form-row">
                        <div class="form-group col-sm-12" style="display: none;" id="quantity-form-group">
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Enter Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" onkeyup="" type="number" />
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12" style="display: none;" id="amount-form-group">
                          <label for="amount" class="label-control"><span class="form-error1">* </span> Enter Amount Paid: </label>
                          <input name="amount" id="amount" step="any" class="form-control" type="number" />
                          <span class="form-error"></span>
                        </div> 
                      </div>
                      <input type="submit" class="btn btn-primary">
                    </div>
                  </form>

                </div>
              </div>

              <div class="card" id="requested-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestedServices(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Requests Of Services</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="edit-mortuary-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Edit This Mortuary Service</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditMortuaryService(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'edit-mortuary-services-form','class' => '');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/edit_mortuary_service',$attr);
                  ?>
                    
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="name" class="label-control"><span class="form-error1">* </span>Service Name: </label>
                          <input type="text" class="form-control" id="name" name="name" required>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                          <p class="label">Service Type: </p>
                          
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="type" id="fixed" value="1" onclick="return serviceTypeFired1(this,event)" checked> Fixed                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="type" id="rate" value="0" onclick="return serviceTypeFired1(this,event)"> Rate                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>
                          
                          <span class="form-error"></span> 
                        </div>
  
                        <div class="form-group col-sm-12" id="mortuary-price-div">
                          <label for="price" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="price" name="price" step="any">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-12" style="display: none;" id="mortuary-ppq-div">
                          <label for="ppq" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="ppq" name="ppq" step="any">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12" style="display: none;" id="mortuary-quantity-div">
                          <label for="quantity" class="label-control"><span class="form-error1">* </span>Quantity: </label>
                          <input type="number" class="form-control" id="quantity" name="quantity">
                          <span class="form-error"></span>
                        </div> 
                      </div>
                      <input type="submit" class="btn btn-primary">
                    </div>
                    
                  </form> 


                </div> 
              </div>

              <div class="card" id="add-mortuary-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Add New Mortuary Service</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewMortuaryService(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'add-mortuary-services-form','class' => '');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_new_mortuary_service',$attr);
                  ?>
                    
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="name" class="label-control"><span class="form-error1">* </span>Service Name: </label>
                          <input type="text" class="form-control" id="name" name="name" required>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                          <p class="label">Service Type: </p>
                          
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="type" id="fixed" value="1" onclick="return serviceTypeFired(this,event)" checked> Fixed                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="type" id="rate" value="0" onclick="return serviceTypeFired(this,event)"> Rate                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>
                          
                          <span class="form-error"></span> 
                        </div>
  
                        <div class="form-group col-sm-12" id="mortuary-price-div">
                          <label for="price" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="price" name="price" step="any">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-12" style="display: none;" id="mortuary-ppq-div">
                          <label for="ppq" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="ppq" name="ppq" step="any">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12" style="display: none;" id="mortuary-quantity-div">
                          <label for="quantity" class="label-control"><span class="form-error1">* </span>Quantity: </label>
                          <input type="number" class="form-control" id="quantity" name="quantity">
                          <span class="form-error"></span>
                        </div> 
                      </div>
                      <input type="submit" class="btn btn-primary">
                    </div>
                    
                  </form> 


                </div> 
              </div>

              <div class="card" id="request-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Select Service To Request</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackRequestServicesCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                </div> 
              </div>

              <div class="card" id="mortuary-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Mortuary Services</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMortuaryServices(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                </div> 
              </div>

              <div class="card" id="register-body-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackRegisterBodyCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Register Body</h3>
                </div>
                <div class="card-body">

                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <table class="table">
                    <tbody>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="registerInternalBody(this,event)">Register Internal Body</a></td>
                      </tr>
                      <tr class="pointer-cursor">
                        <td>2</td>
                        <td><a href="#" onclick="registerExternalBodies(this,event)">Register External Bodies</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="internal-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackInternalBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Internal Bodies Awaiting Registration</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="external-unlinked-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackExternalUnlinkedBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">External Unlinked Bodies </h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="all-registered-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackAllRegisteredBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Registered Bodies </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="bodies-for-services-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBodyForServiceCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Select Body To Request Service </h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="death-certificate-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackDeathCertificateCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Bodies With Ready Death Certificates</h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="linked-external-bodies-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackLinkedExternalBodiesCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Linked External Bodies Awaiting Registration</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="internal-mortuary-record-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackInternalMortuaryRecordInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Body's BioData</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="mortuary-record-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackMortuaryRecordInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Body's BioData</h3>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="external-linked-mortuary-record-info-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackExternalLinkedMortuaryRecordInfoCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Body's BioData</h3>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="enter-external-unlinked-mortuary-records-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackEnterExternalUnLinkedMortuaryRecordCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Enter New Body's Data</h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'enter-external-unlinked-mortuary-records-form') ?>
                  <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_body_received_bio_data_unlinked_external',$attr); ?>
                    <span class="form-error1 text-right">* </span>: required
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="time_of_death" class="label-control">Time Of Death: </label>
                          <input type="text" class="form-control" name="time_of_death" id="time_of_death">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="date_received" class="label-control"><span class="form-error1">*</span> Date Body Was Received: </label>
                          <input type="date" class="form-control" name="date_received" id="date_received">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="referring_dr" class="label-control">Referring Dr. : </label>
                          <input type="text" class="form-control" name="referring_dr" id="referring_dr">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row"> 
                        <div class="form-group col-sm-4">
                            <label for="firstname" class="label-control"> FirstName: </label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group col-sm-4">
                            <label for="lastname" class="label-control"> LastName: </label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <label for="dob" class="label-control">Date Of Birth: </label>
                            <input type="date" class="form-control" name="dob" id="dob">
                            <span class="form-error"></span>
                          </div>
                          
                          <div class="form-group col-sm-3">
                            <label for="age" class="label-control"> Age: </label>
                            <input type="number" class="form-control" name="age" id="age">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <div id="age_unit">
                              <label for="age_unit" class="label-control"> Age Unit: </label>
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
                            <label for="race" class="label-control"> Race/Tribe: </label>
                            <input type="text" class="form-control" id="race" name="race">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="nationality" class="label-control"> Nationality: </label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                            <span class="form-error"></span>
                          </div>

                          

                          <div class="form-group col-sm-6">
                            <label for="religion" class="label-control"> Religion: </label>
                            <input type="text" class="form-control" id="religion" name="religion">
                            <span class="form-error"></span>
                          </div>

                        </div>
                      </div>

                      <div class="wrap">
                        <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                        <div class="form-row">
                          <div class="form-group col-sm-6">
                            <label for="name_of_next_of_kin" class="label-control"> Name Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="address_of_next_of_kin" class="label-control"> Address Of Next Of Kin: </label>
                            <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="mobile_no_of_next_of_kin" class="label-control"> Mobile No. Of  Next Of Kin: </label>
                            <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="relationship_of_next_of_kin" class="label-control"> Relationship With Next Of Kin: </label>
                            <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>
                        </div>
                      </div>
                    <input type="submit" class="btn btn-primary">
                  </form>
                </div>
              </div>

              <div class="card" id="edit-external-unlinked-mortuary-records-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackEditExternalUnLinkedMortuaryRecordCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Edit Body's Data</h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'edit-external-unlinked-mortuary-records-form') ?>
                  <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_body_received_bio_data_unlinked_external_edit',$attr); ?>
                    <span class="form-error1 text-right">* </span>: required
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="time_of_death" class="label-control">Time Of Death: </label>
                          <input type="text" class="form-control" name="time_of_death" id="time_of_death">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="date_received" class="label-control"><span class="form-error1">*</span> Date Body Was Received: </label>
                          <input type="date" class="form-control" name="date_received" id="date_received">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="referring_dr" class="label-control">Referring Dr. : </label>
                          <input type="text" class="form-control" name="referring_dr" id="referring_dr">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row"> 
                        <div class="form-group col-sm-4">
                            <label for="firstname" class="label-control"> FirstName: </label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group col-sm-4">
                            <label for="lastname" class="label-control"> LastName: </label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <label for="dob" class="label-control">Date Of Birth: </label>
                            <input type="date" class="form-control" name="dob" id="dob">
                            <span class="form-error"></span>
                          </div>
                          
                          <div class="form-group col-sm-3">
                            <label for="age" class="label-control"> Age: </label>
                            <input type="number" class="form-control" name="age" id="age">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <div id="age_unit">
                              <label for="age_unit" class="label-control"> Age Unit: </label>
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
                            <label for="race" class="label-control"> Race/Tribe: </label>
                            <input type="text" class="form-control" id="race" name="race">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="nationality" class="label-control"> Nationality: </label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                            <span class="form-error"></span>
                          </div>

                          

                          <div class="form-group col-sm-6">
                            <label for="religion" class="label-control"> Religion: </label>
                            <input type="text" class="form-control" id="religion" name="religion">
                            <span class="form-error"></span>
                          </div>

                        </div>
                      </div>

                      <div class="wrap">
                        <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                        <div class="form-row">
                          <div class="form-group col-sm-6">
                            <label for="name_of_next_of_kin" class="label-control"> Name Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="address_of_next_of_kin" class="label-control"> Address Of Next Of Kin: </label>
                            <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="mobile_no_of_next_of_kin" class="label-control"> Mobile No. Of  Next Of Kin: </label>
                            <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="relationship_of_next_of_kin" class="label-control"> Relationship With Next Of Kin: </label>
                            <input type="text" class="form-control" id="relationship_of_next_of_kin" name="relationship_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>
                        </div>
                      </div>
                    <input type="submit" class="btn btn-primary">
                  </form>
                </div>
              </div>

              <div class="card" id="edit-external-unlinked-mortuary-records-card-1" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-round btn-warning" onclick="goBackEditExternalUnLinkedMortuaryRecordCard1(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Edit Body's Data</h3>
                </div>
                <div class="card-body">
                  <?php $attr = array('id' => 'edit-external-unlinked-mortuary-records-form-1') ?>
                  <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_body_received_bio_data_unlinked_external_edit',$attr); ?>
                    <span class="form-error1 text-right">* </span>: required
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="time_of_death" class="label-control">Time Of Death: </label>
                          <input type="text" class="form-control" name="time_of_death" id="time_of_death">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="date_received" class="label-control"><span class="form-error1">*</span> Date Body Was Received: </label>
                          <input type="date" class="form-control" name="date_received" id="date_received">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="referring_dr" class="label-control">Referring Dr. : </label>
                          <input type="text" class="form-control" name="referring_dr" id="referring_dr">
                          <span class="form-error"></span>
                        </div>
                        <div class="form-group col-sm-6">
                          <label for="hospital_name" class="label-control">Hospital Name : </label>
                          <input type="text" class="form-control" name="hospital_name" id="hospital_name">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>
                    <h4 class="form-sub-heading">Personal Information</h4>
                    <div class="wrap">
                      <div class="form-row"> 
                        <div class="form-group col-sm-4">
                            <label for="firstname" class="label-control"> FirstName: </label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group col-sm-4">
                            <label for="lastname" class="label-control"> LastName: </label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <label for="dob" class="label-control">Date Of Birth: </label>
                            <input type="date" class="form-control" name="dob" id="dob">
                            <span class="form-error"></span>
                          </div>
                          
                          <div class="form-group col-sm-3">
                            <label for="age" class="label-control"> Age: </label>
                            <input type="number" class="form-control" name="age" id="age">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-3">
                            <div id="age_unit">
                              <label for="age_unit" class="label-control"> Age Unit: </label>
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
                            <label for="race" class="label-control"> Race/Tribe: </label>
                            <input type="text" class="form-control" id="race" name="race">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="nationality" class="label-control"> Nationality: </label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                            <span class="form-error"></span>
                          </div>

                          

                          <div class="form-group col-sm-6">
                            <label for="religion" class="label-control"> Religion: </label>
                            <input type="text" class="form-control" id="religion" name="religion">
                            <span class="form-error"></span>
                          </div>

                        </div>
                      </div>

                      <div class="wrap">
                        <h4 class="form-sub-heading">Next Of Kin Bio Data</h4>
                        <div class="form-row">
                          <div class="form-group col-sm-6">
                            <label for="name_of_next_of_kin" class="label-control"> Name Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="name_of_next_of_kin" name="name_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="address_of_next_of_kin" class="label-control"> Address Of Next Of Kin: </label>
                            <textarea name="address_of_next_of_kin" id="address_of_next_of_kin" cols="10" rows="10" class="form-control"></textarea>
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="mobile_no_of_next_of_kin" class="label-control"> Mobile No. Of  Next Of Kin: </label>
                            <input type="number" class="form-control" id="mobile_no_of_next_of_kin" name="mobile_no_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="username_of_next_of_kin" class="label-control"> Username Of Next Of Kin: </label>
                            <input type="text" class="form-control" id="username_of_next_of_kin" name="username_of_next_of_kin">
                            <span class="form-error"></span>
                          </div>

                          <div class="form-group col-sm-6">
                            <label for="relationship_of_next_of_kin" class="label-control"> Relationship With Next Of Kin: </label>
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

          <div id="register-body-internal-btn" onclick="registerBodyInternal(this,event)" rel="tooltip" data-toggle="tooltip" title="Register This Body" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fas fa-pen" style="font-size: 25px;  color: #fff;"></i>
            </div>
          </div>


          <div id="register-body-external-linked-btn" onclick="registerBodyExternalLinked(this,event)" rel="tooltip" data-toggle="tooltip" title="Register This Body" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fas fa-pen" style="font-size: 25px;  color: #fff;"></i>
            </div>
          </div>


          <div id="add-mortuary-services-btn" onclick="addNewMortuaryService(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Mortuary Service" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

            </div>
          </div>

          <div class="modal fade" data-backdrop="static" id="body-received-date-modal-linked-external" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Date Body Was Received</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body" id="modal-body">
                  <?php
                    $attr = array('id' => 'body-received-date-form-linked-external');
                   echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_body_received_date',$attr);
                  ?>
                    <div class="form-group">
                      <label for="date">Enter Date</label>
                      <input type="date" name="date" id="date" class="form-control" required>
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

          <div class="modal fade" data-backdrop="static" id="body-received-date-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Date Body Was Received</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body" id="modal-body">
                  <?php
                    $attr = array('id' => 'body-received-date-form');
                   echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_body_received_date',$attr);
                  ?>
                    <div class="form-group">
                      <label for="date">Enter Date</label>
                      <input type="date" name="date" id="date" class="form-control" required>
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

      $("#request-service-form").submit(function (evt) {
        
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
        
        var service_id = me.attr("data-id");
        var mortuary_record_id = me.attr("data-mortuary-record-id");
        var type = me.attr("data-type");

        form_data = form_data.concat({
          "name" : "mortuary_record_id",
          "value" : mortuary_record_id
        })

        form_data = form_data.concat({
          "name" : "service_id",
          "value" : service_id
        })

        form_data = form_data.concat({
          "name" : "type",
          "value" : type
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
            if(response.success && response.pay_now){
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
                "mortuary_number" : mortuary_number,
                "facility_address" : facility_address,
                "date" : date,
                'mod' : 'teller',
                "receipt_file" : receipt_file,
                "clinic" : true,
                "mortuary" : true,
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
            }else if(response.amount_too_big && response.price != ""){
              $.notify({
              message:"The Amount Entered Is Too Large. Price Is "+response.price
              },{
                type : "warning"  
              });
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else if(!response.pay_now && response.success){
              document.location.reload();
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#request-service-form #'+key);
              
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

      $("#edit-mortuary-services-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = me.attr("action");
        var id = me.attr("data-id");
        var form_data = me.serializeArray();
        
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })
        console.log(form_data)
        $(".spinner-overlay").show();
        if(id != ""){
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
                message:"Service Edited Successfully"
                },{
                  type : "success"  
                });
                $("#edit-mortuary-services-form .form-error").html("");
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#edit-mortuary-services-form #'+key);
                
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
      });

      $("#add-mortuary-services-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = me.attr("action");
        var form_data = me.serializeArray();
        // console.log(form_data)
        
        $(".spinner-overlay").show();
        console.log(form_data)
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
              message:"Service Added Successfully"
              },{
                type : "success"  
              });
              $("#add-mortuary-services-card").hide();
              $("#add-mortuary-services-btn").show("fast");
              manageServices(this,event)
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#add-mortuary-services-form #'+key);
              
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

      $("#edit-external-unlinked-mortuary-records-form-1").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var form_data = me.serializeArray();
        var id = me.attr("data-id");
        var url = me.attr("action");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })
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
            if(response.success == true){
               me.find(".form-error").html("");
              $.notify({
              message:"Body Data Has Been Edited Successfully"
              },{
                type : "success"  
              });
              
            }else{
             $.each(response.messages, function (key,value) {

              var element = $('#edit-external-unlinked-mortuary-records-form-1 #'+key);
              
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

      $("#edit-external-unlinked-mortuary-records-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var form_data = me.serializeArray();
        var id = me.attr("data-id");
        var url = me.attr("action");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })
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
            if(response.success == true){
              $.notify({
              message:"Body Data Has Been Edited Successfully"
              },{
                type : "success"  
              });
              me.find(".form-error").html("");
              
            }else{
             $.each(response.messages, function (key,value) {

              var element = $('#edit-external-unlinked-mortuary-records-form #'+key);
              
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

      $("#enter-external-unlinked-mortuary-records-form").submit(function (evt) {
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
            if(response.success == true){
              $.notify({
              message:"Body Has Been Successfully Registered In Mortuary"
              },{
                type : "success"  
              });
              setTimeout(goDefault, 1000);
              
            }else{
             $.each(response.messages, function (key,value) {

              var element = $('#enter-external-unlinked-mortuary-records-form #'+key);
              
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


      $("#body-received-date-form-linked-external").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var date = me.find("#date").val();
        var id= me.attr("data-id");
        if(date != ""){

          var form_data = {
            "date" : date,
            "id" : id
          };
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.success == true){
                $.notify({
                message:"Successful"
                },{
                  type : "success"  
                });
                setTimeout(goDefault, 1000);
              }else{
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
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
          }); 
        }else{

        }
      })

      $("#body-received-date-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var date = me.find("#date").val();
        var id= me.attr("data-id");
        if(date != ""){

          var form_data = {
            "date" : date,
            "id" : id
          };
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              $(".spinner-overlay").hide();
              console.log(response)
              if(response.success == true){
                $.notify({
                message:"Successful"
                },{
                  type : "success"  
                });
                setTimeout(goDefault, 1000);
              }else{
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
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
          }); 
        }else{

        }
      })

      
      
      <?php if($this->session->service_requested){ ?>
         $.notify({
          message:"Service Request Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->service_pay_later_successful){ ?>
         $.notify({
          message:"Service Request Made Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>
    });

  </script>
