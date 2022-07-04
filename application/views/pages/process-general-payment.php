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
        <p id="processing">Processing.... Please Do Not Leave Or Reload This Page</p>
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
          <?php if($success){ ?>
            <?php 
            if($second_addition == "outstanding_bills_payment"){
            ?>
            
            // $(".spinner-overlay").show();
            $.ajax({
              url : submit_tests_url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              async : false,
              data : "",
              success : function (response) {
                // 
                var main_response = response;
                console.log(response.length)
                if(response.length > 0){
                  for(var i = 0; i < response.length; i++){
                    var lab_id = response[i].lab_id;
                    var initiation_code = response[i].initiation_code;
                    var patient_name = response[i].patient_name;
                    var receipt_number = response[i].receipt_number;
                    var facility_state = response[i].facility_state;
                    var facility_country = response[i].facility_country;
                    var facility_name = '<?php echo $health_facility_name; ?>';
                    var facility_address = "<?php echo $health_facility_address ?>";
                    var date = response[i].date;
                    var sum = response[i].sum;
                    sum = String(addCommas(sum));
                    console.log(lab_id + ' ' + initiation_code + ' ' + patient_name + ' ' + receipt_number + ' ' + facility_state + ' ' + facility_country);
                    
                    var get_pdf_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/get_pdf_tests') ?>";
                        
                    $.ajax({
                      url : get_pdf_tests_url,
                      type : "POST",
                      responseType : "json",
                      dataType : "json",
                      async : false,
                      data : "get_pdf_tests=true&lab_id="+lab_id,
                      success : function (response) {
                        // $(".spinner-overlay").hide();
                         
                        var pdf_data =  {
                            'logo' : company_logo,
                            'color' : <?php echo $color; ?>,
                            'tests' : response,
                            'sum' : sum,
                            'facility_name' : facility_name,
                            'initiation_code' : initiation_code,
                            'lab_id' : lab_id,
                            'mod' : 'online payment',
                            'facility_address' : "<?php echo $health_facility_address; ?>",
                            'facility_id' : "<?php echo $health_facility_id; ?>",
                            'patient_name' : patient_name,
                            'receipt_number' : receipt_number,
                            'facility_state' : facility_state,
                            'facility_country' : facility_country,
                            'date' : date
                          };
                          console.log(pdf_data);
                    
                        var url = "<?php echo site_url('onehealth/index/'.$addition.'/save_receipt') ?>";
                        // var pdf = btoa(doc.output());
                        $.ajax({
                          url : url,
                          type : "POST",
                          responseType : "json",
                          dataType : "json",
                          async : false,
                          data : pdf_data,
                          success : function (response) {
                            if(response.success){
                              var url = "<?php echo site_url('onehealth/index/'.$addition.'/notify_clerical') ?>";
                              // var pdf = btoa(doc.output());
                              $.ajax({
                                url : url,
                                type : "POST",
                                responseType : "text",
                                dataType : "text",
                                async : false,
                                data : "lab_id="+lab_id,
                                success : function (response) {
                                    // var pdf_url = "<?php echo base_url('assets/images/') ?>" + lab_id + '_receipt.pdf';
                                    if(i == (main_response.length - 1)){
                                      console.log('last')
                                      <?php
                                      if($third_addition == "clinic"){
                                        ?>
                                        
                                        window.location.assign("<?php echo site_url('onehealth/cl_admin'); ?>");
                                       <?php   
                                      }else{
                                        ?>

                                        window.location.assign("<?php echo site_url('onehealth/index/'.$addition.'/access-laboratory-services'); ?>");
                                        <?php
                                      }
                                    ?> 
                                    }                               
                                },
                                error :function(){

                                }
                              }); 
                            } 
                          },
                          error : function () {
                            
                          }
                        })
                      },
                      error : function () {
                        
                      }
                    });  
                    
                  }
                }else{
                  window.location.assign("<?php echo site_url('onehealth/cl_admin'); ?>");
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
            <?php } ?>
          <?php }else{
            if($unverified){
          ?>
            $("#spinner-overlay").hide();
            $("#processing").hide();
            swal({
              type: 'error',
              title: 'Oops.....',
              text: 'Your Payment Could Not Be Verified'
              // footer: '<a href>Why do I have this issue?</a>'
            })
          <?php
            }
          ?>
        });
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 