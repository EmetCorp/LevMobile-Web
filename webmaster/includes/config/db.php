<?php
   class db
    {
        var $arr=array();
        var $fields=array();
        // main constructor
        function db($hostname='localhost',$username='levmobile',$password='mobilelev2132!',$database='levmobile')
        {
            $this->hostname=$hostname;
            $this->username=$username;
            $this->password=$password;
            $this->database=$database;
            $this->conn=mysql_connect($hostname,$username,$password);
			//if($this->conn){echo "conn";} die;
			mysql_select_db($database);
            $rtable=@mysql_list_tables($this->database);
            while($temp=mysql_fetch_assoc($rtable))
            {
                $this->tables[]=$temp["Tables_in_{$database}"];
            }

        }// db function close tag
        function query($query)
        {
            $this->dbquery=$query;
            $this->fields=array();
            $this->arr=array();
            unset($this->rs);
            $this->result=mysql_query($query,$this->conn);
            if(mysql_error())
            {
                echo("<b>".mysql_error()."</b>");
            }
            if(!stristr($query,'update') and !stristr($query,'insert') and !stristr($query,'delete'))
            {
                while($this->temp=mysql_fetch_field($this->result))
                {
                    $this->fields[]=$this->temp->name;
                    $this->type[]=$this->temp->type;
                    if($this->temp->primary_key)
                    {
                        $this->primary=$this->temp->name;
                    }
                }
                while($this->rs=mysql_fetch_assoc($this->result))
                {
                    if($this->rs)
                    {
                        foreach($this->rs as $key=>$val)
                        {
                            $this->rs[$key]=nl2br($val);
                        }
                        $this->arr[]=$this->rs;
                    }
                }
                $this->carr=colarr($this->arr);
                @mysql_data_seek($this->result,0);
                $this->rs=mysql_fetch_assoc($this->result);
                return mysql_num_rows($this->result);
            }
            else
            {
                return  mysql_affected_rows();
            }

        }// query function closing tag
		
        function table($grid='',$parameters='')
        {
            if(empty($array))
            {
                $array=$this->arr;
            }
            if(empty($array[0]))
            {
                $output='<table border="0" cellspacing="0" cellpadding="0" align="center" ><tr><td class="error">No Records Found.</td></tr></table>';
                return $output;
            }
            //print_r($this->fields);
            foreach($this->fields as $field)
            {
                $fields[]=ucfirst($field);
            }

            array_unshift($array,$fields);
            $output='<form method="POST" style="margin:0px; padding:0px;">
                        <table border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                                <td>
                                    <table class="grid" ;border="0" cellspacing="0" cellpadding="0">';
            foreach($array as $key=>$col)
            {
                if(!$key)
                {
                    $attributes='onclick="gridchk(this.form);"';
                }
                else
                {
                    $attributes=' name="'.$this->primary.'[]" value="'.@$col[$this->primary].'" onclick="gridclass(this);"';
                }
                $output.='<tr>';
                if($grid)
                {
                    $output.='<td height="25px"><input type="checkbox"  '.$attributes.'></td>';
                }
                foreach($col as $row)
                {
                  $output.='<td class="td_margin">'.$row.'</td>';
                }
                $output.='</tr>';
            }
            $output.='</table>';
            if($grid)
            {
                $output.='</td>
                            </tr>
                            <tr>
                                <td >
                                    <input type="submit" name="action" value="insert">
                                    <input type="submit" name="action" value="update">
                                    <input type="submit" name="action" value="delete">
                                </td>
                            </tr>';
            }

            $output.='</table>
                </form>';
            return $output;
        }// Grid function closing tags
    }// db class closing tag

    function colarr($array) // function return 2-dimensional array with key-value pair
    {
        foreach($array as $key=>$val)
        {
            foreach($val as $k=>$v)
            {
                $temp[$k][$key]=$v;
            }
        }
        return @$temp;
      }

    if (!empty($HTTP_POST_VARS))
    {
        reset($HTTP_POST_VARS);
        while (list($k,$v) = each($HTTP_POST_VARS))
        {
            ${$k} = $v;
        }
    }
    if (!empty($HTTP_GET_VARS))
    {
        reset($HTTP_GET_VARS);
        while (list($k,$v) = each($HTTP_GET_VARS))
        {
            ${$k} = $v;
        }
    }
    if (!empty($HTTP_SERVER_VARS))
    {
        reset($HTTP_SERVER_VARS);
        while (list($k,$v) = each($HTTP_SERVER_VARS))
        {
            ${$k} = $v;
        }
    }
    if (!empty($HTTP_COOKIE_VARS))
    {
        reset($HTTP_COOKIE_VARS);
        while (list($k,$v) = each($HTTP_COOKIE_VARS))
        {
            ${$k} = $v;
        }
    }
    if (!empty($HTTP_SESSION_VARS))
    {
        reset($HTTP_SESSION_VARS);
        while (list($k,$v) = each($HTTP_SESSION_VARS))
        {
            ${$k} = $v;
        }
    }

    if (!empty($HTTP_POST_FILES))
    {
        reset($HTTP_POST_FILES);
        while (list($k,$v) = each($HTTP_POST_FILES))
        {
		   ${$k} = $v['tmp_name'];
           ${$k._name} = $v['name'];
            ${$k._type} = $v['type'];
            ${$k._size} = $v['size'];
            ${$k._error} = $v['error'];
        }
    }

?>