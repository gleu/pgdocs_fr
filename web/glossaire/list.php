<?
require_once('main.inc.php');
require_once('dbConnection.class.php');
require_once('openConnection.inc.php');

echo XML_HEADER;

if (empty($_GET['order'])) {
	$order = 'fr';
}
else {
	$order = $_GET['order'];
}

$statement = "SELECT * FROM terms WHERE term_$order IS NOT NULL ORDER BY term_$order";
$db->query($statement);
$numRows = $db->numrows();

$titles = array('fr' => 'Liste fran&ccedil;ais-anglais','en' => 'Liste anglais-fran&ccedil;ais');
$inverse = array('fr' => 'en','en' => 'fr');
$alimit = ord('A');
$zlimit = ord('Z');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Glossaire pour la traduction de PostgreSQL: <? echo $titles[$order]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body onload="editForm.english.focus()">
<h1>Glossaire pour la traduction de PostgreSQL</h1>
<h2><? echo $titles[$order]; ?></h2>
<p>
<?
for ($i = $alimit; $i <= $zlimit; $i++) {
	if ($i > $alimit) echo ' - ';
	echo '<a href="#'.chr($i).'">'.chr($i).'</a>';
}
?>
</p>
<table cellpadding="4" cellspacing="0" border="0">
<?
$oldLetter = $alimit - 1;
for ($i = 0; $i < $numRows; $i++) {
	$record = $db->next();
	$key1 = "term_$order";
	$key2 = "term_{$inverse[$order]}";
	$currentLetter = ord(substr($record[$key1],0,1));
	if ($currentLetter != $oldLetter) {
		for ($j = $oldLetter + 1; $j <= $currentLetter; $j++) {
			$letter = chr($j);
			echo "<tr><td colspan=\"3\"><a name=\"$letter\"></a><strong>$letter</strong></td></tr>\n";
		}
		$oldLetter = $currentLetter;
	}
	$cell1 = empty($record[$key1])?'-':$record[$key1];
	$cell2 = empty($record[$key2])?'-':$record[$key2];
	if (!empty($record['comments'])) {
		$cell3 = '<em>'.nl2br($record['comments']).'</em>';
	}
	else {
		$cell3 = '&nbsp;';
	}
	echo "<tr valign=\"top\"><td>$cell1</td><td>$cell2</td><td>$cell3</td></tr>\n";
}
for ($j = $currentLetter + 1; $j <= $zlimit; $j++) {
	$letter = chr($j);
	echo "<tr><td colspan=\"3\"><a name=\"$letter\"></a><strong>$letter</strong></td></tr>\n";
}
?>
</table>
<p><a href="index.html">Menu</a> - <a href="list.php?order=<? echo $inverse[$order]; ?>"><? echo $titles[$inverse[$order]]; ?></a></p>
</body>
</html>