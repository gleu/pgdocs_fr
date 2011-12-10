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
    <h1>Documentation</h1>
    <p>Pour rapporter tout probl&egrave;me dans la traduction, merci d'envoyer un
    mail &agrave; <a href="mailto:guillaume chez lelarge point info">Guillaume
    Lelarge</a>.</p>
    <p>Certaines vieilles versions, non maintenues, sont disponibles sur
    l'<a href="index_obsoletes.php">index des version obsolètes</a>.</p>
    <div id="pg91">
      <h2>Documentation PostgreSQL, version 9.1</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="9.1/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg91.zip');"
                href="http://docs.postgresql.fr/9.1/pg91.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg91.tar.gz');"
                href="http://docs.postgresql.fr/9.1/pg91.tar.gz">TAR.GZ</a>
            </li>
            <!--
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg91.chm');"
              href="http://docs.postgresql.fr/9.1/pg91.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg91.pdf');"
              href="http://docs.postgresql.fr/9.1/pg91.pdf">PDF</a>
            </li>
            -->
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL91.html');"
              href="http://docs.postgresql.fr/9.1/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL91.txt');"
              href="http://docs.postgresql.fr/9.1/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg91.man.tar.gz');"
              href="http://docs.postgresql.fr/9.1/pg91.man.tar.gz">Pages
              man</a></li>
        </ul>
      </div>
    </div>
    <div id="pg90">
      <h2>Documentation PostgreSQL, version 9.0</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="9.0/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg90.zip');"
                href="http://docs.postgresql.fr/9.0/pg90.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg90.tar.gz');"
                href="http://docs.postgresql.fr/9.0/pg90.tar.gz">TAR.GZ</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg90.chm');"
              href="http://docs.postgresql.fr/9.0/pg90.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg90.pdf');"
              href="http://docs.postgresql.fr/9.0/pg90.pdf">PDF</a>
            </li>
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL90.html');"
              href="http://docs.postgresql.fr/9.0/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL90.txt');"
              href="http://docs.postgresql.fr/9.0/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg90.man.tar.gz');"
              href="http://docs.postgresql.fr/9.0/pg90.man.tar.gz">Pages
              man</a></li>
        </ul>
      </div>
    </div>
    <div id="pg84">
      <h2>Documentation PostgreSQL, version 8.4</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="8.4/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg84.zip');"
                href="http://docs.postgresql.fr/8.4/pg84.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg84.tar.gz');"
                href="http://docs.postgresql.fr/8.4/pg84.tar.gz">TAR.GZ</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg84.chm');"
              href="http://docs.postgresql.fr/8.4/pg84.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg84.pdf');"
              href="http://docs.postgresql.fr/8.4/pg84.pdf">PDF</a>
            </li>
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL84.html');"
              href="http://docs.postgresql.fr/8.4/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL84.txt');"
              href="http://docs.postgresql.fr/8.4/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg84.man.tar.gz');"
              href="http://docs.postgresql.fr/8.4/pg84.man.tar.gz">Pages
              man</a></li>
        </ul>
      </div>
    </div>
    <div id="pg83">
      <h2>Documentation PostgreSQL, version 8.3</h2>
      <div class="listes">
        <ul>
            <li>Manuel au format HTML&nbsp;:
                <a href="8.3/">Consultation en
                ligne</a>,
                t&eacute;l&eacute;chargement en
                <a onclick="pageTracker._trackPageview('/pg83.zip');"
                href="http://docs.postgresql.fr/8.3/pg83.zip">ZIP</a> ou
                <a onclick="pageTracker._trackPageview('/pg83.tar.gz');"
                href="http://docs.postgresql.fr/8.3/pg83.tar.gz">TAR.GZ</a>
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg83.chm');"
              href="http://docs.postgresql.fr/8.3/pg83.chm">CHM</a>
              (syst&egrave;me d'aide Windows)
            </li>
            <li>Manuel au format <a onclick="pageTracker._trackPageview('/pg833.pdf');"
              href="http://docs.postgresql.fr/8.3/pg83.pdf">PDF</a>
            </li>
            <li>Document d'installation au format <a
              onclick="pageTracker._trackPageview('/INSTALL83.html');"
              href="http://docs.postgresql.fr/8.3/INSTALL.html">HTML</a> et
              <a onclick="pageTracker._trackPageview('/INSTALL83.txt');"
              href="http://docs.postgresql.fr/8.3/INSTALL.txt">texte</a>
            </li>
            <li><a onclick="pageTracker._trackPageview('/pg834.man.tar.gz');"
              href="http://docs.postgresql.fr/8.3/pg83.man.tar.gz">Pages
              man</a></li>
	    <!--
            <li><a onclick="pageTracker._trackPageview('/plugin833.firefox');"
              href="javascript:addEngine('pgfr-docs83','png','Computer',0)">
              Plugin de recherche</a> pour Firefox et Mozilla</li>
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
