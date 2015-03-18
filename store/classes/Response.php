<?php

require_once('DBinfo.php');
require_once('Laundry.php');

/**
 * Response class.
 */
class Response {
    public $table;
    public $data;
    public $response;
    protected $sql;
    protected $id;

    protected function getData($table) {
    	$DBinfo = DBinfo::getDBinfo();
    	try {
    		$connection = new PDO($DBinfo[0], $DBinfo[1], $DBinfo[2]);
    		$connection->setAttribute(
    				PDO::ATTR_ERRMODE,
    				PDO::ERRMODE_EXCEPTION
    		);
    	} catch (PDOException $e) {
    		$message = "Connection failed: " . $e->getMessage();
    		return $message;
    	}
    	$sql  = $this->sql;
    	$result = $connection->query($sql);
    	$data = $result->fetch(PDO::FETCH_ASSOC);
    	return $data;
    }

    protected function createJsonResponse($data) {
        foreach ($data as $k => $v) {
            $data[$k] = Laundry::htmlDirty($v);
        }
        $data = ["{$this->table}"=>$data];
        $this->response = json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    protected function setSql($sql) {
        $this->sql = $sql;
    }

    protected function getId() {
        return $this->id;
    }

    public function __construct($table, $id) {
        $this->table = $table;
        $this->id = $id;
    }

    public function getResponse() {
        $this->data = $this->getData($this->table);
        $this->createJsonResponse($this->data);
        return $this->response;
    }

}

/**
 * ArrayResponse class.
 *
 * @extends Response
 */
class ArrayResponse extends Response {

    protected function getData($table) {
    	$DBinfo = DBinfo::getDBinfo();
    	try {
    		$connection = new PDO($DBinfo[0], $DBinfo[1], $DBinfo[2]);
    		$connection->setAttribute(
    				PDO::ATTR_ERRMODE,
    				PDO::ERRMODE_EXCEPTION
    		);
    	} catch (PDOException $e) {
    		$message = "Connection failed: " . $e->getMessage();
    		return $message;
    	}
    	$sql  = $this->sql;
    	$result = $connection->query($sql);
    	$data = $result->fetchAll(PDO::FETCH_ASSOC);
    	return $data;
    }


    protected function createJsonResponse($data) {
        for ($i = 0, $l = count($data); $i < $l; $i += 1) {
            foreach ($data[$i] as $k => $v) {
                $data[$i][$k] = Laundry::htmlDirty($v);
            }
        }
        $data = ["{$this->table}"=>$data];
        $this->response = json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public function __construct($table, $id = NULL) {
        parent::__construct($table, $id = NULL);
        $sql = "SELECT *
                FROM {$table}";
        parent::setSql($sql);
    }
}

/**
 * ObjectResponse class.
 *
 * @extends Response
 */
class ObjectResponse extends Response {
    public function __construct($table, $id) {
        parent::__construct($table, $id);
        if ($id === NULL) {
            $sql = "SELECT *
                    FROM {$table}
                    LIMIT 1";
        } else {
            $sql = "SELECT *
                    FROM {$table}
                    WHERE id = {$id}
                    LIMIT 1";
        }

        parent::setSql($sql);
    }
}

/**
 * IndexResponse class.
 *
 * @extends Response
 */
class IndexResponse extends ArrayResponse {

    protected function createJsonResponse($data) {
        for ($i = 0, $l = count($data); $i < $l; $i += 1) {
            foreach ($data[$i] as $k => $v) {
                $data[$i] = '';
                $data[$i]['id'] = $i;
                $data[$i]['name'] = Laundry::htmlDirty($v);
            }
        }
        $data = ["{$this->table}"=>$data];
        $this->response = json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public function __construct($table, $id = NULL) {
        parent::__construct($table, $id = NULL);
        $sql = "SHOW TABLES";

        parent::setSql($sql);
    }
}
?>