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
        <h1>Résultats de la recherche</h1>

        <?php include "lib/resultats_recherche.php" ?>

      </div>
