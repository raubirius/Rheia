<?php

class GalleryLoader
{
	// public static $contentPath = '../content/';
	// public static $parsedPath = '../parsed/';

	private $baseName = null;
	private $imgGetPath = null;
	private $navigatePath = null;
	private $downLevel = null;

	public function __construct($baseName = null, $imgGetPath = null,
		$navigatePath = null, $downLevel = '../', $load = true)
	{
		$this->baseName = $baseName;
		$this->imgGetPath = $imgGetPath;
		if (empty($navigatePath))
			$this->navigatePath = $imgGetPath;
		else
			$this->navigatePath = $navigatePath;
		$this->downLevel = $downLevel;
		if ($load && $this->load()) $this->show();
	}


	public function load()
	{
		$source = RheiaMainClass::$contentPath.$this->baseName.'.txg';
		$parsed = RheiaMainClass::$parsedPath.$this->baseName.'-g.php';
		$mustRevalidate = !file_exists($parsed) ||
			(filemtime($source) > filemtime($parsed));

		if ($mustRevalidate)
		{
			// Default gallery
			$this->definition = array(
				'ID' => '',
				'URI' => empty($this->navigatePath) ?
					'galeria' : $this->navigatePath,
				'imgSep' => '?',
				'filePath' => '../downloads/fakulta/galeria',
				'imagePath' => 'galeria',
				'cssNázov' => null,
				'cssOpis' => null,
				'cssPopis' => array(),

				// Názvy asociatívnych indexov vychádzajú z kľúčových slov
				// syntaxe súboru *.txg
				'názov' => 'Galéria',
				'opis' => '',
				'počet' => 0,
				'obrázok' => array(),
				'popis' => array(),
				'alias' => array());

			if (!file_exists($source))
			{
				include '404.php';
				echo EOL.'<!-- Definícia galérie nebola nájdená. ('.
					$this->baseName.') -->'.EOL;
				return false;
			}

			// Prepare the string (split to lines)
			$lines = explode('<br />', nl2br(file_get_contents($source)));
			$this->definition['počet'] = 0;
			$count = 1;

			if (!empty($this->imgGetPath))
			{
				$this->definition['ID'] = $this->baseName.'&';
				$this->definition['URI'] = 'katedry/'.
					$this->navigatePath.'?'.$this->baseName;
				$this->definition['imgSep'] = '&';
				$this->definition['filePath'] =
					$this->downLevel.'../downloads/katedry/'.
					$this->imgGetPath.'/'.$this->baseName;
				$this->definition['imagePath'] =
					$this->imgGetPath.'/'.$this->baseName;
			}

			// Trim whitespace
			foreach ($lines as $i => $line)
				$lines[$i] = preg_replace('/[ \r\n]+/',
					' ', rtrim(ltrim($line), ' '.EOL));

			$galleryRheiaClass = new RheiaMainClass();
			RheiaMainClass::loadObjects();

			// Parse gallery definition
			foreach ($lines as $line)
			{
				if (empty($line) || ';' === $line[0])
				{
					// Ignore comments (and empty lines)
					continue;
				}

				if (preg_match('/^(\pL+)\[\]: *(.*)$/ui', $line, $matches))
				{
					$matches[1] = mb_strtolower($matches[1], 'UTF-8');
					if ('obrázok' == $matches[1] ||
						'popis' == $matches[1] ||
						'alias' == $matches[1])
					{
						if ('popis' == $matches[1])
							$galleryRheiaClass->simpleHTML($matches[2],
								$this->baseName);

						if (isSet($this->definition[$matches[1]][$count]))
							++$count;
						if ($this->definition['počet'] < $count)
							$this->definition['počet'] = $count;
						$this->definition[$matches[1]][$count] = $matches[2];
					}
				}
				elseif (preg_match('/^(\pL+)\[([0-9]+)\]: *(.*)$/ui',
					$line, $matches))
				{
					$matches[1] = mb_strtolower($matches[1], 'UTF-8');
					if ('obrázok' == $matches[1] ||
						'popis' == $matches[1] ||
						'alias' == $matches[1])
					{
						$count = (int)$matches[2];
						if ($this->definition['počet'] < $count)
							$this->definition['počet'] = $count;

						if ('popis' == $matches[1])
							$this->definition['cssPopis'][$count] =
								$galleryRheiaClass->simpleHTML($matches[3],
								$this->baseName);

						$this->definition[$matches[1]][$count] = $matches[3];
					}
				}
				elseif (preg_match('/^(\pL+): *(.*)$/ui', $line, $matches))
				{
					$matches[1] = mb_strtolower($matches[1], 'UTF-8');
					if ('názov' == $matches[1] || 'opis' == $matches[1] ||
						'počet' == $matches[1])
					{
						if ('názov' == $matches[1])
							$this->definition['cssNázov'] =
								$galleryRheiaClass->simpleHTML($matches[2],
								$this->baseName);
						elseif ('opis' == $matches[1])
							$this->definition['cssOpis'] =
							$galleryRheiaClass->simpleHTML($matches[2],
								$this->baseName);

						$this->definition[$matches[1]] = $matches[2];
						$this->definition[$matches[1]] = $matches[2];
					}
					elseif ('zdroj' == $matches[1])
					{
						$this->definition['filePath'] =
							$this->downLevel.'../downloads/katedry/'.
							$this->imgGetPath.'/'.$matches[2];
						$this->definition['imagePath'] =
							$this->imgGetPath.'/'.$matches[2];
					}
					elseif ('jazyk' == $matches[1] && preg_match(
						'/^([^:]+):\s*(.+)$/', $matches[2], $match))
					{
						if (empty($this->definition['lang']))
							$this->definition['lang'] = array();
						$this->definition['lang'][$match[1]] = $match[2];
					}
				}
			}

			$content = '<'.'?php'.EOL2;
			$content .= '$this->definition = '.
				var_export($this->definition, true);
			$content .= ';'.EOL2.'?'.'>';

			file_put_contents($parsed, $content, LOCK_EX);
		}
		else
		{
			include './'.$parsed;
		}

		return true;
	}


