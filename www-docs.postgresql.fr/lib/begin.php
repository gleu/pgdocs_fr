<?php
include "lib/config.php";

$obsoletes = false;

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

$pgconn = pg_connect("$DSN");

if ($pgconn)
{
  $query = "SET client_encoding TO utf8;";
  $result = pg_query($pgconn, $query);
}
?>

