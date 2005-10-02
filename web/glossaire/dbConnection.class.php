<?
Class DBConnection {
/*
 * The purpose of this class is to provide a layer of abstraction from the database used.
 * In this case it is implemented for PostgreSQL
 *
 * This class manages a single database connection, but can handle several queries in parallel.
 * Each time an SQL command is executed, it can be named so that the resulting recordset (or rather the handle
 * to the recordset) can be remembered. Then the method switch() can be used to change the active recordset.
 * All other methods execute on the active recordset.
 *
 * Francois Suter, Cobweb, February 2002
 */

/*
 * Properties:
 *
 *	$conn			handle to the database connection
 *	$language		language the error messages should be displayed in
 *	$pointer		pointer to the current record in the current result recordset
 *	$handles		array of all result handles "in memory"
 *	$currentHandle	the one handle from $handles the object is currently acting on
 *	$isDuplicate	this flag is set to true when a query generates a duplicate key error
 */
	var $conn;
	var $language;
	var $pointer;
	var $handles;
	var $currentHandle;
	var $isDuplicate;

/*
 * Definition of messages
 * Note that the display of error messages assumes the existence of an "error" CSS style (which may not be used
 * if an error happens before the CSS file is loaded)
 */

	var $messages = array("fr" => array("connectionError" => "La connexion &agrave; la base de donn&eacute;s a &eacute;chou&eacute;.",
										"queryError" => "Une erreur s'est produite avec la requ&ecirc;te: ",
										"dbMessage" => "La base de donn&eacute;es a r&eacute;pondu: ",
										"badHandle" => "Ce nom ne correspond pas &agrave; une requ&ecirc;te"),
							"en" => array("connectionError" => "The connection to the database failed.",
											"queryError" => "An error happened with the query: ",
											"dbMessage" => "The database answered: ",
											"badHandle" => "This name doesn't match any query"));

// Constructor

	function DBConnection($server,$dbname,$username,$password,$language = "fr") {
/*
 * The constructor initializes the connection to the datbase
 *
 * $server		name/address of the server where the database resides
 * $dbname		name of the database
 * $username	user's name
 * $password	user's password
 * $language	chosen language for the display of messages (available are French ("fr") and English ("en"))
 *				(optional, defaults to French)
 */
		$this->language = $language;
		$this->conn = pg_connect("user=$username password=$password host=$server dbname=$dbname");
		if (!$this->conn) {
			echo "<p class=\"error\">{$this->messages[$this->language]["connectionError"]}.</p>";
			exit;
		}
	}

// Methods

	function query($statement,$name = "query1") {
/*
 * This method performs a given SQL query
 * If the query fails, an error message is printed and the script dies.
 * The handle to the resulting recordset is stored in the $currentHandle. It is also stored in the associative array
 * $handles so that it can be recalled later
 *
 * $statement	SQL statement
 * $name		name used to "store" the handle returned by the SQL statement execution
 *				(optional, defaults to dummy name "query1")
 */
		$this->isDuplicate = false; // reset duplicate key flag
		$this->currentHandle = @pg_query($this->conn,$statement);
		if (!$this->currentHandle) {
/*
 * An error occured while executing the query
 * Most of the time, this shouldn't happen, so we want the program to print the error message and stop.
 * However in some cases these errors are "planned", e.g. when constraints have been set up to avoid duplicate
 * foreign key pairs in some many-to-many relationships. Since PostgreSQL doesn't issue error codes and since its error
 * messages are the same in this case than when a primary key is duplicated, sorting out this case relies on testing
 * the error message for the string "duplicate key" and knowing that the constraint has a name which contains the word "pair".
 */
			$errorMessage = pg_last_error($this->conn);
			if (strpos($errorMessage,"duplicate key") === false || strpos($errorMessage,"pair") === false) {
				echo "<p class=\"error\">{$this->messages[$this->language]["queryError"]}<kbd>$statement</kbd></p>";
				echo "<p class=\"error\">{$this->messages[$this->language]["dbMessage"]}<cite>$errorMessage</cite></p>";
				exit;
			}
			else {
				$this->isDuplicate = true;
			}
		}
		$this->pointer = 0;
		$this->handles[$name] = $this->currentHandle;
	}

	function numrows() {
/*
 * This method returns the number of rows in the recordset or false if there is no recordset
 */
		if ($this->currentHandle) {
			return pg_num_rows($this->currentHandle);
		}
		else {
			return false;
		}
	}

	function affectedRows() {
/*
 * This method returns the number of rows affected by the last INSERT, UPDATE or DELETE query
 */
		if ($this->currentHandle) {
			return pg_affected_rows($this->currentHandle);
		}
		else {
			return false;
		}
	}

	function next() {
/*
 * This method returns the next element in the recordset or false if there is no recordset
 * Fields are stored both in an indexed and an associative array
 */
		if ($this->currentHandle) {
			$record = pg_fetch_array($this->currentHandle,$this->pointer);
			$this->pointer++;
			return $record;
		}
		else {
			return false;
		}
	}

	function rewind() {
/*
 * This method sets the pointer back to the first recordset
 */
		$this->pointer = 0;
	}

	function move($record) {
/*
 * This method moves the pointer to the given record number
 *
 * $record	number of the record to be reached
 */
		$this->pointer = $record;
	}

	function isDuplicate() {
/*
 * This method returns the value of the duplicate key flag
 */
		return $this->isDuplicate;
	}

	function getField($field) {
/*
 * This method returns the value of a given field in the current record or false if there is no recordset
 *
 * $field	index of the field
 */
		if ($this->currentHandle) {
			return pg_fetch_result($this->currentHandle,$this->pointer,$field);
		}
		else {
			return false;
		}
	}

	function getRecord($record) {
/*
 * This method returns a particular record from the recordset or false if there is no recordset
 * Fields are stored both in an indexed and an associative array
 */
		if ($this->currentHandle) {
			return pg_fetch_array($this->currentHandle,$record);
		}
		else {
			return false;
		}
	}

	function numFields() {
/*
 * This method returns the number of fields in the current recordset
 */
		return pg_num_fields($this->currentHandle);
	}

	function getFieldName($position) {
/*
 * This method returns the name of a given field in the current recordset
 *
 * $position	number of the field (starts at 0)
 */
		return pg_field_name($this->currentHandle,$position);
	}

	function getFieldType($position) {
/*
 * This method returns the type of a given field in the current recordset
 *
 * $position	number of the field (starts at 0)
 */
		return pg_field_type($this->currentHandle,$position);
	}

	function hasRecordset($name) {
/*
 * This function checks whether a given recordset exists or not and return true or false accordingly
 *
 * $name	name of the recordset
 */
		return isset($this->handles[$name]);
	}

	function setRecordset($name) {
/*
 * This method sets the named handle as the current handle
 * This also resets the pointer in the recordset to zero
 *
 * $name	name of the recordset
 */
		if (!isset($this->handles[$name])) {
			echo "<p class=\"error\">{$this->messages[$this->language]["badHandle"]} <em>($name)</em>.</p>";
			$this->currentHandle = false;
		}
		else {
			$this->currentHandle = $this->handles[$name];
			$this->rewind();
		}
	}

	function toArray() {
/*
 * This method dumps the complete current recordset as an associative array
 */
		$records = array();
		$numRows = $this->numrows();
		for ($i = 0; $i < $numRows; $i++) {
			$records[$i] = pg_fetch_array($this->currentHandle,$i);
		}
		return $records;
	}

	function toHash($keyField,$valueField) {
/*
 * This method creates an associative array using the values of the first field as the keys and the values of the second
 * field as the values
 *
 * $keyField		name (or number) of the field to be used as a key
 * $valueField		name (or number) of the field to be used as a value
 */
		$twodarray = array();
		$numRows = $this->numrows();
		for ($i = 0; $i < $numRows; $i++) {
			$record = pg_fetch_array($this->currentHandle,$i);
			$twodarray[$record[$keyField]] = $record[$valueField];
		}
		return $twodarray;
	}

	function toVector($field) {
/*
 * This method creates a one-dimensional array with the values of the given field, one cell per record in the current recordset
 *
 * $field	name (or number) of the field
 */
		$vector = array();
		$numRows = $this->numrows();
		for ($i = 0; $i < $numRows; $i++) {
			$record = pg_fetch_array($this->currentHandle,$i);
			$vector[$i] = $record[$field];
		}
		return $vector;
	}
}
?>