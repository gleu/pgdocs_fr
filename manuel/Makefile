VERSION := $(shell grep -v major version.xml | cut -c19-23)
VER := $(shell grep -v major version.xml | cut -c19-23 | sed 's/\.//g')

BASEDIR := $(HOME)/pgsql-$(VERSION)-fr
HTM_OUTPUT := pgsql-$(VERSION)-fr
TGZ_OUTPUT := pg$(VER).tar.gz
ZIP_OUTPUT := pg$(VER).zip
PDF_OUTPUT := pg$(VER).pdf
FO_OUTPUT := pg$(VER).fo
QUICKPDF_OUTPUT := quickpg$(VER).pdf
MAN_OUTPUT := pg$(VER).man.tar.gz
CHM_OUTPUT := pg$(VER).chm.tar.gz
NOCHUNKS_OUTPUT := pg$(VER).html

CHUNK_QUIET=0
XSLROOTDIR=/opt/docbook-xsl
VPATH = $(BASEDIR):$(BASEDIR)/ref
src = *.xml ref/*.xml

all: html pdf htmlhelp manpages INSTALL.html INSTALL.txt

html: index.html
index.html: $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet -stringparam profile.condition html \
		-stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		-stringparam chunk.quietly $(CHUNK_QUIET) \
		-stringparam use.id.as.filename "yes" \
		-stringparam base.dir $(BASEDIR)/$(HTM_OUTPUT)/ \
		stylesheets/pg-chunked.xsl postgres.xml

	[ -d $(BASEDIR)/$(HTM_OUTPUT)/stylesheets ] || mkdir $(BASEDIR)/$(HTM_OUTPUT)/stylesheets
	cp stylesheets/*.css $(BASEDIR)/$(HTM_OUTPUT)/stylesheets

	[ -d $(BASEDIR)/$(HTM_OUTPUT)/images ] || mkdir $(BASEDIR)/$(HTM_OUTPUT)/images
	cp img/*.png $(BASEDIR)/$(HTM_OUTPUT)/images
	cd $(BASEDIR)/$(HTM_OUTPUT)/; sed -i -e "s@../stylesheets@stylesheets@g" *.html
	cd $(BASEDIR)/$(HTM_OUTPUT)/; sed -i -e "s@../images@images@g" *.html

	for filename in `find $(BASEDIR)/$(HTM_OUTPUT) -name "*.html"`; do \
	  tidy -config tidy.conf $$filename; \
	  true; \
	  sed -i -e "s@text/html@application/xhtml+xml@g" $$filename; \
	done;

	cd $(BASEDIR)/$(HTM_OUTPUT)/; sed -i -e "s@</body>@</body><script src='http://www.google-analytics.com/urchin.js' type='text/javascript'></script><script type='text/javascript'> _uacct = 'UA-140513-1'; urchinTracker(); </script>@g" *.html

	cd $(BASEDIR); tar cfz $(TGZ_OUTPUT) $(HTM_OUTPUT)
	cd $(BASEDIR); zip -r $(ZIP_OUTPUT) $(HTM_OUTPUT)
	mv $(BASEDIR)/$(TGZ_OUTPUT) $(BASEDIR)/$(ZIP_OUTPUT) $(BASEDIR)/$(HTM_OUTPUT)

fo: $(FO_OUTPUT)
$(FO_OUTPUT): $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet --stringparam profile.condition pdf \
                -stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		--output $(BASEDIR)/pg-pdf.xml stylesheets/pg-profile.xsl postgres.xml
	xsltproc --nonet --output $(BASEDIR)/pg-pdf.fo stylesheets/pg-pdf.xsl \
		$(BASEDIR)/pg-pdf.xml

pdf: $(PDF_OUTPUT)
$(PDF_OUTPUT): $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet --stringparam profile.condition pdf \
                -stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		--output $(BASEDIR)/pg-pdf.xml stylesheets/pg-profile.xsl postgres.xml
	xsltproc --nonet --output $(BASEDIR)/pg-pdf.fo stylesheets/pg-pdf.xsl \
		$(BASEDIR)/pg-pdf.xml
	sed -i -e "s/inherit/all/" $(BASEDIR)/pg-pdf.fo
	fop.sh $(BASEDIR)/pg-pdf.fo $(BASEDIR)/$(HTM_OUTPUT)/$(PDF_OUTPUT)
	rm $(BASEDIR)/pg-pdf.xml $(BASEDIR)/pg-pdf.fo

quickpdf: $(QUICKPDF_OUTPUT)
$(QUICKPDF_OUTPUT): $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet --stringparam profile.condition pdf \
                -stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		--output $(BASEDIR)/pg-pdf.xml stylesheets/pg-profile.xsl gettingstarted.xml
	xsltproc --nonet --output $(BASEDIR)/pg-pdf.fo stylesheets/quickpg-pdf.xsl \
		$(BASEDIR)/pg-pdf.xml
	sed -i -e "s/inherit/all/" $(BASEDIR)/pg-pdf.fo
	fop.sh $(BASEDIR)/pg-pdf.fo $(BASEDIR)/$(HTM_OUTPUT)/$(PDF_OUTPUT)
	#rm $(BASEDIR)/pg-pdf.xml $(BASEDIR)/pg-pdf.fo

nochunks: $(NOCHUNKS_OUTPUT)
$(NOCHUNKS_OUTPUT): $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet -stringparam profile.condition html \
		--output $(BASEDIR)/$(NOCHUNKS_OUTPUT) \
		stylesheets/pg-nochunks.xsl postgres.xml

	tidy -config tidy.conf $(BASEDIR)/$(NOCHUNKS_OUTPUT) || true

	sed -i -e "s@text/html@application/xhtml+xml@g"  \
	  $(BASEDIR)/$(NOCHUNKS_OUTPUT)

validate:
	xmllint --noout --nonet --xinclude --postvalid postgres.xml

INSTALL.html: $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc --xinclude --nonet -stringparam profile.condition html \
                --stringparam  profile.attribute  "standalone" --stringparam  profile.value  "yes" \
		--output $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.html \
		stylesheets/pg-nochunks.xsl standalone-install.xml

	tidy -config tidy.conf $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.html || true

	sed -i -e "s@text/html@application/xhtml+xml@g"  \
	  $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.html

INSTALL.txt: INSTALL.html
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	html2text -nobs -style pretty $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.html > $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.txt
	recode iso-8859-1..utf-8 $(BASEDIR)/$(HTM_OUTPUT)/INSTALL.txt

manpages: psql.1
psql.1: $(src)
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc $(XSLROOTDIR)/manpages/docbook.xsl \
		standalone-manpages.xml
	[ -d man/man1 ] || mkdir -p man/man1
	mv *.1 man/man1
	recode iso-8859-1..utf-8 man/man1/*.1
	tar cvfz $(BASEDIR)/$(HTM_OUTPUT)/$(MAN_OUTPUT) man
	rm -r man

htmlhelp:
	[ -d $(BASEDIR)/$(HTM_OUTPUT) ] || mkdir -p $(BASEDIR)/$(HTM_OUTPUT)
	xsltproc stylesheets/pg-chm.xsl \
		postgres.xml
	test -d chm || mkdir chm
	mv *.html *.h?? chm
	tar cvfz $(BASEDIR)/$(HTM_OUTPUT)/$(CHM_OUTPUT) chm
	rm -r chm
