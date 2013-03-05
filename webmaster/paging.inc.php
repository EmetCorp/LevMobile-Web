<?php

if($reccnt!=0){
$pagenum=$reccnt/$pagesize;
$PHP_SELF=@$HTTP_SERVER_VARS['PHP_SELF'];
$qry_str=@$HTTP_SERVER_VARS['argv'][0];

$m=$_GET;
unset($m['start']);

$qry_str=qry_str($m);

//echo "$qry_str : $p<br>";

	if($pagenum>10)
	{
		$j=$start/$pagesize;		
		$k=$j+10;
		if($k>$pagenum)
		{
			$k=$pagenum;
		}
	}
	else
	{
		$j=0;
		$k=$pagenum;
	}


?>
<?php  //="$start : $pagesize : $j : $k"?>
<table  border="0" align="center" cellpadding="0" cellspacing="0">
<tr> 
     <td width="82" align="center" valign="middle" class="link1"> <a href="<?=$PHP_SELF?><?=$qry_str?>&start=<?=$start-$pagesize?>"> 
	  <?php if($start!=0){ ?>
     Pre</a> 
      <?php } ?>      </td>
      <?php
			for($i=$j;$i<$k;$i++)
			{
				if($i==$j) echo "";
			   if(($pagesize*($i))!=$start)
				  {
	  ?>
     <td width="17" height="20" align="center" valign="middle"  class="link1"> <a href="<?=$PHP_SELF?><?=$qry_str?>&start=<?=$pagesize*($i)?>" class="green-14-underline" > 
      <?=$i+1?>
      </a> </td>
      <?php  } else { ?>
	 <td width="31" align="center" valign="middle" bgcolor="#000000" style="padding:3px 0px 3px 5px;"><span style="color:#FF0000; text-align:center;"><?=$i+1?></span></td>
    <?php  }
	 } ?>
      
    <td width="96" align="center" valign="middle"  class="link1">
        <?php
	    if($start+$pagesize < $reccnt){
		?>
      &nbsp;&nbsp; <a href="<?=$PHP_SELF?><?=$qry_str?>&start=<?=$start+$pagesize?>">Next</a>
     
      <?
		}
  ?>      </td>
  </tr>
</table>
<?php } ?>
