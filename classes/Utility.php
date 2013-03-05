<?php

/**
 * General Utility Class
 * This library having the function related entire application
 * @category		Utility
 */

class Utility
{
   	/**
	* This function is used to generate physical filename for media.
	* @param str $fileExt(Extension of File)
	* @return string
	**/
	public static function generateMediaFileName($fileExt='')
	{
			$strAlphaNumeric = self::getRandomAlphaNumeric(8).'-'.self::getRandomAlphaNumeric(4).'-'.self::getRandomAlphaNumeric(12).$fileExt;
			return $strAlphaNumeric;
	}

	/**
	* This function is used to generate random alpha numeric string.
	* @param int $intpLength(length of string)
	* @return string
	**/
	public static function getRandomAlphaNumeric($intpLength = 16)
	{
		$arrAlphaNumeric = array();
		$arrAlpha = range('A','Z');
		$arrNumeric = range(0,9);

		$arrAlphaNumeric = array_merge($arrAlphaNumeric, $arrAlpha, $arrNumeric);

		mt_srand((double)microtime() * 1234567);
		shuffle($arrAlphaNumeric);

		$strAlphaNumeric = '';
		for($x=0; $x<$intpLength; $x++)
			$strAlphaNumeric .= $arrAlphaNumeric[mt_rand(0, (sizeof($arrAlphaNumeric)-1))];
		return $strAlphaNumeric;
	}

	/**
	* This function is used to generate random numeric string.
	* @param int $intpLength(length of string)
	* @return string
	**/
	public static function getRandomNumeric($intpLength = 16)
	{
		$arrAlphaNumeric = array();
		$arrAlpha = range(0,9);
		$arrNumeric = range(0,9);

		$arrAlphaNumeric = array_merge($arrAlphaNumeric, $arrAlpha, $arrNumeric);

		mt_srand((double)microtime() * 1234567);
		shuffle($arrAlphaNumeric);

		$strAlphaNumeric = '';
		for($x=0; $x<$intpLength; $x++)
			$strAlphaNumeric .= $arrAlphaNumeric[mt_rand(0, (sizeof($arrAlphaNumeric)-1))];
		return $strAlphaNumeric;
	}

	/**
	* Used to get physical path and web path for media or stuff uploaded through system
	*/
	public static function getMediaPath($strpType, $intpServerId="")
	{
		if($strpType == "physical")
			{
				$strPath = _DOC_PATH;
			}

		else if($strpType == "web")
		   {
				$strPath = _DOC_ROOT;
			}

			return $strPath;
	}

 

	/**
     * create directory structure for given path
	 *@param $pstrBasePath (base path), string $pstrDirPath (dir path to be created)
	 *return string path
     */
    public static function createDirStructure($pstrBasePath, $pstrDirPath)
    {   //echo $pstrBasePath, '/',$pstrDirPath.'  --  ';
		$arrDir = explode('/',$pstrDirPath);
		for($intCount=0; $intCount<count($arrDir);$intCount++)
		{
			if($arrDir[$intCount] !== '')
			{
				$pstrBasePath .= '/'.$arrDir[$intCount];

				if(!is_dir($pstrBasePath))
				{
					mkdir($pstrBasePath, 0777, TRUE);
                   // exec('visudo mkdir '.$pstrBasePath);
					exec('chmod 0777 '.$pstrBasePath.' -R');
				}

			}
		}
		return $pstrBasePath;
    }


/* For Creating a file in a folder */

 public static function createFile($pstrBasePath, $pstrDirPath, $pstrFileName, $pstrContent)
    {
			   @chmod($pstrBasePath.$pstrDirPath, 0777);
	           $myFile = $pstrBasePath.$pstrDirPath."/".$pstrFileName;
               $fh = fopen($myFile, 'w') or die();
               $stringData = $pstrContent;
               fwrite($fh, $stringData);
               fclose($fh);
		       return true;
    }





