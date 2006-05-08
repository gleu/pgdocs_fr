BASEDIR := $(shell echo "~/pgsql-`grep -v major version.xml | cut -c19-23`-fr")
PDF_OUTPUT := $(shell echo "pg`grep -v major version.xml | cut -c19-23 | sed -e 's/\.//g'`.pdf")
NOCHUNKS_OUTPUT := $(shell echo "pg`grep -v major version.xml | cut -c19-23 | sed -e 's/\.//g'`.html")
CHUNK_QUIET=0
XSLROOTDIR=/usr/share/xml/docbook/stylesheet/nwalsh

html:
	xsltproc --xinclude --nonet -stringparam profile.condition html \
                -stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		-stringparam chunk.quietly $(CHUNK_QUIET) \
		-stringparam base.dir $(BASEDIR)/ \
		stylesheets/pg-chunked.xsl postgres.xml

	if [ ! -e $(BASEDIR)/stylesheets ]; then \
	  mkdir -p $(BASEDIR)/stylesheets; \
	fi;
	cp stylesheets/*.css $(BASEDIR)/stylesheets

	if [ ! -e $(BASEDIR)/images ]; then \
	  mkdir -p $(BASEDIR)/images; \
	fi;
	cp $(XSLROOTDIR)/images/*.png \
	  $(BASEDIR)/images
	cd $(BASEDIR)/; sed -i -e "s@../stylesheets@stylesheets@g" \
	  *.html
	cd $(BASEDIR)/; sed -i -e "s@../images@images@g" \
	  *.html

	for filename in `find $(BASEDIR) -name "*.html"`; do \
	  tidy -config tidy.conf $$filename; \
	  true; \
	  sed -i -e "s@text/html@application/xhtml+xml@g" $$filename; \
	done;

pdf:
	xsltproc --xinclude --nonet --stringparam profile.condition pdf \
                -stringparam  profile.attribute  "standalone" -stringparam  profile.value  "no" \
		--output $(BASEDIR)/pg-pdf.xml stylesheets/pg-profile.xsl postgres.xml
	xsltproc --nonet --output $(BASEDIR)/pg-pdf.fo stylesheets/pg-pdf.xsl \
		$(BASEDIR)/pg-pdf.xml
	sed -i -e "s/inherit/all/" $(BASEDIR)/pg-pdf.fo
	fop.sh $(BASEDIR)/pg-pdf.fo $(BASEDIR)/$(PDF_OUTPUT)
	rm $(BASEDIR)/pg-pdf.xml $(BASEDIR)/pg-pdf.fo

nochunks:
	xsltproc --xinclude --nonet -stringparam profile.condition html \
		--output $(BASEDIR)/$(NOCHUNKS_OUTPUT) \
		stylesheets/pg-nochunks.xsl postgres.xml

	tidy -config tidy.conf $(BASEDIR)/$(NOCHUNKS_OUTPUT) || true

	sed -i -e "s@text/html@application/xhtml+xml@g"  \
	  $(BASEDIR)/$(NOCHUNKS_OUTPUT)

validate:
	xmllint --noout --nonet --xinclude --postvalid postgres.xml

INSTALL.html:
	xsltproc --xinclude --nonet -stringparam profile.condition html \
                --stringparam  profile.attribute  "standalone" --stringparam  profile.value  "yes" \
		--output $(BASEDIR)/INSTALL.html \
		stylesheets/pg-nochunks.xsl standalone-install.xml

	tidy -config tidy.conf $(BASEDIR)/INSTALL.html || true

	sed -i -e "s@text/html@application/xhtml+xml@g"  \
	  $(BASEDIR)/INSTALL.html

INSTALL.txt:
	make INSTALL.html
	html2text -nobs -style pretty $(BASEDIR)/INSTALL.html > $(BASEDIR)/INSTALL.txt
	recode iso-8859-1..utf-8 $(BASEDIR)/INSTALL.txt

manpages:
	xsltproc /usr/share/xml/docbook/stylesheet/nwalsh/manpages/docbook.xsl \
		standalone-manpages.xml
	mkdir $(BASEDIR)/ref
	mv *.1 $(BASEDIR)/ref
	recode iso-8859-1..utf-8 $(BASEDIR)/ref/*
