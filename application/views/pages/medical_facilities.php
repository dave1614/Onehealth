  <script>
    function registerPatient (elem) {
      var facility_table_name = String(elem.getAttribute("data-hospital-name1"));
      var hospital_name = String(elem.getAttribute("data-hospital-name"));
      var hospital_id = elem.getAttribute("data-hosid");
      var url = "<?php echo site_url('onehealth/register_patient'); ?>";
      var data = "table_name="+facility_table_name+"&facility_name="+hospital_name;
       var str = "#"+hospital_id+"-count";
      $.ajax({
        url : url,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : data,
        success : function(response){
          if(response == "values_messed"){
            $.notify({
            message:"Sorry Something Went Wrong"
            },{
              type : "warning"  
            });
          }else if(response == "could not register patient"){
           $.notify({
            message:"Sorry You Could Not Be Registered"
            },{
              type : "warning"  
            });
          }else if(response == "already_registered"){
           $.notify({
            message:"Sorry You've Already Registered On This Facility"
            },{
              type : "warning"  
            });
          }else if(response == "successful"){
            
           elem.classList.remove("btn-success");
           elem.classList.add("btn-primary");
           elem.innerHTML = "Registered";
           elem.setAttribute("onclick","unRegisterPatient(this)");
           var old_num =  document.getElementById(str).innerHTML;
           var new_num = Number(old_num) + 1;
           document.getElementById(str).innerHTML = new_num;
          }
        },
        error: function () {
           $.notify({
            message:"Sorry You Could Not Be Registered"
            },{
              type : "danger"  
            });
        }
      })
    }

     function unRegisterPatient (elem) {
      var facility_table_name = String(elem.getAttribute("data-hospital-name1"));
      var hospital_name = String(elem.getAttribute("data-hospital-name"));
      var hospital_id = elem.getAttribute("data-hosid");
      var url = "<?php echo site_url('onehealth/unregister_patient'); ?>";
      var data = "table_name="+facility_table_name+"&facility_name="+hospital_name;
       var str = "#"+hospital_id+"-count";
      $.ajax({
        url : url,
        type : "POST",
        responseType : "text",
        dataType : "text",
        data : data,
        success : function(response){
          if(response == "values_messed"){
            $.notify({
            message:"Sorry Something Went Wrong"
            },{
              type : "warning"  
            });
          }else if(response == "could not register patient"){
           $.notify({
            message:"Sorry You Could Not Be Registered"
            },{
              type : "warning"  
            });
          }else if(response == "already_registered"){
           $.notify({
            message:"Sorry You've Not Registered On This Facility"
            },{
              type : "warning"  
            });
          }else if(response == "successful"){
            
           elem.classList.remove("btn-primary");
           elem.classList.add("btn-success");
           elem.innerHTML = "Register";
           elem.setAttribute("onclick","registerPatient(this)");
           var old_num =  document.getElementById(str).innerHTML;
           var new_num = Number(old_num) - 1;
           document.getElementById(str).innerHTML = new_num;
          }
        },
        error: function () {
           $.notify({
            message:"Sorry You Could Not Be Registered"
            },{
              type : "danger"  
            });
        }
      })
    }
  </script>

      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          
          <div class="row">
            <div class="col-sm-10">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Health Facilities</h3>
                </div>
                <div class="card-body">
                 <div class="row">
                  <div class="scroller_anchor"></div>
                  
                   <div class="col-lg-4 col-md-6">
                    <div class="sticky-top">
                     <ul class="nav nav-pills nav-pills-primary nav-pills-icons flex-column" role="tablist">
                       <li class="nav-item">
                         <a href="#hospital" data-toggle="tab" class="nav-link active show">
                           <i class="material-icons">home</i>
                           Hospitals
                         </a>
                       </li>

                       <li class="nav-item">
                         <a href="#laboratory" data-toggle="tab" class="nav-link">
                           <i class="fas fa-vials"></i>
                           Laboratories
                         </a>
                       </li>

                        <li class="nav-item">
                         <a href="#maternity-home" data-toggle="tab" class="nav-link">
                           <i class="fas fa-child"></i>
                           Maternity Home
                         </a>
                       </li>

                        <li class="nav-item">
                         <a href="#clinic" data-toggle="tab" class="nav-link">
                           <i class="fas fa-hospital-alt" style="font-size: 20px;"></i>
                           Clinics
                         </a>
                       </li>
                     </ul>
                   </div>
                   </div>
                   

                   <div class="col-md-8">
                    <div class="hospitals-col">
                     <div class="tab-content">
                       <div class="tab-pane active" id="hospital">
                          <h4 class="tab-pane-title">All Hospitals</h4>
                          <form class="navbar-form">
                            <span class="bmd-form-group"><div class="input-group no-border">
                              <input type="text" value="" class="form-control" placeholder="Search...">
                              <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">search</i>
                                <div class="ripple-container"></div>
                              </button>
                            </div></span>
                          </form>
                          <?php   
                              $first_ten_hospitals = $this->onehealth_model->getFirstTenHospitals();
                              if(is_array($first_ten_hospitals)){
                            ?>
                          <table>
                            <tbody>
                              <tr class="row">
                                <?php foreach($first_ten_hospitals as $row){ 
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
                                  if($this->onehealth_model->checkIfUserIsRegisteredOnThisFacility($hospital_table_name,$user_name,$hospital_name,$user_id)){
                                    $registered = true;
                                  }else{
                                    $registered = false;
                                  }
                                ?>
                             <td class="col-sm-6"><a href="<?php echo site_url('onehealth/index/'.$hospital_id.'/'.$hospital_slug) ?>" id="edit-test-card-link" class="text-primary list-group-item list-group-item-action"><?php echo $hospital_name; ?></a></td>
                             <td class="col-sm-3"><p class="text-secondary text-center" id="#<?php echo $hospital_id; ?>-count"><?php echo $this->onehealth_model->getNumberOfRegisteredFacilityPatients($hospital_table_name,$hospital_name); ?></p><p>Registered</p></td>
                            <?php if($registered){  ?>
                              <td class="col-sm-3"><button class="btn btn-primary btn-round" data-hospital-name1="<?php echo $hospital_table_name; ?>" data-hospital-name="<?php echo $hospital_name ?>" data-hosid="<?php echo $hospital_id;?>" onclick="return unRegisterPatient(this)">Registered</button></td>
                              <?php }else{ ?>  
                              <td class="col-sm-3"><button class="btn btn-success btn-round" data-hospital-name1="<?php echo $hospital_table_name; ?>" data-hospital-name="<?php echo $hospital_name ?>" data-hosid="<?php echo $hospital_id;?>" onclick="return registerPatient(this)">Register</button></td>  
                              <?php } ?>  
                            <?php }  ?>
                              </tr>  
                            </tbody>
                          </table>
                        <?php } ?>
                       </div>

                        <div class="tab-pane" id="laboratory">
                          <h4 class="tab-pane-title">All Laboratories</h4>
                          <form class="navbar-form">
                            <span class="bmd-form-group"><div class="input-group no-border">
                              <input type="text" value="" class="form-control" placeholder="Search...">
                              <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">search</i>
                                <div class="ripple-container"></div>
                              </button>
                            </div></span>
                          </form>
                          <div class="list-group">
                            <ol>
                             <li><a href="#" id="edit-test-card-link" class="text-primary list-group-item list-group-item-action">Edit Tests</a></li>
                              <li><a href="#" class="text-primary list-group-item list-group-item-action">Second item</a></li>
                              <li><a href="#" class="text-primary list-group-item list-group-item-action">Third item</a></li>
                            </ol>
                          </div>
                       </div>
                     </div>
                   </div>
                   </div>
                 </div>
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
  </div>
  <!--   Core JS Files   -->
 <script>
   $(document).ready(function () {
   
   })
 </script>