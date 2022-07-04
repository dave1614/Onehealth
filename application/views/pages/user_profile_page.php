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
    background: #e6ecf0;
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

body.modal-open {
    overflow: hidden;
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

.add-friend-div{
  cursor: pointer;
  position: absolute;
  border: 2px solid white;
  padding: 5px;
  z-index: 100;
  top: 7px;
  left: 10px;
  color: white;

}

.add-message-div{
  cursor: pointer;
  position: absolute;
  border: 2px solid white;
  padding: 5px;
  z-index: 100;
  top: 7px;
  right: 10px;
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
.profile-amt{
  /*color: darkgrey;*/
  font-size: 22px;
  font-weight: 800;
}
.follow-loader{
  width:20px;
  margin-left: 13px;
  display: none;
}
.small-cover-img{
  height: 150px;
  margin-top: -79px;
}
.card-avatar{
  z-index: 1;
  margin-right: 113px;
}
.hide{
  display: none;
}
#trigger-upload {
    color: white;
    background-color: #00ABC7;
    font-size: 14px;
    padding: 7px 20px;
    background-image: none;
}

#fine-uploader-manual-trigger .qq-upload-button {
    margin-right: 15px;
}

#fine-uploader-manual-trigger .buttons {
    width: 36%;
}

#fine-uploader-manual-trigger .qq-uploader .qq-total-progress-bar-container {
    width: 60%;
}
.owl-demo .item img{
    display: block;
    width: 100%;
    height: auto;
}
i.liked{
  color: #e91e63;
}



</style>
<?php
  $is_admin = false;
  $no_logo = true;
  if($user_name == $this->onehealth_model->getUserNameBySlug($addition)){
    $is_admin = true;
  }
  $user_info = $this->onehealth_model->getUserInfoBySlug($addition);
  if(is_array($user_info)){
    foreach($user_info as $user){
      $user_id = $user->id;
      $user_name = $user->user_name;
      $email = $user->email;
      $phone = $user->phone;
      $country_id = $user->country_id;
      $state_id = $user->state_id;
      $address = $user->address;
      $user_slug = $user->slug;
      $cover_photo = $user->cover_photo;
      $date = $user->date;
      $time = $user->time;
      $logo = $user->logo;
    }
  }  

  if(is_null($logo)){
      $no_logo = true;
      $logo_url = base_url('assets/images/avatar.jpg');
      if($is_admin == true){
        
        $logo = "<img id='logo-image' width='100' height='100' class='round img-raised rounded-circle img-fluid logo-link-cont' src='".$logo_url."' rel='tooltip' data-original-title='Change Your Profile Picture' data-toggle='modal' data-target='#change-logo-modal' data-backdrop='false'>";
      }else{
        $logo = "<img id='logo-image' width='100' height='100' class='round img-raised rounded-circle img-fluid' src='".$logo_url."'>";
      }
      
    }else{
      $no_logo = false;
      $logo_url = base_url('assets/images/'.$logo);
      if($is_admin == true){
        $logo = '<img id="logo-image" src="'.$logo_url.'" alt="" width="100" height="100" class="round img-raised rounded-circle img-fluid logo-link-cont" rel="tooltip" data-original-title="Change Your Profile Picture" data-toggle="modal" data-target="#change-logo-modal" data-backdrop="false" style="width:100px; height:100px;">';
      }else{
        $logo = '<img id="logo-image" src="'.$logo_url.'" alt="" width="100" height="100" class="round img-raised rounded-circle img-fluid" style="width:100px; height:100px;">';
      }
    }
?>

