<?php

ob_start();

$fileModified = $expires = gmdate('D, d M Y H:i:s').' GMT';
$fileName = null; if (!isSet($downloadPath)) $downloadPath = '';
if (!isSet($downloadLevel)) $downloadLevel = '';
if (!isSet($downloadSubfolder)) $downloadSubfolder = '../downloads';
if (!isSet($downloadPathAllowDots)) $downloadPathAllowDots = false;

if (!isSet($designFilesPath)) $designFilesPath = '../design/';

function expirationHeaders()
{
	global $expires;

	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
	header('Pragma: public', false);
	header('Expires: '.$expires);
}

function rangeNotSatisfiable()
{
	header('HTTP/1.1 416 Requested Range Not Satisfiable');
	header('Content-Length: 0');
	ob_end_clean();
	exit;
}

function transferCommonHeaders($contentLength, $acceptRanges = true)
{
	global $fileName, $fileModified;
	if (null !== $contentLength)
		header('Content-Length: '.$contentLength);
	header('Content-Description: File Transfer');
	header(mimeContentType($fileName));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header('Content-Transfer-Encoding: binary');
	///**/ expirationHeaders();
	header('Last-Modified: '.$fileModified);
	if ($acceptRanges) header('Accept-Ranges: bytes');
	else header('Accept-Ranges: none');
	ob_clean();
	set_time_limit(0);
}


function thumbnailImage($inputFile,
	$width = null, $height = null, $decors = null,
	$putResizeSignOrSaveToFile = true)
{
	global $designFilesPath;

	// Check prerequisites
	if (!file_exists($inputFile)) return false;

	// Modify initial sizes
	if (empty($width)) $width = $height;
	elseif (empty($height)) $height = $width;

	if (empty($width)) $width = $height = 70;

	// Get new dimensions
	list($widthOrig, $heightOrig) = getimagesize($inputFile);

	$ratio = $width / $height;
	$ratioOrig = $widthOrig / $heightOrig;

	if (($ratio >= 1 && ($ratioOrig < 1 || $ratio > $ratioOrig)) ||
		($ratio < 1 && $ratioOrig < 1 && $ratio > $ratioOrig))
	{
		$x = 0;
		$heightNew = $widthOrig / $ratio;

		$y = ($heightOrig - $heightNew) / 2;
		$heightOrig = $heightNew;
	}
	else
	{
		$y = 0;
		$widthNew = $heightOrig * $ratio;

		$x = ($widthOrig - $widthNew) / 2;
		$widthOrig = $widthNew;
	}

	// Resample
	$imageOutput = imagecreatetruecolor($width, $height);


	// imagealphablending($imageOutput, false);
	// $transparency = imagecolorallocatealpha($imageOutput, 255, 0, 255, 127);
	// imagefill($imageOutput, 0, 0, $transparency);
	// imagesavealpha($imageOutput, true);
	// imagealphablending($imageOutput, true);


	if (preg_match('/\.(jpg|jpeg)$/i', $inputFile))
		$imageInput = imagecreatefromjpeg($inputFile);
	else if (preg_match('/\.png$/i', $inputFile))
		$imageInput = imagecreatefrompng($inputFile);
	else if (preg_match('/\.gif$/i', $inputFile))
		$imageInput = imagecreatefromgif($inputFile);
	else
		$imageInput = imagecreatefromstring(file_get_contents($inputFile));

	imagecopyresampled($imageOutput, $imageInput, 0, 0, $x, $y,
		$width, $height, $widthOrig, $heightOrig);

	// Put decors like mourning stripe…
	if (is_array($decors) && !empty($decors['name']))
	{
		$decorFile = $designFilesPath.$decors['name'].'.png';
		list($widthResize, $heightResize) = getimagesize($decorFile);
		$imageDecor = imagecreatefrompng($decorFile);

		if (!empty($decors['right']))
			imagecopyresampled($imageOutput, $imageDecor,
				$width - $widthResize, $height - $heightResize, 0, 0,
				$widthResize, $heightResize, $widthResize, $heightResize);
		else
			imagecopyresampled($imageOutput, $imageDecor,
				0, $height - $heightResize, 0, 0,
				$widthResize, $heightResize, $widthResize, $heightResize);
	}

	// Save to file
	if (is_string($putResizeSignOrSaveToFile))
		return imagejpeg($imageOutput, $putResizeSignOrSaveToFile, 80);

	// Put zoom sign
	if (true === $putResizeSignOrSaveToFile)
	{
		$zoomFile = $designFilesPath.'image-resized.png';
		list($widthResize, $heightResize) = getimagesize($zoomFile);
		$imageResize = imagecreatefrompng($zoomFile);
		imagecopyresampled($imageOutput, $imageResize,
			$width - $widthResize, $height - $heightResize, 0, 0,
			$widthResize, $heightResize, $widthResize, $heightResize);
	}

	// Output
	/*
	$imageFile = 'php://memory/profileTempPhoto.jpeg';
	if (imagejpeg($imageOutput, $imageFile, 80))
	{
		$contentLength = filesize($imageFile);
		header('Content-Length: '.$contentLength);
		return readfile($imageFile);
	*/
		return imagejpeg($imageOutput, null, 80);
	/*
	}
	*/
}


