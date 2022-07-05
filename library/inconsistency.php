<?php

$style .= 'div.page-content pre { overflow: scroll; border: 1px dotted silver; }';
// header('Content-Type: text/plain; charset=UTF-8');

// include_once 'whitespace-constants.php';
include_once 'RheiaMainClass.php';
include_once 'GalleryLoader.php';

// echo 'Nezrovnalosti'.EOL.'============='.EOL2;
$level = (isSet($downloadLevel) ? $downloadLevel : '').'../';

function getAllItems($dir = '')
{
	global $allItems, $level;

	$d = dir($level.'downloads'.$dir);
	while (false !== ($entry = $d->read()))
	{
		if ('..' == $entry || '.' == $entry) continue;

		if (is_dir($level.'downloads'.$dir.'/'.$entry))
			getAllItems($dir.'/'.$entry);
		else
			$allItems[strtolower(ltrim($dir.'/'.$entry, '/'))] = false;
	}
	$d->close();
}


$debugInfo = '';

function debugInfo($info)
{
	global $debugInfo;
	$debugInfo .= 'DEBUG: '.$info.EOL;
}


function cannotResolveAsImage($originalName)
{
	global $downloadPath, $allItems, $level;

	if (false !== strpos($originalName, '$'))
	{
		// echo 'DEBUG – original name: '.$originalName.EOL;
		$originalName = preg_replace('/&(fe)?male$/i', '',
			RheiaMainClass::replaceObjects($originalName));
		// echo 'DEBUG – replaced objects: '.$originalName.EOL;
	}

	$strrpos = strrpos($originalName, '&');
	if (false !== $strrpos) $originalName = substr($originalName, 0, $strrpos);

	if (preg_match('/\.(png|gif|jpg|jpeg)$/i', $originalName))
	{
		foreach (array('htdocs', 'design', '~webs') as $folder)
			if (file_exists($level.$folder.'/'.$originalName))
				return false;

		foreach (explode(';', $downloadPath) as $path)
		{
			$pathName = (empty($path) ? '' : ($path.'/')).$originalName;

			if (file_exists($level.'downloads/'.$pathName))
			{
				$pathName = strtolower($pathName);
				// echo 'DEBUG – resolved as: '.$pathName.EOL;

				if (isSet($allItems[$pathName]))
					$allItems[$pathName] = true;
				else
					$allItems[$pathName] = null;

				return false;
			}
		}
	}

	return true;
}


