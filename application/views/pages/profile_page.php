<style>
  
.name h6 {
    margin-top: 10px;
    margin-bottom: 10px;
}


.profile-page .page-header {
    height: 100px;
    background-position:center;
}

.page-header {
    /*height: 100vh;*/
    height: 300px;
    min-height: 200px;
    background-size: cover;
    margin: 0;
    padding: 0;
    border: 0;
    display: flex;
    align-items: center;
}

.header-filter:after, .header-filter:before {
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 100%;
    display: block;
    left: 0;
    top: 0;
    content: "";
}

.header-filter::before {
    background: rgba(0,0,0,.5);
}

.main-raised {
    margin: -60px 30px 0;
    border-radius: 6px;
    box-shadow: 0 16px 24px 2px rgba(0,0,0,.14), 0 6px 30px 5px rgba(0,0,0,.12), 0 8px 10px -5px rgba(0,0,0,.2);
}

.main {
    background: #FFF;
    position: relative;
    z-index: 3;
}

.profile-page .profile {
    text-align: center;
}

.profile-page .profile img {
    max-width: 160px;
    width: 100%;
    margin: 0 auto;
    -webkit-transform: translate3d(0,-50%,0);
    -moz-transform: translate3d(0,-50%,0);
    -o-transform: translate3d(0,-50%,0);
    -ms-transform: translate3d(0,-50%,0);
    transform: translate3d(0,-50%,0);
}

.img-raised {
    box-shadow: 0 5px 15px -8px rgba(0,0,0,.24), 0 8px 10px -5px rgba(0,0,0,.2);
}

.rounded-circle {
    border-radius: 50%!important;
}

.img-fluid, .img-thumbnail {
    max-width: 100%;
    height: auto;
}

.title {
    margin-top: 30px;
    margin-bottom: 25px;
    min-height: 32px;
    color: #3C4858;
    font-weight: 700;
    font-family: "Roboto Slab","Times New Roman",serif;
}

.profile-page .description {
    margin: 1.071rem auto 0;
    max-width: 600px;
    color: #999;
    font-weight: 300;
}

p {
    font-size: 14px;
    margin: 0 0 10px;
}

.profile-page .profile-tabs {
    margin-top: 4.284rem;
}

.nav-pills, .nav-tabs {
    border: 0;
    border-radius: 3px;
    padding: 0 15px;
}

.nav .nav-item {
    position: relative;
    margin: 0 2px;
}

.nav-pills.nav-pills-icons .nav-item .nav-link {
    border-radius: 4px;
}

.nav-pills .nav-item .nav-link.active {
    color: #fff;
    background-color: #9c27b0;
    box-shadow: 0 5px 20px 0 rgba(0,0,0,.2), 0 13px 24px -11px rgba(156,39,176,.6);
}

.nav-pills .nav-item .nav-link {
    line-height: 24px;
    font-size: 12px;
    font-weight: 500;
    min-width: 100px;
    color: #555;
    transition: all .3s;
    border-radius: 30px;
    padding: 10px 15px;
    text-align: center;
}

.nav-pills .nav-item .nav-link:not(.active):hover {
    background-color: rgba(200,200,200,.2);
}


.nav-pills .nav-item i {
    display: block;
    font-size: 30px;
    padding: 15px 0;
}

.tab-space {
    padding: 20px 0 50px;
}

.profile-page .gallery {
    margin-top: 3.213rem;
    padding-bottom: 50px;
}

.profile-page .gallery img {
    width: 100%;
    margin-bottom: 2.142rem;
}

.profile-page .profile .name{
    margin-top: -80px;
}

img.rounded {
    border-radius: 6px!important;
}

.tab-content>.active {
    display: block;
}

/*buttons*/
.btn {
    position: relative;
    padding: 12px 30px;
    margin: .3125rem 1px;
    font-size: .75rem;
    font-weight: 400;
    line-height: 1.428571;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0;
    border: 0;
    border-radius: .2rem;
    outline: 0;
    transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1);
    will-change: box-shadow,transform;
}

.btn.btn-just-icon {
    font-size: 20px;
    height: 41px;
    min-width: 41px;
    width: 41px;
    padding: 0;
    overflow: hidden;
    position: relative;
    line-height: 41px;
}

