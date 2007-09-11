<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:doc="http://nwalsh.com/xsl/documentation/1.0"
                xmlns:exsl="http://exslt.org/common"
                xmlns:set="http://exslt.org/sets"
		            version="1.0"
                exclude-result-prefixes="doc exsl set">

<!-- ********************************************************************
     $Id: htmlhelp.xsl,v 1.25 2002/06/12 13:21:54 kosek Exp $
     ******************************************************************** 

     This file is used by htmlhelp.xsl if you want to generate source
     files for HTML Help.  It is based on the XSL DocBook Stylesheet
     distribution (especially on JavaHelp code) from Norman Walsh.

     ******************************************************************** -->
  <!--
  <xsl:import href="/opt/docbook-xsl/htmlhelp/htmlhelp.xsl"/>
  -->
  <xsl:import href="C:/Docbook/xsl/htmlhelp/htmlhelp.xsl"/>

  <xsl:param name="html.stylesheet" select="'pg-chm.css'"/>
  <xsl:param name="htmlhelp.chm" select="'pg824.chm'"/>
  <xsl:param name="base.dir" select="'./chm/'"/> 
  <xsl:template name="user.header.navigation">
  </xsl:template>

</xsl:stylesheet>
