(function ( $ ) {
 
    $.fn.conversate = function(options) {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
        }, options );
        getConversations();
        // Click handlers
        $('body').on('click', '#send-msg-btn', function(e){
            e.preventDefault();
            sendMessage($('#msg-input').val());
        });
        // Return.
        return this;
 
    };

    function getConversations() {
        var formData = new FormData();
        formData.append('action', 'do_current_user_conversations_ajax');
        
        $.ajax({
            url: shadowcore_ajax_script.ajaxurl,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response){
                if(response.success) { 
                    showConversationList(response.data);
                } else{
                    onServerError(response.data);
                }
            }
        });
    }

    function showConversationList(conversations) {

        var isActive = false;
        $.each(conversations, function(i, conversation){
            if(i == 0) isActive = true;
            showConversationListItem(conversation, true);
        });
    }

    function showConversationListItem(conversation, isActive) {

        var conversationItemHTML = '<div class="lv-item media active">';
        conversationItemHTML =  conversationItemHTML + '<div class="lv-avatar pull-left">'
        conversationItemHTML =  conversationItemHTML + '<img src="' + conversation.image_url + '" alt="">'
        conversationItemHTML =  conversationItemHTML + '</div>'
        conversationItemHTML =  conversationItemHTML + '<div class="media-body">'
        conversationItemHTML =  conversationItemHTML + '<div class="lv-title">' + conversation.name + '</div>'
        conversationItemHTML =  conversationItemHTML + '<div class="lv-small">' + conversation.description + '</div>'
        conversationItemHTML =  conversationItemHTML + '</div>'
        conversationItemHTML =  conversationItemHTML + '</div>'

        $('#conversation-list').append(conversationItemHTML);

        if(isActive) showMessagesInConversation(conversation);
    }

    function showMessagesInConversation(conversation) {
        $.each(conversation.messages, function(i, message){
            showMessage(message, true);
        });
    }

    function showConversationMessages(message) {

        var messageItemHTML = '<div class="lv-item media">'
        messageItemHTML =  messageItemHTML + '<div class="lv-avatar pull-left">'
        messageItemHTML =  messageItemHTML + '<img src="' + message.image_url + '" alt="">'
        messageItemHTML =  messageItemHTML + '</div>'
        messageItemHTML =  messageItemHTML + '<div class="media-body">'
        messageItemHTML =  messageItemHTML + '<div class="ms-item">'
        messageItemHTML =  messageItemHTML + message.message;
        messageItemHTML =  messageItemHTML + '</div>'
        messageItemHTML =  messageItemHTML + '<small class="ms-date"><i class="zmdi zmdi-time"></i> '+ message.time + '</small>'
        messageItemHTML =  messageItemHTML + '</div>'
        messageItemHTML =  messageItemHTML + '</div>'
        $('#message-list').append(conversationItemHTML);

    }

    function sendMessage(text) {
        var formData = new FormData();
        var params = $('#sb_conversation_chat_form').serializeArray();

        $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });
        formData.append('action', 'send_message_ajax');
        
        $.ajax({
            url: shadowcore_ajax_script.ajaxurl,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response){
                if(response.success) { 
                    console.log(response.data);
                } else{
                    onServerError(response);
                }
            }
        });
    }

    function onServerError(responseData) {
        console.log(responseData);
    }
 
}(jQuery));

jQuery(document).ready(function($)
{
    $('#messages-main').conversate({});
}); 