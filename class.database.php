<?php

/**
 *  Database PDO | PHP 5
 *	Build 2013.05.16
 *
 *	Copyright (c) 2013
 *	Jonathan Discipulo <jonathan.discipulo@gmail.com>
 *	https://github.com/jondiscipulo/
 * 
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 * 
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 * 
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 *  http://www.gnu.org/copyleft/lesser.html
 * 
**/


/** Database Class **/
class Database {

	private $pdo, $host, $user, $pass, $name, $charset, $driver, $option;
	public $statement, $result;
	private $exception = array();
	
	/** Constructor **/
	public function __construct( $db, $option=null ) {
		$this->host = $db['host'];
		$this->user = $db['user'];
		$this->pass = $db['pass'];
		$this->name = $db['name'];
		$this->charset = $db['charset'];
		$this->driver = $db['driver'];
		$this->option = $option;
	}
	
	/** PDO **/
	
	/** Connect **/
	public function connect() {
	
		// default connection string
		$connection = $this->driver . ':host=' . $this->host . ';dbname=' . $this->name . ';charset=' . $this->charset;
		switch ( strtolower($this->driver) ) {
			case "mysql":
				break;
			case "pgsql":
				break;
			case "dblib":
				break;
			case "sqlsrv":
				$connection = $this->driver . ':Server=' . $this->host . ';Database=' . $this->name;
				break;
			default:
				break;
		}
		
		try {
			$this->pdo = new PDO( $connection, $this->user, $this->pass, $this->option );
			return true;
		} catch ( PDOException $e ) {
			$this->exception[] = array( $e->errorInfo(), $e->getMessage(), $e->getCode() );
			return false;
		}
		
		return false;
		
	}

	/** Begin Transaction **/
	public function begin() {
		return $this->pdo->beginTransaction();
	}
	
	/** Commit Transaction **/
	public function commit() {
		return $this->pdo->commit();
	}
	
	/** Error Code [returns SQLSTATE (alpha numeric)] **/
	public function errorCode() {
		return $this->pdo->errorCode();
	}
	
	/** Error Info [returns array] **/
	public function errorInfo() {
		return $this->pdo->errorInfo();
	}
	
	/** Execute [returns number affected rows)] **/
	public function exec( $sql ) {
		return $this->pdo->exec( $sql );
	}
	
	/** Get Attribute [returns string or null] **/
	public function get( $attribute ) {
		return $this->pdo->getAttribute( $attribute );
	}
	
	/** Get Available Drivers [returns array] **/
	public function drivers() {
		return $this->pdo->getAvailableDrivers();
	}
	
	/** In Transaction? [returns true or false] **/
	public function inTransaction() {
		return $this->pdo->inTransaction();
	}

	/** Last Insert ID [returns id string or sequence id string] **/
	public function lastInsertId( $name=null) {
		return $this->pdo->lastInsertId( $name );
	}

	/** Prepare [returns PDOStatement object or false] **/
	public function prepare( $sql, $options=array() ) {
		$this->statement = $this->pdo->prepare( $sql, $options );
		return $this->statement;
	}
	
	/** Query [returns PDOStatement object or false] **/
	public function query( $sql ) {
		$this->statement = $this->pdo->query( $sql );
		return $this->statement;
	}
	
	/** Quote [returns quoted string or false] **/
	public function quote( $string, $type=PDO::PARAM_STR ) {
		return $this->pdo->quote( $string, $type );
	}
	
	/** Roll Back [returns true or false] **/
	public function rollBack() {
		return $this->pdo->rollBack();
	}

	/** Set Attribute [returns true or false] **/
	public function set( $attribute, $value ) {
		return $this->pdo->setAttribute( $attribute, $value );
	}

	/** PDO Statement **/

	/** Bind Column [returns true or false] **/
	public function bindColumn( $column, &$param, $type=PDO::PARAM_INT, $maxlen=null, $data=null ) {
		return $this->statement->bindColumn( $column, $param, $type, $maxlen, $data );
	}

