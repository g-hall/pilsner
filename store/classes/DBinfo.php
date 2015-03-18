<?php
    /**
 * DBinfo class.
 */
class DBinfo {
	private static $dsn      = 'mysql:host=localhost;dbname=bklutherie';
	private static $username = 'root';
	private static $password = 'nEchayev76';

	public static function getDBinfo() {
		$DBinfo = array(self::$dsn, self::$username, self::$password,);
		return $DBinfo;
	}
}
?>