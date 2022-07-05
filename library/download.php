<?php

ob_start();

$fileModified = $expires = gmdate('D, d M Y H:i:s').' GMT';
$errorTitle = $errorMessage = $errorDescription = null;
$fileName = null; if (!isSet($downloadPath)) $downloadPath = '';
if (!isSet($downloadLevel)) $downloadLevel = null;
if (!isSet($downloadSubfolder)) $downloadSubfolder = '../downloads';
if (!isSet($downloadPathAllowDots)) $downloadPathAllowDots = false;

function expirationHeaders()
{
	global $expires;
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
	header('Pragma: public', false);
	header('Expires: '.$expires);
}

function rangeNotSatisfiable($note416)
{
	header('HTTP/1.1 416 Requested Range Not Satisfiable');
	header('Content-Length: 0');
	header('X-416-Note: '.$note416);
	ob_end_clean();
	exit;
}

function transferCommonHeaders($contentLength)
{
	global $fileName, $fileModified;
	header('Content-Length: '.$contentLength);
	header('Content-Description: File Transfer');
	header(mimeContentType($fileName));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header('Content-Transfer-Encoding: binary');
	expirationHeaders();
	header('Last-Modified: '.$fileModified);
	header('Accept-Ranges: bytes');
	ob_clean();
	set_time_limit(0);
}

function timeIsValid($string)
{
	$string = str_replace(chr(0xef).chr(0xbb).chr(0xbf), '', $string);

	@list($day, $month, $year, $hour, $minute, $second) =
		preg_split('/[.:,]/', str_replace(' ', '', str_replace(' ', '',
			str_replace('&#44;', ',', str_replace('&#46;', '.',
			str_replace('&#58;', ':', $string))))).',,,,,');

	$hour = (int)$hour; $minute = (int)$minute;
	$second = (int)$second; $month = (int)$month;
	$day = (int)$day; $year = (int)$year;

	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	$now = time();

	$valid = $timestamp >= $now;

	if (!$valid)
		header('X-Expiration-Date: '.$day.'. '.$month.'. '.$year.', '.
			sprintf('%02d', $hour).':'.sprintf('%02d', $minute).':'.
			sprintf('%02d', $second));

	return $valid;
}


