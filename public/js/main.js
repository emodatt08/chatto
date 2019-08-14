 $(document).ready(function(){
     var conn = new WebSocket("ws://localhost:8080");
     var chatForm = $('.chat-form'),
     messageInputField = chatForm.find('#message'),
     messageList = $('.message-list'),
     usernameForm = $('.username-setter');
     usernameInput = usernameForm.find('.username-input');

     chatForm.on('submit',function(e){
            e.preventDefault();
            //var message = messageInputField.val();
         var message ={
                text: messageInputField.val(),
                sender: $.cookie('chat_name'),
                type: "message"
         }
            conn.send(JSON.stringify(message));
            messageList.prepend('<li>' + message.text + '</li>')
            messageInputField.val('');
     });
     usernameForm.on('submit', function(e){
         e.preventDefault();
         var chatName = usernameInput.val();
         if(chatName.length > 0){
             $.cookie('chat_name', chatName);
         }
         $('.username').text(chatName);
     });
     conn.onopen = function (e) {
        $.ajax({
            url:'/chatto/views/messages.php',
            dataType:'json',
            success: function(data){
                $.each(data, function(){
                    //console.log(data);
                    messageList.prepend('<li>' + this.text + '</li>');
                })
            }
        });
         var chatName = $.cookie('chat_name');
         if(!chatName){
             var timeStamp = (new Date()).getTime();
             chatName = "anonymous" + timeStamp;
             $.cookie('chat_name', chatName);
         }
         $('.username').text(chatName);
     };

     conn.onmessage = function (e) {
         console.log(e.data);
         messageList.prepend('<li>' + e.data + '</li>')
     };
})