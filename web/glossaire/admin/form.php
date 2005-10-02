<?
require_once('../main.inc.php');
require_once('../dbConnection.class.php');
require_once('../openConnection.inc.php');

if (empty($_GET['task'])) {
	$task = 'add';
}
else {
	$task = $_GET['task'];
}

if ($task == 'add') {
	$title = 'Ajouter un terme';
	$term_fr = '';
	$term_en = '';
	$comments = '';
}
elseif ($task == 'edit' || $task == 'delete') {
	if (!isset($_GET['pkey'])) {
		header('Location: index.php');
		exit;
	}
	else {
		$pkey = $_GET['pkey'];
		if ($task == 'edit') {
			$title = 'Modifier un terme';
		}
		else {
			$title = 'Supprimer un terme';
		}
		$statement = "SELECT * FROM terms WHERE termid = $pkey";
		$db->query($statement);
		if ($db->numrows() == 0) {
			header('Location: index.php');
			exit;
		}
		else {
			$record = $db->next();
			$term_fr = $record['term_fr'];
			$term_en = $record['term_en'];
			$comments = $record['comments'];
		}
	}
}

echo XML_HEADER;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Glossaire pour la traduction de PostgreSQL: <? echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body onload="editForm.english.focus()">
<h1>Glossaire pour la traduction de PostgreSQL</h1>
<h2><? echo $title; ?></h2>
<? if ($task == 'add' || $task == 'edit') { ?>
<form name="editForm" action="process.php" method="post">
	<input type="hidden" name="task" value="<? echo $task; ?>" />
	<input type="hidden" name="pkey" value="<? echo $pkey; ?>" />
	<table cellpadding="4" cellspacing="0" border="0">
		<tr>
			<td><strong>Mot anglais</strong></td>
			<td><input type="text" name="english" value="<? echo $term_en; ?>" /></td>
		</tr>
		<tr>
			<td><strong>Mot fran&ccedil;ais</strong></td>
			<td><input type="text" name="french" value="<? echo $term_fr; ?>" /></td>
		</tr>
		<tr valign="top">
			<td><strong>Commentaire</strong></td>
			<td><textarea name="comment" cols="30" rows="5"><? echo $comments; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Valider"></td>
		</tr>
	</table>
</form>
<? } elseif ($task == 'delete') { ?>
<p>Voulez-vous vraiment supprimer le mot &quot;<? echo $term_fr; ?>&quot; du glossaire?</p>
<form name="deleteForm" action="process.php" method="post">
	<input type="hidden" name="task" value="<? echo $task; ?>" />
	<input type="hidden" name="pkey" value="<? echo $pkey; ?>" />
	<table cellpadding="8" cellspacing="0" border="0" align="center">
		<tr>
			<td><input type="button" value="Non" onclick="history.back()"></td>
			<td><input type="submit" value="Oui"></td>
		</tr>
	</table>
</form>
<? } ?>
<p><a href="index.php">Menu administration</a></p>
</body>
</html>