<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/images/logo.jpeg') ?>">
<link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo.jpeg') ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<title>
     <?php echo $title; ?>
</title>

<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />


<!-- Extra details for Live View on GitHub Pages -->
<!-- Canonical SEO -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
<link rel="canonical" href="https://www.creative-tim.com/product/material-dashboard-pro" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link href="<?php echo base_url('assets/css/fine-uploader-new.min.css') ?>" rel="stylesheet">

<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!--     Fonts and icons     -->
<!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" /> -->
<link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/css/component.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/custom_file_input.css') ?>">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.css'); ?>">
<!-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/quill.snow.css'); ?>" rel="stylesheet">
<!-- CSS Files -->
<style>
  .footer .container-fluid{
    display: none;
  }
  span.last-message{
    font-size: 11px;
    font-style: italic;
  }
  #notification_li2
      {
      position:relative
      }
  #notification_li
      {
      position:relative
      }
      #notificationContainer 
      {
      background-color: #fff;
      border: 1px solid rgba(100, 100, 100, .4);
      -webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
      overflow: visible;
      position: absolute;
      top: 40px;
      /*margin-left: -170px;*/
      right: -12px;
      width: 400px;
      z-index: -1;
      /*display: none; // Enable this after jquery implementation */
      }
      #notificationContainer2
      {
      background-color: #fff;
      border: 1px solid rgba(100, 100, 100, .4);
      -webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
      overflow: visible;
      position: absolute;
      top: 40px;
      /*margin-left: -170px;*/
      right: -12px;
      width: 400px;
      z-index: -1;
      /*display: none; // Enable this after jquery implementation */
      }
      // Popup Arrow
      #notificationContainer:before {
      content: '';
      display: block;
      position: absolute;
      width: 0;
      height: 0;
      color: transparent;
      border: 10px solid black;
      border-color: transparent transparent white;
      margin-top: -20px;
      margin-left: 188px;
      }
      // Popup Arrow
      #notificationContainer2:before {
      content: '';
      display: block;
      position: absolute;
      width: 0;
      height: 0;
      color: transparent;
      border: 10px solid black;
      border-color: transparent transparent white;
      margin-top: -20px;
      margin-left: 188px;
      }
      #notificationTitle
      {
      font-weight: bold;
      padding: 8px;
      font-size: 13px;
      background-color: #ffffff;
      /*position: fixed;*/
      z-index: 1000;
      width: 384px;
      border-bottom: 1px solid #dddddd;
      }

      #notificationTitle2
      {
      font-weight: bold;
      padding: 8px;
      font-size: 13px;
      background-color: #ffffff;
      
      z-index: 1000;
      width: 384px;
      border-bottom: 1px solid #dddddd;
      }
      #notificationsBody
      {
      padding: 0px 0px 0px 0px !important;
      /*margin-top: 48px;*/
      min-height:300px;
      }

       #notificationsBody2
      {
      padding: 0px 0px 0px 0px !important;
      
      min-height:300px;
      }


      #notificationsBody p{
        font-size: 13px;
        font-weight: bold;
      }
      #notificationsBody span{
        font-size: 12px;
        /*font-weight: bold;*/
      }

      #notificationsBody2 p{
        font-size: 13px;
        font-weight: bold;
      }
      #notificationsBody2 span{
        font-size: 12px;
        /*font-weight: bold;*/
      }

      #notificationFooter
      {
      background-color: #e9eaed;
      text-align: center;
      font-weight: bold;
      padding: 8px;
      font-size: 12px;
      border-top: 1px solid #dddddd;
      }



    #notification_count 
    {
    padding: 3px 7px 3px 7px;
    background: #cc0000;
    color: #ffffff;
    font-weight: bold;
    margin-left: 77px;
    border-radius: 9px;
    -moz-border-radius: 9px; 
    -webkit-border-radius: 9px;
    position: absolute;
    margin-top: -11px;
    font-size: 11px;
    }

    #notificationsBody .avatar img{
       border-radius: 50%;
       width: 50px;
       height: 50px;
    }
    #notificationsBody .message-sender .sender-name{
      margin-bottom: 3px;
    }
    #notificationsBody .conversation{
      cursor: pointer;
      padding-top: 5px;
      padding-bottom: 5px;
      border-bottom: 1px solid #dddddd;
    }
    #notificationsBody .conversation:hover{
      background-image: linear-gradient(rgba(29, 33, 41, .04), rgba(29, 33, 41, .04));
    }
    #notificationsBody .container{
      max-height: 300px;
      overflow-y: scroll;
    }


    #notificationsBody2 .avatar img{
       border-radius: 50%;
       width: 50px;
       height: 50px;
    }
    #notificationsBody2 .message-sender .sender-name{
      margin-bottom: 3px;
    }
    #notificationsBody2 .conversation{
      height: 80px;
      cursor: pointer;
      padding-top: 5px;
      padding-bottom: 5px;
      border-bottom: 2px solid #F0F0F0 ;
    }
    #notificationsBody2 .conversation:hover{
      background-image: linear-gradient(rgba(29, 33, 41, .04), rgba(29, 33, 41, .04));
    }
    #notificationsBody2 .container{
      max-height: 300px;
      overflow-y: scroll;
    }

    span.new-message-num.notification{
      right: -11px;
      top: -8px;
    }

    #notificationsBody  .small-loader{
      width: 30px;
      height: 30px;
      /*display: none;*/
    }

    .follow-loader{
      width:20px;
      margin-left: 13px;
      display: none;
    }

    .post-time-display{
      font-size: 12px;
      font-weight: bold;
      font-style: italic;
      color: #707070;
    }

    .spinner-overlay{
      z-index: 1000000;
    }
        
