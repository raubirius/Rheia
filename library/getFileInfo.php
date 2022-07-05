<?php
ob_start();

function shutdownTimeout()
{
	$fileInfoContents = ob_get_contents();
	ob_end_clean();

	$lastError = error_get_last();
	if (null !== $lastError)
	{
		echo 'HTTP/1.1 504 Gateway Timeout'."\r\n";
		echo 'X-Error-Message: '.$lastError['message']."\r\n";
	}

	echo $fileInfoContents;
	echo "\r\n";
}

register_shutdown_function('shutdownTimeout');

set_time_limit(16);

$userAgent = 'RheiaRobot/1.5 (';
if (!empty($_SERVER['SERVER_SOFTWARE']))
	$userAgent .= $_SERVER['SERVER_SOFTWARE'].'; +';
if (empty($domain))
{
{
	if (empty($protocol))
		$userAgent .= 'http://pdf.truni.sk/)';
	else
		$userAgent .= $protocol.'://pdf.truni.sk/)';
}
else
{
	if (empty($protocol))
		$userAgent .= 'http://'.$domain.'/)';
	else
		$userAgent .= $protocol.'://'.$domain.'/)';
}

try
{
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 12,
		CURLOPT_CONNECTTIMEOUT => 12,
		CURLOPT_USERAGENT => $userAgent,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_SSL_VERIFYPEER => false,

		CURLOPT_NOBODY => true,
		CURLOPT_HEADER => true));

	echo curl_exec($ch);
	curl_close($ch);
}
catch (Exception $e)
{
	echo 'HTTP/1.1 500 Internal Server Error'."\r\n";
	echo 'X-Error-Message: '.$e->getMessage()."\r\n";
}
?>