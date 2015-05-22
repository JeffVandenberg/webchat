var parameters = {
    action: 'ooc-login',
    id: 8,
    roomId: 1,
    userName: ''
};

var sound = {
    play: function(file) {
        var player = $("#sound-player");
        //player.attr('src', 'sounds/' + file);
        //$("#sound-player-src").attr('src', 'sounds/' + file);
        player[0].load();
        player[0].play();
        //player[0].load();
        //player[0].play();
        //document.getElementById("sound-player").play();
    }
};
var chat = {
    connect: function(userName, action, id, roomId) {
        this.connection = new WebSocket('ws://wantonwicked.gamingsandbox.com:8080?' +
            'action=' + action +
            '&id=' + id +
            '&username=' + encodeURIComponent(userName) +
            '&roomId=' + roomId
        );

        this.connection.onopen = this.openConnection;

        this.connection.onmessage = this.handleMessage;

        this.connection.onclose = this.closeCleanUp;
    },

    handleMessage: function(e) {
        var container = $("#message-container");
        var data = JSON.parse(e.data);

        if(data.type == 'message') {
            sound.play('button-41.wav');
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
            $("#userlist").empty();
            for(var i = 0; i < data.data.length; i++) {
                var user = data.data[i];

                $("#userlist").append(
                    $("<div>")
                        .addClass('userentry')
                        .attr('id', 'userlist-' + user.id)
                        .text(user.username)
                );
            }
        }
        else if(data.type == 'userlist-update') {
            sound.play('button-48.wav');
            var username = data.data.username; //.replace('/[^\w]/', '');
            var userid = data.data.id;

            if(data.data.action == 'add') {
                $("#userlist").append(
                    $("<div>")
                        .addClass('userentry')
                        .attr('id', 'userlist-' + userid)
                        .text(username)
                );
                $("#userlist").find(".userentry").sortElements(function(a, b) {
                    return $(a).text().localeCompare($(b).text());
                });
            }
            if(data.data.action == 'remove') {
                $("#userlist-" + userid).remove();
            }
        }
        else if(data.type == 'roomlist') {
            for(var i= 0; i < data.data.length; i++) {
                var roominfo = data.data[i];
                var room = $("<option>")
                    .val(roominfo.id)
                    .text(roominfo.name);
                $("#roomlist").append(room);
            }
            $("#roomlist").val(parameters.roomId);
        }
    },

    openConnection: function() {
        $("#notification-message").text('Connected to Server!');
        $("#login").hide();
        $("#chat-container").show();
    },

    closeConnection: function() {
        this.connection.close();
    },

    closeCleanUp: function() {
        $("#notification-message").text('Connection Closed!');
        $("#login").show();
        $("#chat-container").hide();
    },

    sendMessage: function(message) {
        var data = {
            action: 'room-message',
            data: {
                message: message
            }
        };
        this.sendCommand(data)
    },

    changeRoom: function(roomId) {
        $("#notification").text('Changing room').show().delay(2000).hide('slow');
        var data = {
            action: 'change-room',
            data: {
                roomId: roomId
            }
        };

        this.sendCommand(data);
    },

    sendCommand: function(jsonData) {
        if(this.connection) {
            this.connection.send(JSON.stringify(jsonData));
        }
        else {
            alert("no connection");
        }
    }
};

$(function() {
    $("#login-button").click(function() {
        var username = $("#username").val();
        if($.trim(username).replace(/[^a-z0-9]/gmi, "") !== '') {
            parameters.userName = username;
            chat.connect(parameters.userName, parameters.action, parameters.id, parameters.roomId);
        }
        else {
            alert('Please Enter a username')
        }
    });

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
        if($.trim(message) !== '') {
            chat.sendMessage(message);
            messageBox.val('');
        }
        else {
            alert('no message entered');
        }
    });

    $("#roomlist").change(function(e) {
        chat.changeRoom($(this).val());
    });

    $("#top-menu li").click(function(e) {
        alert('click');
    });
    $("#logout-class").click(function(e) {
        e.preventDefault();
        alert('hello!');
    });
    $("#logout-button").click(function() {
        chat.closeConnection();
    });
});