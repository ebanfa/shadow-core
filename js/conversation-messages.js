(function($) {

    $.fn.showUserConversations = function(options) {
    	var settings = $.extend({
      	    conversationList :'#conversation-list', 
            messageBox: '#conversation-message-box',
	    conversationMessageListHeader: '#conversation-message-list-header',
            conversationMessageListBody: '#conversation-message-list-body',
            queryUserConversationsForm: '#get-user-conversations-form',
	    queryUserConversationsSuccessHandler: showUserConversations,  
	    queryUserConversationsErrorHandler: showLoadUserConversationsError,
	    queryUserConversationsAjaxFunction: 'get_user_conversations_ajax',
	    sendUserConversationMessageForm: '#send-user-conversation-message-form',
            sendUserConversationMessageAjaxFunction: 'send_user_conversation_message_ajax',
            sendUserConversationMessageSuccessHandler: sendUserConversationMessageSuccessHandler,
            sendUserConversationMessageErrorHandler: sendUserConversationMessageErrorHandler
        }, options);
   
	// A reference to the currently active conversation
	var activeConversation = {};
	var conversationsList = {};
 	
	// First thing we do is to query the server for all conversations for the current user
        postMessageForm(settings.queryUserConversationsForm, settings.queryUserConversationsAjaxFunction,
	    settings.queryUserConversationsSuccessHandler, settings.queryUserConversationsErrorHandler);
	
	// Helper function to post form data via AJAX        
	function postMessageForm(formId, ajaxFunction, successHandler, errorHandler){
            var formData = new FormData();
            var params = $(formId).serializeArray();
            $.each(params, function(i, val) {
	   	formData.append(val.name, val.value);
            });
            formData.append('action', ajaxFunction);

            // Load the messages for this user
            $.ajax({
           	url: shadowcore_ajax_script.ajaxurl,
                processData: false,
           	contentType: false,
            	data: formData,
            	type: 'POST',
            	success: function(response){
	    	    if(response.success) { 
		    	successHandler(response.data);
           	    } else{
   		    	errorHandler(response.data);
               	    }
            	}
            });
	}

        // Display the conversation list
        function showUserConversations(conversations){
	    conversationsList = conversations;
	    var conversationList = settings.conversationList;
	    var messageInput = settings.messageInput;
	    // Clear the list
	    $(conversationList).empty();
	    // Loop through and display the conversations
	    // The first conversation in the list will be marked as active
	    // and have its messages displayed in the message box
	    $.each(conversations, function(i, conversation){
		if(i == 0){
		    conversation.active = true;
		    activeConversation = conversation;
		    showUserConversationMessages(conversation);
		} 
		else {
		    conversation.active = false;
		}
		showUserConversationListItem(conversation);
            });

	    // Setup the select conversation click handler
    	    $('body').on('click', '.conversation', function(event){

		if(!$(this).hasClass('active')) {
		    // First remove all elements with the active class
		    $('.conversation').removeClass('active');
		    // Add the active class to the selected conversation
		    $(this).addClass('active');

		    var selectedConversationId = $(this).data('conversation-id');
	    	    $.each(conversations, function(i, conversation) {           	    
			if(selectedConversationId === conversation.id){ 
			    conversation.active = true;
			    activeConversation = conversation;
			} else {
			    conversation.active = false; 
			}
			
		    });
		    showUserConversationMessages(activeConversation);
		}
	    });

	    // Setup the send message click handler
    	    $('body').on('click', '#send-message-btn', function(event){

		event.preventDefault();
        	postMessageForm(settings.sendUserConversationMessageForm, settings.sendUserConversationMessageAjaxFunction, 
		    settings.sendUserConversationMessageSuccessHandler, settings.sendUserConversationMessageErrorHandler);
	    });
	};

        // Display the conversation list
        function showUserConversationListItem(conversation){
            var activeClass = '';
	    var id = conversation.id;
            var text = conversation.description;
            var counterPartyName = conversation.counter_party_txt;
	    var conversationList = settings.conversationList;

            if(conversation.active){
  		activeClass = ' active';
            }
	    var conversationInboxItem = $('<div class="lv-item media conversation' +  activeClass + '" data-conversation-id="' + id + '"><div class="lv-avatar pull-left"></div></div>');            
            var conversationDetailsItem = $('<div class="media-body"><div class="lv-title">' + counterPartyName + '</div><div class="lv-small">' + text + '</div></div>');
	    conversationInboxItem.append(conversationDetailsItem);
	    $(conversationList).append(conversationInboxItem);
        };

	function showUserConversationMessages(conversation){
            settings.currentUser = conversation.current_user;
	    var conversationMessageList = settings.conversationMessageList;
            var conversationMessageListHeader = settings.conversationMessageListHeader;

            $(conversationMessageListHeader).empty();
            $(conversationMessageListHeader).text(conversation.counter_party_txt);
	    
            var conversationMessageListBody = settings.conversationMessageListBody;
	    $(conversationMessageListBody).empty();
	    $(conversationMessageListBody).css('min-height', 200);
	   
	    // Update the values of the send message form to reflect data from displayed conversation
	    $('#conversation-id').val(conversation.id); 
	    if(settings.currentUser.toString() === conversation.owner){
		$('#counter-party-id').val(conversation.counter_party);
	    }else {
		$('#counter-party-id').val(conversation.owner); 
	    }
	    $.each(conversation.messages, function (i, message){
          	showUserConversationMessage(message);
            });    
        }
        
      	function showUserConversationMessage(message){
	    var messageListItemClass = '';
            var messageAvatarClass = ' pull-left';
	    var currentUser = settings.currentUser;
            var conversationMessageListBody = settings.conversationMessageListBody;
            
	    if(currentUser.toString() === message.owner){
		messageListItemClass = ' right';
       		messageAvatarClass = ' pull-right';
	    }
	    var messageDate = message.message_date;
            var messageText = message.message;
            var messageListItem = $('<div class="lv-item media' + messageListItemClass +'"></div>');
            var messageAvatarItem = $('<div class="lv-avatar' + messageAvatarClass +'"><img src="img/profile-pics/1.jpg" alt=""></div>');
	    var messageListItemBody = $('<div class="media-body"><div class="ms-item">' + messageText + '</div></div>');
            var messageListItemDate = $('<small class="ms-date"><i class="md md-access-time"></i>' + messageDate + '</small>');

	    messageListItem.append(messageAvatarItem); 
	    messageListItemBody.append(messageListItemDate);
            messageListItem.append(messageListItemBody);
           
            $(conversationMessageListBody).append(messageListItem);
        }

        // Display the message box
        function showLoadUserConversationsError(data){

        }
      
        function sendUserConversationMessageSuccessHandler(data){
            showUserConversationMessage(data);
	}

        function sendUserConversationMessageErrorHandler(data){}

   	return this;
    }
} (jQuery));
