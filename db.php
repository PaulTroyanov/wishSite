<?php
	function getConnect() {
		$config = parse_ini_file('config/db.ini');
		$db = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['user'], $config['password']);
		return $db;
	}

	function getUserList($db, $status=1) {
		$result = array();
		$query = $db->prepare("SELECT * FROM user");
		$query->execute();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$result[] = $row;
		}
		return $result;
	}

	function createUser($login, $password, $db){
		$query = $db->prepare("INSERT INTO `user` (login, password) VALUES (:login, :password)");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->execute();
		
	}

	function getUser($login, $db){
		$result = array();
		$query = $db->prepare("SELECT * FROM user WHERE login = :login");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		return $row;
	}

	function incrCounter($login, $db){
		$query = $db->prepare("SELECT counter FROM user WHERE login = :login");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		
		$counter = $row['counter'] + 1;

		$query = $db->prepare("UPDATE user SET counter = :counter WHERE login = :login");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':counter', $counter, PDO::PARAM_INT);
		$query->execute();

	}

	function addWish($id, $wish, $db) {
		$query = $db->prepare("INSERT INTO `wishes` (id, wish) VALUES (:id, :wish)");
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->bindParam(':wish', $wish, PDO::PARAM_STR);
		$query->execute();
	}

	function getWishList($id, $db) {
		$result = array();
		$query = $db->prepare("SELECT wish FROM wishes WHERE id = :id");
		$query->bindParam(':id', $id, PDO::PARAM_STR);
		$query->execute();

		while ($row = $query->fetch(PDO::FETCH_ASSOC)){
			$result[] = $row;
		}
		return $result;
	}

	//--------------------------------------------------------------------------

	    function show_header(){
        ?>
        <html>
            <body>
        <?php
    }
    
    function show_footer(){
        ?>
            </body>
        </html>
        <?php
    }

    function check_user($login, $password){

    	$userInfo = getUser($login, getConnect());

    	if(($userInfo['login'] == $login) && ($userInfo['password'] == $password)){
    		incrCounter($login, getConnect());
    		return true;
    	}
    	else
    		return false;
    }
    
    function page_index(){
        show_header();
        
        include('sub.html');
        
        show_footer();
    }
    
    function page_reg(){
        show_header();
        
        include('registration.html');
        
        show_footer();
    }
