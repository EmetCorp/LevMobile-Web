<?php
function strleft($s1, $s2) { 
	return substr($s1, 0, strpos($s1, $s2));
}
function currenturl() { 
   $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
   $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
   $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
   return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function timeAmPm($time)
{
	$schtime = "";

	$arr = explode(":",trim($time));
	$hr  = $arr[0];

  if($arr>0)
	{
		if($arr[0]<12)
			$schtime = $arr[0].":".$arr[1]." am";
		elseif($arr[0]==12)
			$schtime = $arr[0].":".$arr[1]." pm";
		else
			$schtime = ($arr[0]-12).":".$arr[1]." pm";
	}
	return $schtime;
}

function getWeekDay($day)
 {
	 $d = "";
	 switch($day)
	 { 
	  case 0:
	    $d = "Sun";
	    break;
	   case 1:
	     $d = "Mon";
	     break;
	   case 2:
	     $d = "Tue";
	     break;
	   case 3:
	     $d = "Wed";
	     break;
	   case 4:
	     $d = "Thu";
	     break;
	    case 5:
	     $d = "Fri";
	     break;
		case 6:
	     $d = "Sat";
	     break;
	 }

	return $d;
 }

 function PostValues($hflds,$url,$retVal,$type='msg')
{
    $str = "<html><body>";
    $hflds.='<input type=hidden name="'.$type.'" value="'.$retVal.'">';
    $str.='<form name="f1" action="'.$url.'" method="post">';
    $str.=$hflds;
    $str.='</form>';
    $str.='<script>document.f1.submit();</script>';
    $str.='</body></html>';
    return $str;
}

function createHidden()
{
	$hflds ="";
	if (!empty($_POST))
	{
		reset($_POST);
		while (list($k,$v)=each($_POST))
		{
		   ${$k}=$v;
			if(is_array($v))
			{
				$v=implode(",",$v);
				$hflds .= '<input type="hidden" name="'.$k.'" value="'.htmlentities($v,ENT_QUOTES).'">';
			}
			else
				$hflds .= '<input type="hidden" name="'.$k.'" value="'.htmlentities($v,ENT_QUOTES).'">';
		}
	}
	return $hflds;
}

function upload_my_file($upload_file,$destination)
{	
	if(move_uploaded_file($upload_file,$destination))
	{		
		//umask("000");
		chmod($destination,0777);
		return true;
	}
	else
	{
		return false;
	}
}

function properFileName($text)
{
$text   =  preg_replace('/[_<>\¡¤¢£¥¦§¨ª«¬­®\¯°±²³´µ¶·¸¹º»¼½¾¿×÷\|?{}()","""†‡‰‹›™!#$%\]]/', '', $text);	
//$text = preg_replace('/[_<>\¡¤¢£¥¦§¨ª«¬­®\¯°±²³´µ¶·¸¹º»¼½¾¿×÷\|?{}()",''‚"""†‡‰‹›™!#$%&\'*+:;—=@~–ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜßàáâãäåæçèéêëìíîïñòóôõöøùúûüÿ\-\[\]]/', '', $text);
	$text = str_replace('\\', ' ', $text);
	$text = str_replace("/", " ", $text);
	$text = preg_replace('/\s+/', ' ', $text);
	$text = str_replace(" ", "-", trim($text));

  return  $text;

}

#################################################
#######function used to check valid file extension and upload file in destination folder ##########
function validateFileUpload($fileArray,$destinationPath, $multipleImage=-1, $arrOfExtList=array(), $mimeTypesArray=array())
{
	$multipleImage=(int)$multipleImage;
	//For multiple image
	if($multipleImage > -1)
	{
		$imageError=$fileArray["error"][$multipleImage];
		$imageSize=$fileArray["size"][$multipleImage];
		$imagetmp_name=$fileArray["tmp_name"][$multipleImage];
		$imageName=$fileArray["name"][$multipleImage];
	}
	else
	{
		$imageError=$fileArray["error"];
		$imageSize=$fileArray["size"];
		$imagetmp_name=$fileArray["tmp_name"];
		$imageName=$fileArray["name"];
	}
	if( $imageError=='' && $imageSize >  0){

	   $isValidFile=validateFileUploadExtension($fileArray,$multipleImage, $arrOfExtList,$mimeTypesArray);
	   if( $isValidFile==true){
            // uploding file to the server
			//echo "$imagetmp_name,$destinationPath";
			//die();
            @move_uploaded_file($imagetmp_name,$destinationPath);
            return array("1",$destinationPath,$imageName); // file uploaded successfull
        }else{
            return array("2"); // extension is not valid // "banner can only be of type:-".implode(",", $arrOfExtList);
        }
    }else{
        return array("3"); // file uploading is mandatory or error
    }
}

#################################################
#######function used to check valid file extension##########
function validateFileUploadExtension($fileArray,$multipleImage=-1, $arrOfExtList=array(), $mimeTypesArray=array()){
	global $commonExtenssionArr,$commonMimeTypesArray;
	if(empty($arrOfExtList))
	{
		$arrOfExtList=$commonExtenssionArr;
	}
	if(empty($mimeTypesArray))
	{
		$mimeTypesArray=$commonMimeTypesArray;
	}
	/*
	echo "<pre>";
	print_r($commonExtenssionArr);
	print_r($commonMimeTypesArray);
	*/
	/*For multiple image*/
	if($multipleImage > -1)
	{
		$imageError=$fileArray["error"][$multipleImage];
		$imageSize=$fileArray["size"][$multipleImage];
		$imagetmp_name=$fileArray["tmp_name"][$multipleImage];
		$imageName=$fileArray["name"][$multipleImage];
	}
	else
	{
		$imageError=$fileArray["error"];
		$imageSize=$fileArray["size"];
		$imagetmp_name=$fileArray["tmp_name"];
		$imageName=$fileArray["name"];
	}
	if( $imageError=='' && $imageSize >  0){
      	$fileArr = explode(".", $imageName);
        $extValue=$fileArr[count($fileArr)-1];
		$extValue=strtolower($extValue);
        ob_start();
        system('/usr/bin/file -i -b ' . realpath($imagetmp_name));
        $type = ob_get_clean();
         /*extracting file extention from uploaded file*/
        $parts = explode(';', $type);
        $mime_type_str = trim($parts[0]);
		$mime_type_str=strtolower($mime_type_str);
		/*echo "<br>"."Ext=".$mime_type_str."   , mime_type_str=".$mime_type_str;
		print_r($mimeTypesArray);*/

		if( isset($extValue) && in_array($extValue,$arrOfExtList) && isset($mime_type_str) ){
			/*echo "here";
			exit;*/
			return true;  /*file extenssion is valid*/
        }else{
            return false;  /*extension is not valid  "banner can only be of type:-".implode(",", $arrOfExtList);*/
        }
    }else{
        return false;  /*uploaded file is valid*/
    }
} /* eof function*/

function getFileExtenssion($fileName)
{
	$fileArr = explode(".", $fileName);
	$extValue=$fileArr[count($fileArr)-1];
	$file_base_name=substr($fileName,0,-strlen(".".$extValue));
	$fileArr=array();	
	$fileArr['file_name']=$file_base_name;
	$fileArr['ext']=$extValue;
	return $fileArr;
}

/* Function to convert any valid file to mp4 starts here */

function generateMp3($vidPath,$audioDesPath)
{
		
/*  if($audioFileExt == 'mp3' || $audioFileExt == 'MP3')
	{
		copy($tempDirPath.$audioFile, $audioDesPath.$mp3File);
	}
	else*/
	{           
		//$substr = FLVINSTALPATH." -i ".$tempDirPath.$audioFile." -vn -ar 44100 -ac 2 -ab 192 -f mp3 ".$audioDesPath.$mp3Path; 
		$substr = FLVINSTALPATH." -i ".$vidPath." -vn -ar 44100 -ac 2 -ab 192 -f mp3 ".$audioDesPath; 
	
	}
	exec($substr);
	
	umask(0000);
	chmod($audioDesPath,0777);
	unlink($vidPath);

} // end of function

/* Functon to convert any file to mp4 ends here */
function stripsl($val) {	
	return stripslashes($val);
}

function txfx_limit($text,$len="",$isnl=0)
{
       $fullcount = strlen($text);
	   if($isnl==0)
			$text = nl2br($text);
	   else
			$text = $text;
       //        $text=str_replace(array('<!--', '-->'), array('&lt;!--', '--&gt;'), $text);
               //$text = strip_tags($text);

               if($len == "")
               {
                   $count_chars = 200;
               }
               else
               {
                   $count_chars = $len;
               }
       $string_arr = explode(" ",$text);
       $tmp = "";
       for($i=0;$i<$count_chars;$i++)
       {
           if(strlen($tmp)>$count_chars)
           {
               break;
           }
           //$tmp.=  str_replace("href=","href=$add_category_dir",$string_arr[$i])." ";
          @ $tmp.= $string_arr[$i]." ";
       }
       //$text=html_entity_decode(stripslashes($tmp));
	   $text=$tmp;
       if($text!="")
       {
           if($fullcount>strlen($text))
           return $text."..";
           else
           return $text;
       }
       else
       return $text;
}

function admin_paging($total_recs,$paging,$page)
{
#################### paging starts ##################
$paging_table="";
if($total_recs>0){

	$startPage=((int)(($page)/5))*5;
	$endPage=$startPage+5;

	$previousPage = ($page-1);
	$nextPage = ($page+1);

	$no_of_page=ceil($total_recs/$paging);

	if($endPage > $no_of_page)
		$endPage=$no_of_page;
	$paging_table="";
		if($page != 0)
			$paging_table .='<a href="javascript:change_paging('.$previousPage.');" class="prev">&laquo; Previous</a>';
				

	for($i=$startPage;$i<$endPage;$i++){
		$j=$i+1;
		if($i!=$page) 
			$paging_table.="<a href=\"javascript:change_paging('$i');\">$j</a> ";		
		else
		    $paging_table.="<a href=\"javascript:change_paging('$i');\" class=\"current\">$j</a> ";
		
		$paging_table.= "  ";
	}
	$paging_table= substr($paging_table,0,-2);
	if($nextPage != $no_of_page)
			$paging_table .=' <a href="javascript:change_paging('.$nextPage.');" class="next">Next &raquo;</a>';

}
if(isset($no_of_page) && ($no_of_page<=1))
{
	$paging_table="";
}
		return $paging_table;
}
?>