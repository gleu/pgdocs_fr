@echo off
rem /**
rem  * Génération de la version CHM de la documentation.
rem  */

rem /**
rem  * setlocal sert à ne pas impacter les variable d'environnement globale
rem  */
setlocal

echo Positionnement des variables d environnement.

set DOCBOOK="E:\docbook"
set PG_ENV_82="E:\Versionning\SVN\docpgfr\8.2\manuel"
set HHC="C:\Program Files\HTML Help Workshop\hhc.exe"

echo Suppression des fichiers temporaires précédents.

del /Q %PG_ENV_82%\chm\*.*
del %PG_ENV_82%\index.hhk
del %PG_ENV_82%\toc.hhc
del %PG_ENV_82%\htmlhelp.hhp

echo Génération des fichiers HTML

%DOCBOOK%\bin\xsltproc --xinclude %PG_ENV_82%\stylesheets\pg-chm.xsl %PG_ENV_82%\postgres.xml
rem %DOCBOOK%\bin\xmllint --noout --xinclude --postvalid postgres.xml

rem /**
rem  * On copie les fichiers necessaires à la génération du fichier CHM
rem  */

echo Copie du fichier CSS.

copy /Y %PG_ENV_82%\stylesheets\pg-chm.css %PG_ENV_82%\chm\

rem /**
rem  * génération des fichiers avec la feuille de style spécifique.
rem  */

echo Génération du fichier au format HTMLHelp

%HHC% %PG_ENV_82%\htmlhelp.hhp

rem /**
rem  * Transfert FTP du fichier
rem  */

echo Transfert FTP.

rem ftp -n -s:pgfr.ftp

endlocal

pause
