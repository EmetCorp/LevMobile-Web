<?
//
// +------------------------------------------------------------------------+
// | PHP version 5.0 [IO class file]										|
// +------------------------------------------------------------------------+
// | Copyright (c) 2004-2005 Classic Informatics							|
// +------------------------------------------------------------------------+
// | Description:													      	|
// | Database class encapsulates database related functions.				|
// | Must impliment DbBase class to to syncronize with other RDBMD library.	|
// |																		|
// |																		|
// +------------------------------------------------------------------------+
// | Author           	: Nilay Anand <nilay@classicinformatics.com>		|
// | Created Date     	: 26-02-2005										|
// | Modified On    	: 26-02-2005										|
// | Modified By    	: Nilay Anand										|
// | Last Modified    	: 19-06-2007                  						|
// | Last Modified By 	: Tushar Saswade (mysqli support) 					|
// +------------------------------------------------------------------------+
class clsDADB{
	private $host;
	private $user;
	private $pass;
	private $database;
	private $query;
	private $response;
	private $errNo;
	public $errMesg;
	private $result;
	private $blnNewLink;
	private $objDBLink;

	/**
	* Constructor method
	*/
	public function __construct($strphost,$strpuser,$strppass,$strpdatabase, $pblnNewLink=false)
	{
		$this->host=$strphost;
		$this->user=$strpuser;
		$this->pass=$strppass;
		$this->database=$strpdatabase;
		$this->response=true;
		$this->blnNewLink = $pblnNewLink;
	}

	/**
	* Connect to the database server
	*/
	public function Connect()
	{
		$this->objDBLink = new mysqli( $this->host, $this->user, $this->pass);
	   if( ! $this->objDBLink ) {
			$this->setError(mysqli_connect_errNo(),mysqli_connect_error());
			throw new Exception("Unable to connect with database ". mysqli_connect_errNo(). ' ' .mysqli_connect_error());
	   }
	   else
		{
		   ### select the database
			if( ! @$this->objDBLink->select_db($this->database) ) {
			 $this->setError(@$this->objDBLink->errno,@$this->objDBLink->error);
			 throw new Exception("Unable to select database " .  @$this->objDBLink->errno. ' ' .@$this->objDBLink->error);
			}
		}

	}

	/**
	* Changing the database name
	*/
	function ChangeDatabase($strpDbname)
	{
	  	 if( ! @$this->objDBLink->select_db($strpDbname) ) {
			 $this->setError($this->objDBLink->errno,$this->objDBLink->error);
			 throw new Exception("Unable to change database" . $this->objDBLink->errno. ' ' .$this->objDBLink->error);
		 }
		 else
		   $this->database = $strpDbname;

	}

	/**
	* This function is used to get active database
	*/
	function GetActiveDatabase()
	{
	  return $this->database;
	}

	/**
	* This function is used to disconnect the database server
	*/
	function Close() {
	   if($this->objDBLink)
		   $this->objDBLink->close();
	}

	/**
	* This function is used to execute the query
	*/
	public function ExecuteQuery( $strpquery )
	{
	   if(!$this->objDBLink)
			throw new Exception("Database Unavailable");

	   $blReturn=true;
	   $this->query = $strpquery;
	   $this->result = $this->objDBLink->query( $this->query);
	   if ( ! $this->result )
	   {
			$this->setError($this->objDBLink->errno,$this->objDBLink->error);
	   		throw new Exception("clsDADB-ExecuteQuery() : Unable to execute Query: <br><font color=#0f00ff>" . nl2br($this->query) ."</font><br> Database engine says: <br><font color=#ff000f>" . $this->getError() ."</font><br>");
	   }
	   else
	   	return $this->result;
	}

	/**
	* This function is used to execute the multi queries and should also be used for SP when there is single or more select query.
	*/
	public function ExecuteMultiQuery( $strpquery )
	{
	   if(!$this->objDBLink)
			throw new Exception("Database Unavailable");

	   $blReturn = false;
	   $this->query = $strpquery;


	   if ( ! $this->objDBLink->multi_query( $this->query) )
	   {
			//include_once(_CLS_PATH.'/Email.php');

			$this->setError($this->objDBLink->errno,$this->objDBLink->error);
	   		echo  $strError = ("clsDADB-ExecuteQuery() : Unable to execute Query: <br><font color=#0f00ff>" . nl2br($this->query) ."</font><br> Database engine says: <br><font color=#ff000f>" . $this->getError() ."</font><br>");

	   }
	   else
		{
		   //$this->result = $this->objDBLink->store_result();
		   $blReturn = true;
		}
	   	return $blReturn;
	}


	/**
	* This function is used to transfer resultset in multi query case
	*/
	public function StoreNextResult()
	{
		if($this->objDBLink->next_result())
		{

			$this->FreeResult();
			if($this->result = $this->objDBLink->store_result())
				return true;
		}
		return false;
	}

	/**
	* This function is used to get last insrted id
	*/
	public function InsertedID()	{
   		return $this->objDBLink->insert_id;
	}

	/**
	* This function is used to get last insrted id, when using stored procedure
	*/
	public function InsertedIDBySP()	{
		try{
			$this->ExecuteMultiQuery("SELECT @pInsertedId;");
			$arrRecordSet = $this->FetchRecords();
			if(isset($arrRecordSet[0]["@pInsertedId"]) && !empty($arrRecordSet[0]["@pInsertedId"]))
				return $arrRecordSet[0]["@pInsertedId"];
			else
				return 0;
		}

		catch(Exception $e){
			throw new Exception("clsDADB-ExecuteQuery() :". $e->getMessage());
		}

	}

