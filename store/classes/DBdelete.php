<?php

require_once('DBinfo.php');

/**
 * DBdelete class.
 */
class DBdelete {
    private $db = '';
    private $sql = '';
    private $message = 'no message set';
    private $wasDeleted = false;

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
    		$this->wasDeleted = true;
    		$connection = null; //close connection
    	} catch (PDOException $e) {
    		$this->message = "Database deletion failed: " . $e->getMessage();
    		return false;
    	}

    	return true;
    }

    public function wasDeleted() {
        return $this->wasDeleted;
    }

    public function getErrorMessage() {
        return $this->message;
    }

    private function setSql($db, $id) {
        $this->sql = "DELETE FROM {$db}
                    WHERE id = {$id}";
    }

    public function __construct($db, $id) {

        if ($id !== '') {
            $this->id = Laundry::htmlclean($id);
        }

        $this->db = Laundry::htmlclean($db);

        $this->setSql($this->db, $this->id);
        $this->setData($this->sql);
    }
}
?>