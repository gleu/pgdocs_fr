<?php
include "config.php";

$recherche = $_REQUEST['q'];
$filtreversion = $_REQUEST['v'];

## No backslashes allowed
$recherche = preg_replace('/\\\/', '', $recherche);
$recherche = preg_replace('/\(/', '', $recherche);
$recherche = preg_replace('/\)/', '', $recherche);
## Collapse parens into nearby words:
$recherche = preg_replace('/\s*\(\s*/', ' (', $recherche);
$recherche = preg_replace('/\s*\)\s*/', ') ', $recherche);
## Treat colons as word separators:
$recherche = preg_replace('/:/', ' ', $recherche);

$recherche_value = strlen($recherche)>0 ? $recherche : 'Rechercher';

$pgconn = @pg_connect("$DSN") or die('Connexion impossible');

$query = "SET client_encoding TO utf8;";
$result = pg_query($pgconn, $query);

$version['803'] = '8.3';
$version['804'] = '8.4';
$version['900'] = '9.0';
$version['901'] = '9.1';
$version['902'] = '9.2';
$version['903'] = '9.3';
$version['904'] = '9.4';
$version['905'] = '9.5';
$version['906'] = '9.6';
$version['100'] = '10';
$version['110'] = '11';
$version['120'] = '12';
?>
<!DOCTYPE html SYSTEM "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Documentation PostgreSQL en français</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="stylesheet" href="css/style.css" type="text/css" title="Test" />
		<script type="text/javascript">
		<!--
		function errorMsg()
		{
		  alert("Netscape 6 or Mozilla is needed to install a sherlock plugin");
		}

		function addEngine(name,ext,cat,type)
		{
		  if ((typeof window.sidebar == "object") && (typeof window.sidebar.addSearchEngine == "function")) {
		    window.sidebar.addSearchEngine(
		      "http://docs.postgresql.fr/addon/"+name+".src",
		      "http://docs.postgresql.fr/addon/"+name+"."+ext,
		      name,
		      cat );
		  } else {
		    errorMsg();
		  }
		}
		//-->
		</script>		
	</head>
	<body>
<div id="top">
  <div id="pgHeader">
    <span id="pgHeaderLogoLeft">
      <a href="/" title="PostgreSQL"><img src="http://www.postgresql.fr/lib/tpl/tortoise/images/hdr_left.png" width="230" height="80" alt="PostgreSQL" /></a>
    </span>
    <span id="pgHeaderLogoRight">
      <a href="/" title="La base de donnees la plus sophistiquee au monde."><img src="http://www.postgresql.fr/lib/tpl/tortoise/images/hdr_right.png" width="210" height="80" alt="La base de donnees la plus sophistiquee au monde." /></a>
    </span>
  </div>
</div>

<div class="pgTopNav">
  <div class="pgTopNavLeft"> 
    <img src="http://www.postgresql.fr/lib/tpl/tortoise/images/nav_lft.png" width="7" height="23" alt="" />
  </div>
  <div class="pgTopNavRight">
    <img src="http://www.postgresql.fr/lib/tpl/tortoise/images/nav_rgt.png" width="7" height="23" alt="" />
  </div>
  <ul class="pgTopNavList">
    <li><a href="http://www.postgresql.fr/" title="Accueil">Accueil</a></li>
    <li><a href="http://blog.postgresql.fr/" title="Lire les actualités">Actualités</a></li>
    <li><a href="http://docs.postgresql.fr/" title="Lire la documentation officielle">Documentation</a></li>
    <li><a href="http://forums.postgresql.fr/" title="Pour poser des questions">Forums</a></li>
    <li><a href="http://asso.postgresql.fr/" title="La vie de l'association">Association</a></li>
    <li><a href="http://trac.postgresql.fr" title="Trac des développeurs">Développeurs</a></li>
    <li><a href="http://planete.postgresql.fr" title="La planète francophone sur PostgreSQL">Planète</a></li>
    <li><a href="http://support.postgresql.fr" title="Support sur PostgreSQL">Support</a></li>
  </ul>
</div>
<div id="pgContent">

  <div id="pgSideWrap">
  <div id="pgSideNav">
      <form method="post" action="search.php">
      <div>
      <h2><label for="q">Rechercher</label></h2>
      <input id="q" name="q" type="text" size="16" maxlength="255" title='Vous pouvez utiliser les opérateurs suivants : "and", "&","not","!","or","|","<->" ("suivi de" pour recherche de phrase)' onfocus="if( this.value=='rechercher' ) this.value='';" value="<?= strlen($_request['q'])>0 ? $_request['q'] : 'rechercher' ?>" accesskey="s" />
  <select id="v" name="v">
<?php
  $query = "SELECT version, count(*) as nb FROM pages GROUP BY version ORDER BY version DESC";
  $result = pg_query($pgconn, $query);

  echo '<option value="0"';
  if ($filtreversion=='0') echo ' SELECTED';
  echo '>(toutes)</option>';
  $ligne = 1;
  while ($ligne = pg_fetch_array($result)) {
    echo '<option value="'.$ligne['version'].'"';
    if ($filtreversion==$ligne['version'] or (strlen($filtreversion)==0 and $ligne==1))
      echo ' SELECTED';
    echo '>'.$version[$ligne['version']].' ('.$ligne['nb'].' pages)</option>';
    $ligne++;
  }