    /** This method is used to Get File Size of Folder or File.
	* Input: $path = Path of that Particular file or folder )
	* output: return the size in Bytes
	**/
	 public static function getFileSize($path)
	 {
		 $size = 0;
		 if(!is_dir($path))return @filesize($path);
		 $dir = opendir($path);
		 while($file = readdir($dir))
		 {
			 if(is_file($path."/".$file))
				 $size += filesize($path."/".$file);
			 if(is_dir($path."/".$file) && $file!="." && $file !="..")
				 $size +=self::getFileSize($path."/".$file);
		  }
		  return $size;
	 }

	/** This func returns a human readable size
	* Input: Int $size = Size of the File
	* Output: Return the size in human readable format.
	**/
	public static function getSizeHumanReadable($size)
	{
		$i=0;
		$iec = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		while (($size/1024)>1)
		{
			$size=$size/1024;
			$i++;
		}
		return substr($size,0,strpos($size,'.')+4).$iec[$i];
	}

	/** Getting file name and extension
	* Input: String $strpFileName = Name of the File
	* Output: return the Extension
	**/
    public static function getFileExt($strpFileName)
    {
		$arrFile=explode(".",$strpFileName);
		$strFileExt=strtolower($arrFile[count($arrFile)-1]);
		$strFileName=substr($strpFileName,0,-(strlen($strFileExt) + 1));
		return $strFileExt;
    }

	/** Description:  Append '...' if a string length cross the limit
	* Input: String $strpText = A particular Text/String
	* Input: Int $intpCheckLimit = Maximum Limit of the string. By default it is 15.
	* Input: Int $intpLimitFrom = Starting limit
	* Input: Int $intpLimitTo = Ending limit
	* Output: Return the string after appending dots at the last
	**/
	public static function addDots($strpText, $intpCheckLimit=15, $intpLimitFrom=NULL, $intpLimitTo=NULL)
	{
		if(strlen($strpText) >  $intpCheckLimit)
			return substr($strpText, (($intpLimitFrom==NULL) ? 0 : $intpLimitFrom), ($intpLimitTo==NULL) ? ($intpCheckLimit-3) : $intpLimitTo).'...';
		else
			return $strpText;
	}

	/**
	* This function is used to show data retrieved from database.
	* Pass "yes" in $blpAllowHtml if html tags are needed
	**/
	public static function showData($strpData, $blpAllowHtml="no", $blpLimitText="no", $intpLimitChars="10")
	{
		$blpAllowHtml = strtolower($blpAllowHtml);
		$blpLimitText = strtolower($blpLimitText);
		$strData = stripslashes($strpData);
        if($blpAllowHtml == "no")
        {
	        $strData = htmlentities($strData,ENT_QUOTES);
	        $strData = nl2br($strData);
        }

        // check for limit function
        if($blpLimitText == "yes")
        {
            $strData = self::addDots($strData, $intpLimitChars);
        }

        return $strData;
	}
	/**	This function is used to add slashes before special chars such as (',"",\,/ etc)
	* Input: String $strFieldName
	* output: Return the string with addslashes
	**/

/*___________________________________________________________________________________________________

	Created Date :
	Input: String $strFieldName
	Output: Return the string with slashes removed
	Description : This function is used to remove slashes before special chars such as (',"",\,/ etc)
_____________________________________________________________________________________________________*/
public static function removeFieldSlashes($strFieldName, $HtmlEntities = 0)
	{
		if (get_magic_quotes_gpc()) // if magic quotes ON in php.ini
			$strReturn = trim($strFieldName);
		else
			$strReturn = stripslashes(trim($strFieldName));

		if($HtmlEntities==1 || strtolower($HtmlEntities)=='htmlentities')
			return htmlentities($strReturn);
		else
			return	$strReturn;
	}
	/**	This function is used to remove html and php tags
	* Input: String $strFieldName
	**/
	public static function removeTags($strpData,$blpLimitText="no", $intpLimitChars="10")
	{
	    $strData = trim(strip_tags($strpData));
        if($blpLimitText == "yes")
        {
            $strData = self::addDots($strData, $intpLimitChars);
        }
        return $strData;
	}

