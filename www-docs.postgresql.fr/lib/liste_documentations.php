<?php
  if ($pgconn)
  {
    $query = "SELECT version FROM versions WHERE obsolete=";
    $query .= $obsoletes ? "true" : "false";
    $query .= " ORDER BY ordre";
    $result = pg_query($pgconn, $query);

    $versions = array();
    while ($ligne = pg_fetch_array($result))
    {
      array_push($versions, $ligne['version']);
    }
  }
  elseif ($obsoletes)
  {
    $versions = array('9.4', '9.3', '9.2', '9.1', '9.0', '8.4', '8.3', '8.2', '8.1', '8.0', '7.4');
  }
  else
  {
    $versions = array('13', '12', '11', '10', '9.6', '9.5');
  }

  foreach ($versions as &$version)
  {
    echo "
        <div id=\"pg${version}\">
          <h2>Version ${version}</h2>
          <div class=\"listes\">
            <ul>
              <li>Manuel au format HTML&nbsp;:
                <a href=\"${version}/\">Consultation en ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick=\"pageTracker._trackPageview('/pg${version}.zip');\"
                href=\"http://docs.postgresql.fr/${version}/pg${version}.zip\">ZIP</a> ou
                <a onclick=\"pageTracker._trackPageview('/pg${version}.tar.gz');\"
                href=\"http://docs.postgresql.fr/${version}/pg${version}.tar.gz\">TAR.GZ</a>
              </li>
              <li>Manuel au format <a onclick=\"pageTracker._trackPageview('/pg${version}.pdf');\"
                href=\"http://docs.postgresql.fr/${version}/pg${version}.pdf\">PDF</a>
              </li>
              <li>Document d'installation au format <a
                onclick=\"pageTracker._trackPageview('/INSTALL${version}.html');\"
                href=\"http://docs.postgresql.fr/${version}/INSTALL.html\">HTML</a> et
                <a onclick=\"pageTracker._trackPageview('/INSTALL${version}.txt');\"
                href=\"http://docs.postgresql.fr/${version}/INSTALL.txt\">texte</a>
              </li>
              <li><a onclick=\"pageTracker._trackPageview('/pg${version}.man.tar.gz');\"
                href=\"http://docs.postgresql.fr/${version}/pg${version}.man.tar.gz\">Pages
	        man</a>
              </li>
            </ul>
          </div>
	</div>
";
  }
?>
