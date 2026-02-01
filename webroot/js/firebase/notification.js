//$(document).ready(function() {
//  var NN = firebase.database().ref('one_to_one');
//      NN.on('child_added', function(childSnapshot2) {
//        var childKEY = childSnapshot2.val();
//        
//        $.each( childKEY, function( key, value ) {
//            $.each( value, function( key2, value2 ) {
//                alert( key2 + ": " + value2 );
//            });
//            
//  alert( key + ": " + value );
//});
//        ///console.log(array);
//        //console.log(childKEY);
//        //var childKEY2 = childKEY.key;
//       // alert(childKEY);
//        //alert(childKEY2);
//        //alert(childKEY2.val().chat_message);
//        //alert(snapshot2.val().chat_message);
//       //var OWNID = '<?php echo $this->Session->read('UserD.id');?>';
//       //var chatSenderId = snapshot2.val().chatSenderId;
//       //var chatTime = snapshot2.val().chat_time;
//       //var messageText = snapshot2.val().chat_message;
//       //var cla = 'message-bubble';
//       //if(chatSenderId == OWNID){
//       //      cla = 'message-bubble your-msg';
//       //}
//       //var appEND = '<div class="'+cla+'"><p>'+messageText+'</p><p class="text-muted"><i class="fa fa-clock-o"></i> '+chatTime+' <i class="fa fa-check"></i></p></div>';
//       //$("#DMSG").append(appEND);
//    });
//
//});