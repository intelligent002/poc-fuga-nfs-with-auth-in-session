<html>
<head>
    <style>
        body {
            background-color: #fff;
            color: #222;
            font-family: Tahoma
        }
    </style>
</head>
<body>
<center>
    <br/>
    <br/>
    <br/>
    <img alt="will be rendered only if logged in"
         src="./download.php?disposition=inline&file=www/users/1/logo.png"
         width="250" height="83"/>
    <br/>
    <br/>
    download some file:
    <a href="./download.php?disposition=attachment&file=www/users/1/bla.txt"
       target="_blank">bla.txt</a><br/>
    <br/>
    <br/>
    <a href="./session_login.php">login</a> | <a href="./session_logout.php">logout</a>
</center>
</body>