if (!empty($_SERVER['QUERY_STRING']) &&
	false === strpos($_SERVER['QUERY_STRING'], '..') &&
	($downloadPathAllowDots || (false === strpos($downloadPath, '..'))))
{
	global $designFilesPath;
	include_once 'mime.php';

	$gender = 'neutral';
	$decors = null;
	$originalName = $_SERVER['QUERY_STRING'];

	// include_once 'whitespace-constants.php';

	// echo 'gender: '.$gender.EOL;
	// echo 'decors: '.$decors.EOL;
	// echo 'originalName: '.$originalName.EOL;


	$strrpos = strrpos($originalName, '&');

	while (false !== $strrpos)
	{
		$parameter = substr($originalName, 1 + $strrpos);
		$originalName = substr($originalName, 0, $strrpos);

		// echo 'parameter: '.$parameter.EOL;

		if ('male' == $parameter || 'female' == $parameter)
			$gender = $parameter;
		else if ('mourningStripe' == $parameter ||
			'mourningStripeLeft' == $parameter)
			$decors['name'] = 'mourning-stripe-left';
		else if ('mourningStripeRight' == $parameter)
			$decors = array('name' => 'mourning-stripe-right', 'right' => true);

		$strrpos = strrpos($originalName, '&');
	}

	$originalName = str_replace('\\', '/',
		htmlspecialchars(rawurldecode($originalName)));


	// echo 'gender: '.$gender.EOL;
	// echo 'decors: '.$decors.EOL;
	// echo 'originalName: '.$originalName.EOL;


	// ob_end_flush();
	// exit;


	if (preg_match('/\.(png|gif|jpg|jpeg)$/i', $originalName))
	{
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

		// Find the image…
		$filePathName = '../htdocs/'.$originalName;

		if (!file_exists($filePathName))
		{
			$filePathName = $designFilesPath.$originalName;

			if (!file_exists($filePathName))
			{
				foreach (explode(';', $downloadPath) as $path)
				{
					$filePathName = $downloadLevel.$downloadSubfolder.'/';
					if (!empty($path)) $filePathName .= $path.'/';
					$filePathName .= $originalName;
					if (file_exists($filePathName)) break;
				}

				if (!file_exists($filePathName) &&
					(false !== ($strrpos = strrpos($originalName, '/'))))
				{
					$originalName = substr($originalName, 1 + $strrpos);

					foreach (explode(';', $downloadPath) as $path)
					{
						$filePathName = $downloadLevel.$downloadSubfolder.'/';
						if (!empty($path)) $filePathName .= $path.'/';
						$filePathName .= $originalName;
						if (file_exists($filePathName)) break;
					}
				}
			}
		}

		// Build dummy avatar image name
		if (!file_exists($filePathName))
		{
			$filePathName = $designFilesPath.'avatar-dummy-'.$gender.'.png';
			$dummy = true;
		}
		else $dummy = false;

		// Extract file name
		$strrpos = strrpos($filePathName, '/');
		if ($strrpos === false) $fileName = $filePathName; else
			$fileName = substr($filePathName, 1 + $strrpos);

		if (file_exists($filePathName))
		{
			// Check correct profile photo size
			list($checkWidth, $checkHeight) = getimagesize($filePathName);
			$desiredWidth = 135; $desiredHeight = 180;

			if ($desiredWidth != $checkWidth || $desiredHeight != $checkHeight ||
				is_array($decors))
			{
				if ($dummy)
				{
					transferCommonHeaders(null, false);
					if (!thumbnailImage($filePathName,
						$desiredWidth, $desiredHeight, $decors,
						false))
					{
						ob_clean();
						if (file_exists($designFilesPath.
							'avatar-dummy-'.$gender.'.png'))
							readfile($designFilesPath.
								'avatar-dummy-'.$gender.'.png');
					}
	
					ob_end_flush();
					exit;
				}
				else
				{
					if (is_array($decors))
					{
						$resizedPathName = preg_replace(
							'/(?=\.(?:png|gif|jpg|jpeg)$)/i', '-decor',
							$filePathName);
					}
					else
					{
						$resizedPathName = preg_replace(
							'/(?=\.(?:png|gif|jpg|jpeg)$)/i', '-cut',
							$filePathName);
					}

					$resizeFailed = false;

					if (file_exists($resizedPathName))
					{
						list($checkWidth, $checkHeight) =
							getimagesize($resizedPathName);
	
						if ($desiredWidth != $checkWidth ||
							$desiredHeight != $checkHeight)
							$resizeFailed = true;
					}
					else
					{
						if (!thumbnailImage($filePathName,
							$desiredWidth, $desiredHeight,
							$decors, $resizedPathName))
							$resizeFailed = true;
					}

					if ($resizeFailed)
					{
						transferCommonHeaders(null, false);
						if (!thumbnailImage($filePathName,
							$desiredWidth, $desiredHeight, $decors))
						{
							ob_clean();
							if (file_exists($designFilesPath.
								'avatar-dummy-'.$gender.'.png'))
								readfile($designFilesPath.
									'avatar-dummy-'.$gender.'.png');
						}
	
						ob_end_flush();
						exit;
					}
					else $filePathName = $resizedPathName;
				}
			}


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
					rangeNotSatisfiable();

				if ('' === $matches[1] && '' === $matches[2])
					rangeNotSatisfiable();

				$start  = intval($matches[1]);
				if ('' === $matches[2])
					$end = $filesize - 1;
				else
					$end = intval($matches[2]);
				$length = $end - $start + 1;

				if ($start > $end || $start >= $filesize)
					rangeNotSatisfiable();

				// Send headers
				header('HTTP/1.1 206 Partial Content');
				header('Content-Range: bytes '.$start.'-'.$end.'/'.$filesize);
				transferCommonHeaders($length);

				ob_clean();

				$fileHandle = @fopen($filePathName, 'rb'); // read binary

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
			else
			{
				transferCommonHeaders($filesize);

				// Send the whole file at once:
				//  readfile($filePathName);

				$fileHandle = @fopen($filePathName, 'rb'); // read binary

				while (!feof($fileHandle))
				{
					print(@fread($fileHandle, $speed));
					ob_flush();
					flush();
				}

				fclose($fileHandle);
			}

			ob_end_flush();
			exit;
		}
	}
}

// Create empty file
expirationHeaders();
header('Content-Length: 0');
ob_end_clean();

?>