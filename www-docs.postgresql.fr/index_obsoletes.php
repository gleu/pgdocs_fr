<?
$recherche = $_REQUEST['q'];
$filtreversion = $_REQUEST['v'];

## No backslashes allowed
$recherche = preg_replace('/\\\/', '', $recherche);
## Collapse parens into nearby words:
$recherche = preg_replace('/\s*\(\s*/', ' (', $recherche);
$recherche = preg_replace('/\s*\)\s*/', ') ', $recherche);
## Treat colons as word separators:
$recherche = preg_replace('/:/', ' ', $recherche);

$recherche_value = strlen($recherche)>0 ? $recherche : 'Rechercher';

$pgconn = @pg_connect("host=localhost dbname=docspgfr user=docspgfr") or die('Connexion impossible');

$query = "SET client_encoding TO utf8;";
$result = pg_query($pgconn, $query);

$version['803'] = '8.3';
$version['804'] = '8.4';
$version['900'] = '9.0';
$version['901'] = '9.1';
$version['902'] = '9.2';
?>
<!DOCTYPE html SYSTEM "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Documentation PostgreSQL en français</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" href="css/fixed.css" type="text/css" title="Test" />
    <link rel="search" type="application/opensearchdescription+xml" title="PgFr Docs 8.3.X" href="http://docs.postgresql.fr/addon/pgfr-docs83-ff.xml" />
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
      <a href="/" title="PostgreSQL"><img src="http://docs.postgresql.fr/theme/img/hdr_left.png" width="230" height="80" alt="PostgreSQL" /></a>
    </span>
    <span id="pgHeaderLogoRight">
      <a href="/" title="La base de donnees la plus sophistiquee au monde."><img src="http://docs.postgresql.fr/theme/img/hdr_right.png" width="210" height="80" alt="La base de donnees la plus sophistiquee au monde." /></a>
    </span>
  </div>
</div>

<div class="pgTopNav">
  <div class="pgTopNavLeft"> 
    <img src="http://docs.postgresql.fr/theme/img/nav_lft.png" width="7" height="23" alt="" />
  </div>
  <div class="pgTopNavRight">
    <img src="http://docs.postgresql.fr/theme/img/nav_rgt.png" width="7" height="23" alt="" />
  </div>
  <ul class="pgTopNavList">
    <li><a href="http://www.postgresql.fr/" title="Accueil">Accueil</a></li>
    <li><a href="http://blog.postgresql.fr/" title="Lire les actualités">Actualités</a></li>
    <li><a href="http://docs.postgresql.fr/" title="Lire la documentation officielle">Documentation</a></li>
    <li><a href="http://forums.postgresql.fr/" title="Pour poser des questions">Forums</a></li>
    <li><a href="http://asso.postgresql.fr/" title="La vie de l'association">Association</a></li>
    <li><a href="http://www.postgresql.fr/devel:accueil" title="Informations pour les développeurs/traducteurs">Développeurs</a></li>
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
      <input id="q" name="q" type="text" size="16" maxlength="255" onfocus="if( this.value=='Rechercher' ) this.value='';" value="<?= strlen($_REQUEST['q'])>0 ? $_REQUEST['q'] : 'Rechercher' ?>" accesskey="s" />
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
  <input id="submit" name="submit" type="submit" value="Rechercher" />
  </div>
    </form>
  </div>
  </div>

  <div id="pgContentWrap">
  <div id="pgDownloadsWrap">
  <div id="content">
    <div id="docs">
    <h1>Documentation des versions obsolètes</h1>
    <p>Ces versions ne sont plus mises à jour par le projet PostgreSQL.</p>
    <div id="pg82">
      <h2>Documentation PostgreSQL, version 8.2</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="8.2/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg821.zip');"
                href="http://docs.postgresql.fr/8.2/pg82.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg82.tar.gz');"
                href="http://docs.postgresql.fr/8.2/pg82.tar.gz">TAR.GZ</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg82.chm');"
              href="http://docs.postgresql.fr/8.2/pg82.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg82.pdf');"
              href="http://docs.postgresql.fr/8.2/pg82.pdf">PDF</a>
            </li>
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL82.html');"
              href="http://docs.postgresql.fr/8.2/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL82.txt');"
              href="http://docs.postgresql.fr/8.2/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg82.man.tar.gz');"
              href="http://docs.postgresql.fr/8.2/pg82.man.tar.gz">Pages
              man</a></li>
	    <!--
            <li><a onclick="pageTracker._trackPageview('/plugin82.firefox');"
              href="javascript:addEngine('pgfr-docs82','png','Computer',0)">
              Plugin de recherche</a> pour Firefox et Mozilla</li>
	    -->
        </ul>
      </div>
    </div>
    <div id="pg81">
      <h2>Documentation PostgreSQL, version 8.1</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="8.1/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg81.zip');"
                href="http://docs.postgresql.fr/8.1/pg81.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg81.tar.gz');"
                href="http://docs.postgresql.fr/8.1/pg81.tar.gz">TAR.GZ</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg81.chm');"
                href="http://docs.postgresql.fr/8.1/pg81.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg81.pdf');"
              href="http://docs.postgresql.fr/8.1/pg81.pdf">PDF</a>
            </li>
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL81.html');"
              href="http://docs.postgresql.fr/8.1/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL81.txt');"
              href="http://docs.postgresql.fr/8.1/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg81.man.tar.gz');"
              href="http://docs.postgresql.fr/8.1/pg81.man.tar.gz">Pages
              man</a></li>
            <li><a onclick="pageTracker._trackPageview('/annotated81.html');"
              href="http://docs.postgresql.fr/annotated_postgresql_conf_81.html">Fichier
              postgresql.conf annot&eacute;</a>
            </li>
	    <!--
            <li><a onclick="pageTracker._trackPageview('/plugin81.firefox');"
              href="javascript:addEngine('pgfr-docs81','png','Computer',0)">
              Plugin de recherche</a> pour Firefox et Mozilla</li>
	    -->
        </ul>
      </div>
    </div>
    <div id="pg80">
      <h2>Documentation PostgreSQL, version 8.0</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="http://docs.postgresql.fr/8.0/">Consultation en
                ligne</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg80.pdf');"
              href="http://docs.postgresql.fr/8.0/pg80.pdf">PDF</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/annotated80.html');"
              href="http://docs.postgresql.fr/annotated_postgresql_conf_80.html">Fichier
              postgresql.conf annot&eacute;</a>
            </li>
	    <!--
            <li><a onclick="pageTracker._trackPageview('/plugin80.firefox');"
              href="javascript:addEngine('pgfr-docs80','png','Computer',0)">
              Plugin de recherche</a> pour Firefox et Mozilla</li>
	    -->
        </ul>
      </div>
    </div>
    <div id="pg74">
      <h2>Documentation PostgreSQL, version 7.4</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="http://docs.postgresql.fr/7.4/">Consultation en
                ligne</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg74.pdf');"
              href="http://docs.postgresql.fr/7.4/pg74.pdf">PDF</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/annotated74.html');"
              href="http://docs.postgresql.fr/annotated_postgresql_conf_74.html">Fichier
              postgresql.conf annot&eacute;</a>
            </li>
	    <!--
            <li><a onclick="pageTracker._trackPageview('/plugin74.firefox');"
              href="javascript:addEngine('pgfr-docs74','png','Computer',0)">
              Plugin de recherche</a> pour Firefox et Mozilla
            </li>
	    -->
        </ul>
      </div>
    </div>
</div>
</div>

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
