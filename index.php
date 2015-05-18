<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/14/15
 * Time: 2:45 PM
 */

?>
<html>
<head>
    <title>
        Test Web Chat
    </title>
    <script type="application/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
    <script type="application/javascript" src="js/chat.js"></script>
    <script type="application/javascript" src="js/sortelements.js"></script>
    <link type="text/css" rel="stylesheet" href="style/chat.css" />
</head>
<body>
<div id="chat-container">
    <div id="userlist"></div>
    <div id="message-container"></div>
    <label for="message-box">Message:</label> <input type="text" id="message-box" />
    <input type="submit" id="send-message" />
</div>
</body>
</html>
