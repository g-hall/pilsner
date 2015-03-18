<?php

require_once('DBinfo.php');
require_once('Laundry.php');

/**
 * DBadd class.
 */
class DBadd {
    private $input;
    private $db = '';
    private $sql = '';
    private $message = 'no message set';
    private $lastInsertId = '';
    private $wasDataSet = false;

    public function setData($sql) {
    	$DBinfo = DBinfo::getDBinfo();
    	try {
    		$connection = new PDO($DBinfo[0], $DBinfo[1], $DBinfo[2]);
    		$connection->setAttribute(
    				PDO::ATTR_ERRMODE,
    				PDO::ERRMODE_EXCEPTION
    		);
    	} catch (PDOException $e) {
    		$this->message = "Connection failed: " . $e->getMessage();
    		return false;
    	}
        	try {
    		$connection->exec($sql);
    		$this->wasDataSet = true;
            $this->lastInsertId = $connection->lastInsertId();
    		$connection = null; //close connection
    	} catch (PDOException $e) {
    		$this->message = "Database add failed: " . $e->getMessage();
    		return false;
    	}

    	return true;
    }

    public function wasDataSet() {
        return $this->wasDataSet;
    }

    public function getErrorMessage() {
        return $this->message;
    }

    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    private function setSql($db, $input) {
        if ($db === 'bios') {
            $name = $input['name'];
            $body = $input['body'];
            $src  = $input['src'];
            $this->sql = "INSERT INTO bios
                    VALUES('NULL', '{$name}', '{$body}', '{$src}', 'DEFAULT')";
        }
    }

    public function __construct($db, $input) {

        if (is_array($input)) {
            foreach ($input as $k => $v) {
                $this->input[Laundry::htmlclean($k)] = Laundry::htmlclean($v);
            }
        } else {
            $this->input = Laundry::htmlclean($input);
        }

        $this->db = Laundry::htmlclean($db);

        $this->setSql($this->db, $this->input);
        $this->setData($this->sql);
    }
}
?>