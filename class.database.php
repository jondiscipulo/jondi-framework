<?php

/**
 * Database PDO | PHP 5
 * Build 2013.05.20
 *
 * Copyright (c) 2013
 * Jonathan Discipulo <jonathan.discipulo@gmail.com>
 * https://github.com/jondiscipulo/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * http://www.gnu.org/copyleft/lesser.html
 * 
**/

/** database class **/
class Database {

	private $host, $user, $pass, $name, $charset, $driver, $option;
	public $pdo, $statement, $result;
	public $exception;
	
	/** constructor **/
	public function __construct( $db, $option=null ) {
		$this->host = $db['host'];
		$this->user = $db['user'];
		$this->pass = $db['pass'];
		$this->name = $db['name'];
		$this->charset = $db['charset'];
		$this->driver = $db['driver'];
		$this->option = $option;
	}
	
	/** pdo **/
	
	/** connect **/
	public function connect() {
	
		// default connection string
		$connection = $this->driver . ':host=' . $this->host . ';dbname=' . $this->name . ';charset=' . $this->charset;
		switch ( strtolower($this->driver) ) {
			case "sqlite":
				$connection = $this->driver . ':' . $this->host . $this->name . '.db';
				break;
			case "sqlsrv":
				$connection = $this->driver . ':Server=' . $this->host . ';Database=' . $this->name;
				break;
			case "mysql":
			case "pgsql":
			case "dblib":
			default:
				break;
		}
		
		try {
			$this->pdo = new PDO( $connection, $this->user, $this->pass, $this->option );
			return true;
		} catch ( PDOException $e ) {
			$this->exception = $e;
			return false;
		}
		
		return false;
		
	}

	/** begin transaction **/
	public function beginTransaction() {
		$this->pdo->beginTransaction();
	}
	
	/** commit transaction **/
	public function commit() {
		return $this->pdo->commit();
	}
	
	/** error code [returns SQLSTATE (alpha numeric)] **/
	public function errorCode() {
		return $this->pdo->errorCode();
	}
	
	/** error info [returns array] **/
	public function errorInfo() {
		return $this->pdo->errorInfo();
	}
	
	/** execute [returns number affected rows)] **/
	public function exec( $sql ) {
		return $this->pdo->exec( $sql );
	}
	
	/** get attribute [returns string or null] **/
	public function getAttribute( $attribute ) {
		return $this->pdo->getAttribute( $attribute );
	}
	
	/** get available Drivers [returns array] **/
	public function getAvailableDrivers() {
		return $this->pdo->getAvailableDrivers();
	}
	
	/** in transaction? [returns true or false] **/
	public function inTransaction() {
		return $this->pdo->inTransaction();
	}

	/** last insert id [returns id string or sequence id string] **/
	public function lastInsertId( $name=null) {
		return $this->pdo->lastInsertId( $name );
	}

	/** prepare [returns PDOStatement object or false] **/
	public function prepare( $sql, $options=array() ) {
		$this->statement = $this->pdo->prepare( $sql, $options );
		return $this->statement;
	}
	
	/** query [returns PDOStatement object or false] **/
	public function query( $sql ) {
		$this->statement = $this->pdo->query( $sql );
		return $this->statement;
	}
	
	/** quote [returns quoted string or false] **/
	public function quote( $string, $type=PDO::PARAM_STR ) {
		return $this->pdo->quote( $string, $type );
	}
	
	/** roll back [returns true or false] **/
	public function rollBack() {
		return $this->pdo->rollBack();
	}

	/** set attribute [returns true or false] **/
	public function setAttribute( $attribute, $value ) {
		return $this->pdo->setAttribute( $attribute, $value );
	}

	/** pdo statement **/

	/** bind column [returns true or false] **/
	public function bindColumn( $column, &$param, $type=PDO::PARAM_INT, $maxlen=null, $data=null ) {
		return $this->statement->bindColumn( $column, $param, $type, $maxlen, $data );
	}

	/** bind param [returns true or false] **/
	public function bindParam( $param, &$var, $type=PDO::PARAM_STR, $length=null, $options=null ) {
		return $this->statement->bindParam( $param, $var, $type, $length, $options );
	}

