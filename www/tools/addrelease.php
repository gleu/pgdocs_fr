#! /usr/bin/php -qC
<?
$version_int['8.1.11'] = 801;
$version_int['8.2.7'] = 802;
$version_int['8.3.1'] = 803;

$pgconn = pg_connect("host=localhost dbname=docspgfr user=docspgfr")
  or die('Connexion impossible');

$query = "SET client_encoding TO utf8;";
$result = pg_query($pgconn, $query);

if (strlen($argv[1]) > 0)
  $version = $argv[1];
else
  $version = '8.3';

//$version_int = ereg_replace('\.', '', $version);

$dir = './pgsql-'.$version.'-fr';

if ($handle = opendir($dir)) {
  echo "Traitement du répertoire : ";

  $result = pg_query($pgconn, 'BEGIN');

  $query = 'DELETE FROM pages WHERE version='.$version_int[$version];
  $result = pg_query($pgconn, $query);

  while (false !== ($file = readdir($handle)) and pg_result_status($result) == PGSQL_COMMAND_OK) {
    if (strcmp($file, '.') and strcmp($file, '..')) {
      echo ".";
      // lecture du contenu en un coup
      $contenu = file_get_contents($dir.'/'.$file);
      //$contenu = iconv('ISO-8859-1', 'UTF-8', $contenu);
      // récup du titre
      if (preg_match('#<title[^>]*>([^<]+)</title[^>]*>#si', $contenu, $matches)) {
        $titre = $matches[1];
        $titre = preg_replace('#\s+#',' ',$titre);
      }
      $titre = trim(ereg_replace('[0-9A-Z.]*\&nbsp;', '', $titre));
      $titre = ereg_replace('Chapitre', '', $titre);
      $titre = ereg_replace('Annexe', '', $titre);
      $titre = ereg_replace('Partie', '', $titre);
      $titre = html_entity_decode($titre,ENT_COMPAT,'utf-8');
      // récup des mots clés d'index
      if (preg_match('#<em class="firstterm"[^>]*>([^<]+)</em[^>]*>#si', $contenu, $matches)) {
//print_r($matches);
//echo $file.' : '.count($matches)."\n";
        $tags1 = '';
        for ($index = 1; $index < count($matches); $index=$index+2) {
//echo "  ".$index.' : '.$tags1."\n";
          if (strlen($tags1) > 0) {
            $tags1 .= ',';
          }
          $tags1 .= $matches[$index];
        }
        $tags1 = preg_replace('#\s+#',' ',$tags1);
      }
      // récup des mots clés d'index
      if (preg_match('#<em class="secondterm"[^>]*>([^<]+)</em[^>]*>#si', $contenu, $matches)) {
//echo $file.' : '.count($matches)."\n";
        $tags2 = '';
        for ($index = 1; $index < count($matches); $index=$index+2) {
          if (strlen($tags2) > 0) {
            $tags2 .= ',';
          }
          $tags2 .= $matches[$index];
        }
        $tags2 = preg_replace('#\s+#',' ',$tags2);
      }
      // récup du corps
      if (preg_match('#<div class="part"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<div class="sect1"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<div class="refentry"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<div class="reference"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<div class="appendix"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<div class="chapter"[^>]*>(.*)</div[^>]*>[^<]*<div#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      elseif (preg_match('#<body[^>]*>(.*)</body[^>]*>#si', $contenu, $matches)) {
        $corps = $matches[1];
      }
      $corps = strip_tags($corps);
      $corps = preg_replace('#\s+#',' ',$corps);
      $corps = html_entity_decode($corps,ENT_COMPAT,'utf-8');
      // préparation de la requête...
      $query = "INSERT INTO pages (url, version, titre, tags1, tags2, contenu) VALUES (
               '".pg_escape_string($file)."',
               ".$version_int[$version].",
               '".pg_escape_string($titre)."',
               '".pg_escape_string($tags1)."',
               '".pg_escape_string($tags2)."',
               '".pg_escape_string($corps)."')";
      // ... et exécution de cette dernière
      $result = pg_query($pgconn, $query);
    }
  }

  $result = pg_query($pgconn, 'END');
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo " OK\n";
  else
    echo " ERREUR !\n".pg_last_error($pgconn);

  //$query = "SELECT set_curcfg('utf8_french')";
  //$result = pg_query($pgconn, $query);

  $query = "UPDATE pages SET "
          ."fti = setweight( to_tsvector('french', titre), 'A' ) || setweight( to_tsvector('french', tags1), 'B' ) || setweight( to_tsvector('french', tags2), 'C' ) || setweight( to_tsvector('french', contenu), 'D' )";
  $result = pg_query($pgconn, $query);
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo "Mise à jour OK\n";
  else
    echo "ERREUR lors de la mise à jour !\n".pg_last_error($pgconn);

  $query = "VACUUM ANALYZE pages";
  $result = pg_query($pgconn, $query);
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo "VacAna OK\n";
  else
    echo "ERREUR lors du VacAna !\n".pg_last_error($pgconn);

  closedir($handle);
}

pg_close($pgconn);

?>
