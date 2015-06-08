<?php
	include('db.php');

    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    session_start();

    if(!isset($_SESSION['user'])){
    	page_index();
        if(isset($_POST['sub'])){
            switch($_POST['sub']){
               
               case 'Submit':
                    if((isset($_POST['login']) && isset($_POST['pass'])) && strlen($_POST['login'])>0){
                    	
                    	$login = $_POST['login'];

                    	if($redis->get($login) > 3)
                    	{
                    		echo "<center>You're banned!</center>";
                    		$redis->expire($login, 45);
                    		break;
                    	}

                    	$redis->incr($login);
                    	$redis->expire($login, 15);

                        if(check_user($_POST['login'], $_POST['pass'])){
                            $_SESSION['user'] = true;
                            $_SESSION['login'] = $_POST['login'];
                            $_SESSION['pass'] = $_POST['pass'];
                            $_SESSION['counter'] = $data[$_POST['login']][1];
                            header('Location: /sitev2/account.php');
                            exit();
                        }
                        else
                        	echo "Fail to login";

					}
                    break;
                
                case 'Registration':
                    page_reg();
                    break;

                case 'Create':
                    if((isset($_POST['login_new']) && isset($_POST['pass_new'])) && strlen($_POST['login_new'])>0)
                        createUser($_POST['login_new'], $_POST['pass_new'], getConnect());
                    break;
            }
        }

    }
    else{

        echo "<p>Login: <a href='account.php'>".$_SESSION['login']."</a></p>";
        echo "<p>Password: ".$_SESSION['pass']."</p>";
        
        echo "<center><p style='margin-top:200px'>Hi, ".$_SESSION['login']."</p></center>";
    }


?>