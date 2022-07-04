
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
      <?php
        if(is_array($notif_array)){
          foreach($notif_array as $row){
            $id = $row->id;
            $sender = $row->sender;
            $receiver = $row->receiver;
            $title = $row->title;
            $message = $row->message;
            $date_sent = $row->date_sent;
            $time_sent = $row->time_sent;
            $received = $row->received;
            $action_taken = $row->action_taken;
            $btn1 = $row->btn_1;
            $btn2 = $row->btn_2;
            $btn3 = $row->btn_3;
          }
        
      ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <h2><?php echo $title; ?></h2>
          <div class="row">
            <div class="col-sm-10">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title text-secondary">From: <?php echo $sender; ?></h4>
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary">To:  me</button>
                    <button type="button" class="btn btn-primary btn-round dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                    </button>
                    <div class="dropdown-menu">
                      <p class="dropdown-item" >from: <?php echo $sender; ?></p>
                      <p class="dropdown-item" >reply-to: <?php echo $sender; ?></p>
                      <p class="dropdown-item" >to: <?php echo $receiver; ?></p>
                      <p class="dropdown-item" >date: <?php echo $date_sent; ?></p>
                      <p class="dropdown-item" >time: <?php echo $time_sent; ?></p>
                      <p class="dropdown-item" >status: read</p>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <p style="font-size: 18px;"><?php echo $message; ?></p>
                      <?php
                      if($action_taken == 0){
                        //Set Notif Id As Session
                        $this->session->set_userdata('notif_id',$second_addition);
                          if(!is_null($btn1)){
                            echo $btn1;
                          }
                          if(!is_null($btn2)){
                            echo $btn2;
                          }
                          if(!is_null($btn3)){
                            echo $btn3;
                          }
                        }
                      
                      ?>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <a class="btn btn-success disabled" href="<?php echo site_url('onehealth/index/reply/'.$id); ?>">Reply</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
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
      var table = $('table').DataTable()
    })
 </script>