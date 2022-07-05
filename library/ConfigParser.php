<?php

/**
 * Poznámka: nasledujúce dva informačné bloky sú formulované s použitím
 * terminológie PHP, pretože boli napísané v čase, keď bola definícia ponúk
 * spracúvaná „ručne“ (priamo do poľa PHP, bez použitia konfiguračných
 * súborov). To znamená, že nižšie uvedené informácie sa v skutočnosti
 * vzťahujú na produkt tejto triedy, nie na zdrojovú definíciu, ale zdrojová
 * definícia a produkt vykazujú viacero podobných znakov, preto sú informácie
 * stále vo veľkej miere použiteľné aj pri písaní zdrojovej definície ponuky.
 */

/**
 * Konfigurácia obsahuje zoznam vlastností pre jednotlivé identifikátory.
 * Dôležité je vedieť o jednom pravidle: identifikátor by nemal byť súčasne
 * typu „articles“ a mať vnorenú ponuku „submenu“. Ak to tak predsa
 * definujete, tak vnorené položky sa budú odkazovať na články…
 * 
 * Vlastnosti:
 * ===========
 * 
 *  alias  – položka bude aliasom iného identifikátora
 *  parent – položka bude vnorenou položkou zadaného rodičovského
 *           identifikátora
 *  type   – určuje typ položky (articles, html – predvolené, module)
 *  ✗ strict – nastavuje striktný výber článkov pre stanovenú kategóriu ✗
 * 
 * Ostatné vlastnosti definuje štruktúra premennej „siteStructure“.
 * Pozor! Aj keď je možné definovať typ „gallery“, galérie sú v skutočnosti
 * definované v príslušnom PHP skripte – zahrnutím odlišnej knižnice sídla.
 * (Väčšina skriptov sídla končí zahrnutím 'content.php', galéria končí
 * zahrnutím 'gallery.php'.)
 */

/**
 * Zmeny v stroji vyžiadali pridanie nasledujúcich vlastností:
 * 
 *  uri      – uri adresa položky typu „feature“ (v hornej časti dizajnu)
 *  nosearch – rovnaký význam ako v definícii siteStructure (nižšie)
 */

/**
 * Štruktúra určuje rozmiestnenie a hierarchiu položiek hornej, ľavej
 * a pravej ponuky. Filozofia štruktúry ponuky môže byť rôzna. Horná ponuka
 * môže, napríklad, obsahovať položky hlavnej ponuky a bočné ponuky môžu byť
 * vnímané ako vedľajšie… Alebo horná ponuka môže obsahovať skratky pre
 * hierarchiu bočných ponúk… Definícia pozostáva z nasledujúcich prvkov:
 * 
 *  «položka ponuky» – '«identifikátor»' => '«text položky»',
 *                   – '«identifikátor»' => array(«vlastnosti položky»),
 * 
 *  «vlastnosti položky» – 'label'  => '«text položky»',
 *                       – 'link'   => '«lokálny odkaz»',
 *                       – 'http'   => '«externý odkaz»',
 *                       – 'https'  => '«externý odkaz»',
 *                       – 'hidden' => true, // položka bude skrytá
 *                       – 'card'   => '«názov obrázka»', // položka bude
 *                                                        // grafická
 *                       – 'submenu' => array(«položky ponuky»),
 *                       – 'nosearch' => true, // položka bude vynechaná
 *                                             // z vyhľadávania
 * 
 * Nasledujúce identifikátory sú rezervované:
 * 
 *  top-menu            - horná ponuka
 *  top-menu-before     - horná ponuka (spoločné začiatočné položky)
 *  top-menu-after      - horná ponuka (spoločné koncové položky)
 *  bottom-menu         - dolná ponuka
 *  bottom-menu-before  - dolná ponuka (spoločné začiatočné položky)
 *  bottom-menu-after   - dolná ponuka (spoločné koncové položky)
 *  left-menu           - ľavá ponuka
 *  left-menu-before    - ľavá ponuka (spoločné začiatočné položky)
 *  left-menu-after     - ľavá ponuka (spoločné koncové položky)
 *  right-menu          - pravá ponuka
 *  right-menu-before   - pravá ponuka (spoločné začiatočné položky)
 *  right-menu-after    - pravá ponuka (spoločné koncové položky)
 *  central-menu        - centrálna ponuka
 *  central-menu-before - centrálna ponuka (spoločné začiatočné položky)
 *  central-menu-after  - centrálna ponuka (spoločné koncové položky)
 *  bottom-icons        - zoznam ikon na konci stránky v päte dizajnu
 * 
 */

