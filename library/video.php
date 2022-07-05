<!DOCTYPE html>
<html lang="sk">
<?php

include_once 'mime.php';

if (!isSet($downloadPath)) $downloadPath = '';
if (!isSet($downloadLevel)) $downloadLevel = '';
if (!isSet($downloadSubfolder)) $downloadSubfolder = '../downloads';
if (!isSet($downloadPathAllowDots)) $downloadPathAllowDots = false;

if (isSet($_SERVER['QUERY_STRING']))
{
	// TEST:
	// $pathParts = pathinfo(str_replace('%252F', '/',
	// 	$_SERVER['QUERY_STRING']));
	$pathParts = pathinfo(str_replace('\\', '/', htmlspecialchars(
		rawurldecode($_SERVER['QUERY_STRING']))));

	if (isSet($pathParts['extension']))
	{
		if (isSet($pathParts['dirname']))
		{
			if (false === strpos($pathParts['dirname'], '..') &&
				false === strpos($pathParts['dirname'], ':'))
			{
				$dir = $pathParts['dirname'];
				$ext = $pathParts['extension'];
				$name = $pathParts['filename'];
				$lowExt = strtolower($ext);

				if (isSet($mime_types[$lowExt]))
					$mime = $mime_types[$lowExt];
				else
					$mime = 'video/*';
			}
			else $error = 'Nepovolený znak v ceste.';
		}
		else $error = 'Neznámy priečinok.';
	}
	else $error = 'Neznáma prípona.';
}
else $error = 'Neznáma cesta.';

?>
<head>
<meta charset="UTF-8" />
<title></title>
<style>

html, body
{
	height: 100%;
	margin: 0px;
	padding: 0px;
}

video
{
	width: 100%;
	height: 100%;
}

div
{
	display: flex;
	align-items: center;
	height: 100%;
}

p
{
	margin: auto;
	padding: 0px;
	text-align: center;
	font-family: sans-serif;
	font-size: 1.5em;
	font-weight: bold;
	color: maroon;
}

</style>

<!-- script></script -->

</head>

<body>
<?php

if (isSet($dir) && isSet($ext) && isSet($name) && isSet($mime))
{
	$originalName = $dir.'/'.$name.'.'.$ext;
	$filePathName = $downloadLevel.$downloadSubfolder.'/'.$originalName;

	if (!file_exists($filePathName) &&
		($downloadPathAllowDots || (false === strpos($downloadPath, '..'))) &&
		false === strpos($downloadPath, ':'))
	{
		foreach (explode(';', $downloadPath) as $path)
		{
			$filePathName = $downloadLevel.$downloadSubfolder.'/';
			if (!empty($path)) $filePathName .= $path.'/';
			$filePathName .= $originalName;
			if (file_exists($filePathName)) break;
		}
	}

	if (file_exists($filePathName))
		echo '<video controls>'."\n\t".'<source src="/download?'.
			$originalName.'" type="'.$mime.'">'."\n\t".'Váš prehliadač '.
			'nepodporuje prehrávanie videozáznamov týmto spôsobom.'."\n".
			'<a href="/download?'.$originalName.'" class="download">'.
			'Záznam prevezmete kliknutím na tento odkaz.</a>'.
			'</video>';
	else
		echo '<div><p>Záznam nebol nájdený.<br /> <br /><small>(Skúste stránku obnoviť, môže ísť o dočasnú poruchu.)</small></p></div>';

	echo '<!--'."\r\n".'dir: „', $dir, '“'."\r\n".'ext: „', $ext, '“'.
		"\r\n".'name: „', $name, '“'."\r\n".'mime: „', $mime, '“'.
		"\r\n".'-->'."\r\n";
}
else
	echo '<div><p>Nedostatok údajov alebo nesprávne údaje.<br /> <br /><small>(Na základe zadaných, prípadne chýbajúcich, parametrov nemôžem požadovaný videozáznam vyhľadať.)</small></p></div>';

if (isSet($error)) echo '<!-- '.$error.' -->';

?>
</body>
</html>