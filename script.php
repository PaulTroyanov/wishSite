<?php
	function getConnect() {
		$config = parse_ini_file('config/db.ini');
		$db = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['user'], $config['password']);
		return $db;
	}

	function createUser($login, $password, $db){
		$query = $db->prepare("INSERT INTO `user` (login, password) VALUES (:login, :password)");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->execute();
		
	}

	function addWish($id, $wish, $db) {
		$query = $db->prepare("INSERT INTO `wishes` (id, wish) VALUES (:id, :wish)");
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->bindParam(':wish', $wish, PDO::PARAM_STR);
		$query->execute();
	}

	function generateUser($faker, $amount) {
    	for($i = 0; $i < $amount; $i++){

    		$name = $faker->name;
    		$password = $faker->password;

    		createUser($name, $password, getConnect());
    	}
	}

	include('vendor/autoload.php');
	$faker = Faker\Factory::create();

	generateUser($faker, $argv[1]);

	?>