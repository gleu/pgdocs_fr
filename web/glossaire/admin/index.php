<?
require_once('../main.inc.php');
require_once('../dbConnection.class.php');
require_once('../openConnection.inc.php');

$statement = 'SELECT * FROM terms ORDER BY term_fr';
$db->query($statement);
$numRows = $db->numrows();

echo XML_HEADER;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Traduction PostgreSQL: Administration du glossaire </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<h1>Traduction PostgreSQL: Administration du glossaire</h1>
<p><a href="form.php">Ajouter un mot</a></p>
<?
if ($numRows == 0) {
	echo "<p>Il n'y a encore aucun mot dans le glossaire.</p>\n";
}
else {
	echo "<p>Il y a $numRows mot(s) dans le glossaire.</p>\n";
?>
<table cellpadding="3" cellspacing="0" border="1">
	<tr>
		<th>Mot fran&ccedil;ais</th>
		<th>Mot anglais</th>
		<th colspan="2">Actions</th>
	</tr>
<?
for ($i = 0; $i < $numRows; $i++) {
	$record = $db->next();
	echo "<tr>\n";
	echo "<td>{$record['term_fr']}</td>\n";
	echo "<td>{$record['term_en']}</td>\n";
	echo "<td><a href=\"form.php?task=edit&pkey={$record['termid']}\">Modifier</a></td>\n";
	echo "<td><a href=\"form.php?task=delete&pkey={$record['termid']}\">Supprimer</a></td>\n";
	echo "</tr>\n";
}
?>
</table>
<?
}
?>
</body>
</html>