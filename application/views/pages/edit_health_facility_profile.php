
<style>
  .facility-logo-img{
   
  }
</style>

<script>
  function submitImage (elem,evt) {
    evt.preventDefault()
    var file_input = elem.querySelector("input");
    
    var file_name = file_input.getAttribute("name");
    console.log(file_name)
    var file = file_input.files;
    console.log(file)
    var form_data = new FormData();
    var error = '';
    if(file_input.value !== ""){
      if(file.length == 1){
        var name = file[0].name;
        console.log(name)
        var extension = name.split('.').pop().toLowerCase();
        
        if(jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1){
          error += "Invalid Image File Selected, Please Reselect A Valid Image";
        }else{                 
          form_data.append(file_name,file[0]);
        }
      }else{
        $.notify({
        message:"Sorry You Can Only Select One Image"
        },{
          type : "danger"  
        });
        file_input.val("");
        return false;
      }

      if(error == ''){
        var change_facility_logo = elem.getAttribute("action");
        $(".spinner-overlay").show();
          $.ajax({
            url : change_facility_logo,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            contentType : false,
            cache : false,
            processData : false,
            success : function (response) {
            console.log(response)             
              $(".spinner-overlay").hide();
              if(response.empty == true){
                 $.notify({
                message:"No Image Was Uploaded"
                },{
                  type : "danger"  
                });
                 file_input.val("");
              }
              else if(response.success == true && response.new_image_name !== ""){
                var new_image_name = response.new_image_name;
                
                $("#logo").remove();
                $("#submit-logo").before('<img src="' + new_image_name + '" alt="..." class="facility-logo-img" width="200" height="200" id="logo">');
                $("#change-logo-modal").modal('hide');
                $.notify({
                message:"Logo Changed Successfully"
                },{
                  type : "success"  
                });        
              }else if (response.success == false && response.errors !== "") {
                $.notify({
                message:"Logo Upload Was Unsuccessful"
                },{
                  type : "danger"  
                });
                swal({
                  title: 'Error',
                  text: response.errors,
                  type: 'error',
                  
                })
                file_input.val("");
              }
              
            },
            error : function () {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Something Went Wrong When Trying To Upload Your Images"
                },{
                  type : "danger"  
                });
               file_input.val("");
            } 
          });
      }else{
        swal({
            title: 'Error!',
            text: error,
            type: 'error'         
          })
        file_input.val("");
      }

    }else{
       $.notify({
        message:"Sorry You Must Select One Image"
        },{
          type : "danger"  
        });
       file_input.val("");
      return false;
    }           
  }

  function buttonClicked (evt,elem) {
    evt.preventDefault();
  }
