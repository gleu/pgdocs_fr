#! /usr/bin/php -qC
<?

function usage() {
  echo '
Ceci est is '.$_SERVER["argv"][0].'.

Usage:
  '.$_SERVER["argv"][0].' [OPTIONS]... REPERTOIRE_HTML VERSION_MAJEURE

Options générales :
  -d BASE         nom de la base de données de connexion
                  (par défaut : "'.$PGDATABASE.'")
  --help          affiche cette aide, puis quitte

Options de connexion :
  -h HOTE         hôte du serveur de base de données ou répertoire de la
                  socket (par défaut : "'.$PGHOST.'")
  -p PORT         port TCP du serveur (par défaut : "'.$PGPORT.'")
  -U UTILISATEUR  utilisateur PostgreSQL (par défaut : "'.$PGUSER.'")
  -W              force la demande du mot de passe
';
}

// Récupération des variables d'environnement
$PGHOST = getenv('PGHOST');
$PGPORT = getenv('PGPORT');

$PGUSER = getenv('PGUSER');
if (strlen("$PGUSER") == 0) {
  $PGUSER = getenv('USER');
}

$PGDATABASE = getenv('PGDATABASE');
if (strlen("$PGDATABASE") == 0) {
  $PGDATABASE = $PGUSER;
}

$PGPASSWORD = getenv('PGPASSWORD');

// Déchiffrage des options en ligne de commande
for ($i = 1; $i < $_SERVER["argc"]; $i++) {
  switch($_SERVER["argv"][$i]) {
    case "-h":
    case "--host":
      $PGHOST = $_SERVER['argv'][++$i];
      break;
    case "-p":
    case "--port":
      $PGPORT = $_SERVER['argv'][++$i];
      break;
    case "-U":
    case "--user":
      $PGUSER = $_SERVER['argv'][++$i];
      break;
    case "-d":
    case "--database":
      $PGDATABASE = $_SERVER['argv'][++$i];
      break;
    case "-W":
      $g_passwordrequired = true;
      break;
    case "-?":
    case "-h":
    case "--help":
      usage();
      exit;
      break;
    default:
      if ($i == count($_SERVER['argv']) - 2) {
        $dir = $_SERVER['argv'][$i];
      } else if ($i == count($_SERVER['argv']) - 1) {
        $version = $_SERVER['argv'][$i];
        switch ($version) {
          case '8.1':
            $version_int = 801;
            break;
          case '8.2':
            $version_int = 802;
            break;
          case '8.3':
            $version_int = 803;
            break;
          case '8.4':
            $version_int = 804;
            break;
          case '9.0':
            $version_int = 900;
            break;
          case '9.1':
            $version_int = 901;
            break;
          case '9.2':
            $version_int = 902;
            break;
          default:
            echo "Il s'agit d'une version majeure : 8.1, 8.2, 8.3, 8.4, 9.0 ou 9.1.\n";
            exit;
            break;
        }
      }
      break;
  }
}

if (strlen($dir) == 0 or strlen($version) == 0) {
  usage();
  exit;
}

// Vérification du mot de passe
if ($g_passwordrequired and strlen($PGPASSWORD) == 0) {
  // On commence par la vérification de .pgpass
  $pgpassfilename = getenv('HOME').'/.pgpass';
  if (file_exists($pgpassfilename)) {
    $permissions = substr(decoct(fileperms($pgpassfilename)), 3);
    if (!strcmp($permissions, '600')) {
      $pgpassfile = fopen($pgpassfilename, 'r');
      $found = false;
      while (!$found and $line = fgets($pgpassfile)) {
        list($host, $port, $database, $user, $password) = split (":", trim($line), 5);
        if ((!strcmp($PGHOST, $host) or !strcmp('*', $host)) and
            (!strcmp($PGPORT, $port) or !strcmp('*', $port)) and
            (!strcmp($PGDATABASE, $database) or !strcmp('*', $database)) and
            (!strcmp($PGUSER, $user) or !strcmp('*', $user))) {
          $found = true;
          $PGPASSWORD = $password;
        }
      }
    }
  }
  // s'il n'y a toujours pas de mot de passe
  if (strlen($PGPASSWORD) == 0) {
    // Attention, le mot de passe apparaîtra en clair sur le terminal...
    // Au moins, il ne sera pas affiché avec la commande ps :-/
    echo "Mot de passe : ";
    $stdin = fopen('php://stdin', 'r');
    $PGPASSWORD = fgets(STDIN);
  }
}

// Connexion à la base de données suivant les variables d'environnement

$DSN = '';

if (strlen("$PGHOST") > 0) {
  $DSN .= 'host='.$PGHOST.' ';       
}
if (strlen("$PGPORT") > 0) {
  $DSN .= 'port='.$PGPORT.' ';       
}

$DSN .= 'dbname='.$PGDATABASE.' '.
       'user='.$PGUSER;

if (strlen("$PGPASSWORD") > 0) {
  $DSN .= ' password='.$PGPASSWORD;
}

$pgconn = pg_connect($DSN)
  or die('Connexion impossible');

// l'encodage est l'UTF-8
$query = "SET client_encoding TO utf8;";
pg_query($pgconn, $query);

// lecture du répertoire
if ($handle = opendir($dir)) {
  echo "Traitement du répertoire : ";

  $result = pg_query($pgconn, 'BEGIN');

  $query = 'DELETE FROM pages WHERE version='.$version_int;
  $result = pg_query($pgconn, $query);

  while (false !== ($file = readdir($handle))
         and pg_result_status($result) == PGSQL_COMMAND_OK) {
    if (strcmp($file, '.') and strcmp($file, '..')) {
      echo ".";
      // lecture du contenu en un coup
      $contenu = file_get_contents($dir.'/'.$file);
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
        $tags1 = '';
        for ($index = 1; $index < count($matches); $index=$index+2) {
          if (strlen($tags1) > 0) {
            $tags1 .= ',';
          }
          $tags1 .= $matches[$index];
        }
        $tags1 = preg_replace('#\s+#',' ',$tags1);
      }
      // récup des mots clés d'index
      if (preg_match('#<em class="secondterm"[^>]*>([^<]+)</em[^>]*>#si', $contenu, $matches)) {
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
               ".$version_int.",
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
    echo "Vacuum OK\n";
  else
    echo "ERREUR lors du Vacuum !\n".pg_last_error($pgconn);

  $query = "DROP TABLE mots CASCADE";
  $result = pg_query($pgconn, $query);
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo "Suppression de la table mots OK\n";
  else
    echo "ERREUR lors de la suppression !\n".pg_last_error($pgconn);

  $query = "CREATE TABLE mots AS
  SELECT word AS mot FROM ts_stat('SELECT to_tsvector(''simple'', contenu) FROM pages')";
  $result = pg_query($pgconn, $query);
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo "Création de la table mots OK\n";
  else
    echo "ERREUR lors de la création !\n".pg_last_error($pgconn);

  $query = "CREATE INDEX mots_idx ON mots USING gin(mot gin_trgm_ops)";
  $result = pg_query($pgconn, $query);
  if (pg_result_status($result) == PGSQL_COMMAND_OK)
    echo "Création de l'index pour la table mots OK\n";
  else
    echo "ERREUR lors de la création !\n".pg_last_error($pgconn);

  closedir($handle);
}

pg_close($pgconn);

?>