function checkRheiaContent()
{
	debugInfo("checkRheiaContent()");

	global $allItems, $downloadPath, $rheiaClass, $inconsistencies, $level;

	$putSourceInfo = true;
	$html = $articles = null;

	if (null !== ($html = $rheiaClass->getHTMLData()))
	{
		if (isSet($html['files']))
			foreach ($html['files'] as $file)
			{
				if ($file['exists'])
				{
					foreach (explode(';', $downloadPath) as $path)
					{
						$pathName = (empty($path) ? '' :
							($path.'/')).$file['originalName'];

						if (file_exists($level.'downloads/'.$pathName))
						{
							$pathName = strtolower($pathName);
							if (isSet($allItems[$pathName]))
								$allItems[$pathName] = true;
							else
								$allItems[$pathName] = null;
							break;
						}
					}
				}
				else if (cannotResolveAsImage($file['originalName']))
				{
					if ($putSourceInfo)
					{
						echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
							'.txh'.EOL;
						$putSourceInfo = false;
					}

					echo '    Chýbajúci súbor: '.$file['originalName'].EOL;
					++$inconsistencies;
				}
			}

		if (isSet($html['externFiles']))
			foreach ($html['externFiles'] as $file)
			{
				if (!$file['exists'])
				{
					if ($putSourceInfo)
					{
						echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
							'.txh'.EOL;
						$putSourceInfo = false;
					}

					echo '    Chýbajúci externý súbor: '.$file['url'].EOL;
					++$inconsistencies;
				}
				elseif (empty($file['fileSize']) || empty($file['fileDate']))
				{
					if ($putSourceInfo)
					{
						echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
							'.txh'.EOL;
						$putSourceInfo = false;
					}

					echo '    Poznámka, o nasledujúcom externom súbore '.
						'sa nepodarilo zistiť všetky požadované '.
						'informácie:'.EOL.'      '.$file['url'].EOL;
				}
			}
	}

	if (null !== ($articles = $rheiaClass->getArticlesData()))
	{
		foreach ($articles as $article)
		{
			if (!is_array($article)) break;

			if (isSet($article['files']))
			{
				foreach ($article['files'] as $file)
				{
					if ($file['exists'])
					{
						foreach (explode(';', $downloadPath) as $path)
						{
							$pathName = (empty($path) ? '' :
								($path.'/')).$file['originalName'];

							if (file_exists($level.'downloads/'.$pathName))
							{
								$pathName = strtolower($pathName);
								if (isSet($allItems[$pathName]))
									$allItems[$pathName] = true;
								else
									$allItems[$pathName] = null;
								break;
							}
						}
					}
					else if (cannotResolveAsImage($file['originalName']))
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
								'.txa'.EOL;
							$putSourceInfo = false;
						}

						echo '    Chýbajúci súbor: '.
							$file['originalName'].EOL;
						++$inconsistencies;
					}
				}
			}

			if (isSet($article['attachment']))
			{
				foreach ($article['attachment'] as $i => $attachment)
				{
					$exists = false; $fileSize = null; $fileDate = null;
					RheiaMainClass::getFileInfo($attachment,
						$exists, $fileSize, $fileDate);

					if ($exists)
					{
						foreach (explode(';', $downloadPath) as $path)
						{
							$pathName = (empty($path) ? '' :
								($path.'/')).$attachment;

							if (file_exists($level.'downloads/'.$pathName))
							{
								$pathName = strtolower($pathName);
								if (isSet($allItems[$pathName]))
									$allItems[$pathName] = true;
								else
									$allItems[$pathName] = null;
								break;
							}
						}
					}
					else
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
								'.txa'.EOL;
							$putSourceInfo = false;
						}

						echo '    Chýbajúci súbor: '.$attachment.EOL;
						++$inconsistencies;
					}
				}
			}

			if (isSet($article['externFiles']))
				foreach ($article['externFiles'] as $file)
				{
					if (!$file['exists'])
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
								'.txa'.EOL;
							$putSourceInfo = false;
						}

						echo '    Chýbajúci externý súbor: '.
							$file['url'].EOL;
						++$inconsistencies;
					}
					elseif (empty($file['fileSize']) ||
						empty($file['fileDate']))
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
								'.txa'.EOL;
							$putSourceInfo = false;
						}

						echo '    Poznámka, o nasledujúcom externom súbore '.
							'sa nepodarilo zistiť všetky požadované '.
							'informácie:'.EOL.'      '.$file['url'].EOL;
					}
				}

			if (isSet($article['warnings']))
				foreach ($article['warnings'] as $warning)
				{
					if ($putSourceInfo)
					{
						echo EOL.'  Zdroj: '.$rheiaClass->getBaseName().
							'.txa'.EOL;
						$putSourceInfo = false;
					}
					echo '    Varovanie: '.$warning.EOL;
				}
		}
	}
}


