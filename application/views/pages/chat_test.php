<!doctype html>
<html>
    <head>
        <title>Facebook Style Popup Design</title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/css/basic/emojify.min.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
        <style>
            @media only screen and (max-width : 540px) 
            {
                .chat-sidebar
                {
                    display: none !important;
                }
                
                .chat-popup
                {
                    display: none !important;
                }
            }
            
            body
            {
                background-color: #e9eaed;
            }
            
            .chat-sidebar
            {
                width: 200px;
                position: fixed;
                height: 100%;
                right: 0px;
                top: 0px;
                padding-top: 10px;
                padding-bottom: 10px;
                border: 1px solid rgba(29, 49, 91, .3);
            }
            
            .sidebar-name 
            {
                padding-left: 10px;
                padding-right: 10px;
                margin-bottom: 4px;
                font-size: 12px;
            }
            
            .sidebar-name span
            {
                padding-left: 5px;
            }
            
            .sidebar-name a
            {
                display: block;
                height: 100%;
                text-decoration: none;
                color: inherit;
            }
            
            .sidebar-name:hover
            {
                background-color:#e1e2e5;
            }
            
            .sidebar-name img
            {
                width: 32px;
                height: 32px;
                vertical-align:middle;
            }
            
            .popup-box
            {
                display: none;
                position: fixed;
                bottom: 0px;
                right: 220px;
                height: 400px;
                background-color: rgb(237, 239, 244);
                width: 300px;
                border: 1px solid rgba(29, 49, 91, .3);
            }
            
            .popup-box .popup-head
            {
                background-color: #6d84b4;
                padding: 5px;
                color: white;
                font-weight: bold;
                font-size: 14px;
                clear: both;
            }
            
            .popup-box .popup-head .popup-head-left
            {
                float: left;
            }
            
            .popup-box .popup-head .popup-head-right
            {
                float: right;
                opacity: 0.5;
            }
            
            .popup-box .popup-head .popup-head-right a
            {
                text-decoration: none;
                color: inherit;
            }
            
            .popup-box .popup-messages
            {
               height: 80%;
                overflow-y: scroll;
            }
            
            .discussion {
  list-style: none;
  background: #e5e5e5;
  margin: 0;
  padding: 0 0 0 0;

}
.discussion li {
  padding: 0.5rem;
  overflow: hidden;
  display: flex;
}
.discussion .avatar {
  width: 40px;
  position: relative;
}
.discussion .avatar img {
  display: block;
  width: 100%;
}
.other .avatar:after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  width: 0;
  height: 0;
  border: 5px solid white;
  border-left-color: transparent;
  border-bottom-color: transparent;
}
.self {
  justify-content: flex-end;
  align-items: flex-end;
}
.self .messages {
  order: 1;
  border-bottom-right-radius: 0;
}
.self .avatar {
  order: 2;
}
.self .avatar:after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 0;
  border: 5px solid white;
  border-right-color: transparent;
  border-top-color: transparent;
  box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}