	/** Bind Param [returns true or false] **/
	public function bindParam( $param, &$var, $type=PDO::PARAM_STR, $length=null, $options=null ) {
		return $this->statement->bindParam( $param, $var, $type, $length, $options );
	}

	/** Bind Value [returns true or false] **/
	public function bindValue( $param, &$value, $type=PDO::PARAM_STR ) {
		return $this->statement->bindValue( $param, $value, $type );
	}

	/** Close Cursor [returns true or false] **/
	public function closeCursor() {
		return $this->statement->closeCursor();
	}

	/** Column Count [returns column count or zero] **/
	public function columnCount() {
		return $this->statement->columnCount();
	}
	
	/** Debug Dump Params [returns nothing but outputs string] **/
	public function debug() {
		$this->statement->debugDumpParams();
	}

	/** Statement Error Code [returns SQLSTATE] **/
	public function statementErrorCode() {
		return $this->statement->errorCode();
	}

	/** Statement Error Info [returns array] **/
	public function statementErrorInfo() {
		return $this->statement->errorInfo();
	}
	
	/** Execute [returns true or false] **/
	public function execute( $input=array() ) {
		return $this->statement->execute( $input );
	}

	/** Fetch [returns depends on fetch type or false] **/
	public function fetch( $style=null, $orientation=PDO::FETCH_ORI_NEXT, $offset=0 ) {
		$this->result = $this->statement->fetch( $style, $orientation, $offset );
		return $this->result;
	}

	/** Fetch All [returns array or false] **/
	public function fetchAll( $style, $args=null, $ctor=array() ) {
		$this->result = $this->statement->fetchAll( $style, $args, $ctor );
		return $this->result;
	}
	
	/** Fetch Column [returns integer resultset] **/
	public function fetchColumn( $num=0 ) {
		$this->result = $this->statement->fetchColumn( $num );
		return $this->result;
	}
	
	/** Fetch Object [returns object instance or false] **/
	public function fetchObject( $class, $ctor=array() ) {
		$this->result = $this->statement->fetchObject( $class, $ctor );
		return $this->result;
	}
	
	/** Get Statement Attribute [returns attribute value] **/
	public function getStatementAttribute( $attribute ) {
		return $this->statement->getAttribute( $attribute );
	}
	
	/** Get Statement Column Meta [returns associative array] **/
	public function getStatementColumnMeta( $attribute ) {
		return $this->statement->getAttribute( $attribute );
	}
	
	/** Next Rowset [returns true or false] **/
	public function nextRowset() {
		return $this->statement->nextRowset();
	}
	
	/** Rows [returns row count] **/
	public function rows() {
		return $this->statement->rowCount();
	}

	/** Set Statement Attribute [returns true or false] **/
	public function setStatementAttribute( $attribute, $value ) {
		return $this->statement->setAttribute( $attribute, $value );
	}
	
	/** Set Fetch Mode [returns true or false] **/
	public function setFetchMode( $mode=PDO::FETCH_NUM ) {
		return $this->statement->setFetchMode( $mode );
	}
	
	/** Get Statement [returns statement object] **/
	public function getStatement() {
		return $this->statement;
	}

	/** Result [returns result object] **/
	public function getResult() {
		return $this->result;
	}
	
	/** PDO Exception **/
	
	/** Get Exception [returns array] **/
	public function getException() {
		return $this->exception;
	}
	
	/** Clean **/
	public function clean() {
		if ( isset($this->result) || is_resource($this->result) ) unset($this->result);
		if ( isset($this->statement) || is_resource($this->statement) ) unset($this->statement);
	}

	/** Disconnect **/
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

	/** Magic Method: Sleep **/
    public function __sleep() {
		return array($this->host, $this->user, $this->pass, $this->name, $this->charset, $this->driver, $this->option);
    }
    
	/** Magic Method: Wake Up **/
    public function __wakeup() {
		$this->connect();
    }

	/** Destructor **/
	public function __destruct() {
		$this->disconnect();
	}

}

?>
