      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
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
              }
            }
          ?>
          <h3 class="text-secondary">Welcome <?php echo $user_name; ?> To <?php echo $health_facility_name; ?> Administrators Panel.</h3>
          <div class="row">
            <div class="col-sm-8">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Choose Your Action</h4>
                </div>
                <div class="card-body">
                  <h5>Do You Want To: </h5>
                  <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/edit-profile') ?>" id="edit-profile"  class="btn btn-primary" >Edit Facility Profile</a>
                  <button id="view-sections" class="btn btn-info" data-toggle="modal" data-target="#view-sections-modal">View Sections</button>
                  <div class="modal fade" data-backdrop="static" id="view-sections-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title"><?php echo $this->onehealth_model->custom_echo($health_facility_name,50); ?> Sections</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>

                      <div class="modal-body">
                        <table class="table ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Section</th>
                             <!--  
                              <th class="text-right">Actions</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $depts = $this->onehealth_model->getDepts();
                              if(is_array($depts)){
                                $i = 0;
                                foreach($depts as $dept){
                                  
                                  
                                  $dept_id = $dept->id;
                                  $dept_name = $dept->name;
                                  $dept_slug = $dept->slug;
                                  if($health_facility_structure == "hospital"){
                                    $i++;
                            ?>  
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td style="text-transform: capitalize;"><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin'); ?>"><?php echo $dept_name; ?></a></td>
                                </tr>
                            <?php
                                  }
                                  elseif($health_facility_structure == "laboratory"){
                                    if($dept_id == 1 || $dept_id == 8 || $dept_id == 9){
                                      $i++;
                            ?>  
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td style="text-transform: capitalize;"><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin'); ?>"><?php echo $dept_name; ?></a></td>
                                </tr>
                             <?php
                                   }
                                  }
                                  elseif($health_facility_structure == "pharmacy"){
                                    if($dept_id == 6  || $dept_id == 8 || $dept_id == 9){
                                      $i++;
                            ?>  
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td style="text-transform: capitalize;"><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin'); ?>"><?php echo $dept_name; ?></a></td>
                                </tr>
                            <?php
                               
                                   }
                                  }
                                  elseif($health_facility_structure == "mortuary"){
                                    if($dept_id == 7){
                                      $i++;
                            ?>  
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td style="text-transform: capitalize;"><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin'); ?>"><?php echo $dept_name; ?></a></td>
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

                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
 