</style>

<link rel="stylesheet" href="<?php echo base_url('assets/css/perfect-scrollbar.css') ?>">

<link href="<?php echo base_url('assets/css/material-dashboard.min.css?v=2.0.2') ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url('assets/css/bs-pagination.min.css');?>">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/css/basic/emojify.min.css" />



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0-rc.2/themes/smoothness/jquery-ui.css" />

<script src="https://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.js"></script>
<script src="<?php echo base_url('assets/js/jquery.fine-uploader.js'); ?>"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/fileinput.css') ?>">
<script>
  
  function seeAll (elem,evt) {
    evt.preventDefault();
    document.location.assign("<?php echo site_url('onehealth/index/notifications'); ?>");
  }

  function seeAll2 (elem,evt) {
    evt.preventDefault();
    document.location.assign('<?php echo site_url('onehealth/messages'); ?>');
  }

   function notificationLinkClick2(elem,e)
  {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    e.preventDefault();

    if($(window).width() >= 500){
      if($("#notificationContainer2").hasClass("hide")){ 
        if(!$("#notificationContainer").hasClass("hide")){
          addClass(document.querySelector("#notificationContainer"),"hide")
        }       
        removeClass(document.querySelector("#notificationContainer2"),"hide");
      }else{
        addClass(document.querySelector("#notificationContainer2"),"hide");
      }
      $("#notification_count").fadeOut("slow");
    }else{
       document.location.assign("<?php echo site_url('onehealth/index/messages'); ?>");
    }
    // return false;
  }

  function notificationLinkClick(elem,e)
  {
    if (!e) var e = window.event;                // Get the window event
    e.cancelBubble = true;                       // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation();  // Other Broswers
    e.preventDefault();

    if($(window).width() >= 500){
      if($("#notificationContainer").hasClass("hide")){
        if(!$("#notificationContainer2").hasClass("hide")){
          addClass(document.querySelector("#notificationContainer2"),"hide")
        }
        removeClass(document.querySelector("#notificationContainer"),"hide");
      }else{
        addClass(document.querySelector("#notificationContainer"),"hide");
      }
      
      $("#notification_count").fadeOut("slow");
    }else{
       document.location.assign("<?php echo site_url('onehealth/index/messages'); ?>");
    }
    // return false;
  }
  function toggleNotifPanels(){
    $("#notificationContainer2").addClass('hide');
    $("#notificationContainer").addClass('hide');
  }
  $(document).ready(function () {
    // $("#notificationsBody .container").perfectScrollbar();

      $('body,html').click(function(e){
       toggleNotifPanels();
      });

      //Document Click hiding the popup 
      $(document).click(function()
      {
        // $("#notificationContainer").hide();
      });

      //Popup on click
      $("#notificationContainer").click(function()
      {
        return false;
      });

      $("#notificationContainer2").click(function()
      {
        return false;
      });

    // var requeue = function() {
    //   setTimeout(login, 15000);
    // };

    // function login () {
    //   // body... 
    //   $.ajax({
    //     url : "<?php echo site_url('onehealth/userlogin'); ?>",
    //     type : "POST",
    //     responseType : "text",
    //     dataType : "text",
    //     data : "login=true",
    //     success: function (response) {
    //       // console.log(response)
    //       if(response == "true"){
    //         // console.log('kkl')
    //         requeue();
    //       }
    //     },
    //     error : function () {
    //       clearTimeout(login);
    //     }
    //   });  

    // }
    // setTimeout(login, 15000);

    $("#myInput").keyup(function (e) {
      var search_val = $(this).val().toLowerCase();
      
      if(search_val !== ""){
        var get_tests_url = "<?php echo site_url('onehealth/index/cl_admin/get_all_names'); ?>";
        $.ajax({
          url : get_tests_url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "get_all_names=true&search_val="+search_val,
          success : function (response) {                     
            $(".spinner-overlay").hide();
            // console.log(response);
            if(response.error == true){

            }else if(response.success == true){
              autocomplete1(document.getElementById("myInput"),Object.values(response.array),search_val);
            }
            
          },
          error : function () {
          $(".spinner-overlay").hide();
          }  
        });
      }else{
        $("#myInputautocomplete-list").hide();
      }
    });


  })
  
  var getDataUrl = function (img) {
  var canvas = document.createElement('canvas')
  var ctx = canvas.getContext('2d')

  canvas.width = img.width
  canvas.height = img.height
  ctx.drawImage(img, 0, 0)

  // If the image is not png, the format
  // must be specified here
  return canvas.toDataURL()
}