.btn.btn-just-icon fa{
    margin-top: 0;
    position: absolute;
    width: 100%;
    transform: none;
    left: 0;
    top: 0;
    height: 100%;
    line-height: 41px;
    font-size: 20px;
}
.btn.btn-link{
    background-color: transparent;
    color: #999;
}

/* dropdown */




.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    min-width: 11rem !important;
    margin: .125rem 0 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    background-color: #fff;
    background-clip: padding-box;
    border-radius: .25rem;
    transition: transform .3s cubic-bezier(.4,0,.2,1),opacity .2s cubic-bezier(.4,0,.2,1);
}

.dropdown-menu.show{
    transition: transform .3s cubic-bezier(.4,0,.2,1),opacity .2s cubic-bezier(.4,0,.2,1);
}


.dropdown-menu .dropdown-item:focus, .dropdown-menu .dropdown-item:hover, .dropdown-menu a:active, .dropdown-menu a:focus, .dropdown-menu a:hover {
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(156,39,176,.4);
    background-color: #9c27b0;
    color: #FFF;
}
.show .dropdown-toggle:after {
    transform: rotate(180deg);
}

.dropdown-toggle:after {
    will-change: transform;
    transition: transform .15s linear;
}


.dropdown-menu .dropdown-item, .dropdown-menu li>a {
    position: relative;
    width: auto;
    display: flex;
    flex-flow: nowrap;
    align-items: center;
    color: #333;
    font-weight: 400;
    text-decoration: none;
    font-size: .8125rem;
    border-radius: .125rem;
    margin: 0 .3125rem;
    transition: all .15s linear;
    min-width: 7rem;
    padding: 0.625rem 1.25rem;
    min-height: 1rem !important;
    overflow: hidden;
    line-height: 1.428571;
    text-overflow: ellipsis;
    word-wrap: break-word;
}

.dropdown-menu.dropdown-with-icons .dropdown-item {
    padding: .75rem 1.25rem .75rem .75rem;
}

.dropdown-menu.dropdown-with-icons .dropdown-item .material-icons {
    vertical-align: middle;
    font-size: 24px;
    position: relative;
    margin-top: -4px;
    top: 1px;
    margin-right: 12px;
    opacity: .5;
}

.profile .avatar img{
    -webkit-transform: translate3d(0,-50%,0);
    transform: translate3d(0,-50%,0); 
}
.name h3{
  margin-top: 0;
}
.logo-link-cont{
  cursor: pointer;
}
.add-cover-photo-div{
  cursor: pointer;
  position: absolute;
  border: 2px solid white;
  padding: 5px;
  z-index: 100;
  top: 7px;
  left: 10px;
  color: white;

}

#registeredPatientsModal .modal-body{
  height: 400px;
  overflow-y: auto;
}
.dp-thumb{
  width: 30px;
  height: 30px;
  border-radius: 50%;
}  

