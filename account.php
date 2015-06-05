<?php
	session_start();
    
    include('db.php');

    $user = getUser($_SESSION['login'], getConnect());

	echo "<p>You are logged in as <a href=''>".$user['login']."</a></p>";
	echo " <form method='post'>
        <p><input value='Log off' name='sub' type='submit'>
        <input value='Back to main page' name='sub' type='submit'></p>
        <p><input value='Add wishes' name='sub' type='submit'></p>   
    </form>";
    echo "<center><p style='margin-top:30px'>Hi, you have been logged in ".$user['counter']." times</p></center>";

    if(isset($_POST['sub']))
    {
    	switch($_POST['sub']){
    		case 'Log off':  			
    			session_destroy();
    			header('Location: /sitev2/index.php');
    			exit();
    			break;

    		case 'Back to main page':
    			header('Location: /sitev2/index.php');
    			exit();
    			break;

    		case 'Add wishes':
                include('wisher.html');
                include('wishList.html');
                break;


    		case 'ADD':
			   	addWish($user['id'], $_POST['wish'], getConnect());
                include('wisher.html');
                include('wishList.html');
                
                break;		
    			
    	}
    }
?>