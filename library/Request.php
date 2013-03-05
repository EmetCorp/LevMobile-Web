<?
//
// +------------------------------------------------------------------------+
// | PHP version 5.0 [IO class file]              	                     	|
// +------------------------------------------------------------------------+
// | Copyright (c) 2004-2005 Classic Informatics                          	|
// +------------------------------------------------------------------------+
// | Description:													      	|
// | Request class encapsulates data which come from  POST.			|
// | Intended to centralise the access of Posted data. 						|
// | You may extend this class to provide additional functions.				|
// | All its member methods are static.										|
// +------------------------------------------------------------------------+
// | Author				: Nilay Anand <nilay@classicinformatics.com>       	|
// | Created Date     	: 26-02-2005                  						|
// | Last Modified    	: 26-02-2005                  						|
// | Last Modified By 	: Nilay Anand                  						|
// +------------------------------------------------------------------------+


class Request{
	static function Data($ostrField)
	{
		// centralize access of $_POST
		if(array_key_exists($ostrField, $_REQUEST))
		{
			if(MAGIC_QUOTE_ENABLED)
				return $_REQUEST[$ostrField];
			elseif(is_array($_REQUEST[$ostrField]))
			{
				return $_REQUEST[$ostrField];
			}
			else return addslashes($_REQUEST[$ostrField]);
		}
		else
		{
			// throw an exception if needed
			return false;
		}
	}
}