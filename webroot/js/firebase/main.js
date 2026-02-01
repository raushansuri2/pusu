// Saves a new message on the Cloud Firestore.
function saveMessage(chatsenderId,chatDate,messageText,chatTime,TABLEN,type) {
    //var database = firebase.database();
    //alert(messageText);
    // Add a new message entry to the Firebase database.
    //return firebase.firestore().collection('messages').add({
    //return firebase.database().ref('/messages/').once('value').then(function(snapshot) {
    return firebase.database().ref('one_to_one/'+TABLEN+'/').push({
        attachment_path: '',
        chatSenderId: chatsenderId,
        chat_date: chatDate,
        chat_message:messageText,
        chat_receiver: '',
        chat_receiver_img: '',
        chat_sender: '',
        chat_sender_img:'',
        chat_time:chatTime,
        type:type,
    
    //timestamp: firebase.firestore.FieldValue.serverTimestamp()
    }).catch(function(error) {
        console.error('Error writing new message to Firebase Database', error);
    });
}

function checktable(TABLE){
    firebase.database().ref('/one_to_one/'+TABLE).once('value').then(function(snapshot) {
       return snapshot.numChildren();
   });
}



