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
            
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
      ?>

           <!-- End Navbar -->
      <?php
         if(is_null($health_facility_logo)){
          echo $data_url_img; 
         }else{ 
          ?> 
        <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
        <?php } ?>
      <script>
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
       </script>
      <div class="spinner-overlay">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
        <p>Processing.... Please Do Not Leave Or Reload This Page</p>
      </div>
      <div class="content">
        <div class="container-fluid">
          
          <div class="row">
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      <?php 

      ?>
    
      <script>
        window.onbeforeunload = function() {
            return "If You Reload Or Leave This Page You Could Lose Your Money!";
        }
        $(document).ready(function() {
         
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
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/submit_test_payment_form/'.$third_addition.'?reference='.$_GET["reference"]); ?>";
          $(".spinner-overlay").show();
          $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "show_records=true",
              success : function(response){
                $(".spinner-overlay").hide();
                if(response.success && response.records != ""){
                  response = response.records
                  var lab_id = response.lab_id;
                  var patient_name = response.patient_name;
                  var receipt_number = response.receipt_number;
                  var facility_state = response.facility_state;
                  var facility_country = response.facility_country;
                  var facility_address = response.facility_address;
                  var receipt_file = response.receipt_file;
                  var facility_name = '<?php echo $health_facility_name; ?>';
                  var date = response.date;
                  var total_price = response.total_price;
                  sum = response.amount;
                  var balance = response.balance;
                  var initiation_code = "<?php echo $third_addition; ?>";
                  
                  
                  console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
                  $(".spinner-overlay").hide();
                  var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/get_tests_patient/'.$third_addition) ?>";
                  $(".spinner-overlay").show();
                  $.ajax({
                    url : get_pdf_tests_url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_pdf_tests=true&lab_id="+lab_id,
                    success : function (response) {
                      $(".spinner-overlay").hide();
                      var pdf_data =  {
                        'logo' : company_logo,
                        'color' : <?php echo $color; ?>,
                        'tests' : response,
                        'sum' : String(addCommas(sum)),
                        'facility_name' : facility_name,
                        'facility_id' : "<?php echo $health_facility_id; ?>",
                        'initiation_code' : initiation_code,
                        'lab_id' : lab_id,
                        'mod' : 'online payment',
                        'facility_address' : "<?php echo $health_facility_address; ?>",
                        'patient_name' : patient_name,
                        'receipt_number' : receipt_number,
                        'facility_state' : facility_state,
                        'facility_country' : facility_country,
                        'date' : date,
                        'receipt_file' : receipt_file,
                        'is_teller' : 1,
                        'total_price' : String(addCommas(total_price)),
                        'balance' : String(addCommas(balance))
                      };
                      console.log(JSON.stringify(pdf_data));
                      $(".spinner-overlay").show();
                      var url = "<?php echo site_url('onehealth/index/'.$addition.'/save_receipt/'.$third_addition); ?>";
                      // var pdf = btoa(doc.output());
                      $.ajax({
                        url : url,
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : pdf_data,
                        
                        success : function (response) {
                          $(".spinner-overlay").hide();
                          console.log(response)
                          if(response.success == true){
                            var pdf_url = "<?php echo base_url('assets/images/')?>" + "<?php echo $health_facility_slug; ?>" + "_" +pdf_data.receipt_number + "_" +lab_id + ".html";;
                            window.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/access-laboratory-services'); ?>" +"?receipt_file="+ pdf_url);
                          }else{
                            console.log('false')
                          }
                        },
                        error : function () {
                          
                        }
                      })
                    },
                    error : function () {
                      
                    }
                  });  
                  
                }else if(response.excess){
                  swal({
                    title: 'Error',
                    text: "Amount Entered Here Cannot Be Greater Than "+addCommas(max),
                    type: 'warning',
                    
                  })
                }
              },
              error: function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              }
            
          })
           
    
        });
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 