function checkGalleryContent($alias)
{
	global /*$downloadLevel, */$galleryPath, $downloadPath,
		$allItems, $inconsistencies, $level;

	if (isSet($galleryPath))
	{
		// Po doriešení treba vymazať obmedzenie v katedrových
		// inconsistency.php: showResult(array('katedry/kXXX/galerie/'));
		echo 'Upozornenie! Načítavanie katedrových galérií '.
			'zatiaľ nie je doriešené.'.EOL;
		return false;
		// if (isSet($selectedItem))
		//	$gallery = new GalleryLoader($alias, $galleryPath, null, $level, false);
	}
	else
	{
		$gallery = new GalleryLoader($alias, null, null, $level, false);
	}

	if (!$gallery->load())
	{
		echo 'Chyba! Nepodarilo sa načítať galériu '.$alias.'.'.EOL;
		return false;
	}

	$files = $gallery->getFilesArray();
	$putSourceInfo = true;

	foreach ($files as $file)
	{
		$fileNotExists = true;

		foreach (explode(';', $downloadPath) as $path)
		{
			$pathName = (empty($path) ? '' : ($path.'/')).$file;

			if (file_exists($level.'downloads/'.$pathName))
			{
				$pathName = strtolower($pathName);
				if (isSet($allItems[$pathName]))
					$allItems[$pathName] = true;
				else
					$allItems[$pathName] = null;
				$fileNotExists = false;
				break;
			}
		}

		if ($fileNotExists && cannotResolveAsImage($file))
		{
			if ($putSourceInfo)
			{
				echo EOL.'  Zdroj: '.$alias.'.txg'.EOL;
				$putSourceInfo = false;
			}

			echo '    Chýbajúci súbor: '.$file.EOL;
			++$inconsistencies;
		}
	}

	return true;
}


function walkRheia($file, $label, $link)
{
	debugInfo("walkRheia($file, $label, $link)");

	global $siteContentConfig, $walkCategory,
		$articleSeparator, $rheiaClass, $touch;

	$alias = $file;
	for ($i = 0; isSet($siteContentConfig[$alias]['alias']) && $i < 10;
		++$i) $alias = $siteContentConfig[$alias]['alias'];

	if (isSet($siteContentConfig[$alias]) &&
		isSet($siteContentConfig[$alias]['type']))
		$type = $siteContentConfig[$alias]['type'];
		else $type = 'html';

	if ($alias !== $file && isSet($siteContentConfig[$alias]))
	{
		if (isSet($siteContentConfig[$alias]['parent']))
		{
			$link = $siteContentConfig[$alias]['parent'].'?'.$alias;
			$articleSeparator = '&';
		}
		else
		{
			$link = $alias;
			$articleSeparator = '?';
		}
	}

	if ('module' === $type) { /* ignore */ }
	elseif ('gallery' === $type &&
		checkGalleryContent($alias))
	{ /* gallery checked successfuly */ }
	else
	{
		$touch[$alias] = true;

		if (null === $rheiaClass)
			$rheiaClass = new RheiaMainClass($alias, false);
		else
			$rheiaClass->reset($alias);

		if ('articles' === $type)
			$rheiaClass->loadArticles();
		else
			$rheiaClass->loadHTML();

		checkRheiaContent();
	}
}


