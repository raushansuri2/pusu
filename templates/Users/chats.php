

<link href="<?php echo $this->Url->build('/');?>js/firebase/chat_css.css" rel="stylesheet">
<style>
.chat-wrapper .chat-inner .users-sidebar .user-item.is-active {  
    background: #b0b3bb;
}
.chat-wrapper .chat-inner .chat-body .chat-body-inner .chat-message.is-sent+.is-sent img {
    visibility: visible;
}
.ajaxloader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 0.7;
    background: url(https://www.evirtualservices.com//img/admin/fancybox_loading@2x.gif) 50% 50% no-repeat rgb(249,249,249);
}
</style>

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card chat-app">
            <!-- <div id="plist" class="people-list">-->
            <!--    <div class="input-group">-->
            <!--        <div class="input-group-prepend">-->
            <!--            <span class="input-group-text"><i class="fa fa-search"></i></span>-->
            <!--        </div>-->
            <!--        <input type="text" class="form-control" placeholder="Search...">-->
            <!--    </div>-->
            <!--    <ul class="list-unstyled chat-list mt-2 mb-0">-->
            <!--        <li class="clearfix">-->
            <!--            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">-->
            <!--            <div class="about">-->
            <!--                <div class="name">Vincent Porter</div>-->
            <!--                <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>                                            -->
            <!--            </div>-->
            <!--        </li>-->
            <!--        <li class="clearfix active">-->
            <!--            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">-->
            <!--            <div class="about">-->
            <!--                <div class="name">Aiden Chavez</div>-->
            <!--                <div class="status"> <i class="fa fa-circle online"></i> online </div>-->
            <!--            </div>-->
            <!--        </li>-->
            <!--        <li class="clearfix">-->
            <!--            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">-->
            <!--            <div class="about">-->
            <!--                <div class="name">Mike Thomas</div>-->
            <!--                <div class="status"> <i class="fa fa-circle online"></i> online </div>-->
            <!--            </div>-->
            <!--        </li>                                    -->
            <!--        <li class="clearfix">-->
            <!--            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">-->
            <!--            <div class="about">-->
            <!--                <div class="name">Dean Henry</div>-->
            <!--                <div class="status"> <i class="fa fa-circle offline"></i> offline since Oct 28 </div>-->
            <!--            </div>-->
            <!--        </li>-->
            <!--    </ul>-->
            <!--</div>-->
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <?php $AVT = (@$usersD->profile_picture) ? @$usersD->profile_picture :'no-image-100x100.jpg'; ?>
                                <img src="<?php echo $this->Url->build('/');?>img/uploads/users/<?php echo $AVT;?>" alt="avatar">
                            </a>
                            <div class="chat-about">
                                <h3 class="m-b-0"><?php echo $usersD->firstName;?> <?php echo $usersD->lastName;?></h3>
                                <!-- <small>Last seen: 2 hours ago</small> -->
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="chat-history">
                    <ul class="m-b-0" id="chat_list_display">
                        <!-- <li class="clearfix">
                            <div class="message-data text-right">
                                <span class="message-data-time">10:10 AM, Today</span> 
                            </div>
                            <div class="message other-message float-right"> Hi Aiden, how are you? How is the project coming along? </div>
                        </li>
                        <li class="clearfix">
                            <div class="message-data">
                                <span class="message-data-time">10:12 AM, Today</span>
                            </div>
                            <div class="message my-message">Are we meeting today?</div>                                    
                        </li>                               
                        <li class="clearfix">
                            <div class="message-data">
                                <span class="message-data-time">10:15 AM, Today</span>
                            </div>
                            <div class="message my-message">Project has been already finished and I have results to show you.</div>
                        </li> -->
                    </ul>
                </div>
                <div class="chat-message clearfix">
                    <div class="input-group mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-send"></i></span>
                        </div>
                        <input id="CMSG" type="text" class="form-control" placeholder="Enter text here...">                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ajaxloader" style="display: none;"></div>
 
