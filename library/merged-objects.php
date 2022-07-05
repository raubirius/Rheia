<?php

$mergedObjects = array();
$mergeSeparator = ', ';


function splitObjectPropertyLang(&$object, &$property, &$lang)
{
	$strpos = strpos($object, '#');
	if (false !== $strpos)
	{
		$property = substr($object, $strpos);
		$object = substr($object, 0, $strpos);
	}

	if (!empty($property) && '#' != $property[0])
		$property = '#'.$property;

	if (preg_match('/(–[a-z]{2})$/u', $object, $lang2))
	{
		$object = preg_replace('/–[a-z]{2}$/u', '', $object);
		$lang = $lang2[1]; $lang2 = null;
	}

	if (!empty($lang) && 0 !== strpos($lang, '–'))
		$lang = '–'.$lang;
}


function findIndexByValue($findValue, $object, $property = null, $lang = null)
{
	global $mergedObjects;

	splitObjectPropertyLang($object, $property, $lang);
	// echo '<p>'.$object.' — '.$property.' — '.$lang.'</p>';

	if (isSet($mergedObjects[$object]))
	{
		if (empty($property))
		{
			$value = $mergedObjects[$object][0][1];

			if (is_array($value))
				return array_search($findValue, $value, true);

			return $findValue === $value;
		}

		if (!empty($lang) && isSet($mergedObjects
			[$object][$property.$lang])) $property .= $lang;

		if (isSet($mergedObjects[$object][$property]))
		{
			$value = $mergedObjects[$object][$property][1];

			if (is_array($value))
				return array_search($findValue, $value, true);

			return $findValue === $value;
		}
	}

	return -1;
}

function getPropertyValue($object, $property = null,
	$lang = null, $index = null)
{
	global $mergedObjects, $mergeSeparator;

	splitObjectPropertyLang($object, $property, $lang);
	// echo '<p>'.$object.' — '.$property.' — '.$lang.'</p>';


	if (isSet($mergedObjects[$object]))
	{
		if (empty($property) && isSet($mergedObjects[$object][0]))
		{
			// $mark = $mergedObjects[$object][0][0] ? '<*>' : '';
			$value = $mergedObjects[$object][0][1];

			if (is_array($value))
			{
				if (isSet($index))
				{
					if (true === $index) return $value;
					return isSet($value[$index]) ? $value[$index] : '';
				}

				$value = implode($mergeSeparator, $value);
			}

			// return $mark.$value.$mark;
			return $value;
		}

		$schemaProp = null;

		if (isSet($mergedObjects['']) &&
			isSet($mergedObjects['']['schemas']) &&
			isSet($mergedObjects['']['schemas'][$property]))
		{
			$schemaProp = $property;
			// RheiaMainClass::logError(print_r($mergedObjects['']['schemas'][$property], true));
		}

		// Pridanie jazykového modifikátora
		if (!empty($lang) && isSet($mergedObjects
			[$object][$property.$lang]))
		{
			if (isSet($mergedObjects['']) &&
				isSet($mergedObjects['']['schemas']) &&
				isSet($mergedObjects['']['schemas'][$property.$lang]))
				$schemaProp = $property;

			$property .= $lang;
		}

		if (isSet($mergedObjects[$object][$property]))
		{
			// $mark = $mergedObjects[$object][$property][0] ? '<*>' : '';
			$value = $mergedObjects[$object][$property][1];

			if (!empty($property) && false !== stripos($property, 'mail'))
			{
				if (is_array($value))
				{
					foreach ($value as $key => $mail)
					{
						if (false !== strpos($mail, '@'))
						{
							$checksum = 0;
							$encode = RheiaMainClass::encodeMail(
								$mail, $checksum, false);
							$mailto = 'javascript:mdcs('.
								$encode.', '.$checksum.')';
							$encode = RheiaMainClass::encodeMail(
								$mail, $checksum, true);
							$value[$key] = '<a href="'.$mailto.'" '.
								'class="mcds">'.$encode.'</a>';
						}
					}
				}
				else if (false !== strpos($value, '@'))
				{
					$checksum = 0;
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, false);
					$mailto = 'javascript:mdcs('.
						$encode.', '.$checksum.')';
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, true);
					$value = '<a href="'.$mailto.'" '.
						'class="mcds">'.$encode.'</a>';
				}
			}
			elseif (!empty($schemaProp))
			{
				$oBak = RheiaMainClass::getObjectsData();
				RheiaMainClass::setObjectsData($mergedObjects);

				// RheiaMainClass::logError('Apply schema '.$schemaProp.' for '.$object);

				if (is_array($value))
				{
					foreach ($value as $key => $val)
					{
						$value[$key] = RheiaMainClass::applySchema(
							$object, $schemaProp, $lang, $val);
					}
				}
				else
				{
					$value = RheiaMainClass::applySchema(
						$object, $schemaProp, $lang, $value);
				}

				RheiaMainClass::setObjectsData($oBak);
			}

			if (is_array($value))
			{
				if (isSet($index))
				{
					if (true === $index) return $value;
					return isSet($value[$index]) ? $value[$index] : '';
				}
				$value = implode($mergeSeparator, $value);
			}

			// if (!empty($schemaProp))
			// 	RheiaMainClass::logError('	'.$value);

			// return $mark.$value.$mark;
			return $value;
		}
	}

	return '';
}


