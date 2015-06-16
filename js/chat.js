// client connection parameters
// these will be sent on initial connection to the server
var parameters = {
    action  : 'ooc-login',
    id      : 8,
    roomId  : 1,
    userName: ''
};

var sound = {
    play: function (file) {
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
    type: '',
    // connection property information to send with requests to the server
    // this should be information passed back from the server
    // on window close, there should be a check for remaining connections
    // if there are connections, remove this instance and serialize the rest
    // then send it to storage for another chat window to pick up
    connections: {},
    isConnected: function () {
        return (localStorage.getItem('webchat-is-connected') == 'true');
    },
    connect: function (userName, action, id, roomId) {
        $("#notification-message").text('Connecting....');

        if(chat.isConnected()) {
            // register as a slave window
        }
        else {
            // establish new connection
            this.connection = new WebSocket('ws://wantonwicked.gamingsandbox.com:8080?' +
                'action=' + action +
                '&id=' + id +
                '&username=' + encodeURIComponent(userName) +
                '&roomId=' + roomId
            );

            this.connection.onopen = this.openConnection;

            this.connection.onmessage = this.handleMessage;

            this.connection.onclose = this.closeCleanUp;
        }
    },

    addUserListEntry: function (user) {
        $("#userlist").append(
            $("<div>")
                .attr('id', 'userlist-entry-' + user.id)
                .addClass('userentry')
                .append(
                $("<div>")
                    .attr('id', 'userlist-' + user.id)
                    .attr('data-dropdown', 'user-dropdown-' + user.id)
                    .attr('aria-controls', 'user-dropdown-' + user.id)
                    .attr('aria-expanded', 'false')
                    .addClass('dropdown')
                    .addClass('clickable')
                    .text(user.username)
                )
                .append(
                $('<ul>')
                    .attr('id', 'user-dropdown-' + user.id)
                    .addClass('f-dropdown')
                    .attr('data-dropdown-content', '')
                    .attr('aria-hidden', 'true')
                    .append(
                    $('<li>')
                        .append(
                        $('<a>')
                            .attr('href', '#')
                            .text('action for ' + user.username)
                    )
                )
            )
        );
        $(document).foundation('dropdown', 'reflow')
    },
    sortUserList    : function () {
        $("#userlist").find(".userentry").sortElements(function (a, b) {
            return $(a).find('.clickable').text().localeCompare(
                $(b).find('.clickable').text()
            );
        });
    },
    handleMessage   : function (e) {
        var container = $("#message-container");
        var data = JSON.parse(e.data);

        if (data.type == 'message') {
            sound.play('button-41.wav');
            var message = data.message;
            if (data.message.indexOf(parameters.userName) >= 0) {
                message = message.replace(parameters.userName, '<span style="color:#f00;">' + parameters.userName + '</span>');
            }
            var newMessage = $('<div class="message">').html('[' + data.timestamp + '] ' + data.username + ': ' + message);
            container.append(newMessage);

            if (container.length) {
                container.scrollTop(container[0].scrollHeight - (container.height() - 18));
            }
        }
        else if (data.type == 'userlist') {
            $("#userlist").empty();
            for (var i = 0; i < data.data.length; i++) {
                chat.addUserListEntry(data.data[i]);
            }
            chat.sortUserList();
        }
        else if (data.type == 'userlist-update') {
            sound.play('button-48.wav');

            if (data.data.action == 'add') {
                chat.addUserListEntry(data.data);
                chat.sortUserList();
            }
            if (data.data.action == 'remove') {
                $("#userlist-entry-" + data.data.id).remove();
            }
        }
        else if (data.type == 'roomlist') {
            $("#roomlist").empty();
            for (var i = 0; i < data.data.length; i++) {
                var roominfo = data.data[i];
                var room = $("<option>")
                    .val(roominfo.id)
                    .text(roominfo.name);
                $("#roomlist").append(room);
            }
            $("#roomlist").val(parameters.roomId);
        }
    },

    setIsConnected: function (status) {
        localStorage.setItem('webchat-is-connected', status);
    },
    openConnection: function () {
        chat.setIsConnected('true');
        $("#notification-message").text('Connected');
        $("#login-form").hide();
        $("#chat-container").show();
        $("#message-container").empty();
    },

    closeConnection: function () {
        this.connection.close();
    },

    closeCleanUp: function () {
        $("#notification-message").text('Disconnected');
        $("#login-form").show();
        $("#chat-container").hide();
    },

    sendMessage: function (message) {
        var data = {
            action: 'room-message',
            data  : {
                message: message
            }
        };
        this.sendCommand(data)
    },

    changeRoom: function (roomId) {
        $("#notification").text('Changing room').show().delay(2000).hide('slow');
        var data = {
            action: 'change-room',
            data  : {
                roomId: roomId
            }
        };

        this.sendCommand(data);
    },

    sendCommand: function (jsonData) {
        if (this.connection) {
            this.connection.send(JSON.stringify(jsonData));
        }
        else {
            alert("no connection");
        }
    }
};

$(window).bind('storage', function(e) {
    if(e.originalEvent.key == 'webchat') {
        alert('Received message: ' + e.originalEvent.newValue);
    }
});

$(function () {
    $("#login-button").click(function () {
        var username = $("#username").val();
        if ($.trim(username).replace(/[^a-z0-9]/gmi, "") !== '') {
            parameters.userName = username;
            chat.connect(parameters.userName, parameters.action, parameters.id, parameters.roomId);
        }
        else {
            alert('Please Enter a username')
        }
    });

    $("#message-box").keypress(function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            var messageBox = $("#message-box");
            var message = messageBox.val();
            chat.sendMessage(message);
            messageBox.val('');
        }
    });

    $("#send-message").click(function () {
        var messageBox = $("#message-box"),
            message = messageBox.val();

        if ($.trim(message) !== '') {
            chat.sendMessage(message);
            messageBox.val('');
        }
        else {
            alert('no message entered');
        }
    });

    $('#pm-window-open').click(function() {
        window.open('pm.php');
        return false;
    });

    $("#roomlist").change(function (e) {
        chat.changeRoom($(this).val());
    });

    // wire up menus
    $("#logout").click(function (e) {
        chat.closeConnection();
    });
    $("#logout-button").click(function () {
        chat.closeConnection();
    });
});