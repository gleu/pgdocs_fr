/*** Definition section ***/

%{
#include <stdio.h>

typedef enum { false, true } bool;

// Current  indenting deep
unsigned int deep = 0;

// Has some text be displayed on the currrent line ?
bool NEWLINE = false;
// Ignore indenting, in special tags
bool IGNORE_INDENT = false;
// Are we inside a tag declaration ?
bool INTAG = false;

#define ENTER_TAG { DO_INDENT; deep++; }
#define EXIT_TAG { DO_INDENT; deep--; }
#define INDENT_CHAR  " "

/* DO_INDENT macro
 * It will insert the needed number
 * of INDENT_CHAR if needed when called.
 * It assumes that the flags
 * NEWLINE, INTAG and IGNORE_INDENT
 * are corretly set.
 * NEWLINE flag will be reset if needed to
 * be sure indent will be done only once.
 * It can therefore be called many times
 * without breaking indent.
 */
#define DO_INDENT { \
	if(NEWLINE){ \
		NEWLINE = false; \
		if (!IGNORE_INDENT)\
		{ \
			int _deep; \
			if (INTAG) \
				_deep = deep-1; \
			else \
				_deep = deep; \
			int i; \
			for (i=0;i<_deep;i++) \
				printf("%s", INDENT_CHAR); \
		} \
	} \
}

%}

%option noyywrap

%%

	/*** Rules sections ***/

"<?xml".*"?>" {
	/* Detect xml header, just display it */
	DO_INDENT;
	printf("%s", yytext);
}

"<!DOCTYPE".*">" {
	/* dtd declaration, just display it */
	DO_INDENT;
	printf("%s", yytext);
}

"<!--" {
	/*
	 * begin of xml comment
	 * do not indent inside
	 */
	ENTER_TAG;
	IGNORE_INDENT = true;
	printf("%s", yytext);
}

"-->" {
	/*
	 * end of xml comment
	 * re-enable indenting after that
	 */
	EXIT_TAG;
	IGNORE_INDENT = false;
	printf("%s", yytext);
}

"<programlisting>"|"<synopsis>" {
	/*
	 * special tags
	 * do not indent inside
	 */
	DO_INDENT;
	IGNORE_INDENT = true;
	printf("%s", yytext);
}

^[" "]*"</programlisting>" {
	/* end of special tag programlisting
	 * ignore trailing space(s)
	 * indent this tag and re-enable
	 * indenting after that.
	 */
	IGNORE_INDENT = false;
	DO_INDENT;
	printf("%s", "</programlisting>");
}

^[" "]*"</synopsis>" {
	/* end of special tag synopsis
	 * ignore trailing space(s)
	 * indent this tag and re-enable
	 * indenting after that.
	 */
	IGNORE_INDENT = false;
	DO_INDENT;
	printf("%s", "</synopsis>");
}

"</" {
	/* detect begin of tag closing */
	if (!IGNORE_INDENT)
	{
		INTAG = true;
		EXIT_TAG;
	}
	printf("%s", yytext);
}

"<" {
	/* detect begin of tag opening */
	if (!IGNORE_INDENT)
	{
		ENTER_TAG;
		INTAG = true;
	}
	printf("%s", yytext);
}

"/>" {
	/* detect closing of tag without body */
	if (!IGNORE_INDENT)
	{
		INTAG = false;
		EXIT_TAG;
	}
	printf("%s", yytext);
}

">" {
	/* detect end of tag, can be opening or closing */
	if (!IGNORE_INDENT)
	{
		INTAG = false;
	}
	printf("%s", yytext);
}

[ ]+$ {
	/* Remove trailing whitespaces */
}

[ ]+ {
	/* 
	* deal with space(s)
	* keep existings spaces if inside an unindented part
	* or inside a regular text. We juste ignore
	* trailing spaces as they will be handled by
	* DO_INDENT when needed
	*/
	if ( (IGNORE_INDENT) || (!NEWLINE))
	{
		printf("%s", yytext);
	}
}

\t+ {
	/*
	 * tabs handling
	 * if inside an unindented part, keep them as they are found.
	 * Inside regular text, replace consecutive tabs with 1 space
	 * ignore trailing tabs, they will be replaced by space(s)
	 * by DO_INDENT when needed
	 */
	if (IGNORE_INDENT)
	{
		printf("%s", yytext);
	}
	else
	{
		if (!NEWLINE)
			printf(" ");
	}
}

\n+ {
	/* detect new lines, just display them. */
	NEWLINE = true;
	printf("%s", yytext);
}

. {
	/* anything left, do indent and display char
	 * Not the most efficient way do do it, but
	 * certainly the easiest :)
	 */
	DO_INDENT;
	printf("%s", yytext);
}

%%

/*** C Code section ***/

int main(void)
{
	/* Just call lexer and quit */
	yylex();
	return 0;
}
