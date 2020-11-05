<?php include "lib/begin.php"; ?>
<!DOCTYPE html SYSTEM "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Documentation PostgreSQL en français</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/docspgfr.css" />
  </head>
  <body>
<!-- HEADER -->
    <div id="pgHeader">
      <span id="pgHeaderLogoLeft">
	<a href="/" title="PostgreSQL">
        <img src="img/hdr_left.png" width="230" height="80" alt="PostgreSQL" />
        </a>
      </span>
      <span id="pgHeaderLogoRight">
	<a href="/" title="Le SGBD Open Source de référence">
        <img src="img/hdr_right.png" width="210" height="80" alt="Le SGBD Open Source de référence" />
        </a>
      </span>
    </div>

<!-- NAVIGATION -->
    <div class="pgTopNav">
      <div class="pgTopNavLeft"> 
        <img src="img/nav_left.png" width="7" height="23" alt="" />
      </div>
      <div class="pgTopNavRight">
        <img src="img/nav_right.png" width="7" height="23" alt="" />
      </div>
      <ul class="pgTopNavList">
        <li><a href="https://www.postgresql.fr/" title="Accueil">Accueil</a></li>
        <li><a href="https://blog.postgresql.fr/" title="Lire les actualités">Actualités</a></li>
        <li><a href="https://docs.postgresql.fr/" title="Lire la documentation officielle">Documentation</a></li>
        <li><a href="https://forums.postgresql.fr/" title="Pour poser des questions">Forums</a></li>
        <li><a href="https://asso.postgresql.fr/" title="La vie de l'association">Association</a></li>
        <li><a href="https://www.postgresql.fr/devel:accueil" title="Informations pour les développeurs/traducteurs">Développeurs</a></li>
        <li><a href="https://planete.postgresql.fr" title="La planète francophone sur PostgreSQL">Planète</a></li>
        <li><a href="https://support.postgresql.fr" title="Support sur PostgreSQL">Support</a></li>
      </ul>
    </div>

<!-- CONTENT -->
    <div id="pgContent">

<!--   SEARCHBAR -->
      <div id="pgSideWrap">
        <div id="pgSideNav">
          <form method="post" action="search.php">
            <div>
	      <h2><label for="q">Rechercher</label></h2>
              <?php include "lib/formulaire_recherche.php" ?>
            </div>
          </form>
        </div>
      </div>

<!--   CONTENT -->
      <div id="pgContentWrap">
        <h1>Documentation</h1>

        <p>Pour rapporter tout probl&egrave;me dans la traduction, merci d'envoyer un
        mail &agrave; <a href="mailto:guillaume chez lelarge point info">Guillaume
        Lelarge</a>.</p>

        <p>Certaines vieilles versions, non maintenues, sont disponibles sur
        l'<a href="index_obsoletes.php">index des version obsolètes</a>.</p>

        <?php include "lib/liste_documentations.php" ?>
      </div>
    </div>
  </body>
</html>
