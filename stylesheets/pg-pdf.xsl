<?xml version='1.0' encoding='ISO-8859-1'?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:fo="http://www.w3.org/1999/XSL/Format"
                version="1.0">
    <!-- We use FO and FOP as the processor -->
    <!-- Faire un lien /opt/docbook-xsl/ vers votre version -->
  <xsl:import href="/opt/docbook-xsl/fo/docbook.xsl"/>
  <xsl:param name="fop.extensions" select="1"/>
  <xsl:param name="draft.mode" select="'no'"/>

    <!-- Including our others customized templates -->
  <xsl:include href="pdf/pg-index.xsl"/>
  <xsl:include href="pdf/pg-pagesetup.xsl"/>
  <xsl:include href="pdf/pg-sections.xsl"/>
  <xsl:include href="pdf/pg-admon.xsl"/>
  <xsl:include href="pdf/pg-mixed.xsl"/>
  <xsl:include href="pdf/pg-xref.xsl"/>

    <!-- Standart paper size -->
  <xsl:param name="paper.type" select="'A4'"/>

    <!-- Including callout images -->
  <xsl:param name="callout.graphics.path"
        select="'/opt/docbook-xsl/images/callouts/'"/>

    <!-- Wrapping -->
  <xsl:attribute-set name="monospace.verbatim.properties">
    <xsl:attribute name="wrap-option">wrap</xsl:attribute>
  </xsl:attribute-set>

    <!-- Printing Style -->
  <xsl:param name="double.sided" select="0"/>
  <xsl:param name="hyphenate">true</xsl:param>
  <xsl:param name="alignment">justify</xsl:param>

    <!-- Font size -->
  <xsl:param name="body.font.master">8</xsl:param>
  <xsl:param name="body.font.size">10pt</xsl:param>

    <!-- TOC stuff -->
  <xsl:param name="generate.toc">
    book      toc
    part      nop
  </xsl:param>
  <xsl:param name="toc.section.depth">1</xsl:param>
  <xsl:param name="generate.section.toc.level" select="-1"></xsl:param>
  <xsl:param name="toc.indent.width" select="18"></xsl:param>

    <!-- Page number in Xref ?-->
  <xsl:param name="insert.xref.page.number">no</xsl:param>

    <!-- Prevent duplicate e-mails in the Acknowledgments pages-->
  <xsl:param name="ulink.show" select="0"/>

</xsl:stylesheet>
