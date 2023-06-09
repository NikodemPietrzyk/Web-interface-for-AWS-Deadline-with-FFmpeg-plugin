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
		$result = mysqli_query($this->conn, $query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
	    $result  = mysqli_query($this->conn, $query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
	
	function updateQuery($query) {
	    $result = mysqli_query($this->conn, $query);
		if (!$result) {
		    die('Invalid query: ' . mysqli_error($this->conn));
		} else {
			return $result;
		}
	}
	
	function insertQuery($query) {
	    $result = mysqli_query($this->conn, $query);
		if (!$result) {
		    die('Invalid query: ' . mysqli_error($this->conn));
		} else {
		    return mysqli_insert_id($this->conn);
		}
	}
	
	function deleteQuery($query) {
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


?>
