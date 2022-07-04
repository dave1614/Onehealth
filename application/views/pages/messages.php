
         <!-- End Navbar -->
        <style>
        body{
          /*background: #9124a3;*/
        }
           .avatar img{
       border-radius: 50%;
       width: 50px;
       height: 50px;
    }
     .message-sender .sender-name{
      margin-bottom: 3px;
    }
     .conversation1{
      background: #fff;
      cursor: pointer;
      padding-top: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #dddddd;
      margin-bottom: 10px;
      border-radius: 3px;
    }
     .conversation1:hover{
      background-image: linear-gradient(rgba(29, 33, 41, .04), rgba(29, 33, 41, .04));
    }
     

    span.new-message-num.notification{
      right: -11px;
      top: -8px;
    }

      .small-loader{
      width: 30px;
      height: 30px;
      /*display: none;*/
    }

    .conversation1 p{
      font-size: 15px;
      font-weight: bold;
    }
    .conversation1 span{
      font-size: 12px;
      /*font-weight: bold;*/
    }
        </style>
        
      <div class="content" style="">
        <?php
        
        $page = $page - 1;
        $conversations = $this->onehealth_model->getConversationsRem2($user_id,$page);
        // var_dump($conversations);
          if(is_array($conversations)){
        ?>
        <h3 class="text-center">Recent Messages</h3>
        <div class="container">
        <?php     
              foreach($conversations as $row){
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
         <div class="conversation1 row" onclick="register_popup(<?php echo $partner; ?>, '<?php echo $this->onehealth_model->getUserNameById($partner); ?>','<?php echo site_url('onehealth/index/get_messages') ?>','<?php echo site_url('onehealth/index/get_status') ?>','<?php echo base_url('assets/audio/notif-sound.mp3') ?>','<?php echo site_url('onehealth/index/send_message') ?>','<?php echo site_url('onehealth/index/get_last_chats') ?>','<?php echo site_url('onehealth/index/'.$partner.'/messaging') ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>','<?php echo base_url('assets/images/small-loader.gif'); ?>')">
          <div class="col-2 avatar">
            <img src="<?php echo $this->onehealth_model->getUserLogoById($partner); ?>" alt="<?php echo $this->onehealth_model->getUserNameById($partner); ?>">
          </div>
          <div class="col-6 message-sender">
            <p class="sender-name"><?php echo $this->onehealth_model->getUserNameById($partner); ?> &nbsp;
            <?php
              echo $this->onehealth_model->getNumberOfNewMessagesFromSender($user_id,$partner);
            ?>
            </p>
            <span class="last-message"><?php echo $this->onehealth_model->custom_echo($message,50); ?></span>
          </div>
          <div class="col-4 time-stamp">
            <span>
              <?php echo $post_date; ?>
            </span>
          </div>
        </div>
        <?php
              }
              } 
        ?>
        </div>
        <?php         
          }
        ?>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <?php

            echo $str_links;
          ?>
        </div>
      </footer>
      
      <script>
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 