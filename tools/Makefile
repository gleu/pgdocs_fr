pg_doc_indent: pg_doc_indent.c
	gcc -o pg_doc_indent pg_doc_indent.c

pg_doc_indent.c : pg_doc_indent.l
	flex -o pg_doc_indent.c pg_doc_indent.l
