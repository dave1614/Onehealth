
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
     .sender-name{
      margin-bottom: 3px;
      font-size: 18px;
    }
     .conversation{
      background: #fff;
      cursor: pointer;
      padding-top: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #dddddd;
      margin-bottom: 10px;
      border-radius: 5px;
    }
     .conversation:hover{
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

    .conversation p{
      font-size: 15px;
      font-weight: bold;
    }
    .conversation span{
      font-size: 12px;
      /*font-weight: bold;*/
    }
        </style>
      <div class="content" style="">
        <?php
        
        $page = $page - 1;
        // $conversations = $this->onehealth_model->getConversationsRem2($user_id,$page);
        // var_dump($conversations);
        $all_notifs = $this->onehealth_model->getNotifsPerPage($user_name,$page);
        if(is_array($all_notifs)){
        ?>
        <h3 class="text-center" style="margin-bottom: 10px;">Notifications</h3>
        <div class="container">
        <?php
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
        <div class="conversation row" <?php if($received == 0){ echo "style='background-color: #E8E8E8;'"; } ?> onclick="window.location.assign('<?php echo $site_url; ?>');">
            <div class="col-4">
              <p class="sender-name"><?php echo $sender; ?> 
            </div>

            <div class="col-4 message-sender">
              
              <span class="last-message"><?php echo $this->onehealth_model->custom_echo($notif_title,80); ?></span>
            </div>
            <div class="col-4 time-stamp">
              <span>
                <?php echo $this->onehealth_model->getSocialMediaTime($date_sent,$time_sent); ?>
              </span>
            </div>
        </div>
        <?php
          }
        ?>
        </div>
        <?php         
          }else{
            echo "<h4 class='text-danger'>You Do Not Have Any Notifications</h4>";
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
 