?>
  </select>
  <input id="submit" name="submit" type="submit" value="Rechercher" />
  </div>
    </form>
  </div>
  </div>

  <div id="pgContentWrap">
  <div id="pgDownloadsWrap">
  <div id="content">
<?php
$like[0]="'sql-%".pg_escape_string(preg_replace('/ /','',$recherche))."%.html'";
$like[1]="'app-%".pg_escape_string(preg_replace('/_/','',$recherche))."%.html'";
$like[2]="'app-%".pg_escape_string(preg_replace('/_/','-',$recherche))."%.html'";

$query = "SELECT version, url, titre
FROM pages
WHERE (url like {$like[0]} OR url like {$like[1]} OR url like {$like[2]}) ";
if (array_key_exists($filtreversion, $version)) {
  $query .= "AND version=".pg_escape_string($filtreversion)." ";
}
$query .= "ORDER BY version desc, titre ";
$result = pg_query($pgconn, $query);
if (pg_num_rows($result) > 0) {
?>
		<div style="text-align:left;text-weight:normal;">
<h1>Pages man</h1>
<ol>
<?php

while ($ligne = pg_fetch_array($result)) {
  echo '<li>
<a href="http://docs.postgresql.fr/'.$version[$ligne['version']].'/'.$ligne['url'].'">Manuel PostgreSQL '.$version[$ligne['version']].', '.$ligne['titre'].'</a></li>';
}

  $result = pg_query($pgconn, $query);
?>
</ol>
		</div>
<?php
}
?>
		<div style="text-align:left;text-weight:normal;">
<h1>Résultats complets</h1>
<ol>
<?php

$searchstring = '';

$no_and = false;

if( preg_match_all('/([-!]?)(\S+)\s*/', $recherche, $m, PREG_SET_ORDER ) ) {
  foreach( $m as $terms ) {
    if (strlen($terms[1])) {
      $searchstring .= ' & !';
    }
    if (strtolower($terms[2]) === 'and') {
      $searchstring .= ' & ';
    }
    else if (strtolower($terms[2]) === 'or' or $terms[2] === '|') {
      $searchstring .= ' | ';
    }
    else if (strtolower($terms[2]) === 'not') {
      $searchstring .= ' & !';
    }
    else if (strtolower($terms[2]) === '<->') {
      $searchstring .= ' <->';
      $no_and = true;
    }
    else {
      if ($no_and) {
        $searchstring .= " $terms[2]";
        $no_and = false;
      }
      else {
        $searchstring .= " & $terms[2]";
      }
    }
  }
}


## Strip out leading junk
$searchstring = preg_replace('/^[\s\&\|]+/', '', $searchstring);

## Remove any doubled-up operators
$searchstring = preg_replace('/([\!\&\|]) +(?:[\&\|] +)+/', "$1 ", $searchstring);

## Remove any non-spaced operators (e.g. "Zounds!")
$searchstring = preg_replace('/([^ ])[\!\&\|]/', "$1", $searchstring);

## Remove any trailing whitespace or operators
$searchstring = preg_replace('/[\s\!\&\|]+$/', '', $searchstring);

## Remove unnecessary quotes around everything
$searchstring = preg_replace('/^[\'"](.*)[\'"]$/', "$1", $searchstring);

$query = "SELECT version, url, titre, ts_headline(contenu, q) AS resume, to_char(ts_rank_cd(fti, q,4)*100, '999.99') AS score
FROM pages, to_tsquery('french','".pg_escape_string($searchstring)."') AS q
WHERE fti @@ q ";
if (array_key_exists($filtreversion, $version)) {
  $query .= "AND version=".pg_escape_string($filtreversion)." ";
}
$query .= "ORDER BY ts_rank_cd(fti, q,4) DESC, version DESC
LIMIT 100";
$result = pg_query($pgconn, $query);

if (pg_num_rows($result) > 0) {

  while ($ligne = pg_fetch_array($result)) {
    echo '<li>
<a href="http://docs.postgresql.fr/'.$version[$ligne['version']].'/'.$ligne['url'].'">Manuel PostgreSQL '.$version[$ligne['version']].', '.$ligne['titre'].'</a> ['.$ligne['score'].' %]<br/>
...'.$ligne['resume'].'...<br/>&nbsp;<br/>
</li>';
  }

} else {
  echo '<b>Aucun résultat trouvé</b><br/>';

  $query = "SELECT mot, to_char(similarity(mot, '".pg_escape_string($searchstring)."')*100,'999.99') AS sml
FROM mots
WHERE mot % '".pg_escape_string($searchstring)."'
ORDER BY sml DESC, mot";

  $result = pg_query($pgconn, $query);

  if (pg_num_rows($result) > 0) {

    echo "<p>Peut-être cherchez-vous :</p><ul>";
    while ($ligne = pg_fetch_array($result)) {
      echo '<li>
<a href="http://docs.postgresql.fr/search.php?v='.$filtreversion.'&q='.$ligne['mot'].'">'.$ligne['mot'].'</a> ['.$ligne['sml'].' %]</li>';
    }
    echo "</ul>";
}

}

  $result = pg_query($pgconn, $query);

pg_close($pgconn);
?>
</ol>
		</div>

	</div>
	</div>
  </body>
<script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ?
  "https://ssl." : "http://www.");
  document.write(unescape("%3Cscript src='" + gaJsHost +
  "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
   var pageTracker = _gat._getTracker("UA-140513-1");
   pageTracker._addOrganic("pgfrsearch", "q");
   pageTracker._initData();
   pageTracker._trackPageview();
</script>
</html>
