<?php
include_once __DIR__ . "/../../config.php";
class DBController {
	private $host = DB_HOST;
	private $user = DB_USER;
	private $password = DB_PASSWORD;
	private $database = DB_NAME;
	private $conn;
	
	function __construct() {
		$this->conn = $this->connectDB();
	}
	
	function connectDB() {
	    $conn = mysqli_connect($this->host,$this->user,$this->password, $this->database);
		return $conn;
	}

	function runQuery($query) {
		//$query = mysqli_real_escape_string($this->conn, $query);
		$result = mysqli_query($this->conn, $query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
		//$query = mysqli_real_escape_string($this->conn, $query);
	    $result  = mysqli_query($this->conn, $query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
	
	function updateQuery($query) {
		//$query = mysqli_real_escape_string($this->conn, $query);
	    $result = mysqli_query($this->conn, $query);
		if (!$result) {
		    die('Invalid query: ' . mysqli_error($this->conn));
		} else {
			return $result;
		}
	}
	
	function insertQuery($query) {
		//$query = mysqli_real_escape_string($this->conn, $query);
	    $result = mysqli_query($this->conn, $query);
		if (!$result) {
		    die('Invalid query: ' . mysqli_error($this->conn));
		} else {
		    return mysqli_insert_id($this->conn);
		}
	}
	
	function deleteQuery($query) {
		//$query = mysqli_real_escape_string($this->conn, $query);
	    $result = mysqli_query($this->conn, $query);
		if (!$result) {
		    die('Invalid query: ' . mysqli_error($this->conn));
		} else {
			return $result;
		}
	}


	function escape_string($string){
		$string = mysqli_real_escape_string($this->conn, $string);
		return $string;
	}

	function close() {
        mysqli_close($this->conn);
        return true;
    }

    function __destruct() {
		mysqli_close($this->conn);
    }
}

// $db_handle = new DBController();
// //  $query = "ALTER TABLE `preset_user`
// // 	MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000,
// //  	ADD KEY `user_id` (`user_id`),
// //  	ADD CONSTRAINT `prest_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;";   


// $query = "CREATE TABLE `job` 
// 	(`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
// 	`job_id` varchar(50) NOT NULL,
// 	`status` varchar(20) NOT NULL,
// 	`output_file` varchar(50) NOT NULL,
// 	`send_email` tinyint(1) NOT NULL,
// 	`user_id` bigint(20) UNSIGNED NOT NULL,
// 	`preset_global_id` smallint(5) UNSIGNED DEFAULT NULL,
// 	`preset_user_id` bigint(20) UNSIGNED DEFAULT NULL,
// 	PRIMARY KEY(id),
// 	KEY `user_id` (`user_id`),
// 	FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE,
// 	KEY `preset_global_id` (`preset_global_id`),
// 	FOREIGN KEY (`preset_global_id`) REFERENCES `preset_global`(`id`) ON UPDATE CASCADE ON DELETE SET NULL,
// 	KEY `preset_user_id` (`preset_user_id`),
// 	FOREIGN KEY (`preset_user_id`) REFERENCES `preset_user`(`id`) ON UPDATE CASCADE ON DELETE SET NULL)
//     ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// // $query = "ALTER TABLE 'preset_user' CHANGE COLUMN 'ID' TO 'id';"

// // $query = "INSERT INTO `preset_global` 
// // 	(`codec`, `name` , `width`, `height`, `bitrate`, `framerate`, `audio`, `audio_bitrate`, `send_email`)
// // 	VALUES
// // 	('h.264','HD_10Mbps', '1920', '1080', '10000', '25', '1', '320', '0');";


// $db_handle->runQuery($query);




?>