class ConfigParser
{
	public static $contentPath = '../content/';
	public static $parsedPath = '../parsed/';

	private $baseName;
	private $sourceDate;

	private $siteContentConfig = array();
	private $siteStructure = array();
	private $errors = array();

	public function __construct(
		$baseName = '__basic-menu',
		$autoMerge = true)
	{
		$this->baseName = $baseName;
		$this->loadConfig();
		if ($autoMerge) $this->mergeConfig();
	}

	private function deleteConfig($item)
	{
		if (isSet($this->siteContentConfig[$item]))
			unset($this->siteContentConfig[$item]);
	}

	private function addConfig($item, $config, $value = null)
	{
		if (isSet($this->siteContentConfig[$item]))
		{
			if (!is_array($this->siteContentConfig[$item]))
				$this->siteContentConfig[$item] =
					array($this->siteContentConfig[$item]);

			if (null === $value)
				$this->siteContentConfig[$item][] = $config;
			elseif ('reserved' == $config)
			{
				$this->siteContentConfig[$item]
					['reserved'][$value] = true;
			}
			else
			{
				if (isSet($this->siteContentConfig[$item][$config]) &&
					'parent' != $config)
				{
					if (is_numeric($value) || is_string($value) || is_bool($value))
						$this->errors[] = 'Duplicate value: '.$item.
							'['.$config.'] = '.$value;
					else
						$this->errors[] = 'Duplicate value: '.$item.
							'['.$config.'] = '.print_r($value, true);
				}

				if (is_array($value))
					$this->siteContentConfig[$item][$config] = $value;
				elseif (preg_match('/^true *$/i', $value))
					$this->siteContentConfig[$item][$config] = true;
				elseif (preg_match('/^false *$/i', $value))
					$this->siteContentConfig[$item][$config] = false;
				elseif (preg_match('/^null *$/i', $value))
					$this->siteContentConfig[$item][$config] = null;
				else
					$this->siteContentConfig[$item][$config] = $value;
			}
		}
		else
		{
			if (null === $value)
				$this->siteContentConfig[$item] = $config;
			else
			{
				if (is_array($value))
					$this->siteContentConfig[$item][$config] = $value;
				elseif (preg_match('/^true *$/i', $value))
					$this->siteContentConfig[$item][$config] = true;
				elseif (preg_match('/^false *$/i', $value))
					$this->siteContentConfig[$item][$config] = false;
				elseif (preg_match('/^null *$/i', $value))
					$this->siteContentConfig[$item][$config] = null;
				else
					$this->siteContentConfig[$item][$config] = $value;
			}
		}
	}

