
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
        var url = elem.getAttribute("action");
        $(".spinner-overlay").show();
          $.ajax({
            url : url,
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
                $("#submit-logo").before('<img src="' + new_image_name + '" alt="..." class="user-logo-img" width="200" height="200" id="logo">');
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
         
          <!-- <h2 class="text-secondary" style="text-transform: capitalize;"><?php echo $user_name; ?>'s Profile.</h2> -->
          

          <div class="row">
            <div class="col-sm-9">
              <div class="card">
                <div class="card-header card-header-icon card-header-blue">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Edit Your Profile</h4>

                </div>
                <div class="card-body">
                 
                  
                    <div class="row">
                      <div class="col-sm-6 text-center">
                        <div class="avatar">
                        <?php
                            if(is_null($logo)){
                              $logo_url = base_url('assets/images/avatar.jpg');
                             $logo = "<img width='200' height='200' id='logo' class='img-raised img-fluid logo-link-cont' src='".$logo_url."'  rel='tooltip' data-original-title='Change Your Profile Picture' data-toggle='modal' data-target='#change-logo-modal' data-backdrop='false' class='user-logo-img'>";
                              echo $logo;
                            }else{
                            ?>
                              <img src="<?php if(is_null($logo)){ echo base_url('assets/images/avatar.jpg'); } else{ echo base_url('assets/images/'.$logo); } ?>" alt="..." class="user-logo-img" width="200" height="200" id="logo">
                            <?php
                            }
                          ?>  
                         
                        </div>
                        <button id="submit-logo" data-target="#change-logo-modal" data-toggle="modal" style="margin-top: 30px;" class="btn btn-round btn-info" onclick="return buttonClicked(event,this)">Change Logo</button>
                      </div>
                      <div class="col-sm-6">
                        <?php $attr = array('id' => 'edit-user-form') ?>
                        <?php echo form_open('onehealth/index/'.$slug.'/submitUserForm',$attr); ?>
                        <div class="form-row">
                        <div class="form-group col-sm-12">
                          <label for="email" class="text-primary">Edit Email: </label>
                          <input type="email" class="form-control" id="email" name="email" value="<?php if(is_null($email)){ echo ""; } else{ echo $email; }  ?>" required>
                          <span class="form-error"></span>
                        </div>


                        <div class=" form-group">
                          <label for="country_code">Select Country Short Code:</label>
                          <select id="country_code" name="country_code" class="form-control selectpicker">
                            <option class="firstoption" value="">Select Country</option> <option value="1">USA, US territories, Canada, and some Caribbean countries (+1)</option><option value="93">Afghanistan (+93)</option><option value="355">Albania (+355)</option><option value="213">Algeria (+213)</option><option value="376">Andorra (+376)</option><option value="244">Angola (+244)</option><option value="54">Argentina (+54)</option><option value="374">Armenia (+374)</option><option value="297">Aruba (+297)</option><option value="61">Australia, Christmas Island (+61)</option><option value="43">Austria (+43)</option><option value="994">Azerbaijan (+994)</option><option value="973">Bahrain (+973)</option><option value="880">Bangladesh (+880)</option><option value="375">Belarus (+375)</option><option value="32">Belgium (+32)</option><option value="501">Belize (+501)</option><option value="229">Benin (+229)</option><option value="975">Bhutan (+975)</option><option value="591">Bolivia (+591)</option><option value="599">Bonaire, Saint Eustatius and Saba, Curacao, Netherlands Antilles (+599)</option><option value="387">Bosnia and Herzegovina (+387)</option><option value="267">Botswana (+267)</option><option value="55">Brazil (+55)</option><option value="673">Brunei Darussalam (+673)</option><option value="359">Bulgaria (+359)</option><option value="226">Burkina Faso (+226)</option><option value="257">Burundi (+257)</option><option value="855">Cambodia (+855)</option><option value="237">Cameroon (+237)</option><option value="238">Cape Verde (+238)</option><option value="236">Central African Republic (+236)</option><option value="235">Chad (+235)</option><option value="56">Chile (+56)</option><option value="86">China (+86)</option><option value="57">Colombia (+57)</option><option value="269">Comoros (+269)</option><option value="242">Congo (+242)</option><option value="682">Cook Islands (+682)</option><option value="506">Costa Rica (+506)</option><option value="225">Cote D&#039;Ivoire (+225)</option><option value="385">Croatia (+385)</option><option value="53">Cuba (+53)</option><option value="357">Cyprus (+357)</option><option value="420">Czech Republic (+420)</option><option value="243">Democratic Republic of the Congo (+243)</option><option value="45">Denmark (+45)</option><option value="253">Djibouti (+253)</option><option value="593">Ecuador (+593)</option><option value="20">Egypt (+20)</option><option value="503">El Salvador (+503)</option><option value="240">Equatorial Guinea (+240)</option><option value="291">Eritrea (+291)</option><option value="372">Estonia (+372)</option><option value="251">Ethiopia (+251)</option><option value="500">Falkland Islands, South Georgia and the South Sandwich Islands (+500)</option><option value="298">Faroe Islands (+298)</option><option value="679">Fiji (+679)</option><option value="358">Finland, Aaland Islands (+358)</option><option value="33">France (+33)</option><option value="594">French Guiana (+594)</option><option value="689">French Polynesia (+689)</option><option value="241">Gabon (+241)</option><option value="220">Gambia (+220)</option><option value="995">Georgia (+995)</option><option value="49">Germany (+49)</option><option value="233">Ghana (+233)</option><option value="350">Gibraltar (+350)</option><option value="30">Greece (+30)</option><option value="299">Greenland (+299)</option><option value="590">Guadeloupe, Saint Martin (+590)</option><option value="502">Guatemala (+502)</option><option value="224">Guinea (+224)</option><option value="592">Guyana (+592)</option><option value="509">Haiti (+509)</option><option value="504">Honduras (+504)</option><option value="852">Hong Kong (+852)</option><option value="36">Hungary (+36)</option><option value="354">Iceland (+354)</option><option value="91">India (+91)</option><option value="62">Indonesia (+62)</option><option value="98">Iran (+98)</option><option value="964">Iraq (+964)</option><option value="353">Ireland (+353)</option><option value="972">Israel (+972)</option><option value="39">Italy (+39)</option><option value="81">Japan (+81)</option><option value="962">Jordan (+962)</option><option value="254">Kenya (+254)</option><option value="686">Kiribati (+686)</option><option value="965">Kuwait (+965)</option><option value="996">Kyrgyzstan (+996)</option><option value="856">Lao People&#039;s Democratic Republic (+856)</option><option value="371">Latvia (+371)</option><option value="961">Lebanon (+961)</option><option value="266">Lesotho (+266)</option><option value="231">Liberia (+231)</option><option value="218">Libya (+218)</option><option value="423">Liechtenstein (+423)</option><option value="370">Lithuania (+370)</option><option value="352">Luxembourg (+352)</option><option value="853">Macau (+853)</option><option value="389">Macedonia (+389)</option><option value="261">Madagascar (+261)</option><option value="265">Malawi (+265)</option><option value="60">Malaysia (+60)</option><option value="960">Maldives (+960)</option><option value="223">Mali (+223)</option><option value="356">Malta (+356)</option><option value="692">Marshall Islands (+692)</option><option value="596">Martinique (+596)</option><option value="222">Mauritania (+222)</option><option value="230">Mauritius (+230)</option><option value="262">Mayotte, Reunion (+262)</option><option value="52">Mexico (+52)</option><option value="373">Moldova, Republic of (+373)</option><option value="377">Monaco (+377)</option><option value="976">Mongolia (+976)</option><option value="382">Montenegro (+382)</option><option value="212">Morocco, Western Sahara (+212)</option><option value="258">Mozambique (+258)</option><option value="95">Myanmar (+95)</option><option value="264">Namibia (+264)</option><option value="977">Nepal (+977)</option><option value="31">Netherlands (+31)</option><option value="687">New Caledonia (+687)</option><option value="64">New Zealand (+64)</option><option value="505">Nicaragua (+505)</option><option value="227">Niger (+227)</option><option value="234">Nigeria (+234)</option><option value="683">Niue (+683)</option><option value="672">Norfolk Island,  (+672)</option><option value="47">Norway, Bouvet Island, Svalbard and Jan Mayen Islands (+47)</option><option value="968">Oman (+968)</option><option value="92">Pakistan (+92)</option><option value="680">Palau (+680)</option><option value="970">Palestine (+970)</option><option value="507">Panama (+507)</option><option value="675">Papua New Guinea (+675)</option><option value="595">Paraguay,  (+595)</option><option value="51">Peru (+51)</option><option value="63">Philippines (+63)</option><option value="872">Pitcairn (+872)</option><option value="48">Poland (+48)</option><option value="351">Portugal (+351)</option><option value="974">Qatar (+974)</option><option value="383">Republic of Kosovo (+383)</option><option value="40">Romania (+40)</option><option value="7">Russia, Kazakhstan (+7)</option><option value="250">Rwanda (+250)</option><option value="685">Samoa (Independent) (+685)</option><option value="378">San Marino (+378)</option><option value="239">Sao Tome and Principe (+239)</option><option value="966">Saudi Arabia (+966)</option><option value="221">Senegal (+221)</option><option value="381">Serbia (+381)</option><option value="248">Seychelles (+248)</option><option value="232">Sierra Leone (+232)</option><option value="65">Singapore (+65)</option><option value="721">Sint Maarten (+721)</option><option value="421">Slovakia (+421)</option><option value="386">Slovenia (+386)</option><option value="677">Solomon Islands (+677)</option><option value="252">Somalia (+252)</option><option value="27">South Africa (+27)</option><option value="82">South Korea (+82)</option><option value="850">South Korea,  (+850)</option><option value="211">South Sudan (+211)</option><option value="34">Spain (+34)</option><option value="94">Sri Lanka (+94)</option><option value="249">Sudan (+249)</option><option value="597">Suriname (+597)</option><option value="268">Swaziland (+268)</option><option value="46">Sweden (+46)</option><option value="41">Switzerland (+41)</option><option value="963">Syria (+963)</option><option value="886">Taiwan (+886)</option><option value="992">Tajikistan (+992)</option><option value="255">Tanzania (+255)</option><option value="66">Thailand (+66)</option><option value="670">Timor-Leste (+670)</option><option value="228">Togo (+228)</option><option value="676">Tonga (+676)</option><option value="216">Tunisia (+216)</option><option value="90">Turkey (+90)</option><option value="993">Turkmenistan (+993)</option><option value="256">Uganda (+256)</option><option value="380">Ukraine (+380)</option><option value="971">United Arab Emirates (+971)</option><option value="44">United Kingdom, Guernsey, Isle of Man, Jersey  (Channel Islands) (+44)</option><option value="598">Uruguay (+598)</option><option value="998">Uzbekistan (+998)</option><option value="678">Vanuatu (+678)</option><option value="379">Vatican City State (Holy See) (+379)</option><option value="58">Venezuela (+58)</option><option value="84">Vietnam (+84)</option><option value="967">Yemen (+967)</option><option value="260">Zambia (+260)</option><option value="263">Zimbabwe (+263)</option>
                          </select> 
                          <span class="form-error"></span> 
                        </div>
    
                        <div class="form-group col-sm-12">                        
                          <label for="mobile">Edit Mobile Number: </label>
                          <input type="number" class="form-control" placeholder="e.g 08127027321" name="mobile" id="mobile" value="<?php echo $phone; ?>" required>
                          <span class="form-error"></span> 
                        </div>


                        
                       
                        <div class="form-group col-sm-12">
                          <label for="address" class="text-primary">Edit Address: </label>
                          <textarea required class="form-control" name="address" id="address" rows="3">
                            <?php 
                             echo $address;
                            ?>
                          </textarea>
                          <span class="form-error"></span>
                        </div>
  

                        <div class="form-group col-sm-12">
                          <label for="bio" class="text-primary">Edit Bio: </label>
                          <textarea required class="form-control" name="bio" id="bio" rows="3">
                            <?php 
                              if($form_error == false){ 
                                echo trim($bio);
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
            <?php $attr = array('id' => 'change_user_form','onsubmit' => 'return submitImage(this,event)'); ?>
              <?php echo form_open_multipart("onehealth/index/".$addition."/change_user_logo",$attr); ?>
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
                  <!-- <span class="form-error">1. Max Image Size: 100 kb.</span><br>
                  <span class="form-error">2. Max Dimensions: 300 X 300</span><br>
                  <span class="form-error">3. Allowed Types: GIF,PNG,JPG,JPEG</span> -->
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
    
    var country_code = <?php if(is_null($phone_code)){ echo ""; } else{ echo ltrim($phone_code,"+"); } ?>


    $("#country_code").val(country_code);
    $("#edit-user-form").submit(function (evt) {
      evt.preventDefault();
      var form_data = $(this).serializeArray();
      var url = $(this).attr("action");
      
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
          if(response.success == true && response.successful == true){
            
            $.notify({
            message:"Your Profile Has Been Edited Successfully.",
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
        "name" : "logo"
      })
      var old_val = $("#address").val().trim();
      $("#address").val(old_val)
      
      <?php if($this->session->form_submitted){ ?>
         $.notify({
          message:"Your Details Have Been Changed Successfully"
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
   
 </script>