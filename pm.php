<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/24/2015
 * Time: 10:56 AM
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

<div>
    This is a PM Window. Really. Imagine it!
</div>
<div class="panel">
    <div id="message-container"></div>
    <label for="message-box"></label> <input type="text" id="message-box" />
</div>

<script type="application/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="application/javascript" src="js/pm.js"></script>
<script type="application/javascript" src="js/sortelements.js"></script>
<script type="application/javascript" src="bower_components/foundation/js/foundation.js"></script>
<script type="application/javascript" src="bower_components/modernizr/modernizr.js"></script>
<script>
    $(document).foundation();
</script>
</body>
</html>
