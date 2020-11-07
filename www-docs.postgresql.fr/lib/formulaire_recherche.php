<?php
  if (!$pgconn)
  {
?>
             <p>Recherche désactivée car pas de connexion à la base !</p>
<?php
  }
  else
  {
?>
	      <input id="q" name="q" type="text" size="16" maxlength="255"
                     title='Vous pouvez utiliser les opérateurs suivants : "and", "&","not","!","or","|","<->" ("suivi de" pour recherche de phrase)'
		     onfocus="if( this.value=='Rechercher' ) this.value='';"
		     value="<?= strlen($_REQUEST['q'])>0 ? $_REQUEST['q'] : 'Rechercher' ?>"
                     accesskey="s" />
              <select id="v" name="v">
<?php
  $query = "SELECT p.version as version, v.ordre, count(*) as nb FROM pages p JOIN versions v USING(version) GROUP BY p.version, v.ordre ORDER BY v.ordre";
  $result = pg_query($pgconn, $query);

  echo '<option value="0"';
  if ($filtreversion=='0') echo ' SELECTED';
  echo '>(toutes)</option>';
  $ligne = 1;
  while ($ligne = pg_fetch_array($result))
  {
    echo '<option value="'.$ligne['version'].'"';
    if ($filtreversion==$ligne['version'] or (strlen($filtreversion)==0 and $ligne==1))
      echo ' SELECTED';
    echo '>'.$ligne['version'].' ('.$ligne['nb'].' pages)</option>';
    $ligne++;
  }
?>
              </select>
              <input id="submit" name="submit" type="submit" value="Rechercher" />
<?php
  }
?>
