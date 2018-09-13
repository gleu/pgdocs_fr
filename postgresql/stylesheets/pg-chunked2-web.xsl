<?xml version='1.0' encoding='UTF-8'?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml"
                version="1.0">

    <!-- We use XHTML -->
  <xsl:import href="/opt/docbook-xsl/xhtml/docbook.xsl"/>
  <xsl:param name="chunker.output.encoding" select="'UTF-8'"/>


    <!-- Including our others customized elements templates -->
  <xsl:include href="xhtml/pg-admon.xsl"/>
  <xsl:include href="xhtml/pg-sections.xsl"/>
  <xsl:include href="xhtml/pg-mixed.xsl"/>
  <xsl:include href="xhtml/pg-titles.xsl"/>
  <xsl:include href="xhtml/pg-toc.xsl"/>
  <!--<xsl:include href="xhtml/pg-xref.xsl"/>-->

    <!-- The CSS Stylesheet -->
  <xsl:param name="html.stylesheet" select="'../stylesheets/fixed.css'"/>
  <xsl:template name='user.head.content'>
     <link rel="stylesheet" href="../stylesheets/pg-print.css" type="text/css" media="print"/>
     <link rel="search" type="application/opensearchdescription+xml" title="PgFr Docs 9.0" href="http://docs.postgresql.fr/addon/pgfr-docs90-ff.xml" />
     <!--[if lt IE 7.]>
       <script defer type="text/javascript" src="stylesheets/pngfix.js"></script>
     <![endif]-->     
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
      <xsl:text> &gt; </xsl:text>
    </xsl:for-each>
    <!-- And display the current node, but not as a link -->
    <span class="breadcrumb-node">
      <xsl:apply-templates select="$this.node" mode="title.markup"/>
    </span>
  </xsl:template>
  
  <xsl:template name="user.header.content">
    <xsl:call-template name="breadcrumbs"/>
  </xsl:template>

    <!-- Dropping some unwanted style attributes -->
  <xsl:param name="ulink.target" select="''"></xsl:param>
  <xsl:param name="css.decoration" select="0"></xsl:param>

    <!-- No XML declaration -->
  <xsl:param name="chunker.output.omit-xml-declaration" select="'yes'"/>

  <!-- Custom french translation -->
  <xsl:param name="l10n.xml" select="document('../stylesheets/pgfrl10n.xml')"/>
</xsl:stylesheet>