.small-loader{
  width: 30px;
  height: 30px;
  display: none;
}
</style>
<?php
  if(is_array($curr_health_facility_arr)){
    foreach($curr_health_facility_arr as $row){
      $hospital_id = $row->id;
      $health_facility_id = $row->id;
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
      $bio = $row->bio;
      $no_logo = false;
      $patient_bio_data_table = $this->onehealth_model->createpatientBioDataTableString($hospital_id,$hospital_name);
      $cover_photo = $row->cover_photo;
      if($this->onehealth_model->getUserParamById("admin_facility_id",$user_id) == $hospital_id){
        $is_admin = true;
      }else{
        $is_admin = false;
      }
      $registered = false;
      
      if(!$is_admin){
        if($this->onehealth_model->checkIfUserIsRegisteredOnThisFacility($health_facility_id,$user_id)){
          $registered = true;
        }else{
          $registered = false;
        }
        
      }

      if(is_null($hospital_logo)){
        $no_logo = true;
        if($is_admin == true){
          $hospital_logo = "<img id='logo-image' width='100' height='100' class='round img-raised rounded-circle img-fluid logo-link-cont' avatar='".$hospital_name."' col='".$color."' rel='tooltip' data-original-title='Change Your Facility Logo' data-toggle='modal' data-target='#change-logo-modal' data-backdrop='false'>";
        }else{
          $hospital_logo = "<img id='logo-image' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$hospital_name."' col='".$color."'>";
        }
        
      }else{
        $logo_url = base_url('assets/images/'.$hospital_logo);
        if($is_admin == true){
          $hospital_logo = '<img id="logo-image" src="'.$logo_url.'" alt="" width="100" height="100" class="round img-raised rounded-circle img-fluid logo-link-cont" rel="tooltip" data-original-title="Change Your Facility Logo" data-toggle="modal" data-target="#change-logo-modal" data-backdrop="false" style="width:100px; height:100px;">';
        }else{
          $hospital_logo = '<img id="logo-image" src="'.$logo_url.'" alt="" width="100" height="100" class="round img-raised rounded-circle img-fluid" style="width:100px; height:100px;">';
        }
      }
      
    }
?>

<script>

  <?php if($is_admin){ ?>
  function submitImage (elem,evt) {
    evt.preventDefault()
    var file_input = elem.querySelector("input");
    
    var file_name = file_input.getAttribute("name");
    
    var file = file_input.files;
    
    var form_data = new FormData();
    var error = '';
    if(file_input.value !== ""){
      if(file.length == 1){
        var name = file[0].name;
        
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
            
              $(".spinner-overlay").hide();
              if(response.empty == true){
                 $.notify({
                message:"No Image Was Uploaded"
                },{
                  type : "danger"  
                });
              }
              else if(response.success == true && response.new_image_name !== ""){
                var new_image_name = response.new_image_name;
                $.notify({
                message:"Logo Changed Successfully"
                },{
                  type : "success"  
                });
                $("#change-logo-modal").modal('hide');
                $("#logo-image").remove();
                $(".logo-link-cont").append('<img id="logo-image" src="' + new_image_name + '" alt="" width="100" class="round img-raised rounded-circle img-fluid" height="100">');
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
              }
              
            },
            error : function () {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Something Went Wrong When Trying To Upload Your Images"
                },{
                  type : "danger"  
                });
            } 
          });
      }else{
        swal({
            title: 'Error!',
            text: error,
            type: 'error'         
          })
      }

    }else{
       $.notify({
        message:"Sorry You Must Select One Image"
        },{
          type : "danger"  
        });
      return false;
    }           
  }

  function submitCoverImage (elem,evt) {
    evt.preventDefault()
    var file_input = elem.querySelector("input");
    
    var file_name = file_input.getAttribute("name");
    
    var file = file_input.files;
    
    var form_data = new FormData();
    var error = '';
    if(file_input.value !== ""){
      if(file.length == 1){
        var name = file[0].name;
        
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
            
              $(".spinner-overlay").hide();
              if(response.empty == true){
                 $.notify({
                message:"No Image Was Uploaded"
                },{
                  type : "danger"  
                });
              }
              else if(response.success == true && response.new_image_name !== ""){
                var new_image_name = response.new_image_name;
                $.notify({
                message:"Logo Changed Successfully"
                },{
                  type : "success"  
                });
                $("#change-cover-photo-modal").modal('hide');
                $("#cover-image").css({
                  "background-image" : "url("+ new_image_name +")"
                })
              }else if (response.success == false && response.errors !== "") {
                $.notify({
                message:"Cover Photo Upload Was Unsuccessful"
                },{
                  type : "danger"  
                });
                swal({
                  title: 'Error',
                  text: response.errors,
                  type: 'error',
                  
                })
              }
              
            },
            error : function () {
              $(".spinner-overlay").hide();
               $.notify({
                message:"Something Went Wrong When Trying To Upload Your Image"
                },{
                  type : "danger"  
                });
            } 
          });
      }else{
        swal({
            title: 'Error!',
            text: error,
            type: 'error'         
          })
      }

    }else{
       $.notify({
        message:"Sorry You Must Select One Image"
        },{
          type : "danger"  
        });
      return false;
    }           
  }