<!-- /*****CHAT FUNCTION WITH FIREBASE START****** */ -->
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>
<script src="<?php echo $this->Url->build('/');?>js/firebase/init-firebase.js"></script>
<script src="<?php echo $this->Url->build('/');?>js/firebase/main.js"></script>
<script>
    var table = {};
    var avtr = {};
    var iterateMe = <?=json_encode($userLists)?>;
    
    var TABLEN = '';
	var TABLE;
    var chatReceiverId = '<?php echo $usersD->id;?>';
    var chatsenderId = '<?php echo $this->request->session()->read('RitevetUsers.id');?>';
    var senderPicture = '<?php echo $this->Url->build('/');?>img/uploads/users/<?php echo $senderAvt->profile_picture;?>';
    var receiverPicture = '<?php echo $this->Url->build('/');?>img/uploads/users/<?php echo $receiverAvt->profile_picture;?>';

	//$(window).load(function() {
	$(document).ready(function() {
        jQuery('.ajaxloader').fadeIn();
        var first = 0;
        var userList = <?=json_encode($userLists)?>;

        getdatafunction();
    });
    
    async function checktable(chatsenderId,chatReceiverId){
        TABLEN = chatsenderId+'+'+chatReceiverId;
        //alert(TABLEN);
        return new Promise((resolve, reject) => {
            firebase.database().ref('/one_to_one/'+TABLEN).once('value').then(function(snapshot) {
                if(snapshot.numChildren() < 1){
                    TABLEN = chatReceiverId+'+'+chatsenderId;
                }
                //alert("checkTable="+TABLEN);
                //jQuery('.ajaxloader').fadeOut();
                resolve(TABLEN)
            });
        })
    }

    async function getdatafunction(){
        await checktable(chatsenderId,chatReceiverId);
        //alert("aftercheckcall="+TABLEN);
        firebase.database().ref('/one_to_one/'+TABLEN).once('value').then(function(snapshot) {
            console.log(snapshot);
            var total = snapshot.numChildren();
            // alert(total);
            //alert(snapshot);

            var db_ref = firebase.database().ref('one_to_one/'+TABLEN);
            db_ref.on('child_added', function(snapshot2) {
                //alert(snapshot2.val().chat_message);
                var OWNID = '<?php echo $this->request->session()->read('RitevetUsers.id');?>';
                var chatSenderId = snapshot2.val().chatSenderId;
                var chatTime = snapshot2.val().chat_time;
                var chatDate = snapshot2.val().chat_date;
                var messageText = snapshot2.val().chat_message;
                var mesaage_Side = '';
                var AVTR_cla = 'my-message';
                if(chatSenderId == OWNID){
                    mesaage_Side = ' text-right';
                    AVTR_cla = 'other-message float-right';
                }
                
                var appEND = '<li class="clearfix"> \
                    <div class="message-data'+mesaage_Side+'"> \
                        <span class="message-data-time">'+chatDate+', '+chatTime+'</span> \
                    </div> \
                    <div class="message '+AVTR_cla+'"> \
                        <img src="'+(chatSenderId == OWNID ? senderPicture : receiverPicture)+'" class="user-picture" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"> \
                        <div class="message-text">'+messageText+'</div> \
                    </div> \
                </li>';
                //alert(appEND);
                $("#chat_list_display").append(appEND);
                $("#chat_list_display").scrollTop($("#chat_list_display")[0].scrollHeight);
            });
            jQuery('.ajaxloader').fadeOut();
            return true;

        });

    }

    $("#CMSG").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            sendmsg();
        }
    });

	function sendmsg(){
		//alert(TABLEN);
		var chatsenderId = '<?php echo $this->request->session()->read('RitevetUsers.id');?>';
		var chatReceiverId = '<?php echo $usersD->id;?>';
		var chatDateTime = '<?php echo date('Y-m-d H:i:s');?>';
		var chatDate = '<?php echo date('Y-m-d');?>';
        var messageText = $("#CMSG").val();
        var chatTime = '<?php echo date('h:i a');?>';
		var type = 'Text';
		//alert(messageText);
		//alert(chatsenderId);
		//alert(chatReceiverId);
		//alert(chatDate);
		//alert(chatTime);
		if(chatsenderId ==''){
			window.location.href = "<?php echo $this->Url->build('/');?>";
		}
		if( messageText !=''){
			saveMessage(chatsenderId,chatDate,messageText,chatTime,TABLEN,type='Text')
			saveMessageToDB(chatsenderId,chatReceiverId,messageText,chatDateTime)
			$("#CMSG").val('');
            var appEND = '<li class="clearfix">\
                <div class="message-data text-right">\
                    <span class="message-data-time"><?php echo date('m-d-Y, h:i a');?></span>\
                </div>\
                <div class="message-container float-right"> \
                    <img src="'+senderPicture+'" class="user-picture" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"> \
                    <div class="message other-message">'+messageText+'</div> \
                </div>\
            </li>';
            //$("#chat_list_display").append(appEND);
		}
	}
	
	function saveMessageToDB(chatsenderId, chatReceiverId, messageText, chatDateTime){
        // Write the chat data to your database
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'saveChatMessagesToDB']);?>",
            data: {
              chatsenderId: chatsenderId,
              chatReceiverId: chatReceiverId,
              messageText: messageText,
              chatDateTime: chatDateTime
            },
            success: function(data) {
              console.log("Chat saved successfully!");
            },
            error: function(xhr, status, error) {
              console.log("Error saving chat: " + error);
            }
        });
	}
</script>
<!-- /*****CHAT FUNCTION WITH FIREBASE END****** */ -->
		