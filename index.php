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
    <link type="text/css" rel="stylesheet" href="bower_components/foundation/css/foundation.min.css"/>
    <link type="text/css" rel="stylesheet" href="style/foundation-icons.css"/>
    <link type="text/css" rel="stylesheet" href="style/chat.css"/>
</head>
<body>
<nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
        <li class="name">
            <h1><a href="#">Web Sockets</a></h1>
        </li>
        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
            <li class="has-dropdown">
                <a href="#">User Controls</a>
                <ul class="dropdown">
                    <li><a href="#" id="logout">Logout</a></li>
                    <li><a href="#">Edit Profile</a></li>
                </ul>
            </li>
        </ul>

        <!-- Left Nav Section -->
        <div class="left">
            <div id="notification-message" class="title"></div>
        </div>
    </section>
</nav>
<audio id="sound-player" preload="auto" src="sounds/button-48.wav">
    <!--    <source id="sound-player-src" src="sounds/button-48.wav" type="audio/wav" />-->
</audio>
<div id="login-form" class="panel">
    <label for="username">User Name</label><input type="text" name="username" id="username"/><br/>
    <input type="button" id="login-button" value="Login"/>
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
    <div style="float: right; width: 250px;margin-left: -250px;">
        <div id="roomlist-container">
            <div id="userlist"></div>
        </div>
    </div>
    <div style="margin-right: 250px;">
        <div id="message-container"></div>
        <label for="message-box"></label> <input type="text" id="message-box" />
        <a id="send-message" class="button tiny round" href="#">Send</a>
    </div>
</div>
<script type="application/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="application/javascript" src="js/chat.js"></script>
<script type="application/javascript" src="js/sortelements.js"></script>
<script type="application/javascript" src="bower_components/foundation/js/foundation.js"></script>
<script type="application/javascript" src="bower_components/modernizr/modernizr.js"></script>
<script>
    $(document).foundation();
</script>
</body>
</html>
