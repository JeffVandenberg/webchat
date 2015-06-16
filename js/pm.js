/**
 * Created by JeffVandenberg on 5/24/2015.
 */
$(function() {
    $("#message-box").keypress(function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            var messageBox = $("#message-box"),
                message = messageBox.val();
            localStorage.setItem('webchat', message);
            messageBox.val('');
        }
    });
});