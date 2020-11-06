<div class="pgTopNav">
<div class="pgTopNavLeft"><img src="nav_lft.png" width="7" height=
"23" alt="" /></div>
<div class="pgTopNavRight"><img src="nav_rgt.png" width="7" height=
"23" alt="" /></div>
<ul class="pgTopNavList">
<?php
$DSN = "host=localhost dbname=docspgfr user=docspgfr";

$pgconn = pg_connect("$DSN");

if ($pgconn)
{
  $query = "SET client_encoding TO utf8;";
  $result = pg_query($pgconn, $query);
}
  $url = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);

  if (!$pgconn)
  {
?>
             <li>Bandeau de versions désactivée car pas de connexion à la base !</li>
<?php
  }
  else
  {
    $query = "select p.version, p.url
      from pages p
      join versions v on p.version=v.version
      where p.url='$url' and not obsolete
      order by v.ordre";

    $result = pg_query($pgconn, $query);
    if (pg_num_rows($result) > 0)
    {
      echo "<li>Versions supportées ";
      while ($ligne = pg_fetch_array($result))
      {
        echo '<a href="/'.$ligne['version'].'/'.$ligne['url'].'">'.$ligne['version'].'</a>&nbsp;&nbsp;';
      }
      echo "</li>";
    }
    pg_free_result($result);

    $query = "select p.version, p.url
      from pages p
      join versions v on p.version=v.version
      where p.url='$url' and obsolete
      order by v.ordre";

    $result = pg_query($pgconn, $query);
    if (pg_num_rows($result) > 0)
    {
      echo "<li>Versions obsolètes ";
      while ($ligne = pg_fetch_array($result))
      {
        echo '<a href="/'.$ligne['version'].'/'.$ligne['url'].'">'.$ligne['version'].'</a>&nbsp;&nbsp;';
      }
      echo "</li>";
    }
    pg_free_result($result);
  }

  echo "<li>Version originale ";
  echo '<a href="https://www.postgresql.org/docs'.$_SERVER['PHP_SELF'].'">EN</a>&nbsp;&nbsp;';
?>
</ul>
</div>
