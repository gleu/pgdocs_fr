<?xml version='1.0' encoding='ISO-8859-1'?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml"
                version="1.0">

    <!-- General settings -->
  <xsl:param name="generate.toc">
    appendix  nop
    book      toc,title,figure,table,example,equation
    chapter   toc
    part      toc
    preface   nop
    qandadiv  nop
    qandaset  nop
    reference toc
    sect1     nop
    sect2     nop
    sect3     nop
    sect4     nop
    sect5     nop
    section   nop
    set       nop
  </xsl:param>

  <xsl:param name="toc.section.depth">1</xsl:param>

  <xsl:param name="toc.max.depth">2</xsl:param>

</xsl:stylesheet>