	/**
	* This function is used to get affected rows by a query
	*/
	function AffectedRows()
	{
	   return $this->objDBLink->affected_rows;
	}

	/**
	* This function is used to free the mysql result
		*/
	function FreeResult()
	{
		if(is_object($this->result))
		   $this->result->close();
	}

	/**
	* This function is used to count records after executing the query
	*/
	function CountRecords()
	{
	   if( is_object($this->result) )
		   return $this->result->num_rows;
	   else
		   return 0;
	}

	/**
	* Use this method after executing "ExecuteQuery" method for fetching the records
	*/
	function FetchRecords()
	{

		$arrRecords=array();

		do
		{
			/* store first result set */
			if ($this->result = $this->objDBLink->store_result())
			{
				while ($strRec = $this->result->fetch_array())
				{
					$arrRecords[] = $strRec;
				}
				$this->result->close();
			}
	    } while ($this->objDBLink->next_result());


		return $arrRecords;

		/*if(is_object($this->result)){
			if( $this->countRecords() > 0 )
			{
				while ($strRec = $this->result->fetch_array())
				{
					$arrRecords[] = $strRec;
				}
			}
			if( $this->countRecords() > 1 )
			{
				while ($strRec = $this->result->fetch_array())
				{
					$arrRecords[] = $strRec;
				}
			}
			elseif( $this->countRecords() > 0 )
			{
				   $arrRecords = $this->result->fetch_array();
			}

			//in case of SP below function is used to call next_result() function to execute further new query 	statement. currently comment. uncommented in future according to results.

			//$this->StoreNextResult();
		}
	   return $arrRecords;*/

	}

	/**
	* Use this method after executing "ExecuteMultiQuery" method for fetching the records
	*/
	public function FetchMultiRecords()
	{
		$arrRecords=array();
		if(is_object($this->result)){
			$intCountRS = 0;
			do{
				if( $this->countRecords() > 1 )
				{
					while ($strRec = $this->result->fetch_array())
					{
						$arrRecords[$intCountRS][] = $strRec;
					}
				}
				else
				{
					   $arrRecords[$intCountRS][] = $this->result->fetch_array();
				}
				//$this->FreeResult();
				$intCountRS++;
			}while($this->StoreNextResult());

		}
	   return $arrRecords;

	}

	public function FetchAllRecords()
	{
		$arrRecords=array();
		$i = 0;

		do
		{

			/* store first result set */
			if ($this->result = $this->objDBLink->store_result())
			{
				while ($strRec = $this->result->fetch_array())
				{
					$arrRecords[$i][] = $strRec;
				}
				$this->result->close();
			}
			$i++;
	    } while ($this->objDBLink->next_result());


		return $arrRecords;
	}

	/**
	 * Set Error
	 */
    function SetError($intperrNo,$strperrMesg)
    {
		$this->response=false;
		$this->errNo=$intperrNo;
		$this->errMesg=$strperrMesg;
		return;
    }

	public function GetError(){
		return "$this->errNo: $this->errMesg";
	}


	public function CountFields()
	{
		if(is_object($this->result))
			return $this->result->field_count;
		else
			return 0;
	}

	public function GetFieldName($pintOffset)
	{
		$ostrName = '';
		if(is_object($this->result))
		{
			$objFieldInfo = $this->result->fetch_field_direct($pintOffset);
			if(is_object($objFieldInfo))
				$ostrName = $objFieldInfo->name;
		}
		return $ostrName;
	}

/**
* This function is used to get table max field value length in a result set for a given offset
*/
	public function GetFieldLength($pintOffset)
	{
		$ovarReturn = false;
		if(is_object($this->result))
		{
			$objFieldInfo = $this->result->fetch_field_direct($pintOffset);
			if(is_object($objFieldInfo))
				$ovarReturn = $objFieldInfo->max_length;
		}
		return $ovarReturn;
	}

/**
* This function is used to get table max fields value length for a result set
*/
	public function dbTableFieldsLength($strpQuery)
	{
		if(!$this->objDBLink)
			throw new Exception("Database Unavailable");
		$arrFields = array();
		$this->query = $strpQuery;
		$this->result = $this->objDBLink->query( $this->query);

		if ( ! $this->result ){
				$this->setError($this->objDBLink->errno,$this->objDBLink->error);
				throw new Exception("clsDADB-dbTableFieldsLength() : Unable to execute Query: <br><font color=#0f00ff>" . nl2br($this->query) ."</font><br><font color=#ff000f>" . $this->getError() ."</font><br>");
		}
		else
		{
			$intFieldsCount = $this->result->field_count;
			$objFieldInfo = false;
			for ($intOffset = 0; $intOffset <  $intFieldsCount; ++$intOffset) {
					$objFieldInfo = $this->result->fetch_field_direct($intOffset);
					if(is_object($objFieldInfo))
					{
						$strKey				= $objFieldInfo->name;
						$arrFields[$strKey] =  $objFieldInfo->max_length;
					}

			}

		}
		return $arrFields;
	}

	public function GetSPQuery($strpSP, $arrpParam = array())
	{
		if( count($arrpParam) == 0 )
			return "call ".$strpSP."( ".implode(",", $arrpParam)." )";
		else
			return "call ".$strpSP."( '".implode("','", $arrpParam)."' )";
	}

	/**
	* This function is used to commit transaction
	*/
	function Commit() {
	   if($this->objDBLink)
		   $this->objDBLink->commit();
	}
}