	/**
	* calculating ratio for image
	* @param
	* $StandardWidth,$StandardHeight are from config
	* $ImageWidth, $ImageHeight are from image itself
	*/
	function imageSizeRatio($StandardWidth,$StandardHeight, $ImageWidth, $ImageHeight)
	{
		if(($ImageWidth < $StandardWidth) && ($ImageHeight < $StandardHeight))
		{
			$calWidth = $ImageWidth;
			$calHeight = $ImageHeight;
		}
		else
		{
			$wdZoom = $ImageWidth/$StandardWidth;
			$htZoom = $ImageHeight/$StandardHeight;
			if($wdZoom > $htZoom)
			{
				$calWidth = $ImageWidth/$wdZoom;
				$calHeight = $ImageHeight/$wdZoom;
			}
			else
			{
				$calWidth = $ImageWidth/$htZoom;
				$calHeight = $ImageHeight/$htZoom;
			}
		}
		$returnArr['Width'] = $calWidth;
		$returnArr['Height'] = $calHeight;
		return $returnArr;
	}

	/**
	* This function is used to generate success message
	* @param strMsg
	* @return string
	**/
	public static function getSuccessMsg($strMsg='')
	{
		$strMsg='<div class="successMsg">'.$strMsg.'</div>';
		return $strMsg;
	}

	/**
	* This function is used to generate error message
	* @param strMsg
	* @return string
	**/
	public static function getErrorMsg($strMsg='')
	{
		$strMsg='<div class="errorMsg" align="center">'.$strMsg.'</div>';
		return $strMsg;
	}

	/**
	* This function is used to generate messages for blank record like no record found
	* @param strMsg
	* @return string
	**/
	public static function getNoRecordMsg($strMsg='')
	{
		$strMsg='<div class="NoRecordMsg" align="center">'.$strMsg.'</div>';
		return $strMsg;
	}

	/**
	* This function is used to generate Mendatory message
	* @param strMsg
	* @return string
	**/
	public static function getMndMsg($strMsg='')
	{
		if($strMsg=='')
		{
			$strMsg = "The fields marked with * sign are mandatory";
		}

		$strMsg='<div class="redTxt">'.$strMsg.'</div>';
		return $strMsg;
	}

	/**
	* This function is used to generate Mendatory sign
	* @param strMsg
	* @return string
	**/
	public static function getMndSign($strMsg='')
	{
		$strMsg='<font color="red">*</font>';
		return $strMsg;
	}

	/**
	*Function to manage Special Char
	**/
	public static function dataEntQuotes($strFieldValue)
	{
		return htmlspecialchars(self::addFieldslashes($strFieldValue),ENT_QUOTES);
	}

