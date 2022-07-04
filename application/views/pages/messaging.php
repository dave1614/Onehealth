
<?php
  $is_admin = false;
  $no_logo = true;
  if($user_name == $this->onehealth_model->getUserNameBySlug($addition)){
    $is_admin = true;
  }
  $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
  $user_info = $this->onehealth_model->getUserInfoByUserId($user_id);
  $data['user_id'] = $user_id;
  $data['addition'] = $addition;
  $data['second_addition'] = $second_addition;
  if(is_array($user_info)){
    foreach($user_info as $user){
      $user_name = $user->user_name;
      $email = $user->email;
      $phone = $user->phone;
      $country_id = $user->country_id;
      $state_id = $user->state_id;
      $address = $user->address;
      $user_slug = $user->slug;
      $date = $user->date;
      $time = $user->time;
      $logo = $user->logo;
      $cover_photo = $user->cover_photo;
      $bio = $user->bio;
    }
  }  
  $user_info = $this->onehealth_model->getUserInfoById($addition);
  if(is_array($user_info)){
    foreach($user_info as $user){
      $partner_id = $user->id;
      $partner_user_name = $user->user_name;
      $partner_email = $user->email;
      $partner_phone = $user->phone;
      $partner_country_id = $user->country_id;
      $partner_state_id = $user->state_id;
      $partner_address = $user->address;
      $partner_user_slug = $user->slug;
      $partner_date = $user->date;
      $partner_time = $user->time;
      $partner_logo = $user->logo;
      $partner_cover_photo = $user->cover_photo;
      $partner_bio = $user->bio;
    }
  }  
  $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
  if($is_admin){
    redirect('onehealth/cl_admin');
  }
?>

<script>

  <?php if($is_admin){ ?>
 
<?php } ?>
  
</script>
<style>
  .chat-box{
    /*height: 500px;*/
    overflow-y: scroll;
  }
  .nav{
    padding-bottom: 0;
  }
</style>

         <!-- End Navbar -->
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <div class="content" style="padding-top: 0;">
        <div class="container-fluid" style="padding: 0;">
          <div style="margin:0; width:100%;" class="popup-head text-center row affix" data-big="true"><div class="col-2 popup-head-left"><span class="dot online-status" style="background-color: #aaa;"></span></div><div class="col-8 popup-head-left" onclick="enlargeChatWindow(this)"><?php echo $partner_user_name; ?></div><div style="clear: both"></div></div>
          <div class="chat-box">
          
            
            <div class="popup-messages"><img style="display:none;" class="small-loader-chat" src="http://localhost/onehealth/assets/images/small-loader">
              <ol class="discussion">
                <?php
                // echo $user_id . " " .$partner_id;
                  $messages = $this->onehealth_model->getFirstChatMessages($user_id,$partner_id);
                    if(is_array($messages)){
                      $messages = array_reverse($messages);               
                      
                      $id_arr = array();
                      foreach($messages as $row){
                        $id = $row->id;
                        $id_arr[] .= $id;
                        $you = false;
                        $sender = $row->sender;
                        $receiver = $row->receiver;
                        $message = $row->message;
                        
                        $curr_year = date("Y");
                        $time = $row->time;
                        $chat_date = $row->date;
                        // echo $chat_date;
                        $curr_date = date("j M Y");
                        $curr_time = date("h:i:sa");
                        if($sender == $user_id){                      
                          $you = true;
                        }
                        $year = substr($chat_date, -4);
                        $mon_day = substr($chat_date,0, -4);

                        //if its not the same year
                        if($curr_year > $year){
                          $t_stamp = $chat_date . ' ' . $time;
                        }else{ //same day
                          if($curr_date == $chat_date){
                            $t_stamp = $time;
                          }else{
                            $t_stamp = $mon_day . ' ' .$time;
                          }
                          
                        }
                        

                        if($you){
                          $who = 'self';
                          $display_name = 'you';
                          $mark = "<i class='fas fa-check message-mark'></i>";
                        }else{
                          $who = 'other';
                          $display_name = $partner_user_name;
                          $mark = "";
                        }
                        
                        echo "<li class='".$who."'><div class='messages' id='".$id."'><p>".$message."</p><time datetime=".$chat_date. "".$time.">".$display_name." • ".$t_stamp."</time>".$mark."</div></li>";
                        
                      }
                      $offset = max($id_arr);
                      $this->onehealth_model->updateMessageAsRead($id);
                    }else{
                      echo "no messages here";
                    }
                ?>
              </ol>
            </div>
          </div>
          <form class="form-inline send_message" onsubmit="return sendMessage(event,this,'device',<?php echo $partner_id; ?>,'<?php echo site_url('onehealth/send_message') ?>','<?php echo base_url("assets/images/small-loader.gif") ?>')" style="width: 92%; background: #fff; margin: 0;">
            <div class="form-group" style="width: 100%;">
              <input style="padding-bottom: 3px;" autocomplete="off" name="message" id="message-input" placeholder="Type A Message Here..." class="form-control"/>
            </div>
          </form>
        </div>
      </div>
  
       
      <footer class="footer">
        <div class="container-fluid">
          
        </div>
      </footer>
      
      <script>
        $(document).ready(function () {
          window.scrollTop = window.scrollHeight
          $("form.send_message").css({
            'position' : 'fixed',
            'bottom' : '0'
          })
          var window_height = $(window).height();
          var chat_box_height = 0.8 * window_height;
          // chat_box_height = window_height - chat_box_height;
          $(".chat-box").css({
            "height" : chat_box_height + "px"
          })
          <?php if(is_array($messages)){ ?>
            $(".chat-box").on("scroll",function () {
              // var elem = $(this);
              var url = '<?php echo site_url('onehealth/index/get_last_chats') ?>';
              var scroll_top = $(this).scrollTop();
              var offset = $(".discussion").children().first().children('.messages').attr("id");
              var id = <?php echo $partner_id; ?>;
              // console.log("#"+id+" .discussion");
              // $("#"+id+" .discussion").css({
              //   'display' : 'none'
              // })
              var small_loader = $(".chat-box .small-spinner");
              // console.log(offset)
              if (scroll_top <= 0){
                // console.log('hah')
                small_loader.show();
                $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_last_chats=true&user_id="+id+"&offset="+offset,
                    success: function (response) {
                      small_loader.hide();
                      if(response.success == true && response.messages !== ""){
                        var messages = response.messages;
                        $(".discussion").prepend(messages);
                      }else{
                        $(".chat-box").off("scroll");
                      }
                    },
                    error : function () {
                      small_loader.hide();
                    }
                });  
              }
            });
          scrollWindowBottom ("device",<?php echo $partner_id; ?>);
          setTimeout(getStatus,3000,"device",'<?php echo site_url('onehealth/index/get_status') ?>',<?php echo $partner_id; ?>,<?php echo $offset; ?>,0,'<?php echo base_url('assets/audio/notif-sound.mp3') ?>');
          var online_status = <?php if($this->onehealth_model->getUserOnlineStatus($partner_id)){ echo "true"; }else{ echo "false"; } ?>;
          if(online_status == true){
            console.log('online')
            $(".online-status").css({
              "background-color" : "#33FF00"
            });
          }else{
            console.log('not online')
            $(".online-status").css({
              "background-color" : "#aaa"
            });
          } 
          <?php }else{ ?>
            $(".online-status").css({
              "background-color" : "#aaa"
            });
          <?php } ?>         

        })
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 