</script>
    
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="spinner-overlay" style="display: none;">
            <div class="spinner-well">
              <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading..." style="">
            </div>
          </div>
          <?php
            if(is_array($curr_health_facility_arr)){
              foreach($curr_health_facility_arr as $row){
                $health_facility_id = $row->id;
                $health_facility_name = $row->name;
                $health_facility_logo = $row->logo;
                $health_facility_structure = $row->facility_structure;
                $health_facility_email = $row->email;
                $health_facility_phone_code = $row->phone_code;
                $health_facility_phone = $row->phone;
                $phone_code = $row->phone_code;
                $health_facility_country = $row->country;
                $health_facility_state = $row->state;
                $health_facility_address = $row->address;
                $health_facility_table_name = $row->table_name;
                $health_facility_date = $row->date;
                $health_facility_time = $row->time;
                $health_facility_slug = $row->slug;
                $health_facility_bank_name = $row->bank_name;
                $account_number = $row->account_number;
                $color = $row->color;
                $health_facility_bio = $row->bio;
                
              }
            }
          ?>
          <h2 class="text-secondary" style="text-transform: capitalize;"><?php echo $health_facility_name; ?>'s Profile.</h2>
          <h4>Welcome <?php echo $user_name; ?></h4>
          <a href="<?php echo site_url('onehealth/index/' . $health_facility_slug . '/admin') ?>" class="btn btn-warning"> < < Go Back</a>
          <div class="row">
            <div class="col-sm-9">
              <div class="card">
                <div class="card-header card-header-icon card-header-blue">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Edit Facility Profile</h4>

                </div>
                <div class="card-body">
                 
                  
                    <div class="row">
                      <div class="col-sm-6 text-center">
                        <div class="avatar">
                        <?php
                            if(is_null($health_facility_logo)){
                             $health_facility_logo = "<img width='200' height='200' id='logo' class='img-raised img-fluid logo-link-cont' avatar='".$health_facility_name."' col='".$color."' rel='tooltip' data-original-title='Change Your Facility Logo' data-toggle='modal' data-target='#change-logo-modal' data-backdrop='false' class='facility-logo-img'>";
                              echo $health_facility_logo;
                            }else{
                            ?>
                              <img src="<?php if(is_null($health_facility_logo)){ echo base_url('assets/images/avatar.jpg'); } else{ echo base_url('assets/images/'.$health_facility_logo); } ?>" alt="..." class="facility-logo-img" width="200" height="200" id="logo">
                            <?php
                            }
                          ?>  
                        
                        </div>
                        <button id="submit-logo" data-target="#change-logo-modal" data-toggle="modal" style="margin-top: 30px;" class="btn btn-round btn-info" onclick="return buttonClicked(event,this)">Change Logo</button> 
                      </div>
                      <div class="col-sm-6">
                        <?php $attr = array('id' => 'edit-facility-form') ?>
                        <?php echo form_open('onehealth/index/'.$health_facility_slug.'/submitFacilityForm',$attr); ?>
                        <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="facility-email" class="text-primary">Edit Facility Email: </label>
                          <input type="email" class="form-control" id="facility-email" name="facility_email" value="<?php if($form_error == false){ if(is_null($health_facility_email)){ echo ""; } else{ echo $health_facility_email; } } else{ echo set_value('facility_email'); } ?>" required>
                          <span class="form-error"></span>
                        </div>

                        
    
                        <div class="form-group col-sm-12">                        
                          <label for="mobile">Edit Mobile Number: </label>
                          <input type="number" class="form-control" name="mobile" id="mobile" value="<?php echo $health_facility_phone; ?>" required>
                          <span class="form-error"></span> 
                        </div>


                        
                        <!-- <div class="form-group">
                          <label for="facility-country" class="text-primary">Facility Country: </label>
                          <select name="facility_country" id="facility-country" class="form-control selectpicker" data-style="btn btn-link" required onchange="changeStateAndCity()">
                            <?php
                              $all_countries = $this->onehealth_model->getCountries();
                              if(is_array($all_countries)){ 
                                foreach($all_countries as $country){
                                  $country_name = $country->name;
                                  $country_code = $country->code;
                                  $country_id = $country->id;
                            ?>
                            <option style="text-transform: capitalize;" value="<?php echo $country_id ?>" <?php if($form_error == false){ if($country_id == $health_facility_country){ echo "selected"; } } else { if (set_value('facility_country') == $country_id){ echo "selected"; } } ?>><?php echo $country_name; ?> (<?php echo $country_code; ?>)</option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                        </div>  --> 
                        
                        <!-- <div class="form-group" id="facility-state-form-group">
                          <label for="facility-state" style="margin-bottom: 20px;" class="text-primary">Facility State:</label>
                          <select name="facility_state" id="facility-state" class="form-control selectpicker" data-style="btn btn-link" required>
                            <?php
                              $first_regions = $this->onehealth_model->getRegionsByCountryId($health_facility_country);
                              
                              if(is_array($first_regions)){ 
                                foreach($first_regions as $region){
                                  $region_name = $region->name;
                                  $country_id = $region->country_id;
                                  $region_id = $region->id;
                            ?>
                            <option style="text-transform: capitalize;" value="<?php echo $region_id ?>" <?php if($region_id == $health_facility_state){ echo "selected"; } ?>><?php echo $region_name; ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                          <span class="form-error"><?php echo form_error('facility_state'); ?></span>
                        </div> -->

                        <div class="form-group col-sm-12">
                          <label for="facility-address" class="text-primary">Edit Facility Address: </label>
                          <textarea required class="form-control" name="facility_address" id="facility-address" rows="3">
                            <?php 
                              echo $health_facility_address; 
                            ?>
                          </textarea>
                          <span class="form-error"></span>
                        </div>
  
                        <div class="form-group col-sm-12">
                          <label for="bank_name">Select Bank Name: </label>
                          <select name="bank_name" id="bank_name" class="form-control selectpicker" data-style="btn btn-link" required>
                            <?php
                              $banks_arr = $this->paystack->curl("https://api.paystack.co/bank",FALSE);
                              $banks_arr = json_decode($banks_arr);
                              // var_dump($banks_arr);
                              // print_r($banks_arr);
                              // if(is_array($banks_arr)){
                                if($banks_arr->status && $banks_arr->message == "Banks retrieved"){
                                  $bank_names = $banks_arr->data;
                                  foreach($bank_names as $row){
                                    $bank_name = $row->name;
                                    $code = $row->code;
                                    $long_code = $row->longcode;
                                    $active = $row->active;
                                    $is_deleted = $row->is_deleted;
                                    $id = $row->id;
                            ?>
                            <option value="<?php echo $code; ?>" <?php if($code == $health_facility_bank_name){ echo "selected"; } ?>><?php echo $bank_name; ?></option>
                            <?php } }  ?>
                          </select>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                          <label for="account_number">Enter Account Number: </label>
                          <input type="number" class="form-control" name="account_number" id="account_number" value="<?php if($account_number !== 0){ echo $account_number; } ?>" required>
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                          <label for="facility-bio" class="text-primary">Edit Facility Bio / Description: </label>
                          <textarea required class="form-control" name="bio" id="facility-bio" rows="3">
                            <?php 
                              if($form_error == false){ 
                                echo trim($health_facility_bio);
                              }else{
                                echo set_value('bio');
                              } 
                            ?>
                          </textarea>
                          <span class="form-error"></span>
                        </div>
                        
                        <input type="submit" name="submit" class="btn btn-round btn-primary" style="cursor: pointer;">
                      </div>

                     </div>
                    </div>
                  <?php echo form_close(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="change-logo-modal" class="modal fade text-center" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <?php $attr = array('id' => 'change_facility_form','onsubmit' => 'return submitImage(this,event)'); ?>
              <?php echo form_open_multipart("onehealth/index/".$health_facility_slug."/change_facility_logo",$attr); ?>
            <div class="modal-header">
              <h3 class="modal-title">Change Logo</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              
                <input type="file" name="image" id="image" class="inputfile inputfile-1" accept="image/*"/>
                <label for="image"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                <div class="text-left">
                  <span class="form-error">1. Max Image Size: 100 kb.</span><br>
                  <span class="form-error">2. Max Dimensions: 300 X 300</span><br>
                  <span class="form-error">3. Allowed Types: GIF,PNG,JPG,JPEG</span>
                </div>

            </div>
            <div class="modal-footer">
              
              <button type="submit" class="btn btn-primary text-right">Upload</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
 <script>
   $(document).ready(function(){
    var old_val = $("#facility-bio").val().trim();
      $("#facility-bio").val(old_val)
    var country_code = <?php if(is_null($health_facility_phone_code)){ echo ""; } else{ echo ltrim($health_facility_phone_code,"+"); } ?>


    $("#country_code").val(country_code);
    $("#edit-facility-form").submit(function (evt) {
      evt.preventDefault();
      var form_data = $(this).serializeArray();
      var url = $(this).attr("action");
      var health_facility_id = <?php echo $health_facility_id; ?>;
      var form_data = form_data.concat({
        "name":"health_facility_id",
        "value" : health_facility_id
      });
      $(".form-error").html("");
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
          if(response.success == true && response.successful == true && response.account_name !== ""){
            
            $.notify({
            message:"Facility Profile Edited Successfully. Your Account Name Is "+response.account_name
            },{
              type : "success"  
            });
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
        error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "warning"  
          });
        }
      });
      // console.log(form_data);
    })

      $(".fileinput").fileinput({
        "name" : "health_facility_logo"
      })
      var old_val = $("#facility-address").val().trim();
      $("#facility-address").val(old_val)
      
      <?php if($this->session->form_submitted){ ?>
         $.notify({
          message:"Facility Details Changed Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>

      <?php if($this->session->form_error){ ?>
         $.notify({
          message:"Sorry, Something Went Wrong."
        },{
          type : "danger"  
        });
      <?php }  ?>

      <?php if($this->session->file_upload_error){ ?>
         $.notify({
          message:"Sorry, Your Logo Wasn't Uploaded. Reason: <?php echo $this->session->file_upload_error ?>"
        },{
          type : "danger"  
        });
      <?php }  ?>
       
    });
   function changeStateAndCity(evt){
      var country_id = $('#facility-country').val();
      $.when(
        $.post("<?php echo site_url('onehealth/index/get_regions_for_edit_profile/'.$health_facility_slug) ?>","change_country_id=true&country_id=" + country_id,function(){
          // var states = states;
          
        }),

          
      ).done(function (states,cities){
        console.log(states)
        $("#facility-state-form-group").html(states);
        
      });
    }
   
 </script>