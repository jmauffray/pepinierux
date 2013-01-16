<?php
$sql = "SELECT num, variete, taille, cat, conditionnement FROM " . $tblpref ."article";
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error());

$rqSql = "SELECT id_cat, categorie FROM " . $tblpref ."categorie WHERE 1";
$result = mysql_query( $rqSql ) or die( "Exécution requête impossible.");
$categories = array();
while ( $row = mysql_fetch_array( $result)) {
    $categories[$row["id_cat"]] = $row["categorie"];
}
?>
<script>
  $(function() {  
  var availableTags = [
  <?php 
      while($data = mysql_fetch_array($req))
      {
          echo "{ label:\"".addslashes($data['num']." - ".$data['variete']." - ".$data['taille']." - ".$categories[$data['cat']]." - ".$data['conditionnement'])."\", value:\"".$data['num']."\"},";
      }
  ?>
  ];

  $( "#autocomplete" ).autocomplete({
  source: availableTags
  });

  $("#meta-area").autocomplete({
  source:availableTags,
  select: function(e, ui) {
  e.preventDefault()
  $("#meta_search_ids").val(ui.item.value);

  $(this).val(ui.item.label);
  }
  });
  //alert("this loaded");
  });

</script>
