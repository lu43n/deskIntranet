chat = {
    'toggleChat': function (id) {
        if($('#chat-' + id).hasClass('minimized'))
        {
            $('#chat-' + id).removeClass('minimized').css('bottom', '0px');
            $.cookie('chat-' + id, 'open');
        }
        else
        {
            $('#chat-' + id).addClass('minimized').css('bottom', '-269px');
            $.cookie('chat-' + id, 'minimized');
        }
    },
    'closeChat': function (id) {
        $('#chat-' + id).remove();
        $.removeCookie('chat-' + id);
    },
    'setUserActivity': function () {
        $.ajax({
            url: chatConfig.setUserActivity,
            global: false,
            type: "POST",
            dataType: "json"
        });
    },
    'refreshChatList': function () {
        $.ajax({
            url: chatConfig.refreshUsersList,
            type: "POST",
            dataType: "json",
            global: false,
            async: false,
            success: function (data) 
            {
                if(data != null)
                {     
                    $('#chatsList .list').html(data.html);
                }
            }
        });
    },
    'sendMessage': function (event, id) {
        if(event.keyCode == 13 && event.shiftKey == 0)  
        {
            event.preventDefault();
            
            var textarea = $('#chat-' + id + ' textarea');

            message = textarea.val();

            if (message != '') 
            {
                $.ajax({
                    url: chatConfig.sendMessage,
                    type: "POST",
                    data: { recipient: id, message: message },
                    dataType: "json",
                    global: false,
                    success: function (data) 
                    {
                        if(data != null && data.type == 'success')
                        {
                            $('#chat-' + id + ' .chat').append(data.output);
                            $('#chat-' + id + ' .chat').animate({scrollTop: 9999999},200);
                            chat.refreshChats();
                        }
                        
                        if(data != null && data.type == 'error')
                        {
                            setNotification(data.message, 'error');
                        }
                    }
                });
            }


            $('#chat-' + id + ' textarea').val(null);

            return;
	}
    },
    'openChat': function (id) {
        $.ajax({
            url: chatConfig.openChat,
            type: "POST",
            data: { recipient: id },
            dataType: "json",
            async: false,
            global: false,
            success: function (data) 
            {
                if(data != null)
                {     
                    if($('#chatBoxes #chat-' + id).length)
                    {
                        if($('#chat-' + id).hasClass('minimized'))
                        {
                            chat.toggleChat(id);
                        }
                    }
                    else
                    {
                        $('#chatBoxes').append(data.html);
                        $('#chat-' + id + ' textarea').focus();
                    }
                    
                    $('#chat-' + id + ' .chat').animate({scrollTop: 9999999},200);
                    
                    if($.cookie('chat-' + id) == 'minimized')
                    {
                        chat.toggleChat(id);
                    }
                    else
                    {
                        $.cookie('chat-' + id, 'open');
                    }
                }
            }
        });
    },
    'refreshChats': function () {
        $.ajax({
            url: chatConfig.refreshChats,
            type: "POST",
            dataType: "json",
            global: false,
            success: function (data) 
            {
                if(data != null)
                {
                    $.each(data.chats, function (i, chatMessage) {

                        if($('#chat-' + chatMessage.sender_id).length)
                        {
                            $('#chat-' + chatMessage.sender_id + ' .chat').append(chatMessage.output);
                        }
                        else
                        {
                            chat.openChat(chatMessage.sender_id);
                            if($('#chat-' + chatMessage.sender_id).length)
                            {
                                $('#chat-' + chatMessage.sender_id + ' .chat').append(chatMessage.output);
                            }
                        }
                        
                        $('#chat-' + chatMessage.sender_id + ' .chat').animate({scrollTop: 9999999},200);
    
                        if(($('#chat-' + chatMessage.sender_id).hasClass('minimized') || $('#chat-' + chatMessage.sender_id + ' textarea').not(':focus')) && notifyHeadSet == false)
                        {
                            notifyHeadSet = true; 
                                
                            interval1 = setInterval(function () { 
                                
                                if($('#chat-' + chatMessage.sender_id + ' textarea').is(':focus')) 
                                { 
                                    notifyHeadSet = false; 
                                    clearInterval(interval1); 
                                } 
                                
                                $('#chat-' + chatMessage.sender_id + ' .head').effect("highlight", {color: '#999'}, 1000); 
                                
                            }, 1000);
                             
                        }
                        
                        $('#userChatList-' + chatMessage.sender_id).addClass('unreaded');
                        
                        if($(window).not(':focus') && notifyTitleSet == false)
                        {
                            notify = 0;
                            notifyTitleSet = true; 

                            interval2 = setInterval(function () {
                                
                                $(window).mousemove(function () {
                                    notifyTitleSet = false;
                                    $('title').text(documentTitle); 
                                    clearInterval(interval2); 
                                });

                                if($(window).not(':focus'))
                                {
                                    if(notify == 0) 
                                    { 
                                        notify = 1; 
                                        $('title').text('Nowa wiadomość..'); 
                                    } 
                                    else 
                                    { 
                                        notify = 0; 
                                        $('title').text(documentTitle); 
                                    } 
                                }

                            }, 1000);

                        }
                    
                    });
                }
                
                
            }
        });
    },
    'openInitialChatboxes': function () {
        $.ajax({
            url: chatConfig.getInitChatBoxes,
            type: "POST",
            dataType: "json",
            global: false,
            success: function (data) 
            {
                if(data.chats != null)
                {
                    $.each(data.chats, function (i, chatBox) {
                        chat.openChat(chatBox);
                    });
                }
                
                
            }
        });
    }
};

$(document).ready(function () {

    documentTitle = $('title').text();
    notifyTitleSet = false;
    notifyHeadSet = false;
    
    setInterval (chat.refreshChats, 2000);
    setInterval (chat.refreshChatList, 10000);
    setInterval (chat.setUserActivity, 60000);

    $.idleTimer(180000);

    $(document).bind("active.idleTimer", function(){
        chat.setUserActivity();
        chat.refreshChatList();
    });
    
    chat.openInitialChatboxes();
    
    $('#chatBoxes .chatBox').live('click', function () {
        $(this).find('textarea').focus();
    });
    
    $('#chatBoxes textarea').live('keypress', function(event) {
        chat.sendMessage(event, $(this).data('chatid'));        
    });
    
    $('#chatBoxes a[data-action="toggle"]').live('click', function () {  
        chat.toggleChat($(this).data('chatid'));
        return false;
    });
    
    $('#chatBoxes a[data-action="close"]').live('click', function () {
        chat.closeChat($(this).data('chatid'));
        return false;
    });
    
    $('#chatsList a[data-action="open"]').live('click', function () {
        chat.openChat($(this).data('recipientid'));
        return false;
    });
});