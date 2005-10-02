<?
require_once('../main.inc.php');
require_once('../dbConnection.class.php');
require_once('../openConnection.inc.php');

$task = $_POST['task'];
$pkey = $_POST['pkey'];
$english = $_POST['english'];
$french = $_POST['french'];
$comment = $_POST['comment'];

if ($task == 'add') {
	$variables = '';
	$values = '';
	if (!empty($english)) {
		$variables .= 'term_en';
		$values .= "'$english'";
	}
	if (!empty($french)) {
		if (!empty($variables)) {
			$variables .= ', ';
			$values .= ', ';
		}
		$variables .= 'term_fr';
		$values .= "'$french'";
	}
	if (!empty($comment)) {
		if (!empty($variables)) {
			$variables .= ', ';
			$values .= ', ';
		}
		$variables .= 'comments';
		$values .= "'$comment'";
	}
	$statement = "INSERT INTO terms ($variables) VALUES ($values)";
	$db->query($statement);
}
if ($task == 'edit') {
	$statement = 'UPDATE terms SET';
	if (!empty($english)) {
		$statement .= " term_en = '$english'";
	}
	else {
		$statement .= ' term_en = NULL';
	}
	if (!empty($french)) {
		$statement .= ", term_fr = '$french'";
	}
	else {
		$statement .= ', term_fr = NULL';
	}
	if (!empty($comment)) {
		$statement .= ", comments = '$comment'";
	}
	else {
		$statement .= ', comments = NULL';
	}
	$statement .= " WHERE termid = $pkey::bigint";
	$db->query($statement);
}
if ($task == 'delete') {
	$statement = "DELETE FROM terms WHERE termid = $pkey::bigint";
	$db->query($statement);
}

echo XML_HEADER;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Glossaire pour la traduction de PostgreSQL: <? echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<h1>Glossaire pour la traduction de PostgreSQL</h1>
<h2>R&eacute;sultat de l'op&eacute;ration</h2>
<?
if ($task == 'add') {
	echo "<p>Le terme a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s.</p>";
}
elseif ($task == 'edit') {
	echo "<p>Le terme a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s.</p>";
}
elseif ($task == 'delete') {
	echo "<p>Le terme a &eacute;t&eacute; supprim&eacute; avec succ&egrave;s.</p>";
}
?>
<p><a href="index.php">Menu administration</a> - <a href="form.php">Ajouter un terme</a></p>
</body>
</html>