	private function imageNumber($alias)
	{
		if (is_numeric($alias)) return (int)$alias;
		if ($key = array_search($alias, $this->definition['alias']))
			return $key;
		return null;
	}

	private function imageAlias($number)
	{
		if (isSet($this->definition['alias'][$number]))
			return $this->definition['alias'][$number];
		return $number;
	}

	private function imageName($i, $path = null, $argument = 'w=620|h=480')
	{
		if (isSet($this->definition['obrázok'][$i]))
			$fileName = $this->definition['obrázok'][$i];
		else
			$fileName = sprintf('%04d', $i).'.jpeg';

		if (isSet($argument))
			$fileName .= '&'.$argument;

		if (!empty($path) && file_exists($this->definition
			['filePath'].'/'.$path.'/'.$fileName))
			$imagePath = '/'.$path.'/';
		else
			$imagePath = '/';

		return $imagePath.$fileName;
	}


	public function show()
	{
		$content = '<h1'.(empty($this->definition['cssNázov']) ?
			' style="text-align: center;"' :
				$this->definition['cssNázov']).'><a href="/'.
			$this->definition['URI'].'">'.($title =
				$this->definition['názov']).'</a></h1>'.EOL;

		global $params, $style, $javaScript1, $langPostfix;

		if (isSet($params[2]))
		{
			$current = $this->imageNumber($params[2]);
			if (null === $current) unset($current);
		}
		else if (isSet($params[1]))
		{
			$current = $this->imageNumber($params[1]);
			if (null === $current) unset($current);
		}

		if (isSet($current) && $current >= 1 &&
			$current <= $this->definition['počet'])
		{
			$content .= EOL;

			if ($this->definition['počet'] > 1)
			{
				if ($current <= 1) $prev = $this->definition['počet'];
				else $prev = $current - 1;
				if ($current >= $this->definition['počet']) $next = 1;
				else $next = $current + 1;
			}
			else
			{
				$prev = $next = null;
			}

			$style .= 'a.prev,'.EOL.'a.next'.EOL.'{'.EOLT.'color: #aaa;'.
				EOLT.'font-size: 30px;'.EOL.'}'.EOL.'a.prev:hover, '.
				'a.next:hover,'.EOL.'a.prev:focus, a.next:focus,'.
				EOL.'a.prev:active, a.next:active'.EOL.'{'.EOLT.
				'color: #888;'.EOLT.'text-decoration: none;'.EOL.'}'.EOL.
				'img.detail'.EOL.'{'.EOLT.'margin: 6px;'.EOLT.'padding: 2px;'.
				EOLT.'border: 1px solid #aaa;'.EOL.TAB.'max-width: 620px;'.
				EOLT.'max-height: 480px;'.EOL.'}'.EOL.'div.detail'.EOL.'{'.
				EOLT.'font-size: 14px;'.EOLT.'font-style: italic;'.EOLT.
				'margin-bottom: 20px;'.EOL.'}'.EOL.'img.ukazka'.EOL.'{'.EOLT.
				'margin: 6px;'.EOLT.'padding: 2px;'.EOLT.
				'border: 1px solid #aaa;'.EOLT.'max-height: 90px;'.
				EOL.'}'.EOL;

			// Image date
			$image = $this->definition['filePath'].$this->
				imageName($current);

			if (file_exists($image))
			{
				$imageDate = date('j.n.Y', filemtime($image));
				$content = '<table style="width: 100%">'.EOL.
					'<tr><td colspan="'.(1 + $prev + $next).
					'"><div style="float: right; padding-right: '.
					'18px"><em><small>('.$imageDate.')</small></em>'.
					'</div>'.$content.'<div style="clear: '.
					'both"></div></td></tr>';
			}
			else $content .= '<table style="width: 100%">'.EOL;

			$content .= TAB.'<tr>'.EOL;

			if ($prev) $content .= '<td><a href="?'.$this->
				definition['ID'].$this->imageAlias($prev).
				'" class="prev">«</a></td>'.EOL;

			$content .= '<td style="width: 100%; text-align: '.
				'center"><a href="'.($next ? ('?'.$this->definition
					['ID'].$this->imageAlias($next)) : '/'.$this->
					definition['URI']).
				'"><img src="/image?'.$this->definition['imagePath'].
				$this->imageName($current).'" alt="" class="detail"/></a>';

			if (isSet($this->definition['popis'][$current]))
				$content .= '<div class="detail"'.
					(empty($this->definition['cssPopis'][$current]) ? '' :
						$this->definition['cssPopis'][$current]).'>'.
					$this->definition['popis'][$current].'</div>';

			$content .= '</td>';

			if ($next) $content .= EOL.'<td><a href="?'.$this->
				definition['ID'].$this->imageAlias($next).
					'" class="next">»</a></td>';

			$content .= '</tr>'.EOL;

			if ($prev || $next)
			{
				$javaScript1 .= EOL.'document.onkeydown = function(e)'.
					EOL.'{'.EOLT.'if (!e) e = window.event;'.EOLT.
					'switch (e.keyCode)'.EOLT.'{'.EOL;

				if ($prev) $javaScript1 .= TAB2.
					'case 37: window.location = \'?'.
					$this->definition['ID'].$this->imageAlias($prev).
					'\'; break;'.EOL;
					// 'case 37: pushState(null, \''.$this->definition['ID'].
					// $this->imageAlias($prev).'\'); break;'.EOL;

				if ($next) $javaScript1 .= TAB2.
					'case 39: window.location = \'?'.
					$this->definition['ID'].$this->imageAlias($next).
					'\'; break;'.EOL;
					// 'case 39: pushState(null, \''.$this->definition['ID'].
					// $this->imageAlias($next).'\'); break;'.EOL;

				$javaScript1 .= TAB2.
					'case 27: window.location = \'/'.
						$this->definition['URI'].'\';'.
						// ' console.log(\'/'.
						// 	$this->definition['URI'].'\'); '.
						' return false;'.EOL;

				$javaScript1 .= TAB.'}'.EOL.'};'.EOL2;

				// $javaScript1 .= TAB.'onpopstate = function(event) { '.
				// 	'reloadPage(location); };'.EOL;

				$content .= '<tr><td colspan="'.(1 + $prev + $next).
					'" style="text-align: center">';

				if ($this->definition['počet'] < 5)
					for ($i = 1; $i <= $this->definition['počet']; ++$i)
						$content .= '<a href="?'.$this->definition['ID'].
							$this->imageAlias($i).'"><img src="/image?'.
							$this->definition['imagePath'].$this->
							imageName($i, 'ukazka', 'h=90').'" alt="" '.(isSet(
							$this->definition['popis'][$i]) ? ' title="'.
							RheiaMainClass::filterHTML($this->definition
								['popis'][$i]).'"' : '').
							' class="ukazka" /></a>';
				else
					for ($j = -2; $j <= 2; ++$j)
					{
						$i = $current + $j;
						if ($i < 1) $i += $this->definition['počet'];
						if ($i > $this->definition['počet'])
							$i -= $this->definition['počet'];
						$content .= '<a href="?'.$this->definition['ID'].
							$this->imageAlias($i).'"><img src="/image?'.
							$this->definition['imagePath'].$this->
							imageName($i, 'ukazka', 'h=90').'" alt="" '.(isSet(
							$this->definition['popis'][$i]) ? ' title="'.
							RheiaMainClass::filterHTML($this->definition
								['popis'][$i]).'"' : '').
							' class="ukazka" /></a>';
					}

				$content .= '</td></tr>'.EOL;
			}

			$content .= '</table>'.EOL;

			echo $content;

			if (isSet($this->definition['lang']) &&
				is_array($this->definition['lang']))
			{
				foreach ($this->definition['lang'] as $lang => $uri)
					$this->definition['lang'][$lang] .=
						$this->definition['imgSep'].
							$this->imageAlias($current);

				$langPostfix = $this->definition['lang'];
			}

			$javaScript1 .=
				'var topmost = getElement(\'topmost\');'.EOL2.
				'topmost.innerHTML = \'<div>'.
					addcslashes($content, '\\'.EOL).'</div>\';'.EOL2.
				'showItem(\'topmost\');'.EOL2;
		}
		else
		{
			if (!empty($this->definition['opis']))
				$content .= '<p'.(empty($this->definition['cssOpis']) ?
					' style="text-align: center;"' :
					$this->definition['cssOpis']).'>'.
					$this->definition['opis'].'</p>'.EOL;

			$content .= EOL;

			// Whole gallery
			echo $content;

			$style .= 'img.ukazka'.EOL.'{'.EOLT.
				'margin: 6px;'.EOLT.'padding: 2px;'.EOLT.
				'border: 1px solid #aaa;'.EOLT.'max-height: '.
				'90px;'.EOL.'}'.EOL;

			echo '<p style="text-align: center">'.EOL;

			for ($i = 1; $i <= $this->definition['počet']; ++$i)
				echo '<a href="?'.$this->definition['ID'].$this->
					imageAlias($i).'"><img src="/image?'.$this->
					definition['imagePath'].$this->
					imageName($i, 'ukazka', 'h=90').'" alt="" '.(isSet($this->
					definition['popis'][$i]) ? ' title="'.RheiaMainClass::
						filterHTML($this->definition['popis'][$i]).'"' : '').
					' class="ukazka" /></a>';

			echo '</p>'.EOL;

			if (isSet($this->definition['lang']) &&
				is_array($this->definition['lang']))
				$langPostfix = $this->definition['lang'];
		}
	}


