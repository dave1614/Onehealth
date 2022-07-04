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
<script>
  var selected_rows = [];
  function clearOutstandingPayments (elem,evt) {
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
          $("#outstanding-bills-card .card-body").html(response.messages);
          $("#main-card").hide();
          $("#outstanding-bills-card").show();
          $("#outstanding-bills-table").DataTable();
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

  function goBackOustandingBillsCard (elem,evt) {
    $("#main-card").show();
    $("#outstanding-bills-card").hide();
  }

  function markOutstandingAsPaid (elem,evt,id) {
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
    swal({
      title: 'Warning',
      text: "Are You Sure You Want To Mark This Outstanding Payment As Paid",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText : "No"
    }).then(function(){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_mortuary_patient_outstanding_as_paid'); ?>";
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : {
          "id" : id
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
              "mortuary" : true,
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
    });
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
          $("#outstanding-bills-card").hide();
          $("#outstanding-bills-info-card").show();
          $("#mortuary-outstanding-bills-table").DataTable();
          $("#proceed-bills-selection-btn").show("fast");
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
    $("#outstanding-bills-card").show();
    $("#outstanding-bills-info-card").hide();
    $("#proceed-bills-selection-btn").hide("fast");
  }

  function checkAllMortuary(elem,evt){
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
              <?php
               if(is_null($health_facility_logo)){
                echo $data_url_img; 
               }else{ 
                ?> 
              <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
              <?php } ?>
              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">

                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <table class="table">
                    <tbody>
                      <tr class="pointer-cursor">
                        <td>1</td>
                        <td><a href="#" onclick="clearOutstandingPayments(this,event)">Clear Outstanding Payments</a></td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card" id="outstanding-bills-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOustandingBillsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">All Oustanding Bills</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="outstanding-bills-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOustandingBillsInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Patients Outstanding Bills</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
      </div>
      <div id="proceed-bills-selection-btn" onclick="proceedBillsSelection(this,event)" rel="tooltip"  data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
        <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
          <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>

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