	private function parse($source)
	{
		// global $siteContentConfig, $siteStructure;

		// Prepare the string (split to lines)
		$lines = explode('<br />', nl2br(file_get_contents($source)));

		// Trim whitespace
		foreach ($lines as $i => $line)
			$lines[$i] = preg_replace('/[ \r\n]+/',
				' ', rtrim(ltrim($line), ' '."\r\n"));

		$currentMenu = &$this->siteStructure;
		$currentItem = null; $menuID = null;
		$submenuInProgress = false; $defaultParent = null;

		// Parse objects definitions
		foreach ($lines as $line)
		{
			if (empty($line) || (';' === $line[0]))
			{ /* ignore empty lines and comments */ }

			elseif (preg_match('/^\@([-+\pL\pN]+) *$/ui', $line, $matches))
			{
				if (preg_match('/^end *$/i', $matches[1]))
				{
					$currentMenu = &$this->siteStructure;
					$currentItem = null; $menuID = null;
				}
				elseif (preg_match('/^end-submenu *$/i', $matches[1]))
				{
					if (!$submenuInProgress)
						$this->errors[] = 'No submenu definition '.
							'is currently active';
					else
					{
						if (null === $menuID)
							$currentMenu = &$this->siteStructure;
						else
							$currentMenu = &$this->siteStructure[$menuID];
						$currentItem = null;
						$submenuInProgress = false;
					}
				}
				elseif (preg_match('/^submenu *$/i', $matches[1]))
				{
					if ($submenuInProgress)
						$this->errors[] = 'A submenu definition is '.
							'already in active';
					else
					{
						if (null === $currentItem)
						{
							$this->errors[] = 'No item is active for: '.$line;
							continue;
						}

						if (!is_array($currentMenu[$currentItem]))
							$currentMenu[$currentItem] =
								array('label' => $currentMenu[$currentItem]);

						$currentMenu[$currentItem]['submenu'] = array();
						$currentMenu = &$currentMenu[$currentItem]['submenu'];
						$currentItem = null; $submenuInProgress = true;
					}
				}
				else
				{
					$menuID = $matches[1];
					$this->siteStructure[$menuID] = array();
					$currentMenu = &$this->siteStructure[$menuID];
					$currentItem = null; $submenuInProgress = false;
				}
			}
			elseif (preg_match('/^\$([-+\pL\pN]+): *(.*)$/ui', $line,
				$matches))
			{
				if (isSet($currentMenu[$matches[1]]))
					$this->errors[] = 'Duplicate item: '.$line;

				if (preg_match('/^rss *$/i', $matches[1]))
				{
					$currentItem = null;
					$this->addConfig('rss', $matches[2]);
				}
				elseif (preg_match('/^ical *$/i', $matches[1]))
				{
					$currentItem = null;
					$this->addConfig('ical', $matches[2]);
				}
				else
				{
					$currentItem = $matches[1];
					$currentMenu[$currentItem] = $matches[2];

					if ($submenuInProgress)
					{
						if (!preg_match('/^index *$/i', $matches[1]))
							$this->addConfig($currentItem, 'parent',
								$defaultParent);
					}
					else
					{
						$defaultParent = $currentItem;
						$this->addConfig($currentItem, 'parent', 'null');
					}
				}
			}
			elseif (preg_match('/^\$([-+\pL\pN]+) *$/ui', $line, $matches))
			{
				if (isSet($currentMenu[$matches[1]]))
					$this->errors[] = 'Duplicate item: '.$line;

				if (preg_match('/^rss *$/i', $matches[1]))
				{
					$this->errors[] = 'Invalid RSS definition';
				}
				elseif (preg_match('/^ical *$/i', $matches[1]))
				{
					$this->errors[] = 'Invalid iCal definition';
				}
				else
				{
					$currentItem = $matches[1];

					if ($submenuInProgress)
					{
						if (!preg_match('/^index *$/i', $matches[1]))
							$this->addConfig($currentItem, 'parent',
								$defaultParent);
					}
					else
					{
						$defaultParent = $currentItem;
						$this->addConfig($currentItem, 'parent', 'null');
					}
				}
			}
			elseif (preg_match('/^-+ *$/', $line)) // separator
			{
				$currentItem = null;
				$currentMenu[] = null;
			}
			elseif (preg_match('/^\*(.*)$/', $line, $matches)) // header text
			{
				$currentItem = null;
				$currentMenu[] = $matches[1];
			}
			elseif (preg_match('/^#([-+\pL\pN]+): *(.*)$/ui', $line,
				$matches))
			{
				if (null === $currentItem)
					$this->errors[] = 'No item is active for: '.$line;
				elseif (preg_match('/^en *$/i', $matches[1]))
					$this->addConfig($currentItem, 'en', $matches[2]);
				elseif (preg_match('/^sk *$/i', $matches[1]))
					$this->addConfig($currentItem, 'sk', $matches[2]);
				/*
				elseif (preg_match('/^cz *$/i', $matches[1]))
					$this->addConfig($currentItem, 'cz', $matches[2]);
				elseif (preg_match('/^de *$/i', $matches[1]))
					$this->addConfig($currentItem, 'de', $matches[2]);
				*/
				elseif (preg_match('/^uri *$/i', $matches[1]))
					$this->addConfig($currentItem, 'uri', $matches[2]);
				elseif (preg_match('/^alias *$/i', $matches[1]))
					$this->addConfig($currentItem, 'alias', $matches[2]);
				elseif (preg_match('/^parent *$/i', $matches[1]))
					$this->addConfig($currentItem, 'parent', $matches[2]);
				elseif (preg_match('/^poster *$/i', $matches[1]))
					$this->addConfig($currentItem, 'poster', $matches[2]);
				elseif (preg_match('/^type *$/i', $matches[1]))
				{
					if (preg_match('/^html *$/i', $matches[2]))
						$this->addConfig($currentItem, 'type', 'html');
					elseif (preg_match('/^articles *$/i', $matches[2]))
						$this->addConfig($currentItem, 'type', 'articles');
					elseif (preg_match('/^gallery *$/i', $matches[2]))
						$this->addConfig($currentItem, 'type', 'gallery');
					elseif (preg_match('/^module *$/i', $matches[2]))
						$this->addConfig($currentItem, 'type', 'module');
					else
					{
						$this->errors[] = 'Unknown type: '.$line;
						$this->addConfig($currentItem, 'type', $matches[2]);
					}
				}
				elseif (preg_match('/^sublabel *$/i', $matches[1]))
				{
					if (!empty($matches[2]) && '<' === $matches[2][0])
					{
						$sublabel = '<small>('.substr($matches[2],
							1).')</small>';
						$separator = ' ';
					}
					else
					{
						$sublabel = '<small>('.$matches[2].')</small>';
						$separator = '<br />';
					}

					if (!isSet($currentMenu[$currentItem]))
						$currentMenu[$currentItem] = $sublabel;
					elseif (!is_array($currentMenu[$currentItem]))
						$currentMenu[$currentItem] .= $separator.$sublabel;
					elseif (!isSet($currentMenu[$currentItem]['label']))
						$currentMenu[$currentItem]['label'] .= $sublabel;
					else
						$currentMenu[$currentItem]['label'] .=
							$separator.$sublabel;
				}
				elseif (preg_match('/^highlight *$/i', $matches[1]))
				{
					$this->addConfig($currentItem, 'highlight',
						preg_split('/,\s+/', $matches[2], -1,
							PREG_SPLIT_NO_EMPTY));
				}
				else
				{
					if (preg_match('/^true *$/i', $matches[2]))
						$matches[2] = true;
					elseif (preg_match('/^false *$/i', $matches[2]))
						$matches[2] = false;
					elseif (preg_match('/^null *$/i', $matches[2]))
						$matches[2] = null;

					if (!isSet($currentMenu[$currentItem]))
						$currentMenu[$currentItem] = array();
					elseif (!is_array($currentMenu[$currentItem]))
						$currentMenu[$currentItem] =
							array('label' => $currentMenu[$currentItem]);

					if (isSet($currentMenu[$currentItem][$matches[1]]))
						$this->errors[] = 'Duplicate attribute: '.$line;

					if (preg_match('/^nosearch *$/i', $matches[1]))
					{
						$this->addConfig($currentItem, 'nosearch', true);
						$currentMenu[$currentItem]['nosearch'] = $matches[2];
					}
					elseif (preg_match('/^deleteConfig *$/i', $matches[1]))
						$this->deleteConfig($currentItem);
					else
					{
						if (preg_match('/^https? *$/i', $matches[1]))
							$this->deleteConfig($currentItem);
						$currentMenu[$currentItem][$matches[1]] = $matches[2];
					}
				}
			}
			elseif (preg_match('/^#([-+\pL\pN]+) *$/ui', $line, $matches))
			{
				if (null === $currentItem)
					$this->errors[] = 'No item is active for: '.$line;
				elseif (preg_match('/^(hidden|nosearch|deleteConfig) *$/i',
					$matches[1], $match))
				{
					if (!isSet($currentMenu[$currentItem]))
						$currentMenu[$currentItem] = array();
					elseif (!is_array($currentMenu[$currentItem]))
						$currentMenu[$currentItem] =
							array('label' => $currentMenu[$currentItem]);

					$match[1] = strtolower($match[1]);

					if ('hidden' == $match[1])
						$currentMenu[$currentItem]['hidden'] = true;
					elseif ('nosearch' == $match[1])
					{
						$this->addConfig($currentItem, 'nosearch', true);
						$currentMenu[$currentItem]['nosearch'] = true;
					}
					elseif ('deleteconfig' == $match[1])
						$this->deleteConfig($currentItem);
				}
				elseif (preg_match('/^parent *$/i', $matches[1], $match))
					$this->addConfig($currentItem, 'parent', 'null');
				else
					$this->errors[] = 'Unknown special attribute: '.$line;
			}
			else
			{
				$this->errors[] = 'Syntax error: '.$line;
			}
		}

		foreach ($this->siteStructure as $file => $menu)
		{
			if (is_array($menu))
			{
				if ('top-menu' == $file || 'top-menu-before' == $file ||
					'top-menu-after' == $file ||
					'bottom-menu' == $file || 'bottom-menu-before' == $file ||
					'bottom-menu-after' == $file ||
					'left-menu' == $file || 'left-menu-before' == $file ||
					'left-menu-after' == $file ||
					'right-menu' == $file || 'right-menu-before' == $file ||
					'right-menu-after' == $file ||
					'central-menu' == $file || 'central-menu-before' == $file ||
					'central-menu-after' == $file ||
					'bottom-icons' == $file)
				{
					foreach ($menu as $file2 => $menu2)
					{
						if (is_array($menu2))
						{
							if (isSet($menu2['http']) ||
								isSet($menu2['https']) ||
								isSet($menu2['nosearch']) ||
								(isSet($menu2['link']) &&
									false !== strpos($menu2['link'], '/')))
							{
								$this->deleteConfig($file2);
							}
						}
					}
				}
				elseif (isSet($menu['http']) ||
					isSet($menu['https']) ||
					isSet($menu['nosearch']) ||
					(isSet($menu['link']) &&
						false !== strpos($menu['link'], '/')))
				{
					$this->deleteConfig($file);
				}
			}
		}

		foreach ($this->siteContentConfig as $item => $definition)
		{
			if (is_array($definition))
			{
				if (isSet($definition['parent']))
				{
					if (isSet($definition['type']))
					{
						if (null !== $definition['parent'])
						{
							if (isSet($this->siteContentConfig[
								$definition['parent']]) &&
								isSet($this->siteContentConfig[
								$definition['parent']]['type'])
								/*&& ('html' ... || 'articles' ==
									$this->siteContentConfig[
										$definition['parent']]['type'])*/)
							{
								$this->addConfig($definition['parent'],
									'reserved', $item);
							}
						}
					}
					elseif (isSet($this->siteContentConfig[
						$definition['parent']]) &&
						isSet($this->siteContentConfig[
							$definition['parent']]['type']) &&
						'articles' == $this->siteContentConfig[
							$definition['parent']]['type'])
					{
						$this->deleteConfig($item);
					}
				}
			}
		}
	}

