<?php

require_once('DBinfo.php');
require_once('Laundry.php');

/**
 * DBinsert class.
 */
class DBupdate {
    private $input;
    private $db = '';
    private $id = '';
    private $sql = '';
    private $message = 'no message set';
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
    		$connection = null; //close connection
    	} catch (PDOException $e) {
    		$this->message = "Database insertion failed: " . $e->getMessage();
    		return false;
    	}

        $this->wasDataSet = true;
    	return true;
    }

    public function wasDataSet() {
        return $this->wasDataSet;
    }

    public function getErrorMessage() {
        return $this->message;
    }

    private function setSql($db, $input, $id) {
        if ($db === 'about') {
            $this->sql = "UPDATE about
                    SET body = '{$input}', last_modified = DEFAULT
                    WHERE id = 1";
        } else if ($db === 'bios') {
            $name = $input['name'];
            $body = $input['body'];
            $src  = $input['src'];
            $this->sql = "UPDATE bios
                    SET name = '{$name}', body = '{$body}', src = '{$src}', last_modified = DEFAULT
                    WHERE id = {$id}";
        } else if ($db === 'contact') {
            $address   = $input['address'];
            $city      = $input['city'];
            $state     = $input['state'];
            $zipcode   = $input['zipcode'];
            $telephone = $input['telephone'];
            $email     = $input['email'];
            $this->sql = "UPDATE contact
                    SET address = '{$address}', city = '{$city}', state = '{$state}', zipcode = '{$zipcode}',
                    telephone = '{$telephone}', email = '{$email}', last_modified = DEFAULT";
        } else if ($db === 'repairs') {
            $this->sql = "UPDATE repairs
                    SET body = '{$input}', last_modified = DEFAULT";
        }

    }

    public function __construct($db, $input, $id) {

        if (is_array($input)) {
            foreach ($input as $k => $v) {
                $this->input[Laundry::htmlclean($k)] = Laundry::htmlclean($v);
            }
        } else {
            $this->input = Laundry::htmlclean($input);
        }

        $this->db = Laundry::htmlclean($db);

        if ($id !== '') {
            $this->id = Laundry::htmlclean($id);
        }

        $this->setSql($this->db, $this->input, $this->id);
        $this->setData($this->sql);
    }
}
?>