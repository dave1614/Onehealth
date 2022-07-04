function calculateBmi (weight,height) {
  var height_squ = height * height;
  var bmi = weight / height_squ;
  return bmi.toFixed(2);
}
function randomColorProperty (obj) {
  var keys = Object.keys(obj)
  return obj[keys[ keys.length * Math.random() << 0]];
}
function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}

function addCommas(nStr)
  {
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }


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
            function close_popup(elem,id)
            {
                for(var iii = 0; iii < popups.length; iii++)
                {
                    if(id == popups[iii])
                    {
                        Array.remove(popups, iii);
                        
                        $("#"+id).remove();
                        elem.parentElement.parentElement.setAttribute("data-big", "true");
                        elem.parentElement.parentElement.style.bottom = '0';
                        
                        calculate_popups();
                        setCookie(id,"closed",0.000173611);
                        return;
                    }
                }   
            }
        
            //displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
            function display_popups()
            {
                var right = 30;
                
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
                  // console.log('hey')
                    var element = document.getElementById(popups[jjj]);
                    // element.remove();
                    element.style.display = "none";
                    // document.location.assign("test");
                }
                
                
            }
            
            //creates markup for a new popup. Adds the id to popups array.
            function register_popup(id, name,url,url2,notif_sound_file,send_message,last_chats_url,chat_messaging_url,img_url,loader_img_url)
            {
              console.log(loader_img_url);
            if($("#"+id).length == 0){
              
            addClass(document.querySelector("#notificationContainer"),"hide");
               
                // for(var iii = 0; iii < popups.length; iii++)
                // {   
                //     //already registered. Bring it to front.
                //     if(id == popups[iii])
                //     {
                //         Array.remove(popups, iii);
                    
                //         popups.unshift(id);
                        
                //         calculate_popups();
                        
                        
                //         return;
                //     }
                // }               
                if($(window).width() >= 500){
                  var element = '<div class="popup-box chat-popup" id="'+ id +'">';
                  element = element + '<div style="margin:0; width:100%;" class="popup-head text-center row"  data-big="true">';
                  element = element + '<div class="col-sm-2 popup-head-left"><span class="dot online-status"></span></div>';
                  element = element + '<div class="col-sm-8 popup-head-left" onclick="enlargeChatWindow(this,'+id+')">'+ name +'</div>';
                  element = element + '<div class="popup-head-right col-sm-2"><a onclick="close_popup(this,\''+ id +'\')">&#10005;</a></div>';
                  element = element + `<div style="clear: both"></div></div><div class="popup-messages"><img style='display:none;' class='small-loader-chat' src='${img_url}'/><ol class="discussion"></ol></div></div>`;
                  
                  document.getElementsByTagName("body")[0].innerHTML = document.getElementsByTagName("body")[0].innerHTML + element;  
                  $("#"+id+" .popup-messages").on("scroll",function () {
                    // var elem = $(this);
                    var url = last_chats_url;
                    var scroll_top = $(this).scrollTop();
                    var offset = $("#"+id+" .discussion").children().first().children('.messages').attr("id");
                    // console.log("#"+id+" .discussion");
                    // $("#"+id+" .discussion").css({
                    //   'display' : 'none'
                    // })
                    var small_loader = $("#"+id+" .popup-messages .small-spinner")
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
                              $("#"+id+" .discussion").prepend(messages);
                            }else{
                              $("#"+id+" .popup-messages").off("scroll");
                            }
                          },
                          error : function () {
                            small_loader.hide();
                          }
                      });  
                    }
                  });
                  popups.unshift(id);
                  var values = "user_id="+id;
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : values,
                    success: function (response) {
                      console.log(response)
                      if(response !== ""){
                        if(response.success == true){
                          $("#"+id+" .discussion").html(response.messages);

                          var online_status = response.online_status;
                          if($("#"+id+" #message-input").length < 1){
                              // send_message = String(send_message)
                              $("#"+id+" .popup-messages").after(`<form class='form-inline' onsubmit='return sendMessage(event,this,"desktop",${id},"${send_message}","${loader_img_url}")'><input style='padding-bottom: 3px;' autocomplete='off' name='message' id='message-input' placeholder='Type A Message Here...' class='form-control col-sm-8'/><button type='submit' class='btn col-sm-3 btn-round'><i class='fas fa-paper-plane'></i></button></form>`);
                              
                              // console.log($("#"+id+" .popup-messages").length)
                              // console.log("#"+id+" .popup-messages")
                              setTimeout(scrollWindowBottom, 1000,"desktop",id);
                              setCookie(id,"open",1);
                          }

                          if(online_status == true){
                            console.log('online')
                            $("#" + id + " .online-status").css({
                              "background-color" : "#33FF00"
                            });
                          }else{
                            console.log('not online')
                            $("#" + id + " .online-status").css({
                              "background-color" : "#aaa"
                            });
                          }  
                          calculate_popups();
                          console.log(response.offset);
                          setTimeout(getStatus,3000,"desktop",url2,id,response.offset,0,notif_sound_file);
                        }else{
                          return ;
                        }
                      }else{
                        return ;
                      }
                    },error : function () {
                      return ;
                    }
                  })
                  // $(".popup-messages").html('<ol class="discussion"><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>yeah, they do early flights cause they connect with big airports.  they wanna get u to your connection</p><time datetime="2009-11-13T20:00">Timothy • 51 min</time></div></li><li class="self"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/profile/profile-80_20.jpg" /></div><div class="messages"><p>That makes sense.</p><p>Its a pretty small airport.</p><time datetime="2009-11-13T20:14">37 mins</time></div></li><li class="other"><div class="avatar"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/5/profile/profile-80_9.jpg" /></div><div class="messages"><p>that mongodb thing looks good, huh?</p><p>tiny master db, and huge document store</p></div></li></ol>');
                  
                  // if($("#"+id+" #message-input").length < 1){
                  //     $(".popup-messages").after("<form class='form-inline'><input type='text' onblur='this.focus()' autofocus autocomplete='off' name='message' id='message-input' class='form-control col-sm-8'><input type='submit' class='btn btn-info btn-round' value='Go'></form>");
                  // }
                }else{
                  document.location.assign(chat_messaging_url);
                }
              }              
            }

            function scrollWindowBottom (type,id) {
              console.log(type)
              // console.log('ksksl')
              // $("#"+id+" .popup-messages").scrollTop($("#"+id+" .popup-messages").scrollHeight);
              if(type == "desktop"){
                var element = $("#"+id+" .popup-messages")[0];
                
                element.scrollTop = element.scrollHeight;
              }else{
                var element = $(".chat-box")[0];
                
                element.scrollTop = element.scrollHeight;
              }
            }

            function enlargeChatWindow (elem,id){
              
              if(elem.getAttribute("data-big") == "true"){
                $("#"+ id +".popup-box").css({
                  "bottom" : "-366px"
                })
                elem.setAttribute("data-big","false");
              }else{
                $("#"+ id +".popup-box").css({
                  "bottom" : "0"
                })
                elem.setAttribute("data-big","true");
              }
            }

            function replaceWithCustom (old_element,new_element) {
            

              // get the parent
              var parent = old_element.parentNode;

              // replace child of the parent
              parent.replaceChild(new_element, old_element);

            }

            function getLastChats (evt,elem,id,url) {
              var scroll_top = elem.scrollTop;
              var offset = $("#"+id+" .discussion").children().first().children('.messages').attr("id");
              // console.log("#"+id+" .discussion");
              // $("#"+id+" .discussion").css({
              //   'display' : 'none'
              // })
              var small_loader = $("#"+id+" .popup-messages .small-spinner")
              console.log(offset)
              if (scroll_top <= 0){
                small_loader.show();
                $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_last_chats=true&user_id="+id+"&offset="+offset,
                    success: function (response) {
                      small_loader.hide();
                      if(response.success == true){
                        var messages = response.messages;
                        $("#"+id+" .discussion").prepend(messages);
                      }else{
                        $("#"+id+" .popup-messages").off("scroll");
                      }
                    },
                    error : function () {
                      small_loader.hide();
                    }
                });  
              }
            }



            function sendMessage (evt,elem,type,id,url,loader_img_url) {
              evt.preventDefault();
              
              var message = elem.querySelector("input").value;
              if(message !== ""){  
                if(type == "desktop"){             
                  var dob = moment(dob).format('DD MMM  h:mm:ss a');
                  var temp_id = Math.random() * 1000000000000000000;
                  var html_message = "<li class='self'><div class='messages' id='"+ temp_id +"'><p>"+message+"</p><time >you • "+dob+"</time><i class='fas fa-times message-times' style='display:none;'></i><i class='fas fa-check message-mark' style='display:none;'></i><img class='small-loader-chat' src='"+loader_img_url+"'/></div></li>";
                  $("#"+id+" .discussion").append(html_message);
                               
                  scrollWindowBottom("desktop",id);
                  elem.querySelector("input").value = "";
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_status=true&user_id="+id+"&message="+message,
                    success: function (response) {
                      console.log(response)
                      if(response.success == true){
                        var old_elem = $("#" + temp_id + " .small-loader-chat");
                        old_elem.hide();
                        $("#" + temp_id + " .message-mark").show();
                        var message_elem = document.getElementById(temp_id);
                        message_elem.setAttribute("id", response.new_id);
                        // new_elem.setAttribute("class", "fas fa-check message-mark");
                        // replaceWithCustom (old_elem,new_elem);
                      }else{
                        var old_elem = $("#" + temp_id + " .small-loader-chat");
                        old_elem.hide();
                        $("#" + temp_id + " .message-times").show();
                      }
                    },
                    error : function () {
                      var old_elem = $("#" + temp_id + " .small-loader-chat");
                      old_elem.hide();
                      $("#" + temp_id + " .message-times").show();
                    }
                  });  
                }else{
                  var dob = moment(dob).format('DD MMM  h:mm:ss a');
                  var temp_id = Math.random() * 1000000000000000000;
                  var html_message = "<li class='self'><div class='messages' id='"+ temp_id +"'><p>"+message+"</p><time >you • "+dob+"</time><i class='fas fa-times message-times' style='display:none;'></i><i class='fas fa-check message-mark' style='display:none;'></i><img class='small-loader-chat' src='"+loader_img_url+"'/></div></li>";
                  $(".discussion").append(html_message);
                               
                  scrollWindowBottom("device",id);
                  elem.querySelector("input").value = "";
                  $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_status=true&user_id="+id+"&message="+message,
                    success: function (response) {
                      console.log(response)
                      if(response.success == true){
                        var old_elem = $("#" + temp_id + " .small-loader-chat");
                        old_elem.hide();
                        $("#" + temp_id + " .message-mark").show();
                        var message_elem = document.getElementById(temp_id);
                        message_elem.setAttribute("id", response.new_id);
                        // new_elem.setAttribute("class", "fas fa-check message-mark");
                        // replaceWithCustom (old_elem,new_elem);
                      }else{
                        var old_elem = $("#" + temp_id + " .small-loader-chat");
                        old_elem.hide();
                        $("#" + temp_id + " .message-times").show();
                      }
                    },
                    error : function () {
                      var old_elem = $("#" + temp_id + " .small-loader-chat");
                      old_elem.hide();
                      $("#" + temp_id + " .message-times").show();
                    }
                  });  
                }
              }
            }

            function restartGetStatus(type,url,id,offset,num,status,notif_sound_file) {
              clearTimeout(getStatus);
                if(num < 2){
                  setTimeout(getStatus,3000,type,url,id,offset,num,notif_sound_file);
                }else{
                  if(type == "desktop"){
                    $("#" + id + " .online-status").css({
                      "background-color" : "#aaa"
                    });               
                    alert("Could Not Connect To The Server. Please Check your Internet Connection And Refresh Your Browser");
                  }else{
                    $(".online-status").css({
                      "background-color" : "#aaa"
                    });               
                    alert("Could Not Connect To The Server. Please Check your Internet Connection And Refresh Your Browser");
                  }
                }
              
            }

            function playNotif(filename){
              
              var mp3Source = '<source src="' + filename + '" type="audio/mpeg">';
              var oggSource = '<source src="' + filename + '" type="audio/ogg">';
              var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'">';
              document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
            }


            function getStatus(type,url,id,offset,num,notif_sound_file){
              if(type == "desktop"){
                $.ajax({
                    url : url,
                    type : "POST",
                    responseType : "json",
                    dataType : "json",
                    data : "get_status=true&user_id="+id+"&offset="+offset,
                    success: function (response) {
                      console.log(response)
                      if(getCookie(id) !== "closed"){
                        num = 0;
                        // console.log(response)
                        if(response.success == true){
                          restartGetStatus("desktop",url,id,offset,num,"",notif_sound_file);
                          var messages = response.messages;
                          var online_status = response.online_status;
                          var offset = response.offset;
                          if(messages !== ""){
                            $("#"+id+" .discussion").append(messages);
                            scrollWindowBottom ("desktop",id);
                            playNotif(notif_sound_file);
                            restartGetStatus("desktop",url,id,offset,num,"",notif_sound_file);
                          }
                          if(online_status == true){
                            $("#" + id + " .online-status").css({
                              "background-color" : "#33FF00"
                            });
                          }else{
                            $("#" + id + " .online-status").css({
                              "background-color" : "#aaa"
                            });
                          }
                        }else{
                          return ;
                        }
                      }  
                    },error : function () {
                      num++;
                      console.log(num)
                      restartGetStatus("desktop",url,id,offset,num,"",notif_sound_file);
                      return ;
                    }
                })
              }else{
                $.ajax({
                  url : url,
                  type : "POST",
                  responseType : "json",
                  dataType : "json",
                  data : "get_status=true&user_id="+id+"&offset="+offset,
                  success: function (response) {
                    console.log(response)
                    
                      num = 0;
                      // console.log(response)
                      if(response.success == true){
                        restartGetStatus(type,url,id,offset,num,"",notif_sound_file);
                        var messages = response.messages;
                        var online_status = response.online_status;
                        var offset = response.offset;
                        if(messages !== ""){
                          $(".discussion").append(messages);
                          scrollWindowBottom (type,id);
                          playNotif(notif_sound_file);
                          restartGetStatus(type,url,id,offset,num,"",notif_sound_file);
                        }
                        if(online_status == true){
                          $(".online-status").css({
                            "background-color" : "#33FF00"
                          });
                        }else{
                          $(".online-status").css({
                            "background-color" : "#aaa"
                          });
                        }
                      }else{
                        return ;
                      }
                     
                  },error : function () {
                    num++;
                    console.log(num)
                    restartGetStatus(type,url,id,offset,num,"",notif_sound_file);
                    return ;
                  }
              })
              }
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

            function scrollelems () {
              
              oVal = ($(window).scrollTop() / 3);
              var add_message_div = $(".add-message-div");
              var add_cover_photo_div = $(".add-cover-photo-div");
             

              big_image.css({
                'transform': 'translate3d(0,' + oVal + 'px,0)',
                '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              });

              add_message_div.css({
                'transform': 'translate3d(0,' + oVal + 'px,0)',
                '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              });

              add_cover_photo_div.css({
                'transform': 'translate3d(0,' + oVal + 'px,0)',
                '-webkit-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-ms-transform': 'translate3d(0,' + oVal + 'px,0)',
                '-o-transform': 'translate3d(0,' + oVal + 'px,0)'
              });
            }
            
            //recalculate when window is loaded and also when window is resized.
            window.addEventListener("resize", calculate_popups);
            window.addEventListener("load", calculate_popups);  
// Check it out on Dribbble  https://dribbble.com/shots/2536070-Jelly-Buttons-CSS
function autocomplete(inp, arr,type) {
    var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?1234567890]/;
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        if(val == ""){ return false; }
        if(type == "patient_name"){
		   if(format.test(val)){ return false; }
		}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        a.style.maxHeight = "300px";
        a.style.overflowY = 'auto';

        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].toString().substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].toString().substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].toString().substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                  /*insert the value for the autocomplete text field:*/
                  
                  var full_str = this.getElementsByTagName("input")[0].value;
                  var n = full_str.split(" ");
            			var lab_id = n[n.length - 1];
            			inp.value = this.getElementsByTagName("input")[0].value;
            			loadPatientInfo1(lab_id);
      			
                  /*close the list of autocompleted values,
                  (or any other open lists of autocompleted values:*/
                  closeAllLists();
              });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