	private function loadConfig()
	{
		// global $siteContentConfig, $siteStructure;
		$contentPath = ConfigParser::$contentPath;
		$parsedPath = ConfigParser::$parsedPath;
		$level = '';

		for ($i = 0; $i < 25; ++$i)
		{
			if (file_exists($contentPath))
			{
				if (file_exists($parsedPath))
				{
					$source = $contentPath.$level.$this->baseName.'.txm';
					$parsed = $parsedPath.$level.$this->baseName.'-m.php';

					if (file_exists($source))
					{
						$this->sourceDate = filemtime($source);

						if (file_exists($parsed) && ($this->sourceDate <=
							filemtime($parsed))) { include './'.$parsed; }
						else
						{
							$this->parse($source);
							$content = '<'.'?php'."\r\n\r\n";
							$content .= '$this->siteContentConfig = '.
								var_export($this->siteContentConfig, true);
							$content .= ';'."\r\n\r\n";
							$content .= '$this->siteStructure = '.
								var_export($this->siteStructure, true);

							if (!empty($this->errors))
							{
								$content .= ';'."\r\n\r\n";
								$content .= '$this->errors = '.
									var_export($this->errors, true);
							}

							$content .= ';'."\r\n\r\n".'?'.'>';
							file_put_contents($parsed, $content, LOCK_EX);
						}

						return;
					}

					$level .= '../';
				}
				else $parsedPath = '../'.$parsedPath;
			}
			else $contentPath = '../'.$contentPath;
		}
	}