<script>
      
    function deletePost (elem,evt) {
      evt.preventDefault();
      var id = elem.parentElement.parentElement.getAttribute("data-id");
      swal({
        title: 'Warning',
        text: "Are You Sure You Want To Delete This Post?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!'
      }).then((result) => {
        $(".spinner-overlay").show();
        $.ajax({
            url : "<?php echo site_url('onehealth/delete_post') ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "post_id="+id,
            success : function (response) {
              if(response.success == true){
                $.notify({
                message:"Post Deleted Successfully"
                },{
                  type : "success"  
                });
                setTimeout(reloadPage,3000);
              }
              $(".spinner-overlay").hide();
            },
            error : function(){
              $(".spinner-overlay").hide();
            }
          }); 
      });
    }

    function addNewImage(id) {
      $("#add-new-post-image-modal").attr("data-id",id);
      $("#add-new-post-image-modal").modal({
        "backdrop" : false,
        "show" : true
      })
    }

    function reloadPage () {
      document.location.reload();
    }

    function submitEditPostContent(elem,evt) {
      evt.preventDefault();
      var id = $("#edit-post-modal").attr("data-id");
      var content = elem.querySelector("textarea").value;
        if(content !== ""){
          $.ajax({
            url : "<?php echo site_url('onehealth/update_post_content') ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "post_id="+id+"&content="+content,
            success : function (response) {
              
              if(response.success == true){
                $.notify({
                message:"Post Updated Successfully"
                },{
                  type : "success"  
                });
              }
              $(".spinner-overlay").hide();
            },
            error : function(){
              $(".spinner-overlay").hide();
            }
          }); 
        }else{
          $.notify({
          message:"Field Cannot Be Empty"
          },{
            type : "warning"  
          });
        }
    }

    function deletePostImage(elem,evt,id,image_name) {
      evt.preventDefault();
       swal({
        title: 'Warning',
        text: "Are You Sure You Want To Delete This Image?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!'
      }).then((result) => {
        $(".spinner-overlay").show();
        $.ajax({
          url : "<?php echo site_url('onehealth/delete_post_image') ?>",
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "post_id="+id+"&image_name="+image_name,
          success : function (response) {
            console.log(response)
            
            if(response.success == true){
              $("#edit-post-modal .row").html(response.messages);
            }
            $(".spinner-overlay").hide();
          },
          error : function(){
            $(".spinner-overlay").hide();
          }
        }); 
      });  
    }

    function editPost(elem,evt) {
      evt.preventDefault();
      var id = elem.parentElement.parentElement.getAttribute("data-id");
      
      // $("#choose-action-modal").modal("hide");
      
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/get_post_for_edit') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "post_id="+id,
        success : function (response) {
          console.log(response)
          
          if(response.success == true && response.post_content !== ""){
            elem.parentElement.parentElement.setAttribute("data-id","");
            // $("#choose-action-modal").modal("hide");
            $("#edit-post-modal textarea").val(response.post_content);
            $("#edit-post-modal .row").html(response.messages);
            $("#edit-post-modal").attr("data-id",id);
            $("#edit-post-modal").modal({
              "backdrop" : false,
              "show" : true
            });
          }
          $(".spinner-overlay").hide();
        },
        error : function(){
          $(".spinner-overlay").hide();
        }
      }); 
    }

    function moreButton (elem,id) {
      $("#choose-action-modal").modal({
        "backdrop" : false,
        "show" : true
      });
      $("#choose-action-modal .modal-body ul.list-group").attr("data-id",id);
    }

    function changeActiveTab(type) {
      switch (type) {
        case "posts":
          setCookie("active_profile_tab","posts",100000000000000000000);
          break;
        case "followers":
          setCookie("active_profile_tab","followers",100000000000000000000);
          break;
        case "following":
          setCookie("active_profile_tab","following",100000000000000000000);
          break;
        
        default:
          
          break;
      }

    }

    function loadMoreComments (elem) {
      var comment_section = elem.parentElement.parentElement.querySelector(".comment-section");
      var first_comment = comment_section.firstElementChild.getAttribute("id");
      // console.log(first_comment);
      var loader = elem.parentElement.querySelector("img.loader-img");
      var comment_id = first_comment.substr(0, first_comment.length-8);


      removeClass(loader,"hide");
      $.ajax({
        url : "<?php echo site_url('onehealth/load_more_comments') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "offset="+comment_id,
        success : function (response) {
          console.log(response)
          addClass(loader,"hide");
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $(messages).prependTo(comment_section);
            if(response.last_batch == true){
              addClass(elem,"hide");
            }
          }
        },
        error : function(){
          addClass(loader,"hide");
        }
      });  
    }
    
    function getMorePostContent(elem) {
      var small_content = elem.parentElement.querySelector(".small-content");
      var big_content = elem.parentElement.querySelector(".big-content");
      addClass(small_content,"hide");
      
      removeClass(big_content,"hide");
     
      addClass(elem,"hide");
    }

    function comment (elem) {
      var comment_input = elem.parentElement.parentElement.parentElement.querySelector(".add-comment-section textarea");
      comment_input.focus();
    }

    function commentInputKeypress (evt,elem,id) {
      if(elem.value !== ""){
        if(evt.which == 13){
          elem.value = elem.value.replace(/^\s*$(?:\r\n?|\n)/gm,"");
          var content = elem.value;
          var small_loader = elem.parentElement.querySelector("img.loader-img");
          var comment_section = elem.parentElement.parentElement.parentElement.parentElement.querySelector(".comment-section");
          <?php 
            $main_user_name = $this->onehealth_model->getUserNameById($main_user_id);
          ?>
          var user_name = "<?php echo $main_user_name; ?>";
          var user_url = "<?php echo site_url('onehealth/'.$main_user_name); ?>";
          removeClass(small_loader,"hide");
          addClass(elem,"disabled");
          $.ajax({
            url : "<?php echo site_url('onehealth/comment') ?>",
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : "post_id="+id+"&content="+content,
            success : function(response){
              removeClass(elem,"disabled");
              addClass(small_loader,"hide");
              if(response.success == true){
                var val = $('<h6><a href="'+user_url+'" style="font-size: 14px; font-weight: 600;">'+user_name+'</a>&nbsp;&nbsp;<small>'+content+'</small></h6>');
                
                val.appendTo(comment_section);
                elem.value = "";
              }
            },error : function () {
              addClass(small_loader,"hide");
              removeClass(elem,"disabled");
            }
          });  
        }
      }
    }
    
    function likePost (elem,id) {
      var i = elem.querySelector("i");
      var temp_likes_elem = elem.parentElement.parentElement.querySelector(".post-likes-temp");
      var temp_likes = elem.parentElement.parentElement.querySelector(".post-likes-temp").innerHTML;
      var likes_num = elem.parentElement.parentElement.querySelector(".likes-num");
      addClass(i,"liked");
      elem.setAttribute("onclick", "unlikePost(this,"+id+")");
      temp_likes++;
      if(temp_likes > 1){
        likes_num.innerHTML = temp_likes + " likes";
      }else if(temp_likes == 1){
        likes_num.innerHTML = temp_likes + " like";
      }
      temp_likes_elem.innerHTML = temp_likes;
      $.ajax({
        url : "<?php echo site_url('onehealth/like_post') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "post_id="+id,
        success : function(response){
          console.log(response)

        },error : function () {
          
        }
      });  
    }

    function unlikePost (elem,id) {
      var i = elem.querySelector("i");
      var temp_likes_elem = elem.parentElement.parentElement.querySelector(".post-likes-temp");
      var temp_likes = elem.parentElement.parentElement.querySelector(".post-likes-temp").innerHTML;
      var likes_num = elem.parentElement.parentElement.querySelector(".likes-num");
      removeClass(i,"liked");
      elem.setAttribute("onclick", "likePost(this,"+id+")");
      temp_likes--;
      if(temp_likes > 1){
        likes_num.innerHTML = temp_likes + " likes";
      }else if(temp_likes == 1){
        likes_num.innerHTML = temp_likes + " like";
      }else if(temp_likes == 0){
        likes_num.innerHTML = "No Likes";
      }
      $.ajax({
        url : "<?php echo site_url('onehealth/unlike_post') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "post_id="+id,
        success : function(response){
          console.log(response)

        },error : function () {
          
        }
      }); 
      temp_likes_elem.innerHTML = temp_likes;
    }

    function socialMediaFormatNum (num) {
      if(num >= 1000 && num < 1000000){
        num = round(num / 1000,2);
        num = num + 'K';
      }else if(num >= 1000000 && num < 1000000000){
        num = round(num / 1000000,2);
        num = num + 'M';
      }else if(num >= 1000000000 && num < 1000000000000){
        num = round(num / 1000000000,2);
        num = num + 'B';
      }else if(num >= 1000000000000){
        num = round(num / 1000000000000,2);
        num = num + 'T';
      }
      return num;
    }

    function round (value, precision, mode) {
      var m, f, isHalf, sgn // helper variables
      // making sure precision is integer
      precision |= 0
      m = Math.pow(10, precision)
      value *= m
      // sign of the number
      sgn = (value > 0) | -(value < 0)
      isHalf = value % 1 === 0.5 * sgn
      f = Math.floor(value)

      if (isHalf) {
        switch (mode) {
          case 'PHP_ROUND_HALF_DOWN':
          // rounds .5 toward zero
            value = f + (sgn < 0)
            break
          case 'PHP_ROUND_HALF_EVEN':
          // rouds .5 towards the next even integer
            value = f + (f % 2 * sgn)
            break
          case 'PHP_ROUND_HALF_ODD':
          // rounds .5 towards the next odd integer
            value = f + !(f % 2)
            break
          default:
          // rounds .5 away from zero
            value = f + (sgn > 0)
        }
      }

      return (isHalf ? value : Math.round(value)) / m
    }

    // console.log(socialMediaFormatNum(1000000000000));

    function followUser (elem,user_id,type = true) {
      var loader = elem.querySelector("img.follow-loader");
      var loader_url = '<?php echo base_url("assets/images/ajax-loader.gif"); ?>';
      loader.style.display = 'inline-block';
      addClass(elem,"disabled");
      $.ajax({
        url : "<?php echo site_url('onehealth/follow') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "user_id="+user_id,
        success : function(response){
          if(response.success == true){
            removeClass(elem,"disabled");
            loader.style.display = 'none';
            elem.setAttribute("onclick","unfollowUser(this,"+user_id+")")
            elem.innerHTML = 'Following <img class="follow-loader" src="'+loader_url+'" alt=""> <div class="ripple-container"></div>';
            if(type == true){
              var temp_followers = Number($(".followers_temp").html());
              var new_followes_num = temp_followers + 1;
              $(".followers_temp").html(new_followes_num);
              var new_followes_num = socialMediaFormatNum(new_followes_num);
              $(".follower-num").html(new_followes_num);
            }
          }else{
            loader.style.display = 'none';
            removeClass(elem,"disabled");
          }
        },
        error: function () {
          removeClass(elem,"disabled");
          loader.style.display = 'none';
        }
      });
    }

     function unfollowUser (elem,user_id,type) {
      var loader = elem.querySelector("img.follow-loader");
      var loader_url = '<?php echo base_url("assets/images/ajax-loader.gif"); ?>';
      loader.style.display = 'inline-block';
      addClass(elem,"disabled");
      $.ajax({
        url : "<?php echo site_url('onehealth/unfollow') ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "user_id="+user_id,
        success : function(response){
          console.log(response)
          if(response.success == true){
            removeClass(elem,"disabled");
            loader.style.display = 'none';
            elem.setAttribute("onclick","followUser(this,"+user_id+")")
            elem.innerHTML = 'Follow <img class="follow-loader" src="'+loader_url+'" alt=""> <div class="ripple-container"></div>';
            if(type == true){
              var temp_followers = Number($(".followers_temp").html());
              var new_followes_num = temp_followers - 1;
              $(".followers_temp").html(new_followes_num);
              var new_followes_num = socialMediaFormatNum(new_followes_num);
              if(new_followes_num == 0){ new_followes_num = "no followers"; }
              $(".follower-num").html(new_followes_num);
            }
          }else{
            removeClass(elem,"disabled");
            loader.style.display = 'none';
          }
        },
        error: function () {
          removeClass(elem,"disabled");
          loader.style.display = 'none';
        }
      });
    }

  

   function displayRegisteredUsers(elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/get-first-registered-users'); ?>";
    $("#offset").html("7");
    $("#registeredPatientsModal .modal-body tbody").html("");
    $.ajax({
      url : url,
      type : "POST",
      responseType : "text",
      dataType : "text",
      data : "",
      success : function(response){
        if(response !== ""){
          $("#registeredPatientsModal .modal-body tbody").html(response);
          $("#registeredPatientsModal").modal({
            "backdrop" : false,
            "show" : true
          })
        }else{
           
        }
      },error : function () {
        
      }
    });    
  }
  <?php if($is_admin){ ?>

   function submitPostImage (elem,evt) {
    evt.preventDefault()
    var id = $("#add-new-post-image-modal").attr("data-id");
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
        setCookie("post_id",id);
          $.ajax({
            url : change_facility_logo,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data ,
            contentType : false,
            cache : false,
            processData : false,
            success : function (response) {
            
              $(".spinner-overlay").hide();
              if(response.max == true){
                 $.notify({
                message:"Max Number Of Images Reached"
                },{
                  type : "danger"  
                });
              }
              else if(response.success == true && response.new_image_name !== ""){
                var new_image_name = response.new_image_name;
                $.notify({
                message:"Image Uploaded Successfully"
                },{
                  type : "success"  
                });
                
                 $(".spinner-overlay").show();
                 $.ajax({
                  url : "<?php echo site_url('onehealth/get_all_post_images'); ?>",
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "post_id="+id,
                  success : function(response){
                    $(".spinner-overlay").hide();
                    if(response.success == true){
                      $("#add-new-post-image-modal").modal('hide');

                      $("#edit-post-modal .row").html(response.messages);
                      setTimeout(function () {
                        document.body.classList.add("modal-open")
                      },1000);
                    }
                  },
                  error : function () {
                    $(".spinner-overlay").hide();
                  } 
                });  
                
              }else if (response.success == false && response.errors !== "") {
                $.notify({
                message:"Image Upload Was Unsuccessful"
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
                $(".logo-link-cont").append('<img id="logo-image" src="' + new_image_name + '" alt="" width="100" class="round img-raised rounded-circle img-fluid" height="100" style="width:100px; height:100px;" data-toggle="modal" data-target="#change-logo-modal" data-backdrop="false">');
                $(".sidebar-wrapper .user .photo").html('<img src="' + new_image_name + '" class="small-profile-img" />')
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
                message:"Cover Photo Changed Successfully"
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

function onScroll() {
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
      var active_profile_tab = getCookie("active_profile_tab");
      switch (active_profile_tab) {
        case "posts":
          var posts_container = document.querySelector(".posts-container");
          var posts_num = posts_container.children.length;
          var user = <?php echo $user_id; ?>;
          if(posts_num >= 2){
            var loader = $("#posts .big-loader");
            var offset = posts_container.lastElementChild.querySelector(".post-card").getAttribute("id");
            offset = offset.substr(0, offset.length-5);
            loader.show();
            $.ajax({
              url : "<?php echo site_url('onehealth/index/load_more_posts') ?>",
              type : "POST",
              responseType : "json",
              dataType : "json",
              async : false,
              data : "offset="+offset+"&this_user="+user,
              success : function (response) {
                console.log(response)
                loader.hide();
                if(response.success == true && response.messages !== ""){
                  var messages = response.messages;
                  $(messages).appendTo(posts_container);
                  $(".owl-demo").owlCarousel({

                      // navigation : true, // Show next and prev buttons
                      slideSpeed : 300,
                      paginationSpeed : 400,
                      singleItem:true
                 
                      // "singleItem:true" is a shortcut for:
                      // items : 1, 
                      // itemsDesktop : false,
                      // itemsDesktopSmall : false,
                      // itemsTablet: false,
                      // itemsMobile : false
                 
                  });
                  if(response.last_batch == true){
                    // loader.hide();
                  }
                }
              },
              error : function(){
                loader.hide();
              }
            }); 
            console.log(offset)
          }
          console.log(posts_num);
          break;
        case "followers":
          
          break;
        case "following":
          
          break;
        
        default:
          alert("An Error Just Occurred. Please Reload The Page");
          break;
      }
    }
  }

function showPostLikes(elem,post_id){
    if(!isNaN(post_id)){
      var post_likes = Number(elem.parentElement.querySelector(".post-likes-temp").innerHTML);
      if(post_likes > 0){
        var url = "<?php echo site_url('onehealth/get_users_who_liked_post'); ?>";
        $(".spinner-overlay").show();
        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'json',
          data: {post_id: post_id},
        })
        .done(function(response) {
          console.log(response);
          $(".spinner-overlay").hide();
          if(response.success){
            var messages = response.messages;
            if(messages !== ""){
              var ret_str = "";
              for(var i = 0; i < messages.length; i++){
                var user_info = messages[i];
                var user_name = user_info.user_name;
                var user_id = user_info.user_id;
                var you = user_info.you;
                var profile_url = user_info.profile_url;
                var following = user_info.following;
                ret_str += '<tr><td style="border: 0;">';
                if(!you){
                  ret_str += '<a href="' + profile_url + '">' + user_name + '</a>';
                }else{
                  ret_str += '<p class="text-rose">' + user_name + '</p>';
                }
                ret_str += '</td>';
                if(!you){
                  ret_str += '<td style="float: right; border: 0;"><button style="padding: 9px 23px;" class="btn btn-rose btn-round justify-content-center"'; 
                  if(!following){
                    ret_str += 'onclick="followUser(this,' + user_id + ',false)">Follow <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt="Loading..."> </button></td>';
                  }else{
                    ret_str += 'onclick="unfollowUser(this,' + user_id + ',false)">Following <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt="Loading..."> </button></td>';
                  }
                       
                }
                ret_str += '</tr>';
              }
              console.log(ret_str)
              $("#showLikesModal .modal-body tbody").html(ret_str);
              $("#showLikesModal").modal({
                "show" : true
              });
            }
          }
        })
        .fail(function() {
          $(".spinner-overlay").hide();
        })     
      }
    }
    
  }
 
</script>
<script type="text/template" id="qq-template-manual-trigger">
  <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
      <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
          <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
      </div>
      <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
          <span class="qq-upload-drop-area-text-selector"></span>
      </div>
      <div class="buttons">
          <div class="qq-upload-button-selector qq-upload-button btn-primary">
              <div>Select Images</div>
          </div>
          
      </div>
      <span class="qq-drop-processing-selector qq-drop-processing">
          <span>Processing dropped files...</span>
          <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
      </span>
      <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
          <li>
              <div class="qq-progress-bar-container-selector">
                  <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
              </div>
              <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
              <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
              <span class="qq-upload-file-selector qq-upload-file"></span>
              <span class="qq-edit-filename-icon-selector" aria-label="Edit filename"><i class="fas fa-edit"></i></span>
              <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
              <span class="qq-upload-size-selector qq-upload-size"></span>
              <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
              <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
              <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
              <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
          </li>
      </ul>

      <dialog class="qq-alert-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Close</button>
          </div>
      </dialog>

      <dialog class="qq-confirm-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">No</button>
              <button type="button" class="qq-ok-button-selector">Yes</button>
          </div>
      </dialog>

      <dialog class="qq-prompt-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <input type="text">
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Cancel</button>
              <button type="button" class="qq-ok-button-selector">Ok</button>
          </div>
      </dialog>
  </div>
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
            <?php }else{ ?>
            
             <div class="add-message-div" onclick="register_popup(<?php echo $user_id; ?>, '<?php echo $user_name; ?>','<?php echo site_url('onehealth/get_messages') ?>','<?php echo site_url('onehealth/get_status') ?>','<?php echo base_url('assets/audio/notif-sound.mp3') ?>','<?php echo site_url('onehealth/send_message') ?>','<?php echo site_url('onehealth/get_last_chats') ?>','<?php echo site_url('onehealth/index/'.$user_id.'/messaging') ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>')">
              <i class="fab fa-facebook-messenger"></i>
              &nbsp;&nbsp; Send Message
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
                                  <?php echo $logo; ?>
                                <?php if($is_admin == true){ ?>
                                  </a>
                                <?php } ?>  
                                  
                              </div>
                              <?php  
                                if($no_logo == true && $is_admin == true){
                              ?>
                                <span class="form-error">Please Add A Picture To Help Users Identify Your Page</span>
                              <?php
                                }
                              ?>
                              <div class="name">
                                <h3 class="title"><?php echo $user_name; ?></h3>
                                <?php
                                if(!$is_admin){
                                if($this->onehealth_model->checkIfUserIsFollowingUser($main_user_id,$user_id)){ ?>
                                <button class="btn btn-rose btn-round justify-content-center" onclick="followUser(this,<?php echo $user_id; ?>)">Follow <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>
                                <?php }else{ ?>
                                  <button class="btn btn-rose btn-round justify-content-center" onclick="unfollowUser(this,<?php echo $user_id; ?>)">Following <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>

                                <?php } } ?>
                                <div class="row" style="margin-top: 50px;">
  
                                  <?php if($is_admin){ ?>
                                  <a href='<?php echo site_url("onehealth/index/".$addition.'/'."edit-profile") ?>' class="btn btn-primary btn-round col-sm-12">Edit Profile</a>
                                  <?php } ?>
                                  
                                </div>
                               <!--  <div class="social-cont">
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
                    
                    <div class="col-sm-12">
                      <ul class="nav nav-pills nav-pills-primary justify-content-center" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" onclick="changeActiveTab('posts');" data-toggle="tab" href="#posts" role="tablist">
                            <small class="col-xs-12">Posts</small>
                            <?php  
                              $posts_num = $this->onehealth_model->getUserTotalPostsNum($user_id);
                              if($posts_num == 0){
                                $posts_num = "No Posts";
                              }else{
                                $posts_num = $this->onehealth_model->socialMediaFormatNum($posts_num);
                              }
                            ?>
                            <p class='profile-amt post-num'><?php echo $posts_num; ?></p>
                            <p class="posts_temp hide"><?php echo $this->onehealth_model->getUserTotalPostsNum($user_id); ?></p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" onclick="changeActiveTab('followers');" data-toggle="tab" href="#followers" role="tablist">
                            <small class="col-xs-12">Followers</small>
                            <?php  
                              $followers_num = $this->onehealth_model->getUserTotalFollowersNum($user_id);
                              if($followers_num == 0){
                                $followers_num = "No Followers";
                              }else{
                                $followers_num = $this->onehealth_model->socialMediaFormatNum($followers_num);
                              }
                            ?>
                            <p class='profile-amt follower-num'><?php echo $followers_num; ?></p>
                            <p class="followers_temp hide"><?php echo $this->onehealth_model->getUserTotalFollowersNum($user_id); ?></p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" onclick="changeActiveTab('following');" data-toggle="tab" href="#following" role="tablist">
                            <small class="col-xs-12">Following</small>
                            <?php  
                              $following_num = $this->onehealth_model->getUserTotalFollowingNum($user_id);
                              if($following_num == 0){
                                $following_num = 0;
                              }else{
                                $following_num = $this->onehealth_model->socialMediaFormatNum($following_num);
                              }
                            ?>
                            <p class='profile-amt following-num'><?php echo $following_num; ?></p>
                            <p class="following_temp hide"><?php echo $this->onehealth_model->getUserTotalFollowingNum($user_id); ?></p>
                          </a>
                        </li>
                        
                      </ul>
                      <div class="tab-content tab-space"  style="margin-top: 30px;">
                        <div class="tab-pane active row" id="posts">
                          <?php if($is_admin){ ?>
                          <div class="text-center">
                            <button type="button" class="btn btn-rose btn-round" data-backdrop='false' data-toggle="modal" data-target="#makePostModal">
                              Make Post
                            </button>
                          </div>

                          <?php } ?>
                          <div class="text-center posts-container">
                            <?php 
                            $posts_by_user = $this->onehealth_model->getFirstTenPostsByUser($user_id);


                            if(is_array($posts_by_user)){
                              // $posts_by_user = array_reverse($posts_by_user);
                              foreach($posts_by_user as $row){
                                $post_id = $row->id;
                                $post_content = $row->content;
                                $images = $row->images;
                                $image_arr = explode(",", $images);
                                $likes = $row->likes;
                                $slug = $row->slug;
                                $date = $row->date;
                                $time = $row->time;
                                $sender = $row->sender;
                                $sender_username = $this->onehealth_model->getUserNameById($sender);
                                $likes_num = 0;
                                if($likes !== ""){
                                  $likes_arr = explode(",", $likes);
                                  if(!empty($likes_arr)){
                                    $likes_arr = array_unique($likes_arr);
                                    $this->onehealth_model->updatePostLikes($likes_arr,$post_id);
                                    $likes_num = count($likes_arr);
                                  }
                                }
                            ?>
                            <div class="row justify-content-center">
                              
                              <div class="card post-card col-sm-7" id="<?php echo $post_id; ?>-post">
                                <div class="card-header text-left">
                                  <div class="text-left" style="display: inline-block;">
                                    <h4 class="card-title text-left"><img src="<?php echo $this->onehealth_model->getUserLogoById($sender); ?>" alt="">
                                    <span><?php echo $user_name; ?></span></h4>
                                  </div>
                                  
                                  <div class="text-right float-right" onclick="moreButton(this,<?php echo $post_id; ?>,'<?php echo $slug; ?>')" style="cursor: pointer; display: inline-block;">
                                    <i class="fas fa-ellipsis-h"></i>
                                  </div>
                                  
                                </div>
                                <div class="card-body" >
                                  <?php if(!empty($image_arr) && $images !== ""){ ?>
                                  <div id="" class="owl-carousel owl-theme owl-demo">
                                    <?php for($i = 0; $i < count($image_arr); $i++){ 
                                      $current_image = $image_arr[$i];
                                    ?>
                                    <div class="item"><img src="<?php echo base_url('assets/images/'.$current_image); ?>" alt="<?php echo $post_content; ?>"></div>
                                    
                                    <?php } ?>                                 
                                  </div>
                                  <?php } ?>

                                  <div class="action-buttons-panel text-left" style="">
                                    <div class="row">
                                      <div class="col-2 col-xs-2 like-btn" onclick="<?php if($this->onehealth_model->checkIfUserHasAlreadyLikedPost($main_user_id,$post_id)){  ?>likePost(this,<?php echo $post_id; ?>) <?php }else{ ?> unlikePost(this,<?php echo $post_id; ?>) <?php } ?> ">
                                        <i class="far fa-heart <?php if(!$this->onehealth_model->checkIfUserHasAlreadyLikedPost($main_user_id,$post_id)){ echo 'liked'; } ?>"></i>
                                      </div>
                                      <div class="col-2 col-xs-2 comment-btn" onclick="comment(this)">
                                        <i class="far fa-comment"></i>
                                      </div>
                                      <?php if($main_user_name !== $sender_username){ ?>
                                        <div class="col-2 col-xs-2 share-btn" style="" onclick="sharePost(this,<?php echo $post_id; ?>)">
                                          <i class="far fa-share-square"></i>
                                        </div>
                                      <?php } ?>
                                    </div>
                                    <p class="likes-num text-left" onclick="showPostLikes(this,<?php echo $post_id; ?>)"><?php if($likes_num == 0){ echo "No Likes"; } else{ if($likes_num == 1){ echo $likes_num . ' like'; }else{ echo $likes_num . ' likes'; } }  ?></p>
                                    <span class="post-likes-temp hide"><?php echo $likes_num; ?></span>
                                  </div>
                                  <div class="post-content text-left">
                                    <h6><a style="text-transform: lowercase;" href="<?php echo site_url('onehealth/index/'.$sender_username); ?>" style="font-size: 14px; font-weight: 600;"><?php echo $sender_username;  ?></a>&nbsp;&nbsp;<small><?php if(strlen($post_content) >= 60){ ?><span class="small-content"><?php echo $this->onehealth_model->custom_echo($post_content,60); ?></span><span class="hide big-content"><?php echo $post_content; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='content-more-btn' onclick="getMorePostContent(this)">more</span><?php }else{ ?> <span><?php echo $post_content; ?></span> <?php } ?></small></h6>
                                  </div>
                                  <div class="get-more-comments text-left">
                                    <?php
                                      if($this->onehealth_model->getNoOfCommentsInPost($post_id) !== false){
                                        if($this->onehealth_model->getNoOfCommentsInPost($post_id) > 5){
                                          echo "<span class='more-comments-btn' onclick='return loadMoreComments(this,".$post_id.")'>view more comments(".$this->onehealth_model->getNoOfCommentsInPost($post_id).")</span>";
                                          echo  '<img class="loader-img hide" src="'.base_url('assets/images/loading.gif').'" alt="">';
                                        }
                                      }
                                    ?>
                                  </div>
                                  <div class="comment-section text-left">
                                    
                                    <?php
                                      $comments_on_post = $this->onehealth_model->getFirstFiveCommentsOnPost($post_id);
                                      if(is_array($comments_on_post)){
                                        $comments_on_post = array_reverse($comments_on_post); 
                                        foreach($comments_on_post as $row){
                                          $comment_id = $row->id;
                                          $comment_content = $row->content;
                                         
                                          $likes = $row->likes;
                                          $sender = $row->sender;
                                          $sender_username = $this->onehealth_model->getUserNameById($sender);
                                          $likes_num = 0;
                                          if($likes !== ""){
                                            $likes_arr = explode(",", $likes);
                                            if(!empty($likes_arr)){
                                              $likes_arr = array_unique($likes_arr);
                                              $this->onehealth_model->updateCommentLikes($likes_arr,$comment_id);
                                              $likes_num = count($likes_arr);
                                            }
                                          }
                                    ?>
                                    <h6 id="<?php echo $comment_id; ?>-comment"><a href="<?php echo site_url('onehealth/index/'.$sender_username); ?>" style="font-size: 14px; font-weight: 600;"><?php echo $sender_username; ?></a>&nbsp;&nbsp;<small><?php echo $comment_content; ?></small></h6>
                                    <?php } } ?>
                                    
                                  </div>
                                  <div class="add-comment-section">
                                    <form>
                                      <div class="form-group">
                                        <textarea style="padding-bottom: 3px;" onkeypress="return commentInputKeypress (event,this,<?php echo $post_id; ?>);" autocomplete="off" name="message"  placeholder="Add A Comment..." class="form-control comment-input col-sm-10">
                                        </textarea>
                                        <div class="text-right col-sm-1">
                                          <img class="loader-img hide" src="<?php echo base_url('assets/images/loading.gif'); ?>" alt="">
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                  <div class="text-left">
                                    <span class="post-time-display"><?php echo $this->onehealth_model->getSocialMediaTime($date,$time); ?></span>
                                  </div>
                                </div>
                              </div>
                             
                            </div>
                          <?php } } ?>
                          </div>
                          <div class="text-center">
                            <img class="big-loader" src="<?php echo base_url('assets/images/loading.gif'); ?>" alt="">
                          </div>
            
                        </div>
                        
                        <div class="tab-pane" id="followers">
                          <?php
                            $followers_arr = $this->onehealth_model->getUserTotalFollowers($user_id);
                            if(is_array($followers_arr)){
                            for($i = 0; $i < count($followers_arr); $i++){
                          ?>
                          <div class="card card-profile col-sm-4" style="display:inline-block; max-width: 270px;">

                            <div class="card-avatar" style="z-index: 1;">
                              <a href="#pablo">
                                <img class="img" src="<?php echo base_url('assets/images/'.$this->onehealth_model->getUserLogoById1($followers_arr[$i])); ?>">
                              </a>
                            </div>
                            
                            <div class="card-body">
                              <!-- <h6 class="card-category text-gray">CEO / Co-Founder</h6> -->
                              <h4 class="card-title"><?php if($followers_arr[$i] !== $main_user_id){ echo $this->onehealth_model->getUserNameById($followers_arr[$i]); }else{ echo "you"; } ?></h4>
                              <p class="card-description">
                                <?php  echo $this->onehealth_model->custom_echo($this->onehealth_model->getUserBioById($followers_arr[$i]),50); ?>
                              </p>
                              <?php
                              if($followers_arr[$i] !== $main_user_id){
                               if($this->onehealth_model->checkIfUserIsFollowingUser($main_user_id,$followers_arr[$i])){ ?>
                                <button class="btn btn-rose btn-round justify-content-center" onclick="followUser(this,<?php echo $followers_arr[$i]; ?>,false)">Follow <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>
                                <?php }else{ ?>
                                  <button class="btn btn-rose btn-round justify-content-center" onclick="unfollowUser(this,<?php echo $followers_arr[$i]; ?>,false)">Following <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>
                                <?php } } ?>
  
                            </div>
                          </div>                  
                          <?php }  }?>
                          <div class="text-center">
                            <img class="big-loader" src="<?php echo base_url('assets/images/loading.gif'); ?>" alt="">
                          </div>
                        </div>
                        <div class="tab-pane" id="following">
                         <?php
                            $followers_arr = $this->onehealth_model->getUserTotalFollowing($user_id);
                            if(is_array($followers_arr)){
                            for($i = 0; $i < count($followers_arr); $i++){
                          ?>
                          <div class="card card-profile col-sm-4" style="display:inline-block; max-width: 270px;">

                           <div class="card-avatar" style="z-index: 1;">
                              <a href="#pablo">
                                <img class="img" src="<?php echo base_url('assets/images/'.$this->onehealth_model->getUserLogoById1($followers_arr[$i])); ?>">
                              </a>
                            </div>
                            
                            <div class="card-body">
                              <!-- <h6 class="card-category text-gray">CEO / Co-Founder</h6> -->
                              <h4 class="card-title"><?php if($followers_arr[$i] !== $main_user_id){ echo $this->onehealth_model->getUserNameById($followers_arr[$i]); }else{ echo "you"; } ?></h4>
                              <p class="card-description">
                                <?php  echo $this->onehealth_model->custom_echo($this->onehealth_model->getUserBioById($followers_arr[$i]),50); ?>
                              </p>
                              <?php
                              if($followers_arr[$i] !== $main_user_id){
                               if($this->onehealth_model->checkIfUserIsFollowingUser($main_user_id,$followers_arr[$i])){ ?>
                                <button class="btn btn-rose btn-round justify-content-center" onclick="followUser(this,<?php echo $followers_arr[$i]; ?>,false)">Follow <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>
                                <?php }else{ ?>
                                  <button class="btn btn-rose btn-round justify-content-center" onclick="unfollowUser(this,<?php echo $followers_arr[$i]; ?>,false)">Following <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt=""></button>
                                <?php } } ?>
  
                            </div>
                          </div>                    
                          <?php } } ?>
                          <div class="text-center">
                            <img class="big-loader" src="<?php echo base_url('assets/images/loading.gif'); ?>" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                    
                </div>
              </div>
            </div>

            <div class="modal fade" id="showLikesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Likes</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="padding-top: 0;">
                    <table class="table">
                      <thead style="display: none;">
                        <tr>
                          <th>Username</th>
                          <th>Follow Button</th>
                        </tr>
                      </thead>
                      <tbody>                        
                        <?php for($i = 0; $i < 200; $i++){ ?>
                        <tr>
                          <td style="border: 0; padding: 0;"><a href="/">admin</a></td>
                          <td style="float: right; border: 0; padding: 0"><button style="padding: 9px 23px;" class="btn btn-rose btn-round justify-content-center" onclick="followUser(this,119,false)">Follow <img class="follow-loader" src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>" alt="Loading..."> </button></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    
                  </div>
                </div>
              </div>
            </div>
  

            <?php if($is_admin){ ?>
            <!-- Modal -->
            <div class="modal fade" id="makePostModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Make Post</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                   <?php
                    $attr = array('id' => 'post-form');
                    echo form_open('',$attr); 
                   ?>
                   <div class="form-group col-sm-12">
                     
                     <textarea class="form-control" placeholder="Enter Your Post Content Here....." name="post" id="post-text-area" rows="3">
                       
                     </textarea>
                   </div>
                   </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="upload">Upload</button>
                  </div>
                </div>
              </div>
            </div>
            <div id="change-logo-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <?php $attr = array('id' => 'change_facility_form','onsubmit' => 'return submitImage(this,event)'); ?>
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
                        <span class="form-error">1. Allowed Types: GIF,PNG,JPG,JPEG</span>
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
                    <?php echo form_open_multipart("onehealth/index/".$addition."/change_user_cover_photo",$attr); ?>
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
                        <span class="form-error">1. Recommended Dimensions: 851 X 350</span><br>
                        <span class="form-error">2. Allowed Types: GIF,PNG,JPG,JPEG</span>
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

            <?php if($is_admin){ ?>
              <div id="choose-action-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                  
                  <div class="modal-header" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <h5 class="modal-title text-rose">Choose Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                     
                    <ul class="list-group">    
                      <li class="list-group-item"><a href="#" onclick="editPost(this,event)">Edit Post</a></li>                      
                      <li class="list-group-item"><a href="#" class="text-danger" onclick="deletePost(this,event)">Delete Post</a></li>
                    </ul>  
                      
                  </div>
                  
                  <div class="modal-footer text-center">
                    
                  </div>
                </div>
              </div>
            </div>

            <div id="edit-post-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  
                  <div class="modal-header">
                    <h5 class="modal-title text-rose">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                     <?php  
                      $attr = array('id' => 'edit-post-form','onsubmit' => 'submitEditPostContent(this,event)');
                      echo form_open('',$attr);
                     ?>
                     <div class="form-group col-sm-12">
                     
                       <textarea class="form-control" value="" name="post" id="post-text-area" rows="3">
                         
                       </textarea>
                     </div>
                     <input type="submit" class="btn btn-success">
    
                    </form>
                    <div class="row">
                       
                    </div>
                  </div>
                  
                  <div class="modal-footer text-center">
                    
                  </div>
                </div>
              </div>
            </div>

            <div id="add-new-post-image-modal" class="modal fade text-center" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <?php $attr = array('id' => 'add-new-post-image-form','onsubmit' => 'return submitPostImage(this,event)'); ?>
                    <?php echo form_open_multipart("onehealth/add-new-post-image",$attr); ?>
                  <div class="modal-header">
                    <h3 class="modal-title">Select Image</h3>
                    <button type="button" class="close" onclick="reloadPage()" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    
                      <input type="file" name="image" id="add-new-post-image-input" class="inputfile inputfile-1" accept="image/*"/>
                      <label for="add-new-post-image-input"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                      <div class="text-left">
                        
                        <span class="form-error">2. Max Dimensions: 1400 X 1400</span><br>
                        <span class="form-error">3. Allowed Types: GIF,PNG,JPG,JPEG</span>
                      </div>

                  </div>
                  <div class="modal-footer">
                    <button onclick="reloadPage()" class="btn btn-default">Close</button>
                    <button type="submit" class="btn btn-primary text-right">Upload</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            <?php } ?>
            
        </div>
      </div>
    
      <span style="display: none;" id="offset">7</span>  
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      <script>
        $(document).ready(function() {

          $(document.body).on('touchmove', onScroll);
          $(window).on('scroll',onScroll);
          setCookie("active_profile_tab","posts",100000000000000000000);
          $("#post-form #post-text-area").val("");
          $(".comment-input").val("");
          $(".owl-demo").owlCarousel({
         
              // navigation : true, // Show next and prev buttons
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem:true
         
              // "singleItem:true" is a shortcut for:
              // items : 1, 
              // itemsDesktop : false,
              // itemsDesktopSmall : false,
              // itemsTablet: false,
              // itemsMobile : false
         
          });
          $('#makePostModal .modal-body').fineUploader({
              template: 'qq-template-manual-trigger',
              // inputName : "image",
              validation : {
                allowedExtensions : ["gif","png","jpeg","jpg"],
                
                image : {
                  
                  minHeight : 100,
                  minWidth : 100
                },
                itemLimit : 5
              },

              request: {
                  endpoint: '<?php echo site_url('onehealth/index/upload_post_images'); ?>',
                  params : {
                      'upload_image' : true
                  }
              },
              thumbnails: {
                  placeholders: {
                      waitingPath: '<?php echo base_url('assets/images/waiting-generic.png'); ?>',
                      notAvailablePath: '<?php echo base_url('assets/images/not_available-generic.png'); ?>'
                  }
              },
              messages : {
                noFilesError : "You Have Not Selected Any Files But Your Post Has Been Made. Reload Your Page To Proceed"
              },
              autoUpload: false,

              callbacks:  {
                  onComplete : function (id,name,response,xhr) {
                      console.log(response);
                  },
                  onAllComplete : function (id,name,response,xhr) {
                    var post_id = getCookie("send_post_id");
                    $(".spinner-overlay").show();
                   
                    setTimeout(testFunc, 1000);
                    function testFunc () {
                       $.ajax({
                        url : "<?php echo site_url('onehealth/get_post'); ?>",
                        type : "POST",
                        responseType : "json",
                        dataType : "json",
                        data : "post_id="+post_id,
                        success : function(response){
                          console.log(response)
                          $(".spinner-overlay").hide();
                          if(response.success == true && response.messages !== ""){
                            $("#makePostModal").modal("hide");
                            document.location.reload();
                          }  
                            
                        },error : function () {
                            $(".spinner-overlay").hide();
                        } 
                         
                      });
                    }
                          
                  }
                        

              },
              maxConnections : 1000

          });


          $('#upload').click(function() {
            console.log('done')
            var post = $("#post-form #post-text-area").val();
            $(".spinner-overlay").show();
            $.ajax({
              url : "<?php echo site_url('onehealth/index/make_post'); ?>",
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "post="+post,
              success : function(response){
                $(".spinner-overlay").hide();
                console.log(response)
                if(response.success == true){
                  $('#makePostModal .modal-body').fineUploader('uploadStoredFiles');
                }else{
                  if(response.noval == true){
                    $.notify({
                    message:response.messages
                    },{
                      type : "danger"  
                    });
                  }
                }
              },error : function () {
                $(".spinner-overlay").hide();
              }
            });  
            // console.log($('#fine-uploader-manual-trigger').length)
              
          });
        });
       </script>
      </script>
      <script>
        var big_image;

        

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
              var add_message_div = $(".add-message-div");
              var add_cover_photo_div = $(".add-cover-photo-div");
              var add_friend_div = $(".add-friend-div");

              // big_image.css({
              //   'transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
              //   '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              // });

              // add_message_div.css({
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
              // add_friend_div.css({
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
 