<?
$recherche = $_POST['q'];
$filtreversion = $_POST['v'];
$pgconn = @pg_connect("host=localhost dbname=docspgfr user=docspgfr") or die('Connexion impossible');
//$query = "SELECT set_curcfg('utf8_french')";
//$result = pg_query($pgconn, $query);

$version['801'] = '8.1';
$version['802'] = '8.2';
$version['803'] = '8.3';
?>
<!DOCTYPE html SYSTEM "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Documentation PostgreSQL en français</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="stylesheet" href="css/style.css" type="text/css" title="Test" />
		<!--
		<link rel="search" type="application/opensearchdescription+xml" title="PgFr Docs 8.1.X" href="http://docs.postgresqlfr.org/addon/pgfr-docs81-ff.osd" />
		-->
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
		      "http://docs.postgresqlfr.org/addon/"+name+".src",
		      "http://docs.postgresqlfr.org/addon/"+name+"."+ext,
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
	<div id="content">
		<a id="bandeau" href="http://www.postgresqlfr.org/">
			<img src="img/gauche.png" id="gauche" alt="PostgreSQL" />
			<img src="img/droite.png" id="droite" alt="PostgreSQL" />
		</a>
<form method="post" action="search.php">
  <div>
  <input id="q" name="q" type="text" size="20" maxlength="255" onfocus="if( this.value=='Rechercher' ) this.value='';" value="<?= strlen($_POST['q'])>0 ? $_POST['q'] : 'Rechercher' ?>" accesskey="s" /><input id="submit" name="submit" type="submit" value="Rechercher" />
  <select id="v" name="v">
<?
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
  </div>
</form>
<?
$recherche = $_POST['q'];

$query = "SELECT version, url, titre
FROM pages
WHERE (url like 'sql-%".ereg_replace(' ','',$recherche)."%.html' OR url like 'app-%".ereg_replace('_','',$recherche)."%.html' OR url like 'app-%".ereg_replace('_','-',$recherche)."%.html') ";
if ($filtreversion > 0)
  $query .= "AND version=".$filtreversion." ";
$query .= "ORDER BY version desc, titre ";
$result = pg_query($pgconn, $query);
if (pg_num_rows($result) > 0) {
?>
		<div style="text-align:left;text-weight:normal;">
<h1>Pages man</h1>
<ol>
<?

while ($ligne = pg_fetch_array($result)) {
  echo '<li>
<a href="http://docs.postgresqlfr.org/'.$version[$ligne['version']].'/'.$ligne['url'].'">Manuel PostgreSQL '.$version[$ligne['version']].', '.$ligne['titre'].'</a></li>';
}

  $result = pg_query($pgconn, $query);

pg_close($pgconn);
?>
</ol>
		</div>
<?
}
?>
		<div style="text-align:left;text-weight:normal;">
<h1>Résultats complets</h1>
<ol>
<?

$pgconn = pg_connect("host=localhost dbname=docspgfr  user=docspgfr") or die('Connexion impossible');
$query = "SET client_encoding TO utf8;";
$result = pg_query($pgconn, $query);

$term = $_POST['q'];

        ## No backslashes allowed
        $term = preg_replace('/\\\/', '', $term);

        ## Collapse parens into nearby words:
        $term = preg_replace('/\s*\(\s*/', ' (', $term);
        $term = preg_replace('/\s*\)\s*/', ') ', $term);

        ## Treat colons as word separators:
        $term = preg_replace('/:/', ' ', $term);

        $searchstring = '';
        if( preg_match_all('/([-!]?)(\S+)\s*/', $term, $m, PREG_SET_ORDER ) ) {
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
                else {
                    $searchstring .= " & $terms[2]";
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

        ## Quote the whole thing
        //$searchstring = $this->db->addQuotes($searchstring);

//echo "term : $term<br/>\nsearchstring = $searchstring<br/>\n";

$query = "SELECT version, url, titre, ts_headline(contenu, q) AS resume, to_char(ts_rank(fti, q)*100, '999.99') AS score
FROM pages, to_tsquery('".pg_escape_string($searchstring)."') AS q
WHERE fti @@ q ";
if ($filtreversion > 0)
  $query .= "AND version=".$filtreversion." ";
$query .= "ORDER BY ts_rank(fti, q) DESC
LIMIT 100";
$result = pg_query($pgconn, $query);

//echo $query;

while ($ligne = pg_fetch_array($result)) {
  echo '<li>
<a href="http://docs.postgresqlfr.org/'.$version[$ligne['version']].'/'.$ligne['url'].'">Manuel PostgreSQL '.$version[$ligne['version']].', '.$ligne['titre'].'</a> ['.$ligne['score'].' %]<br/>
...'.$ligne['resume'].'...<br/>&nbsp;<br/>
</li>';
}

  $result = pg_query($pgconn, $query);

pg_close($pgconn);
?>
</ol>
		</div>
<!--
		<div id="pg81">
			<h1>Un problème avec la documentation&nbsp;?</h1>
			<div class="listes">
				N'hésitez pas à nous en faire part sur notre <a
				href="http://svn.postgresqlfr.org">site de traduction</a>
				en remplissant un ticket.
			</div>
		</div>
-->
		<div id="basdepage" >
			<a href="http://www.postgresqlfr.org/">Retour au site</a>
			&nbsp;|&nbsp;
			<a href="http://www.postgresqlfr.org/?q=forum">Forums Web</a>
			&nbsp;|&nbsp;
			<a href="http://www.freebsd.org/copyright/license.html">Documentations sous licence BSD</a>
			&nbsp;|&nbsp;
			<a href="http://www.mozilla-europe.org/fr/products/firefox/"><img border="0" alt="Get Firefox!" title="Get Firefox!" src="http://sfx-images.mozilla.org/affiliates/Buttons/80x15/white_1.gif"/></a>
		</div>
	</div>
	</div>
  </body>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-140513-1";
urchinTracker();
</script>
</html>
