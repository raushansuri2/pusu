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
            <div id="plist" class="people-list">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <div class="clearfix"></div>
                <ul class="list-unstyled chat-list mt-2 mb-0" id="user-list">
                    <!-- chat list items will be appended here -->
                    <li class="clearfix">-->
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                        <div class="about">
                            <div class="name">Vincent Porter</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>                                            
                        </div>
                    </li>
                    <li class="clearfix">-->
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                        <div class="about">
                            <div class="name">Vincent Porter</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>                                            
                        </div>
                    </li>
                    <li class="clearfix">-->
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                        <div class="about">
                            <div class="name">Vincent Porter</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>                                            
                        </div>
                    </li>
                </ul>
            </div>
            <div class="chat">
                <div class="chat-header clearfix">
                    
                </div>
                <div class="chat-history">
                    <ul class="m-b-0" id="chat_list_display">
                        <!-- chat messages will be appended here -->
                    </ul>
                </div>
                <div class="chat-message clearfix">
                    
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
<!--<script src="<?php echo $this->Url->build('/');?>js/firebase/main.js"></script>-->
<script>
// Get a reference to the database service
var database = firebase.database();

// Get a reference to the chat list
var userList = document.getElementById('user-list');

// Get the user ID from the session
var currentUserId = '<?php echo $this->request->session()->read('RitevetUsers.id');?>';

// Get a reference to the one_to_one node
var oneToOneRef = database.ref('one_to_one');

// Fetch all users that the current user has chatted with
oneToOneRef.once('value', function(snapshot) {
    snapshot.forEach(function(childSnapshot) {
        var chatId = childSnapshot.key;
        console.log(childSnapshot)
        var chatRef = database.ref('one_to_one/' + chatId);
        chatRef.once('value', function(chatSnapshot) {
            var chat = chatSnapshot.val();
            var otherUserId = null;
            chatSnapshot.forEach(function(child) {
                if (child.val().chatSenderId !== currentUserId) {
                    otherUserId = child.val().chatSenderId;
                }
            });
            // console.log(otherUserId)
            if (otherUserId !== null) {
                var usersRef = database.ref('child_added/' + otherUserId);
                usersRef.once('value', function(userSnapshot) {
                    var user = userSnapshot.val();
                    if (user !== null) {
                        var listItem = document.createElement('li');
                        listItem.className = 'clearfix';
                        var img = document.createElement('img');
                        img.src = user.profile_picture ? user.profile_picture : 'default-avatar.jpg';
                        img.alt = 'avatar';
                        var about = document.createElement('div');
                        about.className = 'about';
                        var name = document.createElement('div');
                        name.className = 'name';
                        name.textContent = user.name ? user.name : 'Unknown User';
                        var status = document.createElement('div');
                        status.className = 'status';
                        status.textContent = 'online';
                        about.appendChild(name);
                        about.appendChild(status);
                        listItem.appendChild(img);
                        listItem.appendChild(about);
                        userList.appendChild(listItem);
                    } else {
                        console.log('User not found');
                    }
                });
            }
        });
    });
});

</script>