function seekObjects($contentPath, $parsedPath)
{
	global $mergedObjects;

	if ($handle = opendir($contentPath))
	{
		while (false !== ($entry = readdir($handle)))
		{
			if ($entry != '.' && $entry != '..')
			{
				if (is_dir($pathname = $contentPath.$entry))
				{
					// echo '['.$entry.']'."\r\n";
					seekObjects($contentPath.$entry.'/',
						$parsedPath.$entry.'/');
				}
				else if (0 === strcasecmp($entry,
					RheiaMainClass::$objectsFileBaseName.'.txo'))
				{
					// echo $contentPath.$entry."\r\n";
					// echo $parsedPath.$entry."\r\n";

					RheiaMainClass::setObjectsData(array());
					RheiaMainClass::$contentPath = $contentPath;
					RheiaMainClass::$parsedPath = $parsedPath;

					RheiaMainClass::reloadObjects();
					$objects = RheiaMainClass::getObjectsData();

					foreach ($objects as $object => $data)
					{
						// echo '  '.$object."\r\n";

						if (!isSet($objects[$object]['#origin']))
						{
							$objects[$object]['#origin'][0] = NULL;
							$objects[$object]['#origin'][1][] = $contentPath;
						}
						else if (!is_array($objects[$object]['#origin']))
						{
							$objects[$object]['#origin'][0] = NULL;
							$objects[$object]['#origin'][1][] =
								$objects[$object]['#origin'];
							$objects[$object]['#origin'][1][] = $contentPath;
						}
						else
						{
							$objects[$object]['#origin'][0] = NULL;
							$objects[$object]['#origin'][1][] = $contentPath;
						}
					}

					RheiaMainClass::joinObjects($mergedObjects, $objects);
				}
			}
		}
		closedir($handle);
	}
}

function loadMergedObject()
{
	global $mergeLevel;

	$objects = RheiaMainClass::getObjectsData();
	$contentPath = RheiaMainClass::$contentPath;
	$parsedPath = RheiaMainClass::$parsedPath;

	if (isSet($mergeLevel))
		seekObjects($contentPath.$mergeLevel, $parsedPath.$mergeLevel);
	else
		seekObjects($contentPath, $parsedPath);

	RheiaMainClass::setObjectsData($objects);
	RheiaMainClass::$contentPath = $contentPath;
	RheiaMainClass::$parsedPath = $parsedPath;
}

?>