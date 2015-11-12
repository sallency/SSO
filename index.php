<?php
    error_reporting(E_ALL);
    //session的句柄设为redis
    ini_set('session.save_handler', 'redis');
    //session的保存路径设为redis服务器的tcp链接
    ini_set('session.save_path', 'tcp://your.redisserver.domian:6379');
    //使用session_set_cookie_params来设置session_start()时
    //在客户端当前域下保存的session的cookie标识的生存时间，路径，域
    session_set_cookie_params(3600, '/', '.mysso.com');
    //开启session时，session会在客户端需找当前同源域下的键名为session_name()的cookie
    //此cookie存储着session_id()的值，服务器根据此标示来识别那条session记录时当前客户端的
    //若不存在会按session_set_cookie_params给定的参数设定一条cookie在本地
    session_start();

    if (isset($_SESSION['user'])) {
        echo "welcome!" . $_SESSION['user'] . '<br/>';
        //这里讲明下redis是如何存放session的
        $redis = new Redis();
        $redis->connect('your.redisserver.domian', 6379);
        //PHPREDIS_SESSION:session_id() 的组合键为键名，以string类型存放在redis服务器中
        $session = $redis->get('PHPREDIS_SESSION:' . session_id());
        var_dump($session);
    } else {
        if (isset($_POST['submit'])) {
            $_SESSION['user] = $_POST['user];
            $_SESSION['password'] = $_POST['password'];
            header("location:/");
        } else { ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>sso_redis</title>
            </head>
            <body>
                <div>
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                    <input type="text" name="user" />
                    <input type="password" name="password" />
                    <input type="submit" name="submit" value="submit">
                    </form>
                </div>
            </body>
            </html>  
<?php
        }//end post if
    }//end user if
    ?>