	/** bind value [returns true or false] **/
	public function bindValue( $param, &$value, $type=PDO::PARAM_STR ) {
		return $this->statement->bindValue( $param, $value, $type );
	}

	/** close cursor [returns true or false] **/
	public function closeCursor() {
		return $this->statement->closeCursor();
	}

	/** column count [returns column count or zero] **/
	public function columnCount() {
		return $this->statement->columnCount();
	}
	
	/** debug dump params [returns nothing but outputs string] **/
	public function debugDumpParams() {
		$this->statement->debugDumpParams();
	}

	/** statement error code [returns SQLSTATE] **/
	public function statementErrorCode() {
		return $this->statement->errorCode();
	}

	/** statement error info [returns array] **/
	public function statementErrorInfo() {
		return $this->statement->errorInfo();
	}
	
	/** execute [returns true or false] **/
	public function execute( $input=null ) {
		return $this->statement->execute( $input );
	}

	/** fetch [returns depends on fetch type or false] **/
	public function fetch( $style=null, $orientation=PDO::FETCH_ORI_NEXT, $offset=0 ) {
		$this->result = $this->statement->fetch( $style, $orientation, $offset );
		return $this->result;
	}

	/** fetch all [returns array or false] **/
	public function fetchAll( $style=null, $args=null, $ctor=array() ) {
		$this->result = $this->statement->fetchAll( $style, $args, $ctor );
		return $this->result;
	}
	
	/** fetch column [returns integer resultset] **/
	public function fetchColumn( $num=0 ) {
		$this->result = $this->statement->fetchColumn( $num );
		return $this->result;
	}
	
	/** fetch object [returns object instance or false] **/
	public function fetchObject( $class, $ctor=array() ) {
		$this->result = $this->statement->fetchObject( $class, $ctor );
		return $this->result;
	}
	
	/** get statement attribute [returns attribute value] **/
	public function statementGetAttribute( $attribute ) {
		return $this->statement->getAttribute( $attribute );
	}
	
	/** get statement column meta [returns associative array] **/
	public function statementGetColumnMeta( $attribute ) {
		return $this->statement->getColumnMeta( $attribute );
	}
	
	/** next rowset [returns true or false] **/
	public function nextRowset() {
		return $this->statement->nextRowset();
	}
	
	/** rows [returns row count] **/
	public function rowCount() {
		return $this->statement->rowCount();
	}

	/** set statement attribute [returns true or false] **/
	public function statementSetAttribute( $attribute, $value ) {
		return $this->statement->setAttribute( $attribute, $value );
	}
	
	/** set fetch mode [returns true or false] **/
	public function setFetchMode( $mode=PDO::FETCH_NUM ) {
		return $this->statement->setFetchMode( $mode );
	}
	
	/** get statement [returns statement object] **/
	public function getStatement() {
		return $this->statement;
	}

	/** result [returns result object] **/
	public function getResult() {
		return $this->result;
	}
	
	/** pdo exception **/
	
	/** get exception [returns array] **/
	public function getException() {
		return $this->exception;
	}
	
	/** clean **/
	public function clean() {
		if ( isset($this->result) || is_resource($this->result) ) unset($this->result);
		if ( isset($this->statement) || is_resource($this->statement) ) unset($this->statement);
	}

	/** disconnect **/
	public function disconnect() {
		if ( isset($this->host) ) unset($this->host);
		if ( isset($this->user) ) unset($this->user);
		if ( isset($this->pass) ) unset($this->pass);
		if ( isset($this->name) ) unset($this->name);
		if ( isset($this->charset) ) unset($this->charset);
		if ( isset($this->driver) ) unset($this->driver);
		if ( isset($this->option) ) unset($this->option);
		$this->clean();
		$this->pdo = null;
		if ( isset($this->pdo) || is_resource($this->pdo) ) unset($this->pdo);
	}

	/** magic method: sleep **/
    public function __sleep() {
		return array($this->host, $this->user, $this->pass, $this->name, $this->charset, $this->driver, $this->option);
    }
    
	/** magic method: wake up **/
    public function __wakeup() {
		$this->connect();
    }

	/** destructor **/
	public function __destruct() {
		$this->disconnect();
	}

}

?>