</script>
</head>

<body id="body">
  <div id="sound" style="visibility: hidden;"></div>
  <div class="wrapper ">
    <div class="sidebar" data-color="azure" data-background-color="black" data-image="<?php  echo base_url('assets/images/side-bar-img.jpg'); ?>">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->

   <!--  <div class="logo" data-toggle="tooltip" title="<?php ?>">
      <div class="user">
        <div class="photo" style="">
          <a href="" class="simple-text logo-mini">
             <img src="<?php echo base_url('assets/images/avatar.jpg') ?>" alt="">
          </a>
        </div>
        <a href="<?php  ?>" class="simple-text logo-normal">
             HEALTH FACILITY NAME
        </a>
      </div>
    </div> -->

    

    <div class="sidebar-wrapper">
        
        <div class="user">
            <div class="photo">
              <?php  
                if(is_null($logo)){
                  $user_avatar = 'avatar.jpg';
                }else{
                  $user_avatar = $logo;
                }
              ?>
                <img src="<?php echo base_url('assets/images/'.$user_avatar) ?>" class="small-profile-img" />
            </div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span style="text-transform: capitalize;">
                       <?php echo $user_name; ?> 
                      <b class="caret"></b>
                    </span>
                </a>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo site_url('onehealth/'.$user_name); ?>">
                              <span class="sidebar-mini"> YP </span>
                              <span class="sidebar-normal"> Your Profile </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo site_url('onehealth/change_password'); ?>">
                              <span class="sidebar-mini"> CP </span>
                              <span class="sidebar-normal"> Change Your Password </span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">

            <li class="nav-item active ">
                <a class="nav-link" href="<?php echo site_url('onehealth/cl_admin') ?>">
                    <i class="material-icons">home</i>
                    <p> Home </p>
                </a>
            </li>
            <?php $user_id = $this->onehealth_model->getUserIdWhenLoggedIn(); ?>
  
          <li class="nav-item" id="new-messages-nav-item">
            <a href="<?php echo site_url('onehealth/messages'); ?>" class="nav-link">
              <i class="fab fa-facebook-messenger" style="font-size: 25px;"></i>
              <span class="sidebar-normal">Messages  <?php if($this->onehealth_model->getNewMessagesCount($user_id) > 0){ echo "(" . $this->onehealth_model->getNewMessagesCount($user_id) . ")" ; } ?></span>
            </a>
          </li>  

          <li class="nav-item" id="new-notifs-nav-item">
            <a href="<?php echo site_url('onehealth/notifications'); ?>" class="nav-link">
              <i class="material-icons">notifications</i>
              <span class="sidebar-normal">Notifications  <?php echo $this->onehealth_model->getNewNotifsCount(); ?></span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo site_url('onehealth/index/cl_admin/employer-health-facilities'); ?>" class="nav-link">
              <i class="fas fa-hospital-alt" style="font-size: 20px;"></i>
              <span class="sidebar-normal"><?php echo $this->onehealth_model->custom_echo("Affiliated Health Facilities",30); ?></span>
            </a>
          </li>
         <!--  <li class="nav-item">
            <div class="add-message-div " onclick="register_popup(100, 'ikejaadmin','http://localhost/onehealth/index.php/onehealth/get_messages','http://localhost/onehealth/index.php/onehealth/get_status','http://localhost/onehealth/assets/audio/notif-sound.mp3','http://localhost/onehealth/index.php/onehealth/send_message','http://localhost/onehealth/index.php/onehealth/get_last_chats','http://localhost/onehealth/index.php/onehealth/index/100/messaging')">
              <i class="fab fa-facebook-messenger"></i>
              &nbsp;&nbsp; Send Message
            </div>
          </li> -->
            <?php
              // }
            ?>

            <?php 
             
              if($this->onehealth_model->checkIfUserIsPatient($user_name)){
            ?>
          <!-- <li class="nav-item">
            <a href="<?php echo site_url('onehealth/index/cl_admin/medical-facilities'); ?>" class="nav-link">
              <i class="fas fa-hospital-symbol" style="font-size: 20px"></i>
              <span class="sidebar-normal"><?php echo $this->onehealth_model->custom_echo("Get Medical Help",30); ?></span>
            </a>
          </li> -->
            <?php
              }
            ?>

            <?php
             if($this->onehealth_model->getUserParamById("is_admin",$user_id) == 1){ 
                $health_facility_id = $this->onehealth_model->getUserParamById("admin_facility_id",$user_id);
                $health_facility_slug = $this->onehealth_model->getHealthFacilityParamById("slug",$health_facility_id);
            ?>
              <li class="nav-item">
                <a href="<?php echo site_url('onehealth/index/' . $health_facility_slug .'/earnings'); ?>" class="nav-link">
                  <i class="fas fa-money-check" style="font-size: 20px"></i>
                  <span class="sidebar-normal">Finances</span>
                </a>
              </li>
            <?php } ?>


            <?php
             if($this->onehealth_model->getUserParamById("is_patient",$user_id) == 0){ 
               
            ?>
              <li class="nav-item">
                <a href="<?php echo site_url('onehealth/edit_personnel_info'); ?>" class="nav-link">
                  <i class="far fa-edit" style="font-size: 20px;"></i>
                  <span class="sidebar-normal">Edit Your Personnel Details</span>
                </a>
              </li>
            <?php } ?>

             <?php
             if($this->onehealth_model->getUserParamById("is_patient",$user_id) == 1){ 
               
            ?>
              <li class="nav-item">
                <a href="<?php echo site_url('onehealth/edit_patient_info'); ?>" class="nav-link">
                  <i class="far fa-edit" style="font-size: 20px;"></i>
                  <span class="sidebar-normal">Edit Your Patient Information</span>
                </a>
              </li>
            <?php } ?>

            <li class="nav-item">
              <a href="<?php echo site_url('onehealth/terms'); ?>" class="nav-link">
                <i class="fas fa-file-alt" style="font-size: 20px;"></i>
                <span class="sidebar-normal">Terms</span>
              </a>
            </li>
        </ul>
        

        
    </div>