function autocomplete1(inp, arr,value1) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  
      var a, b, i, val = value1;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", inp.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      a.style.maxHeight = "300px";
      a.style.overflowY = 'auto';

      /*append the DIV element as a child of the autocomplete container:*/
      inp.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong style='font-size:18px; font-weight:bold;'>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } 
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
    }
  }
}



$("#main-search-form").submit(function (evt) {
  evt.preventDefault();

  var search_val = $("#myInput").val().toLowerCase();
  var url = $(this).attr("action");
  var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
  
  if(search_val !== "" && !format.test(search_val)){
    document.location.assign(encodeURI(url+"/"+search_val));
  }
})



function removeClass (element,name) {
      element.classList.remove(name);
    }

function addClass (element,name) {
  var arr;
  // element = document.getElementById("myDIV");
  // name = "mystyle";
  arr = element.className.split(" ");
  if (arr.indexOf(name) == -1) {
      element.className += " " + name;
  }
}

function toggleClass (element,name) {
  

  if (element.classList) { 
      element.classList.toggle(name);
  } else {
      // For IE9
      var classes = element.className.split(" ");
      var i = classes.indexOf(name);

      if (i >= 0) 
          classes.splice(i, 1);
      else 
          classes.push(name);
          element.className = classes.join(" "); 
  }
}