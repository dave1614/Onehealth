  

  <div class="">
  
   
    <section id="contact" style="padding-top: 30px; background: #f5fbff">
      <div class="container">

        <h2 class="text-center sub-head" style="margin-bottom: 50px;">Contact Us</h2>
        <div class=" row">
          <div class="col-sm-6">
            <p style="font-weight: bold;">One Health Global Issues Ltd. <br>Suite D404, Global Plaza, Jabi upstairs, Jabi, Abuja.</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3940.0180630478517!2d7.421766014276691!3d9.062116340965805!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sSuit%20D404%2C%20Global%20Plaza%2C%20Jabi%20upstairs%2C%20Jabi%2C%20Abuja!5e0!3m2!1sen!2sng!4v1579048590040!5m2!1sen!2sng" width="400" height="300" frameborder="0" style="border:0;" allowfullscreen=""></iframe>

          </div>

          <div class="col-sm-6" style="color: #000;">
            <span target="_blank" href="#">
              <div class="row">
              
                <div class="col-2 text-right" style="padding: 0;">
                  <i class="fas fa-envelope" style="font-size: 21px; color: #000;"></i>
                  
                </div>

                <div class="col-10" style="color: #000;">
                  support@onehealthpoints.com
                </div>
              
              </div>
            </span>




            <a target="_blank" href="https://www.facebook.com/Onehealth-makes-life-easy-100938764788816/">
              <div class="row" style="margin-top: 13px">
              
                <div class="col-2 text-right" style="padding: 0;">
                  <i class="fab fa-facebook-f" style="font-size: 21px; color: #000;"></i> 
                </div>

                <div class="col-10" style="color: #000;">
                  Onehealth Makes Life Easy
                </div>
              
              </div>
            </a>
            

            <!-- Twitter -->
            <a target="_blank" href="https://twitter.com/Onehealth9/">
              <div class="row" style="margin-top: 13px">
              
                <div class="col-2 text-right" style="padding: 0;">
                  <i class="fab fa-twitter" style="font-size: 21px; color: #000;"></i>
                  
                </div>

                <div class="col-10" style="color: #000;">
                  Onehealth9
                </div>
              
              </div>
            </a>

            <a target="_blank" href="#">
              <div class="row" style="margin-top: 13px">
              
                <div class="col-2 text-right" style="padding: 0;">
                  <i class="fas fa-phone" style="font-size: 21px; color: #000;"></i> 
                </div>

                <div class="col-10" style="color: #000;">
                  09021793333, 07010519000 
                </div>
              
              </div>
            </a>


            <div class="send-us-message row" style="margin-top: 40px;">
              <div class="col-1"></div>
              <div class="col-11" style="">
                <h4 style="font-weight: bold;">Send Us A Message</h4>
                <?php
                 $attr = array('id' => 'send-message-form'); 
                 echo form_open('meetglobal/process_send_message',$attr);
                ?>
                  <div class="form-group">
                    <textarea placeholder="Type Something Here......" name="message" id="message" cols="30" rows="10" class="form-control" required></textarea>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                    <input type="text" name="name" placeholder="Enter Your Name" id="name" class="form-control" required>
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                    <input type="number" name="mobile" id="mobile" placeholder="Enter Your Mobile Number" class="form-control" required>
                    <span class="form-error"></span>
                  </div>
                  <button type="submit" class="btn-primary">Submit <img src="<?php echo base_url('assets/images/ajax-loader.gif'); ?>"  class="spinner"></button>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>

    </section>
  </div>


 