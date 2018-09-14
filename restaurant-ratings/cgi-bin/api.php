<?php
/**
 * API class
 * foundation borrowed from https://github.com/memaker/webservice-php-json
 */
class api {
	private $db;
	/**
	 * Constructor - open DB connection
	 */
	function __construct()
	{
		$conf = json_decode(file_get_contents('configuration.json'), TRUE);
		$this->db = new mysqli($conf["host"], $conf["user"], $conf["password"], $conf["database"], $conf["port"]);
		if ($this->db->connect_error) {
			die('Connect Error: ' . $this->db->connect_error);
		}
	}
	/**
	 * Destructor - close DB connection
	 */
	function __destruct() {
		$this->db->close();
	}
	
	/* This function handles a single query and returns the result. */
	function sql_query($query) {
		$list = array();
		$code = null;
		if($result = $this->db->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$list[] = $row;
			}
			$code = 1;
		}  else {
			$list[] = $this->db->error_list;
			$code = 0;
			foreach ($list as $err) {
				error_log($err);
			}
		}
		
		$message = array();
		$message['code'] = $code;
		$message['data'] = $list;
		return $message;
	}
	
	/* This function processes multiple queries and returns all of the results. */
	function multi_query($query, $keys) {
		$list = array();
		$code = null;
		if ($this->db->multi_query($query)) {
			do {
				if ($result = $this->db->store_result()) {
					/* We collect the field names from the query result to use
						as keys for the row data. */
					$fields = $result->fetch_fields();
					$cols = array();
					foreach ($fields as $val) {
						$cols[] = $val->name;
					}
					
					/* Here the rows are iterated through, and we combine the field names
						with the row data to create a key:value mapping. */
					$rows = array();
					while ($row = $result->fetch_row()) {
						$rows[] = array_combine($cols,$row);
					}
					/* The array of multiple row data we just created is then added
						to the array that carries the arrays for each of the queries,
						not just this individual one. */
					$list[] = $rows;
					$code = 1;
					$result->free();
				} else {
					$list[] = $this->db->error_list;
					$code = 0;
					foreach ($list as $err) {
						error_log($err);
					}
				}
			} while ($this->db->next_result());
		} else {
			$list[] = $this->db->error_list;
			$code = 0;
			foreach ($list as $err) {
				error_log($err);
			}
		}
		
		/* We prepare the array we're going to send back to the client by adding the code. */
		$message = array();
		$message['code'] = $code;
		
		/* If $code = 1, we create another key:value mapping between the names given for
			each of the queries and the results they generated. Otherwise we submit the
			errors generated. */
		if($code) {
			$data = array_combine($keys,$list);
			$message['data'] = $data;
		} else {
			$message['data'] = $list;
		}
		return $message;
	}
}