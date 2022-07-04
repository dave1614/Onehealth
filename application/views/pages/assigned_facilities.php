<script>
  
</script>

      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <h2>Welcome <?php echo $user_name; ?></h2>
          <div class="row">
            <div class="col-sm-10">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Health Facilities You Are Affiliated With</h3>
                </div>
                <div class="card-body">
                  
                    <?php
                    $is_patient = $this->onehealth_model->getUserParamById("is_patient",$user_id);
                    if($is_patient == 0){
                    $user_roles = $this->onehealth_model->getUserRoles();
                    // var_dump($user_roles);
                    ?>
                    <ul class="nav nav-pills nav-pills-primary" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist" aria-expanded="true">
                              Admin Roles
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#link2" role="tablist" aria-expanded="false">
                              Sub Admin Roles
                          </a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#link3" role="tablist" aria-expanded="false">
                              Personnel Roles
                          </a>
                      </li>
                  </ul>
                  <div class="tab-content tab-space">
                      <div class="tab-pane active" id="link1" aria-expanded="true">
                        <?php
                        $admin_roles = $user_roles['admin_roles'];
                        if(is_array($admin_roles) && count($admin_roles) > 0){
                        ?>
                        <div class="table-responsive">
                          <table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Facility Name</th>
                                <th>Affiliation</th>
                                <th class="text-right">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              
                                $facility_id = $admin_roles['facility_id'];
                                $facility_slug = $admin_roles['facility_slug'];
                                $facility_name = $this->onehealth_model->getHealthFacilityParamById("name",$facility_id);
                              ?>
                              <tr>
                                <td>1.</td>
                                <td style="text-transform: capitalize;"><?php echo $facility_name; ?></td>
                                <td>Admin</td>
                                <td class="td-actions text-right">
                                  <a href="<?php echo site_url('onehealth/index/'.$facility_slug.'/'.'admin'); ?>" rel="tooltip" data-toggle="tooltip" id="" title="Enter The Appropriate Panel" class="btn btn-primary">
                                    <i class="fas fa-user-tie"></i>
                                  </a>
                                </td>
                              </tr>
                              
                            </tbody>
                            
                          </table>
                        </div>
                        <?php
                          }else{
                            echo "<h4 class='text-warning'>You Do Not Have Admin Roles In Any Facility.</h4>";
                          }
                        ?>
                      </div>
                      <div class="tab-pane" id="link2" aria-expanded="false">
                        <?php
                          $sub_admin_roles = $user_roles['sub_admin_roles'];
                          if(is_array($sub_admin_roles)){
                            $i = 0;
                        ?>
                        <div class="table-responsive">
                          <table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Facility Name</th>
                                <th>Dept. </th>
                                <th>Sub Dept. </th>
                                <th class="text-right">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                        <?php
                            foreach($sub_admin_roles as $row){
                              $i++;
                              $id = $row->id;
                              $facility_id = $row->health_facility_id;
                              $sub_dept_id = $row->personnel_id;
                              $user_id = $row->user_id;
                              $type = $row->type;
                              $date = $row->date;
                              $time = $row->time;

                              
                              $dept_id = $this->onehealth_model->getDeptIdBySubDeptId($sub_dept_id);

                              $dept_name = $this->onehealth_model->getDeptNameById($dept_id);
                              $facility_name = $this->onehealth_model->getHealthFacilityParamById("name",$facility_id);
                              $sub_dept_name = $this->onehealth_model->getSubDeptNameById($sub_dept_id);
                              $facility_slug = $this->onehealth_model->getHealthFacilityParamById("slug",$facility_id);
                              $dept_slug = $this->onehealth_model->getDeptSlugById($dept_id);
                              $sub_dept_slug = $this->onehealth_model->getSubDeptSlugById($sub_dept_id,$dept_id);
                        ?>
                            <tr>
                              <td><?php echo $i; ?>. </td>
                              <td style="text-transform: capitalize;"><?php echo $facility_name; ?></td>
                              <td style="text-transform: capitalize;"><?php echo $dept_name; ?></td>
                              <td style="text-transform: capitalize;"><?php echo $sub_dept_name; ?></td>
                              <td class="td-actions text-right">
                                <a href="<?php echo site_url('onehealth/index/'.$facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin'); ?>" rel="tooltip" data-toggle="tooltip" id="" title="Enter The Appropriate Panel" class="btn btn-primary">
                                  <i class="fas fa-user-tie"></i>
                                </a>
                              </td>
                            </tr>
                        <?php
                            }
                        ?>
                            </tbody>
                          </table>
                        </div>
                        <?php
                          }else{
                            echo "<h4 class='text-warning'>You Do Not Have Sub Admin Roles In Any Facility.</h4>";
                          }
                        ?>
                      </div>



                      <div class="tab-pane" id="link3" aria-expanded="false">
                        <?php
                          $personnel_roles = $user_roles['personnel_roles'];
                          if(is_array($personnel_roles)){
                            $i = 0;
                        ?>
                        <div class="table-responsive">
                          <table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Facility Name</th>
                                <th>Dept. </th>
                                <th>Sub Dept. </th>
                                <th>Personnel </th>
                                <th class="text-right">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                        <?php
                            foreach($personnel_roles as $row){
                              $i++;
                              $id = $row->id;
                              $facility_id = $row->health_facility_id;
                              $personnel_id = $row->personnel_id;
                              $user_id = $row->user_id;
                              $type = $row->type;
                              $date = $row->date;
                              $time = $row->time;

                              $facility_name = $this->onehealth_model->getHealthFacilityParamById("name",$facility_id);
                              $facility_slug = $this->onehealth_model->getHealthFacilityParamById("slug",$facility_id);

                              if($type == ""){
                                $personnel_name = $this->onehealth_model->getPersonnelParamById("name",$personnel_id);
                                $personnel_slug = $this->onehealth_model->getPersonnelParamById("slug",$personnel_id);
                                $sub_dept_id = $this->onehealth_model->getSubDeptIdByPersonnelId($personnel_id);
                                $dept_id = $this->onehealth_model->getDeptIdBySubDeptId($sub_dept_id);

                                $dept_name = $this->onehealth_model->getDeptNameById($dept_id);
                                
                                $sub_dept_name = $this->onehealth_model->getSubDeptNameById($sub_dept_id);
                                
                                $dept_slug = $this->onehealth_model->getDeptSlugById($dept_id);
                                $sub_dept_slug = $this->onehealth_model->getSubDeptSlugById($sub_dept_id,$dept_id);
                                $url = site_url('onehealth/index/'.$facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/'.$personnel_slug.'/admin');
                              }else{
                                $dept_slug = $this->onehealth_model->getDeptSlugById(1);
                                $dept_name = $this->onehealth_model->getDeptNameById(1);
                                $sub_dept_name = "";
                                $sub_dept_slug = "";

                                $lab_officer_slug = $this->onehealth_model->getFirstLabOfficersParamById("slug",$personnel_id);
                                $url = site_url('onehealth/index/'.$facility_slug.'/'.$dept_slug.'/'.$lab_officer_slug.'/lab_officer');
                                $personnel_slug = $lab_officer_slug;
                                $personnel_name = $this->onehealth_model->getFirstLabOfficersParamById("name",$personnel_id);
                              }
                        ?>
                            <tr>
                              <td><?php echo $i; ?>. </td>
                              <td style="text-transform: capitalize;"><?php echo $facility_name; ?></td>
                              <td style="text-transform: capitalize;"><?php echo $dept_name; ?></td>
                              <td style="text-transform: capitalize;"><?php echo $sub_dept_name; ?></td>
                              <td style="text-transform: capitalize;"><?php echo $personnel_name; ?></td>
                              <td class="td-actions text-right">
                                <a href="<?php echo $url; ?>" rel="tooltip" data-toggle="tooltip" id="" title="Enter The Appropriate Panel" class="btn btn-primary">
                                  <i class="fas fa-user-tie"></i>
                                </a>
                              </td>
                            </tr>
                        <?php
                            }
                        ?>
                            </tbody>
                          </table>
                        </div>
                        <?php
                          }else{
                            echo "<h4 class='text-warning'>You Do Not Have Sub Admin Roles In Any Facility.</h4>";
                          }
                        ?>
                      </div>
                  </div>

                  <?php }else{ ?>
                    <?php
                      $patient_roles = $this->onehealth_model->getFacilitiesThisPatientIsAffiliatedWith($user_id);
                      if(is_array($patient_roles)){
                        $j = 0;
                      ?>
                      <div class="table-responsive">
                        <table class="table table-test table-striped table-bordered nowrap hover display" id="example" cellspacing="0" width="100%" style="width:100%">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Facility Name</th>
                              <th>Registration Number</th>
                              <th>Registration Time</th>
                              <th class="text-right">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                          foreach ($patient_roles as $row) {
                            $j++;
                            $id = $row->id;
                            $health_facility_id = $row->health_facility_id;
                            $registration_num = $row->registration_num;
                            $date_created = $row->date_created;
                            $time_created = $row->time_created;

                            $health_facility_slug = $this->onehealth_model->getHealthFacilityParamById("slug",$health_facility_id);
                            $health_facility_name = $this->onehealth_model->getHealthFacilityParamById("name",$health_facility_id);
                          ?>
                          <tr>
                            <td><?php echo $j; ?></td>
                            <td><a href="<?php echo site_url('onehealth/'.$health_facility_slug) ?>"><?php echo $health_facility_name; ?></a></td>
                            <td><?php echo $registration_num; ?></td>
                            <td><?php echo $date_created . " " . $time_created; ?></td>
                              <td class="td-actions text-right">
                                <a href="<?php echo site_url('onehealth/'.$health_facility_slug) ?>" rel="tooltip" data-toggle="tooltip" id="" title="Enter The Appropriate Panel" class="btn btn-primary">
                                  <i class="fas fa-user-tie"></i>
                                </a>
                              </td>
                          </tr>
                          <?php
                          }
                          ?>
                          </tbody>
                        </table>
                      </div>
                  <?php }else{ ?>
                      <h4 class="text-warning">You Are Not Currently Registered In Any Facility</h4>
                  <?php } }?>
                  
                </div>
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
  </div>
  <!--   Core JS Files   -->
 <script>
   $(document).ready(function () {
     var table = $('.table').DataTable();
     $('.table tbody').on( 'click', 'a#resign', function () {
        swal({
        title: 'Warning?',
        text: "Are You Sure You Want To Proceed?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Proceed'
      }).then((result) => {
   
        var facility_table_name = $(this).attr("data-hospital-name1");
        var hospital_name = $(this).attr("data-hospital-name");
        if(unRegisterPatient(facility_table_name,hospital_name) == true){
          table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
        }
        
      });
      
    } );
   })
 </script>