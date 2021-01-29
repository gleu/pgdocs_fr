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
  $version = substr($_SERVER['PHP_SELF'], 1, strpos($_SERVER['PHP_SELF'], "/", 1)-1);
  $url = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);

  $query = "SELECT p.version as version, v.ordre, count(*) as nb FROM pages p JOIN versions v USING(version) GROUP BY p.version, v.ordre ORDER BY v.ordre";
  $result = pg_query($pgconn, $query);

  while ($ligne = pg_fetch_array($result))
  {
    echo '<option value="'.$ligne['version'].'"';
    if ($filtreversion==$ligne['version'] or (strlen($filtreversion)==0 and $version==$ligne['version']))
      echo ' SELECTED';
    echo '>'.$ligne['version'].' ('.$ligne['nb'].' pages)</option>';
  }
  echo '<option value="0"';
  if ($filtreversion=='0') echo ' SELECTED';
  echo '>Toutes versions</option>';
?>
              </select>
              <input id="submit" name="submit" type="submit" value="Rechercher" />
<?php
  }
?>