</div>
<div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="<?php echo site_url('onehealth') ?>"><img style="width: 66px; border-radius: 50%;" src="<?php echo base_url('assets/images/logo.jpeg'); ?>" id="our-logo" alt="One Health">Onehealth</a>
            <img src="<?php echo base_url('assets/images/logo.jpeg'); ?>" id="our-logo2" style="display: none;" alt="One Health">
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form" action="<?php echo site_url('onehealth/index/cl_admin/search/top') ?>" id="main-search-form">
              <div class="input-group no-border">
                
                <div class="autocomplete">
                  <input type="text" value="<?php if($this->uri->segment(3,0) == "cl_admin" && $this->uri->segment(4,0) == "search" && $this->uri->segment(5,0) == "top"){ echo urldecode($this->uri->segment(6)); } ?>" class="form-control" placeholder="Search..." autocomplete="off" id="myInput" name="my-input">
                </div>
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
               
            
            <ul class="navbar-nav">
              
              <li id="noti_Container">
                  <li id="notification_li" style="margin-right: 15px; margin-left: 15px;">
                    <a href="#" id="notificationLink" onclick="return notificationLinkClick(this,event)" style="color: unset;"><i class="fab fa-facebook-messenger" style="font-size: 25px;">
                   <?php
                   if($this->onehealth_model->getNewMessagesCount($user_id) > 0){?><span class="new-message-num notification"><?php echo $this->onehealth_model->getNewMessagesCount($user_id); ?></span><?php } 
                   ?></i></a>
                      <div id="notificationContainer" class="hide">
                      <div id="notificationTitle">Recent</div>
                      <div id="notificationsBody" class="notifications">
                        <div class="container">
                          <?php
                            $all_notifs = $this->onehealth_model->getConversations($user_id);
                            if(is_array($all_notifs)){
                              foreach($all_notifs as $row){
                                $receiver = $row['receiver'];
                                $sender = $row['sender'];
                                $last_message_id = $row['id'];
                                $received = $row['received'];
                                $date_time = $row['date_time'];
                                $date_time = new DateTime($date_time);
                                $date = $date_time->format('j M Y');
                                $time = $date_time->format('h:i:sa');
                                $post_date = $this->onehealth_model->getSocialMediaTime($date,$time);
                                $message = $row['message'];
                                if($sender == $user_id){
                                  $partner = $receiver;
                                }elseif ($receiver == $user_id) {
                                  $partner = $sender;
                                }else{
                                  $partner = "";
                                }
                                if($partner !== ""){
                          ?>
                          <div class="conversation row" onclick="register_popup(<?php echo $sender; ?>, '<?php echo $this->onehealth_model->getUserNameById($sender); ?>','<?php echo site_url('onehealth/index/get_messages') ?>','<?php echo site_url('onehealth/index/get_status') ?>','<?php echo base_url('assets/audio/notif-sound.mp3') ?>','<?php echo site_url('onehealth/index/send_message') ?>','<?php echo site_url('onehealth/index/get_last_chats') ?>','<?php echo site_url('onehealth/index/'.$user_id.'/messaging') ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>')">
                            <div class="col-sm-2 avatar">
                              <img src="<?php echo $this->onehealth_model->getUserLogoById($sender); ?>" alt="<?php echo $this->onehealth_model->getUserNameById($sender); ?>">
                            </div>
                            <div class="col-sm-6 message-sender">
                              <p class="sender-name"><?php echo $this->onehealth_model->getUserNameById($sender); ?> &nbsp;
                              <?php
                                echo $this->onehealth_model->getNumberOfNewMessagesFromSender($user_id,$sender);
                              ?>
                              </p>
                              <span class="last-message"><?php echo $this->onehealth_model->custom_echo($message,30); ?></span>
                            </div>
                            <div class="col-sm-4 time-stamp">
                              <span>
                                <?php echo $this->onehealth_model->getSocialMediaTime($date,$time); ?>
                              </span>
                            </div>
                          </div>
                          <?php } } }else{
                            echo "<p class='text-danger'>You Do Have Not Sent Or Received Any Messages. Please Go To A Users Profile To Send A Message</p>";
                          } ?>
                        </div>
                        <div class="text-center">
                          <img src="<?php echo base_url('assets/images/small-loader.gif'); ?>" class="small-loader hide" alt="">
                        </div>
                      </div>

                      <div id="notificationFooter"><a href="#" onclick="seeAll2(this,event)">See All(<?php echo $this->onehealth_model->getConversationsNum($user_id); ?>)</a></div>
                      </div>

                  </li>

              </li>

              <li id="noti_Container2">
                  <li id="notification_li2">
                    <a href="#" id="notificationLink2" onclick="return notificationLinkClick2(this,event)" style="color: unset;"><i class="material-icons">notifications</i>
                   <?php

                   if($this->onehealth_model->getNotifCount($user_name) > 0){?><span class="new-message-num notification"><?php echo $this->onehealth_model->getNotifCount($user_name); ?></span><?php } 
                   ?></i></a>
                      <div id="notificationContainer2" class="hide">
                      <div id="notificationTitle2">Notifications</div>
                      <div id="notificationsBody2" class="notifications">
                        <div class="container">
                          <?php
                          // set_cookie("user_id",$user_id);
                          $all_notifs = $this->onehealth_model->getNotifs($user_name);

                            if(is_array($all_notifs)){
                              foreach($all_notifs as $row){
                                $id = $row->id;
                                $sender = $row->sender;
                                $notif_id = $row->id;
                                $notif_title = $row->title;
                                $received = $row->received;
                                $date_sent = $row->date_sent;
                                $time_sent = $row->time_sent;
                                $received = $row->received;
                                
                                $site_url = site_url('onehealth/index/notification/'.$id);
                                
                          ?>
                          <div class="conversation row" <?php if($received == 0){ echo "style='background-color: #D0D0D0;'"; } ?> onclick="window.location.assign('<?php echo $site_url; ?>');">
                            
                            <div class="col-sm-8 message-sender">
                              <p class="sender-name"><?php echo $sender; ?>
                              </p>
                              <span class="last-message"><?php echo $this->onehealth_model->custom_echo($notif_title,80); ?></span>
                            </div>
                            <div class="col-sm-4 time-stamp">
                              <span>
                                <?php echo $this->onehealth_model->getSocialMediaTime($date_sent,$time_sent); ?>
                              </span>
                            </div>
                          </div>
                          <?php } }else{
                            echo "<p class='text-danger'>You Do Not Have Any Notifications</p>";
                          } ?>
                        </div>
                        <div class="text-center">
                          <img src="<?php echo base_url('assets/images/small-loader.gif'); ?>" class="small-loader hide" alt="">
                        </div>
                      </div>

                      <div id="notificationFooter"><a href="#" onclick="seeAll(this,event)">See All(<?php echo $this->onehealth_model->getNotifsNum($user_name); ?>)</a></div>
                      </div>

                  </li>

              </li>
              <li class="nav-item" data-toggle="tooltip" title="Logout">
                <a class="nav-link" href="<?php echo site_url('onehealth/logout') ?>">
                  <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                  <p class="d-lg-none d-md-block">
                    Logout
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>