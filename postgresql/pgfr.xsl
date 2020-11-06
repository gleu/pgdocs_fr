<?xml version="1.0" encoding="iso8859-15"?>
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

  <body id="docContent">
    <xsl:call-template name="body.attributes"/>
	<xsl:if test="$website.stylesheet = 1">
    <div id="top">
      <div id="pgHeader">
        <span id="pgHeaderLogoLeft">
          <a href="/" title="PostgreSQL">
		  <img src="hdr_left.png"
		       width="230"
			   height="80"
			   alt="PostgreSQL" />
		  </a>
        </span>
        <span id="pgHeaderLogoRight">
          <a href="/" title="La base de données la plus sophistiquée au monde.">
		  <img src="hdr_right.png"
		       width="210"
			   height="80"
			   alt="La base de données la plus sophistiquée au monde." />
		  </a>
        </span>
      </div>
    </div>
    <div class="pgTopNav">
      <div class="pgTopNavLeft"> 
        <img src="nav_lft.png" width="7" height="23" alt="" />
      </div>
      <div class="pgTopNavRight">
        <img src="nav_rgt.png" width="7" height="23" alt="" />
      </div>
      <ul class="pgTopNavList">
        <li>
		  <a href="https://www.postgresql.fr/" title="Accueil">Accueil</a>
		</li>
        <li>
		  <a href="https://blog.postgresql.fr/" title="Lire les actualités">Actualités</a>
		</li>
        <li>
		  <a href="https://docs.postgresql.fr/" title="Lire la documentation officielle">Documentation</a>
		</li>
        <li>
		  <a href="https://forums.postgresql.fr/" title="Pour poser des questions">Forums</a>
		</li>
        <li>
		  <a href="https://asso.postgresql.fr/" title="La vie de l'association">Association</a>
		</li>
        <li>
		  <a href="https://www.postgresql.fr/devel:accueil"
		     title="Informations pour les développeurs/traducteurs">Développeurs</a>
		</li>
        <li>
		  <a href="https://planete.postgresql.fr" title="La planète francophone sur PostgreSQL">Planète</a>
		</li>
        <li>
		  <a href="https://support.postgresql.fr" title="Support sur PostgreSQL">Support</a>
		</li>
      </ul>
    </div>
	</xsl:if>
    <div class="pgContent">
	  <xsl:call-template name="breadcrumbs"/>
      <xsl:call-template name="footer.navigation">
        <xsl:with-param name="prev" select="$prev"/>
        <xsl:with-param name="next" select="$next"/>
        <xsl:with-param name="nav.context" select="$nav.context"/>
      </xsl:call-template>
      <xsl:copy-of select="$content"/>
    </div>
  </body>
</html>
    <xsl:value-of select="$chunk.append"/>
  </xsl:template>

  <xsl:template name="breadcrumbs">
    <xsl:param name="this.node" select="."/>
    <xsl:for-each select="$this.node/ancestor::*">
      <span class="breadcrumb-link">
        <a>
          <xsl:attribute name="href">
            <xsl:call-template name="href.target">
              <xsl:with-param name="object" select="."/>
              <xsl:with-param name="context" select="$this.node"/>
            </xsl:call-template>
          </xsl:attribute>
          <xsl:apply-templates select="." mode="title.markup"/>
        </a>
      </span>
      <xsl:text> » </xsl:text>
    </xsl:for-each>
    <!-- And display the current node, but not as a link -->
    <span class="breadcrumb-node">
      <xsl:apply-templates select="$this.node" mode="title.markup"/>
    </span>
  </xsl:template>

</xsl:stylesheet>
