<style>
  tr{
    cursor: pointer;
  }
</style>
<script>
      function goDefault() {
        $("#edit-tests-card").hide("slow");
        $("#sections-card").hide("slow");
        $("#settings-card").hide("slow");
        $("#main-card").show("slow");  
      }

      function goBackSelectDrugsOptions(){
        $("#settings-card").show("slow");
        $("#select-drugs-option-card").hide("slow");
      }

      function goBackMainStoreLogsCard () {
        $("#select-drugs-option-card").show("slow");
        $("#main-store-logs-card").hide("slow");
      }

      function goBackMainStoreCard () {
        $("#settings-card").show("slow");
        $("#main-store-card").hide("slow");
        $("#add-drugs-main-store-btn").hide("fast");
      }

      function goBackAddDrugsMainStoreCard () {
        $("#main-store-card").show("slow");
        $("#add-drugs-main-store-btn").show("fast");
        $("#add-drugs-main-store-card").hide("slow");
      }

      function goBackViewDrugMainStoreCard () {
        $("#main-store-card").show("slow");
        $("#add-drugs-main-store-btn").show("fast");
        $("#view-drug-main-store-card").hide("slow");
        $("#edit-drug-main-store-btn").hide("fast");
        $("#move-drug-to-dispenary-btn").hide("fast");
      }

      function goBackEditDrugCard () {
        $("#view-drug-main-store-card").show("slow");
        $("#edit-drug-main-store-btn").show("fast");
        $("#move-drug-to-dispenary-btn").show("fast");
        $("#edit-drug-form").attr("data-id","");

        $("#edit-drug-card").hide();


      }

      function deleteDrug (elem,e,id) {
        if (!e) var e = window.event;                // Get the window event
        e.cancelBubble = true;                       // IE Stop propagation
        if (e.stopPropagation) e.stopPropagation();  // Other Broswers
        if(id != ""){
          swal({
            title: 'Warning?',
            text: "Are You Sure You Want To Delete This Drug?",
            html : "<h4 class='text-secondary'>Note: This Is Irreversible</h4>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete!'
          }).then((result) => {
            $(".spinner-overlay").show();
            $.ajax({
              url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/delete_drug'); ?>",
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "id="+id,
              success : function (response) {
                $(".spinner-overlay").hide();
                if(response.success == true){
                  document.location.reload();
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

      function moveDrugToDispensary (elem,e) {
        if (!e) var e = window.event;                // Get the window event
        e.cancelBubble = true;                       // IE Stop propagation
        if (e.stopPropagation) e.stopPropagation();  // Other Broswers
        var id = $(elem).attr("data-id");
        var quantity = $(elem).attr("data-quantity");
        if(id != "" && quantity != ""){
          // $("#move-drug-to-dispensary-form #quantity").attr("max",quantity);
          $("#move-drug-to-dispensary-form").attr("data-id",id);
          $("#add-drug-to-dispensary-modal").modal("show");
        }
      }

      function editDrugMainStore(elem,evt){
        var id = $(elem).attr("data-id");
        if(id != ""){
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drug_info'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "id="+id,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success == true && response.messages.length != 0){
                var messages = response.messages;
                $("#view-drug-main-store-card").hide("slow");
                $("#edit-drug-main-store-btn").hide("fast");
                $("#move-drug-to-dispenary-btn").hide("fast");
                $("#edit-drug-form").attr("data-id",id);
                $.each(messages, function(index, val) {
                  $("#edit-drug-form").find("#"+index).val(val);
                  $('#edit-drug-form select#'+index).val(val);
                  $('#edit-drug-form .selectpicker').selectpicker('refresh');
                  if(index == "yes"){
                    console.log('yes')
                    $('#edit-drug-form #yes').prop('checked',true);
                    $('#edit-drug-form #no').prop('checked',false);
                  }
                  
                });
                $("#edit-drug-card").show();


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

      function viewDrugMainStore (elem,event,id) {
        var quantity = $(elem).attr("data-quantity");
        if(id != "" && quantity != ""){
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_drug_main_store'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "id="+id,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success == true && response.messages != ""){
                var messages = response.messages;
                
                $("#main-store-card").hide("slow");
                $("#add-drugs-main-store-btn").hide("fast");
                $("#view-drug-main-store-card .card-body").html(messages);
                
                $("#view-drug-main-store-card").show("slow");
                $("#edit-drug-main-store-btn").attr("data-id",id);
                $("#edit-drug-main-store-btn").show("fast");
                $("#move-drug-to-dispenary-btn").attr("data-id",id);
                $("#move-drug-to-dispenary-btn").attr("data-quantity",quantity);
                $("#move-drug-to-dispenary-btn").show("fast");

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

      function addNewDrugMainStore(elem,event){
        $("#main-store-card").hide("slow");
        $("#add-drugs-main-store-btn").hide("fast");
        $("#add-drugs-main-store-card").show("slow");
      }

      

      function editDrugs(elem,evt){
        
        swal({
          title: 'Choose Action',
          text: "Do You Want To?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'View Store',
          cancelButtonText : "View Store Activity Logs"
        }).then(function(){
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_main_store'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "",
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success == true && response.messages != ""){
                var messages = response.messages;
                $("#settings-card").hide("slow");
                
                $("#main-store-card .card-body").html(messages);
                $("#main-store-card #main-store-table").DataTable();
                $("#main-store-card").show("slow");
                $("#add-drugs-main-store-btn").show("fast");
                // $("#add-drugs-main-store-btn").show("fast");
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
        }, function(dismiss){
           if(dismiss == 'cancel'){
              $(".spinner-overlay").show();
              $.ajax({
                url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_main_store_logs'); ?>",
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : "",
                success : function (response) {
                  $(".spinner-overlay").hide();
                  if(response.success == true && response.messages != ""){
                    var messages = response.messages;
                    $("#settings-card").hide("slow");
                    $("#main-store-logs-card .card-body").html(messages);
                    $("#main-store-logs-card #main-store-table").DataTable();
                    $("#main-store-logs-card").show("slow");
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

      function showTests(){
        $("#main-card").hide(); 
        $("#edit-tests-card").show();
      }

      function deleteTest (elem,e) {
        if (!e) var e = window.event;                // Get the window event
        e.cancelBubble = true;                       // IE Stop propagation
        if (e.stopPropagation) e.stopPropagation();  // Other Broswers
        var id = elem.getAttribute("data-id");
        var section_label = elem.getAttribute("data-label");
        console.log(id)
        swal({
          title: 'Warning?',
          text: "Are You Sure You Want To Delete This Test ?<h4>Note : This Would Delete All Sub Tests Of This Particular Test</h4>",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
          // if (result.value) {
          $(".spinner-overlay").show();
          $.ajax({
            url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/delete_test'); ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "id="+id,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success == true){
                 

                  document.location.reload();
                 
              }else if(response.delete_error == true){
                 $.notify({
                  message:"Error In Deleting Test"
                  },{
                    type : "warning"  
                  });
              }else if(response.id_error == true){
                 $.notify({
                  message:"Please Do Not Mess With The Form"
                  },{
                    type : "warning"  
                  });
              }else if(response.id_set_error == true){
                 $.notify({
                  message:"Please Do Not Mess With The Form"
                  },{
                    type : "warning"  
                  });
              }
            },
            error:function () {
             $(".spinner-overlay").hide(); 
              $.notify({
                message:"Sorry , Something Went Wrong"
                },{
                  type : "warning"  
                });
            }
          })
            
          // }
        });
      }

      function addTest1 (elem,e) {
        if (!e) var e = window.event;                // Get the window event
        e.cancelBubble = true;                       // IE Stop propagation
        if (e.stopPropagation) e.stopPropagation();  // Other Broswers
        var id = elem.getAttribute("data-id");
        var section_label = elem.getAttribute("data-label");
        var test_name = elem.getAttribute("data-test-name");
        console.log(id + " " + section_label);
        var header_str = "Add Test To " +test_name;
        $("#add-sub-test-modal #add-sub-test-form").attr({
          "data-super-test-id": id
        });
        $("#add-sub-test-form input").val("");
        $("#add-sub-test-form #range_enable").val("1");
        $("#add-sub-test-form #range_disable").val("0");
        $("#add-sub-test-form #units_enable").val("1");
        $("#add-sub-test-form #units_disable").val("0");
        $("#add-sub-test-form #control_enable").val("1");
        $("#add-sub-test-form #control_disable").val("0");
        $("#add-sub-test-form #range_type_interval").val("1");
        $("#add-sub-test-form #range_type_desirable").val("0");
        $("#add-sub-test-form #range_lower").val("0.000");
        $("#add-sub-test-form #range_higher").val("0.000");
        $("#add-sub-test-form #range_desirable_input").val(">2");
        $("#add-sub-test-form input[type='submit']").val("Submit");
        
        $("#add-sub-test-form #data-super-test-id").val(id);
        $("#add-sub-test-modal .modal-title").html(header_str);
        $("#add-sub-test-modal").modal("show");
      }

      function testClicked (elem,evt,no_of_sub_tests) {
        var section = elem.getAttribute("data-test-section-name");
        var id = elem.getAttribute("data-id");
        var test_id = elem.getAttribute("data-test-id");
        var test_name = elem.getAttribute("data-test-name");
        var sample_required = elem.getAttribute("data-sample-required");
        var indication = elem.getAttribute("data-indication");
        var test_cost = elem.getAttribute("data-test-cost");
        var ta_time = elem.getAttribute("data-ta-time");
        var active = elem.getAttribute("data-ta_active");
        var test_modal = $("#test-modal");
        var control_enabled = elem.getAttribute("data-control-enabled");
        
        var range_enabled = elem.getAttribute("data-range-enabled");
        var range_lower = elem.getAttribute("data-range-lower");
        var range_higher = elem.getAttribute("data-range-higher");
        var unit_enabled = elem.getAttribute("data-unit-enabled");
        var unit = elem.getAttribute("data-unit");
        var range_type = elem.getAttribute("data-range-type");
        var desirable_value = elem.getAttribute("data-desirable-value");
        console.log(range_type)
        $("#test-modal .modal-title").html("Edit Test Under "+section);
        $("#test-modal #edit-test-form").attr({
          'onsubmit' : 'submitEditTest(this,event,' + id + ')'
        });
        $("#test-modal #edit-test-form #test_id").val(test_id);
        $("#test-modal #edit-test-form #test_name").val(test_name);
        $("#test-modal #edit-test-form #sample_required").val(sample_required);
        $("#test-modal #edit-test-form #indication").val(indication);
        $("#test-modal #edit-test-form #test_cost").val(test_cost);
        $("#test-modal #edit-test-form #ta_time").val(ta_time);
        $("#test-modal #edit-test-form #id").val(id);
        $("#test-modal #edit-test-form #range_lower").val(range_lower);
        $("#test-modal #edit-test-form #range_higher").val(range_higher);
        $("#test-modal #edit-test-form #units_value").val(unit);
        $("#test-modal #edit-test-form #range_desirable_input").val(desirable_value);
        // $("#edit-range-div input").attr("required","true");
        // $("#edit-units-div input").attr("required","true");
        if(active == 1){
          $("#test-modal #edit-test-form #yes").prop("checked",true);
        }else if (active == 0){
          $("#test-modal #edit-test-form #no").prop("checked",true);

        }
        if(control_enabled == 1){
          $("#test-modal #edit-test-form #edit_control_enable").prop("checked",true);
        }else if(control_enabled == 0){
          $("#test-modal #edit-test-form #edit_control_disable").prop("checked",true);

        }

        if(range_type == "interval"){
          $("#test-modal #edit-test-form #range_type_interval").prop("checked",true);
        }else if(range_type == "desirable"){
          $("#test-modal #edit-test-form #range_type_desirable").prop("checked",true);
        }

        if(range_enabled == 1){
          $("#range-div").show();
          $("#test-modal #edit-test-form #edit_range_enable").prop("checked",true);
          if(range_type == "interval"){
            $("#test-modal #edit-test-form #range-interval-div").show();
            $("#test-modal #edit-test-form #range-desirable-div").hide();
          }else if(range_type == "desirable"){
            $("#test-modal #edit-test-form #range-interval-div").hide();
            $("#test-modal #edit-test-form #range-desirable-div").show();
          }
        }else if(range_enabled == 0){
          $("#range-div").hide();
          $("#test-modal #edit-test-form #edit_range_disable").prop("checked",true);
          $("#edit-range-div").hide();
          if(range_type == "interval"){
            $("#test-modal #edit-test-form #range-interval-div").show();
            $("#test-modal #edit-test-form #range-desirable-div").hide();
          }else if(range_type == "desirable"){
            $("#test-modal #edit-test-form #range-interval-div").hide();
            $("#test-modal #edit-test-form #range-desirable-div").show();
          }
        }

        if(unit_enabled == 1){
          $("#units-div").show();
          $("#test-modal #edit-test-form #edit_units_enable").prop("checked",true);
        }else if(unit_enabled == 0){
          $("#units-div").hide();
          $("#test-modal #edit-test-form #edit_units_disable").prop("checked",true);
          $("#edit-units-div").hide();
        }
        $(".form-error").html("");
        if(no_of_sub_tests > 0){
          swal({
            title: 'Choose Action',
            text: "Do You Want To?",
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'View Sub Tests',
            cancelButtonText : "Edit Test"
          }).then(function(){
              $(".spinner-overlay").show();
              var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/view_tests_sub_tests'); ?>";
              $.ajax({
                url : url,
                type : "POST",
                responseType : "json",
                dataType : "json",
                data : "test_id="+id,
                success : function (response) {
                  // console.log(response)
                  $(".spinner-overlay").hide();
                  if(response.success == true && response.messages != "" && response.test_name != ""){
                    $(".spinner-overlay").hide();
                    var messages = response.messages;
                    var test_name = response.test_name;
                    var card_header_str = "Sub Tests Of " + test_name;
                    $("#sub-tests-card .card-title").html(card_header_str);
                    $("#sub-tests-card .card-body").html(messages);

                    $("#sub-tests-table").DataTable();
                    $("#edit-tests-card").hide();
                    $("#sub-tests-card").show();
                  }
                },error : function (argument) {
                  $(".spinner-overlay").hide();
                }
              });  
          }, function(dismiss){
             if(dismiss == 'cancel'){
                test_modal.modal('show');
             }
          }); 
        } else{
          test_modal.modal('show');
        } 
        
      }

      function goBackSubTests1(){
        $("#sub-tests-card").hide();
        $("#edit-tests-card").show();
        
      }

      function submitEditTest (elem,evt,id) {
       evt.preventDefault();
       var me  = $(elem);
        var invalid_range = false;
        var invalid_desirable = false;
        var isChecked = false;
        var isCheckedInterval = false;
        var isCheckedDesirable = false;
        // var me = $('#edit-test-form');
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/test'); ?>";
        // console.log(url)
        var lower_range = Number(me.find("#range_lower").val());
        var higher_range = Number(me.find("#range_higher").val());
        var range_desirable_input = me.find("#range_desirable_input").val();
        var desirable_first_char = range_desirable_input.charAt(0);
        var desirable_first_two_chars = range_desirable_input.substring(0,2);
        var desirable_last_chars1 = range_desirable_input.substring(1);
        var desirable_last_chars2 = range_desirable_input.substring(2);
        
        if( me.find('#edit_range_enable:checked').length == 1){ isChecked = true; }
        if( me.find('#range_type_interval').length == 1){ isCheckedInterval = true; }
        if( me.find('#range_type_desirable').length == 1){ isCheckedDesirable = true; }
         
        if(isChecked == true ){

          if(isCheckedInterval){
            if(lower_range > higher_range){
              invalid_range = true;
            }
          }

          if(isCheckedDesirable){

            if(desirable_first_char != ">"){   
              if(isNaN(desirable_last_chars1)){         
                invalid_desirable = true;  
              }                          
            }

            if(desirable_first_char != "<"){
              if(isNaN(desirable_last_chars1)){
                invalid_desirable = true; 
              }
            }
          }

        } 


        console.log(invalid_desirable)
        
        if(invalid_range == false && invalid_desirable == false){
          console.log(me.serializeArray())
          $("#edit-range-val").html("")
          $("#edit-desirable-val").html("")
          console.log('Proceeding....')
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : me.serialize(),
            success : function (response) {

              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){
                $(".form-error").html("");
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

                var element = me.find('#'+key);
                
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
                message:"Sorry Something Went Wrong Please Your Internet Connection"
                },{
                  type : "danger"  
                });
            }
          }); 
        }else{
          if(invalid_range){
            me.find("#edit-range-val").html("The Higher Range Value Cannot Be Less Than The Lower Range Value");
          }else{

            me.find("#edit-desirable-val").html("Desirable Value Field Can Only Contain > or < symbols followed by a valid number.");
          }
        }
      }

    function radioFiredEnter (elem,evt) {
      var elem_id = elem.getAttribute("id");
      console.log(elem_id)
      if(elem_id == "range_disable"){
        $(".add-test-form #enter-range-div").hide();
        
      }else if(elem_id == "range_enable"){
        $(".add-test-form #enter-range-div").show();
      }else if(elem_id == "units_enable"){
        $(".add-test-form #units-div").show();
      }else if(elem_id == "units_disable"){
        $(".add-test-form #units-div").hide();
      }else if(elem_id == "range_type_interval"){
        $(".add-test-form #range-desirable-div").hide();
        $(".add-test-form #range-interval-div").show();
      }else if(elem_id == "range_type_desirable"){
        $(".add-test-form #range-interval-div").hide();
        $(".add-test-form #range-desirable-div").show();
        
      }

    }
 
    function radioFiredEdit (elem,evt) {
      var elem_id = elem.getAttribute("id");
      console.log(elem_id)
      if(elem_id == "edit_range_disable"){
        $("#edit-range-div").hide();
        // $("#edit-range-div input").removeAttr("required");
      }else if(elem_id == "edit_range_enable"){
        $("#edit-range-div").show();
        // $("#edit-range-div input").attr("required","true");
      }else if(elem_id == "edit_units_enable"){
        $("#edit-units-div").show();
        // $("#edit-units-div input").attr("required","true");
      }else if(elem_id == "edit_units_disable"){
        $("#edit-units-div").hide();
        // $("#edit-units-div input").removeAttr("required");
      }
      else if(elem_id == "range_type_interval"){
        $("#test-modal #edit-test-form #range-desirable-div").hide();
        $("#test-modal #edit-test-form #range-interval-div").show();
      }else if(elem_id == "range_type_desirable"){
        $("#test-modal #edit-test-form #range-interval-div").hide();
        $("#test-modal #edit-test-form #range-desirable-div").show();
        
      }
    }

     function goBackWardSettings(elem,evt){
      $("#settings-card").show();
       $("#ward-settings-card").hide();
     }

     function goBackEditWardAdmissionInfo(elem,evt){
      $("#ward-settings-card").show();
      $("#edit-ward-admission-info-card").hide();
     }

     function wardSettings (elem,evt) {
       evt.preventDefault();
       $("#settings-card").hide();
       $("#ward-settings-card").show();
     }

    function editAdmissionFee(elem,evt){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_ward_admission_fee_info'); ?>";
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
            if(messages != ""){
              var fee = messages.fee;
              var no_of_days = messages.no_of_days;
              var grace_days = messages.grace_days;

            }else{
              var fee = 10000;
              var no_of_days = 14;
              var grace_days = 2;
            }

            $("#edit-ward-admission-info-form #fee").val(fee);
            $("#edit-ward-admission-info-form #no_of_days").val(no_of_days);
            $("#edit-ward-admission-info-form #grace_days").val(grace_days);
            $("#ward-settings-card").hide();
            $("#edit-ward-admission-info-card").show();
          }
        },error : function (argument) {
          $(".spinner-overlay").hide();
        }
      });  
    }

    function goBackWardServices (elem,evt) {
      $("#ward-settings-card").show();
      $("#ward-services-card").hide();
      $("#add-ward-services-btn").hide("fast"); 
    }

    function serviceTypeFired(elem,evt){
      elem = $(elem);
      var id = elem.attr("id");
      if(id == "fixed" && elem.val() == 1){
        $("#add-ward-services-form #ward-price-div").show();
        $("#add-ward-services-form #ward-ppq-div").hide();
        $("#add-ward-services-form #ward-quantity-div").hide();
      }else if(id == "rate" && elem.val() == 0){
        $("#add-ward-services-form #ward-price-div").hide();
        $("#add-ward-services-form #ward-ppq-div").show();
        $("#add-ward-services-form #ward-quantity-div").show();
      }
    }

    function serviceTypeFired1(elem,evt){
      elem = $(elem);
      var id = elem.attr("id");
      if(id == "fixed" && elem.val() == 1){
        $("#edit-ward-services-form #ward-price-div").show();
        $("#edit-ward-services-form #ward-ppq-div").hide();
        $("#edit-ward-services-form #ward-quantity-div").hide();
      }else if(id == "rate" && elem.val() == 0){
        $("#edit-ward-services-form #ward-price-div").hide();
        $("#edit-ward-services-form #ward-ppq-div").show();
        $("#edit-ward-services-form #ward-quantity-div").show();
      }
    }

    function goBackAddNewWardService (elem,evt) {
      $("#add-ward-services-card").hide();
      $("#ward-services-card").show();
      $("#add-ward-services-btn").show("fast"); 
    }

    function goBackEditWardService (elem,evt) {
      $("#edit-ward-services-form #name").val("");
      
      $("#edit-ward-services-form #fixed").prop('checked', true);
      $("#edit-ward-services-form #price").val("");
      $("#edit-ward-services-form #ppq").val("")
      $("#edit-ward-services-form #quantity").val("");
      
      $("#edit-ward-services-card").hide();
      $("#ward-services-card").show();
      $("#add-ward-services-btn").show("fast");
      $("#edit-ward-services-form").attr('data-id', "");
    }

    function editWardService(elem,evt,id){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_ward_service_info'); ?>";
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

            $("#edit-ward-services-form #name").val(name);
            if(type == "fixed"){
              $("#edit-ward-services-form #fixed").prop('checked', true);
              $("#edit-ward-services-form #price").val(price);
              $("#edit-ward-services-form #ward-price-div").show();
              $("#edit-ward-services-form #ward-ppq-div").hide();
              $("#edit-ward-services-form #ward-quantity-div").hide();
            }else if(type == "rate"){
              $("#edit-ward-services-form #rate").prop("checked",true);
              $("#edit-ward-services-form #ppq").val(ppq)
              $("#edit-ward-services-form #quantity").val(quantity);
              $("#edit-ward-services-form #ward-price-div").hide();
              $("#edit-ward-services-form #ward-ppq-div").show();
              $("#edit-ward-services-form #ward-quantity-div").show();
            }
            $("#edit-ward-services-form").attr('data-id', id);
            $("#edit-ward-services-card").show();
            $("#ward-services-card").hide();
            $("#add-ward-services-btn").hide("fast");
          }
        },error : function (argument) {
          $(".spinner-overlay").hide();
        }
      });
    }

    function addNewWardService (elem,evt) {
      $("#add-ward-services-card").show();
      $("#ward-services-card").hide();
      $("#add-ward-services-btn").hide("fast"); 
    }

    function editServicesSettings (elem,evt) {
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_ward_services'); ?>";
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
            $("#ward-settings-card").hide();
            $("#ward-services-card .card-body").html(messages);
            $("#ward-services-card").show();
            $("#add-ward-services-btn").show("fast");
            $("#ward-services-table").DataTable();
          }
        },error : function (argument) {
          $(".spinner-overlay").hide();
          
        }
      });
    }
    </script>    
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
            $lab_structure = $row->lab_structure;
            $clinic_structure = $row->clinic_structure;
            $pharmacy_structure = $row->pharmacy_structure;
          }
        }
      ?>

      <!-- End Navbar -->
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <h3>Welcome <?php echo $user_name; ?></h3>
          <?php
            $depts_arr = $this->onehealth_model->getDeptById($dept_id);
            if(is_array($depts_arr)){
              foreach($depts_arr as $dept){
                $dept_name = $dept->name;
                $dept_slug = $dept->slug;        
              } 
              $sub_dept_info = $this->onehealth_model->getSubDeptBySlug($third_addition);
              if(is_array($sub_dept_info)){
                foreach($sub_dept_info as $sub_dept){
                  $sub_dept_id = $sub_dept->id;
                  $sub_dept_name = $sub_dept->name;
                  $sub_dept_slug = $sub_dept->slug;
                   
                }
              }  
            }
          ?>
          <?php if($this->onehealth_model->checkIfUserIsAnAdminOfFacility1($health_facility_slug)){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-primary" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/admin') ?>">Home</a>&nbsp;&nbsp; > >  </span>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-primary" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a>&nbsp;&nbsp; > >  <?php echo $sub_dept_name; ?> </span>
          <?php  } ?>
          <h3 style="text-transform: capitalize;" class="text-center"><?php echo $sub_dept_name; ?></h3>
          
          <div class="row">
            <div class="col-sm-12">
              <div class="card" id="sections-card" style="display: none;">

                <div class="card-header card-header-blue card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <div class="card-title">
                    <h4 style="text-transform: capitalize;"><?php echo $sub_dept_name; ?>'s personnel</h4>
                  </div>
                </div>
                <div class="card-body">
                  <button type="button" class="btn btn-round btn-warning" onclick="goDefault()">Go Back</button>
                  <?php if($dept_id == 1){ ?>
                  <?php if($lab_structure == "maximum" || $lab_structure == "standard"){ ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              
                              $num++;


                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php      
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php }else{ 
                    if($sub_dept_id != 6){
                  ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              if($personnel_id == 5 || $personnel_id == 17 || $personnel_id == 27 || $personnel_id == 36 || $personnel_id == 54){
                              
                              $num++;


                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php 
                              }     
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                    }else{
                  ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              if($personnel_id == 45 || $personnel_id == 46 || $personnel_id == 47 || $personnel_id == 359){
                              
                              $num++;


                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php 
                              }     
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php
                    }
                   } ?>
                  <?php }else if($dept_id == 3){ ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              
                              $num++;

                              if($clinic_structure == "mini"){
                                if($personnel_slug != "multitasking-records-officer" && $personnel_slug != "multitasking-nurse"){
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                                }
                              }else{
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                              }      
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  
                  <?php }else if($dept_id == 6){ ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              
                              

                              if($pharmacy_structure == "mini"){
                                if($personnel_id == 360){
                                  $num++;
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 
                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                                }
                              }else{
                                if($personnel_id != 360){
                                  $num++;
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php
                                }
                              }      
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php }else{ ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Name</th>
                          <th>Personnel</th>
                          <th>Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $num = 0;
                          if(is_array($personnels)){

                            foreach($personnels as $personnel){
                              $personnel_id = $personnel->id;
                              $personnel_name = $personnel->name;
                              $personnel_slug = $personnel->slug;
                              
                              $personnel_num = $this->onehealth_model->getPersonnelNum($health_facility_id,$personnel_id);
                              if($personnel_num == 1){
                                $personnel_user_id = $this->onehealth_model->getFirstPersonnelUserid($health_facility_id,$personnel_id);
                                $personnel_user_name = $this->onehealth_model->getUserParamById("user_name",$personnel_user_id);
                                $personnel_user_slug = $this->onehealth_model->getUserParamById("slug",$personnel_user_id);
                              }
                              
                              $num++;


                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td style="text-transform: capitalize;"><a style="color: #00bcd4;" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin'); ?>"><?php echo $personnel_name; ?></a></td>
                          <?php if($personnel_num == 1){ ?>
                          <td style="text-transform: capitalize;"><a target="_blank" href="<?php echo site_url('onehealth/'.$personnel_user_slug) ?>"><?php echo $personnel_user_name; ?></a></td>
                          <?php }else{ ?>
                            <td style="text-transform: capitalize;"><a class="blue-text" href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>"><?php echo $personnel_num; ?></a></td>
                          <?php } ?>
                          <?php if($personnel_num == 0){ ?>
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a>
                            </td>
                          <?php }else{ ?> 
                            <td class="td-actions text-right">
                              <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$personnel_slug.'/personnel') ?>" rel="tooltip" data-toggle="tooltip" title="View Personnel" class="btn btn-info">
                                <i class="fas fa-users"></i>
                              </a>
                                
                              <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/add_admin') ?>" rel="tooltip" data-toggle="tooltip" title="Add Personnel" class="btn btn-success">
                                <i class="fas fa-user-plus"></i>
                              </a> 

                              

                            </td>

                          <?php } ?> 
                        </tr>
                        <?php      
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php } ?>
                </div>
              </div>

              <div class="card" id="sub-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSubTests1()">Go Back</button>
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



              <div class="card" id="main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">All Drugs In Store</h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div> 

              <div class="card" id="main-store-logs-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreLogsCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Drugs Store Logs</h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div> 

  
              <div class="card" id="add-drugs-main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddDrugsMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Add New Drug To Main Store</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'add-drugs-main-store-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_drug_to_store',$attr);
                  ?> 
                    <span class="form-error1">*</span>: Required
                    <h3 class="form-sub-heading text-center">Drug Info</h4>
                    <div class="wrap">
                      <div class="form-row">

                        <div class="form-group col-sm-6">
                          <label for="class_name" class="label-control"><span class="form-error1">* </span> Class Name: </label>
                          <input name="class_name" id="class_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="generic_name" class="label-control"><span class="form-error1">* </span> Generic Name: </label>
                          <input name="generic_name" id="generic_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="formulation" class="label-control"><span class="form-error1">* </span> Formulation: </label>
                          <input name="formulation" id="formulation" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="brand_name" class="label-control"><span class="form-error1">* </span> Brand Name: </label>
                          <input name="brand_name" id="brand_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3">
                          <label for="strength" class="label-control"><span class="form-error1">* </span> Strength: </label>
                          <input name="strength" id="strength" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="strength_unit" class="label-control"><span class="form-error1">* </span> Unit Of Strength: </label>
                          <input name="strength_unit" id="strength_unit" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          
                          <label for="unit" class="label-control"><span class="form-error1">* </span> Unit: </label>
                          <input name="unit" id="unit" class="form-control" data-html="true" data-toggle="tooltip" data-placement="top" title="This Is The Unit In Which The Physician Must Prescribe e.g (tablets,mg,ml e.t.c)">
                          <span class="form-error"></span>
                        </div> 

                        

                        <div class="form-group col-sm-4">
                          
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" data-html="true" data-toggle="tooltip" title="This Is The Total Units Of This Drug In Your Facility e.g(Total Tablets,Total ml's, Total mg's e.t.c). Total Must Be In The Units Already Inputed. This Is Also The Unit Upon Which This Agent Will Be Dispensed.">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <p class="label"><span class="form-error1">*</span>  Poison? </p>
                          <div id="poison">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="1" id="yes"> Yes
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="0" id="no" checked> No
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          
                          <label for="expiry_date" class="label-control"><span class="form-error1">* </span> Expiry Date: </label>
                          <input name="expiry_date" id="expiry_date" class="form-control" type="date" >
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>


                    <h3 class="form-sub-heading text-center">Prescription Info</h4>
                    <div class="wrap" style="padding-bottom: 40px;">
                      <h4 style="font-size: 21px;" class="text-center">Common Adult Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_adult_dosage" class="label-control">Dosage: </label>
                          <input name="common_adult_dosage" type="number" step="any" id="common_adult_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_frequency_num" id="common_adult_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_frequency_time" id="common_adult_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="nocte">Nocte</option>
                            <option value="stat">Stat</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_duration_num" id="common_adult_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_duration_time" id="common_adult_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                          </select>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px; ]padding-bottom: 40px;" class="text-center">Common Pediatric Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_pediatric_dosage" class="label-control">Dosage: </label>
                          <input name="common_pediatric_dosage" type="number" step="any" id="common_pediatric_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_pediatric_dose_frequency_num" id="common_pediatric_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_frequency_time" id="common_pediatric_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="nocte">Nocte</option>
                            <option value="stat">Stat</option>
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any"  name="common_pediatric_dose_duration_num" id="common_pediatric_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_duration_time" id="common_pediatric_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                      </div>

                    </div>  

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px;" class="text-center">Pricing Info</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="price" class="label-control">Enter Price Per Unit: </label>
                          <input name="price" step="any" id="price" class="form-control">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>      
                    <input type="submit" class="btn btn-success">
                  </form>
                </div> 
              </div>

              <div class="card" id="edit-drug-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditDrugCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Edit This Drug</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'edit-drug-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/edit_drug',$attr);
                  ?>
                   <span class="form-error1">*</span>: Required
                    <h3 class="form-sub-heading text-center">Drug Info</h4>
                    <div class="wrap">
                      <div class="form-row">

                        <div class="form-group col-sm-6">
                          <label for="class_name" class="label-control"><span class="form-error1">* </span> Class Name: </label>
                          <input name="class_name" id="class_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="generic_name" class="label-control"><span class="form-error1">* </span> Generic Name: </label>
                          <input name="generic_name" id="generic_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="formulation" class="label-control"><span class="form-error1">* </span> Formulation: </label>
                          <input name="formulation" id="formulation" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="brand_name" class="label-control"><span class="form-error1">* </span> Brand Name: </label>
                          <input name="brand_name" id="brand_name" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3">
                          <label for="strength" class="label-control"><span class="form-error1">* </span> Strength: </label>
                          <input name="strength" id="strength" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3">
                          <label for="strength_unit" class="label-control"><span class="form-error1">* </span> Unit Of Strength: </label>
                          <input name="strength_unit" id="strength_unit" type="text" class="form-control">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          
                          <label for="unit" class="label-control"><span class="form-error1">* </span> Unit: </label>
                          <input name="unit" id="unit" class="form-control" data-html="true" data-toggle="tooltip" data-placement="top" title="This Is The Unit In Which The Physician Must Prescribe e.g (tablets,mg,ml e.t.c)">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" data-html="true" data-toggle="tooltip" title="This Is The Total Units Of This Drug In Your Facility e.g(Total Tablets,Total ml's, Total mg's e.t.c). Total Must Be In The Units Already Inputed. This Is Also The Unit Upon Which This Agent Will Be Dispensed.">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-4">
                          <p class="label"><span class="form-error1">*</span>  Poison? </p>
                          <div id="poison">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="1" id="yes"> Yes
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="poison" value="0" id="no" checked> No
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>
                            </div>
                            
                          </div>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-4">
                          
                          <label for="expiry_date" class="label-control"><span class="form-error1">* </span> Expiry Date: </label>
                          <input name="expiry_date" id="expiry_date" class="form-control" type="date" >
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>


                    <h3 class="form-sub-heading text-center">Prescription Info</h4>
                    <div class="wrap" style="padding-bottom: 40px;">
                      <h4 style="font-size: 21px;" class="text-center">Common Adult Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_adult_dosage" class="label-control">Dosage: </label>
                          <input name="common_adult_dosage" type="number" step="any" id="common_adult_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_frequency_num" id="common_adult_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_frequency_time" id="common_adult_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_adult_dose_duration_num" id="common_adult_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_adult_dose_duration_time" id="common_adult_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                          </select>
                          <span class="form-error"></span>
                        </div>

                      </div>
                    </div>

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px; ]padding-bottom: 40px;" class="text-center">Common Pediatric Dose</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="common_pediatric_dosage" class="label-control">Dosage: </label>
                          <input name="common_pediatric_dosage" type="number" step="any" id="common_pediatric_dosage" class="form-control">
                          <span class="form-error"></span>
                        </div>


                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Frequency</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any" name="common_pediatric_dose_frequency_num" id="common_pediatric_dose_frequency_num">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_frequency_time" id="common_pediatric_dose_frequency_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Frequency Time Range" data-size="7">
                            <option value="minutely" selected>Minutely</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                        

                        <h5 class="col-sm-12 text-center" style="font-size: 20px; margin-bottom: 40px; margin-top: 18px">Duration</h5>
                        <div class="form-group col-sm-3">
                          <input class="form-control" type="number" step="any"  name="common_pediatric_dose_duration_num" id="common_pediatric_dose_duration_num">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-3" style="padding: 0;">
                          <select name="common_pediatric_dose_duration_time" id="common_pediatric_dose_duration_time" class="form-control selectpicker" data-style="btn btn-primary btn-round" title="Select Duration Time Range" data-size="7">
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                            
                          </select>
                          <span class="form-error"></span>
                        </div>
                      </div>

                    </div>  

                    <div class="wrap">
                      <h4 style="font-size: 21px; margin-bottom: 40px; margin-top: 20px;" class="text-center">Pricing Info</h4>
                      <div class="form-row justify-content-center">
                        <div class="form-group col-sm-12">
                          <label for="price" class="label-control">Enter Price Per Unit: </label>
                          <input name="price" step="any" id="price" class="form-control">
                          <span class="form-error"></span>
                        </div>
                      </div>
                    </div>      
                    <input type="submit" class="btn btn-success">
                  </form>
                </div> 
              </div> 


              <div class="card" id="ward-settings-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Ward Settings</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackWardSettings(this,event)">Go Back</button>
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
                          <td onclick="editServicesSettings(this,event)">Edit Service Charges</td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>              
                </div> 
              </div>

              <div class="card" id="ward-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Ward Services</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackWardServices(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                </div> 
              </div>

              <div class="card" id="edit-ward-admission-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Edit Ward Admission Settings</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditWardAdmissionInfo()">Go Back</button>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'edit-ward-admission-info-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/edit_ward_admission_info',$attr);
                  ?>
                    
                    <div class="wrap">
                      <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="fee" class="label-control"><span class="form-error1">* </span>Admission Fee: </label>
                          <input type="number" class="form-control" id="fee" name="fee" required>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="no_of_days" class="label-control"><span class="form-error1">* </span>No. Of Days Covered By Admission Fee: </label>
                          <input type="number" class="form-control" id="no_of_days" name="no_of_days" required>
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-6">
                          <label for="grace_days" class="label-control"><span class="form-error1">* </span>Days Of Grace After Admission Fee Expires: </label>
                          <input type="number" class="form-control" id="grace_days" name="grace_days" required>
                          <span class="form-error"></span>
                        </div> 
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary">
                  </form> 


                </div> 
              </div>

              <div class="card" id="edit-ward-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Edit This Ward Service</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditWardService(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'edit-ward-services-form','class' => '');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/edit_ward_service',$attr);
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
  
                        <div class="form-group col-sm-12" id="ward-price-div">
                          <label for="price" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="price" name="price">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-12" style="display: none;" id="ward-ppq-div">
                          <label for="ppq" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="ppq" name="ppq">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12" style="display: none;" id="ward-quantity-div">
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

              <div class="card" id="add-ward-services-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Add New Ward Service</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewWardService(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'add-ward-services-form','class' => '');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/add_new_ward_service',$attr);
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
  
                        <div class="form-group col-sm-12" id="ward-price-div">
                          <label for="price" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="price" name="price">
                          <span class="form-error"></span>
                        </div> 

                        <div class="form-group col-sm-12" style="display: none;" id="ward-ppq-div">
                          <label for="ppq" class="label-control"><span class="form-error1">* </span>Price: </label>
                          <input type="number" class="form-control" id="ppq" name="ppq">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12" style="display: none;" id="ward-quantity-div">
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

              <div class="card" id="settings-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"><?php echo $sub_dept_name; ?> Settings:</h3>
                </div>
                <div class="card-body">
                  <button  type="button" class="btn btn-round btn-warning" onclick="goDefault()">Go Back</button>
                  
                  <div class="list-group">
                    <ol>
                      <?php if($dept_id == 1){ ?>
                      <li><a href="#" id="edit-test-card-link" class="text-primary list-group-item list-group-item-action">Edit Tests</a></li>
                      <?php } ?>

                      <?php if($dept_id == 5){ ?>
                      <li><a href="#" onclick="wardSettings(this,event)" class="text-primary list-group-item list-group-item-action">Ward Settings</a></li>
                      <?php } ?>

                      <?php if($dept_id == 6){ ?>
                      <li><a href="#" onclick="editDrugs(this,event)" class="text-primary list-group-item list-group-item-action">Manage Drugs Store</a></li>
                      <?php } ?>
                      <!-- <li><a href="#" class="text-primary list-group-item list-group-item-action">Second item</a></li>
                      <li><a href="#" class="text-primary list-group-item list-group-item-action">Third item</a></li> -->
                    </ol>
                  </div>
                </div> 
              </div>  
              
              <div class="card" id="edit-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Edit <?php echo $sub_dept_name; ?> Tests:</h3>
                </div>
                <div class="card-body">
                  <button type="button" class="btn btn-round btn-warning" onclick="goDefault()">Go Back</button>
                  <ul class="nav nav-pills nav-pills-info" role="tablist">
                    <?php
                      $test_sections = $this->onehealth_model->getTestSections($sub_dept_id);
                      if(is_array($test_sections)){
                        $i = 0;
                        foreach($test_sections as $row){
                          $i++;
                          $test_section_id = $row->id;
                          $test_section_name = $row->name;
                          $test_section_label = $row->label;
                          $upper_test_section_name = strtoupper($test_section_name);
                    ?>
                    <li class="nav-item" >
                      <a href="#<?php echo $test_section_label; ?>" class="nav-link <?php if($i == 1){ echo 'active show'; } ?>" data-toggle="tab" role="tablist">
                        <?php echo $test_section_name; ?>
                      </a>
                    </li>
                   
                    <?php
                        }
                      }
                    ?>
                  </ul>
                    <?php
                      $test_sections = $this->onehealth_model->getTestSections($sub_dept_id);
                      if(is_array($test_sections)){
                    ?>
                  <div class="tab-content">
                    <?php
                        $i = 0;
                        foreach($test_sections as $row){
                          $i++;
                          $test_section_id = $row->id;
                          $test_section_name = $row->name;
                          $test_section_label = $row->label;
                    ?>

                      <div class="tab-pane <?php if($i == 1){ echo 'active'; } ?>" id="<?php echo $test_section_label; ?>">
                         
                        <table class="table table-striped tests-table table-responsive table-bordered" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>#</th>
                              
                              <th>Test Id</th>
                              <th>Test Name</th>
                              <th>Sample Required</th>
                              <th>Indication</th>
                              <th>Cost</th>
                              <th>TurnAround Time<small>(days)</small></th>
                              <th>No. Of Sub Tests</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $health_facility_test_table_name = $this->onehealth_model->createTestTableHeaderString($health_facility_id,$health_facility_name);
                              $tests = $this->onehealth_model->getTestsBySection($test_section_label,$health_facility_test_table_name,$sub_dept_id);
                              if(is_array($tests)){
                                $j = 0;
                                foreach($tests as $row){
                                  $j++;
                                  $id = $row->id;
                                  $test_id = $row->test_id;
                                  $test_name = $row->name;
                                  $sample_required = $row->sample_required;
                                  $indication = $row->indication;
                                  $test_cost = $row->cost;
                                  $ta_time = $row->t_a;
                                  $ta_active = $row->active;
                                  $control_enabled = $row->control_enabled;
                                  
                                  $range_enabled = $row->range_enabled;
                                  $range_lower = $row->range_lower;
                                  $range_higher = $row->range_higher;
                                  $range_type = $row->range_type;
                                  $unit_enabled = $row->unit_enabled;
                                  $desirable_value = $row->desirable_value;
                                  $unit = $row->unit;
                                  $no_of_sub_tests = $this->onehealth_model->getNoOfSubTests($health_facility_test_table_name,$id);
                            ?>
                            <tr onclick="return testClicked(this,event,<?php echo $no_of_sub_tests; ?>)" data-test-section-name="<?php echo $test_section_name; ?>" data-id="<?php echo $id; ?>" data-test-id="<?php echo $test_id; ?>" data-test-name="<?php echo $test_name; ?>" data-sample-required="<?php echo $sample_required; ?>" data-indication="<?php echo $indication; ?>" data-test-cost="<?php echo $test_cost; ?>" data-ta-time="<?php echo $ta_time; ?>" data-ta_active="<?php echo $ta_active; ?>" data-control-enabled="<?php echo $control_enabled; ?>" data-range-enabled="<?php echo $range_enabled; ?>" data-range-lower="<?php echo $range_lower; ?>" data-range-higher="<?php echo $range_higher ?>" data-unit-enabled="<?php echo $unit_enabled ?>" data-unit="<?php echo $unit; ?>" data-range-type="<?php echo $range_type; ?>" data-desirable-value="<?php echo $desirable_value; ?>">
                              
                              <td><?php echo $id; ?></td> 
                              <td><?php echo $test_id; ?></td>
                              <td><?php echo $test_name; ?></td>
                              <td><?php echo $sample_required; ?></td>
                              <td><?php echo $indication; ?></td>
                              <td><?php echo $test_cost; ?></td>
                              <td><?php echo $ta_time; ?></td>
                              <td><?php echo $no_of_sub_tests; ?></td>
                              <td class="td-actions">
                                <button style="margin-right: 5px;" type="button" onclick="addTest1(this,event)" class="btn btn-success add-test-1" data-toggle="tooltip" title="Add Sub Test" data-id="<?php echo $id; ?>" data-test-name="<?php echo $test_name; ?>" data-label="<?php echo $test_section_label; ?>">
                                  <i class="material-icons">add</i>
                                </button>

                                <button type="button" onclick="deleteTest(this,event)" class="btn btn-danger delete-test" data-toggle="tooltip" title="Delete Test" data-id="<?php echo $id; ?>" data-label="<?php echo $test_section_label; ?>">
                                  <i class="material-icons">delete</i>
                                </button>
                              </td>
                            </tr>
                            <?php      
                                }
                              }
                            ?>
                          </tbody>
                        </table>
                       
                         <div class="add-test" rel="tooltip" data-toggle="modal" data-target="#add-test-modal-<?php echo $test_section_label; ?>" data-toggle="tooltip" title="Add New Test" style="cursor: pointer; position: fixed; bottom: 0; right: 0; background: #e91e63; border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
                            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

                            </div>
                          </div>

          

                        <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="add-test-modal-<?php echo $test_section_label; ?>" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" style="text-transform: capitalize;">Add New Test To <?php echo $test_section_name; ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>

                              <div class="modal-body">
                                <?php $attributes = array('class' => 'add-test-form','id' => $test_section_label.'-add-test-form') ?>
                                <?php echo form_open('onehealth/index/'.$health_facility_slug.'/'.$second_addition.'/'.$third_addition.'/add_test',$attributes); ?>
                                  <input type="hidden" value="<?php echo $test_section_label; ?>" name="section">
                                  <div class="form-group">
                                    <label for="test_id">Enter Test Id: </label>
                                    <input type="text" id="test_id" class="form-control" name="test_id" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="test_name">Enter Test Name: </label>
                                    <input type="text" id="test_name" class="form-control" name="test_name" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="test_sample">Enter Test Sample: </label>
                                    <input type="text" id="<test_sample" class="form-control" name="test_sample" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="test_indication">Enter Test Indication: </label>
                                    <input type="text" id="test_indication" class="form-control" name="test_indication" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="test_cost">Enter Test Cost: </label>
                                    <input type="number" id="test_cost" class="form-control" name="test_cost" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="test_ta">Enter Test Turn Around Time(days): </label>
                                    <input type="number" id="test_ta" class="form-control" name="test_ta" required>
                                    <span class="form-error"></span>
                                  </div>
                                  <div class="form-group">
                                    <p class="label">Reference Range: </p>
                                    <div id="range">
                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="range" id="range_enable" value="1" onclick="return radioFiredEnter(this,event)" checked> Enable                                   
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>

                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">                                  
                                          <input type="radio" class="form-check-input" name="range" id="range_disable" value="0"  onclick="return radioFiredEnter(this,event)">Disable 
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>
                                    </div> 
                                    <span class="form-error"></span> 
                                  </div>

                                  <div id="enter-range-div">
                                    <div class="form-group">
                                      <p class="label">Range Type: </p>
                                      <div id="range">
                                        <div class="form-check form-check-radio form-check-inline">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="range_type" id="range_type_interval" value="1" onclick="return radioFiredEnter(this,event)" checked> Interval                                   
                                            <span class="circle">
                                                <span class="check"></span>
                                            </span>
                                          </label>                                                                 
                                        </div>

                                        <div class="form-check form-check-radio form-check-inline">
                                          <label class="form-check-label">                                  
                                            <input type="radio" class="form-check-input" name="range_type" id="range_type_desirable" value="0"  onclick="return radioFiredEnter(this,event)">Desirable Limit 
                                            <span class="circle">
                                                <span class="check"></span>
                                            </span>
                                          </label>                                                                 
                                        </div>
                                      </div> 
                                      <span class="form-error"></span> 
                                    </div>

                                    <div id="range-interval-div">
                                      <div class="form-group">
                                        <label for="range_lower">Edit Test Lower Range Value: </label>
                                        <input type="number" id="range_lower" class="form-control" name="range_lower" value="0.000" step="any">
                                        <span class="form-error"></span>
                                      </div>
                                      <div class="form-group">
                                        <label for="range_higher">Edit Test Higher Range Value: </label>
                                        <input type="number" id="range_higher" class="form-control" name="range_higher" value="0.000" step="any">
                                        <span class="form-error" id="range-val"></span>
                                      </div>
                                    </div>

                                    <div id="range-desirable-div">
                                      <div class="form-group">
                                        <label for="range_desirable_input">Edit Test Desirable Range Value: </label>
                                        <input type="text" id="range_desirable_input" class="form-control" name="range_desirable_input" value=">2">
                                        <span class="form-error" id="enter-desirable-val"></span>
                                      </div>
                                      
                                    </div>
                                  </div>
                                  
                                  
                                  
                                  <div class="form-group">
                                    <p class="label">Units: </p>
                                    <div id="units">
                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="units" id="units_enable" value="1" onclick="return radioFiredEnter(this,event)" checked> Enable                                   
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>

                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">                                  
                                          <input type="radio" class="form-check-input" name="units" id="units_disable" value="0" onclick="return radioFiredEnter(this,event)">Disable 
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>
                                    </div>  
                                    <span class="form-error"></span>
                                  </div>

                                  <div id="units-div">
                                    <div class="form-group">
                                      <label for="units_value">Edit Test Unit: </label>
                                      <input type="text" id="units_value" class="form-control" name="units_value" value="">
                                      <span class="form-error"></span>
                                    </div>
                                  </div>
                                  
                                 
                                  <div class="form-group">
                                    <p class="label">Control Values: </p>
                                    <div id="control_values">
                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="control_values" id="control_enable" value="1" onclick="return radioFiredEnter(this,event)" checked> Enable                                   
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>

                                      <div class="form-check form-check-radio form-check-inline">
                                        <label class="form-check-label">                                  
                                          <input type="radio" class="form-check-input" name="control_values" id="control_disable" value="0" onclick="return radioFiredEnter(this,event)">Disable 
                                          <span class="circle">
                                              <span class="check"></span>
                                          </span>
                                        </label>                                                                 
                                      </div>
                                    </div>  
                                    <span class="form-error"></span>
                                  </div>
                                  <input type="submit" class="btn btn-success" name="submit">
                                <?php echo form_close(); ?>                               
                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div> 

                        
                      </div>
                    <?php
                        }
                    ?>
                  </div>
                    <?php
                      }    
                    ?>
                  

                </div> 
              </div>  
              
              <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="test-modal" data-focus="true"  aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      
                          
                            <?php $attr = array('id' => 'edit-test-form');  ?>
                            <?php echo form_open('',$attr); ?>
                            <div class="form-group">
                              <label for="test_id">Edit Test Id: </label>
                              <input type="text" id="test_id" class="form-control" name="test_id" value="" required>
                              <span class="form-error"></span>
                            </div>
                            <div class="form-group">
                              <label for="test_name">Edit Test Name: </label>
                              <input type="text" id="test_name" class="form-control" name="test_name" value="" required>
                              <span class="form-error"></span>
                            </div>
                            <div class="form-group">
                              <label for="sample_required">Edit Sample Required: </label>
                              <input type="text" id="sample_required" class="form-control" name="sample_required" value="" required>
                              <span class="form-error"></span>
                            </div>
                            <div class="form-group">
                              <label for="indication">Edit Test Indication: </label>
                              <input type="text" id="indication" class="form-control" name="indication" value="" required>
                              <span class="form-error"></span>
                            </div>
                            <div class="form-group">
                              <label for="test_cost">Edit Test Cost: </label>
                              <input type="number" id="test_cost" class="form-control" name="cost" value="" required>
                              <span class="form-error"></span>
                            </div>
                            <div class="form-group">
                              <label for="ta_time">Edit Test TurnAround Time: </label>
                              <input type="text" id="ta_time" class="form-control" name="ta_time" value="" required>
                              <span class="form-error"></span>
                            </div>
                            
                            <div class="form-group">
                              <p class="label">Reference Range: </p>
                              <div id="range">
                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="range" id="edit_range_enable" value="1" onclick="return radioFiredEdit(this,event)"> Enable                                   
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>

                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">                                  
                                    <input type="radio" class="form-check-input" name="range" id="edit_range_disable" value="0"  onclick="return radioFiredEdit(this,event)">Disable 
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>
                              </div> 
                              <span class="form-error"></span> 
                            </div>
                            <div id="edit-range-div">
                              <div class="form-group">
                                <p class="label">Range Type: </p>
                                <div id="range">
                                  <div class="form-check form-check-radio form-check-inline">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="range_type" id="range_type_interval" value="1" onclick="return radioFiredEdit(this,event)" checked> Interval                                   
                                      <span class="circle">
                                          <span class="check"></span>
                                      </span>
                                    </label>                                                                 
                                  </div>

                                  <div class="form-check form-check-radio form-check-inline">
                                    <label class="form-check-label">                                  
                                      <input type="radio" class="form-check-input" name="range_type" id="range_type_desirable" value="0"  onclick="return radioFiredEdit(this,event)">Desirable Limit 
                                      <span class="circle">
                                          <span class="check"></span>
                                      </span>
                                    </label>                                                                 
                                  </div>
                                </div> 
                                <span class="form-error"></span> 
                              </div>

                              <div id="range-interval-div">
                                <div class="form-group">
                                  <label for="range_lower">Edit Test Lower Range Value: </label>
                                  <input type="number" id="range_lower" class="form-control" name="range_lower" value="" step="any">
                                  <span class="form-error"></span>
                                </div>
                                <div class="form-group">
                                  <label for="range_higher">Edit Test Higher Range Value: </label>
                                  <input type="number" id="range_higher" class="form-control" name="range_higher" value="" step="any">
                                  <span class="form-error" id="edit-range-val"></span>
                                </div>
                              </div>

                              <div id="range-desirable-div">
                                <div class="form-group">
                                  <label for="range_desirable_input">Edit Test Desirable Range Value: </label>
                                  <input type="text" id="range_desirable_input" class="form-control" name="range_desirable_input" value="">
                                  <span class="form-error" id="edit-desirable-val"></span>
                                </div>
                                
                              </div>

                            </div>
                            
                            <div class="form-group">
                              <p class="label">Units: </p>
                              <div id="units">
                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="units" id="edit_units_enable" value="1" onclick="return radioFiredEdit(this,event)"> Enable                                   
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>

                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">                                  
                                    <input type="radio" class="form-check-input" name="units" id="edit_units_disable" value="0" onclick="return radioFiredEdit(this,event)">Disable 
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>
                              </div>  
                              <span class="form-error"></span>
                            </div>

                            <div id="edit-units-div">
                              <div class="form-group">
                                <label for="units_value">Edit Test Unit: </label>
                                <input type="text" id="units_value" class="form-control" name="units_value" value="">
                                <span class="form-error"></span>
                              </div>
                            </div>
                            
                           
                            <div class="form-group">
                              <p class="label">Control Values: </p>
                              <div id="control_values">
                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="control_values" id="edit_control_enable" value="1" onclick="return radioFiredEdit(this,event)"> Enable                                   
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>

                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">                                  
                                    <input type="radio" class="form-check-input" name="control_values" id="edit_control_disable" value="0" onclick="return radioFiredEdit(this,event)">Disable 
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>
                              </div>  
                              <span class="form-error"></span>
                            </div> 

                            <div class="form-group">
                              <p class="label">Active ? </p>
                              <div id="control_values">
                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="active" id="yes" value="1"> Yes                                   
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>

                                <div class="form-check form-check-radio form-check-inline">
                                  <label class="form-check-label">                                  
                                    <input type="radio" class="form-check-input" name="active" id="no" value="0">
                                    No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                  </label>                                                                 
                                </div>
                              </div>  
                              <span class="form-error"></span>
                            </div>   
                            <!-- <div class="">
                              <p>Active ?</p>
                              <label class="radio-inline"><input type="radio" name="active" id="yes" value="1">Yes</label>
                              <label class="radio-inline"><input type="radio" name="active" id="no" value="0">No</label>
                            </div> -->
                            <input type="hidden" id="id" name="id" value="">
                             
                            <input type="submit" class="btn btn-success" name="submit">
                          <?php echo form_close(); ?>
                      </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
                </div>
              
          
              <div class="card" id="main-card">
                <div class="card-header">
                  <h4 class="card-title">Choose Your Action</h4>
                </div>
                <div class="card-body">
                  <h5>Do You Want To: </h5>
                  <div class="btn-grp" style="margin-top: 40px;">
                    <button type="button" id="edit-settings" class="btn btn-primary btn-round">Edit Settings</button>
                    <button type="button" id="view-sections" class="btn btn-info btn-round">View Sections</button>
                  </div>
                </div> 
              </div>
             
            <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="add-sub-test-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" style="text-transform: capitalize;"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <?php $attributes = array('class' => '','id' => 'add-sub-test-form','data-super-test-id' => '') ?>
                    <?php echo form_open('onehealth/index/'.$health_facility_slug.'/'.$second_addition.'/'.$third_addition.'/add_sub_test',$attributes); ?>
                      <input type="hidden" value="" name="super_test_id" id="data-super-test-id">
                      <div class="form-group">
                        <label for="test_id">Enter Test Id: </label>
                        <input type="text" id="test_id" class="form-control" name="test_id" required>
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group">
                        <label for="test_name">Enter Test Name: </label>
                        <input type="text" id="test_name" class="form-control" name="test_name" required>
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group">
                        <label for="test_sample">Enter Test Sample: </label>
                        <input type="text" id="test_sample" class="form-control" name="test_sample" required>
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group">
                        <label for="test_indication">Enter Test Indication: </label>
                        <input type="text" id="test_indication" class="form-control" name="test_indication" required>
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group">
                        <label for="test_ta">Enter Test Turn Around Time(days): </label>
                        <input type="number" id="test_ta" class="form-control" name="test_ta" required>
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group">
                        <p class="label">Reference Range: </p>
                        <div id="range">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="range" id="range_enable" value="1"  checked> Enable                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">                                  
                              <input type="radio" class="form-check-input" name="range" id="range_disable" value="0"  >Disable 
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>
                        </div> 
                        <span class="form-error"></span> 
                      </div>

                      <div id="enter-range-div">
                        <div class="form-group">
                          <p class="label">Range Type: </p>
                          <div id="range">
                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="range_type" id="range_type_interval" value="1"  checked> Interval                                   
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>                                                                 
                            </div>

                            <div class="form-check form-check-radio form-check-inline">
                              <label class="form-check-label">                                  
                                <input type="radio" class="form-check-input" name="range_type" id="range_type_desirable" value="0">Desirable Limit 
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                              </label>                                                                 
                            </div>
                          </div> 
                          <span class="form-error"></span> 
                        </div>

                        <div id="range-interval-div">
                          <div class="form-group">
                            <label for="range_lower">Edit Test Lower Range Value: </label>
                            <input type="number" id="range_lower" class="form-control" name="range_lower" value="0.000" step="any">
                            <span class="form-error"></span>
                          </div>
                          <div class="form-group">
                            <label for="range_higher">Edit Test Higher Range Value: </label>
                            <input type="number" id="range_higher" class="form-control" name="range_higher" value="0.000" step="any">
                            <span class="form-error" id="range-val"></span>
                          </div>
                        </div>

                        <div id="range-desirable-div">
                          <div class="form-group">
                            <label for="range_desirable_input">Edit Test Desirable Range Value: </label>
                            <input type="text" id="range_desirable_input" class="form-control" name="range_desirable_input" value=">2">
                            <span class="form-error" id="enter-desirable-val"></span>
                          </div>
                          
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <p class="label">Units: </p>
                        <div id="units">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="units" id="units_enable" value="1"  checked> Enable                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">                                  
                              <input type="radio" class="form-check-input" name="units" id="units_disable" value="0" >Disable 
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>
                        </div>  
                        <span class="form-error"></span>
                      </div>

                      <div id="units-div">
                        <div class="form-group">
                          <label for="units_value">Edit Test Unit: </label>
                          <input type="text" id="units_value" class="form-control" name="units_value" value="">
                          <span class="form-error"></span>
                        </div>
                      </div>
                      
                     
                      <div class="form-group">
                        <p class="label">Control Values: </p>
                        <div id="control_values">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="control_values" id="control_enable" value="1" onclick="return radioFiredEnter(this,event)" checked> Enable                                   
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>

                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">                                  
                              <input type="radio" class="form-check-input" name="control_values" id="control_disable" value="0" onclick="return radioFiredEnter(this,event)">Disable 
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>                                                                 
                          </div>
                        </div>  
                        <span class="form-error"></span>
                      </div>
                      <input type="submit" class="btn btn-success" name="submit">
                    <?php echo form_close(); ?>                               
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


            <div class="modal fade" data-backdrop="static" id="add-drug-to-dispensary-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" style="text-transform: capitalize;"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <?php $attributes = array('class' => '','id' => 'move-drug-to-dispensary-form') ?>
                    <?php echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/submit_add_to_dispensary_form',$attributes); ?>
                    <div class="form-group">
                      <label for="quantity">Enter Quantity: </label>
                      <input type="number" name="quantity" id="quantity" class="form-control" max="" step="any" required>
                      <span class="form-error"></span>
                    </div>
                    <input type="submit" class="btn btn-primary">
                    <?php echo form_close(); ?>                               
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

             <div id="move-drug-to-dispenary-btn" onclick="moveDrugToDispensary(this,event)" rel="tooltip" data-toggle="tooltip" title="Move This Drug To Dispsenary" style="background: #e91e63; cursor: pointer; position: fixed; bottom: 60px; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;">folder</i>

              </div>
            </div>
    

            <div id="edit-drug-main-store-btn" onclick="editDrugMainStore(this,event)" rel="tooltip" data-toggle="tooltip" title="Edit This Drug" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fas fa-edit" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

      
            <div id="add-drugs-main-store-btn" onclick="addNewDrugMainStore(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Drug To Main Store" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
            </div>

            <div id="add-ward-services-btn" onclick="addNewWardService(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Ward Service" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
              <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

              </div>
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
      $("#move-drug-to-dispensary-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        var quantity = me.find("#quantity").val();
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        });
        var max = me.find("#quantity").attr("max");

        console.log(form_data)
        if(!isNaN(quantity)  && quantity != "" ){
          quantity = parseFloat(quantity);
          // max = parseFloat(max);
          
          swal({
            title: 'Warning?',
            text: "You Are About To Move " + addCommas(quantity) +" Units Of This Drug To Dispensary. Are You Sure You Want To Proceed?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Proceed!'
          }).then((result) => {
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
                if(response.success && response.main_store_quantity !== "" && response.main_store_quantity1 !== "" && response.dispensary_quantity !== ""){
                  var main_store_quantity = response.main_store_quantity;
                  var main_store_quantity1 = response.main_store_quantity1;
                  var dispensary_quantity = response.dispensary_quantity;
                  console.log(main_store_quantity)
                  console.log(dispensary_quantity)
                  $.notify({
                  message:addCommas(quantity) +" Units Of This Drug Has Been Successfully Moved To Dispensary"
                  },{
                    type : "success"  
                  });
                  $('#move-drug-to-dispensary-form .form-error').html("");
                  $("#main-store-card tr[data-id='"+id+"'] .main-store-quantity").html(main_store_quantity);
                  $("#main-store-card tr[data-id='"+id+"'] .dispensary-quantity").html(dispensary_quantity);
                  $("#main-store-card tr[data-id='"+id+"']").attr("data-quantity",main_store_quantity1);
                  $("#view-drug-main-store-card .main-store-quantity").html(main_store_quantity);
                  $("#view-drug-main-store-card .dispensary-quantity").html(dispensary_quantity);
                  // me.find("#quantity").attr("max",main_store_quantity1);
                  $("#add-drug-to-dispensary-modal").modal("hide");
                }else if(response.too_big && response.main_store_quantity !== ""){
                  swal({
                    title: 'Ooops',
                    text: "Quantity Entered Cannot Exceed Main Store Quantity Which Is " + response.main_store_quantity + " Units",
                    type: 'error'
                  })
                }else{
                  $.each(response.messages, function (key,value) {

                  var element = $('#move-drug-to-dispensary-form #'+key);
                  
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
         
        }else{
          swal({
            title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        }
      })

      // $('[data-toggle="popover"]').popover();
      $('#edit-drug-form').submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        var form_data = me.serializeArray();
        var url = me.attr("action");
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        });
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
              $.notify({
              message:"Drug Edited Successfully"
              },{
                type : "success"  
              });
              $('#edit-drug-form .form-error').html("");
            }else if(response.expired){
              swal({
                title: 'Ooops',
                text: "Date Entered Must Be In The In The Future",
                type: 'error'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#edit-drug-form #'+key);
              
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

      $('#add-drugs-main-store-form').submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        console.log($(this).serializeArray())
        $(".spinner-overlay").show();
        var form_data = $(this).serializeArray();
        var url = me.attr("action");
      
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            console.log(JSON.stringify(response.form_data))
            $(".spinner-overlay").hide();
            if(response.success){
              document.location.reload();
            }else if(response.expired){
              swal({
                title: 'Ooops',
                text: "Date Entered Must Be In The In The Future",
                type: 'error'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#add-drugs-main-store-form #'+key);
              
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

      $('#add-drugs-main-store-form #unit').tooltip({'trigger':'focus', 'title': 'Password tooltip'});
      $('#add-drugs-main-store-form #quantity').tooltip({'trigger':'focus', 'title': 'Password tooltip'});

      $( "#generic_name" ).autocomplete({
        source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=generic_name') ?>"
      });

      $( "#formulation" ).autocomplete({
        source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=formulation') ?>"
      });

      $( "#class_name" ).autocomplete({
        source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=class_name') ?>"
      });

      $( "#brand_name" ).autocomplete({
        source: "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/get_drugs_autocomplete?type=brand_name') ?>"
      });

      $("#edit-ward-services-form").submit(function(evt) {
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
                $("#edit-ward-services-form .form-error").html("");
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#edit-ward-services-form #'+key);
                
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

      $("#add-ward-services-form").submit(function(evt) {
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
              setTimeout(function () {
                document.location.reload();
              }, 2000);
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#add-ward-services-form #'+key);
              
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

      $("#edit-ward-admission-info-form").submit(function(evt) {
        evt.preventDefault();

        var me  = $(this);
        
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
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
              message:"Settings Edited Successfully"
              },{
                type : "success"  
              });
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#edit-ward-admission-info-form #'+key);
              
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

      $(".add-test-form #range-desirable-div").hide();
      $("#add-sub-test-form #range-desirable-div").hide();

      $("#add-sub-test-form #range_enable").click(function (evt) {
        $("#add-sub-test-form #enter-range-div").show();
      })

      $("#add-sub-test-form #range_disable").click(function (evt) {        
        $("#add-sub-test-form #enter-range-div").hide();
      })

      $("#add-sub-test-form #units_enable").click(function (evt) {
        $("#add-sub-test-form").find("#units-div").show();
      });

      $("#add-sub-test-form #units_disable").click(function (evt) {
        $("#add-sub-test-form").find("#units-div").hide();
      });

      $("#add-sub-test-form #range_type_interval").click(function (evt) {
        $("#add-sub-test-form #range-desirable-div").hide();
        $("#add-sub-test-form #range-interval-div").show();
      });

      $("#add-sub-test-form #range_type_desirable").click(function (evt) {
        $("#add-sub-test-form #range-desirable-div").show();
        $("#add-sub-test-form #range-interval-div").hide();
      });


      $("#add-sub-test-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var invalid_range = false;
        var invalid_desirable = false;
        var isChecked = false;
        var isCheckedInterval = false;
        var isCheckedDesirable = false;
       
       
        var lower_range = Number(me.find("#range_lower").val());
        var higher_range = Number(me.find("#range_higher").val());
        var range_desirable_input = me.find("#range_desirable_input").val();
        var desirable_first_char = range_desirable_input.charAt(0);
        var desirable_first_two_chars = range_desirable_input.substring(0,2);
        var desirable_last_chars1 = range_desirable_input.substring(1);
        var desirable_last_chars2 = range_desirable_input.substring(2);
        console.log(higher_range)
        console.log(lower_range)
        if( me.find('#range_enable').is(':checked')){ isChecked = true; }
        if( me.find('#range_type_interval').is(':checked')){ isCheckedInterval = true; }
        if( me.find('#range_type_desirable').is(':checked')){ isCheckedDesirable = true; }
         
        if(isChecked == true ){

          if(isCheckedInterval){
            if(lower_range > higher_range){
              invalid_range = true;
            }
          }

          if(isCheckedDesirable){

            if(desirable_first_char != ">"){   
              if(isNaN(desirable_last_chars1)){         
                invalid_desirable = true;  
              }                          
            }

            if(desirable_first_char != "<"){
              if(isNaN(desirable_last_chars1)){
                invalid_desirable = true; 
              }
            }
          }
        }

        me.find("#range-val").html("");
        me.find("#enter-desirable-val").html("");
        $(".form-error").html("");
        if(invalid_range == false && invalid_desirable == false){
          console.log(me.serializeArray())
          me.find("#range-val").html("")
          me.find("#enter-desirable-val").html("")
          console.log('Proceeding....')
          $(".spinner-overlay").show();
          $.ajax({
            url : me.attr('action'),
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : me.serialize(),
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success == true && response.successful == true){
                $(".form-error").html("");
                document.location.reload();
              }else if(response.success == true && response.successful == false){
                $.notify({
                message:"Sorry Something Went Wrong"
                },{
                  type : "warning"  
                });
              }
              else{
                $(".form-error").html("");
               $.each(response.messages, function (key,value) {
                key = '#'+key;
                console.log(key)
                var element = $("#add-sub-test-form "+key);
                console.log(element.length)
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
                message:"Sorry Something Went Wrong Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
            }
          });  
        }else{
          if(invalid_range){
            me.find("#range-val").html("The Higher Range Value Cannot Be Less Than The Lower Range Value");
          }else{
            me.find("#enter-desirable-val").html("Desirable Value Field Can Only Contain > or < symbols followed by a valid number.");
          }
        }
      })

      var tests_table = $('.tests-table').DataTable();
      $('.tests-table tr').css({
        'cursor' : 'pointer'
      })
      $('.tests-table tbody').on('click', 'tr', function () {
          if ( $(this).hasClass('selected') ) {
              $(this).removeClass('selected');
          }
          else {
              tests_table.$('tr.selected').removeClass('selected');
              $(this).addClass('selected');
          }
      } );  
      $("#view-sections").click(function(evt){
        $("#sections-card").show("slow");
        $("#main-card").hide("slow");
      });

      $("#edit-settings").click(function(evt){
        $("#settings-card").show("slow");
        $("#main-card").hide("slow");
      });

      $("#edit-test-card-link").click(function (evt) {
        $("#edit-tests-card").show("slow");
        $(".add-test").css("display","inline-block");
        $("#settings-card").hide("slow");
      });

      <?php if($this->onehealth_model->getClinicalPathologyTestSectionByLabel($fivth_addition)){ ?>
        $("#edit-tests-card").show();
        $(".add-test").css("display","inline-block");
        $("#settings-card").hide();
        $('.nav-link a[href="#<?php echo $fivth_addition ?>"]').tab('show')
      <?php } ?>

      $(".add-test-1").click(function(e) {
        if (!e) var e = window.event;                // Get the window event
        e.cancelBubble = true;                       // IE Stop propagation
        if (e.stopPropagation) e.stopPropagation();  // Other Broswers
      });

      
      
      <?php
        if($no_admin == true){
      ?>
        swal({
          title: 'Warning?',
          text: "You do not currently have any admin in this section. Do Want To Add One?",
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
      <?php if($this->session->drug_deleted){ ?>
       $.notify({
        message:"Drug Deleted Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->drug_added){ ?>
       $.notify({
        message:"Drug Added Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->add_admin){ ?>
       $.notify({
        message:"Personnel Added Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>
      <?php if($this->session->successful){ ?>
      $.notify({
      message:"Test Added Successfully"
      },{
        type : "success"  
      });
      <?php }  ?>

       <?php if($this->session->update_successful){ ?>
      $.notify({
      message:"Tests Edited Successfully"
      },{
        type : "success"  
      });
      showTests();
      <?php }  ?>

      <?php if($this->session->test_delete){ ?>
       $.notify({
        message:"Test Deleted Successfully"
        },{
          type : "success"  
        });
     <?php } ?>
      <?php
        if(is_array($test_sections)){
          $i = 0;
          foreach($test_sections as $row){
            $i++;
            $test_section_id = $row->id;
            $test_section_name = $row->name;
            $test_section_label = $row->label;
            $upper_test_section_name = strtoupper($test_section_name);
      ?>

          $("#<?php echo $test_section_label ?>-add-test-form").submit(function (evt) {
            evt.preventDefault();
            var me = $(this);
            var invalid_range = false;
            var invalid_desirable = false;
            var isChecked = false;
            var isCheckedInterval = false;
            var isCheckedDesirable = false;
           
           
            var lower_range = Number(me.find("#range_lower").val());
            var higher_range = Number(me.find("#range_higher").val());
            var range_desirable_input = me.find("#range_desirable_input").val();
            var desirable_first_char = range_desirable_input.charAt(0);
            var desirable_first_two_chars = range_desirable_input.substring(0,2);
            var desirable_last_chars1 = range_desirable_input.substring(1);
            var desirable_last_chars2 = range_desirable_input.substring(2);
            console.log(higher_range)
            console.log(lower_range)
            if( me.find('#range_enable').is(':checked')){ isChecked = true; }
            if( me.find('#range_type_interval').is(':checked')){ isCheckedInterval = true; }
            if( me.find('#range_type_desirable').is(':checked')){ isCheckedDesirable = true; }
             
            if(isChecked == true ){

              if(isCheckedInterval){
                if(lower_range > higher_range){
                  invalid_range = true;
                }
              }

              if(isCheckedDesirable){

                if(desirable_first_char != ">"){   
                  if(isNaN(desirable_last_chars1)){         
                    invalid_desirable = true;  
                  }                          
                }

                if(desirable_first_char != "<"){
                  if(isNaN(desirable_last_chars1)){
                    invalid_desirable = true; 
                  }
                }
              }
              
            }
            if(invalid_range == false && invalid_desirable == false){
              console.log(me.serializeArray())
              me.find("#range-val").html("")
              me.find("#enter-desirable-val").html("")
              console.log('Proceeding....')
               $(".spinner-overlay").show();
                $.ajax({
                  url : me.attr('action'),
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : me.serialize(),
                  success : function (response) {
                    
                    $(".spinner-overlay").hide();
                    if(response.success == true && response.successful == true){
                      $(".form-error").html("");
                      document.location.reload();
                    }else if(response.success == true && response.successful == false){
                      $.notify({
                      message:"Sorry Something Went Wrong"
                      },{
                        type : "warning"  
                      });
                    }
                    else{
                      $(".form-error").html("");
                     $.each(response.messages, function (key,value) {

                      var element = me.find('#'+key);
                      console.log(element)
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
                      message:"Sorry Something Went Wrong . Please Check Your Internet Connection"
                      },{
                        type : "danger"  
                      });
                  }
                });  
            }else{
              if(invalid_range){
                me.find("#range-val").html("The Higher Range Value Cannot Be Less Than The Lower Range Value");
              }else{
                me.find("#enter-desirable-val").html("Desirable Value Field Can Only Contain > or < symbols followed by a valid number.");
              }
            }
          })

          $('#<?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table'));?> input[type="checkbox"]').on('change', function(e){
            
            if($(this).prop('checked'))
            {
               $(this).next().remove();
            } else {
                $(this).after('<input type="hidden" name="active[]" value="0">');
            }
          });
        
 
        
          $('#<?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_form')); ?>').submit( function(evt) {
            console.log(true)
            evt.preventDefault();

            swal({
              title: 'Continue?',
              text: "<p class='text-primary' id='num-tests-para'> Do Want To Continue?",
              type: 'success',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
              var <?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table')); ?> = $('#<?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table')); ?>').DataTable();
              var <?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table_')); ?>sData = <?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table')); ?>.$('input, select').serialize();
              console.log(<?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table_')); ?>sData);
              $(".spinner-overlay").show();
              $.ajax({
                url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/test'); ?>",
                type : "POST",
                responseType : "text",
                dataType : "text",
                data : <?php echo strtolower(url_title(preg_replace('/[\s-]+/', '_',$test_section_name). '_table_')); ?>sData,
                success : function (response) {
                  $(".spinner-overlay").hide();
                  if(response == 1){
                    $.notify({
                    message:"Tests Modified Successfully"
                    },{
                      type : "success"  
                    });
                  }else if(response == "unsuccessful"){
                    $.notify({
                    message:"Tests Not Modified Successfully"
                    },{
                      type : "warning"  
                    });
                  }else if(response == "field empty"){
                    $.notify({
                    message:"One Or More Fields Empty. Please Make Sure None Are Empty"
                    },{
                      type : "warning"  
                    }); 
                  }
                  else{
                    $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "danger"  
                    });
                  
                  }
                  console.log(response)
                 
                },
                error: function () {
                  $(".spinner-overlay").hide();
                   $.notify({
                    message:"Sorry Something Went Wrong"
                    },{
                      type : "danger"  
                    });
                }
              });

              return false;
            });
        } );
      <?php      
          }  
        }
      ?>
    });
  </script>
