<?php
/*
 * Factux le facturier libre
 * Copyright (C) 2003-2004 Guy Hendrickx
 * 
 * Licensed under the terms of the GNU  General Public License:
 * 		http://www.opensource.org/licenses/gpl-license.php
 * 
 * For further information visit:
 * 		http://factux.sourceforge.net
 * 
 * File Name: fckconfig.js
 * 	Editor configuration settings.
 * 
 * * Version:  1.1.5
 * * * Modified: 23/07/2005
 * 
 * File Authors:
 * 		Guy Hendrickx
 *.
 */

include_once("nb.php");
include_once("date.php");
include_once("graphisme.php");

function mysql_result2($res,$col=0,$row=0){ 
    $numrows = mysql_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysql_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysql_fetch_row($res) : mysql_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

function strfloatval($val){
    $val = str_replace(",",".",$val);
    $val = preg_replace('/\.(?=.*\.)/', '', $val);
    return $val;
}

?>
