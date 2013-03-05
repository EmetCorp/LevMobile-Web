<?php
require_once('common/inc/global.inc.php');
class common
{
	static function userNameSimple($uid)
	{
		global $_objDB;
		$sql = "SELECT CONCAT(firstName,' ',lastName) username FROM tblUsers WHERE id ='".$uid."'";
		$_objDB ->ExecuteMultiQuery($sql);
		$res = $_objDB ->FetchRecords();			
		return $res[0]["username"];
	}
	static  function userDetails($uid)
	{
		global $_objDB;
		$sql = "SELECT mobileNo,email,CONCAT(firstName,' ',lastName) username FROM tblUsers WHERE id ='".$uid."'";
		$_objDB ->ExecuteMultiQuery($sql);
		$res = $_objDB ->FetchRecords();	
		return $res;
	}
	/**Time difference */
	static function timeDiff($time, $opt = array())
	{	
		global $_objDB;
		$defOptions = array(
							'to' => 0,
							'parts' => 2,
							'precision' => 'second',
							'distance' => TRUE,
							'separator' => ', ',
							'next'=>'ago',
							'prev'=>'away'
		);
		$opt = array_merge($defOptions, $opt);
		$query = "SELECT NOW() as t";
		$_objDB ->ExecuteMultiQuery($query);
		$span = $_objDB ->FetchRecords();
		
		(!$opt['to']) && ($opt['to'] = strtotime($span[0]["t"]));
		$str = '';
		$diff = ($opt['to'] > $time) ? $opt['to']-$time : $time-$opt['to'];
		$periods = array(
						'year' => 31556926,
						'month' => 2629744,
						'week' => 604800,
						'day' => 86400,
						'hour' => 3600,
						'minute' => 60,
						'second' => 1
		);
		if ($opt['precision'] != 'second')
		$diff = round(($diff/$periods[$opt['precision']])) * $periods[$opt['precision']];
		(0 == $diff) && ($str = 'less than 1 '.$opt['precision']);
		foreach ($periods as $label => $value) 
		{
			(($x=floor($diff/$value))&&$opt['parts']--) && $str.=($str?$opt['separator']:'').($x.' '.$label.(($x>1)?'s':''));
			if ($opt['parts'] == 0 || $label == $opt['precision']) break;
			$diff -= $x*$value;
		}
		$opt['distance'] && $str.=" ".(($str&&$opt['to']>$time)?$opt['next']:$opt['prev']);
		return $str;
	}
}
?>