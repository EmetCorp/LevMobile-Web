<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
define("ADMINTITLE", "::LevMobile::");
define("TITLE", "::LevMobile::");
if($_SERVER['HTTP_HOST']=='localhost'){ $Host = 'http://localhost/levmobile/'; }else{$Host = 'http://'.$_SERVER['HTTP_HOST'];}
define("siteUrl", $Host);
define("CSS", "".siteUrl."css/");
define("IMG", "".siteUrl."images/");
define("JS", "".siteUrl."js/");
function executeQuery($sql)
{
	$result = mysql_query($sql) or die("<span style='FONT-SIZE:11px; FONT-COLOR: #000000; font-family=tahoma;'><center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysql_error()."'</center></FONT>");
	return $result;
} 


function getSingleResult($sql)
{
	$response = "";
	$result = mysql_query($sql) or die("<center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysql_error()."'</center>");
	if ($line = mysql_fetch_array($result)) {
		$response = $line[0];
	} 
	return $response;
} 

function RtnWord($word,$n){ $str="";	 $word=explode(" ",$word);  $num=count($word); if($num<=$n) $n=$num; for($i=0;$i<$n;$i++) $str=$str.$word[$i]." "; return $str;	}
 
function filter($value)
	{
		if($value!='')
			{
				$data = trim($value);
				$data = addslashes($data);
			}
		return $data;	
	}


function num_amt($theval) { return number_format($theval,2,'.',','); }

function my_sdate($timestamp)
{
    if($timestamp=="")
    {
        $timestamp=time();
    }
     return date("d F, Y",$timestamp);
}


//date and time function
function mydate($timestamp)
{
    if(!$timestamp)
    {
        $timestamp=time();
    }
     return date("m/j/y, g:i A",$timestamp);
}
// date only function
function date_only($timestamp)
{
    if(!$timestamp)
    {
        $timestamp=time();
    }
     return date("F d",$timestamp);

}
//maintain query string...
function querystring($string='')
{
    $output = "&".$_SERVER['QUERY_STRING']."&";
    $pairs=explode("&",$string);
    foreach($pairs as $pair)
    {
        if($pair)
        {
            $vars=explode("=",$pair);
            //echo $output;
            $output=preg_replace("/&{$vars[0]}=.[^&]*&/","&",$output);
        }
    }
    if(!preg_match("{^&}",$output))
    {   
        $output="&".$output;
    }
    $output="?".$string.$output;
    return substr($output,0,-1);
}

// function for querystrng
function qStr($url,$str)
{
    $arr=explode('?',$url);
    $str_arr=explode('=',$str);
    //print_r($str_arr);
    if(empty($str_arr[0]))
    {
        return $url;
    }
    if($arr[1])
    {
        return $url."&$str";
    }
    else
    {
        return $url."?$str";
    }
    //return $url;
}

function qry_str($arr, $skip = '')
{
	$s = "?";
	$i = 0;
	foreach($arr as $key => $value) {
		if ($key != $skip) {
			if ($i == 0) {
				$s .= "$key=$value";
				$i = 1;
			} else {
				$s .= "&$key=$value";
			} 
		} 
	} 

	return $s;
}

/**---Image resize ------*
*name - ----Source uploaded file
*filename---Target file location
*/
function resize($name,$filename,$width,$height)
{
	global $gd2;
	$system=explode(".",$name);
	$img_ext=strrchr($name, ".");
	if (preg_match("/(jpg)/i",$img_ext)){
		$src_img=imagecreatefromjpeg($name);
	} else	if (preg_match("/(jpeg)/i",$img_ext)){
		$src_img=imagecreatefromjpeg($name);
	}else if (preg_match("/png/i",$img_ext)){
		$src_img=imagecreatefrompng($name);
	}else if (preg_match("/gif/i",$img_ext)){
		$src_img=imagecreatefromgif($name);
	}
	else
	{
		return "default.jpg";
	}
	$new_w = "";
	$new_h = "";
	$w=imageSX($src_img);
    $h=imageSY($src_img);
	$tx = $w;
	$ty = $h;
	$Ratio=$tx/$ty;
	while ($tx>$width || $ty > $height)
	{
		$tx=$tx-1;
		$ty = $tx/$Ratio;
	}
	$new_w=$tx;
    $new_h=$ty;
	if ($gd2==""){
		$dst_img=ImageCreate($new_w,$new_h);
		imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imageSX($src_img),imageSY($src_img)); 
	}else{
		$dst_img=ImageCreateTrueColor($new_w,$new_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imageSX($src_img),imageSY($src_img)); 
	}
	if (preg_match("/png/i",$img_ext)){
		imagepng($dst_img,$filename); 
	} else if (preg_match("/jpeg/i",$img_ext)){
		imagejpeg($dst_img,$filename); 
	}else if (preg_match("/jpg/i",$img_ext)){
		imagejpeg($dst_img,$filename); 
	}else if (preg_match("/gif/i",$img_ext)){
		imagegif($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
	return($filename);
}
function imageResize($target, $newcopy, $w, $h, $ext)
{
		list($w_orig, $h_orig) = getimagesize($target);
		$scale_ratio = $w_orig / $h_orig;
		if($w_orig<$w){ $w = $w_orig; } 
		if($h_orig<$h){ $h=     $h_orig; } 
		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif")
		{ 
		  $img = imagecreatefromgif($target);
		} 
		else if($ext =="png")
		{ 
		  $img = imagecreatefrompng($target);
		}
		else
		{ 
		  $img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($w, $h);
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
		imagejpeg($tci, $newcopy, 80);
}
// Function for send a mail
function send_email($from,$to,$subject,$message,$from_name="webmaster")
{
    if($from=='own')
    {
        $from="ali@xevoke.com";
    }
    $headers  = "From: $from_name <$from>\n";
    $headers .= "X-Sender: <$from>\n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "X-Priority: 3\n";
    $headers .= "Return-Path: <$from>\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
    @mail($to,$subject,$message,$headers);
}

function sendMail($to,$from,$subject,$message,$from_name="PassionRide.com Support")
{
    if($from=='own'){$from="ali@xevoke.com";  }
    $headers  = "From: $from_name <$from>\n";
    $headers .= "X-Sender: <$from>\n";
    $headers .= "X-Mailer: PHP\n";
    $headers .= "X-Priority: 3\n";
    $headers .= "Return-Path: <$from>\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
    @mail($to,$subject,$message,$headers);
}

//Function for base path
function basepath(){
$path=substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1);
//$_SERVER['SERVER_NAME']
return "http://"."".$path;
}
$aaliyakhan = 'Aaliyakhan';

?> 