function walkMenu($structure)
{
	debugInfo("walkMenu($structure)");

	global $siteContentConfig, $walkCategory, $articleSeparator;

	foreach ($structure as $file => $item)
	{
		debugInfo("walkMenu-item($file)");

		if (empty($item)) { /* Separator */ }
		elseif (is_array($item))
		{
			if (!empty($item['hidden'])) continue;

			if (isSet($item['label']))
				$label = $item['label'];
			elseif (isSet($item['link']))
				$label = $item['link'];
			elseif (isSet($item['http']))
				$label = $item['http'];
			elseif (isSet($item['https']))
				$label = $item['https'];
			else $label = '';

			if (!is_numeric($file) && !isSet($item['http']) &&
				!isSet($item['https']) && !isSet($item['nosearch']) &&
				(!isSet($item['link']) || strpos($item['link'], '/')
					=== false))
			{
				if (isSet($item['link']))
				{
					$link = $item['link'];
					$articleSeparator = null;
				}
				elseif (isSet($siteContentConfig[$file]))
				{
					if (isSet($siteContentConfig[$file]['parent']))
					{
						$link = $siteContentConfig[$file]
							['parent'].'?'.$file;
						$articleSeparator = '&';
					}
					else
					{
						$walkCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $walkCategory.'?'.$file;
					$articleSeparator = '&';
				}

				walkRheia($file, $label, $link);
			}

			if (isSet($item['submenu']) &&
				(!isSet($siteContentConfig[$file]) ||
					!isSet($siteContentConfig[$file]['type']) ||
					'articles' !== $siteContentConfig[$file]['type'] ||
					!empty($siteContentConfig[$file]['reserved'])))
				walkMenu($item['submenu']);
		}
		else
		{
			if (!is_numeric($file))
			{
				if (isSet($siteContentConfig[$file]))
				{
					if (isSet($siteContentConfig[$file]['parent']))
					{
						$link = $siteContentConfig
							[$file]['parent'].'?'.$file;
						$articleSeparator = '&';
					}
					else
					{
						$walkCategory = $link = $file;
						$articleSeparator = '?';
					}
				}
				else
				{
					$link = $walkCategory.'?'.$file;
					$articleSeparator = '&';
				}

				walkRheia($file, $item, $link);
			}
		}
	}
}


function walkFile($file)
{
	debugInfo("walkFile($file)");

	global $siteContentConfig, $walkCategory, $articleSeparator;

	if (isSet($siteContentConfig[$file]))
	{
		if (isSet($siteContentConfig[$file]['parent']))
		{
			$link = $siteContentConfig[$file]['parent'].'?'.$file;
			$articleSeparator = '&';
		}
		else
		{
			$walkCategory = $link = $file;
			$articleSeparator = '?';
		}
	}
	else
	{
		$link = $walkCategory.'?'.$file;
		$articleSeparator = '&';
	}

	walkRheia($file, null, $link);
}


function walk()
{
	global $siteContentConfig, $siteStructure, $walkCategory,
		$articleSeparator, $rheiaClass, $touch, $inconsistencies,
		$downloadPath, $allItems, $level;

	$inconsistencies = 0;
	$walkCategory = 'index';
	$articleSeparator = null;
	$rheiaClass = null;
	$touch = array();

	if (isSet($siteStructure))
	{
		if (isSet($siteStructure['top-menu-before']))
			walkMenu($siteStructure['top-menu-before']);

		if (isSet($siteStructure['top-menu-after']))
			walkMenu($siteStructure['top-menu-after']);

		if (isSet($siteStructure['bottom-menu-before']))
			walkMenu($siteStructure['bottom-menu-before']);

		if (isSet($siteStructure['bottom-menu-after']))
			walkMenu($siteStructure['bottom-menu-after']);

		if (isSet($siteStructure['left-menu-before']))
			walkMenu($siteStructure['left-menu-before']);

		if (isSet($siteStructure['left-menu-after']))
			walkMenu($siteStructure['left-menu-after']);

		if (isSet($siteStructure['right-menu-before']))
			walkMenu($siteStructure['right-menu-before']);

		if (isSet($siteStructure['right-menu-after']))
			walkMenu($siteStructure['right-menu-after']);

		if (isSet($siteStructure['central-menu-before']))
			walkMenu($siteStructure['central-menu-before']);

		if (isSet($siteStructure['central-menu-after']))
			walkMenu($siteStructure['central-menu-after']);

		if (isSet($siteStructure['bottom-icons']))
			walkMenu($siteStructure['bottom-icons']);

		foreach ($siteStructure as $file => $menu)
		{
			if ('top-menu' != $file && 'top-menu-before' != $file &&
				'top-menu-after' != $file &&
				'bottom-menu' != $file && 'bottom-menu-before' != $file &&
				'bottom-menu-after' != $file &&
				'left-menu' != $file && 'left-menu-before' != $file &&
				'left-menu-after' != $file &&
				'right-menu' != $file && 'right-menu-before' != $file &&
				'right-menu-after' != $file &&
				'central-menu' != $file && 'central-menu-before' != $file &&
				'central-menu-after' != $file &&
				'bottom-icons' != $file)
			{
				$walkCategory = $file;
				$articleSeparator = '?';

				if (is_array($menu))
				{
					if (!is_numeric($file) && !isSet($menu['http']) &&
						!isSet($menu['https']) && !isSet($menu['nosearch']) &&
						(!isSet($menu['link']) || strpos($menu['link'], '/')
							=== false))
						walkRheia($file, $menu['label'], $file);

					if (isSet($menu['top-menu']))
						walkMenu($menu['top-menu']);

					if (isSet($menu['bottom-menu']))
						walkMenu($menu['bottom-menu']);

					if (isSet($menu['left-menu']))
						walkMenu($menu['left-menu']);

					if (isSet($menu['right-menu']))
						walkMenu($menu['right-menu']);

					if (isSet($menu['central-menu']))
						walkMenu($menu['central-menu']);
				}
				elseif (!is_numeric($file))
					walkRheia($file, $menu, $file);
			}
		}

		foreach ($siteContentConfig as $file => $config)
		{
			if (is_array($config) && empty($touch[$file]) &&
				empty($config['nosearch']))
			{
				$walkCategory = $file;
				$articleSeparator = '?';
				walkFile($file);
			}
		}
	}

	if (isSet($rheiaClass))
	{
		$putSourceInfo = true;

		if (null != ($objects = RheiaMainClass::getObjectsData()) &&
			isSet($objects['']))
		{
			$objects = $objects[''];

			if (isSet($objects['files']))
				foreach ($objects['files'] as $file)
				{
					if ($file['exists'])
					{
						foreach (explode(';', $downloadPath) as $path)
						{
							$pathName = (empty($path) ? '' :
								($path.'/')).$file['originalName'];

							if (file_exists($level.'downloads/'.$pathName))
							{
								$pathName = strtolower($pathName);
								if (isSet($allItems[$pathName]))
									$allItems[$pathName] = true;
								else
									$allItems[$pathName] = null;
								break;
							}
						}
					}
					else if (cannotResolveAsImage($file['originalName']))
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: «objekty»'.EOL;
							$putSourceInfo = false;
						}

						echo '    Chýbajúci súbor: '.$file['originalName'].EOL;
						++$inconsistencies;
					}
				}

			if (isSet($objects['externFiles']))
				foreach ($objects['externFiles'] as $file)
				{
					if (!$file['exists'])
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: «objekty»'.EOL;
							$putSourceInfo = false;
						}

						echo '    Chýbajúci externý súbor: '.$file['url'].EOL;
						++$inconsistencies;
					}
					elseif (empty($file['fileSize']) ||
						empty($file['fileDate']))
					{
						if ($putSourceInfo)
						{
							echo EOL.'  Zdroj: «objekty»'.EOL;
							$putSourceInfo = false;
						}

						echo '    Poznámka, o nasledujúcom externom súbore '.
							'sa nepodarilo zistiť všetky požadované '.
							'informácie:'.EOL.'      '.$file['url'].EOL;
					}
				}
		}
	}
}


