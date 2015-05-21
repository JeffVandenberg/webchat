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
    <script type="application/javascript" src="bower_components/foundation/js/foundation.min.js"></script>
    <link type="text/css" rel="stylesheet" href="bower_components/foundation/css/foundation.min.css" />
    <link type="text/css" rel="stylesheet" href="style/chat.css" />
</head>
<body>
<div class="top-bar" role="navigation" data-topbar>
    <ul class="title-area">
        <li class="name">
            <h1><a href="#">WebSocket Chat</a></h1>
        </li>
        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
            <li class="has-dropdown">
                <a href="#">Menu</a>
                <ul class="dropdown" id="submenu">
                    <li><a href="#" id="logout">Logout</a></li>
                    <li><a href="#">Change Name</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <section class="middle tab-bar-section">
        <div id="notification-message" class="title"></div>
    </section>
</div>
<audio id="sound-player" preload="auto" src="sounds/button-48.wav">
<!--    <source id="sound-player-src" src="sounds/button-48.wav" type="audio/wav" />-->
</audio>
<div id="login" class="panel">
    <label for="username">User Name</label><input type="text" name="username" id="username" /><br />
    <input type="button" id="login-button" value="Login" />
</div>
<div id="chat-container">
    <div id="header" class="row">
        <div class="small-1 columns">
            <label for="roomlist">
                Room:
            </label>
        </div>
        <div class="small-11 columns">
            <select id="roomlist"></select>
        </div>
    </div>
    <div class="row panel">
        <div class="small-10 columns">
            <div id="message-container"></div>
            <label for="message-box"></label> <input type="text" id="message-box" />
            <a id="send-message" class="button tiny round" href="#">Send</a>
            <a id="logout-button" class="button tiny round" href="#">Logout</a>
        </div>
        <div class="small-2 columns">
            <div id="roomlist-container">
                <div id="userlist"></div>
            </div>
        </div>
    </div>
</div>
<div id="chat-container">
</div>
</body>
</html>
