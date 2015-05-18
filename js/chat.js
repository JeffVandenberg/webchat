var parameters = {
    action: 'ooc-login',
    id: 8,
    userName: ''
};

var chat = {
    connect: function(userName, action, id) {
        this.connection = new WebSocket('ws://wantonwicked.gamingsandbox.com:8080?' +
            'action=' + action +
            '&id=' + id +
            '&username=' + encodeURIComponent(userName)
        );

        this.connection.onopen = function() {
            alert("connected!");
        };

        this.connection.onmessage = function(e) {
            var container = $("#message-container");
            var data = JSON.parse(e.data);

            if(data.type == 'message') {
                var message = data.message;
                if(data.message.indexOf(parameters.userName) >= 0) {
                    message = message.replace(parameters.userName, '<span style="color:#f00;">' + parameters.userName + '</span>');
                }
                var newMessage = $('<div class="message">').html('[' + data.timestamp + '] ' + data.username + ': ' + message);
                container.append(newMessage);

                if(container.length) {
                    container.scrollTop(container[0].scrollHeight - (container.height()-18));
                }
            }
            else if(data.type == 'userlist') {
                for(var i = 0; i < data.data.length; i++) {
                    var username = data.data[i];

                    $("#userlist").append(
                        $("<div>")
                            .addClass('userentry')
                            .attr('id', 'userlist-' + username.toLowerCase())
                            .text(username)
                    );
                }
            }
            else if(data.type == 'userlist-update') {
                var username = data.data.username; //.replace('/[^\w]/', '');
                if(data.data.action == 'add') {
                    $("#userlist").append(
                        $("<div>")
                            .addClass('userentry')
                            .attr('id', 'userlist-' + username.toLowerCase())
                            .text(username)
                    );
                    $("#userlist .userentry").sortElements(function(a, b) {
                            return $(a).text().localeCompare($(b).text());
                        });
                }
                if(data.data.action == 'remove') {
                    $("#userlist-" + username.toLowerCase()).remove();
                }
            }

        };

        this.connection.onclose = function() {
            alert('closed connection');
            this.connection.open();
        };
    },

    sendMessage: function(message) {
        if(this.connection) {
            this.connection.send(message);
        }
        else {
            alert("no connection");
        }
    }
};

$(function() {
    do {
        parameters.userName = $.trim(prompt('Please Enter your user name').replace(/[^a-z0-9]/gmi, ""));
    } while (parameters.userName == '');

    chat.connect(parameters.userName, parameters.action, parameters.id);

    $("#message-box").keypress(function(e) {
        var code = e.keyCode || e.which;
        if(code == 13) {
            var messageBox = $("#message-box");
            var message = messageBox.val();
            chat.sendMessage(message);
            messageBox.val('');
        }
    });

    $("#send-message").click(function() {
        var messageBox = $("#message-box");
        var message = messageBox.val();
        chat.sendMessage(message);
        messageBox.val('');
    });
});