function showResult($exclude = array())
{
	global $allItems, $inconsistencies;

	$needAcrobatVersion = array(
		'docx' => true, 'dotx' => true, 'doc' => true, 'dot' => true,
		'xlsx' => true, 'xltx' => true, 'xls' => true, 'xlt' => true,
		'ppsx' => true, 'pptx' => true, 'potx' => true, 'pps' => true,
		'ppt' => true, 'pot' => true, 'rtf' => true,

		'htm' => false, 'html' => false, 'elp' => false, 'sql' => false,
		'php' => false, 'css' => false, 'pdf' => false, 'zip' => false,
		'rar' => false, 'java' => false, 'class' => false, 'jar' => false,
		'imp' => false, 'gif' => false, 'png' => false, 'jpg' => false,
		'jpeg' => false, 'msi' => false, 'txt' => false, 'exe' => false,
		'bat' => false);

	echo EOL;

	foreach ($allItems as $pathName => $state)
	{
		if ($state === null)
		{
			echo '  Poznámka, nasledujúci súbor pravdepodobne pochádza '.
				'z iného umiestnenia:'.EOL.'    '.$pathName.EOL;
			// ++$inconsistencies;
		}
		else if ($state === false)
		{
			$show = true;
			foreach ($exclude as $hide)
			{
				if (0 === strpos($pathName, $hide))
				{
					$show = false;
					break;
				}
			}

			if ($show && !preg_match('~[\-\.]redirect$~i', $pathName))
			{
				echo '  Nepoužitý súbor: '.$pathName.EOL;
				++$inconsistencies;
			}
		}
		else
		{
			/** – keď je použitý, treba overiť…/
			$show = true;
			foreach ($exclude as $hide)
			{
				if (0 === strpos($pathName, $hide))
				{
					$show = false;
					break;
				}
			}
			if (!$show) continue;
			/**/

			$unknown = true;

			foreach ($needAcrobatVersion as $type => $need)
			{
				if (preg_match('~\.'.$type.'$~i', $pathName))
				{
					$unknown = false;

					if ($need)
					{
						$pathNameAcrobat = preg_replace('~\.'.$type.'$~i',
							'.pdf', $pathName);

						if (!isSet($allItems[$pathNameAcrobat]))
						{
							if (isSet($allItems[str_replace('/', '-',
								$pathNameAcrobat).'.redirect']) ||
								isSet($allItems[$pathNameAcrobat.'-redirect']))
							{
								echo '  (Našlo sa presmerovanie: '.
									$pathNameAcrobat.')'.EOL;
								continue;
							}

							$notFound = true; $pathName2 = null;

							if (preg_match('~([^/]+)$~',
								$pathNameAcrobat, $match))
							{
								// echo '    DEBUG: '.$match[1].EOL;
								foreach ($allItems as $pathName2 => $state2)
								{
									if (preg_match('~^'.$match[1].
										'$~i', $pathName2) ||
										preg_match('~/'.$match[1].
										'$~i', $pathName2))
									{
										$notFound = false;
										// echo '    DEBUG: '.$pathName2.EOL;
										break;
									}
								}
							}

							if ($notFound)
							{
								echo '  Acrobat verzia súboru '.
									'nebola nájdená: '.$pathName.EOL;
								++$inconsistencies;
							}
							else
							{
								echo '  Poznámka, Acrobat verzia súboru: '.
									$pathName.' bola nájdená na inej '.
									'lokalite.'.EOL.'    Pravdepodobne '.
									'nejde o verziu rovnakého dokumentu(!): '.
									$pathName2.EOL;
							}
						}
						/**else
						{
							echo '  DEBUG: Acrobat verzia súboru *bola* '.
								'nájdená: '.$pathName.EOL;
						}/**/
					}
					/**else
					{
						if ('pdf' != $type)
							echo '  DEBUG: Súbor nevyžaduje '.
								'Acrobat verziu: '.$pathName.EOL;
						// else
						// 	echo '  DEBUG: Toto je Acrobat verzia '.
						// 		'súboru: '.$pathName.EOL;
					}/**/
					break;
				}
			}

			if ($unknown)
			{
				echo '  Poznámka, nedokážem posúdiť, či je vyžadovaná '.
					'Acrobat verzia súboru neznámeho typu: '.$pathName.EOL;

			}
		}
	}

	if (0 == $inconsistencies)
		echo 'Neboli nájdené žiadne nekonzistencie.';
	else
		echo EOL.'Počet nájdených nekonzitencií: '.$inconsistencies.EOL;

	// global $debugInfo; echo EOL2.$debugInfo;
}

?>