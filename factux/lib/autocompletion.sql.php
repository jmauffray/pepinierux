<?php 
	if(isset($_POST['chef'])){
		header('Content-type: text/html; charset=iso-8859-1');
		// on fait la requete
		$sql = "SELECT `num`, `article`, `variete`
				FROM " . $tblpref ."article
				WHERE `variete` LIKE '".$_POST['chef']."%'";
		$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
		
		$i = 0;
		echo '<ul class="contacts">';
		// on boucle sur tous les elements
		while($autoCompletion = mysql_fetch_assoc($req)){
			echo '
			<li class="contact">
                         <div class="nom">'.$autoCompletion['num'].'/'.$autoCompletion['article'].'/'.$autoCompletion['variete'].'</div>
                        <span class="informal" style="display:none">'.$autoCompletion['num'].'-idcache</span>
			</li>';
			// on s'arrete s il y en a trop
			if (++$i >= 2000)
				die('<li>...</li></ul>');
		}
		echo '</ul>';
		die();
	}
?>