	public function mergeConfig()//(&$siteContentConfig, &$siteStructure)
	{
		global $siteContentConfig, $siteStructure;

		if (!isSet($siteContentConfig) || !is_array($siteContentConfig))
			$siteContentConfig = array();
		if (!isSet($siteStructure) || !is_array($siteStructure))
			$siteStructure = array();

		$siteContentConfig = $siteContentConfig + $this->siteContentConfig;
		$siteStructure = $siteStructure + $this->siteStructure;
	}

	public static function procesHighlights()
	{
		global $siteContentConfig, $siteStructure, $selectedItem, $category;

		$alias = isSet($selectedItem) ? $selectedItem : $category;
		for ($i = 0; isSet($siteContentConfig[$alias]['alias']) &&
			$i < 10; ++$i) $alias = $siteContentConfig[$alias]['alias'];

		// $siteContentConfig['debug'] = array($alias);

		if (isSet($siteContentConfig[$alias]))
		{
			$item = $siteContentConfig[$alias];

			if (isSet($item['highlight']))
				foreach ($item['highlight'] as $highlight)
				{
					if (!isSet($siteContentConfig[null]) ||
						!is_array($siteContentConfig[null]))
						$siteContentConfig[null] = array();

					if (isSet($siteContentConfig[null][$highlight]))
					{
						if (is_array($siteContentConfig[null][$highlight]))
							$siteContentConfig[null][$highlight]
								['active'] = true;
						else
							$siteContentConfig[null][$highlight] =
								array($siteContentConfig[null][$highlight],
									'active' => true);
					}
					else
						$siteContentConfig[null][$highlight] =
							array('active' => true);
				}
		}
	}


	public function getErrors() { return $this->errors; }
}

if (isSet($contentPath)) ConfigParser::$contentPath = $contentPath;
if (isSet($parsedPath))  ConfigParser::$parsedPath = $parsedPath;

?>