<?php } ?>
 function registerPatient (elem) {
    elem = $(elem);
    var facility_id = elem.attr("data-facility-id");
    var form_data = {
      health_facility_id : facility_id
    };
    var url = "<?php echo site_url('onehealth/register_patient') ?>"
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function(response){
        $(".spinner-overlay").hide();
       if(response.success){
        
        elem.replaceWith('<span class="text-primary">You Are Registered</span>');
        swal({
          title: 'Choose Action',
          text: "Do You Have An Insurance Code From This Facility?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#4caf50',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText : "No"
        }).then(function(){
          $("#enter-insurance-code-modal #enter-insurance-code-form").attr("data-facility-id",facility_id);
          $("#enter-insurance-code-modal").modal("show");
        }, function(dismiss){
          if(dismiss == 'cancel'){
            swal({
              title: 'Success',
              text: "You Have Been Successfully Registered In This Facility",
              type: 'success'             
            })
          }
        });

       }else if(response.incomplete_details){
        swal({
          title: 'Error!',
          text: "We Have Noticed That Your Patient Information Is Incomplete. Please Use The 'Edit Your Patient Information Tab' On The Sidebar To Enter Your Information",
          type: 'error'
        });
       }else if(response.already_registered){
        swal({
          title: 'Error!',
          text: "You're Already Registered In This Facility",
          type: 'error'
        });
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
  }

  function insuranceModalClose(elem,evt){
    swal({
      title: 'Success',
      text: "You Have Been Successfully Registered In This Facility",
      type: 'success'             
    })
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
          <?php
            $cover_photo_url = base_url('assets/images/cover_photo.png');
            if(!is_null($cover_photo)){
              $cover_photo_url = base_url('assets/images/'.$cover_photo);
            }
          ?>
          <div class="" style="position: relative;">
            <?php if($is_admin){ ?>
            <div class="add-cover-photo-div" data-toggle='modal' data-target='#change-cover-photo-modal' data-backdrop='false'>
              <i class="fas fa-camera"></i>
              &nbsp;&nbsp;<?php if(is_null($cover_photo)){ echo "Add"; } else{ echo "Change"; } ?> Cover Photo
            </div>
            <?php } ?>
            <div class="page-header header-filter" id="cover-image" data-parallax="true" style="background-image:url('<?php echo $cover_photo_url; ?>'); background-color: #8d949e;">
              
            </div>
          </div>
          <!-- <img height="300" src="<?php echo $cover_photo_url; ?>" class="page-header header-filter" data-parallax="true" alt=""> -->
            <div class="main main-raised">
              <div class="profile-content">
                <div class="container">
                    <div class="row">
                      <div class="col-md-6 ml-auto mr-auto text-center">
                         <div class="profile">
                              <div class="avatar">
                                <?php if($is_admin == true){ ?>
                                  <div class="logo-link-cont">
                                <?php } ?>
                                  <?php echo $hospital_logo; ?>
                                <?php if($is_admin == true){ ?>
                                  </a>
                                <?php } ?>  
                                  
                              </div>
                              <?php  
                                if($no_logo == true && $is_admin == true){
                              ?>
                                <span class="form-error">Please Add A Logo To Help Users Identify Your Page</span>
                              <?php
                                }
                              ?>
                              <div class="name">
                                <h3 class="title"><?php echo $hospital_name; ?></h3>
                                <h6><?php echo $facility_structure; ?></h6>
                                <?php
                                  $registered_num = $this->onehealth_model->getNumberOfRegisteredFacilityPatients($health_facility_id);
                                ?>
                                <p id="temp_num" style="display: none;"><?php echo $registered_num; ?></p>
                                <?php
                                  if($registered_num > 0){
                                    $registered_num = '<span class="text-primary">' . $registered_num . ' registered</span>';
                                  }elseif($registered_num == 0){
                                    $registered_num = 'no registered users';
                                  }
                                ?>
                                <div class="" style="margin-top: 50px;">

                                  
                                  <?php if($is_admin){ ?>
                                  <a href='<?php echo site_url("onehealth/index/".$hospital_slug.'/'."edit-profile") ?>' class="btn btn-primary btn-round col-sm-5">Edit Profile</a>
                                  <?php } if($this->onehealth_model->getPatientAccount($user_id)){ ?>
                                  <?php if($registered){  ?>
                                    <span class="text-primary">You Are Registered</span>
                                    <?php }else{ ?>  
                                    <button class="btn btn-success btn-round col-sm-5" data-facility-id="<?php echo $hospital_id;?>" onclick="return registerPatient(this,event)">Register</button></td>  
                                  <?php } ?>  
                                  <?php } ?>
                                </div>
                                <!-- <div class="social-cont">
                                  <a href="#pablo" class="btn btn-just-icon btn-link btn-facebook"><i class="fa fa-facebook"></i></a>
                                  <a href="#pablo" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"></i></a>
                                  <a href="#pablo" class="btn btn-just-icon btn-link btn-pinterest"><i class="fa fa-pinterest"></i></a>
                                  <a href="#pablo" class="btn btn-just-icon btn-link btn-instagram"><i class="fa fa-instagram"></i></a>
                                </div> -->
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="description text-center">
                        <p><?php echo $bio; ?></p>
                    </div>
                    
                    <?php if($this->onehealth_model->getPatientAccount($user_id)){ ?>
                    <div class="row">
                      <div class="col-md-6 ml-auto mr-auto">
                        <div class="profile-tabs">
                          <ul class="nav nav-pills nav-pills-icons justify-content-center" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#studio" role="tab" data-toggle="tab">
                                  <i class="material-icons">local_hospital</i>
                                  Get Medical Help
                                </a>
                            </li>
                            
                          </ul>
                        </div>
                      </div>
                    </div>
            
                    <div class="tab-content">
                      <div class="tab-pane active" id="studio">
                        <div class="row">
                          <table class="table">
                            <tbody>
                              <tr class="pointer-cursor">
                                <td>1</td>
                                <td><a href="<?php echo site_url('onehealth/index/'.$addition.'/access-laboratory-services'); ?>">Access Laboratory Services</a></td>
                              </tr>
                              <tr class="pointer-cursor">
                                <td>2</td>
                                <td>Chat With Doctor</td>
                              </tr>
                              <tr class="pointer-cursor">
                                <td>3</td>
                                <td>Access Pharmacy Services</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane text-center gallery" id="works">
                        <div class="row">
                        </div>
                      </div>
                      <div class="tab-pane text-center gallery" id="favorite">
                        <div class="row">
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>

            <!-- Modals -->
            <div id="registeredPatientsModal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                  
                  <div class="modal-header">
                    <h5 class="modal-title">Registered Patients</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                     <table class="table">
                      <thead>
                        
                      </thead>
                      <tbody>
                     </tbody>
                     </table> 
                  </div>
                  <div class="text-center">
                    <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" class="small-loader" alt="...">
                  </div>
                  <div class="modal-footer text-center">
                    
                  </div>
                </div>
              </div>
            </div>
            <?php if($is_admin){ ?>
            <div id="change-logo-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <?php $attr = array('id' => 'change_facility_form','onsubmit' => 'return submitImage(this,event)'); ?>
                    <?php echo form_open_multipart("onehealth/index/".$hospital_slug."/change_facility_logo",$attr); ?>
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

            <div id="change-cover-photo-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <?php $attr = array('id' => 'change_facility_cover_photo','onsubmit' => 'return submitCoverImage(this,event)'); ?>
                    <?php echo form_open_multipart("onehealth/index/".$hospital_slug."/change_facility_cover_photo",$attr); ?>
                  <div class="modal-header">
                    <h3 class="modal-title"><?php if(is_null($cover_photo)){ echo "Add"; } else{ echo "Change"; } ?> Cover Photo</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    
                      <input type="file" name="image" id="cover_image" class="inputfile inputfile-1" accept="image/*"/>
                      <label for="cover_image"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                      <div class="text-left">
                        <span class="form-error">1. Max Image Size: 4MB.</span><br>
                        <span class="form-error">2. Recommended Dimensions: 851 X 350</span><br>
                        <span class="form-error">3. Max Dimensions: 1000 X 500</span><br>
                        <span class="form-error">4. Allowed Types: GIF,PNG,JPG,JPEG</span>
                      </div>

                  </div>
                  <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-primary text-right">Upload</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            <?php } ?>
          
        </div>
      </div>
<?php
  }
?>    
      <span style="display: none;" id="offset">7</span>  
      <div class="modal fade" data-backdrop="static" id="enter-insurance-code-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title">Enter Insurance Code Details</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="insuranceModalClose(this,event)">
                  <span aria-hidden="true">×</span>
              </button>
            </div>

            <div class="modal-body" style="">
              <?php
              $attr = array('id' => 'enter-insurance-code-form');
              echo form_open("onehealth/submit_insurance_code_patient_registration",$attr);
              ?>
                <div class="form-group">
                  <label for="user_type">Insurance Type: </label>
                  <select name="user_type" id="user_type" class="form-control selectpicker" data-style="btn btn-link">
                    <option value="pfp">Part Fee Paying</option>
                    <option value="nfp">None Fee Paying</option>
                  </select>
                  <span class="form-error"></span>
                </div>

                <div class="form-group">
                  <label for="code">Insurance Code: </label>
                  <input type="text" class="form-control" name="code" id="code">
                  <span class="form-error"></span>
                </div>

                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="insuranceModalClose(this,event)">Close</button>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      
      <script>
        var big_image;

        $(document).ready(function() {

          $("#enter-insurance-code-form").submit(function (evt) {
            evt.preventDefault();
            var me  = $(this);
            var form_data = me.serializeArray();

            var facility_id = me.attr("data-facility-id");
            form_data = form_data.concat({
              "name" : "health_facility_id",
              "value" : facility_id
            })
            var url = me.attr("action");

            $(".spinner-overlay").show();
            $.ajax({
              url : url,
              type : "POST",
              dataType : "json",
              responseType : "json",
              data : form_data,
              success : function (response) {
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success){
                  $("#enter-insurance-code-modal").modal("hide");
                  swal({
                    title: 'Success',
                    text: "You Have Been Successfully Registered In This Facility",
                    type: 'success'             
                  })
                }else if(response.not_registered){
                  $.notify({
                  message:"You Are Currently Not Registered With This Facility"
                  },{
                    type : "warning"  
                  });
                }else if(response.messages != ""){
                  $.each(response.messages, function (key,value) {

                    var element = me.find('#'+key);
                    
                    element.closest('div.form-group')
                            
                            .find('.form-error').remove();
                    element.after(value);
                    
                  });

                  $.notify({
                  message:"Some Values Where Not Valid. Please Enter Valid Values"
                  },{
                    type : "warning"  
                  });
                }else if(response.insurance_details_invalid){
                  swal({
                    title: 'Error!',
                    text: "The Insurance Details You Entered Are Invalid. Please Try Again",
                    type: 'error'             
                  })
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
          })
          $("#registeredPatientsModal .modal-body").scroll(function (evt) {
            
             if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                $(".small-loader").show();
                var url = "<?php echo site_url('onehealth/index/'.$addition.'/get-remainder-registered-users'); ?>";
                var offset = $("#offset").html();
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "text",
                  dataType : "text",
                  data : "offset="+offset,
                  success : function(response){
                    
                    if(response !== ""){
                      offset = offset * 7;
                      $("#offset").html(offset);
                      $("#registeredPatientsModal .modal-body tbody").append(response);
                      $(".small-loader").hide();
                    }else{
                       $(this).off("scroll");
                    }
                  },error : function () {
                    $(this).off("scroll");
                  }
                });
              }
          })

          $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
          });
          BrowserDetect.init();

          // Init Material scripts for buttons ripples, inputs animations etc, more info on the next link https://github.com/FezVrasta/bootstrap-material-design#materialjs
          $('body').bootstrapMaterialDesign();

          window_width = $(window).width();

          $navbar = $('.navbar[color-on-scroll]');
          scroll_distance = $navbar.attr('color-on-scroll') || 500;

          $navbar_collapse = $('.navbar').find('.navbar-collapse');

          //  Activate the Tooltips
          $('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();

          // Activate Popovers
          $('[data-toggle="popover"]').popover();

          if ($('.navbar-color-on-scroll').length != 0) {
            $(window).on('scroll', materialKit.checkScrollForTransparentNavbar);
          }

          materialKit.checkScrollForTransparentNavbar();

          if (window_width >= 768) {
            big_image = $('.page-header[data-parallax="true"]');
            if (big_image.length != 0) {
              $(window).on('scroll', materialKit.checkScrollForParallax);
            }

          }


        });

        $(document).on('click', '.navbar-toggler', function() {
          $toggle = $(this);

          if (materialKit.misc.navbar_menu_visible == 1) {
            $('html').removeClass('nav-open');
            materialKit.misc.navbar_menu_visible = 0;
            $('#bodyClick').remove();
            setTimeout(function() {
              $toggle.removeClass('toggled');
            }, 550);

            $('html').removeClass('nav-open-absolute');
          } else {
            setTimeout(function() {
              $toggle.addClass('toggled');
            }, 580);


            div = '<div id="bodyClick"></div>';
            $(div).appendTo("body").click(function() {
              $('html').removeClass('nav-open');

              if ($('nav').hasClass('navbar-absolute')) {
                $('html').removeClass('nav-open-absolute');
              }
              materialKit.misc.navbar_menu_visible = 0;
              $('#bodyClick').remove();
              setTimeout(function() {
                $toggle.removeClass('toggled');
              }, 550);
            });

            if ($('nav').hasClass('navbar-absolute')) {
              $('html').addClass('nav-open-absolute');
            }

            $('html').addClass('nav-open');
            materialKit.misc.navbar_menu_visible = 1;
          }
        });

          materialKit = {
            misc: {
              navbar_menu_visible: 0,
              window_width: 0,
              transparent: true,
              fixedTop: false,
              navbar_initialized: false,
              isWindow: document.documentMode || /Edge/.test(navigator.userAgent)
            },

            initFormExtendedDatetimepickers: function() {
              $('.datetimepicker').datetimepicker({
                icons: {
                  time: "fa fa-clock-o",
                  date: "fa fa-calendar",
                  up: "fa fa-chevron-up",
                  down: "fa fa-chevron-down",
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                }
              });
            },

            initSliders: function() {
              // Sliders for demo purpose
              var slider = document.getElementById('sliderRegular');

              noUiSlider.create(slider, {
                start: 40,
                connect: [true, false],
                range: {
                  min: 0,
                  max: 100
                }
              });

              var slider2 = document.getElementById('sliderDouble');

              noUiSlider.create(slider2, {
                start: [20, 60],
                connect: true,
                range: {
                  min: 0,
                  max: 100
                }
              });
            },

            checkScrollForParallax: function() {
              oVal = ($(window).scrollTop() / 3);
              
              // big_image.css({
              //   'transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              // });
              // add_cover_photo_div.css({
              //   'transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              // });
            },

            checkScrollForTransparentNavbar: debounce(function() {
              if ($(document).scrollTop() > scroll_distance) {
                if (materialKit.misc.transparent) {
                  materialKit.misc.transparent = false;
                  $('.navbar-color-on-scroll').removeClass('navbar-transparent');
                }
              } else {
                if (!materialKit.misc.transparent) {
                  materialKit.misc.transparent = true;
                  $('.navbar-color-on-scroll').addClass('navbar-transparent');
                }
              }
            }, 17)
          };

          // Returns a function, that, as long as it continues to be invoked, will not
          // be triggered. The function will be called after it stops being called for
          // N milliseconds. If `immediate` is passed, trigger the function on the
          // leading edge, instead of the trailing.

          function debounce(func, wait, immediate) {
            var timeout;
            return function() {
              var context = this,
                args = arguments;
              clearTimeout(timeout);
              timeout = setTimeout(function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
              }, wait);
              if (immediate && !timeout) func.apply(context, args);
            };
          };

          var BrowserDetect = {
            init: function() {
              this.browser = this.searchString(this.dataBrowser) || "Other";
              this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "Unknown";
            },
            searchString: function(data) {
              for (var i = 0; i < data.length; i++) {
                var dataString = data[i].string;
                this.versionSearchString = data[i].subString;

                if (dataString.indexOf(data[i].subString) !== -1) {
                  return data[i].identity;
                }
              }
            },
            searchVersion: function(dataString) {
              var index = dataString.indexOf(this.versionSearchString);
              if (index === -1) {
                return;
              }

              var rv = dataString.indexOf("rv:");
              if (this.versionSearchString === "Trident" && rv !== -1) {
                return parseFloat(dataString.substring(rv + 3));
              } else {
                return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
              }
            },

            dataBrowser: [{
                string: navigator.userAgent,
                subString: "Chrome",
                identity: "Chrome"
              },
              {
                string: navigator.userAgent,
                subString: "MSIE",
                identity: "Explorer"
              },
              {
                string: navigator.userAgent,
                subString: "Trident",
                identity: "Explorer"
              },
              {
                string: navigator.userAgent,
                subString: "Firefox",
                identity: "Firefox"
              },
              {
                string: navigator.userAgent,
                subString: "Safari",
                identity: "Safari"
              },
              {
                string: navigator.userAgent,
                subString: "Opera",
                identity: "Opera"
              }
            ]

          };
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 