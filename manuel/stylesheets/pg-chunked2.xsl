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
  <xsl:param name="html.stylesheet" select="'../stylesheets/pg.css'"/>
  <xsl:template name='user.head.content'>
     <link rel="stylesheet" href="../stylesheets/pg-print.css" type="text/css" media="print"/>
     <link rel="search" type="application/opensearchdescription+xml" title="PgFr Docs 8.2" href="http://docs.postgresql.fr/addon/pgfr-docs82-ff.xml" />
     <!--[if lt IE 7.]>
       <script defer type="text/javascript" src="stylesheets/pngfix.js"></script>
     <![endif]-->     
  </xsl:template>

    <!-- Dropping some unwanted style attributes -->
  <xsl:param name="ulink.target" select="''"></xsl:param>
  <xsl:param name="css.decoration" select="0"></xsl:param>

    <!-- No XML declaration -->
  <xsl:param name="chunker.output.omit-xml-declaration" select="'yes'"/>

</xsl:stylesheet>
