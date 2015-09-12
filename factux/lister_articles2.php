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
 * File Name: lister_article.php
 * 	liste les article et donne acces a differentes actions
 *
 * * * Version:  1.1.5
 * * * * Modified: 23/07/2005
 *
 * File Authors:
 * 		Guy Hendrickx
 *.
 */
require_once("include/verif.php");
include_once("include/config/common.php");
include_once("include/config/var.php");
include_once("include/language/$lang.php");
include_once("include/utils.php");
include_once("include/headers.php");?>
<script type="text/javascript" src="javascripts/confdel.js"></script>


<link rel="stylesheet" type="text/css" href="easyui-crud/easyui.css">
<link rel="stylesheet" type="text/css" href="easyui-crud/icon.css">
<link rel="stylesheet" type="text/css" href="easyui-crud/demo.css">

<script type="text/javascript" src="easyui-crud/jquery-1.6.min.js"></script>
<script type="text/javascript" src="easyui-crud/jquery.easyui.min.js"></script>
<script type="text/javascript" src="easyui-crud/jquery.edatagrid.js"></script>
<script type="text/javascript">
  $(function(){
    $('#dg').edatagrid({
      url: 'easyui-crud/get_users.php',
      saveUrl: 'easyui-crud/save_user.php',
      updateUrl: 'easyui-crud/update_user.php',
      destroyUrl: 'easyui-crud/destroy_user.php'
    });
  });
</script>

<?php
include_once("include/finhead.php");
include_once("include/configav.php");
?>
<table width="1000" border="0" class="page" align="center">
  <tr>
    <td class="page" align="center">
      <?php
	include_once("include/head.php");
      ?>
    </td>
  </tr>
  <tr>

    <table id="dg" title="My Users" style="width:700px;height:250px"
        toolbar="#toolbar" pagination="true" idField="num"
        rownumbers="true" fitColumns="true" singleSelect="false">
      <thead>
        <tr>
  				<th field="num" width="50" editor="text">Num</th>
  				<th field="article" width="50" editor="text">Article</th>
  				<th field="variete" width="50" editor="text">Variete</th>
  				<th field="taille" width="50" editor="text">Phone</th>
        </tr>
      </thead>
    </table>
    <div id="toolbar">
      <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">New</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Destroy</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
    </div>



  </tr>
    </table></body>
  </html>
