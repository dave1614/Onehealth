<?php
  if(is_array($curr_health_facility_arr)){
    foreach($curr_health_facility_arr as $row){
      $hospital_id = $row->id;
      $hospital_name = $row->name;
      $hospital_logo = $row->logo;
      $hopsital_email = $row->email;
      $hospital_phone = $row->phone;
      $hospital_country = $this->onehealth_model->getCountryById($row->country);
      $hospital_state = $this->onehealth_model->getStateById($row->state);
      $hospital_address = $row->address;
      $hospital_slug = $row->slug;
      $hospital_table_name = $row->table_name;
      $facility_structure = $row->facility_structure;
      $color = $row->color;
      $no_logo = false;
      $registered = false;

    }
?>
<?php if(is_array($this->onehealth_model->getFirstSubDept(1))){ 
  foreach($this->onehealth_model->getFirstSubDept(1) as $row){
    $sub_dept_name = $row->name;
    $sub_dept_slug = $row->slug;
  }
  

?>

<script>
  
  var tests_selected_obj = [];
  var patient_facility_id = <?php echo $this->onehealth_model->getPatientFacilityIdByUserIdAndFacilityId($hospital_id,$user_id); ?>;
  function goBackSubTests1(){
    $("#sub-tests-card").hide();
    $("#select-test-card").show();
    
  }

  

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

  

  function goDefaultCodes() {
    $("#test-info-card").hide();
    $("#initiation-codes-card").show();
  }

  

  function goDefault() {
    
    document.location.reload();
  }

  function openSelectTestsCard (elem) {
    
    submitPatientNameForm("registered_patient",'clinical-pathology');
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


  function openSelectTestsCard(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/get_tests_for_this_lab_patient') ?>";
   
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "type=others",
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          // console.log(messages)
          if(messages !== ""){  
            $("#main-card").hide();
            
            $("#select-tests-card .card-body").html(messages);
            $("#select-tests-card #select-tests-table").DataTable();
            $("#select-tests-card").show();
            $("#proceed-btn").show("fast");
            
          }

          // $('.table').DataTable();
        },
        error : function () {
          $(".spinner-overlay").hide();
          $(".sub-dept-tabs").show();
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "danger"  
          });
        } 
      }); 
      
    }
  }

  function goBackFromSelectTestsCard(elem,evt){
    if(tests_selected_obj.length == 0){
      $("#main-card").show();
      
      $("#select-tests-card").hide();
      $("#proceed-btn").hide("fast");
    }else{
      swal({
        title: 'Confirm Action',
        text: "Are You Sure You Want To Go Back?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Go Back'
      }).then((result) => {
        tests_selected_obj = [];
        $("#main-card").show();
        
        $("#select-tests-card").hide();
        $("#proceed-btn").hide("fast");
      });  
    }
  }



  function testRadioClicked(elem,e) {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    var isChecked =  elem.checked;
    var main_test_id = elem.getAttribute("data-main-test-id");
    var test_id = elem.getAttribute("data-testid");
    var test_name = elem.getAttribute("data-testname");
    var test_cost = elem.getAttribute("rel");
    var ta_time = elem.getAttribute("data-testta");
    
    var sub_dept_name = elem.getAttribute("data-sub-dept-name");
    var sub_dept_id = elem.getAttribute("data-sub-dept-id");
    var health_facility_slug = elem.getAttribute("data-facility-slug");
    var sub_dept_slug = elem.getAttribute("data-sub-dept-slug");
    var dept_slug = elem.getAttribute("data-dept_slug");
  
    var value = {
      "dept_slug" : dept_slug,
      "sub_dept_slug" : sub_dept_slug,
      "facility_slug" : health_facility_slug,
      "status" : "registered_patient",
      "main_test_id" : main_test_id,
      "test_id": test_id,
      "test_name":test_name,
      "test_cost" : test_cost,
      "ta_time" : ta_time,
      "sub_dept_id" : sub_dept_id,
      "sub_dept_name" : sub_dept_name
    };
    if(isChecked){
      tests_selected_obj.push(value)
    }else{      
      var index = tests_selected_obj.map(function(obj, index) {
          if(obj.test_id === test_id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        tests_selected_obj.splice(index, 1);
      }
    }

    console.log(tests_selected_obj)
  } 

  function viewTestSubTests (elem,e,url) {
    
    $(".spinner-overlay").show();
    
    var tr = $(elem.parentElement.parentElement);
    var id = tr.find(".tests-checkboxes").attr("data-main-test-id");
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "test_id="+id+"&receptionist=true",
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
          $("#select-tests-card").hide("fast");
          $("#sub-tests-card").show("fast");
          $("#proceed-btn").hide("fast");
        }
      },error : function (argument) {
        $(".spinner-overlay").hide();
      }
    });  
  }

  function patientCheckBoxEvt() {
    var total = tests_selected_obj.length;
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    for(var i = 0; i < tests_selected_obj.length; i++){
      var test_cost = tests_selected_obj[i]['test_cost'];
      sum += parseInt(test_cost);
    }
    if(total > 0){
      
      if(!$("#num-tests-para")){
        $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " test selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }else{
        $("#num-tests-para").remove();
        $("#welcome-heading").after("<p class='text-primary' id='num-tests-para'>" + total + " tests selected with total sum of ₦" + addCommas(sum) + ".</p>");
      }
    }else{
      $("#num-tests-para").html("");
    }
  }

  function proceed (elem,evt) {

    patientCheckBoxEvt();
    
    var total = tests_selected_obj.length;
    console.log({data : tests_selected_obj});
    console.log(JSON.stringify({data : tests_selected_obj}))
    // total += $('.tests-checkboxes:checkbox:hidden:checked').length;
    var sum = 0;
    // var selectedRows = client_table.rows({ selected: true }).length;
    var sub_dept_id = 0;
    
    for(var i = 0; i < tests_selected_obj.length; i++){
      var test_cost = tests_selected_obj[i]['test_cost'];
      sum += parseInt(test_cost);
    }
    

    if(total > 0){
      var q = 0;
      var selected_tests_str = '';
      selected_tests_str += '<p><em class="text-primary">You Can Go Back And Unselect Selected And Select New Tests If You Wish. Click The Proceed Button To Continue.</em></p>'
      selected_tests_str += '<div class="table-div table-responsive material-datatables" style="">';
      selected_tests_str += '<table class="table table-striped table-bordered nowrap hover" id="all-tests-selected-display-table" cellspacing="0" width="100%" style="width:100%">';
      selected_tests_str += '<thead>';
      selected_tests_str += '<tr>';
      selected_tests_str += '<th>#</th>';
      selected_tests_str += '<th>Test Id</th>';
      selected_tests_str += '<th>Test Name</th>';
      selected_tests_str += '<th>Cost(₦)</th>';
      selected_tests_str += '<th>TA Time(days)</th>';
      selected_tests_str += '<th>Dept. Name</th>';
      selected_tests_str += '</tr>';
      selected_tests_str += '</thead>';
      selected_tests_str += '<tbody>';
      for(j = 0; j < tests_selected_obj.length; j++){
        q++;
        
        var test_id = tests_selected_obj[j]['test_id'];
        var test_name = tests_selected_obj[j]['test_name'];
        var test_cost = tests_selected_obj[j]['test_cost'];
        var ta_time = tests_selected_obj[j]['ta_time'];
        var sub_dept_name = tests_selected_obj[j]['sub_dept_name'];

        selected_tests_str += '<tr>';
        selected_tests_str += '<td>'+q+'</td>';
        selected_tests_str += '<td>'+test_id+'</td>';
        selected_tests_str += '<td>'+test_name+'</td>';
        selected_tests_str += '<td>'+addCommas(test_cost)+'</td>';
        selected_tests_str += '<td>'+ta_time+'</td>';
        selected_tests_str += '<td style="text-transform: capitalize;">'+sub_dept_name+'</td>';

        selected_tests_str += '</tr>';

      }
      selected_tests_str += '</tbody>';
      selected_tests_str += '</table>';
      selected_tests_str += '</div>';

      selected_tests_str += '<div style="margin-top: 20px;">';
      selected_tests_str += '<h4>Total Cost Of Tests Selected: <em class="text-primary">₦'+addCommas(sum)+'</em></h4>';
      selected_tests_str += '</div>';

      $("#all-tests-selected-display-card .card-body").html(selected_tests_str);
      $("#all-tests-selected-display-card #all-tests-selected-display-table").DataTable({
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1
      });
      $("#select-tests-card").hide();
      $("#proceed-btn").hide("fast");
      $("#proceed-btn-1").show("fast");
      $("#all-tests-selected-display-card").show();
      window.scrollTo(0,0);
      
    }else{
      swal({
        type: 'error',
        title: 'Oops.....',
        text: 'Sorry, you have not selected any tests. Please Select To Continue'
        // footer: '<a href>Why do I have this issue?</a>'
      })
    }
  }

  function goBackFromAllTestsSelectedDisplayCard (elem,evt) {
    $("#select-tests-card").show();
    $("#proceed-btn").show("fast");
    $("#proceed-btn-1").hide("fast");
    $("#all-tests-selected-display-card").hide();
  }

  function proceed1 (elem,evt) {
    $("#all-tests-selected-display-card").hide("fast");
    $("#additional-information-on-tests-selected-card").show("fast");
    $("#proceed-btn-1").hide("fast");
    $("#proceed-from-additional-info-tests-selected-btn").show("fast");
  }

  function goBackFromSelectSubTestsCard(elem,evt){
    $("#select-tests-card").show("fast");
    $("#sub-tests-card").hide("fast"); 
    $("#proceed-btn").show("fast");
  }

  function reloadPage (elem) {
    document.location.reload(); 
  }
  
  function proceedFromAdditionalTestsSelected(elem,evt){
    var form_data = $("#additional-information-on-tests-selected-form").serializeArray();
    additional_patient_test_info = form_data;
    // console.log(additional_patient_test_info)
    var i = 0;
    var form_data = [];
        
          
    var submit_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/submit_tests_selected_patient') ?>";
    $(".spinner-overlay").show(); 

    var obj = {
      data : tests_selected_obj,
      additional_patient_test_info : additional_patient_test_info,
      patient_facility_id : patient_facility_id
    }
    console.log(obj)
    
    $.ajax({
        url : submit_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : obj,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response);
          if(response.success && response.initiation_code != ""){
            var initiation_code = response.initiation_code;
            swal({
              type: 'success',
              title: 'Successful',
              allowOutsideClick : false,
              allowEscapeKey :false,
              text: 'The Tests Have Been Added Successfully. Initiation code is <b class="text-primary" style="font-style: italic; cursor : pointer;" onclick="copyText(\'' + initiation_code+ '\')">' + initiation_code +'</b>. Click Initiation Code To Copy.'
              // footer: '<a href>Why do I have this issue?</a>'
            }).then((result) => {
              reloadPage();
            }); 
          }else if(response.patient_not_registered){
            swal({
              type: 'error',
              title: 'Error',
              text: 'This Patient Is Not Currently Registered With This Facility. Please Go Back And Select Another Patient'
              
            })
          }else{
            $(".spinner-overlay").hide();
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Sorry, something went wrong. Please try again!'
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
    })
  }

  function goBackFromAdditionalInformationOnTestsSelectedCard (elem,evt) {
    $("#all-tests-selected-display-card").show("fast");
    $("#additional-information-on-tests-selected-card").hide("fast");
    $("#proceed-from-additional-info-tests-selected-btn").hide("fast");
    $("#proceed-btn-1").show("fast");
  }

  function loadPreviousTransactions (elem,evt) {
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/load_previous_initiations_patient') ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        $(".spinner-overlay").hide();
        console.log(response);
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#carryOutTransaction").modal("hide");
          $("#tests-awaiting-payment-card .card-body").html(messages);
          $("#tests-awaiting-payment-card #initiation-codes-table").DataTable();
          $("#main-card").hide();
          $("#tests-awaiting-payment-card").show();
        }else if(response.not_registered){
          swal({
            type: 'error',
            title: 'Error!',
            text: 'You Are Not Registered In This Facility'
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
      error : function(){
        $(".spinner-overlay").hide();
        swal({
          type: 'error',
          title: 'Oops.....',
          text: 'Sorry, something went wrong. Please try again!'
          // footer: '<a href>Why do I have this issue?</a>'
        })
      }
    })
  }
  
  function goBackFromTestsAwaitingPaymentCard (elem,evt) {
    $("#carryOutTransaction").modal("show");
    $("#main-card").show();
    $("#tests-awaiting-payment-card").hide();
  }

  function loadPatientInitiationCodeTests(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");

    if(initiation_code != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/load_tests_previous_initiations_patient') ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response);
          if(response.success && response.messages != "" && response.total_amount != "" && response.sub_total_amount != ""){
            var messages = response.messages;
            var total_amount = response.total_amount;
            var sub_total_amount = response.sub_total_amount;


            $("#tests-awaiting-payment-info-card .card-body").html(messages);
            $("#tests-awaiting-payment-info-card table").DataTable();
            $("#tests-awaiting-payment-card").hide();
            $("#tests-awaiting-payment-info-card .card-title").html("Your Tests With Initiation Code: <em class='text-primary'>"+initiation_code+"</em>")
            $("#tests-awaiting-payment-info-card").show();
            $("#proceed-to-payment-btn").show("fast");
            $("#proceed-to-payment-btn").attr("data-initiation-code",initiation_code);
            $("#proceed-to-payment-btn").attr("data-total-amount",total_amount);
            $("#proceed-to-payment-btn").attr("data-sub-total-amount",sub_total_amount);
            window.scrollTo(0,0);
          }else if(response.not_registered){
            swal({
              type: 'error',
              title: 'Error!',
              text: 'You Are Not Registered In This Facility'
            })
          }else if(response.not_for_you){
            swal({
              type: 'error',
              title: 'Error!',
              text: 'This Initiation Is Not For You'
            })
          }else if(response.pfp){
            swal({
              type: 'info',
              title: '',
              text: "You Were Registered As A Part Fee Paying Patient When This Initiation Took Place. You Have To Go To This Facility Located At <?php echo $hospital_address ?> To Pay." 
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
        error : function(){
          $(".spinner-overlay").hide();
          swal({
            type: 'error',
            title: 'Oops.....',
            text: 'Sorry, something went wrong. Please try again!'
            // footer: '<a href>Why do I have this issue?</a>'
          })
        }
      })
    }
  }

  function goBackFromTestsAwaitingPaymentInfoCard (elem,evt) {
    $("#tests-awaiting-payment-card").show();
    $("#tests-awaiting-payment-info-card").hide();
    $("#proceed-to-payment-btn").hide("fast");
  }

  function proceedToPaymentBtn (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var total_amount = elem.attr("data-total-amount");
    var sub_total_amount = elem.attr("data-sub-total-amount");
    $("#online-payment-btn").attr("data-initiation-code",initiation_code)
    $("#online-payment-btn").attr("onclick","onlinePayment(this,event,"+total_amount+","+sub_total_amount+")")
    $("#choosePaymentMethod").modal("show");
  }

  function onlinePayment(elem,evt,total_amount,sub_total_amount) {
    var initiation_code = elem.getAttribute("data-initiation-code");
    
    swal({
      title: 'Continue?',
      text: "<p class='text-primary' id='num-tests-para'>Amount For Tests: ₦" + addCommas(total_amount) +".</p>" +
        "<p class='text-primary'>Vat: 7%</p>" +
        "<p class='text-primary'>Sub Total: ₦"+addCommas(sub_total_amount)+"</p>" +
       " Do Want To Continue To Payment?",
      type: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
      $(".spinner-overlay").show();
     window.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/test_online_payment/') ?>"+initiation_code);
    }); 
    
  }

  function physicalPayment (elem) {
    $("#choosePaymentMethod").modal("hide");
    $("#tests-awaiting-payment-info-card").hide();
    $("#pay-in-facility-card").show();
    $("#proceed-to-payment-btn").hide("fast");
  }
  function goBackFromPayInFacilityCard (elem,evt) {
    $("#choosePaymentMethod").modal("show");
    $("#tests-awaiting-payment-info-card").show();
    $("#pay-in-facility-card").hide();
    $("#proceed-to-payment-btn").show("fast");
  }


  function loadPatientInitiationCodeTable(initiation_code,type) {
    

    if(initiation_code != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/load_tests_previous_initiations_patient') ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response);
          if(response.success && response.messages != ""){
            var messages = response.messages;

            var balance = response.balance;
            if(balance > 0){
              var total_amount = response.total_amount;
              var sub_total_amount = response.sub_total_amount;
            }



            $("#tests-awaiting-payment-info-card-1 .card-body").html(messages);
            $("#tests-awaiting-payment-info-card-1 table").DataTable();
            $("#main-card").hide();
            $("#tests-awaiting-payment-info-card-1 .card-title").html("Your Tests With Initiation Code: <em class='text-primary'>"+initiation_code+"</em>")
            $("#tests-awaiting-payment-info-card-1").show();
            if(balance > 0){
              $("#proceed-to-payment-btn-1").show("fast");
              $("#proceed-to-payment-btn-1").attr("data-initiation-code",initiation_code);
              $("#proceed-to-payment-btn-1").attr("data-total-amount",total_amount);
              $("#proceed-to-payment-btn-1").attr("data-sub-total-amount",sub_total_amount);
            }
            $("#carryOutTransaction").modal("hide");
            window.scrollTo(0,0);
          }else if(response.not_for_you){
            swal({
              type: 'error',
              title: 'Error!',
              text: 'This Initiation Is Not For You'
            })
          }else if(response.not_registered){
            swal({
              type: 'error',
              title: 'Error!',
              text: 'You Are Not Registered In This Facility'
            })
          }else if(response.nfp){
            swal({
              type: 'info',
              title: '',
              text: "You Were Registered As A None Fee Paying Patient When This Initiation Took Place. So You Don't Need To Pay."
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
        error : function(){
          $(".spinner-overlay").hide();
          swal({
            type: 'error',
            title: 'Oops.....',
            text: 'Sorry, something went wrong. Please try again!'
            // footer: '<a href>Why do I have this issue?</a>'
          })
        }
      })
    }
  }

  function goBackFromTestsAwaitingPaymentInfoCard1 (elem,evt) {
    $("#main-card").show();
    $("#tests-awaiting-payment-info-card-1").hide();
    $("#proceed-to-payment-btn-1").hide("fast");
    $("#carryOutTransaction").modal("show");
  }
  
  function proceedToPaymentBtn1 (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var total_amount = elem.attr("data-total-amount");
    var sub_total_amount = elem.attr("data-sub-total-amount");
    $("#online-payment-btn-1").attr("data-initiation-code",initiation_code)
    $("#online-payment-btn-1").attr("onclick","onlinePayment(this,event,"+total_amount+","+sub_total_amount+")")
    $("#choosePaymentMethod-1").modal("show");
  }

  function physicalPayment1 (elem) {
    $("#choosePaymentMethod-1").modal("hide");
    $("#tests-awaiting-payment-info-card-1").hide();
    $("#pay-in-facility-card-1").show();
    $("#proceed-to-payment-btn-1").hide("fast");
  }

  function goBackFromPayInFacilityCard1 (elem,evt) {
    $("#choosePaymentMethod-1").modal("show");
    $("#tests-awaiting-payment-info-card-1").show();
    $("#pay-in-facility-card-1").hide();
    $("#proceed-to-payment-btn-1").show("fast");
  }
</script>
         <!-- End Navbar -->

       <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading..." style="">
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">

              <div class="card" id="tests-awaiting-payment-info-card-1" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Your Tests With </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackFromTestsAwaitingPaymentInfoCard1(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                                    
                </div> 
              </div>

              <div class="card" id="tests-awaiting-payment-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Your Tests With </h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackFromTestsAwaitingPaymentInfoCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                                    
                </div> 
              </div>


              <div class="card" id="tests-awaiting-payment-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Your Tests Awaiting Payment</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackFromTestsAwaitingPaymentCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                                    
                </div> 
              </div>


              <div class="card" id="additional-information-on-tests-selected-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Enter Additional Patient Information</h3>
                  <button onclick="goBackFromAdditionalInformationOnTestsSelectedCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                  <p><em class="text-primary">Note: No Field Is Required. Click The Proceed Button To Continue.</em></p>
                </div>
                <div class="card-body">
                  <?php
                    $attr = array('id' => 'additional-information-on-tests-selected-form');
                    echo form_open('',$attr);
                  ?>
                  
                  
                  <div class="wrap">
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="height">Height (metres): </label>
                        <input type="number" max="3" class="form-control" step="any" name="height" id="height" >
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="weight">Weight (kg): </label>
                        <input type="number" class="form-control" step="any" name="weight" id="weight" >
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <p class="label">  Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_yes" name="fasting" value="1"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" id="fasting_no" name="fasting" value="0" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="present-medications" class="label-control">Present Medications: </label>
                        <input type="text" class="form-control" id="edit_present_medications" name="present_medications">
                        <span class="form-error"></span>
                      </div>



                    </div>
                  </div>
                  </form>
                </div>
              </div>

              <div class="card" id="all-tests-selected-display-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">These are all the tests you selected.</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackFromAllTestsSelectedDisplayCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">

                                    
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
              
              <div class="card" id="select-tests-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Select Tests</h3>
                  <button onclick="goBackFromSelectTestsCard(this,event)" class="btn btn-round btn-warning">< < Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="test-info-card" style="display: none;">
                <div class="card-header">
                  <h2 class="card-title"><?php echo $hospital_name; ?></h2>
                </div>
                <div class="card-body" id="test-info-card-body">
                  
                </div>
              </div>
              <div class="card" id="main-card">
                <div class="card-header">
                  <h2 class="card-title"><?php echo $hospital_name; ?></h2>
                </div>
                <div class="card-body" style="margin-top: 50px;">
                  <button type="button" onclick="openSelectTestsCard(this,event)" class="btn btn-primary">Select Tests</button>
                  <button class="btn btn-info btn-action" data-toggle="modal" data-target="#carryOutTransaction">Carry Out Transaction</button>

                  <!-- <button class="btn btn-success btn-action" onclick="trackTest(this,event)">Track Test</button> -->
                  
                </div>
              </div>
              
              <div class="card" id="pay-in-facility-card" style="display: none;">
                <div class="card-header">
                  <h2 class="card-title">Pay In Health Facility</h2>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goBackFromPayInFacilityCard(this,event)">Go Back</button>
                  <h5 class="text-secondary">Note: To Pay In Health Facilty, Copy Down Your Initiation Code And Proceed To The Health Facility Located At <span class="text-primary" style="font-style: italic;"><?php echo $hospital_address; ?></span>. Ask For The Teller And Give Him Your Initiation Code And Complete Payment.</h5>
                  
                </div>
              </div>

              <div class="card" id="pay-in-facility-card-1" style="display: none;">
                <div class="card-header">
                  <h2 class="card-title">Pay In Health Facility</h2>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goBackFromPayInFacilityCard1(this,event)">Go Back</button>
                  <h5 class="text-secondary">Note: To Pay In Health Facilty, Copy Down Your Initiation Code And Proceed To The Health Facility Located At <span class="text-primary" style="font-style: italic;"><?php echo $hospital_address; ?></span>. Ask For The Teller And Give Him Your Initiation Code And Complete Payment.</h5>
                  
                </div>
              </div>

              
              
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="choosePaymentMethod-1" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="carryOutTransaction" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title"><span class="text-secondary">Choose Payment Method</span></h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <p class="text-secondary">Note: Once Payment Is Completed Please Go To The Facility For Collection Of Sample.</p>
              <p>Facility Address: <?php echo $hospital_address; ?>. <span class="text-primary">Please Save It.</span></p>
              <?php
               if($this->onehealth_model->checkIfBankDetailsAreSet($hospital_name,$hospital_id)){ 
                ?>
              <button class="btn btn-primary" id="online-payment-btn-1" onclick="return onlinePayment(this)">Online Payment</button>
              <?php 
            } 
            ?>
              <button class="btn btn-info" onclick="return physicalPayment1(this)">Pay In Health Facility</button>
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return goDefault()">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="carryOutTransaction" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="carryOutTransaction" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><span class="text-secondary">Note: Code Is Case Sensitive</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php $attributes = array('class' => '','id' => 'initiation-code-form') ?>
              <?php echo form_open('',$attributes); ?>

                <div class="form-group">

                  <label for="initiation-code">Enter Initiation Code: </label>
                  <input type="text" id="initiation-code" class="form-control" name="initiation-code" required>
                  <span class="form-error"></span>
                </div>
                <input type="submit" class="btn btn-success" value="PROCEED" name="submit">
              <?php echo form_close(); ?>
              <span class="form-error">Cannot Remember Initiation Code?</span><br>
              <button class="btn btn-primary" onclick="loadPreviousTransactions(this)">Click Here</button>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return goDefault()">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="choosePaymentMethod" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="carryOutTransaction" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title"><span class="text-secondary">Choose Payment Method</span></h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <p class="text-secondary">Note: Once Payment Is Completed Please Go To The Facility For Collection Of Sample.</p>
              <p>Facility Address: <?php echo $hospital_address; ?>. <span class="text-primary">Please Save It.</span></p>
              <?php
               if($this->onehealth_model->checkIfBankDetailsAreSet($hospital_name,$hospital_id)){ 
                ?>
              <button class="btn btn-primary" id="online-payment-btn" onclick="return onlinePayment(this)">Online Payment</button>
              <?php 
            } 
            ?>
              <button class="btn btn-info" onclick="return physicalPayment(this)">Pay In Health Facility</button>
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="return goDefault()">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="paymentSuccess" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="paymentSuccessful" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><span class="text-primary"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body">
              
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div id="proceed-to-payment-btn-1" onclick="proceedToPaymentBtn1(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed To Payment For These Tests" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
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

      <div id="proceed-btn-1" onclick="proceed1(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
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
          <?php
          $code_date = date("j");
          $code_time = date("h");
          $initiation_code = substr(bin2hex($this->encryption->create_key(8)),4). '-' . $code_date .'-' . $code_time;
        ?>
        <p id="var-dump" style="display: none;"><?php echo $initiation_code; ?></p>
        </div>
      </footer>
      
      <script>
        $(document).ready(function() {
         
         
          $("#initiation-code-form").submit(function (evt) {
            evt.preventDefault();
            var initiation_code = $("#initiation-code-form #initiation-code").val();
            $("#no-record").remove();
            loadPatientInitiationCodeTable(initiation_code,"new");
          });
        <?php
         if($this->session->paid_successfully){ 
          unset($_SESSION['paid_successfully'])
          ?>
          $.notify({
          message:"Payment Successful. A Notification Has Been Sent To You. You Would Find Your Receipt Attached"
          },{
            type : "success"  
          });
        <?php } ?>
        <?php
         if($this->session->refundrequestapproved){ 
          unset($_SESSION['refundrequestapproved'])
          ?>
          $.notify({
          message:"Payment Has Been Refunded Successfully"
          },{
            type : "success"  
          });
        <?php } ?>  
        <?php
        if($this->session->refundrequestdeclined){ 
          unset($_SESSION['refundrequestdeclined'])
          ?>
          $.notify({
          message:"Refund Request Has Been Declined Successfully"
          },{
            type : "success"  
          });
        <?php } ?> 
        <?php
         if($this->session->error && $this->session->error == "wrong"){ 
          
          ?>
          $.notify({
          message:"Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "warning"  
          });
        <?php } ?>  
        <?php
         if($this->session->error && $this->session->error == "invalid_code"){ 
          
          ?>
          $.notify({
          message:"Invalid Code Entered"
          },{
            type : "warning"  
          });
        <?php } ?>  
        <?php
        if($this->session->error && $this->session->error == "code_absent"){ 
          
          ?>
          $.notify({
          message:"Something Went Wrong"
          },{
            type : "danger"  
          });
        <?php } ?>  

        <?php if(!$this->onehealth_model->checkIfPatientHasHisDetailsComplete($user_id)){ ?>
        swal({
          type: 'info',
          title: 'Warning',
          allowOutsideClick : false,
          allowEscapeKey :false,
          text: "We've Noticed That Your Patient Information Are Not Complete. Please Click On The Button Below Or Use The Edit Patient Information Tab On The Sidebar To Complete These Details"
          
        }).then((result) => {
          window.location.assign("<?php echo site_url('onehealth/edit_patient_info'); ?>");
        });
      <?php } ?>

      <?php 
      if($this->session->online_payment_for_tests_completed && isset($_GET['receipt_file'])){ 
        unset($_SESSION['online_payment_for_tests_completed']);
        $receipt_file = $_GET['receipt_file'];
      ?>
        swal({
          type: 'success',
          title: 'Payment Successful',
          text: "Your Online Payment Has Been Verified Successfully. Click The button Below To View Receipt",
          confirmButtonText: 'View Your Receipt'
          
        }).then((result) => {
          window.location.assign("<?php echo $receipt_file; ?>");
        });
      <?php } ?>
      });
      </script>
    </div>
  </div>
<?php }  }?>
  <!--   Core JS Files   -->
 