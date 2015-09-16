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
        function doSearch(){
        $('#dg').datagrid('load',{
            num: $('#num').val(),
            article: $('#article').val(),
            variete: $('#variete').val()
        });
    }
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

    <table id="dg" title="Articles" style="width:1300px;height:750px"
        toolbar="#toolbar"
        pagination="true" data-options="pageSize:50"
        idField="num"
        rownumbers="false" fitColumns="true" singleSelect="false">
      <thead>
        <tr>
  				<th field="num" width="10" editor="text">N.</th>
  				<th field="article" width="50" editor="text">Plante</th>
  				<th field="variete" width="70" editor="text">Variete</th>
          <th field="contenance" width="12" editor="text">Cont.</th>
          <th field="phyto" width="7" editor="text">P</th>
  				<th field="taille" width="30" editor="text">Taille</th>
  				<th field="conditionnement" width="30" editor="text">Cond.</th>
          <th field="prix_htva" width="20" editor="text">HT</th>
          <th field="prix_htva_gros" width="20" editor="text">HT:Gros</th>
          <th field="taux_tva" width="10" editor="text">TVA</th>
          <th field="prix_ttc_part" width="20" editor="text">TTC:Part.</th>
          <th field="taux_tva_part" width="10" editor="text">TVA:Part.</th>
          <th field="stock" width="20" editor="text">Quantite</th>
          <th field="cat" width="10" editor="text">Origine</th>
        </tr>
      </thead>
    </table>
    <div id="toolbar">

        <span>Num:</span>
        <input id="num" style="line-height:26px;border:1px solid #ccc">
        <span>Plante:</span>
        <input id="article" style="line-height:26px;border:1px solid #ccc">
        <span>Variete:</span>
        <input id="variete" style="line-height:26px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
    <br/>
    
      <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Creer</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Supprimer</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Sauver</a>
      <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Annuler</a>
    </div>


  </tr>
    </table></body>
  </html>
