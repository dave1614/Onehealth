    <?php
      if(is_array($curr_health_facility_arr)){
        foreach($curr_health_facility_arr as $row){
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
      <script>
        function deletePersonnel(elem,evt,personnels_id){
          evt.preventDefault();
          swal({
              title: 'Warning',
              html: "Are You Sure You Want To Remove This Personnel From Performing This Functionality?",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText : "No"
          }).then(function(){
            var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/delete_personnel') ?>";
           
            $(".spinner-overlay").show();
            $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "id="+personnels_id,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success){
                  document.location.reload();
                }else{
                  $.notify({
                    message:"Sorry Something Went Wrong."
                    },{
                      type : "warning" 
                  });
                }
              },error : function () {
                $(".spinner-overlay").hide();
                $.notify({
                  message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
                  },{
                    type : "danger" 
                });
              } 
            }); 
          });
        }
      </script>
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
            $dept_id = $this->onehealth_model->getDeptIdBySlug($second_addition);
            $depts_arr = $this->onehealth_model->getDeptById($dept_id);
            if(is_array($depts_arr)){
              foreach($depts_arr as $dept){
                $dept_id = $dept->id;
                $dept_name = $dept->name;
                $dept_slug = $dept->slug;        
              } 
              $sub_dept_info = $this->onehealth_model->getSubDeptBySlugAndDeptId($dept_id,$third_addition);
              if(is_array($sub_dept_info)){
                foreach($sub_dept_info as $sub_dept){
                  $sub_dept_id = $sub_dept->id;
                  $sub_dept_name = $sub_dept->name;
                  $sub_dept_slug = $sub_dept->slug;
                }  
                $personnel_info = $this->onehealth_model->getPersonnelBySlugDeptIdAndSubDeptId($fourth_addition,$dept_id,$sub_dept_id);
                if(is_array($personnel_info)){
                  foreach($personnel_info as $row){
                    $personnel_id = $row->id;
                    $personnel_name = $row->name;
                    $personnel_slug = $row->slug;
                  }
                  
                }
              }  
            }
          ?>
         
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a>&nbsp;&nbsp; > ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin') ?>">  <?php echo $sub_dept_name; ?></a> &nbsp;&nbsp; > >  <?php echo $personnel_name; ?></span>
          
          <h3 style="text-transform: capitalize;" class="text-center"><?php echo $sub_dept_name; ?></h3>
          
         
          
          <div class="row">
            <div class="col-sm-8">
              <div class="card">
                <div class="card-header card-header-blue card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <div class="card-title">
                    <h4 style="text-transform: capitalize;"><?php echo $personnel_name; ?>'s Personnel</h4>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                    $personnel_arr = $this->onehealth_model->getPersonnelForSubDept($health_facility_id,$personnel_id);
                    if(is_array($personnel_arr)){
                      $num = 0;
                  ?>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th>Username</th>
                          <th>Full Name</th>
                          <th>Date Registered</th>
                          <th class="text-right">Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                            foreach($personnel_arr as $row){
                              $num++;
                              $id = $row->id;
                              $health_facility_id = $row->health_facility_id;
                              $personnel_id = $row->personnel_id;
                              $user_id = $row->user_id;
                              $type = $row->type;
                              $date = $row->date;
                              $time = $row->time;

                              $user_slug = $this->onehealth_model->getUserParamById("slug",$user_id);
                              $user_full_name = $this->onehealth_model->getUserParamById("full_name",$user_id);
                              $user_name = $this->onehealth_model->getUserParamById("user_name",$user_id);
                        ?>
                        <tr>
                          <td><?php echo $num; ?></td>
                          <td ><a target="_blank" class="text-primary" href="<?php echo site_url('onehealth/'.$user_slug); ?>"><?php echo $user_name; ?></a></td>
                          <td style="text-transform: capitalize;"><?php echo $user_full_name; ?></td>
                         <td class="text-secondary"><?php echo $date . ' at ' . $time; ?></td>
                          
                          <td class="td-actions text-right">
                            <a href="#" rel="tooltip" data-toggle="tooltip" onclick="deletePersonnel(this,event,<?php echo $id; ?>)"  title="Delete Personnel" class="btn btn-danger">
                              <i class="fas fa-user-times"></i>
                            </a>
                              
                            <!-- <a href="" rel="tooltip" data-toggle="tooltip" id="suspend-sub-admin-<?php echo $personnel_user_id; ?>" title="Suspend Personnel" class="btn btn-warning">
                              <i class="fas fa-user-slash"></i>
                            </a> 

                            <a href="" rel="tooltip" data-toggle="tooltip" id="track-sub-admin-<?php echo $personnel_user_id; ?>" title="Track Personnel" class="btn btn-info">
                              <i class="fas fa-users-cog"></i>
                            </a>  -->
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
                      echo "<h3 class='text-warning'>You Have No Personnel Here</h4>";
                    }
                  ?>
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
      
      
      <?php if($this->session->add_admin){ ?>
       $.notify({
        message:"Personnel Added Successfully"
        },{
          type : "success"  
        });
      <?php }  ?>
    });
  </script>
