<?php
	function getConnect() {
		$config = parse_ini_file('config/db.ini');
		$db = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['user'], $config['password']);
		return $db;
	}

/**
 * @param string $login
 * @param string $password
 * @param PDO $db
 */

	function createUser($login, $password, $db){
		$query = $db->prepare("INSERT INTO `user` (login, password) VALUES (:login, :password)");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->execute();
		
	}

/**
 * @param int $id
 * @param string $wish
 * @param PDO $db
 */
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

	function generateWishes($argv) {
		$wishDB=array();

		$file = fopen('wishes.txt', 'r');
		while(!feof($file)){
			$string = fgets($file);
			$wishDB[] = trim($string);
		}

		for($i = 0; $i < $argv[1]; $i++){
			$wishAmount = rand(1,$argv[2]);

			for($j = 0; $j < $wishAmount; $j++){
				$temp = rand(0, 23);
				addWish($i+1, $wishDB[$temp], getConnect());
			}
		}
	}

	include('vendor/autoload.php');
	$faker = Faker\Factory::create();

	generateUser($faker, $argv[1]);
	generateWishes($argv);

	?>