	/**
	* This function is used to show the date in a particular format
	* @param $strpDate
	* @return formatted date
	**/
	public static function showDate($strpDate, $strpFormat="") // Earlier the default value for $strpFormat was 'datetime'
    {

		//if($strpFormat=='')
		//{



		//}
		//echo $strpFormat, '  ', $strpDate;
		$DateMsg='';
		if($strpFormat == "date")
		{
			$DateMsg= strftime('%d %B %Y', strtotime($strpDate));
		}
		elseif($strpFormat == "datetime")
		{
			$datetimetemp = strtotime($strpDate);
			$currenttime = strtotime(date("Y-m-d H:i:s"));
			$datediff = $currenttime-$datetimetemp;
			$yearTime = 365*24*60*60;
			$monthTime = 30*24*60*60;
			$weekTime = 7*24*60*60;
			$dayTime = 1*24*60*60;
			$hourTime = 60*60;
			$minutTime = 60;
			$YearDivision = floor(abs($datediff/$yearTime));
			if($YearDivision!=0)
			{
				if($YearDivision==1)
					$DateMsg=$YearDivision." year ago";
				else
					$DateMsg=$YearDivision." years ago";
			}
			elseif($YearDivision==0)
			{
				$MonthDivision=floor(abs($datediff/$monthTime));
				if($MonthDivision!=0)
				{
					if($MonthDivision==1)
						$DateMsg=$MonthDivision." month ago";
					else
						$DateMsg=$MonthDivision." months ago";
				}
				elseif($MonthDivision==0)
				{
					$DayDivision=floor(abs($datediff/$dayTime));
					if($DayDivision!=0)
					{
						if($DayDivision==1)
							$DateMsg=$DayDivision." day	ago";
						else
							$DateMsg=$DayDivision." days ago";
					}
					elseif($DayDivision==0)
					{
						$HourDiff=floor(abs($datediff/$hourTime));
						$HourReminder=$datediff%$hourTime;
						$MinutDiff=floor(abs($HourReminder/$minutTime));
						if($HourDiff==0)
							$DateMsg=$MinutDiff." min ago";
						else
							$DateMsg=$HourDiff." hrs ".$MinutDiff." min ago";
					}
				}
			}
		}
		elseif($strpFormat == 'dd-mm-yy')
		{
			$DateMsg= strftime('%d-%m-%y', strtotime($strpDate));
		}
		elseif($strpFormat == "dd/mm/yy")
		{
			$DateMsg= strftime('%d/%m/%y', strtotime($strpDate));
		}
		elseif($strpFormat == "dd.mm.yy")
		{
			$DateMsg= strftime('%d.%m.%y', strtotime($strpDate));
		}
		elseif($strpFormat == "dd.M.yy")
		{
			$DateMsg= strftime('%d.%b.%Y', strtotime($strpDate));
		}
		elseif($strpFormat == "mm-dd-yy")
		{
			$DateMsg= strftime('%m-%d-%y', strtotime($strpDate));
		}
		elseif($strpFormat == "mm/dd/yyyy")
		{
			$DateMsg= strftime('%m/%d/%Y', strtotime($strpDate));
		}
		elseif($strpFormat == "yyyy-mm-dd")
		{
			$DateMsg= strftime('%Y-%m-%d', strtotime($strpDate));
		}
		elseif($strpFormat == "monthdayyear")
		{
			$DateMsg= strftime('%b %d, %Y', strtotime($strpDate));
		}
		elseif($strpFormat == "monthdayyeartime")
		{
			$DateMsg= strftime('%b %d, %Y %H:%M ', strtotime($strpDate));
		}elseif($strpFormat == "monthdayyear")
		{
			$DateMsg= strftime('%b %d, %Y', strtotime($strpDate));
		}
		elseif($strpFormat == "time")
		{
			$DateMsg= date('g:i A', strtotime($strpDate));
		}

        return $DateMsg;
	}
	
public static function get_correct_utf8_string($s) 
	{ 
		if(empty($s)) return $s; 
		$s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]| 
			[\xC2-\xDF][\x80-\xBF]| \xE0[\xA0-\xBF][\x80-\xBF]| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}| 
			\xED[\x80-\x9F][\x80-\xBF]#x", $s, $m ); 
		return implode("",$m[0]); 
	} 
	

	public static function addSuccess($strMsg='')
{
		self::initSession(); 
		$strMsg='<div class="successmsg">'.$strMsg.'</div>';
		$_SESSION['AlertMessage'] = $strMsg;
}

public static function initSession()
{
		if(session_id() == ''){
            session_start();
        } 
}
	
	
	/**	This function is used to add slashes before special chars such as (',"",\,/ etc)
	* Input: String $strFieldName 
	* output: Return the string with addslashes
	**/
	public static function addFieldslashes($strFieldName)
	{
		if (get_magic_quotes_gpc()) // if magic quotes ON in php.ini
			return trim($strFieldName);
		else
			return addslashes(trim($strFieldName));
	}
	
	static function GetCourtList($Name, $Id, $Default='', $Class='dropdown', $cssStyle='',$Selected='', $onChange='')
 	{
	$pstrCondition=" status='approved'";
	$arrOptions = Court::GetCourtList("courtId", "DESC" , $pstrCondition);
	
	foreach ($arrOptions as $key => $Options)
		{
			$arrVals[] = array($Options['courtId'],$Options['courtName']);
		}
	return self::CreateCombo($arrVals, $Class, $Name, $Id, $Default,$Selected,$onChange,'',$cssStyle);
  }
  
 

	
	public static function printMessage()
	{
		self::initSession(); 
		if(isset($_SESSION['AlertMessage'])){
              echo $_SESSION['AlertMessage']; 
		      unset($_SESSION['AlertMessage']);
		}
	}
	
	
	/* Get Table data*/
	
	public function getTableData($table_name,$pstrcond,$req_feild)
	{
		global $_objDB;
		$pstrcond = (trim($pstrcond)) ? " WHERE $pstrcond " : "";
		$req_feild = (($req_feild)) ? "  $req_feild " : "*";
		$query="SELECT $req_feild FROM $table_name  $pstrcond";
		$res = $_objDB->ExecuteMultiQuery($query);
		$adminData = $_objDB ->FetchRecords();
		if(isset($adminData))
			return $adminData;
		else
			return '';
	}
	