.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
.messages p {
  font-size: 0.8rem;
  margin: 0 0 0.2rem 0;
}
.messages time {
  font-size: 0.7rem;
  color: #ccc;
}
@keyframes pulse {
  from {
    opacity: 0;
  }
  to {
    opacity: 0.5;
  }
}


        </style>
        
        <script>
            //this function can remove a array element.
            Array.remove = function(array, from, to) {
                var rest = array.slice((to || from) + 1 || array.length);
                array.length = from < 0 ? array.length + from : from;
                return array.push.apply(array, rest);
            };
        
            //this variable represents the total number of popups can be displayed according to the viewport width
            var total_popups = 0;
            
            //arrays of popups ids
            var popups = [];
        
            //this is used to close a popup
            function close_popup(id)
            {
                for(var iii = 0; iii < popups.length; iii++)
                {
                    if(id == popups[iii])
                    {
                        Array.remove(popups, iii);
                        
                        document.getElementById(id).style.display = "none";
                        
                        calculate_popups();
                        
                        return;
                    }
                }   
            }
        
            //displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
            function display_popups()
            {
                var right = 220;
                
                var iii = 0;
                for(iii; iii < total_popups; iii++)
                {
                    if(popups[iii] != undefined)
                    {
                        var element = document.getElementById(popups[iii]);
                        element.style.right = right + "px";
                        right = right + 320;
                        element.style.display = "block";
                    }
                }
                
                for(var jjj = iii; jjj < popups.length; jjj++)
                {
                    var element = document.getElementById(popups[jjj]);
                    element.style.display = "none";
                }
            }
            
            //creates markup for a new popup. Adds the id to popups array.
            function register_popup(id, name)
            {
                
                for(var iii = 0; iii < popups.length; iii++)
                {   
                    //already registered. Bring it to front.
                    if(id == popups[iii])
                    {
                        Array.remove(popups, iii);
                    
                        popups.unshift(id);
                        
                        calculate_popups();
                        
                        
                        return;
                    }
                }               
                
                var element = '<div class="popup-box chat-popup" id="'+ id +'">';
                element = element + '<div class="popup-head">';
                element = element + '<div class="popup-head-left">'+ name +'</div>';
                element = element + '<div class="popup-head-right"><a href="javascript:close_popup(\''+ id +'\');">&#10005;</a></div>';
                element = element + '<div style="clear: both"></div></div><div class="popup-messages"></div></div>';
                
                document.getElementsByTagName("body")[0].innerHTML = document.getElementsByTagName("body")[0].innerHTML + element;  
        
                popups.unshift(id);
                $(".popup-messages").html('<ol class="discussion"><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li></ol>');
                console.log($("#"+id+" #message-input").length);
                if($("#"+id+" #message-input").length < 1){
                    $(".popup-messages").after("<form class='form-inline'><input type='text' name='message' id='message-input' class='form-control col-sm-10'><input type='submit' class='btn btn-primary' value='Go'></form>");
                }
                calculate_popups();
                
            }
            
            //calculate the total number of popups suitable and then populate the toatal_popups variable.
            function calculate_popups()
            {
                var width = window.innerWidth;
                if(width < 540)
                {
                    total_popups = 0;
                }
                else
                {
                    width = width - 200;
                    //320 is width of a single popup box
                    total_popups = parseInt(width/320);
                }
                
                display_popups();
                
            }
            
            //recalculate when window is loaded and also when window is resized.
            window.addEventListener("resize", calculate_popups);
            window.addEventListener("load", calculate_popups);
            
        </script>
    </head>
    <body>
        <div class="chat-sidebar">
            <div class="sidebar-name">
                <!-- Pass username and display name to register popup -->
                <a href="javascript:register_popup('narayan-prusty', 'Narayan Prusty');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>Narayan Prusty</span>
                </a>
            </div>
            <div class="sidebar-name">
                <a href="javascript:register_popup('qnimate', 'QNimate');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>QNimate</span>
                </a>
            </div>
            <div class="sidebar-name">
                <a href="javascript:register_popup('qscutter', 'QScutter');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>QScutter</span>
                </a>
            </div>
            <div class="sidebar-name">
                <a href="javascript:register_popup('qidea', 'QIdea');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>QIdea</span>
                </a>
            </div>
            <div class="sidebar-name">
                <a href="javascript:register_popup('qazy', 'QAzy');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>QAzy</span>
                </a>
            </div>
            <div class="sidebar-name">
                <a href="javascript:register_popup('qblock', 'QBlock');">
                    <img width="30" height="30" src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p50x50/1510656_10203002897620130_521137935_n.jpg?oh=572eaca929315b26c58852d24bb73310&oe=54BEE7DA&__gda__=1418131725_c7fb34dd0f499751e94e77b1dd067f4c" />
                    <span>QBlock</span>
                </a>
            </div>
        </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/emojify.js/1.1.0/js/emojify.min.js"></script>
    <script>
        $(document).ready(function () {
            
        })
    </script>
    </body>
</html>
