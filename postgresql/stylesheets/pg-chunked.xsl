<?xml version='1.0' encoding='UTF-8'?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/1999/xhtml"
                version="1.0">

  <xsl:import href="pg-chunked2.xsl"/>
  <xsl:import href="/opt/docbook-xsl/xhtml/chunk-common.xsl"/>
  <xsl:import href="/opt/docbook-xsl/xhtml/manifest.xsl"/>


    <!--  From the original chunk.xsl file:

    Why is chunk-code now xsl:included?

    Suppose you want to customize *both* the chunking algorithm used *and* the
    presentation of some elements that may be chunks. In order to do that, you
    must get the order of imports "just right". The answer is to make your own
    copy of this file, where you replace the initial import of "docbook.xsl"
    with an import of your own base.xsl (that does its own import of docbook.xsl).

    Put the templates for changing the presentation of elements in your base.xsl.

    Put the templates that control chunking after the include of chunk-code.xsl.

    Voila! (Man I hope we can do this better in XSLT 2.0)  -->

  <xsl:import href="/opt/docbook-xsl/xhtml/profile-chunk-code.xsl"/>

    <!-- Including our others customized chunks templates -->
  <xsl:include href="xhtml/pg-legalnotice.xsl"/>
  <xsl:include href="xhtml/pg-navigational.xsl"/>
  <xsl:include href="xhtml/pg-function.xsl"/>

<xsl:param name="refentry.generate.name" select="0"></xsl:param>
<xsl:param name="refentry.generate.title" select="1"></xsl:param>

</xsl:stylesheet>