	public function getFilesArray()
	{
		$files = array();
		for ($i = 1; $i <= $this->definition['počet']; ++$i)
		{
			$files[] = $this->definition['imagePath'].
				$this->imageName($i);
			$files[] = $this->definition['imagePath'].
				$this->imageName($i, 'ukazka', 'h=90');
		}
		return $files;
	}

	public function search()
	{
		RheiaMainClass::$searchIn =
			RheiaMainClass::transliterate(RheiaMainClass::
			filterHTML(RheiaMainClass::filterObjects(
				$this->definition['názov'].' '.$this->definition['opis'])));
		$rate = RheiaMainClass::getSearchRate();

		if ($rate)
		{
			// Title, Preview, Link, Rate, Date
			RheiaMainClass::$searchResults[] = array(
				RheiaMainClass::filterHTML($this->definition['názov']),
				null, '/'.$this->definition['URI'], $rate, 0);
		}

		for ($i = 1; $i <= $this->definition['počet']; ++$i)
		{
			if (!isSet($this->definition['popis'][$i])) continue;

			// replaceObjects
			RheiaMainClass::$searchIn = RheiaMainClass::transliterate(RheiaMainClass::
				filterHTML(RheiaMainClass::filterObjects($this->definition
					['popis'][$i])));
			$rate = RheiaMainClass::getSearchRate();

			if ($rate)
			{
				// replaceObjects
				RheiaMainClass::$searchResults[] =
					array(RheiaMainClass::filterHTML(
					$this->definition['názov'].' » '.$this->definition
					['popis'][$i]), null, '/'.$this->definition['URI'].
					$this->definition['imgSep'].$this->imageAlias($i),
					$rate, 0);
			}
		}
	}
}

// if (isSet($contentPath)) GalleryLoader::$contentPath = $contentPath;
// if (isSet($parsedPath))  GalleryLoader::$parsedPath = $parsedPath;

?>