/**
	 * This function is used to create combo from an 2 dimensional array
	 *
	 * @param unknown_type $arrValue
	 * @param unknown_type $clsName
	 * @param unknown_type $cmbName
	 * @param unknown_type $defValue
	 * @param unknown_type $selValue
	 * @return drop down
	 */


	public static function CreateCombo($arrValue, $clsName, $cmbName, $cmbId, $defValue='', $selValue='',$onchange='',$disabled='',$cssStyle='', $MultipleSelect = false, $Size=7 )
	{ 
	if($onchange==1) { 
	}
	   $strCombo = "";
	   if($onchange=='')
		{
			$onchange= '';			
		}
	  
	   $Multiple = $MultipleSelect?'multiple=multiple size='.$Size:'';
		if($MultipleSelect)
		{
			$cmbName = trim($cmbName, '[]').'[]';
		}

 	  // if multiple selection
	  if(@strstr($selValue,','))
				$selValue = explode(',',$selValue);
	  
	   $disabled = ($disabled == "true") ? "disabled" : "";
	   $strCombo .= '<Select name="'.$cmbName.'" id="'.$cmbId.'" class="'.$clsName.'" onchange="'.$onchange.'"'.$disabled.'  '.$cssStyle.' '.$Multiple.'>';
	   if($defValue!='' && !$MultipleSelect)
	   	$strCombo .= ' <option value="">'.$defValue.'</option>';
	  
		for($i=0;$i<count($arrValue);$i++)
		{   
			 if(is_array($selValue) && in_array($arrValue[$i][0],$selValue))
			 {
				$strCombo .= '<option value="'.$arrValue[$i][0].'" selected>'.($arrValue[$i][1]).'</option>';
			 }
			elseif($selValue == $arrValue[$i][0])
				{
				$strCombo .= '<option value="'.$arrValue[$i][0].'" selected>'.($arrValue[$i][1]).'</option>';
			    }
			else 
				{
				$strCombo .= '<option value="'.$arrValue[$i][0].'">'.($arrValue[$i][1]).'</option>';
				}
		
		} 
		$strCombo .= '</select>';
		return $strCombo;
	}
	
 public static function getValidImgUrl($imgPath,$defaultImgPath){
 	 $url=@getimagesize($imgPath);
	 if(!is_array($url))
	    $imgPath = $defaultImgPath;	
	return $imgPath; 
  }
  
  // welcome mail
  
  public static function sendWelcomeMail($email,$name){
  	  if(empty($email) || !self::isValidEmail($email))
  	  	  return false;
  	  
  	   $mail = new phpmailer(); 
	   $mail->IsSendmail(); // telling the class to use SendMail transport
	   $mail->IsHTML(true);
	   //$mail->AddReplyTo('sachin_kumaram@rediffmail.com', 'sachin kumaram');	   
	   
	   
	   $mail->AddAddress($email, $name);
	   $mail->From = 'support@racquetime.com';
	   $mail->FromName = 'Racquetime Support';
	   $mail->Subject = 'IGA - Welcome Email';
	   //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	   $fh = fopen(_WELCOMEMAIL_TPL_PATH,'r');
	   $mailContent = str_replace('[_WWWROOT]',_WWWROOT,fread($fh,filesize(_WELCOMEMAIL_TPL_PATH)));

	   $mail->Body = $mailContent;
	   $mail->Send();
  }
  
  public static function isValidEmail($email){
      $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
      if (eregi($pattern, $email)){
         return true;
      }
      else {
         return false;
      }   
   }
   
  
  
  
} // End of the Class