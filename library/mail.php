<?php
header('Content-type: image/svg+xml');
include_once 'whitespace-constants.php';
echo '<'.'?'.'xml version="1.0"'.'?'.'>'.EOL;

if (false === strrpos($_SERVER['QUERY_STRING'], '&'))
{
	$mail = $_SERVER['QUERY_STRING'];
	$size = null;
}
else
{
	list($mail, $size) = explode('&', $_SERVER['QUERY_STRING']);
	if (strlen($mail) < strlen($size))
	{
		$swap = $mail;
		$mail = $size;
		$size = $swap;
	}
}

$mail = str_replace('%7C', '|', $mail);
list($mail, $checksum) = explode('-', $mail);
$mail = explode('|', $mail); $sum = 0;

/*echo '<!--'.EOL;
echo '$mail = '; var_dump($mail); echo EOL;
echo '$size = '.$size.EOL;
echo '-->'.EOL;*/

$decode = '';
foreach ($mail as $char)
{
	$decode .= chr($char ^ 0x4B);
	$sum += ($char ^ 0x4B);
}


include_once 'mail-font.php';

if (isSet($size) && !empty($size))
	$scale = $size / 11.0;
else
	$scale = null;

if ($sum == ($checksum ^ 0xB44B))
{
	if (isSet($mailColor) && is_array($mailColor))
	{
		$colour = '#';
		foreach ($mailColor as $value)
		{
			if ($value < 10) $colour .= '0';
			$colour .= dechex($value);
		}
	}
	else
		$colour = '#000';
	$text = $decode;
}
else
{
	$colour = '#f00';
	$text = chr(171).'error'.chr(187);
}

$length = strlen($text);

$width = 0; $data = '';
for ($i = 0; $i < $length; ++$i)
{
	$ord = ord($text[$i]);
	if (!isSet($letters[$ord])) $ord = ord('?');

	$data .= $letters[$ord]['svgA'].' style="fill:'.$colour.'"';
	if (0 != $width)
		$data .= ' transform="translate('.$width.')"';
	$data .= $letters[$ord]['svgZ'].EOL;

	$width += $letters[$ord]['width'];
}

echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" ';
if (isSet($scale) && !empty($scale))
	echo 'viewBox="0 0 '.$width.' 20" width="'.($width * $scale).
		'px" height="'.(20 * $scale).'px"';
else
	echo 'width="'.$width.'px" height="20px"';
echo '>'.EOL.$data.'</svg>'.EOL;
// echo '<!-- '.$text.' -->';
?>