if (empty($_SERVER['QUERY_STRING']))
{
	$errorTitle = 'Poznámka';
	$errorMessage = 'Nezadali ste názov súboru, ktorý chcete prevziať.';
	$errorDescription = 'Tento druh požiadavky slúži na našom serveri na '.
		'prevzatie súborov. Na to, aby sme mohli požiadavke vyhovieť, musíte '.
		'zadať názov súboru jestvujúceho v rámci nášho webového sídla.';
}
elseif (false === strpos($_SERVER['QUERY_STRING'], '..') &&
	false === strpos($_SERVER['QUERY_STRING'], ':') &&
	// false === strpos($downloadPath, '..') &&
	($downloadPathAllowDots || (false === strpos($downloadPath, '..'))) &&
	false === strpos($downloadPath, ':'))
{
	include_once 'mime.php';

	$originalName = str_replace('\\', '/',
		htmlspecialchars(rawurldecode($_SERVER['QUERY_STRING'])));
	$filePathName = null;

	if (isSet($downloadModifyOriginalName) &&
		is_array($downloadModifyOriginalName))
	{
		$i = 0; $regEx = '';
		foreach ($downloadModifyOriginalName as $data)
		{
			if (0 == ($i % 2)) $regEx = $data; else
				$originalName = preg_replace($regEx, $data, $originalName);
			++$i;
		}
	}

	foreach (explode(';', $downloadPath) as $path)
	{
		$filePathName = $downloadLevel.$downloadSubfolder.'/';
		if (!empty($path)) $filePathName .= $path.'/';
		$filePathName .= $originalName;
		if (file_exists($filePathName)) break;
	}

	if (!file_exists($filePathName))
	{
		foreach (explode(';', $downloadPath) as $path)
		{
			$tempPathName = $downloadLevel.$downloadSubfolder.'-timed/';
			if (!empty($path)) $tempPathName .= $path.'/';
			$tempPathName .= $originalName;
			if (file_exists($tempPathName) &&
				file_exists($tempPathName.'-time'))
			{
				if (timeIsValid(file_get_contents($tempPathName.'-time')))
				{
					$filePathName = $tempPathName;
					break;
				}
				else
				{
					expirationHeaders();
					ob_end_clean();

					include_once 'design.php';
					include '410.php'; // obsahuje header 410
					ob_end_flush();

					exit;
				}
			}
		}
	}

	// Extract file name
	$strpos = strrpos($filePathName, '/');
	if ($strpos === false) $fileName = $filePathName; else
		$fileName = substr($filePathName, 1 + $strpos);


	if (file_exists($filePathName))
	{
		define('counterBase', 'downloads');

		include_once 'whitespace-constants.php';
		include_once 'counter.php';

		$filetime = filemtime($filePathName);
		if ($filetime < time()) $fileModified =
			gmdate('D, d M Y H:i:s', $filetime).' GMT';
		$filesize = filesize($filePathName);
		$speed = 8192;

		if (isSet($_SERVER['HTTP_RANGE']))
		{
			$matches = array();

			if (!preg_match('/bytes=(\d*)-(\d*)?/',
				$_SERVER['HTTP_RANGE'], $matches))
				rangeNotSatisfiable('Invalid range format: '.
					$_SERVER['HTTP_RANGE']);

			if ('' === $matches[1] && '' === $matches[2])
				rangeNotSatisfiable('Empty range');

			$start  = intval($matches[1]);
			if ('' === $matches[2])
				$end = $filesize - 1;
			else
				$end = intval($matches[2]);
			$length = $end - $start + 1;

			if ($start > $end)
				rangeNotSatisfiable('Range overflow; the range start ('.
					$start.') crosses over the range end ('.$end.')');
			if ($start >= $filesize)
				rangeNotSatisfiable('Range overflow; the range start ('.
					$start.') overflows the file size ('.$filesize.')');

			// Send headers
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes '.$start.'-'.$end.'/'.$filesize);
			transferCommonHeaders($length);

			ob_clean();

			if ('HEAD' !== strtoupper($_SERVER['REQUEST_METHOD']) &&
				false !== ($fileHandle = @fopen($filePathName, 'rb')))
			{
				// read binary

				// Send the whole range at once:
				//  fseek($fileHandle, $start);
				//  print(@fread($fileHandle, $length));

				$left = $length;

				fseek($fileHandle, $start);
				// Loop while there are bytes left
				while ($left > 0 && !feof($fileHandle))
				{
					// Bytes to be transferred
					// according to the defined speed
					if ($left < $speed) $bytes = $left;
					else $bytes = $speed;

					print(@fread($fileHandle, $bytes));
					ob_flush();
					flush();

					$left -= $bytes;
				}

				fclose($fileHandle);
			}
			else header('X-Note: content not sent due to HEAD request type');
		}
		else
		{
			transferCommonHeaders($filesize);

			if ('HEAD' !== strtoupper($_SERVER['REQUEST_METHOD']))
			{
				// Send the whole file at once:
				//  readfile($filePathName);

				if (false !== ($fileHandle = @fopen($filePathName, 'rb')))
				{
					// read binary

					while (!feof($fileHandle))
					{
						print(@fread($fileHandle, $speed));
						ob_flush();
						flush();
					}

					fclose($fileHandle);
				}
			}
			else header('X-Note: content not sent due to HEAD request type');
		}

		ob_end_flush();
		exit;
	}
	else
	{
		foreach (explode(';', $downloadPath) as $path)
		{
			$directRedirection = $downloadLevel.$downloadSubfolder.'/';
			if (!empty($path)) $directRedirection .= $path.'/';
			$directRedirection .= $originalName.'-redirect';

			if (file_exists($directRedirection) && is_file($directRedirection))
			{
				$newName = $_SERVER['REQUEST_SCHEME'].'://'.
					$_SERVER['HTTP_HOST'].'/'.(isSet($downloadScript) ?
						$downloadScript : 'download').'?'.
						file_get_contents($directRedirection);
				header('Location: '.$newName);
				include '301.php'; // obsahuje header 301
				ob_end_flush();
				exit;
			}
		}

		$directRedirection = $downloadSubfolder.'/'.str_replace(
			'/', '-', $originalName).'.redirect';

		if (file_exists($directRedirection) && is_file($directRedirection))
		{
			$newName = $_SERVER['REQUEST_SCHEME'].'://'.
				$_SERVER['HTTP_HOST'].'/'.(isSet($downloadScript) ?
					$downloadScript : 'download').'?'.
					file_get_contents($directRedirection);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}

		foreach (explode(';', $downloadPath) as $path)
		{
			$directRedirection = $downloadLevel.$downloadSubfolder.'/';
			if (!empty($path)) $directRedirection .= $path.'/';
			$directRedirection .= $originalName.'-location';

			if (file_exists($directRedirection) && is_file($directRedirection))
			{
				$newName = file_get_contents($directRedirection);
				header('Location: '.$newName);
				include '301.php'; // obsahuje header 301
				ob_end_flush();
				exit;
			}
		}

		$directRedirection = $downloadSubfolder.'/'.str_replace(
			'/', '-', $originalName).'.location';

		if (file_exists($directRedirection) && is_file($directRedirection))
		{
			$newName = file_get_contents($directRedirection);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}


		foreach (explode(';', $downloadPath) as $path)
		{
			$directRedirection = $downloadLevel.$downloadSubfolder.'-timed/';
			if (!empty($path)) $directRedirection .= $path.'/';
			$directRedirection .= $originalName.'-redirect';

			if (file_exists($directRedirection) && is_file($directRedirection))
			{
				$newName = $_SERVER['REQUEST_SCHEME'].'://'.
					$_SERVER['HTTP_HOST'].'/'.(isSet($downloadScript) ?
						$downloadScript : 'download').'?'.
						file_get_contents($directRedirection);
				header('Location: '.$newName);
				include '301.php'; // obsahuje header 301
				ob_end_flush();
				exit;
			}
		}

		$directRedirection = $downloadSubfolder.'-timed/'.str_replace(
			'/', '-', $originalName).'.redirect';

		if (file_exists($directRedirection) && is_file($directRedirection))
		{
			$newName = $_SERVER['REQUEST_SCHEME'].'://'.
				$_SERVER['HTTP_HOST'].'/'.(isSet($downloadScript) ?
					$downloadScript : 'download').'?'.
					file_get_contents($directRedirection);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}

		foreach (explode(';', $downloadPath) as $path)
		{
			$directRedirection = $downloadLevel.$downloadSubfolder.'-timed/';
			if (!empty($path)) $directRedirection .= $path.'/';
			$directRedirection .= $originalName.'-location';

			if (file_exists($directRedirection) && is_file($directRedirection))
			{
				$newName = file_get_contents($directRedirection);
				header('Location: '.$newName);
				include '301.php'; // obsahuje header 301
				ob_end_flush();
				exit;
			}
		}

		$directRedirection = $downloadSubfolder.'-timed/'.str_replace(
			'/', '-', $originalName).'.location';

		if (file_exists($directRedirection) && is_file($directRedirection))
		{
			$newName = file_get_contents($directRedirection);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}


		include_once 'remove-diacritics.php';
		include_once 'ExternalRedirects.php';

		if (($newName = ExternalRedirects::replace(removeDiacriticsFromURI(
			$originalName))) !== $originalName)
		{
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			ob_end_flush();
			exit;
		}


		header('HTTP/1.1 404 Not Found');
		$errorTitle = 'Chýbajúci dokument';
		$errorMessage = 'Ľutujeme, ale požadovaný dokument momentálne '.
			'nie je k dispozícii.';
		$errorDescription = 'Vami zadaná adresa môže byť zastaraná. Skúste '.
			'získať aktuálnu adresu súboru alebo dokument vyhľadať na '.
			'našich stránkach.';
	}
}
else
{
	header('HTTP/1.1 400 Bad Request');
	$errorTitle = 'Varovanie!';
	$errorMessage = 'Chybné meno súboru!';
	$errorDescription = 'Zadaný názov súboru obsahuje neplatné znaky!';
}


// Send error message to the browser
expirationHeaders();
ob_end_clean();

include_once 'design.php';

echo '<h1 class="error">'.$errorTitle.'</h1>'.
	'<p class="error">'.$errorMessage.'</p>'.
	'<p>'.$errorDescription.'</p>';

?>