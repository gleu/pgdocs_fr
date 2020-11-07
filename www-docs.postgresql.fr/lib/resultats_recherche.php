<?php

/***************************
 * RECHERCHE DES PAGES MAN *
 ***************************/

$like[0]="'sql-%".pg_escape_string(preg_replace('/ /','',$recherche))."%.html'";
$like[1]="'app-%".pg_escape_string(preg_replace('/_/','',$recherche))."%.html'";
$like[2]="'app-%".pg_escape_string(preg_replace('/_/','-',$recherche))."%.html'";

$query = "SELECT pages.version, url, titre
FROM pages
JOIN versions ON pages.version=versions.version
WHERE (url ilike {$like[0]} OR url ilike {$like[1]} OR url ilike {$like[2]}) ";
if (strlen($filtreversion) > 1) {
  $query .= "AND versions.version='".pg_escape_string($filtreversion)."' ";
}
$query .= "ORDER BY ordre, titre ";
$result = pg_query($pgconn, $query);
if (pg_num_rows($result) > 0) {
?>
<div style="text-align:left;text-weight:normal;">
<h2>Pages man</h2>
<ol>
<?php

while ($ligne = pg_fetch_array($result)) {
  echo '
<li>
  <a href="'.$ligne['version'].'/'.$ligne['url'].'">Manuel PostgreSQL '.$ligne['version'].', '.$ligne['titre'].'</a>
</li>';
}

  $result = pg_query($pgconn, $query);
?>
</ol>
</div>
<?php
}

/***************************************
 * RECHERCHE DANS LE CONTENU DES PAGES *
 ***************************************/

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

$query = "SELECT pages.version, url, titre, ts_headline(contenu, q) AS resume, to_char(ts_rank_cd(fti, q,4)*100, '999.99') AS score
FROM versions, pages, to_tsquery('french','".pg_escape_string($searchstring)."') AS q
WHERE fti @@ q 
  AND pages.version=versions.version ";
if (strlen($filtreversion) > 1) {
  $query .= "AND versions.version='".pg_escape_string($filtreversion)."' ";
}
$query .= "ORDER BY ts_rank_cd(fti, q,4) DESC, ordre
LIMIT 100";
$result = pg_query($pgconn, $query);

if (pg_num_rows($result) > 0) {
?>
<div style="text-align:left;text-weight:normal;">
<h2>Contenu des pages</h2>
<ol>
<?php

  while ($ligne = pg_fetch_array($result)) {
    echo '<li>
<a href="'.$ligne['version'].'/'.$ligne['url'].'">Manuel PostgreSQL '.$ligne['version'].', '.$ligne['titre'].'</a>
 ['.$ligne['score'].' %]<br/>
...'.$ligne['resume'].'...<br/>&nbsp;<br/>
</li>';
  }

} else {
  echo '<b>Aucun résultat trouvé !</b><br/>';

/*********************************
 * RECHERCHE DES MOTS SIMILAIRES *
 *********************************/

  $query = "SELECT mot, to_char(similarity(mot, '".pg_escape_string($searchstring)."')*100,'999.99') AS sml
FROM mots
WHERE mot % '".pg_escape_string($searchstring)."'
ORDER BY sml DESC, mot";

  $result = pg_query($pgconn, $query);

  if (pg_num_rows($result) > 0) {

    echo "<p>Peut-être cherchez-vous :</p><ul>";
    while ($ligne = pg_fetch_array($result)) {
      echo '<li>
  <a href="search.php?v='.$filtreversion.'&q='.$ligne['mot'].'">'.$ligne['mot'].'</a> ['.$ligne['sml'].' %]
</li>';
    }
    echo "</ul>";
}

}

  $result = pg_query($pgconn, $query);

pg_close($pgconn);
?>
</ol>
