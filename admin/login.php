<?php
require '../include.php';
require_once 'head.php';
?>
    <title>登录</title>
    <link rel="stylesheet" href="styles/login.css">
</head>

<body class="login-body">
    <div class="loginwrap">
        <div class="box-hd">
            <div class="logo"></div>
            <div class="rect"></div>
        </div>
        <div class="box-bd">
            <form action="doLogin.php" method="post" name="login_form" autocomplete="off" id="loginForm">
                <div class="login-error"></div>
                <div class="input-wrap">
                    <span class="wsicon  wsicon-person"></span>
                    <input type="text" id="username" name="username" value="" maxlength="32" autocomplete="off" />
                </div>
                <div class="input-wrap">
                    <span class="wsicon wsicon-lock"></span>
                    <input type="password" name="password" maxlength="32" id="password" autocomplete="off" />
                </div>
                <p class="error-tip" id="errorTip"><span class="errorTipTxt"></span></p>
                <input type="submit" class="login-btn" id="login-btn" value="登录" ></a>
            </form>
        </div>
    </div>
    <div class="flashBg"></div>
    <div class="footer">
        <p style="font-size: 16px;color:#fff" si="slogan">卓越的互联网业务平台提供商</p>
        <p style="font-size:12px">www.ChinaNetCenter.com</p>
        <p>Copyright 2010-2016 ChinaNetCenter.ALL Rights Reserved.</p>
    </div>
    <script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
</body>

</html>