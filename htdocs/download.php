<?php
$downloadPath = 'fakulta;fakulta/studium;fakulta/medzinarodne;;katedry';

function astuRedirect()
{
	$query = $_SERVER['QUERY_STRING'];
	if (0 === strpos(strtolower($query), 'astu/'))
	{
		$newName = preg_replace('~[Aa][Ss][Tt][Uu]/(.*)~',
			'/astu/download?$1', $query);
		// echo $newName;
		header('Location: '.$newName);
		include '301.php'; // obsahuje header 301
		exit;
	}
	// else echo 'No match';
}
astuRedirect();

include 'download.php';
?>