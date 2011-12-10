<?xml version="1.0" encoding="iso8859-15"?>
<!--This file was created automatically by html2xhtml-->
<!--from the HTML stylesheets.-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:exsl="http://exslt.org/common" xmlns:cf="http://docbook.sourceforge.net/xmlns/chunkfast/1.0" xmlns:ng="http://docbook.org/docbook-ng" xmlns:db="http://docbook.org/ns/docbook" xmlns="http://www.w3.org/1999/xhtml" version="1.0" exclude-result-prefixes="exsl cf ng db">

<xsl:template name="chunk-element-content">
  <xsl:param name="prev"/>
  <xsl:param name="next"/>
  <xsl:param name="nav.context"/>
  <xsl:param name="content">
    <xsl:apply-imports/>
  </xsl:param>

  <xsl:call-template name="user.preroot"/>

  <html>
    <xsl:call-template name="html.head">
      <xsl:with-param name="prev" select="$prev"/>
      <xsl:with-param name="next" select="$next"/>
    </xsl:call-template>

    <body>
      <xsl:call-template name="body.attributes"/>
<div id="top">
  <div id="pgHeader">
    <span id="pgHeaderLogoLeft">
      <a href="/" title="PostgreSQL"><img src="http://docs.postgresql.fr/theme/img/hdr_left.png" width="230" height="80" alt="PostgreSQL" /></a>
    </span>
    <span id="pgHeaderLogoRight">
      <a href="/" title="La base de données la plus sophistiquée au monde."><img src="http://docs.postgresql.fr/theme/img/hdr_right.png" width="210" height="80" alt="La base de données la plus sophistiquée au monde." /></a>
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
   <li> <a href="http://www.postgresql.fr/" title="Accueil">Accueil</a> </li>
   <li> <a href="http://blog.postgresql.fr/" title="Lire les actualités">Actualités</a> </li>
   <li> <a href="http://docs.postgresql.fr/" title="Lire la documentation officielle">Documentation</a> </li>
   <li> <a href="http://forums.postgresql.fr/" title="Pour poser des questions">Forums</a> </li>
   <li> <a href="http://asso.postgresql.fr/" title="La vie de l'association">Association</a> </li>
   <li> <a href="http://www.postgresql.fr/devel:accueil" title="Informations pour les développeurs/traducteurs">Développeurs</a> </li>
   <li> <a href="http://planete.postgresql.fr" title="La planète francophone sur PostgreSQL">Planète</a> </li>
   <li> <a href="http://support.postgresql.fr" title="Support sur PostgreSQL">Support</a> </li>
  </ul>
</div>
<div id="pgContent">
  <div id="pgSideWrap">
    <div id="pgSideNav">
      <form method="post" action="http://docs.postgresql.fr/search.php">
        <div>
          <h2><label for="q">Rechercher</label></h2>
          <input type='hidden' id='v' name='v' value='901' />
          <input id="q" name="q" type="text" size="10" maxlength="255" onfocus="if( this.value=='Rechercher' ) this.value='';" value="Rechercher" accesskey="r" /><input id="submit" name="submit" type="submit" value="Rechercher" />
        </div>
      </form>
    </div>
    <div id="pgSideNav">
      <div>
<script>
function jump_to_english() {
var fr_url = location.href;
var fr_pattern = "http://docs.postgresql(.fr|fr.org)/([7-9].[0-4])";
var en_pattern = "http://www.postgresql.org/docs/$2/static";
var reg=new RegExp(fr_pattern, "");
window.location = fr_url.replace(reg,en_pattern) ;
}
</script>
        <a href="javascript:jump_to_english()">Version anglaise</a>
      </div>
    </div>
  </div>

  <div id="pgContentWrap">
    <div id="pgContentNav">


      <xsl:call-template name="footer.navigation">
        <xsl:with-param name="prev" select="$prev"/>
        <xsl:with-param name="next" select="$next"/>
        <xsl:with-param name="nav.context" select="$nav.context"/>
      </xsl:call-template>

      <xsl:copy-of select="$content"/>
    </div>
  </div>
</div>
    </body>
  </html>
  <xsl:value-of select="$chunk.append"/>
</xsl:template>

</xsl:stylesheet>
