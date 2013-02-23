<?
if ( !class_exists( Base ) ) {

class Base {

    var $host;
    var $dbuser;
    var $dbpass;
    var $dbname;
    var $conn;
    var $dbconn;
/*
    function Base() {
        error_reporting(1);
        $this->host = $confdbhost;
        $this->dbuser = $confdbuser;
        $this->dbpass = $confdbpass;
        $this->dbname = $confdbname;
    }
*/
    function Base($host = "", $dbuser = "", $dbpass = "", $dbname = "") {
        //error_reporting(1);

        if (empty($host))
        	$this->host = "192.168.1.111";
        else
			$this->host = $host;

        if (empty($dbuser))
        	$this->dbuser = "interbis_system";
        else
        	$this->dbuser = $dbuser;

        if (empty($dbpass))
        	$this->dbpass = "";
        else
        	$this->dbpass = $dbpass;

        if (empty($dbname))
        	$this->dbname = "interbis_system";
        else
        	$this->dbname = $dbname;
    }


    function login() {

        $this->conn = mysql_connect($this->host,$this->dbuser,$this->dbpass)
                        or die(mysql_error());
        //$this->dbconn = mysql_select_db($this->dbname,$this->conn) or die("Nie mo¿na po³¹czyæ siê z baz¹");
		$this->dbconn = mysql_select_db($this->dbname,$this->conn) or die(mysql_error());
		$this->dml("SET NAMES utf8");
    }

    function logoff() {
      mysql_close($this->conn);
        return 0;
    }

	function dql($sql, $const = MYSQL_BOTH) {
		$result = @mysql_query($sql);
		//$result = mysql_query($sql) or die(mysql_error()." zapytanie: " .$this->addError($sql));
		$tab = array();
        
		if ($result) {
			$i = 0;
			while(($array=mysql_fetch_array($result, $const))) {
				if (count($array) > 0) {
					$tab[] = $array;

				}   $i++;
			}
		} else {
			$this->addError($sql);
		}
        
		return $tab;

    }
	function addError($sql) {
		$s = "INSERT INTO cms_errors(errortype,value,dt,site,ip) 
			VALUES('SQL','".addslashes($sql)."',NOW(),'".$_SERVER['REQUEST_URI']."','".$_SERVER['REMOTE_ADDR']."')";
		$this->startTransaction();
		mysql_query($s) or die(mysql_error() . "zap: " . $s);
		$this->commitTransaction();
		return $sql;
	}
    function dml($sql1) {
        $result = mysql_query($sql1);

		if ($result)
        	return true;
        else {
			$this->addError($sql1);
			return false;
		}
        	
    }

	function insert_id() {
		return mysql_insert_id($this->dbconn);
	}

	function rowCount($sql) {
		$result = mysql_query($sql) or die(mysql_error());
		$rowCount = mysql_num_rows($result);

		return $rowCount;
	}
    function numfield($sql2) {
        $result = mysql_num_fields($sql2);
        return $result;
    }

    function list_fields($table) {
        $sql = "select * from " . $table . " limit 0,1";
        $result = mysql_query($sql,$this->$conn);
        $fields = mysql_num_fields($result);

        for($i=0; $i<$fields; $i++) {
            $columns[$i][0] = mysql_field_name($result,$i);
            $columns[$i][1] = mysql_field_type($result,$i);
            $columns[$i][2] = mysql_field_flags($result,$i);
        }
        return $columns;
    }
	
	function startTransaction() {
		$this->dml("set autocommit=false");
		$this->dml("START TRANSACTION");
	}
	function commitTransaction() {
		$this->dml("COMMIT");
		$this->dml("set autocommit=true");
	}
	function rollbackTransaction() {
		$this->dml("ROLLBACK");
		$this->dml("set autocommit=true");
	}

	
}

}

?>