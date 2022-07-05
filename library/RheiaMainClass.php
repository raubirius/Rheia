<?php

/**
 * Do NOT change any default values here. To modify the values,
 * use global variables in your configuration PHP file.
 */

include_once 'replace-table.php';
// include_once 'runPhp.php';

define('gmtdate_format', 'D, d M Y H:i:s +0100');
define('datetime_format', 'j. n. Y H:i:s');
define('date_format', 'j. n. Y');
define('icalstamp_format', 'Ymd\\THis');
define('icaldate_format', 'Ymd');


// define('check_extern_interval', 7200);


/* --- * /
$my_error_handler_debug_info = null;

function my_error_handler($errno, $errstr, $errfile, $errline)
{
	// This error code is not included in error_reporting
	if (!(error_reporting() & $errno)) return;
	global $my_error_handler_debug_info;

	echo '<p class="error"><b>Error #'.$errno.'</b>: '.$errstr.'<br />'.EOL.
		'  Line: '.$errline.', file '.$errfile.', PHP '.PHP_VERSION.
		' ('.PHP_OS.')';

	if (!empty($my_error_handler_debug_info))
	{
		if (is_array($my_error_handler_debug_info))
		{
			echo '</p>'.EOL.'<pre>';
			var_dump($my_error_handler_debug_info);
			echo '</pre>'.EOL.'<p>';
		}
		else echo ': '.$my_error_handler_debug_info;

		$my_error_handler_debug_info = null;
	}

	echo '</p>'.EOL;

	// Don’t execute PHP internal error handler
	return true;
};

set_error_handler('my_error_handler');
/* --- */


class RheiaMainClass
{
	/**
	 * Do NOT change any default values here. To modify the values,
	 * use global variables in your configuration PHP file.
	 */

	// for debug search „mustRevalidate“
	public static $protocol = 'http';
	public static $domain = 'pdf.truni.sk';
	public static $contentPath = '../content/';
	public static $parsedPath = '../parsed/';
	public static $iconsPath = 'design/';
	public static $designFilesPath = '../design/';
	public static $siteSectionPath = '';
	public static $externDownParsedPath = '../parsed/externdown/';
	public static $externalLinkIcon = 'design/null.gif';
	public static $objectsFileBaseName = '__objects';
	public static $downloadScript = 'download';
	public static $imageScript = 'image';
	public static $icalScript = 'calendar';
	public static $mailType = 'paragraph';
	public static $mailSize = array('paragraph' => 11, 'table' => 8);
	public static $mailScript = 'mail';
	public static $checkExtern = true;

	public static $rssCore = null;
	public static $rssTitle = null;
	public static $rssDescription = null;
	public static $currentTime = null;
	public static $downloadPath = null;
	public static $downloadLevel = null;
	public static $downloadSubfolder = '../downloads';
	public static $internalRedirects = null;

	public static $searchIn = null;
	public static $searchString = null;
	public static $searchPhrase = null;
	public static $searchWords = null;
	public static $searchCount = null;
	public static $searchResults = null;

	public static $icalProdID = null;
	public static $icalTimezone = null;
	public static $icalDefaultLanguage = null;
	// public static $icalDefaultCategory = null;
	// public static $icalDefaultSummary = null;

	public static $icalUIDPostfix = 'calendar@pdf.truni.sk';

	private static $cropTextID = ''; // ‼Warning‼ Emergency solution… Not the best, not any right, but necessary at the moment… (2021-08-12)
	private static $cropTextIDNo = 0;

	private static $templateInCommentRegex =
		'/;[  	]*#Šablóna:\s*([\pL\pN]+([-+]*[\pL\pN]*)*)(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?\n?/ui';
	private static $templateRegex =
		'/#Šablóna:\s*([\pL\pN]+([-+]*[\pL\pN]*)*)(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?(,[^,\n]*)?/ui';

	private static $includeRegex = '/#VložText\(([^,\)]+)(,[^\)]+)?\)/';

	private static $expandRegex =
		'/#RozviňPole\(([^,\)]+)(,[^\)]+)?\)(?:\\r\\n|\\n\\r|\\r|\\n)?/';

	private static $moduleRegex = '/#Modul\(([^,\)]+)(,[^\)]+)?\)/';

	private static $defaultClasses = array(
		'table' => null,
		'tables' => 'shaded',
		'firstRows' => null,
		'oddRows' => null,
		'evenRows' => null,
		'lastRows' => null,
		'headCells' => null,
		'leftCells' => null,
		'oddCells' => null,
		'evenCells' => null,
		'rightCells' => null,
		'floatLeft' => 'floatLeft',
		'floatRight' => 'floatRight',
		'orderedList' => null,
		'unorderedList' => null,
		'paragraph' => null,
		'paragraphs' => null,
		'codeClass' => null,
		);

	private static $defaultIds = array(
		'table' => null,
		'tableRow' => null,
		'orderedList' => null,
		'unorderedList' => null,
		'listItem' => null,
		'paragraph' => null,
		'code' => null,
		);

	private static $fileUnits = array(
		array('', 'k', 'M', 'G', 'T'),
		array('', 'Ki', 'Mi', 'Gi', 'Ti'));

	private static $text = array(
		'yesterday-1' => 'predvčerom',
		'yesterday' => 'včera',
		'today' => 'dnes',
		'tomorow' => 'zajtra',
		'tomorow+1' => 'pozajtra',
		'+few-days' => 'o pár dní',
		'future' => 'v budúcnosti',

		'this-week' => 'tento týždeň',
		'last-week' => 'minulý týždeň',
		'last-week-1' => 'predminulý týždeň',

		'this-month' => 'tento mesiac',
		'last-month' => 'minulý mesiac',
		'last-month-1' => 'predminulý mesiac',

		'this-year' => 'tento rok',
		'last-year' => 'minulý rok',
		'this-year-2' => 'predminulý rok',
		'this-year-3' => 'pred tromi rokmi',
		'this-year-4' => 'pred štyrmi rokmi',
		'this-year-5' => 'pred piatimi rokmi',

		'-few-years' => 'pred niekoľkými rokmi',
		'-more-years' => 'pred viacerými rokmi',
		'-many-years' => 'mnoho rokov dozadu',

		'e-mail-alt' => 'adresa elektronickej pošty',
		'icon-alt' => 'ikona',
		'image-alt' => 'obrázok',
		'profile-photo-alt' => 'profilová fotografia',

		'article-untitled' => 'bez názvu',
		'article-list-empty' => 'Nie sú k dispozícii žiadne články na zobrazenie.',
		'article-list-read-more' => 'čítať viac',
		'article-date' => 'Dátum',
		'article-updated' => 'aktualizované',
		'article-author' => 'Autor',
		'article-authors' => 'Autori',
		'article-publisher' => 'Zverejnil',
		'article-publishers' => 'Zverejnili',
		'article-expired-warning' => 'Upozornenie',
		'article-expired-expired' => 'Platnosť tohto článku sa skončila',
		'article-expired-description' => 'Tento článok je v súčasnosti archivovaný na našich stránkach, ale jeho obsah nie je záväzný a môže obsahovať zastarané, mylné alebo neplatné informácie',

		'search-number-pages-found' => 'Počet výsledkov nájdených na stránkach',
		'search-rate-title' => 'počet bodov získaných v rámci tohto vyhľadávania',
		'search-target-title' => 'symbol „otvárané v novom okne“',
		'search-pages-result' => 'Výsledok vyhľadávania na stránkach',
		'search-not-found' => 'Požadovaná informácia nebola nájdená',

		'common-note' => 'Poznámka',
		'common-reconstruct' => 'Stránka je momentálne v procese rekonštrukcie',
		'common-work' => 'Na tejto časti stránky sa momentálne pracuje',
		'common-work-soon' => 'Na tejto časti stránky budeme pracovať v blízkej budúcnosti',
		'common-update' => 'Tento článok vyžaduje aktualizáciu',
		'common-not-available' => 'Táto informácia nie je dostupná v slovenskom jazyku',
		'common-empty' => 'V tejto sekcii momentálne neponúkame žiadne informácie',
		'common-target-blank' => 'Tento článok reprezentuje odkaz na externý informačný zdroj. Kliknutím na nadpis článku prejdete na externú lokalitu.',

		// 'external-link' => 'externý odkaz',
		'external-link' => "otvárané v novom okne\r\n(obvykle ide o externý odkaz)",

		'error-template-not-found-prefix' => 'Šablóna „',
		'error-template-not-found-postfix' => '“ nebola nájdená',

		'error-capture-not-started' => 'Zachytávanie obsahu sa nemohlo začať.',
		'error-capture-reserved-identifier' => 'Tento indentifikátor je rezervovaný.',
		'error-capture-not-neutral-state' => 'Stroj RS Rheia nebol v neutrálnom stave.',
		'error-capture-tabs-open' => 'Stroj RS Rheia má otvorenú definíciu obsahu kariet.',
		'error-capture-not-closed-correctly' => 'Zachytávanie obsahu nebolo korektne ukončené.',
		);

	private static $objects = null;
	// private static $lastObject = null;
	private static $objectsNewestDate = null;
	private static $usedTemplates = null;
	private static $newestTemplate = null;
	private static $filteredObjects = null;
	private static $revalidationDate = null;

	// buď počítadlo nahradení, alebo pole vlastností pri budovaní polí
	private static $objectsProps = 0;

	// Niektoré stránky sa pri nahrávaní viacnásobne odkazujú na iné stránky.
	// Nie je prípustné, aby sa pri každom odkaze znova nahrával zdroj do
	// pamäte. Namiesto toho je vhodné, aby sa zdroje cacheovali…
	private static $rheiasCache = array();

	// private $category = null;
	// private $strictCategory = null;

	private $classes = null;
	private $ids = null;
	private $autoids = null;
	private $autoidcnts = null;
	// Poznámka: Systém automatického číslovania je v miernom konflikte so
	// systémom automatického rozvíjania polí. Keďže boli vytvárané úplne
	// nezávisle v rozdielnych časových úsekoch, nevedia komunikovať
	// (pretože o sebe nevedia navzájom). Z toho dôvodu vznikajú v dokumente
	// duplicitné identifikátory (ID) automatického číslovania, ak je
	// číslovanie zapnuté v takej oblasti, kde nastáva automatické rozvíjanie
	// polí.
	// 
	private $baseName = null;
	private $sourceDate = null;

	private $articlesRefs = null;
	private $rheiaDefs = null;
	private $anonymousDefs = null;
	private $referencePattern = null;
	private $referenceOptions = null;
	private $files = null;
	private $externFiles = null;
	private $style = null;
	private $class = null;

	private $lastFile = null;
	private $externLastFile = null;
	private $lastDate = null;
	private $articles = null;
	private $html = null;
	private $title = null;
	private $ifListEmpty = null;
	private $listEmptyMessage = null;
	private $styleSheed = null;
	private $javaScript = null;
	private $onShow = null;
	private $onExit = null;
	private $linkStyles = null;
	private $linkJavaScripts = null;
	private $macro = array();
	private $tabsSearch = null;
	private $icalProcessing = null;
	private $icalData = null;
	private $calcInstance = null;
	private $calcCaptured = null;


	/** TOO COMPLICATED!
	private function setupCategory()
	{
		global $category, $siteContentConfig;

		if (isSet($category))
		{
			RheiaMainClass::$category = $category;

			if (isSet($siteContentConfig) &&
				isSet($siteContentConfig[$category]))
			{
				if (isSet($siteContentConfig[$category]['strict']))
					RheiaMainClass::$strictCategory = true;
			}
		}
	}
	**/

	public function __construct($baseName = null,
		$autoload = true, $autoshow = true)
	{
		// setupCategory();

		$this->classes = RheiaMainClass::$defaultClasses;
		$this->ids = RheiaMainClass::$defaultIds;
		$this->autoids = RheiaMainClass::$defaultIds;
		$this->autoidcnts = array();
		if (isSet($baseName))
		{
			$this->baseName = $baseName;
			RheiaMainClass::$cropTextID = $this->baseName; // ‼Warning‼ Emergency solution… Not the best, not any right, but necessary at the moment… (2021-08-12)
			if ($autoload)
			{
				RheiaMainClass::loadObjects();
				$this->loadHTML();
				if ($autoshow) $this->showHTML();
			}
		}
	}


	public static $errorLog = '../rheia-error.log';
	public static $lastProcessed = null;
	public static $newRecord = true;

	public static function logError($string)
	{
		$logRecord = '';

		if (RheiaMainClass::$newRecord)
		{
			$logRecord .= EOL2.'--- New Record: '.date(gmtdate_format,
				RheiaMainClass::$currentTime).' ---'.EOL;
			RheiaMainClass::$newRecord = false;

			if (isSet($_SERVER['REDIRECT_STATUS']))
				$logRecord .= 'Status: '.$_SERVER['REDIRECT_STATUS'].EOL;
			if (isSet($_SERVER['HTTP_REFERER']))
				$logRecord .= 'Referer: '.$_SERVER['HTTP_REFERER'].EOL;
			if (isSet($_SERVER['HTTP_USER_AGENT']))
				$logRecord .= 'Agent: '.$_SERVER['HTTP_USER_AGENT'].EOL;
			if (isSet($_SERVER['SCRIPT_FILENAME']))
				$logRecord .= 'Script: '.$_SERVER['SCRIPT_FILENAME'].EOL;
		}

		if (isSet(RheiaMainClass::$lastProcessed))
		{
			$logRecord .= TAB.RheiaMainClass::$lastProcessed.EOL;
			RheiaMainClass::$lastProcessed = null;
		}

		if (preg_match('/^([\r\n]+)(.*)$/', $string, $match))
			$logRecord .= $match[1].TAB2.$match[2].EOL;
		else
			$logRecord .= TAB2.$string.EOL;

		$logFile = RheiaMainClass::$downloadLevel.RheiaMainClass::$errorLog;
		file_put_contents($logFile, $logRecord, FILE_APPEND);
	}


	public function reset($baseName = null)
	{
		// $this->category = null;
		// $this->strictCategory = null;
		// setupCategory();

		RheiaMainClass::$usedTemplates = null;
		RheiaMainClass::$newestTemplate = null;
		RheiaMainClass::$revalidationDate = null;

		$this->classes = RheiaMainClass::$defaultClasses;
		$this->ids = RheiaMainClass::$defaultIds;
		$this->autoids = RheiaMainClass::$defaultIds;
		$this->autoidcnts = array();
		$this->baseName = $baseName;
		RheiaMainClass::$cropTextID = $this->baseName; // ‼Warning‼ Emergency solution… Not the best, not any right, but necessary at the moment… (2021-08-12)

		$this->articles = null;
		$this->html = null;
		$this->title = null;
		$this->styleSheed = null;
		$this->javaScript = null;
		$this->onShow = null;
		$this->onExit = null;
		$this->linkStyles = null;
		$this->linkJavaScripts = null;
		$this->macro = array();
		// $this->tabsSearch = null;
		$this->icalProcessing = null;
		$this->icalData = null;
		$this->calcInstance = null;
		$this->calcCaptured = null;

		RheiaMainClass::loadObjects();
	}

	public static function makeExternalLink($label, $link = null)
	{
		if (empty($link)) $link = $label;
		return '<a href="'.$link.'" target="_blank" '.
			'class="external-link"><span>'.$label.
			'</span> <img src="'.RheiaMainClass::$externalLinkIcon.
			'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].
			'" class="external-icon" /></a>';
	}

	public static function localize($translation)
	{
		foreach ($translation as $key => $value)
			if (isSet(RheiaMainClass::$text[$key]))
				RheiaMainClass::$text[$key] = $value;
	}

	public static function getText($key)
	{
		if (isSet(RheiaMainClass::$text[$key]))
			return RheiaMainClass::$text[$key];
		return null;
	}

	public static function searchFor($searchString)
	{
		RheiaMainClass::$searchString = $searchString;
		RheiaMainClass::$searchPhrase = RheiaMainClass::transliterate($searchString);
		if (empty(RheiaMainClass::$searchPhrase)) return false;
		RheiaMainClass::$searchWords = preg_split('/\s+/',
			RheiaMainClass::$searchPhrase, -1, PREG_SPLIT_NO_EMPTY);
		RheiaMainClass::$searchCount = count(RheiaMainClass::$searchWords);
		RheiaMainClass::$searchResults = array();
		return true;
	}

	public static function getSearchRate()
	{
		if (empty(RheiaMainClass::$searchIn)) return 0;

		$rate = 0;

		if (strpos(RheiaMainClass::$searchIn, RheiaMainClass::$searchPhrase)
			!== false)
			$rate += RheiaMainClass::$searchCount * RheiaMainClass::$searchCount;

		$foundAll = true;
		foreach (RheiaMainClass::$searchWords as $word)
			if (strpos(RheiaMainClass::$searchIn, $word) === false)
				{ $foundAll = false; break; }

		if ($foundAll)
			$rate += RheiaMainClass::$searchCount * RheiaMainClass::$searchCount;

		foreach (RheiaMainClass::$searchWords as $word)
		{
			if (strlen($word) <= 2) continue;
			if (strpos(RheiaMainClass::$searchIn, ' '.$word.' ') !== false)
				$rate += 2;
			if (strpos(RheiaMainClass::$searchIn, $word) !== false) ++$rate;
		}

		return $rate;
	}


	private static function convertHTMLEntity($match)
	{ return mb_convert_encoding($match[1], 'UTF-8', 'HTML-ENTITIES'); }

	private static function getClassTag($class, $class2 = null)
	{
		if (empty($class2))
			return empty($class) ? '' : (' class="'.$class.'"');
		return ' class="'.(empty($class) ? '' : ($class.' ')).$class2.'"';
	}

	private static function addClassToList(&$list, $class)
	{
		if (!empty($class))
		{
			if (!empty($list)) $list .= ' ';
			$list .= $class;
		}
	}

	private static function formatFileInfo($fileSize, $fileDate)
	{
		$return = '';

		if (isSet($fileSize))
		{
			$return .= RheiaMainClass::formatFileSize($fileSize);
			if (isSet($fileDate))
				$return .= ', '.RheiaMainClass::formatFileDate($fileDate);
				// ' ('.RheiaMainClass::getWhen($fileDate).')'
		}
		elseif (isSet($fileDate))
			$return .= RheiaMainClass::formatFileDate($fileDate);
			// ' ('.RheiaMainClass::getWhen($fileDate).')'

		if (!empty($return))
			$return = ' <em class="fileinfo">'.$return.'</em>';

		return $return;
	}


	public static function getWhen($timestamp)
	{
		$days = (int)((7200 + RheiaMainClass::$currentTime) / 86400) -
			(int)((7200 + $timestamp) / 86400);

		/*
		echo
			'<p> <br /><small>('.RheiaMainClass::$currentTime.' | '.$timestamp.
			') '.(int)((7200 + RheiaMainClass::$currentTime) / 86400).
			' - '.(int)((7200 + $timestamp) / 86400)
			.' = '.$days.'</small></p>';
		*/

		if ($days < 0)
		{
			switch ($days)
			{
			case -1: return RheiaMainClass::$text['tomorow'];
			case -2: return RheiaMainClass::$text['tomorow+1'];
			}
			if ($days >= -10) return RheiaMainClass::$text['+few-days'];
			return RheiaMainClass::$text['future'];
		}
		switch ($days)
		{
		case 0: return RheiaMainClass::$text['today'];
		case 1: return RheiaMainClass::$text['yesterday'];
		case 2: return RheiaMainClass::$text['yesterday-1'];
		}

		$years = date('o', RheiaMainClass::$currentTime) - date('o', $timestamp);

		if ($days <= 21 && $years == 0)
		{
			$weeks = date('W', RheiaMainClass::$currentTime) -
				date('W', $timestamp);

			switch ($weeks)
			{
			case 0: return RheiaMainClass::$text['this-week'];
			case 1: return RheiaMainClass::$text['last-week'];
			case 2: return RheiaMainClass::$text['last-week-1'];
			}
		}

		if ($days <= 100 && $years == 0)
		{
			$months = date('n', RheiaMainClass::$currentTime) -
				date('n', $timestamp);

			switch ($months)
			{
			case 0: return RheiaMainClass::$text['this-month'];
			case 1: return RheiaMainClass::$text['last-month'];
			case 2: return RheiaMainClass::$text['last-month-1'];
			}
		}

		switch ($years)
		{
		case 0: return RheiaMainClass::$text['this-year'];
		case 1: return RheiaMainClass::$text['last-year'];
		case 2: return RheiaMainClass::$text['this-year-2'];
		case 3: return RheiaMainClass::$text['this-year-3'];
		case 4: return RheiaMainClass::$text['this-year-4'];
		case 5: return RheiaMainClass::$text['this-year-5'];
		}

		if ($years < 10) return RheiaMainClass::$text['-few-years'];
		if ($years < 20) return RheiaMainClass::$text['-more-years'];
		return RheiaMainClass::$text['-many-years'];
	}

	public static function downTimeIsValid($filename)
	{
		$string = str_replace(chr(0xef).chr(0xbb).chr(0xbf), '',
			file_get_contents($filename.'-time'));

		@list($day, $month, $year, $hour, $minute, $second) =
			preg_split('/[.:,]/', str_replace(' ', '', str_replace(' ', '',
				str_replace('&#44;', ',', str_replace('&#46;', '.',
				str_replace('&#58;', ':', $string))))).',,,,,');

		$hour = (int)$hour; $minute = (int)$minute;
		$second = (int)$second; $month = (int)$month;
		$day = (int)$day; $year = (int)$year;

		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$valid = $timestamp >= RheiaMainClass::$currentTime;

		if (!$valid)
			echo EOL.'<!-- Platnosť časového limitu na prevzatie '.
				'súboru „'.$filename.'“ sa skončila dňa '.$day.'. '.$month.
				'. '.$year.' o '.sprintf('%02d', $hour).':'.sprintf('%02d',
					$minute).':'.sprintf('%02d', $second).' -->'.EOL;

		return $valid;
	}

	public static function getExeFileInfo($originalName,
		&$exists, &$fileSize, &$fileDate)
	{
		$exists = false; $fileSize = null;
		$fileDate = null; $filePathName = null;
		$mustRevalidate = true; $saveParsed = false;
		$counter = 0;

		$parsed = RheiaMainClass::$externDownParsedPath.'localhost/';

		if (file_exists($parsed) || RheiaMainClass::mkdir($parsed))
		{
			$saveParsed = true;
			$parsed .= RheiaMainClass::transliterate($originalName, '-').'.php';

			if (file_exists($parsed))
			{
				include './'.$parsed;
			}
		}

		if ($mustRevalidate)
		{
			if (isSet(RheiaMainClass::$downloadPath))
			{
				if (!is_array(RheiaMainClass::$downloadPath))
					RheiaMainClass::$downloadPath =
						explode(';', RheiaMainClass::$downloadPath);

				foreach (RheiaMainClass::$downloadPath as $path)
				{
					$filePathName = RheiaMainClass::$downloadLevel.
						RheiaMainClass::$downloadSubfolder.'/';

					if (!empty($path)) $filePathName .= $path.'/';
					$filePathName .= $originalName;

					if (file_exists($filePathName))
					{
						break;
					}
				}

				if (!file_exists($filePathName))
				{
					foreach (RheiaMainClass::$downloadPath as $path)
					{
						$tempPathName = RheiaMainClass::$downloadLevel.
							RheiaMainClass::$downloadSubfolder.'-timed/';
						if (!empty($path)) $tempPathName .= $path.'/';
						$tempPathName .= $originalName;
						if (file_exists($tempPathName) &&
							file_exists($tempPathName.'-time') &&
							RheiaMainClass::downTimeIsValid($tempPathName))
						{
							$filePathName = $tempPathName;
							break;
						}
					}
				}
			}
			else
			{
				$filePathName = RheiaMainClass::$downloadLevel.
					RheiaMainClass::$downloadSubfolder.'/'.$originalName;
			}

			if (file_exists($filePathName))
			{
				$fileSize = filesize($filePathName);
				$fileDate = filemtime($filePathName);
				$exists = true;
			}

			if ($saveParsed)
			{
				++$counter;

				$content = '<'.'?php'.EOL2;
				$content .= '$exists = '.var_export(
					$exists, true).';'.EOL;
				$content .= '$fileSize = '.var_export(
					$fileSize, true).';'.EOL;
				$content .= '$fileDate = '.var_export(
					$fileDate, true).';'.EOL;
				$content .= '$filePathName = '.var_export(
					$filePathName, true).';'.EOL;
				$content .= '$counter = '.var_export(
					$counter, true).';'.EOL;
				$content .= '$mustRevalidate = '.
					(rand(172800, 345600) +
						// Interval of Revalidation [s]
						// (like check_extern_interval)
						// 172800-345600 s == 2-4 days
						RheiaMainClass::$currentTime).
					' <= RheiaMainClass::$currentTime;'.
					EOL2.'?'.'>';

				file_put_contents($parsed, $content, LOCK_EX);
			}
		}

		return $filePathName;
	}

	public static function getFileInfo($originalName,
		&$exists, &$fileSize, &$fileDate)
	{
		if (0 === strcasecmp(substr($originalName, -4), '.exe'))
			return RheiaMainClass::getExeFileInfo($originalName,
				$exists, $fileSize, $fileDate);

		$exists = false; $fileSize = null;
		$fileDate = null; $filePathName = null;

		// echo '<!-- Aktuálny pracovný adresár: „'.getcwd().'“ -->'.EOL;

		if (isSet(RheiaMainClass::$downloadPath))
		{
			if (!is_array(RheiaMainClass::$downloadPath))
				RheiaMainClass::$downloadPath =
					explode(';', RheiaMainClass::$downloadPath);

			foreach (RheiaMainClass::$downloadPath as $path)
			{
				$filePathName = RheiaMainClass::$downloadLevel.
					RheiaMainClass::$downloadSubfolder.'/';

				// RheiaMainClass::logError('Download path A: '.$filePathName);

				if (!empty($path)) $filePathName .= $path.'/';
				$filePathName .= $originalName;

				// RheiaMainClass::logError('Download path B: '.$filePathName);

				if (file_exists($filePathName))
				{
					// echo '<!-- Cesta bola nastavená, súbor bol v umiestnení „'.
					// 	$path.'“, celý názov: „'.$filePathName.'“ -->'.EOL;
					break;
				}
			}

			if (!file_exists($filePathName))
			{
				foreach (RheiaMainClass::$downloadPath as $path)
				{
					$tempPathName = RheiaMainClass::$downloadLevel.
						RheiaMainClass::$downloadSubfolder.'-timed/';
					if (!empty($path)) $tempPathName .= $path.'/';
					$tempPathName .= $originalName;
					if (file_exists($tempPathName) &&
						file_exists($tempPathName.'-time') &&
						RheiaMainClass::downTimeIsValid($tempPathName))
					{
						$filePathName = $tempPathName;
						break;
					}
				}
			}

			// if (!file_exists($filePathName))
			// {
			// 	echo '<!-- Cesta bola nastavená, ale súbor sa nepodarilo nájsť.'.
			// 		' Posledné skúmané umiestnenie: „'.$filePathName.'“ -->'.EOL;
			// }
		}
		else
		{
			$filePathName = RheiaMainClass::$downloadLevel.
				RheiaMainClass::$downloadSubfolder.'/'.$originalName;
			// echo '<!-- Cesta nie je nastavená, súbor „'.
			// 	$filePathName.'“ '.(file_exists($filePathName) ?
			// 		'' : 'ne').'jestvuje. -->'.EOL;
		}

		if (file_exists($filePathName))
		{
			$fileSize = filesize($filePathName);
			$fileDate = filemtime($filePathName);
			$exists = true;
		}

		return $filePathName;
	}

	private static function mkdir($parsed)
	{
		if (!mkdir($parsed))
		{
			RheiaMainClass::logError('Cannot create directory: '.$parsed);
			return false;
		}
		return true;
	}

	public static function getExternFileInfo($url,
		&$exists, &$fileSize, &$fileDate)
	{
		$exists = false; $fileSize = null; $fileDate = null;

		$mustRevalidate = true; $saveParsed = false;
		$fails = 0; $successes = 0;

		if (!preg_match('#^https?://.*#i', $url)) $url = 'http://'.$url;

		if (preg_match('#^https?:/{0,2}([^/]+)(.*)#i', $url, $match))
		{
			if (empty($match[1])) $match[1] = '_unknown';
			$parsed = RheiaMainClass::$externDownParsedPath.$match[1].'/';

			if (file_exists($parsed) || RheiaMainClass::mkdir($parsed))
			{
				$saveParsed = true;

				if (empty($match[2])) $match[2] = '_unknown';
				else $match[2] = RheiaMainClass::transliterate($match[2], '-');
				$parsed .= $match[2].'.php';

				if (file_exists($parsed))
				{
					include './'.$parsed;
				}
			}
		}

		if ($mustRevalidate)
		{
			// @list($content, $retval) = runPhp($url);

			$content = ''; $headers = get_headers($url);
			foreach ($headers as $header) $content .= $header."\r\n";


			// RheiaMainClass::logError('getExternFileInfo():');
			// RheiaMainClass::logError('$content = '.
			// 	var_export($content, true).';');
			// RheiaMainClass::logError('$retval = '.
			// 	var_export($retval, true).';');

			$fields = preg_split('/[\r\n]+/',
				$content, -1, PREG_SPLIT_NO_EMPTY);
			$headers = array();

			foreach ($fields as $field)
			{
				if (preg_match('/([^:]+): +(.+)/', $field, $match))
				{
					$match[1] = strtolower($match[1]);

					if (isSet($headers[$match[1]]))
					{
						if (is_array($headers[$match[1]]))
						{
							$headers[$match[1]][] = trim($match[2]);
						}
						else
						{
							$headers[$match[1]] =
								array($headers[$match[1]], trim($match[2]));
						}
					}
					else
					{
						$headers[$match[1]] = trim($match[2]);
					}
				}
				else if (preg_match('/HTTP\/(.*?) ([0-9]+) (.*)/i',
					$field, $match))
				{
					$headers['http'] = array('version' => $match[1],
						'code' => (int)$match[2], 'reason' => $match[3]);
				}
				// else { unknown }
			}

			if ($saveParsed)
			{
				if (isSet($headers['http']) &&
					200 == $headers['http']['code'])
					++$successes; else ++$fails;

				$content = '<'.'?php'.EOL2;
				$content .= '$headers = '.
					var_export($headers, true);
				$content .= ';'.EOL2;
				$content .= '$fails = '.var_export
					($fails, true).';'.EOL;
				$content .= '$successes = '.var_export
					($successes, true).';'.EOL;
				$content .= '$mustRevalidate = '.
					(rand(172800, 345600) +
						// Interval of Revalidation [s]
						// (like check_extern_interval)
						// 172800-345600 s == 2-4 days
						RheiaMainClass::$currentTime).
					' <= RheiaMainClass::$currentTime;'.
					EOL2.'?'.'>';

				file_put_contents($parsed, $content, LOCK_EX);
			}
		}

		if (isSet($headers['http']) && 200 == $headers['http']['code'])
		{
			if (isSet($headers['content-length']))
			{
				if (is_array($headers['content-length']))
				{
					if (isSet($headers['content-length'][0]))
						$fileSize = $headers['content-length'][0];
				}
				else
					$fileSize = $headers['content-length'];
			}

			if (isSet($headers['last-modified']))
			{
				if (is_array($headers['last-modified']))
				{
					if (isSet($headers['last-modified'][0]))
						$fileDate = strtotime($headers['last-modified'][0]);
				}
				else
					$fileDate = strtotime($headers['last-modified']);
			}

			$exists = true;
		}
	}

	public static function mktimestamp($string)
	{
		@list($day, $month, $year, $hour, $minute, $second) =
			preg_split('/[.:,]/', str_replace(' ', '', str_replace(' ', '',
				str_replace('&#44;', ',', str_replace('&#46;', '.',
				str_replace('&#58;', ':', $string))))).',,,,,');
		return mktime((int)$hour, (int)$minute, (int)$second,
			(int)$month, (int)$day, (int)$year);
	}


	public static function handleNBSP($text)
	{
		// old way/non-unicode: preg_replace('/(\b[a-z,A-Z]\b)( )/', '$1 ', …
		return	preg_replace('/^(\pL) /u', '$1 ',
				preg_replace('/(\PL)(\pL) /u', '$1$2 ', $text));
	}

	public static function transliterate($text, $separator = ' ')
	{
		// ini_set('mbstring.substitute_character', 32);
		// $text = mb_convert_encoding($text, 'utf-8', 'utf-8');

		// Transliterate all unknown characters using strtr
		$return = strtr($text, $GLOBALS['replaceTable']);
		// Clean all non-alphanumeric characters
		$return = preg_replace('/[^\\pL0-9_]+/u', $separator, $return);
		// Transliterate the rest
		// error_log('1> "'.$return.'"'.EOL);
		$return = iconv('utf-8', 'us-ascii//TRANSLIT', $return);
		// error_log('2> "'.$return.'"'.EOL);
		// Lowercase
		$return = strtolower($return);
		// Filter unwanted characters
		$return = preg_replace('/[^'.$separator.'a-z0-9_]+/', '', $return);

		// Trim and return
		return trim($return, $separator);
	}

	public static function filterHTML($html)
	{
		// Remove styles and scripts
		$html = preg_replace('#<style[^>]*>.*</style>#i', '', $html);
		$html = preg_replace('#<script[^>]*>.*</script>#i', '', $html);

		// Remove files info
		$html = preg_replace('#<em\s+class="fileinfo"[^>]*>.*</em>#i',
			'', $html);

		// Replace &shy;‑ or ­‑ combination with just -
		$html = preg_replace('/&shy;‑|­‑/i', '-', $html);

		// Remove all tags and replace all HTML entities
		$text = preg_replace('#</?[^>]*>#', '', $html);
		$text = preg_replace_callback('/(&#[0-9]+;)/',
			array('RheiaMainClass', 'convertHTMLEntity'), $text);
		$text = preg_replace_callback('/(&[a-z][a-z0-9]+;)/i',
			array('RheiaMainClass', 'convertHTMLEntity'), $text);
		return $text;
	}


	public static function addPropertyValue($lineNumber, $object,
		$generated, $property, $value = null)
	{
		/**
		 * (null == $value)      means “it’s object’s general contents”
		 * (null == $generated)  means “property is an array” – arrays must
		 *                       not contain generated value…
		 */

		if (null === $value)
		{
			$value = $property;
			$property = 0;
		}
		else $property = '#'.$property;

		if (!isSet(RheiaMainClass::$objects[$object]))
			RheiaMainClass::$objects[$object] = array();

		// RheiaMainClass::logError('addPropertyValue(');
		// RheiaMainClass::logError(TAB.'$lineNumber = '.print_r($lineNumber, true).',');
		// RheiaMainClass::logError(TAB.'$object = '.print_r($object, true).',');
		// RheiaMainClass::logError(TAB.'$generated = '.print_r($generated, true).',');
		// RheiaMainClass::logError(TAB.'$property = '.print_r($property, true).',');
		// RheiaMainClass::logError(TAB.'$value = '.print_r($value, true));
		// RheiaMainClass::logError(');');
		// RheiaMainClass::logError(EOL);

		if (isSet(RheiaMainClass::$objects[$object][$property]))
		{
			if (null === RheiaMainClass::$objects[$object][$property][0])
			{
				// Store next array element
				if (preg_match('/^(.*)#sort([ad])#([^#]+)#(.*)$/is',
					$value, $matches))
				{
					RheiaMainClass::$objects[$object]
						[$property][1][] = $matches[1].$matches[3].$matches[4];
					RheiaMainClass::$objects[$object]
						[$property][2][] = $lineNumber;
					RheiaMainClass::$objects[$object]
						[$property][3][] = array($matches[2], $matches[3]);
				}
				else
				{
					RheiaMainClass::$objects[$object]
						[$property][1][] = $value;
					RheiaMainClass::$objects[$object]
						[$property][2][] = $lineNumber;
					RheiaMainClass::$objects[$object]
						[$property][3][] = array(null, null);
				}
			}
			else
			{
				if ($generated) RheiaMainClass::$objects
					[$object][$property][0] = true;

				RheiaMainClass::$objects[$object]
					[$property][1] .= $value;
				RheiaMainClass::$objects[$object]
					[$property][2] = $lineNumber;
			}
		}
		else
		{
			if (null === $generated)
			{
				// Create array
				if (preg_match('/^(.*)#sort([ad])#([^#]+)#(.*)$/is',
					$value, $matches))
				{
					RheiaMainClass::$objects[$object][$property] =
						array(null, array($matches[1].$matches[3].$matches[4]),
							array($lineNumber), array(array($matches[2],
								$matches[3])));
				}
				else
				{
					RheiaMainClass::$objects[$object][$property] =
						array(null, array($value), array($lineNumber),
							array(array(null, null)));
				}
			}
			else
			{
				RheiaMainClass::$objects[$object][$property] =
					array($generated, $value, $lineNumber);
			}
		}
	}


	private static function catchFilteredObjects($match)
	{
		if (null === RheiaMainClass::$filteredObjects)
			RheiaMainClass::$filteredObjects = array();
		if (isSet($match[3])) { $match[1] = $match[2];
			$match[2] = $match[3]; }
		if (!isSet($match[2])) $match[2] = '';
		RheiaMainClass::$filteredObjects['$'.$match[1].$match[2]] = true;
	}


	public static function implodeFilteredObjects()
	{
		$imploded = ' ';
		foreach (RheiaMainClass::$filteredObjects as $key => $value)
			if ($value) $imploded .= $key.' ';
		return $imploded;
	}

	public static function filterObjects($content)
	{
		RheiaMainClass::$filteredObjects = null;

		$content =
			preg_replace(RheiaMainClass::$moduleRegex, '',
			preg_replace_callback(
				RheiaMainClass::$includeRegex,
					array('RheiaMainClass', 'replaceIncludes'),
			preg_replace_callback('/\$when\(([0-9]+)\)/',
					array('RheiaMainClass', 'replaceWhens'),
			str_replace('$currentYear',
				date('Y', RheiaMainClass::$currentTime),
			str_replace('$currentMonth',
				date('n', RheiaMainClass::$currentTime),
			str_replace('$currentDay',
				date('j', RheiaMainClass::$currentTime),
				$content))))));

		$content = preg_replace('/\$\(Neprázdne:'.
			'([\pL–]+)((?:#\pL+)+)\[\]\)/ui', '', $content);

		$content = preg_replace_callback('/\$\((Platí|Neplatí):([^,]+),'.
			'([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
			'catchFilteredObjects'), $content);

		$content = preg_replace_callback('/\$\((Zhoduje):([^,]*),'.
			'([\pL–]+#\pL+),((?:[\pL–]+)?(?:#\pL+)?)'.
			'(,(?:[\pL–]+)?(?:#\pL+)?)?(,(?:[\pL–]+)?(?:#\pL+)?)?'.
			'\)/ui', array('RheiaMainClass', 'catchFilteredObjects'), $content);

		$content = preg_replace_callback('/\$\((Oddeľovač|Náhrada):([^,]+)'.
			',([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
			'catchFilteredObjects'), $content);

		/*$content = preg_replace_callback('/\$\((Platí|Neplatí):([^,]+)'.
			',(\pL+)(#\pL+)?\)/ui', array('RheiaMainClass',
			'catchFilteredObjects'), $content);*/

		$content = preg_replace_callback('/\$([\pL–]+)(#\pL+)?/u',
			array('RheiaMainClass', 'catchFilteredObjects'), $content);

		$content = // preg_replace('/\[[0-9]+\]/', '',
			str_replace('$[', '', str_replace('$]', '',
			str_replace('[]', '', $content)))
			//)
			;

		/*$content = preg_replace_callback('/\$(\pL+)(#\pL+)?/u',
			array('RheiaMainClass', 'catchFilteredObjects'), $content);*/

		return $content;
	}


	public static function parseSchema($schema)
	{
		// Prepare the string (split to lines)
		$lines = explode('<br />', nl2br($schema));

		$schema = array(); $value = '';

		// Trim whitespace and create 2-dimensional
		// array with elements grouped by 4
		foreach ($lines as $i => $line)
		{
			/*
			Schéma má štruktúru podobnú skriptu, pričom skript je
			reprezentovaný dvojrozmerným poľom.

			Každý riadok skriptu (prvok poľa prvého rozmeru) je ekvivaletný
			poľu (druhého rozmeru) s jedným alebo dvoma prvkami. Prvý prvok
			má číselnú hodnotu a určuje príkaz:

				0 – label
				1 – values
				2 – match
				3 – lang
				4 – set
				5 – log
				6 – go
				7 – default
				8 – stop

			druhý prvok (ak je prítomný) má reťazcovú hodnotu, ktorá určuje
			argument príkazu. (V jednom špeciálnom prípade sa vyskytuje hodnota
			null.)

			Nerozpoznané príkazy sú ignorované.
			*/
			$line = preg_replace('/[ \r\n]+/', ' ', trim($line, ' '.EOL));

			if     (preg_match('/^label: +(.*)$/i', $line, $value))
				$schema[] = array(0, $value[1]);
			elseif (preg_match('/^values: +(.*)$/i', $line, $value))
				$schema[] = array(1, $value[1]);
			elseif (preg_match('/^match: +(.*)$/i', $line, $value))
				$schema[] = array(2, $value[1]);

			elseif (preg_match('/^set(–[^:]+): +(.*)$/i', $line, $value))
			{
				$schema[] = array(3, $value[1]);
				$schema[] = array(4, $value[2]);
			}

			elseif (preg_match('/^set: +(.*)$/i', $line, $value))
			{
				$schema[] = array(3, null);
				$schema[] = array(4, $value[1]);
			}

			elseif (preg_match('/^log: +(.*)$/i', $line, $value))
				$schema[] = array(5, $value[1]);
			elseif (preg_match('/^go: +(.*)$/i', $line, $value))
				$schema[] = array(6, $value[1]);
			elseif (preg_match('/^default$/i', $line, $value))
				$schema[] = array(7);
			elseif (preg_match('/^stop$/i', $line, $value))
				$schema[] = array(8);
			elseif (preg_match('/^debug:? +(.*)$/i', $line, $value))
				$schema[] = array(9, $value[1]);
		}

		return $schema;
	}


	private static $schemaSemaphore = false;

	public static function applySchema($thisObject, $thisProp, $lang, $value)
	{
		if (RheiaMainClass::$schemaSemaphore) return $value;
		RheiaMainClass::$schemaSemaphore = true;

		$values = ''; $curLang = null;
		$execute = false; $references = array();
		$debug = null;

		if (isSet(RheiaMainClass::$objects['']) &&
			isSet(RheiaMainClass::$objects['']['schemas']))
		{
			// Zistí dostupnosť schémy v určenom jazyku…
			if (!empty($lang) &&
				isSet(RheiaMainClass::$objects['']['schemas'][$thisProp.$lang]))
				$schema = RheiaMainClass::$objects['']['schemas'][$thisProp.$lang];
			// …ak nejestvuje, tak overí dostupnosť predvolenej schémy.
			elseif (isSet(RheiaMainClass::$objects['']['schemas'][$thisProp]))
				$schema = RheiaMainClass::$objects['']['schemas'][$thisProp];
			else
			{
				// Inak „nič“:
				RheiaMainClass::$schemaSemaphore = false;
				return $value;
			}

			$lc = count($schema);

			for ($ln = 0; $ln < $lc; ++$ln)
			{
				switch ($schema[$ln][0])
				{
				case 0: // label
					if ($debug) RheiaMainClass::logError(
						'	label: '.$schema[$ln][1]);

					// $values = '';
					// $curLang = null;
					$execute = false;
					$references = array();
					break;

				case 1: // values
					if ($debug) RheiaMainClass::logError(
						'	values: '.$schema[$ln][1]);

					$objectsProps = RheiaMainClass::$objectsProps;
					RheiaMainClass::$objectsProps = 0;

					$values =
						// RheiaMainClass::replaceObjects( // NO!
						str_replace('$this', $thisObject,
						str_replace('##this', $thisProp,
						str_replace('$$this##this', $value,
						$schema[$ln][1])))
						// )
						;

					for ($i = 0; $i < 25; ++$i)
					{
						if (strpos($values, '$') === false) break;

						$values = preg_replace_callback('/\$([\pL–]+)'.
							'(?![\pL–])(?!#)/u', array('RheiaMainClass',
								'replaceStandardObjects'), $values);

						$values = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
							'(?![\pL–])(?!\[)/u', array('RheiaMainClass',
								'replaceStandardObjects'), $values);

						$values = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
							'?$/u', array('RheiaMainClass',
								'replaceStandardObjects'), $values);

						// Zazátvorkované objekty +++
						$values = preg_replace_callback('/\$⦅([\pL–]+)'.
							'⦆/u', array('RheiaMainClass',
								'replaceStandardObjects'), $values);
						$values = preg_replace_callback('/\$⦅([\pL–]+)'.
							'(#\pL+)?⦆/u', array('RheiaMainClass',
								'replaceStandardObjects'), $values);
						// +++ Zazátvorkované objekty

						$values = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
							'\[([0-9]+)\]/u', array('RheiaMainClass',
								'replaceArrayElement'), $values);

						if (0 === RheiaMainClass::$objectsProps) break;
					}

					RheiaMainClass::$objectsProps = $objectsProps;

					if ($debug) RheiaMainClass::logError(
						'		> '.$values);
					break;

				case 2: // match
					if ($debug)
					{
						RheiaMainClass::logError('	match: '.$schema[$ln][1]);
						RheiaMainClass::logError('		> '.$values);
					}

					if (preg_match($schema[$ln][1], $values, $matches))
					{
						$references = $matches;
						$execute = true;
					}
					else $execute = false;

					if ($debug) RheiaMainClass::logError(
						'		> '.($execute ? 'true' : 'false'));
					break;

				case 3: // lang
					if ($debug)
					{
						if (null === $schema[$ln][1])
							RheiaMainClass::logError('	reset lang');
						else
							RheiaMainClass::logError(
								'	lang: '.$schema[$ln][1]);
					}

					if ($execute)
					{
						$curLang = $schema[$ln][1];
						if ($debug) RheiaMainClass::logError(
							'		> executed');
					}
					break;

				case 4: // set
					if ($debug) RheiaMainClass::logError(
						'	set: '.$schema[$ln][1]);

					if ($execute)
					{
						$act = false;
						if (!empty($curLang) && !empty($lang))
						{
							if ($curLang == $lang)
							{
								$value = $schema[$ln][1];
								$act = true;
							}
						}
						elseif (!empty($curLang))
						{
							if ($curLang == '–sk')
							{
								$value = $schema[$ln][1];
								$act = true;
							}
						}
						else
						{
							$value = $schema[$ln][1];
							$act = true;
						}

						if ($act)
						{
							foreach ($references as $ref => $val)
								$value = str_replace('$'.$ref, $val, $value);
						}

						if ($debug) RheiaMainClass::logError(
							'		> '.$value);
					}
					break;

				case 5: // log
					if ($debug) RheiaMainClass::logError(
						'	log: '.$schema[$ln][1]);

					$error = 'Schema for “$'.$thisObject.$thisProp;
					if (!empty($lang) && isSet(RheiaMainClass::$objects['']
						['schemas'][$thisProp.$lang])) $error .= $lang;
					$error .= '” says: '.$schema[$ln][1];

					foreach ($references as $ref => $val)
						$error = str_replace('$'.$ref, $val, $error);

					$error = str_replace('$this', $thisObject,
						str_replace('##this', $thisProp,
						str_replace('$$this##this', $value,
						$error)));

					// if ($debug) // NO!
						RheiaMainClass::logError($error);

					break;

				case 6: // go
					if ($debug) RheiaMainClass::logError(
						'	go: '.$schema[$ln][1]);

					if ($execute)
					{
						for ($jmp = $ln + 1; $jmp < $lc; ++$jmp)
						{
							if (0 == $schema[$jmp][0] &&
								$schema[$ln][1] == $schema[$jmp][1])
							{
								// $values = '';
								// $curLang = null;
								$execute = false;
								$references = array();
								$ln = $jmp;

								if ($debug) RheiaMainClass::logError(
									'		> '.$ln);
								break;
							}
						}
					}
					break;

				case 7: // default
					if ($debug) RheiaMainClass::logError('	default');

					// $values = '';
					// $curLang = null;
					$execute = true;
					$references = array(0 => $value);
					break;

				case 8: // stop
					if ($debug) RheiaMainClass::logError('	stop');

					if ($execute)
					{
						$ln = $lc;
						if ($debug) RheiaMainClass::logError(
							'		> stopped');
					}
					break;

				case 9: // debug
					if ('on' === $schema[$ln][1])
					{
						if (null === $debug)
						{
							$debug = EOL.'Schema “$'.$thisObject.$thisProp;
							if (!empty($lang) && isSet(
								RheiaMainClass::$objects['']
								['schemas'][$thisProp.$lang]))
								$debug .= $lang;
							$debug .= '” starts; value: '.$value;
							RheiaMainClass::logError($debug);
						}

						$debug = true;
					}
					elseif (null !== $debug) $debug = false;
					break;
				}
			}

			// $value  = '«'.$value.'»('.$schema.')'; // debug
		}

		RheiaMainClass::$schemaSemaphore = false;
		return $value;
	}


	// private static function objectValueIsEmpty($match)
	// {
	// }

	// Štruktúra poľa objektov je opísaná v tele statickej funkcie loadObjects.
	private static function replaceStandardObjects($match)
	{
		$schemaProp = null;

		if (preg_match('/(–[a-z]{2})$/u', $match[1], $lang))
		{
			$match[1] = preg_replace('/–[a-z]{2}$/u', '', $match[1]);
			$lang = $lang[1];
		}
		else $lang = null;

		// echo '<pre>Klasika '.$lang.':'.EOL; var_dump($match); echo '</pre>';

		// if ('#' === $match[1]) $match[1] = RheiaMainClass::$lastObject;
		// else RheiaMainClass::$lastObject = $match[1];

		if (!empty($match[1]) && isSet(RheiaMainClass::$objects[$match[1]]))
		{
			// Hlavná hodnota objektu
			if (empty($match[2]))
			{
				$mark = RheiaMainClass::$objects[$match[1]][0][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects[$match[1]][0][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet(
							$value[RheiaMainClass::$objectsProps['line']]))
						{
							RheiaMainClass::$objectsProps['depleted'] = false;
							if (!empty($value[RheiaMainClass::
								$objectsProps['line']]))
								RheiaMainClass::$objectsProps['empty'] = false;
							$value =
								$value[RheiaMainClass::$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[1];
							return '';
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value)) $value = implode(' ', $value);
				}

				// RheiaMainClass::$lastObject = $match[1];
				return $mark.$value.$mark;
			}

			if (isSet(RheiaMainClass::$objects['']) &&
				isSet(RheiaMainClass::$objects['']['schemas']) &&
				isSet(RheiaMainClass::$objects['']['schemas'][$match[2]]))
				$schemaProp = $match[2];

			// Pridanie jazykového modifikátora
			if (!empty($lang) && isSet(RheiaMainClass::$objects
				[$match[1]][$match[2].$lang]))
			{
				if (isSet(RheiaMainClass::$objects['']) &&
					isSet(RheiaMainClass::$objects['']['schemas']) &&
					isSet(RheiaMainClass::$objects['']['schemas'][$match[2].$lang]))
					$schemaProp = $match[2];

				$match[2] .= $lang;
			}

			// echo '<pre>Over atribút '.$lang.':'.EOL; var_dump($match); echo '</pre>';

			// Spracovanie hodnoty (ak nie je možné, návratom je prázdny
			// reťazec)
			if (isSet(RheiaMainClass::$objects[$match[1]][$match[2]]))
			{
				$mark = RheiaMainClass::$objects[$match[1]]
					[$match[2]][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects
					[$match[1]][$match[2]][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet(
							$value[RheiaMainClass::$objectsProps['line']]))
						{
							RheiaMainClass::$objectsProps['depleted'] = false;
							if (!empty($value[RheiaMainClass::$objectsProps
								['line']])) RheiaMainClass::$objectsProps
									['empty'] = false;
							$value =
								$value[RheiaMainClass::$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[1];
							return '';
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value))
					{
						// Tie prvky atribútov obsahujúcich reťazec „mail“
						// v názve, ktorých hodnota obsahuje znak „@“,
						// sú spracované ako adresy elektronickej pošty.
						if (!empty($match[2]))
						{
							if (false !== stripos($match[2], 'mail'))
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
										$value[$key] = '<a href="'.$mailto.
											'" class="mcds">'.$encode.'</a>';
									}
								}
							}
							elseif (!empty($schemaProp))
							{
								foreach ($value as $key => $val)
								{
									$value[$key] = RheiaMainClass::
										applySchema($match[1], $schemaProp,
											$lang, $val);
								}
							}
						}

						$value = implode(' ', $value);
						// RheiaMainClass::$lastObject = $match[1];
						return $mark.$value.$mark;
					}
				}

				if (!empty($match[2]))
				{
					// Tie atribúty obsahujúce reťazec „mail“
					// v názve, ktorých hodnota obsahuje znak „@“,
					// sú spracované ako adresy elektronickej pošty.
					if (false !== stripos($match[2], 'mail') &&
						false !== strpos($value, '@'))
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
					elseif (!empty($schemaProp))
					{
						$value = RheiaMainClass::applySchema(
							$match[1], $schemaProp, $lang, $value);
					}
				}

				// RheiaMainClass::$lastObject = $match[1];
				return $mark.$value.$mark;
			}
		}

		// Objekt alebo jeho vlastnosť nebola nájdená
		// if (is_array(RheiaMainClass::$objectsProps)) return '';
		// return '$'.$match[1].(isSet($match[2]) ? $match[2] : '');
		return '';
	}

	// For expired (or planed) items chceck the array depletion…
	private static function checkObjectsArrayDepletion($match)
	{
		if (preg_match('/(–[a-z]{2})$/u', $match[2], $lang))
		{
			$match[2] = preg_replace('/–[a-z]{2}$/u', '', $match[2]);
			$lang = $lang[1];
		}
		else $lang = null;

		// if ('#' === $match[2]) $match[2] = RheiaMainClass::$lastObject;
		// else RheiaMainClass::$lastObject = $match[2];

		if (isSet(RheiaMainClass::$objects[$match[2]]))
		{
			if (empty($match[3]))
			{
				$value = RheiaMainClass::$objects[$match[2]][0][1];

				if (is_array(RheiaMainClass::$objectsProps) &&
					is_array($value) && isSet($value[RheiaMainClass::
						$objectsProps['line']]))
						RheiaMainClass::$objectsProps['depleted'] = false;

				// RheiaMainClass::$lastObject = $match[2];
			}
			else
			{
				if (!empty($lang) && isSet(RheiaMainClass::$objects
					[$match[2]][$match[3].$lang])) $match[3] .= $lang;

				if (isSet(RheiaMainClass::$objects[$match[2]][$match[3]]))
				{
					$value = RheiaMainClass::$objects[$match[2]][$match[3]][1];

					if (is_array(RheiaMainClass::$objectsProps) &&
						is_array($value) && isSet($value[RheiaMainClass::
							$objectsProps['line']]))
							RheiaMainClass::$objectsProps['depleted'] = false;

					// RheiaMainClass::$lastObject = $match[2];
				}
			}
		}
	}

	private static function checkEmptyObjectArray($match)
	{
		if (preg_match('/(–[a-z]{2})$/u', $match[1], $lang))
		{
			$match[1] = preg_replace('/–[a-z]{2}$/u', '', $match[1]);
			$lang = $lang[1];
		}
		else $lang = null;

		// echo '<pre>Neprázdne:'.EOL; var_dump($match); echo '</pre>';

		if (isSet(RheiaMainClass::$objects[$match[1]]))
		{
			if (empty($match[2]))
			{
				$value = RheiaMainClass::$objects[$match[1]][0][1];

				if (is_array($value))
				{
					RheiaMainClass::$objectsProps
						['nonempty'] = false;

					foreach ($value as $check)
						if (!empty($check))
						{
							RheiaMainClass::$objectsProps
								['nonempty'] = true;
							break;
						}
				}
			}
			else
			{
				$explode = explode('#', $match[2]);
				// echo '<pre>Explode:'.EOL; var_dump($explode); echo '</pre>';

				foreach ($explode as $x)
				{
					if (!empty($x))
					{
						$x = '#'.$x;

						if (!empty($lang) && isSet(RheiaMainClass::$objects
							[$match[1]][$x.$lang])) $x .= $lang;

						if (isSet(RheiaMainClass::$objects[$match[1]][$x]))
						{
							$value = RheiaMainClass::$objects[$match[1]][$x][1];

							if (is_array($value))
							{
								RheiaMainClass::$objectsProps
									['nonempty'] = false;

								foreach ($value as $check)
									if (!empty($check))
									{
										RheiaMainClass::$objectsProps
											['nonempty'] = true;
										break;
									}

								if (RheiaMainClass::$objectsProps
									['nonempty']) break;
							}
						}
					}
				}
			}
		}

		return '';
	}

	private static function replaceTimedObjects($match)
	{
		$times = explode('-', $match[1]);

		if (isSet($times[1]))
		{
			if (RheiaMainClass::mktimestamp($times[0]) >= RheiaMainClass::
				$currentTime || RheiaMainClass::mktimestamp($times[1])
				<= RheiaMainClass::$currentTime)
			{
				RheiaMainClass::checkObjectsArrayDepletion($match);
				return '';
			}
		}
		elseif (RheiaMainClass::mktimestamp($times[0]) >=
			RheiaMainClass::$currentTime)
		{
			RheiaMainClass::checkObjectsArrayDepletion($match);
			return '';
		}

		if (isSet($match[3])) return RheiaMainClass::replaceStandardObjects(
			array($match[0], $match[2], $match[3]));
		return RheiaMainClass::replaceStandardObjects(
			array($match[0], $match[2]));
	}

	private static function replaceExpiryObjects($match)
	{
		$times = explode('-', $match[1]);

		if (isSet($times[1]))
		{
			if (RheiaMainClass::mktimestamp($times[0]) < RheiaMainClass::$currentTime
				&&
				RheiaMainClass::mktimestamp($times[1]) > RheiaMainClass::$currentTime)
			{
				RheiaMainClass::checkObjectsArrayDepletion($match);
				return '';
			}
		}
		elseif (RheiaMainClass::mktimestamp($times[0]) < RheiaMainClass::$currentTime)
		{
			RheiaMainClass::checkObjectsArrayDepletion($match);
			return '';
		}

		if (isSet($match[3])) return RheiaMainClass::replaceStandardObjects(
			array($match[0], $match[2], $match[3]));
		return RheiaMainClass::replaceStandardObjects(
			array($match[0], $match[2]));
	}

	private static function replaceMatchObjects($match)
	{
		$match[2] = explode('#', $match[2]);
		$compare = RheiaMainClass::replaceStandardObjects(array('', $match[2][0],
			'#'.$match[2][1]));

		if (isSet($match[5]) && empty($compare))
		{
			$match[5] = explode('#', substr($match[5], 1));
			return // $empty =
				RheiaMainClass::replaceStandardObjects(
					array('', empty($match[5][0]) ? $match[2][0] : $match[5][0],
					'#'.(empty($match[5][1]) ? $match[2][1] : $match[5][1])));
		}

		$match[3] = explode('#', $match[3]);
		$true = RheiaMainClass::replaceStandardObjects(
			array('', empty($match[3][0]) ? $match[2][0] : $match[3][0],
			'#'.(empty($match[3][1]) ? $match[2][1] : $match[3][1])));

		if (isSet($match[4]))
		{
			$match[4] = explode('#', substr($match[4], 1));

			$false = RheiaMainClass::replaceStandardObjects(
				array('', empty($match[4][0]) ? $match[2][0] : $match[4][0],
				'#'.(empty($match[4][1]) ? $match[2][1] : $match[4][1])));
		}
		else $false = '';

		// echo '<pre>'.EOL;
		// echo '$compare = '; var_dump($compare);
		// echo '$true = '; var_dump($true);
		// echo '$false = '; var_dump($false);
		// if (isSet($empty)) { echo '$empty = '; var_dump($empty); }
		// echo '</pre>';

		// if (isSet($empty) && empty($compare)) return $empty;
		return $match[1] == $compare ? $true : $false;
	}


	// Štruktúra poľa objektov je opísaná v tele statickej funkcie loadObjects.
	private static function replaceArrayElement($match)
	{
		$schemaProp = null;

		if (preg_match('/(–[a-z]{2})$/u', $match[1], $lang))
		{
			$match[1] = preg_replace('/–[a-z]{2}$/u', '', $match[1]);
			$lang = $lang[1];
		}
		else $lang = null;

		// echo '<pre>Klasika '.$lang.':'.EOL; var_dump($match); echo '</pre>';

		// if ('#' === $match[1]) $match[1] = RheiaMainClass::$lastObject;
		// else RheiaMainClass::$lastObject = $match[1];

		if (isSet(RheiaMainClass::$objects[$match[1]]))
		{
			// Hlavná hodnota objektu – tu by sa to nemalo vyskytnúť…

			if (isSet(RheiaMainClass::$objects['']) &&
				isSet(RheiaMainClass::$objects['']['schemas']) &&
				isSet(RheiaMainClass::$objects['']['schemas'][$match[2]]))
				$schemaProp = $match[2];

			// Pridanie jazykového modifikátora
			if (!empty($lang) && isSet(RheiaMainClass::$objects
				[$match[1]][$match[2].$lang]))
			{
				if (isSet(RheiaMainClass::$objects['']) &&
					isSet(RheiaMainClass::$objects['']['schemas']) &&
					isSet(RheiaMainClass::$objects['']['schemas'][$match[2].$lang]))
					$schemaProp = $match[2];

				$match[2] .= $lang;
			}

			// Spracovanie hodnoty (ak nie je možné, návratom je prázdny reťazec)
			if (isSet(RheiaMainClass::$objects[$match[1]][$match[2]]))
			{
				// $mark = RheiaMainClass::$objects[$match[1]]
				// 	[$match[2]][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects
					[$match[1]][$match[2]][1];

				++RheiaMainClass::$objectsProps; // aj prázdna náhrada sa počíta

				// Ide o prvok poľa, čiže spájanie nepripadá do úvahy
				// a „ideme“ rovno na čítanie a spracovanie prvku
				if (is_array($value))
				{
					$value = $value[(int)$match[3]];

					if (!empty($match[2]))
					{
						// Tie atribúty obsahujúce reťazec „mail“
						// v názve, ktorých hodnota obsahuje znak „@“,
						// sú spracované ako adresy elektronickej pošty.
						if (false !== stripos($match[2], 'mail') &&
							false !== strpos($value, '@'))
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
						elseif (!empty($schemaProp))
						{
							$value = RheiaMainClass::applySchema(
								$match[1], $schemaProp, $lang, $value);
						}
					}

					// RheiaMainClass::$lastObject = $match[1];
					// return $mark.$value.$mark;
					return $value;
				}
			}
		}

		return '';
	}

	private static function buildArrayObjects($pattern)
	{
		/// if (strpos($content, '$') === false) ret $pat; checkedBefore; ///
		RheiaMainClass::$objectsProps =
			array('line' => 0, 'empty' => true, 'depleted' => false);

		$lines = '';

		while (!RheiaMainClass::$objectsProps['depleted'])
		{
			RheiaMainClass::$objectsProps['empty'] = true;
			RheiaMainClass::$objectsProps['depleted'] = true;

			$content = $pattern.EOL;

			$content = preg_replace_callback('/\$\(Neprázdne:'.
				'([\pL–]+)((?:#\pL+)+)\[\]\)/ui', array('RheiaMainClass',
					'checkEmptyObjectArray'), $content);
			/*$content = preg_replace_callback('/\$\(Neprázdne:'.
				'(\pL+)((?:#\pL+)+)\[\]\)/ui', array('RheiaMainClass',
					'checkEmptyObjectArray'), $content);*/
			if (isSet(RheiaMainClass::$objectsProps['nonempty']))
				return RheiaMainClass::$objectsProps['nonempty'] ?
					$content : '';

			$content = preg_replace_callback('/\$([\pL–]+)(#\pL+)?'.
				'\[\]/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content);
			/*$content = preg_replace_callback('/\$(\pL+)(#\pL+)?'.
				'\[\]/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content);*/

			// Zazátvorkované objekty +++
			$content = preg_replace_callback('/\$⦅([\pL–]+)(#\pL+)?\[\]'.
				'⦆/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content);
			// +++ Zazátvorkované objekty

			$content = preg_replace_callback('/\$\(Platí:([^,]+)'.
				',([\pL–]+)(#\pL+)?\[\]\)/ui', array('RheiaMainClass',
					'replaceTimedObjects'), $content);
			/*$content = preg_replace_callback('/\$\(Platí:([^,]+)'.
				',(\pL+)(#\pL+)?\[\]\)/ui', array('RheiaMainClass',
					'replaceTimedObjects'), $content);*/

			$content = preg_replace_callback('/\$\(Neplatí:([^,]+)'.
				',([\pL–]+)(#\pL+)?\[\]\)/ui', array('RheiaMainClass',
					'replaceExpiryObjects'), $content);
			/*$content = preg_replace_callback('/\$\(Neplatí:([^,]+)'.
				',(\pL+)(#\pL+)?\[\]\)/ui', array('RheiaMainClass',
					'replaceExpiryObjects'), $content);*/

			if (!RheiaMainClass::$objectsProps['empty'])
			{
				if (false !== strpos($content, '$['))
				{
					$content = str_replace('$[', '',
						str_replace('$]', '', $content));

					while (false !== ($strpos = strpos($pattern, '$[')))
					{
						$length = 2 + strpos($pattern, '$]',
							$strpos) - $strpos;
						if ($length < 2) $length = 2;
						$pattern = substr_replace($pattern,
							'', $strpos, $length);
					}
				}

				if (preg_match('/class="(?:external-)?download"/i',
					$content) &&
					preg_match('/^<li[^>]*><a +href/i', $content))
				{
					if (preg_match('/^<li.*<\/li>$/i', $pattern))
					{
						$content = '</ul>'.EOL.'<ul class="downloads-list">'.
							$content.'</ul>'.EOL.'<ul>';
					}
				}

				if (false !== stripos($content, '#tab#'))
				{
					if (0 === stripos($content, '<li>'))
					{
						$content =
							preg_replace('~^<li>(class=[„"]([^"“]*)["“])?~ui',
								'</ul>'.EOL.
								'<table class="tableAsItem $2"><tr><td>',
							preg_replace('~</li>(?:[ \r\n\t]*)$~i',
								'</td></tr></table>'.EOL.'<ul>',
							preg_replace('~#tab#~i', '</td><td>', $content)));
					}
					else if (0 === stripos($content, '<p>'))
					{
						$content =
							preg_replace('~^<p>(class=[„"]([^"“]*)["“])?~ui',
								EOL.'<table class="tableAsItem $2"><tr><td>',
							preg_replace('~</p>(?:[ \r\n\t]*)$~i',
								'</td></tr></table>'.EOL,
							preg_replace('~#tab#~i', '</td><td>', $content)));
					}
					else
					{
						if (false !== stripos($content, '<td>'))
							$content = preg_replace('~#tab#~i',
								'</td><td>', $content);
						else
							$content = preg_replace('~#tab#~i',
								' ', $content);
					}
				}
				else if (preg_match(
					'/^<li>#break-list#(?:.|[\r\n])*<\/li>\s+$/i',
					$content))
				{
					$content = '</ul>'.EOL.preg_replace('~^<li>#break-list#~i',
						'<p>', preg_replace('~</li>(?:[ \r\n\t]*)$~i',
							'</p>'.EOL, $content)).EOL.'<ul>';
				}
				else if (preg_match(
					'/^<li>#level-2#(?:.|[\r\n])*<\/li>\s+$/is',
					$content))
				{
					$content = '</ul><ul class="level-2">'.EOL.preg_replace(
						'~^<li>#level-2#~i', '<li>', $content).EOL.
						'</ul><ul>';
				}

				$lines .= str_replace('[]', '', preg_replace(
					'/#break-list#/i', '', $content));
			}
			++RheiaMainClass::$objectsProps['line'];
		}

		/**
		if (!empty($lines))
		{
			$lines = '<!-- '.$pattern.' -->'.$lines;
		}
		/**/

		return $lines;
	}

	private static function addSeparatorCallback($match)
	{
		// $match[0] = '–'; return '{'.implode('|', $match).'}';
		// $match[3] = preg_replace('/^,/', '#', $match[3]);
		// return implode(' :o: ', $match);

		if (preg_match('/(–[a-z]{2})$/u', $match[2], $lang))
		{
			$match[2] = preg_replace('/–[a-z]{2}$/u', '', $match[2]);
			$lang = $lang[1];
		}
		else $lang = null;

		if (isSet(RheiaMainClass::$objects[$match[2]]))
		{
			if (empty($match[3]))
			{
				// $mark = RheiaMainClass::$objects[$match[2]][0][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects[$match[2]][0][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet($value[RheiaMainClass::$objectsProps['line']]))
						{
							// RheiaMainClass::$objectsProps['depleted'] = false;
							// if (!empty($value[RheiaMainClass::
							// 	$objectsProps['line']]))
							// 	RheiaMainClass::$objectsProps['empty'] = false;
							$value = $value[RheiaMainClass::
								$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[2];
							return '<#>';
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value)) $value = implode(' ', $value);
				}

				// RheiaMainClass::$lastObject = $match[2];
				// return $mark.$value.$mark;
				return empty($value) ? '<#>' : $match[1];
			}
			// else
			// {
			// 	$match[3] = preg_replace('/^,/', '#', $match[3]);
			// }


			if (!empty($lang) && isSet(RheiaMainClass::$objects
				[$match[2]][$match[3].$lang])) $match[3] .= $lang;

			if (isSet(RheiaMainClass::$objects[$match[2]][$match[3]]))
			{
				// $mark = RheiaMainClass::$objects[$match[2]]
				// 	[$match[3]][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects
					[$match[2]][$match[3]][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet($value[RheiaMainClass::$objectsProps['line']]))
						{
							// RheiaMainClass::$objectsProps['depleted'] = false;
							// if (!empty($value[RheiaMainClass::$objectsProps['line']]))
							// 	RheiaMainClass::$objectsProps['empty'] = false;
							$value = $value[RheiaMainClass::
								$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[2];
							return '<#>';
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value)) $value = implode(' ', $value);
				}

				/*
				if (!empty($match[3]) && false !== strpos($match[3],
					'mail') && false !== strpos($value, '@'))
				{
					$checksum = 0;
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, false);
					$mailto = 'javascript:mdcs('.
						$encode.', '.$checksum.')';
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, true);
					$value = '<a href="'.$mailto.
						'" class="mcds">'.$encode.'</a>';
				}
				*/

				// RheiaMainClass::$lastObject = $match[3];
				// return $mark.$value.$mark;
				return empty($value) ? '<#>' : $match[1];
			}
		}

		return '<#>';
	}

	private static function addReplacementCallback($match)
	{
		// $match[0] = '–';
		// $match[3] = preg_replace('/^,/', '#', $match[3]);
		// return implode(' :n: ', $match);

		if (preg_match('/(–[a-z]{2})$/u', $match[2], $lang))
		{
			$match[2] = preg_replace('/–[a-z]{2}$/u', '', $match[2]);
			$lang = $lang[1];
		}
		else $lang = null;

		if (isSet(RheiaMainClass::$objects[$match[2]]))
		{
			if (empty($match[3]))
			{
				// $mark = RheiaMainClass::$objects[$match[2]][0][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects[$match[2]][0][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet($value[RheiaMainClass::$objectsProps['line']]))
						{
							// RheiaMainClass::$objectsProps['depleted'] = false;
							// if (!empty($value[RheiaMainClass::
							// 	$objectsProps['line']]))
							// 	RheiaMainClass::$objectsProps['empty'] = false;
							$value = $value[RheiaMainClass::
							$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[2];
							return $match[1];
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value)) $value = implode(' ', $value);
				}

				// RheiaMainClass::$lastObject = $match[2];
				// return $mark.$value.$mark;
				return empty($value) ? $match[1] : '';
			}
			// else
			// {
			// 	$match[3] = preg_replace('/^,/', '#', $match[3]);
			// }

			if (!empty($lang) && isSet(RheiaMainClass::$objects
				[$match[2]][$match[3].$lang])) $match[3] .= $lang;

			if (isSet(RheiaMainClass::$objects[$match[2]][$match[3]]))
			{
				// $mark = RheiaMainClass::$objects[$match[2]]
				// 	[$match[3]][0] ? '<*>' : '';
				$value = RheiaMainClass::$objects
					[$match[2]][$match[3]][1];

				// Koriguje návratovú hodnotu polí vlastností
				if (is_array(RheiaMainClass::$objectsProps))
				{
					if (is_array($value))
					{
						if (isSet($value[RheiaMainClass::$objectsProps['line']]))
						{
							// RheiaMainClass::$objectsProps['depleted'] = false;
							// if (!empty($value[RheiaMainClass::$objectsProps['line']]))
							// 	RheiaMainClass::$objectsProps['empty'] = false;
							$value = $value[RheiaMainClass::
								$objectsProps['line']];
						}
						else
						{
							// RheiaMainClass::$lastObject = $match[2];
							return $match[1];
						}
					}
				}
				else
				{
					++RheiaMainClass::$objectsProps;
					if (is_array($value)) $value = implode(' ', $value);
				}

				/*
				if (!empty($match[3]) && false !== strpos($match[3],
					'mail') && false !== strpos($value, '@'))
				{
					$checksum = 0;
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, false);
					$mailto = 'javascript:mdcs('.
						$encode.', '.$checksum.')';
					$encode = RheiaMainClass::encodeMail(
						$value, $checksum, true);
					$value = '<a href="'.$mailto.
						'" class="mcds">'.$encode.'</a>';
				}
				*/

				// RheiaMainClass::$lastObject = $match[3];
				// return $mark.$value.$mark;
				return empty($value) ? $match[1] : '';
			}
		}

		return $match[1];
	}


	private static function computeExpressionCallback($match)
	{
		$match[1] = html_entity_decode(str_replace('–', '-',
			str_replace('­‑', '-', $match[1])));

		if (0 == preg_match('/^[0-9\-\+\*\/<!=>\|& ]+$/', $match[1])) return '';

		$result = eval('return '.$match[1].';');

		if (isSet($match[2]) && isSet($match[3]) && isSet($match[4]))
		{
			switch (abs($result))
			{
			case 1: $result .= $match[2]; break;
			case 2: case 3: case 4: $result .= $match[3]; break;
			default: $result .= $match[4]; break;
			}
		}
		else if (isSet($match[2]) && isSet($match[3]))
		{
			if ($result)
				$result = $match[2];
			else
				$result = $match[3];
		}

		return $result;
	}


	private static function replaceModules($match)
	{
		if (!isSet($match[1])) return '';

		if (isSet($match[2]))
			$args = preg_split('/, ?/', trim(ltrim($match[2], ',')));
		else
			$args = array();

		foreach ($args as $i => $v) $args[$i] = trim($args[$i]);

		$module = trim($match[1]);
		$include = $module.'.php';
		$return = '';

		if (include_once $include)
		{
			$instance = null;
			eval('$instance = new '.$module.'();');
			if (isSet($instance))
			{
				$return .= $instance->run($args);
				$instance = null;
			}
		}

		return $return;
	}

	private static function replaceIncludes($match)
	{
		$source = html_entity_decode(RheiaMainClass::$contentPath.
			str_replace('­‑', '-', $match[1]));

		if (!empty($source) && file_exists($source))
			return file_get_contents($source);

		// Warning! To really read the file you must convert all syntactically
		// reserved characters in source code, e.g. if the file is:
		//	../htdocs/_result-of-experiment.txt
		// you must write down:
		//	..//htdocs//__result--of--experiment.txt
		// Otherwise the file will not be found…
		// (The file is seeked after the document is translated to HTML)

		return trim(ltrim($match[2], ','));//.'∄'.$source;
	}

	private static function replaceWhens($match)
	{
		return RheiaMainClass::getWhen($match[1]);
	}


	private static function cropText($text, $length = 100)
	{
		// NoShy: '/^\X{'.$length.'}\pL*$/u' &shy; » ­
		if (preg_match('/^\X{'.$length.'}[\pL­]*$/u', $text)) return $text;

		if (preg_match('/^(\X{'.$length.'}[\pL­]*)/u', $text, $match))
		{
			if (preg_match('/<[^>]+$/', $match[1]))
				$match[1] = preg_replace('/<[^>]+$/', '', $match[1]);
			++RheiaMainClass::$cropTextIDNo;

			return 
				'<span id="cropText_ID'.
				RheiaMainClass::$cropTextID.
				RheiaMainClass::$cropTextIDNo.
				'_cropped" '.

				'#crtxtufa#'. // 'onclick="cropTextUnfold(\''.
				RheiaMainClass::$cropTextID.
				RheiaMainClass::$cropTextIDNo.
				'#crtxtufb#'. // '\')" '.

				'class="cropTextCropped" style="display:">'.
				$match[1].'…</span><span id="cropText_ID'.
				RheiaMainClass::$cropTextID.
				RheiaMainClass::$cropTextIDNo.
				'_full" '.

				'#crtxttfa#'. // 'onclick="cropTextFold(\''.
				RheiaMainClass::$cropTextID.
				RheiaMainClass::$cropTextIDNo.
				'#crtxttfb#'. // '\')" '.

				'class="cropTextFull" style="display:none">'.$text.
				'</span>';
		}

		return $text;
	}

	private static function cropTextCallback($match)
	{ return RheiaMainClass::cropText($match[2], $match[1]); }


	private static function createHeading($match)
	{
		return $match[1].mb_strtoupper($match[2], 'UTF-8').$match[3];
	}

	private static function joinArraysCallback($a, $b)
	{
		return $a[1] - $b[1];
	}

	private static function joinArrays($match)
	{
		$sort = preg_match('/ZlúčiťPodľaVýskytu/i', $match[1]);
		$propertyName = trim($match[2]);
		$getProperties = preg_split('/[, ]+/',
			trim($match[3]), -1, PREG_SPLIT_NO_EMPTY);

		$propertyName = ltrim($propertyName, '#');

		foreach ($getProperties as $i => $get)
			if ('#' != $get[0]) $getProperties[$i] = '#'.$get;

		// RheiaMainClass::logError('Destination: #'.$propertyName);
		// RheiaMainClass::logError('Sources: '.print_r($getProperties, true));

		if (preg_match('/(–[a-z]{2})$/u', $propertyName, $lang))
		{
			// $propertyName = preg_replace('/–[a-z]{2}$/u', '', $propertyName);
			$lang = $lang[1];
		}
		else $lang = null;

		foreach (RheiaMainClass::$objects as $objectName => $object)
			if (!empty($objectName))
			{
				$work = array();

				foreach ($getProperties as $get)
				{
					if (isSet($object[$get]))
					{
						$schemaProp = null;

						if (isSet(RheiaMainClass::$objects['']) &&
							isSet(RheiaMainClass::$objects['']['schemas']) &&
							isSet(RheiaMainClass::$objects['']['schemas'][$get]))
							$schemaProp = $get;

						// Pridanie jazykového modifikátora
						if (!empty($lang) && isSet(RheiaMainClass::$objects
							[$objectName][$get.$lang]))
						{
							if (isSet(RheiaMainClass::$objects['']) &&
								isSet(RheiaMainClass::$objects['']['schemas']) &&
								isSet(RheiaMainClass::$objects['']['schemas'][$get.$lang]))
								$schemaProp = $get;

							$get .= $lang;
						}

						if (null === $object[$get][0])
						{
							foreach ($object[$get][1] as $i => $value)
							{
								// Tie atribúty obsahujúce reťazec „mail“
								// v názve, ktorých hodnota obsahuje znak „@“,
								// sú spracované ako adresy elektronickej
								// pošty.
								if (false !== stripos($get, 'mail') &&
									false !== strpos($value, '@'))
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
								elseif (!empty($schemaProp))
								{
									$value = RheiaMainClass::applySchema(
										$objectName, $schemaProp, $lang,
										$value);
								}

								$work[] = array($value,
									$object[$get][2][$i], $get);
							}
						}
						else
						{
							$value = $object[$get][1];

							// Tie atribúty obsahujúce reťazec „mail“
							// v názve, ktorých hodnota obsahuje znak „@“,
							// sú spracované ako adresy elektronickej pošty.
							if (false !== stripos($get, 'mail') &&
								false !== strpos($value, '@'))
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
							elseif (!empty($schemaProp))
							{
								$value = RheiaMainClass::applySchema(
									$objectName, $schemaProp, $lang, $value);
							}

							$work[] = array($value, $object[$get][2], $get);
						}
					}
				}

				if ($sort) usort($work, array('RheiaMainClass',
					'joinArraysCallback'));
				// RheiaMainClass::logError($objectName.': '.print_r($work, true));

				foreach ($work as $value)
				{
					RheiaMainClass::addPropertyValue($value[1],
						$objectName, null, $propertyName, $value[0]);
				}

				// if (isSet(RheiaMainClass::$objects[$objectName]['#'.$propertyName]))
				// 	RheiaMainClass::logError($objectName.'#'.$propertyName.': '.
				// 		print_r(RheiaMainClass::$objects[$objectName]['#'.$propertyName],
				// 			true));
				// else
				// 	RheiaMainClass::logError('#WARNING: '.$objectName.
				// 		'’s list is empty!'.EOL2);
			}


		return '';
	}


	public static function replaceObjects($content)
	{
		global $articlesSource, $articleID;

		$content =
			preg_replace_callback(
				RheiaMainClass::$moduleRegex,
					array('RheiaMainClass', 'replaceModules'),
			preg_replace_callback(
				RheiaMainClass::$includeRegex,
					array('RheiaMainClass', 'replaceIncludes'),
			preg_replace_callback('/\$when\(([0-9]+)\)/',
					array('RheiaMainClass', 'replaceWhens'),
			str_replace('$articleID', $articleID,
			str_replace('$articlesSource', $articlesSource,
			str_replace('$currentYear',
				date('Y', RheiaMainClass::$currentTime),
			str_replace('$currentMonth',
				date('n', RheiaMainClass::$currentTime),
			str_replace('$currentDay',
				date('j', RheiaMainClass::$currentTime),
				$content))))))));

			// ()+,?
		$content = preg_replace_callback('/\((Zlúčiť|ZlúčiťPodľaVýskytu): *'.
			'([\pL–]+) *= *([^\)]+)\)/ui', array('RheiaMainClass',
				'joinArrays'), $content);

		// Replace standard (also timed) values
		for ($i = 0; $i < 25; ++$i)
		{
			if (strpos($content, '$') === false) break;

			// RheiaMainClass::$lastObject = '#';
				RheiaMainClass::$objectsProps = 0;

			$content = preg_replace_callback('/\$([\pL–]+)(?![\pL–])(?!#)'.
				'/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content);
			/* $content = preg_replace_callback('/\$(\pL+)(?!\pL)(?!#)'.
				'/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content); */
			$content = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
				'(?![\pL–])(?!\[)/u', array('RheiaMainClass',
					'replaceStandardObjects'), $content);
			/*$content = preg_replace_callback('/\$(\pL+)(#\pL+)'.
				'(?!\pL)(?!\[)/u', array('RheiaMainClass',
					'replaceStandardObjects'), $content); */
			$content = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
				'?$/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content);
			/* $content = preg_replace_callback('/\$(\pL+)(#\pL+)'.
				'?$/u', array('RheiaMainClass', 'replaceStandardObjects'),
				$content); */

			// Zazátvorkované objekty +++
			$content = preg_replace_callback('/\$⦅([\pL–]+)⦆/u',
				array('RheiaMainClass', 'replaceStandardObjects'), $content);
			$content = preg_replace_callback('/\$⦅([\pL–]+)(#\pL+)?⦆/u',
				array('RheiaMainClass', 'replaceStandardObjects'), $content);
			// +++ Zazátvorkované objekty

			$content = preg_replace_callback('/\$([\pL–]+)(#\pL+)'.
				'\[([0-9]+)\]/u', array('RheiaMainClass',
					'replaceArrayElement'), $content);

			$content = preg_replace_callback('/\$\(Platí:([^,]+)'.
				',([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'replaceTimedObjects'), $content);
			/*$content = preg_replace_callback('/\$\(Platí:([^,]+)'.
				',(\pL+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'replaceTimedObjects'), $content);*/

			$content = preg_replace_callback('/\$\(Neplatí:([^,]+)'.
				',([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'replaceExpiryObjects'), $content);
			/*$content = preg_replace_callback('/\$\(Neplatí:([^,]+)'.
				',(\pL+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'replaceExpiryObjects'), $content);*/

			$content = preg_replace_callback('/\$\(Zhoduje:([^,]*),'.
				'([\pL–]+#\pL+),((?:[\pL–]+)?(?:#\pL+)?)'.
				'(,(?:[\pL–]+)?(?:#\pL+)?)?'.
				'(,(?:[\pL–]+)?(?:#\pL+)?)?'.
				'\)/ui', array('RheiaMainClass', 'replaceMatchObjects'),
				$content);

			$content = preg_replace_callback('/\$\(Oddeľovač:([^,]+)'.
				',([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'addSeparatorCallback'), $content);

			$content = preg_replace_callback('/\$\(Náhrada:([^,]+)'.
				',([\pL–]+)(#\pL+)?\)/ui', array('RheiaMainClass',
					'addReplacementCallback'), $content);
			/*$content = preg_replace_callback('/\$\(Oddeľovač:([^,]+)'.
				',(\pL+)(,\pL+)?\)/ui', array('RheiaMainClass',
					'addSeparatorCallback'), $content);*/

			if (0 === RheiaMainClass::$objectsProps) break;
		}

		// return $content;

		// Replace array properties
		for ($i = 0; $i < 500; ++$i)
		{
			$strpos = strpos($content, '[]');
			if (false === $strpos || false ===
				strpos($content, '$')) break;

			// RheiaMainClass::$lastObject = '#';
			// echo EOL.'$strpos: '.$strpos.EOL2;

			$substr = substr($content, 0, $strpos);
			$blockStart = strrpos($substr, '<tr');
			if (false === $blockStart) $blockStart = -1;
			$blockType = '</tr>';

			// echo EOL.'$substr: '.$substr.EOL.'$blockStart: '; var_dump($blockStart); EOL.'$blockType: '.$blockType.EOL2;

			$strrpos = strrpos($substr, '<li');
			if (false !== $strrpos && $blockStart < $strrpos)
			{
				$blockStart = $strrpos;
				$blockType = '</li>';
			}

			// echo '$strrpos: '; var_dump($strrpos); echo EOL.'$blockStart: '; var_dump($blockStart); EOL.'$blockType: '.$blockType.EOL2;

			$strrpos = strrpos($substr, '<p');
			if (false !== $strrpos && $blockStart < $strrpos)
			{
				$blockStart = $strrpos;
				$blockType = '</p>';
			}

			for ($j = 1; $j <= 4; ++$j)
			{
				$strrpos = strrpos($substr, '<h'.$j);
				if (false !== $strrpos && $blockStart < $strrpos)
				{
					$blockStart = $strrpos;
					$blockType = '</h'.$j.'>';
				}
			}

			// echo '$strrpos: '; var_dump($strrpos); echo EOL.'$blockStart: '; var_dump($blockStart); EOL.'$blockType: '.$blockType.EOL2.EOL;

			if (0 > $blockStart) break;

			$blockLength = strpos($content, $blockType,
				$strpos) - $blockStart + strlen($blockType);
			$substr = substr($content, $blockStart, $blockLength);

			if ('</tr>' === $blockType)
			{
				$mailTypeBackup = RheiaMainClass::$mailType;
				RheiaMainClass::$mailType = 'table';
				$substr = RheiaMainClass::buildArrayObjects($substr);
				RheiaMainClass::$mailType = $mailTypeBackup;
			}
			else
				$substr = RheiaMainClass::buildArrayObjects($substr);

			$content = substr_replace($content,
				$substr, $blockStart, $blockLength);

			// break;
		}

		$content = preg_replace('/<(p|h[1-9])[^>]*>\s*<\*>/', '', $content);
		$content = preg_replace('/<\*>\s*<\/(p|h[1-9])[^>]*>/', '',
			$content);
		$content = preg_replace('/<[\*#]>/', '', $content);

		// Compute expressions
		for ($i = 0; $i < 50; ++$i)
		{
			if (strpos($content, '$(=') === false) break;
			$content = preg_replace_callback('/\$\(=([^,\(\)]+),'.
				'([^,\(\)]+),([^,\(\)]+),([^,\(\)]+)\)/u', array(
					'RheiaMainClass', 'computeExpressionCallback'), $content);
			$content = preg_replace_callback('/\$\(=([^,\(\)]+),'.
				'([^,\(\)]*),([^,\(\)]*)\)/u', array(
					'RheiaMainClass', 'computeExpressionCallback'), $content);
			$content = preg_replace_callback('/\$\(=([^,\(\)]+)\)/u',
				array('RheiaMainClass', 'computeExpressionCallback'),
				$content);
		}

		$content = preg_replace('/\$\(Skrátiť,? *[0-9]+: *\)/ui',
			'', $content);

		$content = preg_replace_callback(
			'/\$\(Skrátiť,? *([0-9]+):(\X+?)\)/ui',
			array('RheiaMainClass', 'cropTextCallback'), $content);

		$content = preg_replace('/\$\(Hlavička: *\)/ui', '', $content);

		$content = preg_replace_callback('/\$\(Hlavička:( *(?:<[^>]+>)+\PL*)'.
			'(\pL)(\X*?)\)/ui', array('RheiaMainClass', 'createHeading'),
			$content);

		$content = preg_replace_callback(
			'/\$\(Hlavička:(\PL*)(\pL)(\X*?)\)/ui',
			array('RheiaMainClass', 'createHeading'), $content);

		// Replace double dots:
		$content = preg_replace('~(?<=\.)((<[^>]+>)*)\.~', '$1', $content);

		// cropText patch:
		foreach (array(
			'#crtxtufa#' => 'onclick="cropTextUnfold(\'',
			'#crtxtufb#' => '\')" ',
			'#crtxttfa#' => 'onclick="cropTextFold(\'',
			'#crtxttfb#' => '\')" ',
			) as $key => $val)
		{ $content = str_replace($key, $val, $content); }

		return RheiaMainClass::solveInternalRedirects($content);
	}


	// Štruktúra poľa objektov je opísaná v tele statickej funkcie loadObjects.
	public static function joinObjects(&$objects1, &$objects2)
	{
		foreach ($objects2 as $id => $def)
		{
			if ('' === $id)
			{
				/*
				IGNORUJ – súbory sa pripájajú/registrujú na inom mieste
				$objects1['']['files'] = array_merge(
					$objects1['']['files'], $def['files']);
				$objects1['']['externFiles'] = array_merge(
					$objects1['']['externFiles'], $def['externFiles']);
				*/

				if (isSet($def['schemas']))
				{
					if (isSet($objects1['']) &&
						isSet($objects1['']['schemas']))
					{
						foreach ($def['schemas'] as $id2 => $val)
							$objects1['']['schemas'][$id2] = $val;
					}
					else
					{
						$objects1['']['schemas'] = $def['schemas'];
					}
				}
			}
			elseif (isSet($objects1[$id]))
			{
				foreach ($def as $id2 => $val)
				{
					if (isSet($objects1[$id][$id2]))
					{
						if (null === isSet($objects1[$id][$id2][0]) ||
							null === $val[0])
						{
							if (null === $objects1[$id][$id2][0])
								$array1 = $objects1[$id][$id2][1];
							else
								$array1 = array($objects1[$id][$id2][1]);

							if (null === $val[0])
								$array2 = $val[1];
							else
								$array2 = array($val[1]);

							$objects1[$id][$id2][0] = null;
							$objects1[$id][$id2][1] =
								array_merge($array1, $array2);
						}
						else
						{
							if ($val[0]) $objects1[$id][$id2][0] = true;
							if (isSet($val[1]))
								$objects1[$id][$id2][1] .= $val[1];
						}
					}
					else $objects1[$id][$id2] = $val;
				}
			}
			else $objects1[$id] = $def;
		}
	}

	public static function loadObjects()
	{
		/**
		 * Štruktúra poľa objektov:
		 *
		 * Pole objektov sa skladá z asociatívnych prvkov s takouto
		 * štruktúrou:
		 *
		 *  'id' => array(
		 *      0 => definícia hodnoty,
		 *      '#vlastnosť1' => definícia hodnoty,
		 *      '#vlastnosť2' => definícia hodnoty,
		 *      '#vlastnosť3' => definícia hodnoty,
		 *      …
		 *    ),
		 *
		 * Kde definícia hodnoty je pole s tromi prvkami tohto významu:
		 *
		 *   0 => hodnota tohto prvku určuje typ obsahu ďalších dvoch prvkov;
		 *        ak má tento prvok nasledujúcu hodnotu, tak prvky 1 a 2
		 *        majú nasledujúci obsah:
		 *          null  – polia hodnôt,
		 *          false – jednoduchú (plain) hodnotu,
		 *          true  – generovaný HTML obsah;
		 *   1 => v prípade, že prvok 0 bol typu boolean, tento prvok obsahuje
		 *        jednoduchú/generovanú hodnotu typu string, inak obsahuje
		 *        pole hodnôt (typu string);
		 *   2 => index riadka, na ktorom sa definícia vlastnosti skončila,
		 *        alebo pole hodnôt s rovnakým významom pre
		 *        jednotlivé prvky poľa hodnôt vlastnosti.
		 *        ———
		 *        (V prípade, že prvok 0 bol typu boolean, tento prvok
		 *        obsahuje jednoduchú číselnú hodnotu, inak obsahuje pole
		 *        číselných hodnôt.)
		 *
		 * Výnimku tvorí prvok prázdneho id: '', ktorý je rezerovovaný na
		 * konfiguračné účely…
		 */

		if (null === RheiaMainClass::$objects)
		{
			RheiaMainClass::reloadObjects();

			// Nahraj globálne objekty do katedrových stránok
			// a jazykových mutácií
			if (RheiaMainClass::$contentPath !== '../content/')
			{
				$objects = RheiaMainClass::$objects;
				$contentPath = RheiaMainClass::$contentPath;
				$parsedPath = RheiaMainClass::$parsedPath;

				// Načítavanie objektov prechodom do nadradenej úrovne
				// sa vykoná dva razy, čím sú pokryté katedrové stránky
				// a jazykové mutácie hlavnej a katedrových stránok.
				for ($i = 0; $i < 2; ++$i)
				{
					RheiaMainClass::$objects = array();
					RheiaMainClass::$contentPath .= '../';
					RheiaMainClass::$parsedPath .= '../';
					RheiaMainClass::reloadObjects();
					RheiaMainClass::joinObjects($objects,
						RheiaMainClass::$objects);

					if (isSet(RheiaMainClass::$objects['']))
					{
						if (isSet(RheiaMainClass::$objects['']['files']))
						{
							if (isSet($objects['']) &&
								isSet($objects['']['files']))
							{
								RheiaMainClass::addFilesToRegister(
									$objects['']['files'],
									RheiaMainClass::$objects['']['files']);
							}
							else
							{
								$objects['']['files'] =
									RheiaMainClass::$objects['']['files'];
							}
						}

						if (isSet(RheiaMainClass::$objects['']['externFiles']))
						{
							if (isSet($objects['']) &&
								isSet($objects['']['externFiles']))
							{
								RheiaMainClass::addExternFilesToRegister(
									$objects['']['externFiles'],
									RheiaMainClass::$objects['']['externFiles']);
							}
							else
							{
								$objects['']['externFiles'] =
									RheiaMainClass::$objects['']['externFiles'];
							}
						}

						if (isSet(RheiaMainClass::$objects['']
							['appendSources']))
						{
							if (isSet($objects['']) &&
								isSet($objects['']['appendSources']))
							{
								RheiaMainClass::addFilesToRegister(
									$objects['']['appendSources'],
									RheiaMainClass::$objects['']
									['appendSources']);
							}
							else
							{
								$objects['']['appendSources'] =
									RheiaMainClass::$objects['']
									['appendSources'];
							}
						}
					}
				}

				/* ??? neúspešný pokus…
				RheiaMainClass::$objects = array();
				RheiaMainClass::$contentPath =
					RheiaMainClass::$downloadLevel.'../content/';
				RheiaMainClass::$parsedPath =
					RheiaMainClass::$downloadLevel.'../parsed/';
				RheiaMainClass::reloadObjects();
				RheiaMainClass::joinObjects(RheiaMainClass::$objects, $objects);

				if (isSet($objects['']))
				{
					if (isSet($objects['']['files']))
					{
						if (isSet(RheiaMainClass::$objects['']) &&
							isSet(RheiaMainClass::$objects['']['files']))
						{
							RheiaMainClass::addFilesToRegister(
								RheiaMainClass::$objects['']['files'],
								$objects['']['files']);
						}
						else
						{
							RheiaMainClass::$objects['']['files'] =
								$objects['']['files'];
						}
					}

					if (isSet($objects['']['externFiles']))
					{
						if (isSet(RheiaMainClass::$objects['']) &&
							isSet(RheiaMainClass::$objects['']['externFiles']))
						{
							RheiaMainClass::addExternFilesToRegister(
								RheiaMainClass::$objects['']['externFiles'],
								$objects['']['externFiles']);
						}
						else
						{
							RheiaMainClass::$objects['']['externFiles'] =
								$objects['']['externFiles'];
						}
					}
				}
				*/

				RheiaMainClass::$objects = $objects;
				RheiaMainClass::$contentPath = $contentPath;
				RheiaMainClass::$parsedPath = $parsedPath;
			}
		}
	}


	private static function addFilesToRegister(&$register, &$records)
	{
		foreach ($records as $file1)
		{
			$notFound = true;

			foreach ($register as $file2)
				if ($file1['originalName'] == $file2['originalName'])
				{
					$notFound = false;
					break;
				}

			if ($notFound) $register[] = $file1;
		}
	}

	private static function addExternFilesToRegister(&$register, &$records)
	{
		foreach ($records as $file1)
		{
			$notFound = true;

			foreach ($register as $file2)
				if ($file1['url'] == $file2['url'])
				{
					$notFound = false;
					break;
				}

			if ($notFound) $register[] = $file1;
		}
	}


	public static function getCached($source)
	{
		if (isSet(RheiaMainClass::$rheiasCache[$source]))
		{
			// RheiaMainClass::logError('Retrieve from Cache: '.$source);
			return RheiaMainClass::$rheiasCache[$source];
		}

		// RheiaMainClass::logError('Storing to Cache: '.$source);
		return RheiaMainClass::$rheiasCache[$source] =
			new RheiaMainClass($source, false);
	}


	// Štruktúra poľa objektov je opísaná v tele statickej funkcie loadObjects.
	public static function reloadObjects()
	{
		$source = RheiaMainClass::$contentPath.RheiaMainClass::
			$objectsFileBaseName.'.txo';
		$parsed = RheiaMainClass::$parsedPath.RheiaMainClass::
			$objectsFileBaseName.'-o.php';
		$mustRevalidate = true;
		RheiaMainClass::$lastProcessed = $source;

		if (!file_exists($source)) return;

		$sourceDate = filemtime($source);
		if (empty(RheiaMainClass::$objectsNewestDate) ||
			$sourceDate > RheiaMainClass::$objectsNewestDate)
			RheiaMainClass::$objectsNewestDate = $sourceDate;
		$parsedDated = 0;

		if (file_exists($parsed) && ($sourceDate <=
			($parsedDated = filemtime($parsed))))
		{
			include './'.$parsed;
			$mustRevalidate = false;

			if (isSet(RheiaMainClass::$objects['']))
			{
				if (isSet(RheiaMainClass::$objects['']['files']))
					foreach (RheiaMainClass::$objects['']['files'] as $file)
					{
						$exists = false; $fileSize = null; $fileDate = null;
						RheiaMainClass::getFileInfo($file['originalName'],
							$exists, $fileSize, $fileDate);

						if ($file['exists'] != $exists ||
							$file['fileSize'] != $fileSize ||
							$file['fileDate'] != $fileDate)
						{
							$mustRevalidate = true;
							// echo 'mustRevalidate (HTML file): '.$file['originalName'].EOL;
							break;
						}
					}

				if (RheiaMainClass::$checkExtern &&
					isSet(RheiaMainClass::$objects['']['externFiles']))
					foreach (RheiaMainClass::$objects['']['externFiles'] as $file)
					{
						$exists = false; $fileSize = null; $fileDate = null;
						RheiaMainClass::getExternFileInfo($file['url'],
							$exists, $fileSize, $fileDate);

						if ($file['exists'] != $exists ||
							$file['fileSize'] != $fileSize ||
							$file['fileDate'] != $fileDate)
						{
							$mustRevalidate = true;
							// echo 'mustRevalidate (HTML external file): '.$file['url'].EOL;
							break;
						}
					}

				if (isSet(RheiaMainClass::$objects['']['appendSources']))
					foreach (RheiaMainClass::$objects['']['appendSources'] as
						$appendSource)
					{
						if (file_exists($appendSource['originalName']) &&
							($parsedDated <=
								filemtime($appendSource['originalName'])))
						{
							$mustRevalidate = true;
							break;
						}
					}
			}
		}

		if ($mustRevalidate)
		{
			$parseFiles[] = array(null, $source);
			RheiaMainClass::$objects = array();
			$objectRheiaClass = new RheiaMainClass();

			for ($parserCounter = 0; $parserCounter <
				count($parseFiles); ++$parserCounter)
			{
				// RheiaMainClass::logError('#Objects: Pridávam „'.
				// 	$parseFiles[$parserCounter][1].'“. Predvolený objekt: „'.
				// 	$parseFiles[$parserCounter][0].'“.');

				$objectName = $parseFiles[$parserCounter][0];

				$propertyName = null;
				$valueContent = ''; $grabContent = false;
				$storeProperty = false; $rawGrab = false;

				// Prepare the string (split to lines)
				$lines = explode('<br />', nl2br(file_get_contents(
					$parseFiles[$parserCounter][1])));

				// Trim whitespace
				foreach ($lines as $i => $line)
					$lines[$i] = preg_replace('/[ \r\n]+/',
						' ', rtrim(ltrim($line), ' '.EOL));

				// Parse objects definitions
				foreach ($lines as $i => $line)
				{
					if (!empty($line) && (';' === $line[0]))
					{
						// Ignore comments (but not empty lines!)
						continue;
					}

					// if (false !== strpos($line, 'pridať'))
					// {
					// 	RheiaMainClass::logError('#Objects: Riadok '.
					// 		'obsahujúci pridať: '.$line);
					// 	if (preg_match(
					// 		'/^#(append|prida[jť]):[  ]*([-+\pN\pL]+)[  ]*$/ui',
					// 		$line, $matches))
					// 		RheiaMainClass::logError('#Objects: Zhoda: '.
					// 			$matches);
					// 	else
					// 		RheiaMainClass::logError('#Objects: Nezhoda.');
					// }

					if ($grabContent)
					{
						if (preg_match('/^<--[  ]*(end|koniec)[  ]*$/i',
							$line))
						{
							$storeProperty = true;
							if (preg_match('/schemas/i', $objectName))
							{
								$valueContent =
									RheiaMainClass::parseSchema($valueContent);
							}
							else if (!$rawGrab)
							{
								$valueContent = $objectRheiaClass->
									generateHTML($valueContent);
							}
						}
						else
						{
							$valueContent .= $line.EOL;
						}
					}
					elseif (preg_match(
						'/^\$(\pL+):[  ]*(start|začiatok)'.
						'[  ]*-->[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = $matches[3].EOL;
						$grabContent = true;
						$rawGrab = false;
					}
					elseif (preg_match('/^\$(\pL+)\[\]:[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$objectRheiaClass->handleSpecialCodes($matches[2]);
						$valueContent = $matches[2];
						$grabContent = null;
						$rawGrab = false;
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+)\[\][  ]*$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						$grabContent = null;
						$rawGrab = false;
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+):[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$objectRheiaClass->handleSpecialCodes($matches[2]);
						$valueContent = $matches[2];
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+)[  ]*$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						$storeProperty = true;
					}
					elseif (preg_match('/^\£(\pL+)[  ]*$/ui',
						$line, $matches))
					{
						// $objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						// $storeProperty = true;
						if (!isSet(RheiaMainClass::$objects[$objectName]))
							echo '<b>Varovanie:</b> objekt „'.$objectName.
								'“ nejestvuje!';
					}
					elseif (preg_match(
						'/^#([\pL–]+):[  ]*(start|začiatok)'.
						'[  ]*-->[  ]*(.*)$/ui',
						$line, $matches))
					{
						$propertyName = $matches[1];
						$valueContent = $matches[3].EOL;
						$grabContent = true;
						$rawGrab = false;
					}
					elseif (preg_match(
						'/^#(append|prida[jť]):[  ]*([-+\pN\pL]+)[  ]*$/ui',
						$line, $matches))
					{
						// Kvázi „šablóna“ objektov – tieto zdrojové
						// súbory .txo budú spracované po spracovaní
						// aktuálneho zdrojového súboru (ak jestvujú
						// a ak už neboli raz zaradené do reťaze
						// spracovania):
						$appendSource = RheiaMainClass::$contentPath.
							'__'.$matches[2].'.txo';

						// RheiaMainClass::logError(
						// 	'#Objects: Zaraďujem „'.$appendSource.'“.');

						if (file_exists($appendSource))
						{
							$pN = count($parseFiles);

							for ($pC = 0; $pC < $pN; ++$pC)
								if ($parseFiles[$pC][0] === $objectName &&
									$parseFiles[$pC][1] === $appendSource)
									$appendSource = null;

							if (null !== $appendSource)
								$parseFiles[] = array($objectName,
									$appendSource);


							if (isSet(RheiaMainClass::$objects['']) &&
								isSet(RheiaMainClass::$objects['']
									['appendSources']))
							{
								addFilesToRegister(RheiaMainClass::
									$objects['']['appendSources'],
									array('originalName' => $appendSource));
							}
							else
							{
								RheiaMainClass::$objects['']['appendSources'][] =
									array('originalName' => $appendSource);
							}
						}
						// else RheiaMainClass::logError('#Objects: Súbor „'.
						// 	$appendSource.'“ nejestvuje.');
					}
					elseif (preg_match('/^#([\pL–]+)\[\]:[  ]*(.*)$/ui',
						$line, $matches))
					{
						$propertyName = $matches[1];
						$objectRheiaClass->handleSpecialCodes($matches[2]);
						$valueContent = $matches[2];
						$grabContent = null;
						$rawGrab = false;
						$storeProperty = true;
					}
					elseif (preg_match('/^#([\pL–]+):[  ]*(.*)$/ui',
						$line, $matches))
					{
						$propertyName = $matches[1];
						$objectRheiaClass->handleSpecialCodes($matches[2]);
						$valueContent = $matches[2];
						$storeProperty = true;
					}
					// Ešte skúsime „raw“ hodnoty:
					elseif (preg_match(
						'/^\$(\pL+)•:[  ]*(start|začiatok)'.
						'[  ]*-->[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = $matches[3].EOL;
						$grabContent = true;
						$rawGrab = true;
					}
					elseif (preg_match('/^\$(\pL+)\[\]•:[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = $matches[2];
						$grabContent = null;
						$rawGrab = true;
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+)\[\]•[  ]*$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						$grabContent = null;
						$rawGrab = true;
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+)•:[  ]*(.*)$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = $matches[2];
						$storeProperty = true;
					}
					elseif (preg_match('/^\$(\pL+)•[  ]*$/ui',
						$line, $matches))
					{
						$objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						$storeProperty = true;
					}
					elseif (preg_match('/^\£(\pL+)•[  ]*$/ui',
						$line, $matches))
					{
						// $objectRheiaClass->reset();
						$objectName = $matches[1];
						$propertyName = null;
						$valueContent = '';
						// $storeProperty = true;
						if (!isSet(RheiaMainClass::$objects[$objectName]))
							echo '<b>Varovanie:</b> objekt „'.$objectName.
								'“ nejestvuje!';
					}
					elseif (preg_match(
						'/^#([\pL–]+)•:[  ]*(start|začiatok)'.
						'[  ]*-->[  ]*(.*)$/ui',
						$line, $matches))
					{
						$propertyName = $matches[1];
						$valueContent = $matches[3].EOL;
						$grabContent = true;
						$rawGrab = true;
					}
					elseif (preg_match('/^#([\pL–]+)\[\]•:[  ]*(.*)$/ui', 
						$line, $matches))
					{
						$propertyName = $matches[1];
						$valueContent = $matches[2];
						$grabContent = null;
						$rawGrab = true;
						$storeProperty = true;
					}
					elseif (preg_match('/^#([\pL–]+)•:[  ]*(.*)$/ui', $line,
						$matches))
					{
						$propertyName = $matches[1];
						$valueContent = $matches[2];
						$storeProperty = true;
					}
					// Inak chyba
					elseif (!empty($line))
					{
						echo '<!-- Syntaktická chyba: „'.$line.'“ -->'.EOL;
					}

					if ($storeProperty)
					{
						$storeProperty = false;
						if (preg_match('/schemas/i', $objectName))
						{
							if (!empty($propertyName))
								RheiaMainClass::$objects['']['schemas']
									['#'.$propertyName] = $valueContent;
						}
						elseif (!empty($objectName))
						{
							if ($propertyName === null)
							{
								RheiaMainClass::addPropertyValue($i,
									$objectName, $grabContent,
									$objectRheiaClass->replaceCodes(
										$valueContent));
							}
							elseif (!empty($propertyName))
							{
								RheiaMainClass::addPropertyValue($i,
									$objectName, $grabContent, $propertyName,
									$objectRheiaClass->replaceCodes(
										$valueContent));
							}
							else echo '<!-- Neplatný názov '.
								'vlastnosti! -->'.EOL;
						}
						else echo '<!-- Neplatný názov objektu! -->'.EOL;
						$grabContent = false;
						$rawGrab = false;

						if (isSet($objectRheiaClass->files))
						{
							if (isSet(RheiaMainClass::$objects['']) &&
								isSet(RheiaMainClass::$objects['']['files']))
							{
								RheiaMainClass::addFilesToRegister(
									RheiaMainClass::$objects['']['files'],
									$objectRheiaClass->files);
							}
							else
							{
								RheiaMainClass::$objects['']['files'] =
									$objectRheiaClass->files;
							}

							$objectRheiaClass->files = null;
						}

						if (isSet($objectRheiaClass->externFiles))
						{
							if (isSet(RheiaMainClass::$objects['']) &&
								isSet(RheiaMainClass::$objects['']
									['externFiles']))
							{
								RheiaMainClass::addExternFilesToRegister(
									RheiaMainClass::$objects['']['externFiles'],
									$objectRheiaClass->externFiles);
							}
							else
							{
								RheiaMainClass::$objects['']['externFiles'] =
									$objectRheiaClass->externFiles;
							}

							$objectRheiaClass->externFiles = null;
						}
					}
				}


				// Check for values to be sorted and sort them
				/* */
				foreach (RheiaMainClass::$objects as $object => $properties)
				{
					if (!empty($object)) foreach ($properties as
						$property => $propCont)
					{
						if (isSet($propCont[3]))
						{
							$count = count($propCont[3]);

							$sortStart = $sortEnd = -1;
							$sortType = null;

							for ($i = 0; $i <= $count; ++$i)
							{
								if ($i == $count ||
									$sortType !== $propCont[3][$i][0])
								{
									$sortEnd = $i;

									if (null !== $sortType)
									{
										$sortArray = array();
										$storeElements = array();

										for ($j = $sortStart;
											$j < $sortEnd; ++$j)
										{
											$sortArray[] = $propCont[3][$j][1];
											$storeElements[] = array(
												$propCont[1][$j],
												$propCont[2][$j],
												$propCont[3][$j]);
										}

										if ('a' === $sortType)
											asort($sortArray);
											//, SORT_NATURAL);
										elseif ('d' === $sortType)
											arsort($sortArray);
											//, SORT_NATURAL);
										else
											echo '<!-- Neznámy spôsob '.
												'triedenia -->';

										// echo '<h3>'.$object.','.
										// 	$property.', '.$sortStart.
										// 	' – '.$sortEnd.' ('.$sortType.
										// 	')</h3>'.EOL;

										$j = 0;
										foreach ($sortArray as $key => $val)
										{
											// echo '<p>'.$key.' = '.$val.'</p>'.EOL;

											RheiaMainClass::$objects[$object]
												[$property][1]
												[$sortStart + $j] =
													$storeElements[$key][0];
											RheiaMainClass::$objects[$object]
												[$property][2]
												[$sortStart + $j] =
													$storeElements[$key][1];
											RheiaMainClass::$objects[$object]
												[$property][3]
												[$sortStart + $j] =
													$storeElements[$key][2];

											++$j;
										}
									}

									$sortStart = $i;
									if ($i < $count) $sortType =
										$propCont[3][$i][0];
								}
							}
						}
					}
				}
				/* */
			}

			// Incorrectly defined content will be ignored…
			$objectRheiaClass = null;

			$content = '<'.'?php'.EOL2;
			$content .= 'RheiaMainClass::$objects = '.
				var_export(RheiaMainClass::$objects, true);
			$content .= ';'.EOL2.'?'.'>';

			file_put_contents($parsed, $content, LOCK_EX);
		}
	}


	private static function solveInternalRedirectCallback($matches)
	{
		$address = $matches[1];
		$find = null;

		foreach (RheiaMainClass::$internalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return '<a href="'.$address.'"';
	}

	private static function solveInternalRedirectURL($address)
	{
		if (!is_array(RheiaMainClass::$internalRedirects)) return $address;

		$find = null;

		foreach (RheiaMainClass::$internalRedirects as $replace)
		{
			if (null === $find) $find = $replace; else
			{
				$address = preg_replace($find, $replace, $address);
				$find = null;
			}
		}

		return $address;
	}

	public static function solveInternalRedirects($content)
	{
		if (is_array(RheiaMainClass::$internalRedirects))
			return $content = preg_replace_callback('/<a href="([^"]+)"/i',
				array('RheiaMainClass', 'solveInternalRedirectCallback'),
				$content);
		else return $content;
	}


	public static function getFileIconType($pathname)
	{
		if (preg_match('/\.([a-z0-9]+)$/i', $pathname, $matches))
		{
			switch (strtolower($matches[1]))
			{
			case 'docx': case 'dotx': // $type = 'word-new'; break;
			case 'doc': case 'dot': $type = 'word'; break;
			case 'xlsx': case 'xltm': case 'xltx': // $type = 'excel-new'; break;
			case 'xls': case 'xlt': $type = 'excel'; break;
			case 'ppsx': case 'pptx': case 'potx':
				// $type = 'powerpoint-new'; break;
			case 'pps': case 'ppt': case 'pot': $type = 'powerpoint'; break;
			case 'rtf': $type = 'rtf'; break;
			case 'htm': case 'html': $type = 'web'; break;
			case 'elp': $type = 'elp'; break;
			case 'sql': $type = 'mysql'; break;
			case 'pas': $type = 'pascal'; break;
			case 'sb3': $type = 'scratch'; break;
			case 'php': $type = 'php'; break;
			case 'css': $type = 'css'; break;
			case 'pdf': $type = 'acrobat'; break;
			case 'zip': case 'rar': case '7z': $type = 'packed'; break;
			case 'tar': case 'gz': $type = 'gz'; break;
			case 'java': case 'class': case 'jar': $type = 'java'; break;
			case 'exe': $type = 'application'; break;
			case 'dmg': $type = 'dmg'; break;
			case 'imp': $type = 'imagine'; break;
			case 'gif': $type = 'gif'; break;
			case 'png': $type = 'png'; break;
			case 'jpg': case 'jpeg': $type = 'jpg'; break;
			case 'msi': $type = 'msi'; break;
			case 'reg': $type = 'reg'; break;
			case 'txt': case 'ini': $type = 'text'; break;
			case 'mov': case 'ogg': case 'webm':
			case 'mp4': $type = 'movie'; break;
			case 'notebook': $type = 'notebook'; break;
			default: $type = 'common';
			}
		}
		else $type = 'common';

		return $type;
	}

	public static function getFileIconName($pathname, $exists = false,
		$lookForAlt = true)
	{
		if ($lookForAlt && $exists)
		{
			$iconName = $pathname.'.png'; $iconExists = false;
			$iconSize = null; $iconDate = null;
			RheiaMainClass::getFileInfo($iconName, $iconExists,
				$iconSize, $iconDate);

			if ($iconExists)
				return '/'.RheiaMainClass::$downloadScript.'?'.$iconName;

			$iconName = $pathname.'.svgz';
			RheiaMainClass::getFileInfo($iconName, $iconExists,
				$iconSize, $iconDate);

			if ($iconExists)
				return '/'.RheiaMainClass::$downloadScript.'?'.$iconName;
		}

		$name = 'icon-'.RheiaMainClass::getFileIconType($pathname).
			($exists ? '' : '-gray');
		if (file_exists(RheiaMainClass::$designFilesPath.$name.'.svgz'))
			return RheiaMainClass::$iconsPath.$name.'.svgz';
		if (file_exists(RheiaMainClass::$designFilesPath.$name.'.svg'))
			return RheiaMainClass::$iconsPath.$name.'.svg';
		if (file_exists(RheiaMainClass::$designFilesPath.$name.'.png'))
			return RheiaMainClass::$iconsPath.$name.'.png';
		return RheiaMainClass::$iconsPath.$name.'.gif';
	}

	public static function getDirectoryIconName($exists = true)
	{
		$name = 'icon-folder'.($exists ? '' : '-gray');
		if (file_exists(RheiaMainClass::$designFilesPath.$name.'.png'))
			return RheiaMainClass::$iconsPath.$name.'.png';
		return RheiaMainClass::$iconsPath.$name.'.gif';
	}

	public static function formatFileSize($fileSize)
	{
		while (is_array($fileSize))
			$fileSize = end($fileSize);

		$kibi_size = $kilo_size = $fileSize;
		$kibi_index = $kilo_index = 0;

		for ($i = 0; $i < 4; ++$i)
			if ($kilo_size >= 1000)
			{
				$kilo_size /= 1000;
				++$kilo_index;
			} else break;

		for ($i = 0; $i < 4; ++$i)
			if ($kibi_size >= 1024)
			{
				$kibi_size /= 1024;
				++$kibi_index;
			} else break;

		return str_replace('.', ',', sprintf('%.2f', $kilo_size)).
			' '.RheiaMainClass::$fileUnits[0][$kilo_index].'B ('.
			str_replace('.', ',', sprintf('%.2f', $kibi_size)).' '.
				RheiaMainClass::$fileUnits[1][$kibi_index].'B)';
	}

	public static function formatFileDate($fileDate)
	{
		while (is_array($fileDate)) $fileDate = end($fileDate);
		return date(date_format, $fileDate);
	}

	public static function encodeMail($mail, &$checksum, $visible)
	{
		if ($visible)
		{
			$checksum = 0;
			$encode = '';
			$length = strlen($mail);

			for ($i = 0; $i < $length; ++$i)
			{
				if ($i != 0) $encode .= '|';
				$checksum += ord($mail[$i]);
				$encode .= (ord($mail[$i]) ^ 0x4B);
			}

			$checksum ^= 0xB44B;
			return '<img src="/'.
				RheiaMainClass::$mailScript.'?'.$encode.'-'.$checksum.'&amp;'.
				RheiaMainClass::$mailSize[RheiaMainClass::$mailType].'" alt="'.
				RheiaMainClass::$text['e-mail-alt'].'" class="e-mail-address" />';
			// str_replace('.', '&#46;', str_replace('@', '&#64;', $mail));
		}

		$checksum = 0;
		$encode = '[';
		$length = strlen($mail);

		for ($i = 0; $i < $length; ++$i)
		{
			if ($i != 0) $encode .= ', ';
			$checksum += ord($mail[$i]);
			$encode .= (ord($mail[$i]) ^ 0x5A);
		}

		$checksum ^= 0xA55A;
		return $encode.']';
	}


	private function replaceSimpleTags($text)
	{
		$text = preg_replace('/‗([^‗]+)‗/u', '<s>$1</s>',
			preg_replace('/°([^°]+)°/u', '<u>$1</u>',
			preg_replace('/`([^`]+)`/', '<code>$1</code>',
			preg_replace('/•([^•]+)•/u', '<abbr>$1</abbr>',
			preg_replace('/≈([^≈]+)≈/u', '<dfn>$1</dfn>',
			preg_replace('/~([^~]+)~/', '<strong>$1</strong>',
			preg_replace('/-([^\\-]+)-/', '<del>$1</del>',
			preg_replace('/ˇ([^ˇ]+)ˇ/u', '<sub>$1</sub>',
			preg_replace('/\\^([^\\^]+)\\^/', '<sup>$1</sup>',
			preg_replace('/\\*([^\\*]+)\\*/', '<b>$1</b>',
			preg_replace('/_([^_]+)_/', '<ins>$1</ins>',

			preg_replace('#/([^/]+)/#', '<i>$1</i>',

			str_replace('&#45;', '­‑',
			str_replace(' &#45; ', ' – ',
			str_replace('&#45; ', ' – ',
			str_replace(' &#45;', ' – ',

			str_replace('°°', '&#730;',
			str_replace('``', '&#96;',
			str_replace('••', '&#8226;',
			str_replace('≈≈', '&#8776;',
			str_replace('~~', '&#126;',
			str_replace('||', '&#124;',
			str_replace('ˇˇ', '&#711;',
			str_replace('^^', '&#94;',
			str_replace('**', '&#42;',
			str_replace('‗‗', '&#8215;', // Alt+08215 (not in jEdit )
			str_replace('__', '&#95;',
			str_replace('//', '&#47;',
			str_replace('--', '&#45;',

				preg_replace('/\b([Ee])--/', '$1‑', // e‑mail, e‑learning…
				preg_replace('/\\\\?"\b/', '„',
				preg_replace('/\b\\\\?"/', '“',

				RheiaMainClass::handleNBSP(
					str_replace('...', '…',
					str_replace('>', '&gt;', str_replace('<', '&lt;',
					str_replace('>>', '»', str_replace('<<', '«',
					$text)))))) )))

		))))))))))))) )))) ) )))))))))));

		for ($i = 0; preg_match(
				'/∥([^¦\|∥]*?)[¦\|]([^¦\|∥]+?)∥/u', $text) &&
			$i < 10; ++$i) $text = preg_replace(
				'/∥([^¦\|∥]*?)[¦\|]([^¦\|∥]+?)∥/u',
				'<span class="$1">$2</span>', $text);

		for ($i = 0;  preg_match('/∥([^¦\|∥]+?)∥/u', $text) && $i < 10; ++$i)
			$text = preg_replace('/∥([^¦\|∥]+?)∥/u', '<span>$1</span>', $text);

		for ($i = 0; preg_match(
				'/‖([^¦\|‖]*?)[¦\|]([^¦\|‖]+?)‖/u', $text) &&
			$i < 10; ++$i) $text = preg_replace(
				'/‖([^¦\|‖]*?)[¦\|]([^¦\|‖]+?)‖/u',
				'<span class="$1">$2</span>', $text);

		for ($i = 0;  preg_match('/‖([^¦\|‖]+?)‖/u', $text) && $i < 10; ++$i)
			$text = preg_replace('/‖([^¦\|‖]+?)‖/u', '<span>$1</span>', $text);

		$text = str_replace('\\', ' <br />',
			str_replace('\\\\', '&#92;',
			str_replace(' - ', ' – ',
			str_replace('- ', ' – ',
			str_replace(' -', ' – ',

			preg_replace('/\\|([^\\|]+)\\|/', '<em>$1</em>',
				$text))))));

		return $text;
	}

	private function registerFile(&$matches, &$originalName,
		&$exists, &$fileSize, &$fileDate)
	{
		$originalName = $matches[$this->referenceOptions['link']];
		if ($originalName == '$last')
		{
			$originalName = $this->lastFile;
			$matches[$this->referenceOptions['link']] = $this->lastFile;
		}
		else
			$this->lastFile = $originalName;

		$exists = false; $fileSize = null; $fileDate = null;
		RheiaMainClass::getFileInfo($originalName,
			$exists, $fileSize, $fileDate);

		// $notFound = true;
		if (is_array($this->files))
			foreach ($this->files as $file)
				if ($file['originalName'] == $originalName)
				/*
				{
					$notFound = false;
					break;
				}
				*/
				return;

		// if ($notFound)
			$this->files[] = array(
				'originalName' => $originalName,
				'exists' => $exists,
				'fileSize' => $fileSize,
				'fileDate' => $fileDate,
				// 'type' => 'download' | 'image' | 'info' …,
				);
	}

	private function registerExternFile(&$matches, &$url,
		&$exists, &$fileSize, &$fileDate)
	{
		$url = $matches[$this->referenceOptions['link']];
		if (preg_match('#(https?:)?[/]{0,2}\\$externLast#', $url))
		{
			$url = $this->externLastFile;
			$matches[$this->referenceOptions['link']] = $this->externLastFile;
		}
		else
			$this->externLastFile = $url;

		$exists = false; $fileSize = null; $fileDate = null;
		RheiaMainClass::getExternFileInfo($url, $exists, $fileSize, $fileDate);

		// $notFound = true;
		if (is_array($this->externFiles))
			foreach ($this->externFiles as $file)
				if ($file['url'] == $url)
				/*
				{
					$notFound = false;
					break;
				}
				*/
				return;

		// if ($notFound)
			$this->externFiles[] = array(
				'url' => $url,
				'exists' => $exists,
				'fileSize' => $fileSize,
				'fileDate' => $fileDate,
				// 'type' => 'download' | 'image' | 'info' …,
				);
	}

	private function articleTagsReplaceCallback($matches)
	{
		$count = count($this->articlesRefs);
		$this->articlesRefs[$count] = $matches[1];
		return 'article('.$count.')';
	}

	private function referenceTagsReplaceCallback($matches)
	{
		$pattern = $this->referencePattern;
		if (is_array($this->referenceOptions))
		{
			if (isSet($this->referenceOptions['desc']))
			{
				$matches[$this->referenceOptions['desc']] =
					$this->replaceSimpleTags(
						$matches[$this->referenceOptions['desc']]);
			}
			else
			{
				$matches['desc'] = str_replace('-', '­‑', preg_replace(
					'~(?<!&#8203;|[0-9a-f-A-F%])(%[0-9a-f-A-F%]{2,8})~',
					'&#8203;$1', preg_replace('~([/.?!=&#_]+)~', '$1&#8203;',
					$matches[$this->referenceOptions['link']])));
			}

			// Convert locals
			if ('prefix' == $this->referenceOptions['type'])
			{
				$matches[$this->referenceOptions['link']] =
					RheiaMainClass::$domain.'/'.
						$matches[$this->referenceOptions['link']];
			}

			// Convert phones
			if ('phone' == $this->referenceOptions['type'] ||
				'fax' == $this->referenceOptions['type'])
			{
				$matches[$this->referenceOptions['link']] =
					str_replace(' ', '-', str_replace(' ', '-',
						$matches[$this->referenceOptions['link']]));
			}

			// Convert mails
			else if ('mail' == $this->referenceOptions['type'])
			{
				$checksum = 0;
				/*if (2 == $this->referenceOptions['link'])
					$encode = RheiaMainClass::encodeMail(RheiaMainClass::replaceObjects(
						$matches[1].' <'.$matches[$this->referenceOptions
						['link']].'>'), $checksum, false);
				else*/
				$encode = RheiaMainClass::encodeMail(RheiaMainClass::
					replaceObjects($matches[$this->referenceOptions['link']]),
					$checksum, false);
				$matches['mailto'] = 'javascript:mdcs('.
					$encode.', '.$checksum.')';
				$encode = RheiaMainClass::encodeMail(RheiaMainClass::
					replaceObjects($matches[1]), $checksum, true);
				$matches['image'] = $encode;
			}

			// Check images
			else if ('image' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);
				/*
				$originalName = $matches[$this->referenceOptions['link']];

				$exists = false; $fileSize = null; $fileDate = null;
				RheiaMainClass::getFileInfo($originalName,
					$exists, $fileSize, $fileDate);

				$this->files[] = array(
					'originalName' => $originalName,
					'exists' => $exists,
					'fileSize' => $fileSize,
					'fileDate' => $fileDate,
					'type' => 'image',
					);
				*/
			}

			// Check external dowloads
			/*
			else if ('externdown' == $this->referenceOptions['type'])
			{
				$url = $matches[$this->referenceOptions['link']];
				$protocol = $matches[$this->referenceOptions['link'] - 1];
				$linkurl = $matches[$this->referenceOptions['link']];

				if (preg_match('~^https.*~i', $protocol))
				{
					$linkurl = 'https://'.$linkurl;
					$url = 'https://'.$url;
				}
				else
				{
					if (!empty($protocol))
						$linkurl = $protocol.'//'.$linkurl;
					$url = 'http://'.$url;
				}

				$matches[$this->referenceOptions['link']] = $url;
				$matches[$this->referenceOptions['link'] - 1] = $linkurl;

				$this->registerExternFile($matches, $url,
					$exists, $fileSize, $fileDate);

				if ($exists) $pattern .= RheiaMainClass::
					formatFileInfo($fileSize, $fileDate);

				$matches['icon'] = RheiaMainClass::getFileIconName(
					// $matches[$this->referenceOptions['link']]
					$url, $exists, false);
			}
			else if ('plainexterndown' == $this->referenceOptions['type'])
			{
				$url = $matches[$this->referenceOptions['link']];
				$protocol = $matches[$this->referenceOptions['link'] - 1];
				$linkurl = $matches[$this->referenceOptions['link']];

				if (preg_match('~^https.*~i', $protocol))
				{
					$linkurl = 'https://'.$linkurl;
					$url = 'https://'.$url;
				}
				else
				{
					if (!empty($protocol))
						$linkurl = $protocol.'//'.$linkurl;
					$url = 'http://'.$url;
				}

				$matches[$this->referenceOptions['link']] = $url;
				$matches[$this->referenceOptions['link'] - 1] = $linkurl;

				$this->registerExternFile($matches, $url,
					$exists, $fileSize, $fileDate);
				$matches['icon'] = RheiaMainClass::getFileIconName(
					// $matches[$this->referenceOptions['link']]
					$url, $exists, false);
			}
			*/
			if (false !== stripos($this->referenceOptions['type'], 'extern'))
			{
				$url = $matches[$this->referenceOptions['link']];
				$protocol = $matches[$this->referenceOptions['link'] - 1];
				$linkurl = $matches[$this->referenceOptions['link']];

				if (preg_match('/^https.*/i', $protocol))
				{
					$linkurl = 'https://'.$linkurl;
					$url = 'https://'.$url;
				}
				else
				{
					if (!empty($protocol))
						$linkurl = $protocol.'//'.$linkurl;
					$url = 'http://'.$url;
				}

				$matches[$this->referenceOptions['link']] = $url;
				$matches[$this->referenceOptions['link'] - 1] =
					str_replace('-', '­‑', preg_replace(
					'~(?<!&#8203;|[0-9a-f-A-F%])(%[0-9a-f-A-F%]{2,8})~',
					'&#8203;$1', preg_replace('~([/.?!=&#_]+)~', '$1&#8203;',
						$linkurl)));

				$this->registerExternFile($matches, $url,
					$exists, $fileSize, $fileDate);

				switch ($this->referenceOptions['type'])
				{
				case 'externdown':
					if ($exists) $pattern .= RheiaMainClass::
						formatFileInfo($fileSize, $fileDate);

				case 'plainexterndown':
					$matches['icon'] = RheiaMainClass::getFileIconName(
						// $matches[$this->referenceOptions['link']]
						$url, $exists, false);
				break;

				case 'externsize':
					$matches['size'] = isSet($fileSize) ?
						RheiaMainClass::formatFileSize($fileSize) : '';
				break;

				case 'externdate':
					if (isSet($fileDate))
					{
						$matches['date'] = RheiaMainClass::formatFileDate($fileDate);
						$this->lastDate = $matches['date'];
					}
					else
					{
						$matches['date'] = '';
					}
				break;

				case 'externuniquedate':
					if (isSet($fileDate))
					{
						$matches['date'] = RheiaMainClass::formatFileDate($fileDate);
						if ($matches['date'] == $this->lastDate)
							$matches['date'] = '';
						else
							$this->lastDate = $matches['date'];
					}
					else
					{
						$matches['date'] = '';
					}
				break;

				case 'externwhen':
					$matches['when'] = '$when('.$fileDate.')';
				break;
				}
			}

			// Check dowloads
			else if ('down' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);

				if ($exists) $pattern .= RheiaMainClass::
					formatFileInfo($fileSize, $fileDate);

				$matches['icon'] = RheiaMainClass::getFileIconName(
					$originalName, $exists);
			}
			else if ('plaindown' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);
				$matches['icon'] = RheiaMainClass::getFileIconName(
					$originalName, $exists);
			}
			else if ('size' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);
				$matches['size'] = isSet($fileSize) ?
					RheiaMainClass::formatFileSize($fileSize) : '';
			}
			else if ('date' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);

				if (isSet($fileDate))
				{
					$matches['date'] = RheiaMainClass::formatFileDate($fileDate);
					$this->lastDate = $matches['date'];
				}
				else
				{
					$matches['date'] = '';
				}
			}
			else if ('uniquedate' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);

				if (isSet($fileDate))
				{
					$matches['date'] = RheiaMainClass::formatFileDate($fileDate);
					if ($matches['date'] == $this->lastDate)
						$matches['date'] = '';
					else
						$this->lastDate = $matches['date'];
				}
				else
				{
					$matches['date'] = '';
				}
			}
			else if ('when' == $this->referenceOptions['type'])
			{
				$this->registerFile($matches, $originalName,
					$exists, $fileSize, $fileDate);
				$matches['when'] = '$when('.$fileDate.')';
				// RheiaMainClass::getWhen($fileDate);
			}

			else if ('setdate' == $this->referenceOptions['type'])
			{
				$matches['date'] = date(date_format,
					RheiaMainClass::mktimestamp(
						RheiaMainClass::replaceObjects($matches[$this->
								referenceOptions['link']])));
				$this->lastDate = $matches['date'];
			}

			$matches[$this->referenceOptions['link']] =
				htmlspecialchars($matches[$this->referenceOptions['link']]);
		}

		$count = 0;
		foreach ($matches as $i => $replace)
		{
			$pattern = str_replace('$'.$i, $replace, $pattern, $count);
			if ($count)
			{
				if (isSet($this->referenceOptions['style']))
					foreach ($this->referenceOptions['style'] as
						$key => $value) $this->style[$key] = $value;

				if (isSet($this->referenceOptions['class']))
					$this->class = $this->referenceOptions['class'];
			}
		}

		++$this->anonymousDefs;
		$this->rheiaDefs[$this->anonymousDefs] = $pattern;
		$return = 'code('.$this->anonymousDefs.')';

		return $return;
	}

	private function generateReferenceTags($text)
	{
		/* NEW: now all paragraphs can be aligned‼ */

		if (0 === stripos($text, 'left|'))
		{
			$this->style['text-align'] = 'left';
			$text = trim(substr($text, 5));
		}
		elseif (0 === strpos($text, '<|'))
		{
			$this->style['text-align'] = 'left';
			$text = trim(substr($text, 2));
		}
		elseif (0 === stripos($text, 'center|'))
		{
			$this->style['text-align'] = 'center';
			$text = trim(substr($text, 7));
		}
		elseif (0 === strpos($text, '||'))
		{
			$this->style['text-align'] = 'center';
			$text = trim(substr($text, 2));
		}
		elseif (0 === stripos($text, 'right|'))
		{
			$this->style['text-align'] = 'right';
			$text = trim(substr($text, 6));
		}
		elseif (0 === strpos($text, '>|'))
		{
			$this->style['text-align'] = 'right';
			$text = trim(substr($text, 2));
		}
		elseif (0 === stripos($text, 'justify|'))
		{
			$this->style['text-align'] = 'justify';
			$text = trim(substr($text, 8));
		}
		elseif (0 === strpos($text, '_|'))
		{
			$this->style['text-align'] = 'justify';
			$text = trim(substr($text, 2));
		}

		if (false !== strpos($text, '‖'))
		{
			for ($i = 0; preg_match(
					'/‖([^¦\|‖]*?)\|([^¦\|‖]+?)‖/u',
				$text) && $i < 10; ++$i) $text = preg_replace(
					'/‖([^¦\|‖]*?)\|([^¦\|‖]+?)‖/u',
					// DEBUG: 'A'.$i.'∥B'.$i.'$1C'.$i.'¦D'.$i.'$2E'.$i.'∥F'.$i,
					'∥$1¦$2∥', $text);
		}

		$text = preg_replace_callback('/\{article:([^}]+)\}/',
			array(&$this, 'articleTagsReplaceCallback'), $text);

		$text = preg_replace('#\{([^|}]+)\|(?:(?:img|(?:plain)?(?:extern)?'.
			'(?:down)):)?(?:https?:)?[/]{0,2}\}#', '$1', $text);

		$this->referenceOptions = array(
			'desc' => null,
			'link' => 3,
			'type' => 'image',
			'style' => array('text-align' => 'center'),
			'class' => null);

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$3" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{((float|center),?){2}\|img:'.
			'([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['style'] = null;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$3" alt="'.RheiaMainClass::$text['image-alt'].'" class="'.
			$this->classes['floatRight'].'" />';
		$text = preg_replace_callback('/\{((float|right),?){2}\|'.
			'img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$3" alt="'.RheiaMainClass::$text['image-alt'].'" class="'.
			$this->classes['floatLeft'].'" />';
		$text = preg_replace_callback('/\{((float|left),?){2}\|'.
			'img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['link'] = 4;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$4" alt="'.RheiaMainClass::$text['image-alt'].'" title="$3" />';
		$text = preg_replace_callback('/\{((float|center),){2}'.
			'title:"([^"]+)"\|img:([^}]+)\}/', array(&$this,
			'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = null;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$4" alt="'.RheiaMainClass::$text['image-alt'].
			'" title="$3" class="'.$this->classes['floatRight'].'" />';
		$text = preg_replace_callback('/\{((float|right),){2}'.
			'title:"([^"]+)"\|img:([^}]+)\}/', array(&$this,
			'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$4" alt="'.RheiaMainClass::$text['image-alt'].
			'" title="$3" class="'.$this->classes['floatLeft'].'" />';
		$text = preg_replace_callback('/\{((float|left),){2}'.
			'title:"([^"]+)"\|img:([^}]+)\}/', array(&$this,
			'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['link'] = 1;

		$this->referenceOptions['class'] = 'profile-photo-container';

		$this->referencePattern = '<img src="/profile-photo?$1" alt="'.
			RheiaMainClass::$text['profile-photo-alt'].
			'" class="profile-photo" />';
		$text = preg_replace_callback('/\{profile-photo:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['class'] = null;

		$this->referenceOptions['style'] = array('text-align' => 'center');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$1" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{center\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = array('text-align' => 'right');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$1" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{right\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = array('text-align' => 'left');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$1" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{left\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = null;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$1" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['link'] = 2;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$2" alt="'.RheiaMainClass::$text['image-alt'].'" class="$1" />';
		$text = preg_replace_callback('/\{class:([^|,]+)\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$2" alt="'.RheiaMainClass::$text['image-alt'].'" title="$1" />';
		$text = preg_replace_callback('/\{title:"([^"]+)"\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = array('text-align' => 'center');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$2" alt="'.RheiaMainClass::$text['image-alt'].'" title="$1" />';
		$text = preg_replace_callback('/\{center,title:"([^"]+)"\|'.
			'img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['style'] = array('text-align' => 'right');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$2" alt="'.RheiaMainClass::$text['image-alt'].'" title="$1" />';
		$text = preg_replace_callback('/\{right,title:"([^"]+)"\|'.
			'img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['style'] = array('text-align' => 'left');

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$2" alt="'.RheiaMainClass::$text['image-alt'].'" title="$1" />';
		$text = preg_replace_callback('/\{left,title:"([^"]+)"\|img:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['style'] = null;

		$this->referenceOptions['link'] = 3;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$3" alt="'.RheiaMainClass::$text['image-alt'].
			'" title="$2" class="$1" />';
		$text = preg_replace_callback('/\{class:([^,]+),title:"([^"]+)"'.
			'\|img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$3" alt="'.RheiaMainClass::$text['image-alt'].'" />';
		$text = preg_replace_callback('/\{((float|center|right|left),?)+\|'.
			'img:([^}]+)\}/', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['link'] = 4;

		$this->referencePattern = '<img src="/'.RheiaMainClass::$imageScript.
			'?$4" alt="'.RheiaMainClass::$text['image-alt'].'" title="$3" />';
		$text = preg_replace_callback('/\{((float|center|right|left),)+'.
			'title:"([^"]+)"\|img:([^}]+)\}/', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['desc'] = 1;
		$this->referenceOptions['link'] = 2;
		$this->referenceOptions['type'] = 'web';
		$this->referenceOptions['style'] = null;

		$this->referencePattern = '<a href="https://$2" target="_blank" '.
			'class="external-link"><span>$1</span> <img src="'.
			RheiaMainClass::$externalLinkIcon.'" alt="'.
			RheiaMainClass::$text['external-link'].'" title="'.
			RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{([^|}]+)\|https:[/]{0,2}'.
			'([^}]+)\}#', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referencePattern = '<a href="http://$2" target="_blank" '.
			'class="external-link"><span>$1</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{([^|}]+)\|http:[/]{0,2}'.
			'([^}]+)\}#', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['type'] = 'prefix';

		$this->referencePattern = '<a href="https://$2" target="_blank" '.
			'class="external-link"><span>$1</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{([^|}]+)\|link:s:[/]{0,2}'.
			'([^}]+)\}#', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referencePattern = '<a href="http://$2" target="_blank" '.
			'class="external-link"><span>$1</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{([^|}]+)\|link:[/]{0,2}'.
			'([^}]+)\}#', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['type'] = 'script';

		$this->referencePattern = '<a href="javascript:$2">$1</a>';
		$text = preg_replace_callback('#\{([^|}]+)\|script:[/]{0,2}'.
			'([^}]+)\}#', array(&$this, 'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['type'] = 'phone';

		$this->referencePattern = '<a href="tel:$2" rel="nofollow">$1</a>';
		$text = preg_replace_callback('#\{([^|}]+)\|tel:([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'fax';

		$this->referencePattern = '<a href="fax:$2" rel="nofollow">$1</a>';
		$text = preg_replace_callback('#\{([^|}]+)\|fax:([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'mail';

		// $this->referencePattern = '<a href="mailto:$2">$1</a>';
		$this->referencePattern = '<a href="$mailto" class="mcds">'.
			'$image</a>';
		$text = preg_replace_callback('/\{([^|}]+)\|mailto:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['link'] = 3;
		$this->referenceOptions['type'] = 'externdown';

		$this->referencePattern = '<a href="$3" target="_blank" '.
			'class="external-download"><img src="design/null.gif" '.
			'data-src="$icon" alt="'.RheiaMainClass::$text['icon-alt'].
			'" /><noscript><img src="$icon" alt="'.RheiaMainClass::
			$text['icon-alt'].'" /></noscript><span>$1</span> <img src="'.
			RheiaMainClass::$externalLinkIcon.'" alt="'.RheiaMainClass::
			$text['external-link'].'" title="'.RheiaMainClass::
			$text['external-link'].'" class="external-icon" /></a>';
		$text = preg_replace_callback('#\{([^|}]+)\|externdown:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['type'] = 'plainexterndown';

		$text = preg_replace_callback('#\{([^|}]+)\|plainexterndown:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'),
			$text);

		$this->referenceOptions['link'] = 2;

		$this->referenceOptions['type'] = 'ical';

		$this->referencePattern = '<a href="/'.RheiaMainClass::$icalScript.
			'-$2+$3.ics" class="download ical"><img src="'.
			RheiaMainClass::$iconsPath.'ical.png" alt="'.
			RheiaMainClass::$text['icon-alt'].'" /><span>$1</span></a>';
		$text = preg_replace_callback(
			'/\{([^|}]+)\|ical:([^&\?]+)[&\?]([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$icalScript.
			'-$2.ics" class="download ical"><img src="'.RheiaMainClass::$iconsPath.
			'ical.png" alt="'.RheiaMainClass::$text['icon-alt'].
			'" /><span>$1</span></a>';
		$text = preg_replace_callback('/\{([^|}]+)\|ical:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'down';

		$this->referencePattern = '<a href="/'.RheiaMainClass::$downloadScript.
			'?$2" class="download"><img src="design/null.gif" data-src='.
			'"$icon" alt="'.RheiaMainClass::$text['icon-alt'].'" />'.
			'<noscript><img src="$icon" alt="'.RheiaMainClass::
			$text['icon-alt'].'" /></noscript><span>$1</span></a>';
		$text = preg_replace_callback('/\{([^|}]+)\|down:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'plaindown';

		$text = preg_replace_callback('/\{([^|}]+)\|plaindown:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'local';

		$this->referencePattern = '<a href="/$2" target="_blank">$1</a>';
		$text = preg_replace_callback('/\{([^|}]+)\|blank:\/([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$siteSectionPath.
			'$2" target="_blank">$1</a>';
		$text = preg_replace_callback('/\{([^|}]+)\|blank:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/$2">$1</a>';
		$text = preg_replace_callback('/\{([^|}]+)\|\/([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$siteSectionPath.
			'$2">$1</a>';
		$text = preg_replace_callback('/\{([^|}]+)\|([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['desc'] = null;
		$this->referenceOptions['link'] = 1;
		$this->referenceOptions['type'] = 'web';

		$this->referencePattern = '<a href="https://$1" target="_blank" '.
			'class="external-link"><span>https://$desc</span> <img src="'.
			RheiaMainClass::$externalLinkIcon.'" alt="'.RheiaMainClass::$text
			['external-link'].'" title="'.RheiaMainClass::$text
			['external-link'].'" class="external-icon" /></a>';
		$text = preg_replace_callback('#\{https:[/]{0,2}([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="http://$1" target="_blank" '.
			'class="external-link"><span>http://$desc</span> <img src='.
			'"'.RheiaMainClass::$externalLinkIcon.'" alt="'.RheiaMainClass::
			$text['external-link'].'" title="'.RheiaMainClass::
			$text['external-link'].'" class="external-icon" /></a>';
		$text = preg_replace_callback('#\{http://([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="http://$1" target="_blank" '.
			'class="external-link"><span>$desc</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{http:[/]{0,2}([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'prefix';

		$this->referencePattern = '<a href="https://$1" target="_blank" '.
			'class="external-link"><span>$desc</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{link:s:[/]{0,2}([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="http://$1" target="_blank" '.
			'class="external-link"><span>$desc</span> <img src="'.RheiaMainClass::
			$externalLinkIcon.'" alt="'.RheiaMainClass::$text['external-link'].
			'" title="'.RheiaMainClass::$text['external-link'].'" '.
			'class="external-icon" /></a>';
		$text = preg_replace_callback('#\{link:[/]{0,2}([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'phone';

		$this->referencePattern = '<a href="tel:$1" rel="nofollow">$desc</a>';
		$text = preg_replace_callback('#\{tel:([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'fax';

		$this->referencePattern = '<a href="fax:$1" rel="nofollow">$desc</a>';
		$text = preg_replace_callback('#\{fax:([^}]+)\}#',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'mail';

		// $this->referencePattern = '<a href="mailto:$1">$desc</a>';
		$this->referencePattern = '<a href="$mailto" class='.
			'"mcds">$image</a>';
		$text = preg_replace_callback('/\{mailto:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['link'] = 2;
		$this->referenceOptions['type'] = 'externdown';

		$this->referencePattern = '<a href="$2" target="_blank" '.
			'class="external-download"><img src="design/null.gif" '.
			'data-src="$icon" alt="'.RheiaMainClass::$text['icon-alt'].
			'" /><noscript><img src="$icon" alt="'.RheiaMainClass::
			$text['icon-alt'].'" /></noscript><span>$1</span> <img src="'.
			RheiaMainClass::$externalLinkIcon.'" alt="'.RheiaMainClass::
			$text['external-link'].'" title="'.RheiaMainClass::
			$text['external-link'].'" class="external-icon" /></a>';
		$text = preg_replace_callback('#\{externdown:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'plainexterndown';

		$text = preg_replace_callback('#\{plainexterndown:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'externsize';

		$this->referencePattern = '$size';
		$text = preg_replace_callback('#\{externsize:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'externdate';

		$this->referencePattern = '$date';
		$text = preg_replace_callback('#\{externdate:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'externuniquedate';

		$this->referencePattern = '$date';
		$text = preg_replace_callback('#\{externuniquedate:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'externwhen';

		$this->referencePattern = '$when';
		$text = preg_replace_callback('#\{externwhen:'.
			'(https?:)?[/]{0,2}([^}]+)\}#', array(&$this,
				'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['link'] = 1;

		$this->referenceOptions['type'] = 'ical';

		$this->referencePattern = '<a href="/'.RheiaMainClass::$icalScript.
			'-$1+$2.ics" class="download ical"><img src="'.
			RheiaMainClass::$iconsPath.'ical.png" alt="'.
			RheiaMainClass::$text['icon-alt'].'" /></a>';
		$text = preg_replace_callback('/\{ical:([^&\?]+)[&\?]([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$icalScript.
			'-$1.ics" class="download ical"><img src="'.RheiaMainClass::$iconsPath.
			'ical.png" alt="'.RheiaMainClass::$text['icon-alt'].'" /></a>';
		$text = preg_replace_callback('/\{ical:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'down';

		$this->referencePattern = '<a href="/'.RheiaMainClass::$downloadScript.
			'?$1" class="download"><img src="design/null.gif" '.
			'data-src="$icon" alt="'.RheiaMainClass::$text['icon-alt'].
			'" /><noscript><img src="$icon" alt="'.RheiaMainClass::
			$text['icon-alt'].'" /></noscript><span>$desc</span></a>';
		$text = preg_replace_callback('/\{down:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'plaindown';

		$text = preg_replace_callback('/\{plaindown:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'size';

		$this->referencePattern = '$size';
		$text = preg_replace_callback('/\{size:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'date';

		$this->referencePattern = '$date';
		$text = preg_replace_callback('/\{date:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'setdate';

		$this->referencePattern = '$date';
		$text = preg_replace_callback('/\{setdate:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'uniquedate';

		$this->referencePattern = '$date';
		$text = preg_replace_callback('/\{uniquedate:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'when';

		$this->referencePattern = '$when';
		$text = preg_replace_callback('/\{when:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions['type'] = 'local';

		$this->referencePattern = '<a href="/$1" target="_blank">$desc</a>';
		$text = preg_replace_callback('/\{blank:\/([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$siteSectionPath.
			'$1" target="_blank">$desc</a>';
		$text = preg_replace_callback('/\{blank:([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/$1">$desc</a>';
		$text = preg_replace_callback('/\{\/([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referencePattern = '<a href="/'.RheiaMainClass::$siteSectionPath.
			'$1">$desc</a>';
		$text = preg_replace_callback('/\{([^}]+)\}/',
			array(&$this, 'referenceTagsReplaceCallback'), $text);

		$this->referenceOptions = null;

		return $this->replaceSimpleTags($text);
	}

	private function handleSpecialCodes(&$text)
	{
		$this->style = null; $this->class = null;
		$text = $this->generateReferenceTags($text);

		if (is_array($this->style))
		{
			$return = '';
			foreach ($this->style as $property => $value)
				$return .= $property.':'.$value.';';
			return ' style="'.$return.'"'.(empty($this->class) ?
				'' : (' class="'.$this->class.'"'));
		}

		return (empty($this->class) ? null : (' class="'.$this->class.'"'));
	}

	private static function expandArray($match)
	{
		$content = '';
		$split = preg_split('/ *, */', $match[2], -1, PREG_SPLIT_NO_EMPTY);
		$count = count($split);
		$array = null;

		if ($count >= 2)
		{
			$split[1] = '#'.$split[1];
			if (isSet(RheiaMainClass::$objects[$split[0]]) &&
				isSet(RheiaMainClass::$objects[$split[0]][$split[1]]))
				$array = RheiaMainClass::$objects[$split[0]][$split[1]][1];
		}
		else if ($count >= 1)
		{
			if (isSet(RheiaMainClass::$objects[$split[0]]))
				$array = RheiaMainClass::$objects[$split[0]][0][1];
		}

		if (null !== $array)
		{
			if (is_array($array))
			{
				// $first = true;
				foreach ($array as $num => $val)
				{
					// if ($first) $first = false; else $content .= EOL;
					$content .= '#Šablóna:'.$match[1].','.$val.EOL;
				}
			}
			else
				$content .= '#Šablóna:'.$match[1].','.$array.EOL;
		}

		return $content;
	}

	private function loadTemplates($match)
	{
		if (null === RheiaMainClass::$usedTemplates)
		{
			RheiaMainClass::$usedTemplates = array();
			RheiaMainClass::$newestTemplate = 0;
			RheiaMainClass::$revalidationDate = null;
		}

		$source = RheiaMainClass::$contentPath.$match[1].'.tpt';

		if (!file_exists($source))
		{
			// Podobne, ako pri objektoch, aj tu máme
			// instantné riešenie globálnych šablón
			$source = RheiaMainClass::$contentPath.'../'.$match[1].'.tpt';

			if (!file_exists($source))
			{
				$source = RheiaMainClass::$downloadLevel.
					'../content/'.$match[1].'.tpt';

				if (!file_exists($source)) $source = null;
			}
		}

		if (!empty($source) && file_exists($source))
		{
			RheiaMainClass::$usedTemplates[] = $source;

			$templateDate = filemtime($source);
			if (RheiaMainClass::$newestTemplate < $templateDate)
				RheiaMainClass::$newestTemplate = $templateDate;

			$content = file_get_contents($source);

			// $content .= EOL2.'~';
			// echo '<pre style="color:green">';
			foreach ($match as $num => $val)
			{
				if ($num < 3)
				{
					// $content .= $num.' ';
					continue;
				}

				// $content .= $num.': '.$val.'\\'.EOL;
				$num -= 2; $val = trim(substr($val, 1));

				if (preg_match('/^neplatí:\s*(.*)/ui', $val, $matches))
				{
					// RheiaMainClass::logError('Template match expires 1: '.
					// 	$matches[1]);
					// RheiaMainClass::logError('Template match expires 1b: '.
					// 	RheiaMainClass::replaceObjects(
					// 		$this->replaceGlobals($matches[1])));

					$val = $matches[1];
					$matches[1] = RheiaMainClass::mktimestamp(
						RheiaMainClass::replaceObjects(
							$this->replaceGlobals($matches[1])));

					// RheiaMainClass::logError('Template match expires 2: '.
					// 	$matches[1]);

					if ($matches[1] < RheiaMainClass::$currentTime)
					{
						$content = '';
						break;
					}

					if ($matches[1] >= RheiaMainClass::$currentTime &&
						(null == RheiaMainClass::$revalidationDate ||
						$matches[1] < RheiaMainClass::$revalidationDate))
						RheiaMainClass::$revalidationDate = $matches[1];

					// continue;
				}
				else if (preg_match('/^platí:\s*(.*)/ui', $val, $matches))
				{
					// RheiaMainClass::logError('Template match valid 1: '.
					// 	$matches[1]);
					// RheiaMainClass::logError('Template match valid 1b: '.
					// 	RheiaMainClass::replaceObjects(
					// 		$this->replaceGlobals($matches[1])));

					$val = $matches[1];
					$matches[1] = RheiaMainClass::mktimestamp(
						RheiaMainClass::replaceObjects(
							$this->replaceGlobals($matches[1])));

					// RheiaMainClass::logError('Template match valid 2: '.
					// 	$matches[1]);

					if ($matches[1] >= RheiaMainClass::$currentTime)
					{
						$content = '';

						if (null == RheiaMainClass::$revalidationDate ||
							$matches[1] < RheiaMainClass::$revalidationDate)
							RheiaMainClass::$revalidationDate = $matches[1];

						break;
					}

					// continue;
				}

				$content = str_replace('$'.$num, $val, $content);

				// echo '$'.$num.' => „'.htmlspecialchars($val).'“'.EOL;
			}
			// $content .= '~'.EOL2;
			// echo '</pre>';

			return preg_replace_callback(RheiaMainClass::$templateRegex,
				array(&$this, 'loadTemplates'),
				preg_replace_callback(RheiaMainClass::$expandRegex,
					array('RheiaMainClass', 'expandArray'),
				preg_replace(
					RheiaMainClass::$templateInCommentRegex, '', $content)));

			// return file_get_contents($source);
		}

		// Šablóna „xxx“ nebola nájdená
		return EOL.'#error:'.
			RheiaMainClass::$text['error-template-not-found-prefix'].$match[1].
			RheiaMainClass::$text['error-template-not-found-postfix'].'…'.EOL;
	}

	private function replaceGlobals($content)
	{
		global $category, $selectedItem, $argument;

		return
			str_replace('$sourceYear', date('Y', $this->sourceDate),
			str_replace('$sourceMonth', date('n', $this->sourceDate),
			str_replace('$sourceDay', date('j', $this->sourceDate),
			str_replace('$sourceDate', date(date_format, $this->sourceDate),
			str_replace('$argument', $argument,
			str_replace('$selectedItem', $selectedItem,
			str_replace('$category', $category,
			str_replace('$baseName', preg_replace('/\-en$/u', '',
				$this->baseName), $content))))))));
	}

	private function replaceCodes($html, $deployStyles = false,
		$deployScripts = false, $processTitle = false)
	{
		for ($i = 0; $i < 10; ++$i)
		{
			if (false === stripos($html, 'code(')) break;

			foreach (array('' => '', 'zwj' => '&#8205;', 'zwsp' => '&#8203;',
				'nbsp' => ' ', 'br' => '<br />', 'hr' => '<hr />',
				'p' => '</p>'.EOL.'<p>', 'ps' => '<p>', 'pf' => '</p>',
				'pre' => '<pre>', '/pre' => '</pre>',
				'&#47;pre' => '</pre>', 'code' => '<code>',
				'/code' => '</code>', '&#47;code' => '</code>',
				'lt' => '&lt;', 'gt' => '&gt;',
				'amp' => '&amp;', 'quot' => '&quot;',
				'lp' => '&#40;', 'rp' => '&#41;',
				'lbk' => '&#91;', 'rbk' => '&#93;',
				'lbc' => '&#123;', 'rbc' => '&#125;',
				'back' => '\\', 'dollar' => '&#36;', 'hyp' => '&#45;',
				'hyphen' => '&#45;', 'com' => '&#44;', 'comma' => '&#44;',
				'dot' => '&#46;', 'minus' => '−')
			as $key => $val)
			{
				$html = str_replace('code('.$key.')', $val, $html);
				if ($processTitle && !empty($this->title))
					$this->title = str_replace
						('code('.$key.')', $val, $this->title);
			}

			if (is_array($this->rheiaDefs))
				foreach ($this->rheiaDefs as $key => $val)
				{
					// if ($key !== 'style' && $key !== 'javaScript' &&
					// 	$key !== 'onShow' && $key !== 'onExit')
					// 	$html = str_replace('code('.$key.')', $val, $html);

					if ($key !== 'style' && $key !== 'javaScript' &&
						$key !== 'onShow' && $key !== 'onExit')
					{
						if (false !== stripos($val, '.svg'))
						{
							if (false !== stripos($val, 'h='))
							{
								$val = preg_replace(
									'#&(?:amp;)h=([0-9]+)([^"]*")#i',
									'$2 height="$1"', $val);
							}

							if (false !== stripos($val, 'w='))
							{
								$val = preg_replace(
									'#&(?:amp;)w=([0-9]+)([^"]*")#i',
									'$2 width="$1"', $val);
							}

							// RheiaMainClass::logError('It is here: '.$val);
							$val = preg_replace(
								'#(<img src=")(/'.RheiaMainClass::$imageScript.
								'\?[^"]+\.svgz?)"#i',
								'$1$2" onerror="this.onerror=null; '.
								'this.src=\'$2.png\';"', $val);
							// RheiaMainClass::logError(TAB.'Replaced: '.$val);
						}

						$html = str_replace('code('.$key.')', $val, $html);
						if ($processTitle && !empty($this->title))
							$this->title = str_replace
								('code('.$key.')', $val, $this->title);
					}
				}
		}

		if ($deployStyles && !empty($this->rheiaDefs['style']))
			// $html = '<style>'.$this->rheiaDefs['style'].
			// 	'</style>'.EOL.$html;
			// $html .= EOL.'<style>'.$this->rheiaDefs['style'].
			// 	'</style>';
			$this->styleSheed .= $this->rheiaDefs['style'];

		if ($deployScripts)
		{
			if (!empty($this->rheiaDefs['javaScript']))
				$this->javaScript .= $this->rheiaDefs['javaScript'];
			if (!empty($this->rheiaDefs['onShow']))
				$this->onShow .= $this->rheiaDefs['onShow'];
			if (!empty($this->rheiaDefs['onExit']))
				$this->onExit .= $this->rheiaDefs['onExit'];
		}

		return $html;
	}

	private function expandMacro($match)
	{
		$match = explode(',', $match[1]);

		if (isSet($this->macro[$match[0]]))
		{
			// if (preg_match('/riadokBcMgr/i', $match[0]))
			// {
			// 	ob_start();
			// 	echo 'Expand macro:'.EOL;
			// 	var_dump($match);
			// 	// var_dump($this->macro);
			// 	$catch = true;
			// }
			// else $catch = false;

			$count = count($match);
			$macro = $this->macro[$match[0]];

			for ($i = 0; $i < $count; ++$i)
			{
				////
				// Komplikáciou pri tomto nahrádzaní je to, že nahrádzanie
				// sekvencií referencií musí postupovať vzostupne, pretože
				// v makrách sa v súlade s princípom zjednodušovania často
				// vo vyšších referenčných sekvenciách vyskytujú sekvencie
				// nižších číselných referencií, ktoré by pri zostupnom
				// nahrádzaní boli nesprávne nahradené.
				////

				// echo '['.$i.'] – ['.$match[$i].']: '.$macro.EOL;
				$macro = preg_replace('/\\$'.$i.'(?![0-9])/',
					str_replace('$', '\\$', $match[$i]), $macro);
			}

			// if ($catch)
			// {
			// 	echo $macro.EOL;
			// 	$ob = ob_get_clean();
			// 	RheiaMainClass::logError($ob);
			// }

			return $macro;
		}

		return '';
	}


	public function simpleHTML(&$text, $baseName)
	{
		$this->baseName = $baseName;
		RheiaMainClass::$cropTextID = $this->baseName; // ‼Warning‼ Emergency solution… Not the best, not any right, but necessary at the moment… (2021-08-12)
		$text = $this->replaceGlobals($text);
		$return = $this->handleSpecialCodes($text);
		if (false !== stripos($text, 'code('))
			$text = $this->replaceCodes($text);
		$text = RheiaMainClass::replaceObjects($text);
		return $return;
	}

	private static function findTab(&$tabs, $alias)
	{
		if (preg_match('/^ *tab([0-9]+)-([0-9]+) *$/i', $alias, $match))
			return 'tab'.(int)$match[1].'-'.(int)$match[2];

		if (preg_match('/^ *tab([0-9]+) *$/i', $alias, $match))
			return 'tab'.(int)$match[1];

		$tabID = 0; $alias = RheiaMainClass::transliterate($alias, '-');

		foreach ($tabs as $tab)
		{
			if (is_array($tab))
			{
				if (true !== $tab['name'])
				{
					if (!isSet($tab['link']) && !isSet($tab['redirect']))
					{
						++$tabID;

						if (isSet($tab['alias']))
						{
							if ($tab['alias'] == $alias)
								return 'tab'.$tabID;
						}
						else
						{
							if ('karta-'.$tabID == $alias)
								return 'tab'.$tabID;
						}

						$subtabID = 0;

						if (isSet($tab['subtabs']))
						{
							foreach ($tab['subtabs'] as $subtab)
							{
								if (!isSet($subtab['link']) &&
									!isSet($subtab['redirect']))
								{
									++$subtabID;

									if (isSet($subtab['alias']))
									{
										if ($subtab['alias'] == $alias)
											return 'tab'.$tabID.'-'.$subtabID;
									}
									else
									{
										if ('karta-'.$tabID.'-'.
											$subtabID == $alias)
											return 'tab'.$tabID.'-'.$subtabID;
									}
								}
							}
						}
					}
				}
			}
		}

		return null;
	}


	private function processCalcGetSetPut(&$data)
	{
		$found = false;

		// get
		if (preg_match('/^(.*?)calc\(get; *([^;\(\)]*); '.
			'*([^\(\)]*)\)(.*)$/i', $data, $match))
		{
			$result = null;

			$row = preg_replace('/[-–‒—―−­‑]+/', '-',
				str_replace(',', '.', $match[2]));
			$col = preg_replace('/[-–‒—―−­‑]+/', '-',
				str_replace(',', '.', $match[3]));

			if (is_numeric($row))
				$row = intval(ltrim($row, '0')); else $row = null;
			if (is_numeric($col))
				$col = intval(ltrim($col, '0')); else $col = null;

			if (null !== $row && null !== $col)
			{
				if (isSet($this->calcCaptured[$row]) &&
					isSet($this->calcCaptured[$row][$col]))
					$result = $this->calcCaptured[$row][$col];
			}

			if (null === $result)
			{
				if (isSet($this->calcInstance['empty']))
					$data = $match[1].$this->
						calcInstance['empty'].$match[4];
				else
					$data = $match[1].'code()'.$match[4];
			}
			else
			{
				if (is_float($result))
					$data = $match[1].str_replace('-', '−',
						number_format($result, 2, ',', '')).$match[4];
				elseif (is_numeric($result))
					$data = $match[1].str_replace('-', '−',
						str_replace('.', ',', $result)).$match[4];
				else
					$data = $match[1].str_replace('-', '−',
						$result).$match[4];
			}

			$found = true;
		}

		// set, put
		if (preg_match('/^(.*?)calc\((set|put); *([^;\(\)]*); *([^;\(\)]*); '.
			'*([^\(\)]*)\)(.*)$/i', $data, $match))
		{
			$cmd = strtolower($match[2]);

			$row = preg_replace('/[-–‒—―−­‑]+/', '-',
				str_replace(',', '.', $match[3]));
			$col = preg_replace('/[-–‒—―−­‑]+/', '-',
				str_replace(',', '.', $match[4]));

			if (is_numeric($row))
				$row = intval(ltrim($row, '0')); else $row = null;
			if (is_numeric($col))
				$col = intval(ltrim($col, '0')); else $col = null;

			$result = preg_replace('/[-–‒—―−­‑]+/', '-',
				preg_replace('/code\\(\\)/i', '', $match[5]));

			if (preg_match('/^[^\pL\pN\-]*([0-9\+\-]+)[^\pL\pN]*$/u',
				$result, $match2))
			{
				$result = intval($match2[1]);
			}
			elseif (preg_match('/^[^\pL\pN\-]*([0-9\.,eE\+\-]+)'.
				'[^\pL\pN]*$/u', $result, $match2))
			{
				$result = floatval(str_replace(',', '.', $match2[1]));
			}
			elseif (preg_match('/^[^\pL\pN\+\-]*([\pL\pN  _\+\-]+)'.
				'[^\pL\pN]*$/u', $result, $match2))
			{
				$result = $match2[1];
			}

			if (null !== $row && null !== $col)
				$this->calcCaptured[$row][$col] = $result;

			if ('put' === $cmd)
			{
				if (is_float($result))
					$data = $match[1].str_replace('-', '−',
						number_format($result, 2, ',', '')).$match[6];
				elseif (is_numeric($result))
					$data = $match[1].str_replace('-', '−',
						str_replace('.', ',', $result)).$match[6];
				else
					$data = $match[1].str_replace('-', '−',
						$result).$match[6];
			}
			else $data = $match[1].'code()'.$match[6];

			$found = true;
		}

		return $found;
	}

	/**
	 * Pri návratovej hodnote false musí byť nastavený prvok
	 * $this->calcInstance['line']!
	 */
	private function processCalc($data)
	{
		// $data = preg_replace_callback('/makro\(([^\(\)]+)\)/ui',
		// 	array(&$this, 'expandMacro'), $data);

		$command = strtolower(trim($data));
		switch ($command)
		{
		case 'start':
			$this->calcCaptured = array();
		case 'resume':
			$this->calcInstance['capture'] = true;
			$this->calcInstance['paused'] = false;
			return true;

		case 'pause':
			$this->calcInstance['paused'] = true;
			return true;

		case 'stop':
			$this->calcInstance['capture'] = false;
			return true;

		case 'dump':
			$dump1 = '<table'.RheiaMainClass::getClassTag(
				$this->classes['tables']).'>'.EOL;
			$dump2 = ''; $max = 0;

			foreach ($this->calcCaptured as $i => $row)
			{
				$dump2 .= '<tr><th>'.(1 + $i).'.</th>';
				if (count($row) > $max) $max = count($row);

				foreach ($row as $col)
				{
					if (is_float($col))
						$dump2 .= '<td>('.str_replace('-', '−',
							number_format($col, 2, ',', '')).')</td>';
					elseif (is_numeric($col))
						$dump2 .= '<td>('.str_replace('-', '−',
							str_replace('.', ',', $col)).')</td>';
					else
						$dump2 .= '<td>"'.$col.'"</td>';
				}

				$dump2 .= '</tr>'.EOL;
			}

			$dump1 .= '<thead>'.EOL.'<tr> <th></th>';
			for ($i = 1; $i <= $max; ++$i)
				$dump1 .= '<th>'.$i.'.</th>';
			$dump1 .= '</tr>'.EOL.'</thead>'.EOL;

			$dump2 = '<tbody>'.EOL.$dump2.'</tbody>'.EOL;
			$dump3 = '</table>'.EOL;

			++$this->anonymousDefs;
			$this->rheiaDefs[$this->anonymousDefs] = $dump1.$dump2.$dump3;
			$this->calcInstance['line'] = 'code('.$this->anonymousDefs.')';

			return false;
		}

		$capture = $this->calcInstance['capture'];
		if ($capture && isSet($this->calcInstance['paused']) &&
			$this->calcInstance['paused']) $capture = false;

		if (preg_match('/^empty:(.*)/i', $data, $match))
		{
			$this->calcInstance['empty'] = $match[1];
			return true;
		}
		elseif (preg_match('/^skip:(.*)/i', $data, $match))
		{
			$this->calcInstance['line'] = $match[1];
			return false;
		}
		elseif (preg_match('/^calc:(.*)/i', $data, $match))
		{
			$data = $match[1];
			$capture = false;
		}
		elseif (preg_match('/^capt:(.*)/i', $data, $match))
		{
			$data = $match[1];
			$capture = true;
		}

		if (false !== stripos($data, 'calc(')) for ($i = 0; $i < 100; ++$i)
		{
			if ($this->processCalcGetSetPut($data)) { }
			// avg, cnt, pdt, sum, min, max
			elseif (preg_match(
				'/^(.*?)calc\((avg|cnt|pdt|sum|min|max); *([0-9]+)\)(.*)$/i',
				$data, $match))
			{
				$cmd = strtolower($match[2]);
				$col = intval($match[3]) - 1;
				$num = 0; $result = null;

				switch ($cmd)
				{
				case 'avg':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result += $row[$col];
							++$num;
						}

					if (null !== $result) $result /= $num;
					break;

				case 'cnt':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = 1;
							else
								++$result;
						}
					break;

				case 'pdt':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result *= $row[$col];
						}
					break;

				case 'sum':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result += $row[$col];
						}
					break;

				case 'min':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							elseif ($row[$col] < $result)
								$result = $row[$col];
						}
					break;

				case 'max':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							elseif ($row[$col] > $result)
								$result = $row[$col];
						}
					break;
				}

				if (null === $result)
				{
					if (isSet($this->calcInstance['empty']))
						$data = $match[1].$this->
							calcInstance['empty'].$match[4];
					else
						$data = $match[1].'code()'.$match[4];
				}
				else
				{
					if (is_float($result))
						$data = $match[1].str_replace('-', '−',
							number_format($result, 2, ',', '')).$match[4];
					elseif (is_numeric($result))
						$data = $match[1].str_replace('-', '−',
							str_replace('.', ',', $result)).$match[4];
					else
						$data = $match[1].$result.$match[4];
				}
			}
			// aif, cif, pif, sif, mif, xif
			elseif (preg_match('/^(.*?)calc\(([acpsmx])if; *([0-9]+); '.
				'*([0-9]+); *"([^"]*)"\)(.*)$/i', $data, $match))
			{
				$cmd = strtolower($match[2]);
				$col = intval($match[3]) - 1;
				$colif = intval($match[4]) - 1;
				$cmpif = $match[5];
				$num = 0; $result = null;

				switch ($cmd)
				{
				case 'a':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result += $row[$col];
							++$num;
						}

					if (null !== $result) $result /= $num;
					break;

				case 'c':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = 1;
							else
								++$result;
						}
					break;

				case 'p':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result *= $row[$col];
						}
					break;

				case 's':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							else
								$result += $row[$col];
						}
					break;

				case 'm':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							elseif ($row[$col] < $result)
								$result = $row[$col];
						}
					break;

				case 'x':
					foreach ($this->calcCaptured as $row)
						if (isSet($row[$colif]) && 0 ===
							strcasecmp($row[$colif], $cmpif) &&
							isSet($row[$col]) && is_numeric($row[$col]))
						{
							if (null === $result)
								$result = $row[$col];
							elseif ($row[$col] > $result)
								$result = $row[$col];
						}
					break;
				}

				if (null === $result)
				{
					if (isSet($this->calcInstance['empty']))
						$data = $match[1].$this->
							calcInstance['empty'].$match[6];
					else
						$data = $match[1].'code()'.$match[6];
				}
				else
				{
					if (is_float($result))
						$data = $match[1].str_replace('-', '−',
							number_format($result, 2, ',', '')).$match[6];
					elseif (is_numeric($result))
						$data = $match[1].str_replace('-', '−',
							str_replace('.', ',', $result)).$match[6];
					else
						$data = $match[1].$result.$match[6];
				}
			}
			else break;
		}

		// “…This” functions
		if (false !== stripos($data, 'calc(')) for ($i = 0; $i < 100; ++$i)
		{
			if ($this->processCalcGetSetPut($data)) { }
			// avg, cnt, pdt, sum, min, max
			elseif (preg_match('/^(.*?)calc\((avg|cnt|pdt|sum|min|max)this'.
				'; *([0-9\.,; \-–]+)\)(.*)$/i', $data, $match))
			{
				$cols = explode('	', $data);
				foreach ($cols as $j => $col)
				{
					$col = preg_replace('/[-–‒—―−­‑]+/', '-',
						preg_replace('/code\\(\\)/i', '', $col));

					if (preg_match('/^[^\pL\pN\-]*([0-9\+\-]+)[^\pL\pN]*$/u',
						$col, $match2))
					{
						$cols[$j] = intval($match2[1]);
					}
					elseif (preg_match('/^[^\pL\pN\-]*([0-9\.,eE\+\-]+)'.
						'[^\pL\pN]*$/u', $col, $match2))
					{
						$cols[$j] = floatval(str_replace(',', '.', $match2[1]));
					}
					elseif (preg_match('/^[^\pL\pN\+\-]*([\pL\pN  _\+\-]+)'.
						'[^\pL\pN]*$/u', $col, $match2))
					{
						$cols[$j] = $match2[1];
					}
				}

				$cmd = strtolower($match[2]);

				$colSet = array();
				$colSplit = preg_split('/[;]+/', preg_replace('/[  ]/', '',
					$match[3]), -1, PREG_SPLIT_NO_EMPTY);

				// $this->calcCaptured[] = array($cmd, $match[3], $colSplit[0], $colSplit[1], $colSplit[2], $colSplit[3]);

				foreach ($colSplit as $colNum)
				{
					$colNum = preg_split('/[\-–]/', trim($colNum),
						-1, PREG_SPLIT_NO_EMPTY);

					// $this->calcCaptured[] = $colNum;

					$colNum[0] = intval($colNum[0]);

					if (!isSet($colNum[1]))
						$colNum[1] = $colNum[0];
					else
						$colNum[1] = intval($colNum[1]);

					if ($colNum[0] > $colNum[1])
					{
						$j = $colNum[0];
						$colNum[0] = $colNum[1];
						$colNum[1] = $j;
					}

					// $this->calcCaptured[] = $colNum;

					if (0 === $colNum[0]) continue;

					for ($j = $colNum[0]; $j <= $colNum[1]; ++$j)
						$colSet[$j - 1] = true;
				}

				// $a = array(); foreach ($colSet as $colNum => $dummy) $a[] = $colNum; $this->calcCaptured[] = $a;

				$num = 0; $result = null;

				switch ($cmd)
				{
				case 'avg':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = $cols[$colNum];
							else
								$result += $cols[$colNum];
							++$num;
						}

					if (null !== $result) $result /= $num;
					break;

				case 'cnt':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = 1;
							else
								++$result;
						}
					break;

				case 'pdt':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = $cols[$colNum];
							else
								$result *= $cols[$colNum];
						}
					break;

				case 'sum':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = $cols[$colNum];
							else
								$result += $cols[$colNum];
						}
					break;

				case 'min':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = $cols[$colNum];
							elseif ($cols[$colNum] < $result)
								$result = $cols[$colNum];
						}
					break;

				case 'max':
					foreach ($colSet as $colNum => $dummy)
						if (isSet($cols[$colNum]) && is_numeric($cols[$colNum]))
						{
							if (null === $result)
								$result = $cols[$colNum];
							elseif ($cols[$colNum] > $result)
								$result = $cols[$colNum];
						}
					break;
				}

				if (null === $result)
				{
					if (isSet($this->calcInstance['empty']))
						$data = $match[1].$this->
							calcInstance['empty'].$match[4];
					else
						$data = $match[1].'code()'.$match[4];
				}
				else
				{
					if (is_float($result))
						$data = $match[1].str_replace('-', '−',
							number_format($result, 2, ',', '')).$match[4];
					elseif (is_numeric($result))
						$data = $match[1].str_replace('-', '−',
							str_replace('.', ',', $result)).$match[4];
					else
						$data = $match[1].$result.$match[4];
				}
			}
			else break;
		}

		if (false !== stripos($data, 'calc(')) for ($i = 0; $i < 100; ++$i)
		{
			if ($this->processCalcGetSetPut($data)) { }
			// add, sub, mul, div, mod, pow
			elseif (preg_match('/^(.*?)calc\((add|sub|mul|div|mod|pow); '.
				'*([^;\(\)]*); *([^\(\)]*)\)(.*)$/i', $data, $match))
			{
				$result = null;

				$operand1 = preg_replace('/[-–‒—―−­‑]+/', '-',
					str_replace(',', '.', $match[3]));
				$operand2 = preg_replace('/[-–‒—―−­‑]+/', '-',
					str_replace(',', '.', $match[4]));

				if (is_numeric($operand1))
					$result = $operand1;

				if (is_numeric($operand2))
				{
					$cmd = strtolower($match[2]);

					if (null === $result)
					{
						if ('sub' === $cmd)
							$result = -$operand2;
						else
							$result = $operand2;
					}
					else
					{
						switch ($cmd)
						{
						case 'add': $result += $operand2; break;
						case 'sub': $result -= $operand2; break;
						case 'mul': $result *= $operand2; break;
						case 'div': $result /= $operand2; break;
						case 'mod': $result %= $operand2; break;
						case 'pow': $result = pow($result, $operand2); break;
						}
					}
				}

				// $match[1] = $match[1].''; $match[5] = ''.$match[5];

				if (null === $result)
				{
					if (isSet($this->calcInstance['empty']))
						$data = $match[1].$this->
							calcInstance['empty'].$match[5];
					else
						$data = $match[1].'code()'.$match[5];
				}
				else
					$data = $match[1].str_replace('-', '−',
						str_replace('.', ',', $result)).$match[5];
			}
			else break;
		}

		if ($capture)
		{
			$cols = explode('	', $data);
			foreach ($cols as $i => $col)
			{
				$col = preg_replace('/[-–‒—―−­‑]+/', '-',
					preg_replace('/code\\(\\)/i', '', $col));

				if (preg_match('/^[^\pL\pN\-]*([0-9\+\-]+)[^\pL\pN]*$/u',
					$col, $match))
				{
					$cols[$i] = intval($match[1]);
				}
				elseif (preg_match('/^[^\pL\pN\-]*([0-9\.,eE\+\-]+)'.
					'[^\pL\pN]*$/u', $col, $match))
				{
					$cols[$i] = floatval(str_replace(',', '.', $match[1]));
				}
				elseif (preg_match('/^[^\pL\pN\+\-]*([\pL\pN  _\+\-]+)'.
					'[^\pL\pN]*$/u', $col, $match))
				{
					$cols[$i] = $match[1];
				}
			}

			$this->calcCaptured[] = $cols;
		}

		$this->calcInstance['line'] = $data;
		return false;
	}

	private function spendID($type)
	{
		if (!empty($this->autoids) && is_array($this->autoids) &&
			!empty($this->autoids[$type]) // && is_array($this->autoids[$type])
			)
		{
			// $ID = $this->autoids[$type][0] . $this->autoids[$type][1];
			$ID = $this->autoids[$type] .
				$this->autoidcnts[$this->autoids[$type]];
			++$this->autoidcnts[$this->autoids[$type]];
			return ' id="'.$ID.'"';
		}

		if (empty($this->ids) ||
			!is_array($this->ids)) return '';
		if (!empty($this->ids[$type]))
		{
			$ID = $this->ids[$type];
			$this->ids[$type] = null;
			return ' id="'.$ID.'"';
		}
		return '';
	}

	private function autoIDCntsToJavaScript()
	{
		$makeScript = '';
		foreach ($this->autoidcnts as $id => $cnt)
			$makeScript .= TAB.'myGlobals[\''.$id.'Count\'] = '.$cnt.';'.EOL;
		return $makeScript;
	}


	public function generateHTML($plainText)
	{
		// Initialize defaults
		$this->classes = RheiaMainClass::$defaultClasses;
		$this->ids = RheiaMainClass::$defaultIds;
		$this->autoids = RheiaMainClass::$defaultIds;
		$this->autoidcnts = array();
		$this->files = null; $this->externFiles = null;
		$this->lastFile = null; $this->externLastFile = null;
		$this->lastDate = null; $this->styleSheed = null;
		$this->javaScript = null; $this->onShow = null; $this->onExit = null;
		$this->linkStyles = null; $this->linkJavaScripts = null;
		$this->icalProcessing = array('capture' => false);
		$this->icalData = array();
		$this->calcInstance = array('capture' => false);
		$this->calcCaptured = array();

		// Prepare the string (split to lines)
		$plainText = nl2br($plainText);
		$lines = explode('<br />', $plainText);

		// Trim whitespace
		foreach ($lines as $i => $line)
			$lines[$i] = preg_replace('/[ \r\n]+/', ' ',
				trim($line, ' '.EOL));

		// Initialize variables
		$this->articlesRefs = array();
		$this->rheiaDefs = array('style' => null,
			'javaScript' => null, 'onShow' => null, 'onExit' => null);
		$this->anonymousDefs = 0;

		$html = ''; $lines[] = ''; $captureBuffer = array();
		$captureLiteral = false; $noTabRedirect = false;
		$noSubtabRedirect = false; $tabs = null; $newTabName = false;
		$newSubtabName = false; $matches = null; $buffer = null; $flag = null;
		$flagBak = null; $cols = 0; $rows = 0; $colspan = null;
		$rowspan = null; $number = 0;


		// Detekcia fatálnych chýb generátora (začiatok)
		$fatalError = true;
		try {


		// Generate HTML
		foreach ($lines as $line)
		{
			if (preg_match('/^#closecapture$/i', $line))
			{
				$captured = array_pop($captureBuffer);
				if (null === $captured)
				{
					// $captureLiteral = false;
					continue;
				}

				$capturedContent = $html;
				$html = $captured[0];

				if (!$captureLiteral && null !== $flag)
					$html .= '<p class="error">'.RheiaMainClass::
					$text['error-capture-not-closed-correctly'].' '.
					RheiaMainClass::$text['error-capture-not-neutral-state'].
					'</p>'.EOL;

				$caseMatch = $captured[1];
				$noCaseMatch = strtolower($captured[1]);

				if ($noCaseMatch == 'code')
				{
					++$this->anonymousDefs;
					$this->rheiaDefs[$this->anonymousDefs] =
						implode(EOL, $capturedContent);
					$html .= 'code('.$this->anonymousDefs.')'.EOL;
				}
				elseif ($noCaseMatch == 'style')
				{
					$this->rheiaDefs['style'] .=
						RheiaMainClass::replaceObjects(
							implode(EOL, $capturedContent)).EOL;
				}
				elseif ($noCaseMatch == 'javascript')
				{
					foreach ($capturedContent as $value)
						$this->rheiaDefs['javaScript'] .= TAB.$value.EOL;
				}
				elseif ($noCaseMatch == 'onshow')
				{
					foreach ($capturedContent as $value)
						$this->rheiaDefs['onShow'] .= TAB.$value.EOL;
				}
				elseif ($noCaseMatch == 'onExit')
				{
					foreach ($capturedContent as $value)
						$this->rheiaDefs['onExit'] .= TAB.$value.EOL;
				}
				else
				{
					$this->rheiaDefs[$caseMatch] = $capturedContent;
				}

				$captureLiteral = false;
				// var_dump($matches);
				continue;
			}

			if ($captureLiteral)
			{
				$html[] = $line;
				continue;
			}

			if (preg_match('/^#ical:(.*)$/i', $line, $matches))
			{
				$this->setupIcal($matches[1]);
				continue;
			}

			if ('pre' === $flag)
			{
				if (preg_match('/^#endcode$/i', $line))
				{
					$html .= '</pre>'.EOL;
					$flag = $flagBak;
				}
				else
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					$html .= htmlspecialchars($line).EOL;
				}
				continue;
			}
			elseif (preg_match('/^#startpas[qc][au]i?lcode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizePasquil();'))
				{
					$this->linkStyles['colorizePasquil'] = true;
					$this->linkJavaScripts['colorizePasquil'] = true;
					$this->javaScript .= TAB.'colorizePasquil();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'pasquilCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#startjavacode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizeJava();'))
				{
					$this->linkStyles['colorizeJava'] = true;
					$this->linkJavaScripts['colorizeJava'] = true;
					$this->javaScript .= TAB.'colorizeJava();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'javaCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#startccppcode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizeCCPP();'))
				{
					$this->linkStyles['colorizeCCPP'] = true;
					$this->linkJavaScripts['colorizeCCPP'] = true;
					$this->javaScript .= TAB.'colorizeCCPP();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'ccppCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#starthtmlcode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizeHTML();'))
				{
					// Includes also Java for JavaScript:
					$this->linkStyles['colorizeJava'] = true;
					$this->linkJavaScripts['colorizeJava'] = true;

					// Includes also CSS:
					$this->linkStyles['colorizeCSS'] = true;
					$this->linkJavaScripts['colorizeCSS'] = true;

					$this->linkStyles['colorizeHTML'] = true;
					$this->linkJavaScripts['colorizeHTML'] = true;
					$this->javaScript .= TAB.'colorizeHTML();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'HTMLCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#startcsscode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizeCSS();'))
				{
					$this->linkStyles['colorizeCSS'] = true;
					$this->linkJavaScripts['colorizeCSS'] = true;
					$this->javaScript .= TAB.'colorizeCSS();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'CSSCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#startphpcode$/i', $line))
			{
				if (false === strpos($this->javaScript, 'colorizePHP();'))
				{
					// Includes also Java for JavaScript:
					$this->linkStyles['colorizeJava'] = true;
					$this->linkJavaScripts['colorizeJava'] = true;

					// Includes also CSS:
					$this->linkStyles['colorizeCSS'] = true;
					$this->linkJavaScripts['colorizeCSS'] = true;

					// Includes also HTML:
					$this->linkStyles['colorizeHTML'] = true;
					$this->linkJavaScripts['colorizeHTML'] = true;

					$this->linkStyles['colorizePHP'] = true;
					$this->linkJavaScripts['colorizePHP'] = true;
					$this->javaScript .= TAB.'colorizePHP();'.EOL;
				}

				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass'],
						'PHPCode').'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}
			elseif (preg_match('/^#startcode$/i', $line))
			{
				$html .= '<pre'.$this->spendID('code').
					RheiaMainClass::getClassTag($this->classes['codeClass']).
						'>'.EOL;
				$flagBak = $flag;
				$flag = 'pre';
				continue;
			}

			$line = preg_replace_callback('/makro\(([^\(\)]+)\)/ui',
				array(&$this, 'expandMacro'), $line);

			if (preg_match('/^#calc:(.*)$/i', $line, $matches))
			{
				if ($this->processCalc($matches[1])) continue;
				$line = $this->calcInstance['line'];
			}
			elseif (!empty($this->calcInstance['capture']))
			{
				if ($this->processCalc($line)) continue;
				$line = $this->calcInstance['line'];
			}

			// if (false !== strpos($line, "\n"))
			// { echo 'makro obsahuje viaceré riadky'; }

			if (preg_match('/^#reconstruct#$/i', $line))
			{
				$html .= '<p class="note"><small><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-reconstruct'].
					'…</small></p>'.EOL;
				continue;
			}

			if (preg_match('/^#update#$/i', $line))
			{
				$html .= '<p class="note"><small><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-update'].
					'.</small></p>'.EOL;
				continue;
			}

			if (preg_match('/^#work-soon#$/i', $line))
			{
				$html .= '<p class="note"><small><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-work-soon'].
					'…</small></p>'.EOL;
				continue;
			}

			if (preg_match('/^#work#$/i', $line))
			{
				$html .= '<p class="note"><small><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-work'].'…</small></p>'.EOL;
				continue;
			}

			if (preg_match('/^#not-available#$/i', $line))
			{
				$html .= '<p class="note"><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-not-available'].'…</p>'.EOL;
				continue;
			}

			if (preg_match('/^#(empty|null)#$/i', $line))
			{
				$html .= '<p class="note"><b>'.
					RheiaMainClass::$text['common-note'].':</b> '.
					RheiaMainClass::$text['common-empty'].'…</p>'.EOL;
				continue;
			}

			if (preg_match('/^#makro:\s*([^,]+),(.*)/ui', $line, $matches))
			{
				$this->macro[$matches[1]] = $matches[2];
				continue;
			}

			if (preg_match('/^#titulok:\s*(.*)/ui', $line, $matches))
			{
				$this->title = $matches[1];
				continue;
			}

			if (preg_match('/^#alt:\s*(.*)/ui', $line, $matches))
			{
				RheiaMainClass::$text['image-alt'] = $matches[1];
				continue;
			}

			if (preg_match('/^#(nadpis|názov):\s*(.*)\|(.*)/ui',
				$line, $matches))
			{
				$line = RheiaMainClass::replaceObjects($matches[2]);

				$html .= '<div class="title"><h1><a class="permalink" href="'.
					RheiaMainClass::solveInternalRedirectURL(RheiaMainClass::$protocol.'://'.rtrim(
						RheiaMainClass::$domain.'/'.rtrim(RheiaMainClass::$siteSectionPath,
							'/'), '/').'/'.ltrim($matches[3], '/')).
					'" rel="bookmark" title="';

				if (empty($this->title))
				{
					$this->title = RheiaMainClass::filterHTML($line);
					$html .= $this->title;
				}
				else
				{
					$html .= RheiaMainClass::filterHTML($line);
				}

				$html .= '">'.$line.'</a></h1></div>';
				continue;
			}

			if (preg_match('/^#blok:\s*(.*)\|(.*)\|(.*)\|(.*)/ui',
				$line, $matches))
			{
				$line = RheiaMainClass::replaceObjects($matches[1]);

				$html .= '<div';
				if (!empty($matches[2])) $html .= ' id="'.$matches[2].'"';
				if (!empty($matches[3])) $html .= ' class="'.$matches[3].'"';
				if (!empty($matches[4])) $html .= ' style="'.$matches[4].'"';
				$html .= '>'.$line.'</div>';

				continue;
			}

			if (preg_match('/^#karta:\s*(.*)/ui', $line, $matches))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $this->handleSpecialCodes($matches[1]);
				$newTabName = $matches[1]; $noSubtabRedirect = false;
			}

			if (preg_match('/^#podkarta:\s*(.*)/ui', $line, $matches))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $this->handleSpecialCodes($matches[1]);
				$newSubtabName = $matches[1];
			}

			if (preg_match('/^#pod\s*kartami\s*$/ui', $line, $matches))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $newTabName = true;
				$noSubtabRedirect = false;
			}

			if (preg_match('/^#pod\s*podkartami\s*$/ui', $line, $matches))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $newSubtabName = true;
			}

			if (preg_match('/^#žiadna\s*karta\s*$/ui', $line))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $newTabName = null;
			}

			if (preg_match('/^#žiadna\s*podkarta\s*$/ui', $line))
			{
				if (null === $tabs) $tabs = true;
				$line = ''; $newSubtabName = null;
			}

			if (preg_match('/^#predvolená\s*(pod)?karta\s*$/ui', $line))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['select'] = true;
					}
					else
						$tabs[$i]['select'] = true;
				}
				$line = '';
			}

			if (preg_match('/^#alias\s*(?:pod)?karty:\s*(.*)/ui',
				$line, $matches))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['alias'] =
							RheiaMainClass::transliterate($matches[1], '-');
					}
					else
					{
						$tabs[$i]['alias'] =
							RheiaMainClass::transliterate($matches[1], '-');
					}
				}
				$line = '';
			}

			if (preg_match('/^#odkaz\s*(?:pod)?karty:\s*(.*)/ui',
				$line, $matches))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['link'] = $matches[1];
					}
					else
					{
						$tabs[$i]['link'] = $matches[1];
					}
				}
				$line = '';
			}

			if (preg_match('/^#presmerovanie\s*(?:pod)?karty:\s*(.*)/ui',
				$line, $matches))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['redirect'] = $matches[1];
					}
					else
					{
						$tabs[$i]['redirect'] = $matches[1];
					}
				}
				$line = '';
			}

			if (preg_match('/^#zlomová\s*(?:pod)?karta\s*$/ui',
				$line, $matches))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['break'] = true;
					}
					else
					{
						$tabs[$i]['break'] = true;
					}
				}
				$line = '';
			}

			if (preg_match('/^#skrytá\s*(?:pod)?karta\s*$/ui',
				$line, $matches))
			{
				$i = count($tabs) - 2;
				if (is_array($tabs[$i]))
				{
					if (isSet($tabs[$i]['subtabs']))
					{
						$j = count($tabs[$i]['subtabs']) - 1;
						$tabs[$i]['subtabs'][$j]['hidden'] = true;
					}
					else
					{
						$tabs[$i]['hidden'] = true;
					}
				}
				$line = '';
			}

			if ('' === $line)
			{
				switch ($flag)
				{
				case 'p':
					if (!empty($this->classes['paragraph']))
					{
						$html .= '<p'.$this->spendID('paragraph').
							RheiaMainClass::getClassTag(
							$this->classes['paragraph']).
							$this->handleSpecialCodes($buffer).'>';
						$this->classes['paragraph'] = null;
					}
					else $html .= '<p'.$this->spendID('paragraph').
						RheiaMainClass::getClassTag(
						$this->classes['paragraphs']).
						$this->handleSpecialCodes($buffer).'>';
					$html .= $buffer.'</p>'.EOL;
					// $this->classes['paragraph'] = null;
					break;

				case 'd': case 'u':
					$html .= '<li'.$this->handleSpecialCodes($buffer).
						$this->spendID('listItem').'>';
					$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;
					$this->classes['unorderedList'] = null;
					break;

				case 'o':
					$html .= '<li'.$this->handleSpecialCodes($buffer).
						$this->spendID('listItem').'>';
					$html .= $buffer.'</li>'.EOL.'</ol>'.EOL; $number = 0;
					$this->classes['orderedList'] = null;
					break;

				case 't':
					RheiaMainClass::$mailType = 'table';
					if (!empty($this->classes['table']))
					{
						$html .= '<table'.$this->spendID('table').
							RheiaMainClass::getClassTag(
							$this->classes['table']).'>'.EOL;
						$this->classes['table'] = null;
					}
					else $html .= '<table'.$this->spendID('table').
						RheiaMainClass::getClassTag(
						$this->classes['tables']).'>'.EOL;

					// Remove first column if is completely empty
					$del1stCol = true;

					for ($i = 0; $i < $rows; ++$i)
					{
						if (!empty($buffer[$i][0]) &&
							'*' !== $buffer[$i][0]/* &&
							' ' !== $buffer[$i][0]*/)
						{
							$del1stCol = false;
							break;
						}
					}

					if ($del1stCol)
					{
						--$cols;
						for ($i = 0; $i < $rows; ++$i)
							array_shift($buffer[$i]);
					}

					for ($i = 0; $i < $rows; ++$i)
					{
						// $html .= '	'.($i ? (($i == 1) ? '<tbody>' :
						// 	'') : '<thead>').'<tr';

						if (isSet($buffer['lastblock']))
						{
							if ('tbody' === $buffer['lastblock'] &&
								isSet($buffer['theads'][$i]))
							{
								$html .= '</tbody>'.EOL.'	<thead>';
								$buffer['lastblock'] = 'thead';
							}
							else if ('thead' === $buffer['lastblock'] &&
								!isSet($buffer['theads'][$i]))
							{
								$html .= '</thead>'.EOL.'	<tbody>';
								$buffer['lastblock'] = 'tbody';
							}
							else $html .= EOL.'	';
						}
						else
						{
							if (isSet($buffer['theads'][$i]))
							{
								$html .= '	<thead>';
								$buffer['lastblock'] = 'thead';
							}
							else
							{
								$html .= '	<tbody>';
								$buffer['lastblock'] = 'tbody';
							}
						}

						$classList = null;

						if (!$i)
							RheiaMainClass::addClassToList($classList,
								$this->classes['firstRows']);
						if (($i + 1) == $rows)
							RheiaMainClass::addClassToList($classList,
								$this->classes['lastRows']);

						if ($i % 2)
							RheiaMainClass::addClassToList($classList,
								$this->classes['evenRows']);
						else
							RheiaMainClass::addClassToList($classList,
								$this->classes['oddRows']);

						$html .= '<tr'.RheiaMainClass::getClassTag(
							$classList).$this->spendID('tableRow').'>'.EOL;

						for ($j = 0; $j < $cols; ++$j)
						{
							$classList = null;

							if (!$j)
								RheiaMainClass::addClassToList($classList,
									$this->classes['leftCells']);
							if (($j + 1) == $cols)
								RheiaMainClass::addClassToList($classList,
									$this->classes['rightCells']);

							if ($j % 2)
								RheiaMainClass::addClassToList($classList,
									$this->classes['evenCells']);
							else
								RheiaMainClass::addClassToList($classList,
									$this->classes['oddCells']);

							/* if (!isSet($buffer[$i][$j]))
							{
								RheiaMainClass::logError('Error in Table: '.
									$this->baseName);
							}
							else */
							if (/**/ isSet($buffer[$i][$j]) && /**/
								false === $buffer[$i][$j])
							{ /* ignored */ }
							else if (/**/ !isSet($buffer[$i][$j]) || /**/
								'' === $buffer[$i][$j])
							{
								if (isSet($buffer['theads'][$i]))
									$html .= '		<th'.($rowspan ? (
										' rowspan="'.$rowspan.'"') : '').
										($colspan ? (' colspan="'.$colspan.
											'"') : '').'> </th>'.EOL;
								else
									$html .= '		<td'.($rowspan ? (
										' rowspan="'.$rowspan.'"') : '').
										($colspan ? (' colspan="'.$colspan.
											'"') : '').'> </td>'.EOL;
								$colspan = $rowspan = null;
							}
							else if ('#' === $buffer[$i][$j] ||
								'*#' === $buffer[$i][$j])
							{
								if (null === $colspan) $colspan = 1;
								if ($j < $cols - 1) ++$colspan;
							}
							else
							{
								if ('@' === $buffer[$i][$j] ||
									'*@' === $buffer[$i][$j])
								{
									if (null === $rowspan) $rowspan = 1;

									for ($k = $i; $k < $rows; ++$k)
									{
										if ('@' === $buffer[$k][$j] ||
											'*@' === $buffer[$k][$j])
										{
											if ($k < $rows - 1) ++$rowspan;
											if ($colspan)
											{
												for ($l = 0; $l <
													$colspan; ++$l)
													$buffer[$k]
														[$j - $l] = false;
											}
											else $buffer[$k][$j] = false;
										}
										else
										{
											$buffer[$i][$j] = $buffer[$k][$j];
											if ($colspan)
											{
												for ($l = 0; $l <
													$colspan; ++$l)
													$buffer[$k]
														[$j - $l] = false;
											}
											else $buffer[$k][$j] = false;
											break;
										}
									}
								}

								$cell = $buffer[$i][$j];
								if (0 === strpos($cell, '*'))
								{
									$cell = substr($cell, 1); $attrs = '';

									RheiaMainClass::addClassToList($classList,
										$this->classes['headCells']);

									if (0 === stripos($cell, 'left|'))
									{
										$attrs = ' style="text-align:left"';
										$cell = trim(substr($cell, 5));
									}
									elseif (0 === strpos($cell, '<|'))
									{
										$attrs = ' style="text-align:left"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'center|'))
									{
										$attrs = ' style="text-align:'.
											'center"';
										$cell = trim(substr($cell, 7));
									}
									elseif (0 === strpos($cell, '||'))
									{
										$attrs = ' style="text-align:'.
											'center"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'right|'))
									{
										$attrs = ' style="text-align:right"';
										$cell = trim(substr($cell, 6));
									}
									elseif (0 === strpos($cell, '>|'))
									{
										$attrs = ' style="text-align:right"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'justify|'))
									{
										$attrs = ' style="text-align:justify"';
										$cell = trim(substr($cell, 8));
									}
									elseif (0 === strpos($cell, '_|'))
									{
										$attrs = ' style="text-align:justify"';
										$cell = trim(substr($cell, 2));
									}

									if (preg_match('/^#cell#([^#]+)#(.*)$/',
										$cell, $matches))
									{
										RheiaMainClass::addClassToList(
											$classList, $matches[1]);
										$cell = $matches[2];
									}

									if (preg_match('/^- *\{.+\|down:/', $cell)
										|| preg_match(
											'/^- *\{.+\|plaindown:/', $cell)
										|| preg_match(
											'/^- *\{.+\|externdown:/', $cell)
										|| preg_match(
											'/^- *\{.+\|plainexterndown:/',
											$cell))
									{
										$cell = ltrim(ltrim($cell, '-'));
										$html .= '		<th'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan.'"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.'><ul'.$this->spendID('unorderedList').
												RheiaMainClass::getClassTag($this->
												classes['unorderedList'],
												'downloads-list').'>'.
											'<li'.$this->handleSpecialCodes(
												$cell).$this->spendID('listItem').'>';
										$html .= $cell.'</li></ul></th>'.EOL;
									}
									elseif ('' !== $cell && '-' === $cell[0])
									{
										$cell = ltrim(ltrim($cell, '-'));
										$html .= '		<th'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan. '"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.'><ul'.$this->spendID('unorderedList').
											RheiaMainClass::getClassTag(
												$this->classes['unorderedList']).
											'><li'.$this->handleSpecialCodes(
												$cell).$this->spendID('listItem').'>';
										$html .= $cell.'</li></ul></th>'.EOL;
									}
									else
									{
										$html .= '		<th'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan.'"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.
											$this->handleSpecialCodes($cell).
											'>';
										$html .= $cell.'</th>'.EOL;
									}
								}
								else
								{
									$attrs = '';

									if (0 === stripos($cell, 'left|'))
									{
										$attrs = ' style="text-align:left"';
										$cell = trim(substr($cell, 5));
									}
									elseif (0 === strpos($cell, '<|'))
									{
										$attrs = ' style="text-align:left"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'center|'))
									{
										$attrs = ' style="text-align:'.
											'center"';
										$cell = trim(substr($cell, 7));
									}
									elseif (0 === strpos($cell, '||'))
									{
										$attrs = ' style="text-align:'.
											'center"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'right|'))
									{
										$attrs = ' style="text-align:right"';
										$cell = trim(substr($cell, 6));
									}
									elseif (0 === strpos($cell, '>|'))
									{
										$attrs = ' style="text-align:right"';
										$cell = trim(substr($cell, 2));
									}
									elseif (0 === stripos($cell, 'justify|'))
									{
										$attrs = ' style="text-align:justify"';
										$cell = trim(substr($cell, 8));
									}
									elseif (0 === strpos($cell, '_|'))
									{
										$attrs = ' style="text-align:justify"';
										$cell = trim(substr($cell, 2));
									}

									if (preg_match('/^#cell#([^#]+)#(.*)$/',
										$cell, $matches))
									{
										RheiaMainClass::addClassToList(
											$classList, $matches[1]);
										$cell = $matches[2];
									}

									if (preg_match('/^- *\{.+\|down:/', $cell)
										|| preg_match(
											'/^- *\{.+\|plaindown:/', $cell)
										|| preg_match(
											'/^- *\{.+\|externdown:/', $cell)
										|| preg_match(
											'/^- *\{.+\|plainexterndown:/',
											$cell))
									{
										$cell = ltrim(ltrim($cell, '-'));
										$html .= '		<td'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan.'"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.'><ul'.$this->spendID('unorderedList').
												RheiaMainClass::getClassTag($this->
												classes['unorderedList'],
												'downloads-list').'>'.
											'<li'.$this->handleSpecialCodes(
												$cell).$this->spendID('listItem').'>';
										$html .= $cell.'</li></ul></td>'.EOL;
									}
									elseif ('' !== $cell && '-' === $cell[0])
									{
										$cell = ltrim(ltrim($cell, '-'));
										$html .= '		<td'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan.'"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.'><ul'.$this->spendID('unorderedList').
												RheiaMainClass::getClassTag(
												$this->classes['unorderedList']).
											'><li'.$this->handleSpecialCodes(
												$cell).$this->spendID('listItem').'>';
										$html .= $cell.'</li></ul></td>'.EOL;
									}
									else
									{
										$html .= '		<td'.($rowspan ?
											(' rowspan="'.$rowspan.'"') : '').
											($colspan ?
											(' colspan="'.$colspan.'"') : '').
											RheiaMainClass::getClassTag($classList).
											$attrs.
											$this->handleSpecialCodes($cell).
											'>';
										$html .= $cell.'</td>'.EOL;
									}
								}

								$colspan = $rowspan = null;
							}
						}

						if ($colspan || $rowspan)
						{
							$html .= '		<td'.($rowspan ? (' rowspan="'.
								$rowspan.'"') : '').($colspan ? (' colspan="'.
								$colspan.'"') : '').'> </td>'.EOL;
							$colspan = $rowspan = null;
						}

						$html .= '	</tr>';
						// $html .= '	</tr>'.($i ? ((($i + 1) == $rows) ?
						// 	'</tbody>' : '') : '</thead>').EOL;
					}

					if (isSet($buffer['lastblock']))
					{
						if ('tbody' === $buffer['lastblock'])
							$html .= '</tbody>'.EOL;
						else
							$html .= '</thead>'.EOL;
					}

					$html .= '</table>'.EOL;
					RheiaMainClass::$mailType = 'paragraph';
					break;
				}

				// Store tabs
				if (null !== $tabs)
				{
					/*
					array(
						0 => 'pôvodné html',
						'notab' => 'obsah žiadnej karty', // nepovinné

						(n >= 1) => array(
							'name' => 'názov',
							'content' => 'obsah',
							'nosubtab' => 'obsah žiadnej podkarty', // nepovinné
							'subtabs' => array(
								'name' => 'názov',
								'content' => 'obsah',
								),
							),
					)
					*/
					if (true === $tabs)
					{
						if (null === $newTabName)
						{
							$tabs = array($html, 'notab' => '');
							$noTabRedirect = true;
						}
						else
						{
							$tabs = array($html, 'notab' => false,
								array(
									'name' => $newTabName,
									'content' => ''));
							$noTabRedirect = false;
						}

						$html = '';
						$newTabName = false;

						if (null === $newSubtabName)
						{
							$tabs[2]['nosubtab'] = '';
							$newSubtabName = false;
							$noSubtabRedirect = true;
						}
						elseif ($newSubtabName)
						{
							$tabs[2]['subtabs'][] = array(
								'name' => $newSubtabName,
								'content' => '');
							$newSubtabName = false;
							$noSubtabRedirect = false;
						}
					}
					else
					{
						$i = count($tabs) - 2;
						if (is_array($tabs[$i]))
						{
							if ($noSubtabRedirect)
							{
								if (isSet($tabs[$i]['nosubtab']))
									$tabs[$i]['nosubtab'] .= $html;
								else
									$tabs[$i]['nosubtab'] = $html;
							}
							elseif ($noTabRedirect)
								$tabs['notab'] .= $html;
							elseif (isSet($tabs[$i]['subtabs']))
							{
								$j = count($tabs[$i]['subtabs']) - 1;
								$tabs[$i]['subtabs']
									[$j]['content'] .= $html;
							}
							else $tabs[$i]['content'] .= $html;
						}
						elseif ($noTabRedirect)
							$tabs['notab'] .= $html;
						else
							$tabs[$i] .= $html;
						$html = '';

						if (null === $newTabName)
						{
							if (false === $tabs['notab'])
								$tabs['notab'] = '';
							$newTabName = false;
							$noTabRedirect = true;
							$noSubtabRedirect = false;
						}
						elseif ($newTabName)
						{
							$tabs[] = array(
								'name' => $newTabName,
								'content' => '');
							$newTabName = false;
							$noTabRedirect = false;
							$noSubtabRedirect = false;
						}

						if (null === $newSubtabName)
						{
							if (!isSet($tabs[$i]['nosubtab']))
								$tabs[$i]['nosubtab'] = '';
							$newSubtabName = false;
							$noTabRedirect = false;
							$noSubtabRedirect = true;
						}
						elseif ($newSubtabName)
						{
							$tabs[$i]['subtabs'][] = array(
								'name' => $newSubtabName,
								'content' => '');
							$newSubtabName = false;
							$noTabRedirect = false;
							$noSubtabRedirect = false;
						}
					}
				}

				$buffer = null; $flag = null; $cols = 0; $rows = 0;
			}
			elseif (';' === $line[0]) { /* comment – ignored */ }
			elseif ('t' === $flag)
			{
				if (!empty($this->icalProcessing['capture']))
					$this->processIcal($line);

				if (preg_match('/^\*.*\*$/', $line))
				{
					$line = '*'.str_replace('	', '	*',
						substr($line, 1, strlen($line) - 2));
					$buffer['theads'][$rows] = true;
					// if ($line[0] != '	') $line = '*'.$line;
				}

				// $buffer[$rows] = explode('	', ltrim($line, '	'));
				$buffer[$rows] = explode('	', $line);
				if ($cols < count($buffer[$rows]))
					$cols = count($buffer[$rows]);
				++$rows;
			}
			else
			{
				// Define codes
				if (preg_match('~^#([a-zA-Z/][a-zA-Z0-9]*):#opencapture~i',
					$line, $matches))
				{
					// Najprv hľadám medzi preddefinovanými triedami
					$found = false;
					foreach ($this->classes as $key => $value)
					{
						if ($key == $matches[1])
						{
							$this->classes[$key] = $capturedContent;
							$found = true;
							break;
						}
					}

					if (!$found) foreach ($this->autoids as $key => $value)
					{
						if ($key.'autoid' == $matches[1] ||
							$key.'AutoID' == $matches[1])
						{
							if (empty($capturedContent))
								$this->autoids[$key] = null;
							else
							{
								// $this->autoids[$key] =
								// 	array($capturedContent, 0);
								$this->autoids[$key] = $capturedContent;
								if (!isSet($this->autoidcnts[
									$capturedContent])) $this->
										autoidcnts[$capturedContent] = 0;
							}
							$found = true;
							break;
						}
					}

					if (!$found) foreach ($this->ids as $key => $value)
					{
						if ($key.'id' == $matches[1] ||
							$key.'ID' == $matches[1])
						{
							$this->ids[$key] = $capturedContent;
							$found = true;
							break;
						}
					}

					// Keď nájdem, vyhlásim chybu
					if ($found ||
						preg_match('~^(error|graynotice|(small)?note)$~i',
						$matches[1]))
					{
						$html .= '<p class="error">'.RheiaMainClass::$text[
							'error-capture-not-started'].' '.RheiaMainClass::$text[
							'error-capture-reserved-identifier'].'</p>'.EOL;
					}
					elseif (preg_match(
						'~^(code|style|javascript|onshow|onexit)$~i',
						$matches[1]))
					{
						$captureBuffer[] = array($html, $matches[1]);
						$captureLiteral = true; $html = array();
					}
					elseif (null !== $tabs)
					{
						$html .= '<p class="error">'.RheiaMainClass::$text[
							'error-capture-not-started'].
							' '.RheiaMainClass::$text[
							'error-capture-tabs-open'].'</p>'.EOL;
					}
					elseif (null === $flag)
					{
						$captureBuffer[] = array($html, $matches[1]);
						$captureLiteral = false; $html = '';
					}
					else
					{
						$html .= '<p class="error">'.RheiaMainClass::$text[
							'error-capture-not-started'].
							' '.RheiaMainClass::$text[
							'error-capture-not-neutral-state'].'</p>'.EOL;
					}
					continue;
				}
				elseif (preg_match('~^#([a-zA-Z/][a-zA-Z0-9]*):(.*)~',
					$line, $matches))
				{
					$caseMatch = $matches[1];
					$noCaseMatch = strtolower($matches[1]);
					$capturedContent = $matches[2];

					// if ($noCaseMatch == 'comment') {} else
					if ($noCaseMatch == 'error')
					{
						$html .= '<p class="error">'.
							ltrim($capturedContent).'</p>'.EOL;
					}
					elseif ($noCaseMatch == 'graynotice')
					{
						$html .= '<p class="graynotice">'.
							ltrim($capturedContent).'</p>'.EOL;
					}
					elseif ($noCaseMatch == 'smallnote')
					{
						$html .= '<p class="note"><small><b>'.
							RheiaMainClass::$text['common-note'].':</b> '.
							ltrim($capturedContent).'</small></p>'.EOL;
					}
					elseif ($noCaseMatch == 'note')
					{
						$html .= '<p class="note"><b>'.
							RheiaMainClass::$text['common-note'].':</b> '.
							ltrim($capturedContent).'</p>'.EOL;
					}
					elseif ($noCaseMatch == 'code')
					{
						++$this->anonymousDefs;
						$this->rheiaDefs[$this->anonymousDefs] =
							$capturedContent;
						$html .= 'code('.$this->anonymousDefs.')'.EOL;
					}
					elseif ($noCaseMatch == 'style')
					{
						$this->rheiaDefs['style'] .=
							ltrim(RheiaMainClass::replaceObjects(
								$capturedContent)).EOL;
					}
					elseif ($noCaseMatch == 'javascript')
						$this->rheiaDefs['javaScript'] .=
							TAB.ltrim($capturedContent).EOL;
					elseif ($noCaseMatch == 'onshow')
						$this->rheiaDefs['onShow'] .=
							TAB.ltrim($capturedContent).EOL;
					elseif ($noCaseMatch == 'onexit')
						$this->rheiaDefs['onExit'] .=
							TAB.ltrim($capturedContent).EOL;
					else
					{
						// Najprv hľadám medzi preddefinovanými triedami
						$notFound = true;
						foreach ($this->classes as $key => $value)
						{
							if ($key == $caseMatch)
							{
								$this->classes[$key] = $capturedContent;
								$notFound = false;
								break;
							}
						}
						if ($notFound) foreach ($this->autoids as
							$key => $value)
						{
							if ($key.'autoid' == $caseMatch ||
								$key.'AutoID' == $caseMatch)
							{
								if (empty($capturedContent))
									$this->autoids[$key] = null;
								else
								{
									// $this->autoids[$key] =
									// 	array($capturedContent, 0);
									$this->autoids[$key] = $capturedContent;
									if (!isSet($this->autoidcnts[
										$capturedContent])) $this->
											autoidcnts[$capturedContent] = 0;
								}
								$notFound = false;
								break;
							}
						}
						if ($notFound) foreach ($this->ids as $key => $value)
						{
							if ($key.'id' == $caseMatch ||
								$key.'ID' == $caseMatch)
							{
								$this->ids[$key] = $capturedContent;
								$notFound = false;
								break;
							}
						}
						// Keď nenájdem, vložím to medzi definície
						if ($notFound)
							$this->rheiaDefs[$caseMatch] =
								$capturedContent;
					}

					// var_dump($matches);
				}
				elseif (false !== strpos($line, '	'))
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					if (preg_match('/^\*.*\*$/', $line))
					{
						$line = '*'.str_replace('	', '	*',
							substr($line, 1, strlen($line) - 2));
						$buffer['theads'][$rows] = true;
						// if ($line[0] != '	') $line = '*'.$line;
					}

					if (null !== $flag)
					{
						switch ($flag)
						{
						case 'p':
							if (!empty($this->classes['paragraph']))
							{
								$html .= '<p'.$this->spendID('paragraph').
									RheiaMainClass::getClassTag(
									$this->classes['paragraph']).
									$this->handleSpecialCodes($buffer).'>';
								$this->classes['paragraph'] = null;
							}
							else $html .= '<p'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
							$html .= $buffer.'</p>'.EOL;
							break;

						case 'd': case 'u':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;
							$this->classes['unorderedList'] = null;
							break;

						case 'o':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ol>'.EOL;
							$number = 0;
							$this->classes['orderedList'] = null;
							break;

						default:
							$html .= '<p'.$this->handleSpecialCodes($buffer).
								' class="error">';
							$html .= $buffer.'</p>'.EOL;
						}

						$buffer = null; $cols = 0; $rows = 0;
					}

					$flag = 't';
					// $buffer[$rows] = explode('	', ltrim($line, '	'));
					$buffer[$rows] = explode('	', $line);
					if ($cols < count($buffer[$rows]))
						$cols = count($buffer[$rows]);
					++$rows;
				}
				elseif (0 === strpos($line, '==='))
				{
					if (empty($buffer))
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph'], 'double-line').
								' />'.EOL;
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs'], 'double-line').
								' />'.EOL;
					}
					else
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<h1'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph']).
								$this->handleSpecialCodes($buffer).'>';
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<h1'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';

						$html .= $buffer.'</h1>'.EOL;
						if (empty($this->title))
							$this->title = $buffer;
					}

					$buffer = null; $flag = null;
				}
				elseif (0 === strpos($line, '---'))
				{
					if (empty($buffer))
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph']).' />'.EOL;
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).' />'.EOL;
					}
					else
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<h2'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph']).
								$this->handleSpecialCodes($buffer).'>';
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<h2'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';

						$html .= $buffer.'</h2>'.EOL;
					}

					$buffer = null; $flag = null;
				}
				elseif (0 === strpos($line, '___'))
				{
					if (empty($buffer))
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph'], 'gray-line').
								' />'.EOL;
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs'], 'gray-line').
								' />'.EOL;
					}
					else
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<h3'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph']).
								$this->handleSpecialCodes($buffer).'>';
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<h3'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';

						$html .= $buffer.'</h3>'.EOL;
					}

					$buffer = null; $flag = null;
				}
				elseif (0 === strpos($line, '...'))
				{
					if (empty($buffer))
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph'], 'dotted-line').
								' />'.EOL;
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<hr'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs'], 'dotted-line').
								' />'.EOL;
					}
					else
					{
						if (!empty($this->classes['paragraph']))
						{
							$html .= '<h4'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraph']).
								$this->handleSpecialCodes($buffer).'>';
							$this->classes['paragraph'] = null;
						}
						else
							$html .= '<h4'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
						$html .= $buffer.'</h4>'.EOL;
					}

					$buffer = null; $flag = null;
				}
				elseif (preg_match('/^- *\{.+\|down:/', $line) ||
					preg_match('/^- *\{.+\|plaindown:/', $line) ||
					preg_match('/^- *\{.+\|externdown:/', $line) ||
					preg_match('/^- *\{.+\|plainexterndown:/', $line))
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					if (null === $flag)
					{
						$html .= '<ul'.$this->spendID('unorderedList').
							RheiaMainClass::getClassTag(
							$this->classes['unorderedList'],
							'downloads-list').'>'.EOL;
						$flag = 'd';
					}
					else
					{
						switch ($flag)
						{
						case 'p':
							if (!empty($this->classes['paragraph']))
							{
								$html .= '<p'.$this->spendID('paragraph').
									RheiaMainClass::getClassTag(
									$this->classes['paragraph']).
									$this->handleSpecialCodes($buffer).'>';
								$this->classes['paragraph'] = null;
							}
							else $html .= '<p'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
							$html .= $buffer.'</p>'.EOL;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag(
								$this->classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						case 'u':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag($this->
								classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						case 'o':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ol>'.EOL;
							$number = 0;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag($this->
								classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						default:
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL;
						}
					}
					$buffer = ltrim(ltrim($line, '-'));
				}
				elseif (preg_match('/^- *\{down:/', $line) ||
					preg_match('/^- *\{plaindown:/', $line) ||
					preg_match('/^- *\{externdown:/', $line) ||
					preg_match('/^- *\{plainexterndown:/', $line))
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					if (null === $flag)
					{
						$html .= '<ul'.$this->spendID('unorderedList').
							RheiaMainClass::getClassTag($this->
							classes['unorderedList'], 'downloads-list').'>'.EOL;
						$flag = 'd';
					}
					else
					{
						switch ($flag)
						{
						case 'p':
							if (!empty($this->classes['paragraph']))
							{
								$html .= '<p'.$this->spendID('paragraph').
									RheiaMainClass::getClassTag(
									$this->classes['paragraph']).
									$this->handleSpecialCodes($buffer).'>';
								$this->classes['paragraph'] = null;
							}
							else $html .= '<p'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
							$html .= $buffer.'</p>'.EOL;

							$html .= '<ul'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						case 'u':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag($this->
								classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						case 'o':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ol>'.EOL;
							$number = 0;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag($this->
								classes['unorderedList'],
								'downloads-list').'>'.EOL;
							$flag = 'd';
							break;

						default:
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL;
						}
					}
					$buffer = ltrim(ltrim($line, '-'));
				}
				elseif ('-' === $line[0])
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					if (null === $flag)
					{
						$html .= '<ul'.$this->spendID('unorderedList').
							RheiaMainClass::getClassTag(
							$this->classes['unorderedList']).'>'.EOL;
						$flag = 'u';
					}
					else
					{
						switch ($flag)
						{
						case 'p':
							if (!empty($this->classes['paragraph']))
							{
								$html .= '<p'.$this->spendID('paragraph').
									RheiaMainClass::getClassTag(
									$this->classes['paragraph']).
									$this->handleSpecialCodes($buffer).'>';
								$this->classes['paragraph'] = null;
							}
							else $html .= '<p'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
							$html .= $buffer.'</p>'.EOL;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag(
								$this->classes['unorderedList']).'>'.EOL;
							$flag = 'u';
							break;

						case 'd':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag(
								$this->classes['unorderedList']).'>'.EOL;
							$flag = 'u';
							break;

						case 'o':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ol>'.EOL;
							$number = 0;

							$html .= '<ul'.$this->spendID('unorderedList').
								RheiaMainClass::getClassTag(
								$this->classes['unorderedList']).'>'.EOL;
							$flag = 'u';
							break;

						default:
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL;
						}
					}
					$buffer = ltrim(ltrim($line, '-'));
				}
				else if (preg_match('/^(#|[0-9]+)\./', $line, $matches))
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					if ('#' == $matches[1])
						$matches[1] = $number + 1;

					if (null === $flag)
					{
						$number = $matches[1];
						if (1 != $number)
							$html .= '<ol'.$this->spendID('orderedList').
								RheiaMainClass::getClassTag(
								$this->classes['orderedList']).
								' start="'.$number.'" style="counter-reset: '.
								'orderedList '.($number - 1).'">'.EOL;
						else
							$html .= '<ol'.$this->spendID('orderedList').
								RheiaMainClass::getClassTag(
								$this->classes['orderedList']).'>'.EOL;
						$flag = 'o';
					}
					else
					{
						switch ($flag)
						{
						case 'p':
							if (!empty($this->classes['paragraph']))
							{
								$html .= '<p'.$this->spendID('paragraph').
									RheiaMainClass::getClassTag(
									$this->classes['paragraph']).
									$this->handleSpecialCodes($buffer).'>';
								$this->classes['paragraph'] = null;
							}
							else $html .= '<p'.$this->spendID('paragraph').
								RheiaMainClass::getClassTag(
								$this->classes['paragraphs']).
								$this->handleSpecialCodes($buffer).'>';
							$html .= $buffer.'</p>'.EOL;

							$number = $matches[1];
							if (1 != $number)
								$html .= '<ol'.$this->spendID('orderedList').
									RheiaMainClass::getClassTag(
									$this->classes['orderedList']).
									' start="'.$number.'"'.
									' style="counter-reset: orderedList '.
									($number - 1).'">'.EOL;
							else
								$html .= '<ol'.$this->spendID('orderedList').
									RheiaMainClass::getClassTag(
									$this->classes['orderedList']).'>'.EOL;
							$flag = 'o';
							break;

						case 'd': case 'u':
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL.'</ul>'.EOL;

							$number = $matches[1];
							if (1 != $number)
								$html .= '<ol'.$this->spendID('orderedList').
									RheiaMainClass::getClassTag(
									$this->classes['orderedList']).
									' start="'.$number.'"'.
									' style="counter-reset: orderedList '.
									($number - 1).'">'.EOL;
							else
								$html .= '<ol'.$this->spendID('orderedList').
									RheiaMainClass::getClassTag(
									$this->classes['orderedList']).'>'.EOL;
							$flag = 'o';
							break;

						default:
							$html .= '<li'.$this->handleSpecialCodes(
								$buffer).$this->spendID('listItem').'>';
							$html .= $buffer.'</li>'.EOL;

							if (++$number != $matches[1])
							{
								$html .= '</ol>'.EOL;

								$number = $matches[1];
								if (1 != $number)
									$html .=
										'<ol'.$this->spendID('orderedList').
										RheiaMainClass::getClassTag(
										$this->classes['orderedList']).
										' start="'.$number.'" style="'.
										'counter-reset: orderedList '.
										($number - 1).'">'.EOL;
								else
									$html .=
										'<ol'.$this->spendID('orderedList').
										RheiaMainClass::getClassTag(
										$this->classes['orderedList']).'>'.EOL;
							}
						}
					}
					$buffer = preg_replace('/^[#0-9]+\. */', '', $line);
					// $buffer = ltrim(ltrim($line, '-'));
				}
				elseif (null === $buffer)
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					$flag = 'p';
					$buffer = $line;
				}
				else
				{
					if (!empty($this->icalProcessing['capture']))
						$this->processIcal($line);

					$buffer .= ' '.$line;
				}
			}
		}


		// Detekcia fatálnych chýb generátora (koniec)
		$fatalError = false;
		// } catch (Exception $e) {
		} finally {
			if ($fatalError)
			{
				echo '<p><b>Bola detegovaná fatálna chyba.</b><br />'.
					'Tento typ chyby nie je možné korigovať.<br />'.
					'Najčastejšou príčinou je vykonanie nepovolenej '.
					'vnorenej operácie počas (keď stroj RS Rheia nie '.
					'je v neutrálnom stave – „niekde chýba prázdny '.
					'riadok,“ ktorý slúži na ukončenie predchádzajúcej '.
					'vnorenej operácie; zamerajte sa na znaky # na '.
					'začiatkoch riadkov, ktoré slúžia na vykonávanie '.
					'vnorených operácií).</p>';
			}
		}


		// #Karta: — generovanie kariet začiatok

		// Generate tabs
		if (is_array($tabs))
		{
			$tabID = 0;
			$tabsButtons = '';
			$tabsContent = '';
			$tabsOptions = '';
			$subtabsCounts = null;
			$visibleTabs = 0;

			$this->tabsSearch = array();

			if (false !== $tabs['notab'])
			{
				$afterTabs = EOL.'<div id="notab">'.EOL.
					$tabs['notab'].EOL.'</div>'.EOL;
				$tabsOptions = '	defaultBookmark = null;'.EOL;
				$tabs['notab'] = '';
			}
			else $afterTabs = EOL.'<div id="notab"></div>'.EOL;

			foreach ($tabs as $tab)
			{
				if (is_array($tab))
				{
					if (true === $tab['name'])
					{
						$afterTabs .= $tab['content'].EOL;

						if (isSet($tab['subtabs']))
							foreach ($tab['subtabs'] as $subtab)
								$afterTabs .= $subtab['content'].EOL;

						if (isSet($tab['nosubtab']))
							$afterTabs .= $tab['nosubtab'].EOL;
					}
					else
					{
						if (isSet($tab['break']))
							$tabsButtons .= '</ul><ul '.
								'class="items break">'.EOL;

						if (!isSet($tab['hidden']))
							++$visibleTabs;

						if (isSet($tab['link']))
						{
							$tabsButtons .= '	<li'.(isSet($tab['hidden']) ?
								' style="display: none;"' : '').'>'.
								RheiaMainClass::makeExternalLink($tab['name'],
									$tab['link']).'</li>'.EOL;
						}
						else if (isSet($tab['redirect']))
						{
							$tabID2 = RheiaMainClass::findTab($tabs,
								$tab['redirect']);

							if (isSet($tabID2))
								$tabsButtons .=
									'	<li'.RheiaMainClass::getClassTag(
									isSet($tab['select']) ? 'selected' : '').
									(isSet($tab['hidden']) ?
										' style="display: none;"' : '').
									'><a href="javascript:navigateTab(\''.
									$tabID2.'\')">'.$tab['name'].'</a></li>'.EOL;
							else
								$tabsButtons .=
									'	<li'.RheiaMainClass::getClassTag(
									isSet($tab['select']) ? 'selected' : '').
									(isSet($tab['hidden']) ?
										' style="display: none;"' : '').
									'><a href="'.$tab['redirect'].'">'.
									$tab['name'].'</a></li>'.EOL;
						}
						else
						{
							++$tabID;

							$tabsButtons .= '	<li id="tab'.$tabID.'-item"'.
								(isSet($tab['hidden']) ?
									' style="display: none;"' : '').'>'.
								'<a href="javascript:navigateTab(\'tab'.
								$tabID.'\')"';
							if (isSet($tab['alias']))
								$tabsButtons .= ' name="'.$tab['alias'].'"';
							$tabsButtons .= '>'.$tab['name'].'</a></li>'.EOL;
							$tabsContent .= EOL.'<div id="tab'.$tabID.'">'.
								EOL.$tab['content'].EOL;

							if (isSet($tab['alias']))
							{
								$tabsOptions = '	tabAlias[\'tab'.$tabID.
									'\'] = \''.$tab['alias'].'\';'.EOL.
									$tabsOptions;
								$tabID2 = $tab['alias'];
							}
							else $tabID2 = 'karta-'.$tabID;

							if (isSet($tab['select']))
								$tabsOptions = '	tabSelect[\'tab'.
									$tabID. '\'] = true;'.EOL.$tabsOptions;

							$filtered = $tab['content'];
							$filtered = $this->replaceCodes($filtered);
							$filtered = RheiaMainClass::filterHTML(
								RheiaMainClass::filterObjects($filtered));

							$this->tabsSearch[$tabID2]['search'] =
								RheiaMainClass::transliterate($filtered);
							$this->tabsSearch[$tabID2]['name'] =
								$tab['name'];

							/*if (preg_match('/^(\X{100}\pL*)/u',
								$filtered, $matches))
							{
								if (preg_match('/<[^>]+$/', $matches[1]))
									$matches[1] = preg_replace('/<[^>]+$/',
										'', $matches[1]);
								$this->tabsSearch[$tabID2]
									['preview'] = $matches[1].'…';
							}
							else
								$this->tabsSearch[$tabID2]
									['preview'] = $filtered;*/
							$this->tabsSearch[$tabID2]['preview'] =
								RheiaMainClass::cropText($filtered);

							$subtabID = 0;

							if (isSet($tab['nosubtab']))
							{
								$afterSubtabs = EOL.'<div id="tab'.$tabID.
									'nosub">'.EOL.$tab['nosubtab'].EOL.
									'</div>'.EOL;
							}
							else $afterSubtabs = '';

							if (isSet($tab['subtabs']))
							{
								$subtabsButtons = '';
								$subtabsContent = '';
								$visibleSubtabs = 0;

								foreach ($tab['subtabs'] as $subtab)
								{
									if (true === $subtab['name'])
									{
										$afterSubtabs .= $subtab
											['content'].EOL;
									}
									else
									{
										if (!isSet($subtab['hidden']))
											++$visibleSubtabs;

										if (isSet($subtab['break']))
											$subtabsButtons .= '</ul><ul '.
												'class="items break">'.EOL;

										if (isSet($subtab['link']))
										{
											$subtabsButtons .= '	<li'.
												(isSet($subtab['hidden']) ?
													' style="display: none;"' :
													'').'>'.
												RheiaMainClass::makeExternalLink(
													$subtab['name'],
													$subtab['link']).
												'**</li>'.EOL;
										}
										else if (isSet($subtab['redirect']))
										{
											$tabID2 =
												RheiaMainClass::findTab($tabs,
												$subtab['redirect']);

											if (isSet($tabID2))
												$subtabsButtons .=
													'	<li'.
													RheiaMainClass::getClassTag(
													isSet($subtab['select']) ?
														'selected' : '').
													(isSet($subtab['hidden']) ?
														' style="display: none;"'
														: '').
													'><a href="javascript:'.
													'navigateTab(\''.$tabID2.
													'\')">'.$subtab['name'].
													'</a></li>'.EOL;
											else
												$subtabsButtons .=
													'	<li'.
													RheiaMainClass::getClassTag(
													isSet($subtab['select'])
													?
														'selected' : '').
													(isSet($subtab['hidden'])
														?
														' style="display: none;"'
														: '').'><a href="'.
													$subtab['redirect'].
													'">'.$subtab['name'].
													'</a></li>'.EOL;
										}
										else
										{
											++$subtabID;

											$subtabsButtons .=
												'	<li id="tab'.$tabID.'-'.
												$subtabID.'-item"'.
													(isSet($subtab['hidden'])
														?
														' style="display: none;"'
														: '').'><a href='.
												'"javascript:navigateTab('.
												'\'tab'.$tabID.'-'.$subtabID.
												'\')"';
											if (isSet($subtab['alias']))
												$subtabsButtons .= ' name="'.
													$subtab['alias'].'"';
											$subtabsButtons .=
												'>'.$subtab['name'].'</a>'.
												'</li>'.EOL;
											$subtabsContent .= EOL.
												'<div id="tab'.$tabID.'-'.
												$subtabID.'">'.EOL.$subtab
												['content'].EOL.'</div>'.EOL;

											if (isSet($subtab['alias']))
											{
												$tabsOptions = '	tabAlias['.
													'\'tab'.$tabID.'-'.
													$subtabID.'\'] = \''.
													$subtab['alias'].
													'\';'.EOL.$tabsOptions;
												$tabID2 = $subtab['alias'];
											}
											else $tabID2 = 'karta-'.
												$tabID.'-'.$subtabID;

											if (isSet($subtab['select']))
												$tabsOptions = '	tabSelect['.
													'\'tab'.$tabID.'-'.$subtabID.
													'\'] = true;'.EOL.
													$tabsOptions;

											$filtered = $subtab['content'];
											$filtered = $this->
												replaceCodes($filtered);
											$filtered = RheiaMainClass::
												filterHTML(RheiaMainClass::
													filterObjects($filtered));

											$this->tabsSearch[$tabID2]
												['search'] = RheiaMainClass::
												transliterate($filtered);
											$this->tabsSearch[$tabID2]['name'] =
												$subtab['name'];

											/*if (preg_match(
												'/^(\X{100}\pL*)/u',
												$filtered, $matches))
											{
												if (preg_match('/<[^>]+$/',
													$matches[1])) $matches[1] =
														preg_replace('/<[^>]+$/',
														'', $matches[1]);
												$this->tabsSearch[$tabID2]
													['preview'] =
														$matches[1].'…';
											}
											else
												$this->tabsSearch[$tabID2]
													['preview'] = $filtered;*/
											$this->tabsSearch[$tabID2]
												['preview'] = RheiaMainClass::
												cropText($filtered);
										}
									}
								}

								$tabsContent .= '<div class="tabs-menu"'.
									(0 == $visibleSubtabs ?
										' style="display: none;"' : '').'>'.
									'<ul class="items">'.EOL.$subtabsButtons.
									'</ul></div><div class="clear"></div>'.
									EOL.$subtabsContent.EOL;
							}

							if (!empty($afterSubtabs))
								$tabsContent .= $afterSubtabs;

							if (null === $subtabsCounts)
								$subtabsCounts = '['.$subtabID;
							else
								$subtabsCounts .= ', '.$subtabID;

							$tabsContent .= '</div>'.EOL;
						}
					}
				}
				else
				{
					$html .= $tab;
				}
			}

			if (null === $subtabsCounts)
				$subtabsCounts = 'null';
			else
				$subtabsCounts .= ']';

			$html .= '<div class="tabs-menu"'.(0 == $visibleTabs ?
				' style="display: none;"' : '').'><ul class="items">'.EOL.
				$tabsButtons.'</ul></div><div class="clear"></div>'.EOL.
				$tabsContent.EOL./*
				'<link href="design/tabs-style.css" rel="stylesheet" '.
					'type="text/css" />'.EOL.
				'<script src="design/tabs-script.js" type="text/'.
					'javascript" charset="utf-8"></script>'.EOL.
				'<script type="text/javascript"><!--'.EOL.$tabsOptions.
				'	initTabs('.$tabID.', '.$subtabsCounts.');'.EOL.
				'	// -->'.EOL.
				'</script>'.EOL.*/
				$afterTabs;
			$this->javaScript .= $tabsOptions;
			$this->onShow .= TAB.'initTabs('.$tabID.
				', '.$subtabsCounts.');'.EOL;
			$this->linkStyles['tabs-style'] = true;
			$this->linkJavaScripts['tabs-script'] = true;
		}
		else $this->tabsSearch = null;

		// #Karta: — generovanie kariet koniec


		// Substitute custom automatic links to articles
		foreach ($this->articlesRefs as $key => $val)
		{
			$html = str_replace('article('.$key.')',
				$this->makeArticleLink($val), $html);
		}


		// Substitute user defined defintions
		$html = $this->replaceCodes($html, true, true, true);
		/*
		for ($i = 0; $i < 10; ++$i)
		{
			foreach ($this->rheiaDefs as $key => $val)
			{
				if ($key === 'style')
				{
					if (null != $val)
						// $html = '<style>'.$val.'</style>'.EOL.$html;
						// $html .= EOL.'<style>'.$val.'</style>';
						$this->styleSheed .= $val;
				}
				elseif ($key === 'javaScript')
				{
					if (null != $val)
						$this->javaScript .= TAB.$val.EOL;
				}
				elseif ($key === 'onShow')
				{
					if (null != $val)
						$this->onShow .= TAB.$val.EOL;
				}
				elseif ($key === 'onExit')
				{
					if (null != $val)
						$this->onExit .= TAB.$val.EOL;
				}
				else
				{
					$html = str_replace('code('.$key.')', $val, $html);
					if (!empty($this->title))
						$this->title = str_replace
							('code('.$key.')', $val, $this->title);
				}
			}
			if (false === strpos($html, 'code(')) break;
		}
		*/

		$html = str_replace('<p></p>', '', str_replace('<p></div></p>',
			'</div>', str_replace('<br /></', '</', $html)));
		$html = preg_replace('~<p><div([^>]*)></p>~', '<div$1>', $html);

		if (!empty($this->title))
			$this->title = RheiaMainClass::filterHTML($this->title);

		return $html;
	}


	public function loadHTML()
	{
		$source = RheiaMainClass::$contentPath.$this->baseName.'.txh';
		$gone = RheiaMainClass::$contentPath.$this->baseName.'.txh-gone';
		$redirect = RheiaMainClass::$contentPath.
			$this->baseName.'.txh-redirect';
		$parsed = RheiaMainClass::$parsedPath.$this->baseName.'-h.php';
		// $stamp = RheiaMainClass::$parsedPath.$this->baseName.'-h.tst';
		RheiaMainClass::$lastProcessed = $source;

		if (file_exists($gone))
		{
			include '410.php';
			return;
		}

		if (file_exists($redirect) && is_file($redirect))
		{
			global $newName;
			$newName = file_get_contents($redirect);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			// echo '<p>Presmerovanie: '.$newName.'</p>';
			echo EOL.'<!-- Požadovaný obsah bol presunutý. ('.
				$this->baseName.': '.$newName.') -->'.EOL;
			return;
		}

		$mustRevalidate = true; $parsedDate = null;
		$this->sourceDate = filemtime($source);
		if (!file_exists($source))
			RheiaMainClass::logError('File “'.$source.'” does not exist.');

		/*
		if (!file_exists($stamp) || RheiaMainClass::$currentTime >=
			(int)file_get_contents($stamp))
		{
			$checkExtern = true;
			file_put_contents($stamp, check_extern_interval +
				RheiaMainClass::$currentTime, LOCK_EX);
		}
		else $checkExtern = false;
		*/

		if (file_exists($parsed) &&
			($this->sourceDate <= ($parsedDate = filemtime($parsed))))
		{
			include './'.$parsed;
			$mustRevalidate = RheiaMainClass::$objectsNewestDate > $parsedDate;

			if (isSet($this->html['revalidationDate']) &&
				$this->html['revalidationDate'] < RheiaMainClass::$currentTime)
			{
				$mustRevalidate = true;
			}
			else
			{
				if (!$mustRevalidate && isSet($this->html['files']))
					foreach ($this->html['files'] as $file)
					{
						$exists = false; $fileSize = null; $fileDate = null;
						RheiaMainClass::getFileInfo($file['originalName'],
							$exists, $fileSize, $fileDate);

						if ($file['exists'] != $exists ||
							$file['fileSize'] != $fileSize ||
							$file['fileDate'] != $fileDate)
						{
							$mustRevalidate = true;
							// echo 'mustRevalidate (HTML file): '.$file['originalName'].EOL;
							break;
						}
					}

				if (!$mustRevalidate && RheiaMainClass::$checkExtern &&
					isSet($this->html['externFiles']))
					foreach ($this->html['externFiles'] as $file)
					{
						$exists = false; $fileSize = null; $fileDate = null;
						RheiaMainClass::getExternFileInfo($file['url'],
							$exists, $fileSize, $fileDate);

						if ($file['exists'] != $exists ||
							$file['fileSize'] != $fileSize ||
							$file['fileDate'] != $fileDate)
						{
							$mustRevalidate = true;
							// echo 'mustRevalidate (HTML external file): '.$file['url'].EOL;
							break;
						}
					}

				if (!$mustRevalidate && isSet($this->html['templates']))
					foreach ($this->html['templates'][1] as $template)
					{
						if (file_exists($template) &&
							filemtime($template) > $this->html['templates'][0])
						{
							// echo 'mustRevalidate (HTML template): '.$template.EOL;
							$mustRevalidate = true;
							break;
						}
					}
			}
		}

		if ($mustRevalidate)
		{
			if (!file_exists($source))
			{
				include '404.php';
				echo EOL.'<!-- Zdrojový súbor na generovanie HTML obsahu '.
					'nebol nájdený. ('.$this->baseName.', '.
					RheiaMainClass::$contentPath.') -->'.EOL;
				// RheiaMainClass::logError('File “'.$source.'” does not exist.');
				return;
			}

			// Load contents, generate HTML, filter text, and save it all
			$this->html = array();
			$this->html['content'] = $this->generateHTML(
				$this->replaceGlobals(
					preg_replace_callback(RheiaMainClass::$templateRegex,
						array(&$this, 'loadTemplates'),
					preg_replace_callback(RheiaMainClass::$expandRegex,
						array('RheiaMainClass', 'expandArray'),
					preg_replace(RheiaMainClass::$templateInCommentRegex, '',
						file_get_contents($source))))));
			$this->html['style'] = $this->styleSheed;
			$this->html['javaScript'] = $this->javaScript;

			if (!empty($this->autoidcnts))
				$this->html['javaScript'] =
					$this->autoIDCntsToJavaScript().
					$this->html['javaScript'];

			$this->html['onShow'] = $this->onShow;
			$this->html['onExit'] = $this->onExit;
			$this->html['linkStyles'] = $this->linkStyles;
			$this->html['linkJavaScripts'] = $this->linkJavaScripts;
			$this->html['title'] = $this->title;
			$this->title = RheiaMainClass::filterHTML(
				RheiaMainClass::replaceObjects($this->html['title']));
			$this->html['revalidationDate'] =
				RheiaMainClass::$revalidationDate;

			if (!empty(RheiaMainClass::$usedTemplates))
				$this->html['templates'] = array(
					RheiaMainClass::$newestTemplate,
						RheiaMainClass::$usedTemplates);

			if (isSet($this->files))
				$this->html['files'] = $this->files;

			if (isSet($this->externFiles))
				$this->html['externFiles'] = $this->externFiles;

			$filtered = RheiaMainClass::filterHTML(
				RheiaMainClass::filterObjects($this->html['content']));

			if (!empty(RheiaMainClass::$filteredObjects))
				$this->html['searchInObjects'] =
					RheiaMainClass::implodeFilteredObjects();

			if (is_array($this->tabsSearch))
				$this->html['searchInTabs'] = $this->tabsSearch;

			$this->html['search'] = RheiaMainClass::transliterate($filtered);

			// Create iCal data section
			if (!empty($this->icalData))
			{
				$this->finalizeIcal();
				$this->html['ical'] = $this->icalData;
				$this->icalData = null;
			}

			/*if (preg_match('/^(\X{100}\pL*)/u', $filtered, $matches))
			{
				if (preg_match('/<[^>]+$/', $matches[1]))
					$matches[1] = preg_replace('/<[^>]+$/', '', $matches[1]);
				$this->html['preview'] = $matches[1].'…';
			}
			else
				$this->html['preview'] = $filtered;*/
			$this->html['preview'] = RheiaMainClass::cropText($filtered);


			$content = '<'.'?php'.EOL2;
			$content .= '$this->html = '.var_export($this->html, true);
			$content .= ';'.EOL2.'?'.'>';

			file_put_contents($parsed, $content, LOCK_EX);
			// if (!$checkExtern) file_put_contents($stamp,
			// 	check_extern_interval + RheiaMainClass::$currentTime, LOCK_EX);
		}
	}


	public function showHTML()
	{
		global $style, $javaScript1, $javaScript2, $javaScript3,
			$linkStyles, $linkJavaScripts;

		if (isSet($this->html) &&
			isSet($this->html['content']))
			echo RheiaMainClass::replaceObjects($this->html['content']);
		if (!empty($this->html['style']))
			$style .= $this->html['style'];
		if (!empty($this->html['javaScript']))
			$javaScript1 .= $this->html['javaScript'];
		if (!empty($this->html['onShow']))
			$javaScript2 .= $this->html['onShow'];
		if (!empty($this->html['onExit']))
			$javaScript3 .= $this->html['onExit'];
		if (!empty($this->html['linkStyles']))
		{
			$linkStyles = array_merge($linkStyles,
				$this->html['linkStyles']);
			foreach ($this->html['linkStyles'] as $key => $val)
				if ($val && function_exists('loadStyleVersion'))
					loadStyleVersion($key);
		}
		if (!empty($this->html['linkJavaScripts']))
		{
			$linkJavaScripts = array_merge($linkJavaScripts,
				$this->html['linkJavaScripts']);
			foreach ($this->html['linkJavaScripts'] as $key => $val)
				if ($val && function_exists('loadScriptVersion'))
					loadScriptVersion($key);
		}
		if (empty($this->title))
			$this->title = RheiaMainClass::filterHTML(
				RheiaMainClass::replaceObjects($this->html['title']));
	}


	private function deployStaticHTML(&$staticHTML)
	{
		if (isSet($staticHTML))
		{
			$staticHTML = array('staticHTML' => $this->
				generateHTML($staticHTML), 'style' => $this->styleSheed,
				'javaScript' => $this->javaScript, 'onShow' => $this->onShow,
				'onExit' => $this->onExit, 'linkStyles' => $this->linkStyles,
				'linkJavaScripts' => $this->linkJavaScripts);
			if (isSet($this->ifListEmpty))
				$staticHTML['ifListEmpty'] = $this->ifListEmpty;
			if (isSet($this->files))
				$staticHTML['files'] = $this->files;
			if (isSet($this->externFiles))
				$staticHTML['externFiles'] =
					$this->externFiles;
			$staticHTML['search'] = RheiaMainClass::transliterate(
				RheiaMainClass::filterHTML(RheiaMainClass::filterObjects(
					$staticHTML['staticHTML'])));

			// Create iCal data section
			if (!empty($this->icalData))
			{
				$this->finalizeIcal();
				$staticHTML['ical'] = $this->icalData;
				$this->icalData = null;
			}

			if (is_array($this->tabsSearch))
				$staticHTML['searchInTabs'] =
					$this->tabsSearch;
			if (!empty(RheiaMainClass::$filteredObjects))
				$staticHTML['searchInObjects'] =
					RheiaMainClass::implodeFilteredObjects();
			$this->articles[] = $staticHTML;
			$staticHTML = null;
			$this->ifListEmpty = null;
		}
	}

	public function loadArticles($dump = false)
	{
		$source = RheiaMainClass::$contentPath.$this->baseName.'.txa';
		$gone = RheiaMainClass::$contentPath.$this->baseName.'.txa-gone';
		$redirect = RheiaMainClass::$contentPath.
			$this->baseName.'.txa-redirect';
		$parsed = RheiaMainClass::$parsedPath.$this->baseName.'-a.php';
		// $stamp = RheiaMainClass::$parsedPath.$this->baseName.'-a.tst';
		RheiaMainClass::$lastProcessed = $source;

		if (file_exists($gone))
		{
			include '410.php';
			return;
		}

		if (file_exists($redirect) && is_file($redirect))
		{
			global $newName;
			$newName = file_get_contents($redirect);
			header('Location: '.$newName);
			include '301.php'; // obsahuje header 301
			// echo '<p>Presmerovanie: '.$newName.'</p>';
			echo EOL.'<!-- Požadovaný obsah bol presunutý. ('.
				$this->baseName.': '.$newName.') -->'.EOL;
			return;
		}

		$mustRevalidate = true; $parsedDate = null;
		$this->sourceDate = filemtime($source);
		if (!file_exists($source))
			RheiaMainClass::logError('File “'.$source.'” does not exist.');

		/*
		if (!file_exists($stamp) || RheiaMainClass::$currentTime >=
			(int)file_get_contents($stamp))
		{
			$checkExtern = true;
			file_put_contents($stamp, check_extern_interval +
				RheiaMainClass::$currentTime, LOCK_EX);
		}
		else $checkExtern = false;
		*/

		if (file_exists($parsed) &&
			($this->sourceDate <= ($parsedDate = filemtime($parsed))))
		{
			include './'.$parsed;
			$mustRevalidate = RheiaMainClass::$objectsNewestDate > $parsedDate;

			if (isSet($this->articles['revalidationDate']) &&
				$this->articles['revalidationDate'] < RheiaMainClass::$currentTime)
			{
				$mustRevalidate = true;
			}
			else
			{
				foreach ($this->articles as $link => $article)
				{
					if ('title' === $link ||
						'templates' === $link ||
						'warnings' === $link ||
						'listEmptyMessage' === $link ||
						'revalidationDate' === $link)
						continue;

					if (!$mustRevalidate && isSet($article['files']))
						foreach ($article['files'] as $file)
						{
							$exists = false; $fileSize = null;
							$fileDate = null;
							RheiaMainClass::getFileInfo($file['originalName'],
								$exists, $fileSize, $fileDate);

							if ($file['exists'] != $exists ||
								$file['fileSize'] != $fileSize ||
								$file['fileDate'] != $fileDate)
							{
								// echo 'mustRevalidate (article list file): '.$file['originalName'].EOL;
								$mustRevalidate = true;
								break;
							}
						}

					if (!$mustRevalidate && RheiaMainClass::$checkExtern &&
						isSet($article['externFiles']))
						foreach ($article['externFiles'] as $file)
						{
							$exists = false; $fileSize = null;
							$fileDate = null;
							RheiaMainClass::getExternFileInfo($file['url'],
								$exists, $fileSize, $fileDate);

							if ($file['exists'] != $exists ||
								$file['fileSize'] != $fileSize ||
								$file['fileDate'] != $fileDate)
							{
								// echo 'mustRevalidate (article list external file): '.$file['url'].EOL;
								$mustRevalidate = true;
								break;
							}
						}

					if ($mustRevalidate) break;
				}

				if (!$mustRevalidate && isSet($this->articles['templates']))
					foreach ($this->articles['templates'][1] as $template)
					{
						if (file_exists($template) && filemtime($template) >
							$this->articles['templates'][0])
						{
							// echo 'mustRevalidate (article template): '.$template.EOL;
							$mustRevalidate = true;
							break;
						}
					}
			}
		}

		if ($mustRevalidate || $dump)
		{
			if (!file_exists($source))
			{
				include '404.php';
				echo EOL.'<!-- Zdrojový súbor na generovanie štruktúry '.
					'článkov nebol nájdený. ('.$this->baseName.', '.
					RheiaMainClass::$contentPath.') -->'.EOL;
				// RheiaMainClass::logError('File “'.$source.'” does not exist.');
				return;
			}

			// Load contents and split it to lines
			$lines = $this->replaceGlobals(
				preg_replace_callback(RheiaMainClass::$templateRegex,
					array(&$this, 'loadTemplates'),
				preg_replace_callback(RheiaMainClass::$expandRegex,
					array('RheiaMainClass', 'expandArray'),
				preg_replace(RheiaMainClass::$templateInCommentRegex, '',
					file_get_contents($source)))));
			$lines = explode('<br />', nl2br($lines));

			// Trim whitespace
			foreach ($lines as $i => $line)
				$lines[$i] = preg_replace('/[ \r\n]+/', ' ',
					trim($line, ' '.EOL));

			// Initialize variables
			$this->articles = array();
			if (!empty(RheiaMainClass::$usedTemplates))
				$this->articles['templates'] = array(RheiaMainClass::
					$newestTemplate, RheiaMainClass::$usedTemplates);
			$staticHTML = null;
			$this->ifListEmpty = null;
			$this->listEmptyMessage = null;

			$lines[] = null; $matches = null;
			$article = null; $link = null; $empty = true;
			$defaultHeader = null; $defaultFooter = null;
			$defaultOptions = null; $defaultRoot = null;

			ob_start();

			echo '<pre>Current time: '.RheiaMainClass::$currentTime.' ('.date(
				datetime_format, RheiaMainClass::$currentTime).')'.EOL2;

			// Parse articles
			foreach ($lines as $line)
			{
				// echo '> '.htmlspecialchars($line).EOL;

				if (null == $article)
				{
					$article = array('date' => null, 'updated' => null,
						'expires' => null, 'author' => null, 'publisher' =>
						null, 'note' => null, 'title' => null, 'titleCodes' =>
						null, 'caption' => null, 'captionCodes' => null,
						'root' => null, 'parent' => null, 'source' => null,
						'link' => null, 'targetBlank' => null, 'linkText' =>
						null, 'ID' => null, 'abstract' => null, 'lang' => null,
						'options' => array(
							'článok:odstránený' => false,
							'článok:viacAutorov' => false,
							'článok:viacZverejňovateľov' => false,
							'zoznam:skryAutora' => false,
							'zoznam:skryDátum' => false,
							'zoznam:skryPoznámku' => false,
							'zoznam:skryNadpis' => false,
							'zoznam:nahraďZlomyNadpisu' => false,
							'zoznam:skryUkážku' => false,
							'zoznam:skryČlánok' => false,
							'zoznam:zjednodušZobrazenie' => false,
							'zoznam:akoPriečinok' => false,
							'zoznam:dôležité' => false,
							'zoznam:parte' => false,
							'zoznam:pripnuté' => false,
							'zoznam:vypniOtvorenie' => false,
							'zoznam:ponechajNeplatné' => false,
							'zoznam:zobrazVopred' => false,
							'odkaz:bezDátumu' => false,
							'odkaz:bezNadpisu' => false,
							'ukážka:abstrakt' => false,
							'ukážka:obsah' => false,
							'ukážka:prílohy' => false,
							'detail:skryAutora' => false,
							'detail:skryDátum' => false,
							'detail:skryPoznámku' => false,
							'detail:skryNadpis' => false,
							'detail:nahraďZlomyNadpisu' => false,
							'detail:skryAbstrakt' => false,
							'detail:skryObsah' => false,
							'detail:skryOddeľovačPríloh' => false,
							'detail:skryVarovanieNeplatnosti' => false,
							'detail:dôležité' => false,
							'detail:parte' => false,
							'detail:pripnuté' => false,
							), 'content' => null,
						'header' => $defaultHeader,
						'footer' => $defaultFooter,
						'search' => null, 'preview' => null,
						'previewCode' => null);

					if (is_array($defaultOptions))
					{
						foreach ($defaultOptions as $option)
							if (isSet($article['options'][$option]))
								$article['options'][$option] = true;
					}

					if (is_array($defaultRoot))
						$article['root'] = $defaultRoot;
				}

				if (preg_match('/^<-- *(end|koniec) *$/i', $line))
					{ $line = null; } elseif (null === $line) {}
				elseif (null !== $article['content'])
				{
					$article['content'] .= $line.EOL;
				}
				elseif (empty($line))
				{
					if (!empty($staticHTML))
						$staticHTML .= EOL;
				}
				elseif (';' === $line[0]) { /* comment – ignored */ }
				elseif (preg_match('/^Publikuj! *$/i', $line))
				{
					$this->deployStaticHTML($staticHTML);
					$line = null;
				}
				elseif (preg_match('/^Dátum:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['date']))
						$article['warnings'][] = 'Date already defined';
					$article['date'] = RheiaMainClass::mktimestamp($matches[1]);
					$empty = false;

					echo 'Date timestamp: '.$article['date'].' ('.date(
						datetime_format, $article['date']).')'.EOL;
				}
				elseif (preg_match('/^Aktualizované:\s*(.+)$/ui',
					$line, $matches))
				{
					if (isSet($article['updated']))
						$article['warnings'][] = 'Update already defined';
					$article['updated'] =
						RheiaMainClass::mktimestamp($matches[1]);
					// $empty = false;

					echo 'Update timestamp: '.$article['updated'].' ('.date(
						datetime_format, $article['updated']).')'.EOL;
				}
				elseif (preg_match('/^Platnosť:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['expires']))
						$article['warnings'][] =
							'Expiration already defined';
					$article['expires'] =
						RheiaMainClass::mktimestamp($matches[1]);
					// $empty = false;

					echo 'Expiration timestamp: '.$article['expires'].
						' ('.date(datetime_format, $article['expires']).
						')'.EOL;
				}
				elseif (preg_match('/^Autor:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['author']))
						$article['warnings'][] = 'Author has been '.
							'defined before';
					$article['author'] = htmlspecialchars($matches[1]);
					// $empty = false;

					echo 'Author name: '.$article['author'].EOL;
				}
				elseif (preg_match('/^Zverejnil:\s*(.+)$/ui',
					$line, $matches))
				{
					if (isSet($article['publisher']))
						$article['warnings'][] = 'Publisher has been '.
							'defined before';
					$article['publisher'] = htmlspecialchars($matches[1]);
					// $empty = false;

					echo 'Publisher: '.$article['publisher'].EOL;
				}
				elseif (preg_match('/^Poznámka:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['note']))
						$article['warnings'][] = 'Note has been '.
							'defined before';
					$article['note'] = htmlspecialchars($matches[1]);
					// $empty = false;

					echo 'Article note: '.$article['note'].EOL;
				}
				elseif (preg_match('/^Nadpis:\s*(.+)$/ui', $line, $matches) ||
					preg_match('/^Názov:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['title']))
						$article['warnings'][] = 'Duplicate title';
					$article['title'] = $matches[1];
					$article['titleCodes'] =
						$this->handleSpecialCodes($article['title']);
					// $article['title'] = htmlspecialchars(
					//	str_replace(' ', ' ', $matches[1]));
					$empty = false;

					echo 'Title: '.$article['title'].EOL;
				}
				elseif (preg_match('/^Titul:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['caption']))
						$article['warnings'][] = 'Duplicate caption';
					$article['caption'] = $matches[1];
					$article['captionCodes'] =
						$this->handleSpecialCodes($article['caption']);

					echo 'Caption: '.$article['caption'].EOL;
				}

				/* K hlavičke a päte – začiatok. */
				elseif (preg_match('/^Hlavička:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['header']))
						$article['warnings'][] = 'Duplicate header';

					$article['header'] = $matches[1];
					$empty = false;

					echo 'Header: '.$article['header'][1].EOL;
				}
				elseif (preg_match('/^Päta:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['footer']))
						$article['warnings'][] = 'Duplicate footer';

					$article['footer'] = $matches[1];
					$empty = false;

					echo 'Footer: '.$article['footer'][1].EOL;
				}
				elseif (preg_match('/^Predvolená hlavička:\s*(.+)$/ui',
					$line, $matches))
				{
					$article['header'] = $defaultHeader = $matches[1];
					$empty = false;

					echo 'Default header/Header: '.$defaultHeader.EOL.
						': '.$article['header'][1].EOL;
				}
				elseif (preg_match('/^Predvolená päta:\s*(.+)$/ui',
					$line, $matches))
				{
					$article['footer'] = $defaultFooter = $matches[1];
					$empty = false;

					echo 'Default footer/Footer: '.$defaultFooter.EOL.
						': '.$article['footer'][1].EOL;
				}
				elseif (preg_match('/^Vyčisti hlavičku! *$/ui',
					$line, $matches))
				{
					$article['header'] = $defaultHeader = null;

					echo 'Default header and header have been cleared.'.EOL;
				}
				elseif (preg_match('/^Vyčisti pätu! *$/ui',
					$line, $matches))
				{
					$article['footer'] = $defaultFooter = null;

					echo 'Default footer and footer have been cleared.'.EOL;
				}
				/* K hlavičke a päte – koniec. */

				elseif (preg_match('/^Koreň:\s*([^:]+):\s*(.+)$/ui',
					$line, $matches))
				{
					// Root is another part of the web – article may have
					// more than one “root”.
					if (empty($article['root'])) $article['root'] = array();
					$article['root'][] = array($matches[1], $matches[2]);

					echo 'Root[]: '.$matches[1].': '.$matches[2].EOL;
				}
				elseif (preg_match(
					'/^Predvolený koreň:\s*([^:]+):\s*(.+)$/ui', $line,
					$matches))
				{
					// Root is … (see above)
					if (empty($defaultRoot)) $defaultRoot = array();
					$defaultRoot[] = array($matches[1], $matches[2]);

					if (empty($article['root'])) $article['root'] = array();
					$article['root'][] = array($matches[1], $matches[2]);

					echo 'Default root/Root[]: '.$matches[1].
						': '.$matches[2].EOL;
				}
				elseif (preg_match('/^Vyčisti koreň! *$/ui', $line,
					$matches))
				{
					$defaultRoot = $article['root'] = null;

					echo 'Default root and root have been cleared.'.EOL;
				}
				elseif (preg_match('/^Rodič:\s*(.+)$/ui', $line, $matches))
				{
					// Parent is specified by ID which points to parent
					// article… (Parent may be only one.)
					if (isSet($article['parent']))
						$article['warnings'][] = 'Parent already defined';
					$article['parent'] = htmlspecialchars(
						str_replace(' ', ' ', $matches[1]));

					echo 'Parent: '.$article['parent'].EOL;
				}
				elseif (preg_match(
					'#^Presmerovanie:\s*(https?):/{0,2}(.+)$#i',
					$line, $matches))
				{
					if (isSet($article['link']))
						$article['warnings'][] = 'Link already defined';

					$article['link'] = $matches[1].'://'.$matches[2];
					$article['targetBlank'] = true;

					echo 'External link: '.$article['link'].EOL;
				}
				elseif (preg_match('/^(Zdroj|Presmerovanie):\s*(.+)$/i',
					$line, $matches))
				{
					$matches[1] = strtolower($matches[1]);

					if ('presmerovanie' === $matches[1])
					{
						if (isSet($article['link']))
							$article['warnings'][] = 'Link already defined';

						$article['link'] = $matches[2];

						echo 'Link: '.$article['link'].EOL;
					}

					if (isSet($article['source']))
						$article['warnings'][] = 'Source already defined';

					$params = preg_split('#[\?&]#', trim(str_replace(' ',
						' ', $matches[2]), '/ '), -1, PREG_SPLIT_NO_EMPTY);

					if (isSet($params[2]))
					{
						$article['source'] = array($params[1], $params[2]);
						echo 'Source: '.$article['source'][0].' » '.
							$article['source'][1].EOL;
						$empty = false; $line = null;
					}
					elseif (isSet($params[1]))
					{
						$article['source'] = array($params[0], $params[1]);
						echo 'Source: '.$article['source'][0].' » '.
							$article['source'][1].EOL;
						$empty = false; $line = null;
					}
					else
						$article['warnings'][] = 'Invalid source format';

					if (null === $line)
						$this->deployStaticHTML($staticHTML);
				}
				elseif (preg_match('/^Identifikátor:\s*(.+)$/ui',
					$line, $matches))
				{
					if (isSet($article['ID']))
						$article['warnings'][] = 'Duplicate ID';
					$article['ID'] = htmlspecialchars(
						str_replace(' ', ' ', $matches[1]));
					$empty = false;

					echo 'Identifier: '.$article['ID'].EOL;
				}
				elseif (preg_match('/^Abstrakt:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['abstract']))
						$article['warnings'][] = 'Abstract already defined';
					$article['abstract'] = htmlspecialchars(
						// str_replace(' ', ' ',
							$matches[1]);
					$empty = false;

					echo 'Abstract: '.$article['abstract'].EOL;
				}
				elseif (preg_match('/^Kód\s+ukážky:\s*(.+)$/ui',
					$line, $matches))
				{
					if (isSet($article['previewCode']))
						$article['warnings'][] = 'Preview code already defined';
					$article['previewCode'] = $matches[1];
					$empty = false;

					echo 'Preview code: '.$article['previewCode'].EOL;
				}
				elseif (preg_match('/^Odkaz:\s*(.+)$/i', $line, $matches))
				{
					if (isSet($article['link']))
						$article['warnings'][] = 'Link already defined';

					$article['link'] = $matches[1];

					echo 'Link: '.$article['link'].EOL;
				}
				elseif (preg_match('/^Text odkazu:\s*(.+)$/i', $line,
					$matches))
				{
					if (isSet($article['linkText']))
						$article['warnings'][] = 'Link already defined';

					$article['linkText'] = htmlspecialchars(
						// str_replace(' ', ' ',
							$matches[1]);

					echo 'Link text: '.$article['linkText'].EOL;
				}
				elseif (preg_match('/^Jazyk:\s*([^:]+):\s*(.+)$/i',
					$line, $matches))
				{
					if (empty($article['lang'])) $article['lang'] = array();
					if (isSet($article['lang'][$matches[1]]))
						$article['warnings'][] = 'Language '.$matches[1].
							' already defined';
					$article['lang'][$matches[1]] = $matches[2];

					echo 'Lang['.$matches[1].']: '.$matches[2].EOL;
				}
				elseif (preg_match('/^Príloha:\s*"([^"]+)"\s*"?([^"]+)$/ui',
					$line, $matches))
				{
					if (strpos($matches[2], '"'))
					{
						$matches[2] = rtrim($matches[2], ' ');
						$matches[2] = rtrim($matches[2], '"');
					}

					$article['attachment'][] = $matches[2];
					end($article['attachment']);
					$article['description'][key($article['attachment'])] =
						htmlspecialchars(// str_replace(' ', ' ',
							trim($matches[1]));

					$empty = false;

					echo 'Attachment: '.$matches[1].EOL;
					echo 'Description: '.$matches[2].EOL;
				}
				elseif (preg_match('/^Príloha:\s*(.+)$/ui', $line, $matches))
				{
					$article['attachment'][] = $matches[1];
					$empty = false;

					echo 'Attachment: '.$matches[1].EOL;
				}
				elseif (preg_match('/^Opis:\s*(.+)$/ui', $line, $matches))
				{
					if (isSet($article['attachment']) &&
						is_array($article['attachment']))
					{
						end($article['attachment']);
						$article['description']
							[key($article['attachment'])] =
							htmlspecialchars(// str_replace(' ', ' ',
								$matches[1]);

						echo 'Description: '.$matches[1].EOL;
					}
					else
					{
						echo 'Description (orphaned): '.$matches[1].EOL;
						$article['warnings'][] = 'Orphaned description: '.
							$matches[1].EOL;
					}
				}
				elseif (preg_match('/^Úroveň zoznamu:\s*(.+)$/ui',
					$line, $matches))
				{
					$article['listLevel'] = $matches[1];
					echo 'List level: '.$article['listLevel'].EOL;
				}
				elseif (preg_match('/^Možnosti:\s*(.+)$/ui',
					$line, $matches))
				{
					$options = preg_split('/[, ]+/', $matches[1], -1,
						PREG_SPLIT_NO_EMPTY);

					foreach ($options as $option)
					{
						if (!empty($option) && '-' === $option[0])
						{
							$option = ltrim($option, '-+');

							if (isSet($article['options'][$option]))
								$article['options'][$option] = false;
							else
								$article['warnings'][] = 'Unknown option: '.
									$option;
						}
						else
						{
							$option = ltrim($option, '+-');

							if (isSet($article['options'][$option]))
								$article['options'][$option] = true;
							else
								$article['warnings'][] = 'Unknown option: '.
									$option;
						}
					}

					echo 'Options: ';
					foreach ($article['options'] as $option => $value)
						echo $option.'('.($value ? 'true' : 'false').') ';
					echo EOL;
				}
				elseif (preg_match('/^Predvolené možnosti:\s*(.+)$/ui',
					$line, $matches))
				{
					$options = preg_split('/[, ]+/', $matches[1], -1,
						PREG_SPLIT_NO_EMPTY);
					if (null === $defaultOptions) $defaultOptions = array();

					foreach ($options as $option)
					{
						if (!empty($option) && '-' === $option[0])
						{
							$option = ltrim($option, '-+');

							if (($key = array_search($option,
								$defaultOptions)) !== false)
								unset($defaultOptions[$key]);

							if (isSet($article['options'][$option]))
								$article['options'][$option] = false;
						}
						else
						{
							$option = ltrim($option, '+-');

							$defaultOptions[] = $option;
							if (isSet($article['options'][$option]))
								$article['options'][$option] = true;
						}
					}

					echo 'Default options: ';
					foreach ($defaultOptions as $option) echo $option.' ';
					echo EOL;
					echo 'Options (next article): ';
					foreach ($article['options'] as $option => $value)
						echo $option.'('.($value ? 'true' : 'false').') ';
					echo EOL;
				}
				elseif (preg_match('/^Vyčisti možnosti! *$/ui', $line))
				{
					$defaultOptions = null;
					foreach ($article['options'] as $option => $value)
						$article['options'][$option] = false;

					echo 'Default options list has been cleared.'.EOL;
					echo 'Options (next article): ';
					foreach ($article['options'] as $option => $value)
						echo $option.'('.($value ? 'true' : 'false').') ';
					echo EOL;
				}
				elseif (preg_match('/^Obsah: +(start|začiatok) *--> *$/ui',
					$line))
				{
					$this->deployStaticHTML($staticHTML);

					if (isSet($article['content']))
						$article['warnings'][] = 'Article content '.
							'has been overwritten';
					$article['content'] = ''; $empty = false;
					// echo 'Content started…'.EOL;
				}
				elseif (preg_match('/^Obsah:(.*)$/ui', $line, $matches))
				{
					$this->deployStaticHTML($staticHTML);

					if (isSet($article['content']))
						$article['warnings'][] = 'Article content '.
							'has been overwritten';
					$article['content'] = trim($matches[1]);
					if (!empty($matches[1])) $empty = false;

					$line = null;
					// echo 'Full content syntax matched…'.EOL;
				}
				elseif (preg_match('/^#ak-?(je-?)?zoznam-?prázdny\s*/ui',
					$line))
				{
					if (isSet($this->ifListEmpty))
						$this->deployStaticHTML($staticHTML);
					else
					{
						$this->deployStaticHTML($staticHTML);
						$this->ifListEmpty = true;
					}
				}
				elseif (preg_match(
					'/^#ak-?nie-?(je-?)?zoznam-?prázdny\s*/ui', $line) ||
					preg_match('/^#ak-?(je-?)?zoznam-?neprázdny\s*/ui',
					$line))
				{
					if (isSet($this->ifListEmpty))
						$this->deployStaticHTML($staticHTML);
					else
					{
						$this->deployStaticHTML($staticHTML);
						$this->ifListEmpty = false;
					}
				}
				elseif (preg_match('/^#správa-?prázdneho-?zoznamu:\s*(.*)/ui',
					$line, $matches))
				{
					if (isSet($this->listEmptyMessage))
						$article['warnings'][] = 'There is duplicate '.
							'definition of listEmptyMessage near this'.
							'article';
					$this->listEmptyMessage = $matches[1];
				}
				else
				{
					// $article['warnings'][] = 'Unknown field: '.$line;
					$staticHTML .= $line.EOL;
				}


				if (null === $line)
				{
					if ($empty)
					{
						echo '<b style="color:red">Empty '.
							'record ignored…</b>'.EOL2;
						$article = null; $link = null;
						continue;
					}

					// echo 'Content:'.EOL.$article['content'].EOL;
					echo 'Content-length: '.strlen($article['content']).EOL;

					// if (empty($article['content']))
					//	$article['warnings'][] = 'Article has no content';
					//else
					if (!empty($article['content']))
					{
						$article['content'] = $this->generateHTML(
							$article['content']);
						$article['style'] = $this->styleSheed;
						$article['javaScript'] = $this->javaScript;

						if (!empty($this->autoidcnts))
							$article['javaScript'] =
								$this->autoIDCntsToJavaScript().
								$article['javaScript'];

						$article['onShow'] = $this->onShow;
						$article['onExit'] = $this->onExit;
						$article['linkStyles'] = $this->linkStyles;
						$article['linkJavaScripts'] = $this->linkJavaScripts;
						if (isSet($this->files))
							$article['files'] = $this->files;
						if (isSet($this->externFiles))
							$article['externFiles'] = $this->externFiles;
					}

					// Generate filtered content for search engine
					$line = '';
					if (!empty($article['title']))
						$line .= $article['title'].' ';
					if (!empty($article['abstract']))
						$line .= $article['abstract'].' ';
					if (!empty($article['content']))
						$line .= $article['content'].' ';

					if (isSet($article['attachment']))
						foreach ($article['attachment'] as $i => $attachment)
						{
							if (isSet($article['description']) &&
								isSet($article['description'][$i]))
								$description = $article['description'][$i];
							else
								$description = $attachment;

							$line .= $description.' ';
						}

					if (!empty($line)) $article['search'] =
						RheiaMainClass::transliterate(RheiaMainClass::filterHTML(
							RheiaMainClass::filterObjects($line)));
					if (is_array($this->tabsSearch))
						$article['searchInTabs'] = $this->tabsSearch;
					if (!empty(RheiaMainClass::$filteredObjects))
						$article['searchInObjects'] =
							RheiaMainClass::implodeFilteredObjects();

					// Create iCal data section
					if (!empty($this->icalData))
					{
						$this->finalizeIcal();
						$article['ical'] = $this->icalData;
						$this->icalData = null;
					}

					// Generate preview of this article
					if ($article['options']['ukážka:abstrakt'] ||
						$article['options']['ukážka:obsah'] ||
						$article['options']['ukážka:prílohy'])
					{
						$line =
							($article['options']['ukážka:abstrakt'] ?
								($article['abstract'].' ') : '').
							($article['options']['ukážka:obsah'] ?
								RheiaMainClass::filterHTML(RheiaMainClass::filterObjects(
									$article['content'])) : '');

						if ($article['options']['ukážka:prílohy'] &&
							isSet($article['attachment']))
							foreach ($article['attachment'] as
								$i => $attachment)
							{
								if (isSet($article['description']) &&
									isSet($article['description'][$i]))
									$description =
										$article['description'][$i];
								else
									$description = $attachment;

								$line .= $description.' ';
							}
					}
					else
					{
						$line = !empty($article['abstract']) ?
							$article['abstract'] : RheiaMainClass::filterHTML(
								RheiaMainClass::filterObjects($article['content']));

						if (empty($line) && isSet($article['attachment']))
							foreach ($article['attachment'] as
								$i => $attachment)
							{
								if (isSet($article['description']) &&
									isSet($article['description'][$i]))
									$description =
										$article['description'][$i];
								else
									$description = $attachment;

								$line .= $description.' ';
							}
					}

					if (!empty($line))
					{
						/*if (preg_match('/^(\X{100}\pL*)/u', $line, $matches))
						{
							if (preg_match('/<[^>]+$/', $matches[1]))
								$matches[1] = preg_replace('/<[^>]+$/',
									'', $matches[1]);
							$article['preview'] = $matches[1].'…';
						}
						else
							$article['preview'] = $line;*/
						$article['preview'] = RheiaMainClass::cropText($line);
					}

					if (!empty($article['date']))
					{
						$link = date('Y-m-d-H-i', $article['date']);
						$link = preg_replace('/-00/', '', $link);
					}

					if (!empty($article['header']))
						$article['header'] =
							$this->generateHTML($article['header']);

					if (!empty($article['footer']))
						$article['footer'] =
							$this->generateHTML($article['footer']);

					if (!empty($article['root']))
					{
						foreach ($article['root'] as $key => $val)
						{
							$article['root'][$key][1] =
								$this->generateHTML($val[1]);
						}
					}

					if (!empty($article['parent']))
					{
						$article['parent'] = RheiaMainClass::transliterate(
							$article['parent'], '-');
					}

					if (!empty($article['ID']))
					{
						if ($article['options']['odkaz:bezDátumu'])
							$link = '';
						else if (!empty($link)) $link .= '-';
						$link .= RheiaMainClass::transliterate(
							$article['ID'], '-');
					}
					else if (!empty($article['title']))
					{
						if ($article['options']['odkaz:bezDátumu'])
							$link = '';

						if (!$article['options']['odkaz:bezNadpisu'] ||
							empty($link))
						{
							if (!empty($link)) $link .= '-';
							$link .= RheiaMainClass::transliterate(
								RheiaMainClass::filterHTML(RheiaMainClass::
									filterObjects($article['title'])), '-');
						}
					}

					if (empty($link) && !empty($article['source']))
					{
						$link .= RheiaMainClass::transliterate(
							$article['source'][1], '-');
					}

					if (empty($link))
						$article['warnings'][] =
							'Cannot create article ID – '.
							'Article does not have enough data';

					if ('title' === $link ||
						'templates' === $link ||
						'warnings' === $link ||
						'listEmptyMessage' === $link ||
						'revalidationDate' === $link)
					{
						$article['warnings'][] =
							'Reserved article ID: '.$link;
						$link .= '-info';
					}

					if (isSet($this->articles[$link]))
					{
						// Try to replace duplicates by “itelligent” rules
						if (((!empty($this->articles[$link]['expires']) &&
							$this->articles[$link]['expires'] < RheiaMainClass::
							$currentTime) || $this->articles[$link]['date'] >
							$article['date']) && (empty($article['date']) ||
							$article['expires'] >=
								RheiaMainClass::$currentTime))
							$this->articles[$link] = $article;
						$this->articles[$link]['warnings'][] =
							'Duplicate entry';
					}
					else $this->articles[$link] = $article;

					if (isSet($this->articles[$link]['warnings']))
					{
						// List all warnings
						foreach ($this->articles[$link]['warnings']
							as $warning) echo '  Warning: '.$warning.EOL;
						// echo EOL;
					}

					echo EOL;

					$article = null; $link = null; $empty = true;
				}

			}

			$this->deployStaticHTML($staticHTML);
			$this->articles['title'] = $this->title;
			$this->articles['listEmptyMessage'] =
				$this->listEmptyMessage;
			$this->articles['revalidationDate'] =
				RheiaMainClass::$revalidationDate;
			$this->title = null;
			echo '</pre>';

			if ($dump) ob_end_flush(); else ob_end_clean();

			$content = '<'.'?php'.EOL2;
			$content .= '$this->articles = '.
				var_export($this->articles, true);
			$content .= ';'.EOL2.'?'.'>';

			file_put_contents($parsed, $content, LOCK_EX);
			// if (!$checkExtern) file_put_contents($stamp,
			// 	check_extern_interval + RheiaMainClass::$currentTime, LOCK_EX);
		}
	}


	public function makeArticleLink($articleLink)
	{
		$strpos = strpos($articleLink, '|');
		if (false !== $strpos)
		{
			$attribute = substr($articleLink, 0, $strpos);
			$articleLink = substr($articleLink, 1 + $strpos);
		}
		else
			$attribute = null;

		$params = preg_split('#[\?&]#', trim(str_replace(' ',
			' ', $articleLink), '/ '), -1, PREG_SPLIT_NO_EMPTY);

		if (isSet($params[2]))
		{
			$source = array($params[1], $params[2]);
		}
		elseif (isSet($params[1]))
		{
			$source = array($params[0], $params[1]);
		}
		elseif (isSet($params[0]))
		{
			$source = array($this->baseName, $params[0]);
		}
		else
			return $articleLink;

		if (0 == strcasecmp($source[0], $this->baseName))
		{
			if (isSet($this->articles[$source[1]]))
			{
				$article = $this->articles[$source[1]];
				if (empty($article['link']))
					$article['link'] = $articleLink;
				if (null !== $attribute)
				{
					if ('date' == $attribute ||
						'update' == $attribute ||
						'expires' == $attribute)
						return date(date_format, $article[$attribute]);
					if ('title' == $attribute)
						return RheiaMainClass::replaceObjects(
							$article[$attribute]);
					return $article[$attribute];
				}
				return $this->buildArticleLink($article);
			}
		}
		else
		{
			$articleSource = RheiaMainClass::getCached($source[0]);
			$articleSource->loadArticles();
			$articleSource = $articleSource->getArticlesData();

			if (is_array($articleSource) &&
				isSet($articleSource[$source[1]]))
			{
				$article = $articleSource[$source[1]];
				if (empty($article['link']))
					$article['link'] = $articleLink;
				$articleSource = null;
				if (null !== $attribute)
				{
					if ('date' == $attribute ||
						'update' == $attribute ||
						'expires' == $attribute)
						return date(date_format, $article[$attribute]);
					if ('title' == $attribute)
						return RheiaMainClass::
							replaceObjects($article[$attribute]);
					return $article[$attribute];
				}
				return $this->buildArticleLink($article);
			}

			$articleSource = null;
		}

		return $articleLink;
	}


	public function buildArticleLink(&$article, $link = null,
		$articleLinkFormat = null, $replaceWhen = false)
	{
		$return = '';

		if ($article['options']['zoznam:akoPriečinok'])
		{
			$return .= '<ul';

			if (isSet($article['listLevel']))
				$return .= RheiaMainClass::getClassTag(
					$article['listLevel'], 'downloads-list');
			else
				$return .= ' class="downloads-list"';

			if ($article['options']['zoznam:nahraďZlomyNadpisu'])
			{
				if (empty($article['caption']))
				{
					if (empty($article['title']))
						$caption = str_replace(' <br />', ' ',
							'«'.RheiaMainClass::$text
								['article-untitled'].'»');
					else
						$caption = RheiaMainClass::replaceObjects(
							str_replace(' <br />', ' ', $article['title']));
				}
				else
					$caption = RheiaMainClass::replaceObjects(
						str_replace(' <br />', ' ', $article['caption']));
			}
			else
			{
				if (empty($article['caption']))
				{
					if (empty($article['title']))
						$caption = '«'.RheiaMainClass::$text
							['article-untitled'].'»';
					else
						$caption = RheiaMainClass::replaceObjects(
							$article['title']);
				}
				else
					$caption = RheiaMainClass::replaceObjects(
						$article['caption']);
			}

			$return .= '><li>'.RheiaMainClass::solveInternalRedirects(
				'<a href="'.(isSet($article['link']) ?
					$article['link'] :
					$articleLinkFormat.$link).'"'.
				(isSet($article['targetBlank']) ?
					' target="_blank"' : '').' class="download">').

				'<img src="'.RheiaMainClass::getDirectoryIconName().
				'" alt="'.RheiaMainClass::$text['icon-alt'].'" />'.

				$caption.'</a>';

			// Zmena:
			// zoznam:zjednodušZobrazenie —> zoznam:skryDátum
			if (!empty($article['date']) &&
				!$article['options']['zoznam:skryDátum'])
				$return .= ' <em class="fileinfo">'.date(date_format,
					$article['date']).(empty($article['updated']) ?
						'' : (', '.RheiaMainClass::$text['article-updated'].':
							'.date(date_format, $article['updated']))).'</em>';

			$return .= '</li></ul>';
		}
		else if ($article['options']['zoznam:zjednodušZobrazenie'])
		{
			$return .= '<ul';

			if (isSet($article['listLevel']))
				$return .= RheiaMainClass::getClassTag(
					$article['listLevel']);

			if ($article['options']['zoznam:nahraďZlomyNadpisu'])
			{
				if (empty($article['caption']))
				{
					if (empty($article['title']))
						$caption = str_replace(' <br />', ' ',
							'«'.RheiaMainClass::$text
								['article-untitled'].'»');
					else
						$caption = RheiaMainClass::replaceObjects(
							str_replace(' <br />', ' ', $article['title']));
				}
				else
					$caption = RheiaMainClass::replaceObjects(
						str_replace(' <br />', ' ', $article['caption']));
			}
			else
			{
				if (empty($article['caption']))
				{
					if (empty($article['title']))
						$caption = '«'.RheiaMainClass::$text
							['article-untitled'].'»';
					else
						$caption = RheiaMainClass::replaceObjects(
							$article['title']);
				}
				else
					$caption = RheiaMainClass::replaceObjects(
						$article['caption']);
			}

			$return .= '><li>'.RheiaMainClass::solveInternalRedirects(
				'<a href="'.(isSet($article['link']) ?
					$article['link'] :
					$articleLinkFormat.$link).'"'.
				(isSet($article['targetBlank']) ?
					' target="_blank"' : '').'>').

				$caption.'</a>';

			// Nové:
			if (!empty($article['date']) &&
				!$article['options']['zoznam:skryDátum'])
				$return .= ' <em class="fileinfo">'.date(date_format,
					$article['date']).(empty($article['updated']) ?
						'' : (', '.RheiaMainClass::$text['article-updated'].':
							'.date(date_format, $article['updated']))).'</em>';

			$return .= '</li></ul>';
		}
		else
		{
			$returnValue = '';
			$startTag = '<div class="article-preview';
			if ($article['options']['zoznam:dôležité'])
				$startTag .= ' article-important';
			if ($article['options']['zoznam:parte'])
				$startTag .= ' article-parte';
			if ($article['options']['zoznam:pripnuté'])
				$startTag .= ' article-pinned';
			$startTag .= '">';
			$previewTopDots = ' preview-top-dots';

			if (!$article['options']['zoznam:skryDátum'] &&
				!empty($article['date']))
			{
				$returnValue .= '<div class="date"><p>'.date(date_format,
					$article['date']).', '.($replaceWhen ? RheiaMainClass::getWhen(
						$article['date']) : '$when('.$article['date'].')');

				if (!empty($article['updated'])) $returnValue .= ', '.
					RheiaMainClass::$text['article-updated'].': '.
					date(date_format, $article['updated']).', '.($replaceWhen
						? RheiaMainClass::getWhen($article['updated']) : '$when('.
					$article['updated'].')');

				if (!$article['options']['zoznam:skryPoznámku'] &&
					!empty($article['note']))
					$returnValue .= ', '.$article['note'];
				$returnValue .= '</p></div>';
			}
			elseif (!$article['options']['zoznam:skryPoznámku'] &&
				!empty($article['note'])) $returnValue .= '<div class='.
					'"date"><p>'.$article['note'].'</p></div>';

			if (!$article['options']['zoznam:skryAutora'] &&
				(!empty($article['author']) ||
					!empty($article['publisher'])))
			{
				$returnValue .= '<div class="author">';
				if (!empty($article['author']))
					$returnValue .= '<p>'.$article['author'].'</p>';
				if (!empty($article['publisher']))
					$returnValue .= '<p>'.$article['publisher'].'</p>';
				$returnValue .= '</div>';
			}

			if ((!$article['options']['zoznam:skryPoznámku'] &&
					!empty($article['note'])) ||
				(!$article['options']['zoznam:skryDátum'] &&
					!empty($article['date'])) ||
				(!$article['options']['zoznam:skryAutora'] &&
					(!empty($article['author']) ||
						!empty($article['publisher']))))
				$returnValue .= '<div class="clear"></div>';
				else $previewTopDots = '';

			if (!$article['options']['zoznam:skryNadpis'] &&
				(!empty($article['title']) || !empty($article['caption'])))
			{
				$returnValue .= '<div class="title'.$previewTopDots.'"><h2>';

				if (empty($article['caption']))
				{
					if ($article['options']['zoznam:vypniOtvorenie'])
						$returnValue .= // RheiaMainClass::handleNBSP(
							RheiaMainClass::replaceObjects(
								$article['options']
								['zoznam:nahraďZlomyNadpisu'] ?
								str_replace(' <br />', ' ',
									$article['title']) : $article['title']);
					else
						$returnValue .= RheiaMainClass::solveInternalRedirects(
							'<a href="'.(isSet($article['link']) ?
								$article['link'] :
								$articleLinkFormat.$link).'"'.
							(isSet($article['targetBlank']) ?
								' target="_blank"' : '').'>').
							// RheiaMainClass::handleNBSP(
								RheiaMainClass::replaceObjects(
									$article['options']
									['zoznam:nahraďZlomyNadpisu'] ?
									str_replace(' <br />', ' ',
										$article['title']) :
									$article['title']).'</a>';
				}
				else
				{
					if ($article['options']['zoznam:vypniOtvorenie'])
						$returnValue .= // RheiaMainClass::handleNBSP(
							RheiaMainClass::replaceObjects(
								$article['options']
								['zoznam:nahraďZlomyNadpisu'] ?
								str_replace(' <br />', ' ',
									$article['caption']) :
								$article['caption']);
					else
						$returnValue .= RheiaMainClass::solveInternalRedirects(
							'<a href="'.(isSet($article['link']) ?
								$article['link'] :
								$articleLinkFormat.$link).'"'.
							(isSet($article['targetBlank']) ?
								' target="_blank"' : '').'>').
							// RheiaMainClass::handleNBSP(
								RheiaMainClass::replaceObjects(
									$article['options']
									['zoznam:nahraďZlomyNadpisu'] ?
									str_replace(' <br />', ' ',
										$article['caption']) :
									$article['caption']).'</a>';
				}

				$returnValue .= '</h2></div>'; $previewTopDots = '';
			}

			if (!$article['options']['zoznam:skryUkážku'] &&
				(!empty($article['preview']) ||
					!empty($article['previewCode'])))
			{
				$returnValue .= '<div class="preview'.$previewTopDots.'"><p>'.
					(empty($article['previewCode']) ?
						RheiaMainClass::handleNBSP($article['preview']) :
						$article['previewCode']);

				if (!$article['options']['zoznam:vypniOtvorenie'])
				{
					$returnValue .= RheiaMainClass::solveInternalRedirects(
						'<a href="'.(isSet($article['link']) ?
							$article['link'] :
							$articleLinkFormat.$link).
						'"'.(isSet($article['targetBlank']) ?
							' target="_blank"' : '').
						' class="read-more">');
					if (isSet($article['linkText']))
						$returnValue .= $article['linkText'];
					else $returnValue .= RheiaMainClass::$text
						['article-list-read-more'];
					$returnValue .= ' »</a>';
				}

				$returnValue .= '</p></div>';
			}

			if (!empty($returnValue))
				$return .= $startTag.$returnValue.'</div>';
			else
			{
				$return .= EOL.'<!-- Nie je čo zobraziť.';
				if (!empty($article['source']))
					$return .= ' ('.$article['source'][0].
						': '.$article['source'][1].')';
				$return .= ' -->'.EOL;
			}
		}

		return $return;
	}

	public function getArticlePreview(&$article, $link = null,
		$articleLinkFormat = null, $replaceWhen = true)
	{
		if ('title' === $link || 'templates' === $link ||
			'warnings' === $link || 'listEmptyMessage' === $link ||
			'revalidationDate' === $link || isSet($article['staticHTML']) ||
			$article['options']['článok:odstránený'])
			return false;

		if (!empty($article['source']))
		{
			$articleSource =
				RheiaMainClass::getCached($article['source'][0]);
			$articleSource->loadArticles();
			$articleSource = $articleSource->getArticlesData();

			if (is_array($articleSource) &&
				isSet($articleSource[$article['source'][1]]))
			{
				$articleSource = $articleSource[$article['source'][1]];

				foreach ($article as $attribute => $value)
				{
					if (empty($article[$attribute]) &&
						isSet($articleSource[$attribute]))
						$article[$attribute] =
							$articleSource[$attribute];
				}

				if (isSet($articleSource['targetBlank']))
					$article['link'] = $articleSource['link'];
			}

			$articleSource = null;
		}

		if (!$article['options']['zoznam:skryČlánok'] &&
			(!isSet($article['expires']) ||
			($article['expires'] >= RheiaMainClass::$currentTime) ||
			$article['options']['zoznam:ponechajNeplatné']) &&
			(!isSet($article['date']) ||
			($article['date'] <= RheiaMainClass::$currentTime) ||
			$article['options']['zoznam:zobrazVopred']))
			return $this->buildArticleLink($article,
				$link, $articleLinkFormat, $replaceWhen);

		return null;
	}

	public function articlesList($articleLinkFormat = null)
	{
		global $style, $javaScript1, $javaScript2, $javaScript3,
			$linkStyles, $linkJavaScripts;

		$articlesDisplayed = 0; $listEmptyMessagesShowed = 0;

		foreach ($this->articles as $link => $article)
		{
			if ('title' === $link)
			{
				if (empty($this->title))
					$this->title = RheiaMainClass::filterHTML(
						RheiaMainClass::replaceObjects($article));
			}
			elseif ('templates' === $link || 'warnings' === $link ||
				'listEmptyMessage' === $link || 'revalidationDate' === $link)
			{ /* reserved */ }
			elseif (isSet($article['staticHTML']))
			{
				if (!isSet($article['ifListEmpty']) ||
					((0 === $articlesDisplayed) === $article['ifListEmpty']))
				{
					echo RheiaMainClass::replaceObjects(
						$article['staticHTML']);
					if (!empty($article['style']))
						$style .= $article['style'];
					if (!empty($article['javaScript']))
						$javaScript1 .= $article['javaScript'];
					if (!empty($article['onShow']))
						$javaScript2 .= $article['onShow'];
					if (!empty($article['onExit']))
						$javaScript3 .= $article['onExit'];
					if (!empty($article['linkStyles']))
					{
						$linkStyles = array_merge($linkStyles,
							$article['linkStyles']);
						foreach ($article['linkStyles'] as $key => $val)
							if ($val && function_exists('loadStyleVersion'))
								loadStyleVersion($key);
					}
					if (!empty($article['linkJavaScripts']))
					{
						$linkJavaScripts = array_merge($linkJavaScripts,
							$article['linkJavaScripts']);
						foreach ($article['linkJavaScripts'] as $key => $val)
							if ($val && function_exists('loadScriptVersion'))
								loadScriptVersion($key);
					}

					if (isSet($article['ifListEmpty']))
					{
						++$listEmptyMessagesShowed;
						$articlesDisplayed = 0;
					}
				}
			}
			else if (!$article['options']['článok:odstránený'])
			{
				// if (!empty($article['category']))
				// {
				// }

				if (!empty($article['source']))
				{
					$articleSource =
						RheiaMainClass::getCached($article['source'][0]);
					$articleSource->loadArticles();
					$articleSource = $articleSource->getArticlesData();

					if (is_array($articleSource) &&
						isSet($articleSource[$article['source'][1]]))
					{
						$articleSource = $articleSource[$article['source'][1]];

						foreach ($article as $attribute => $value)
						{
							if (empty($article[$attribute]) &&
								isSet($articleSource[$attribute]))
								$article[$attribute] =
									$articleSource[$attribute];
						}

						if (isSet($articleSource['targetBlank']))
							$article['link'] = $articleSource['link'];
					}
					// Zdroj nebol nájdený, lenže to neprekáža, lebo nie
					// vždy je zdroj priamym odkazom. Je to riešené inak,
					// v metóde buildArticleLink je kontrola, či je čo
					// zobraziť a ak nie, tak sa vygeneruje upozornenie
					// do komentára, že pri tomto zdroji nie je čo zobraziť.
					// else
					// {
					// 	echo EOL.'<!-- Zdroj článku nebol nájdený. ('.
					// 		$article['source'][0].': '.
					// 		$article['source'][1].') -->'.EOL;
					// 	$article['options']['zoznam:skryČlánok'] = true;
					// }

					$articleSource = null;
				}

				if (!$article['options']['zoznam:skryČlánok'] &&
					(!isSet($article['expires']) ||
					($article['expires'] >= RheiaMainClass::$currentTime) ||
					$article['options']['zoznam:ponechajNeplatné']) &&
					(!isSet($article['date']) ||
					($article['date'] <= RheiaMainClass::$currentTime) ||
					$article['options']['zoznam:zobrazVopred']))
				{
					echo $this->buildArticleLink($article,
						$link, $articleLinkFormat, true);
					++$articlesDisplayed;
				}
			}
		}

		if (0 == $articlesDisplayed && 0 == $listEmptyMessagesShowed)
		{
			if (isSet($this->articles['listEmptyMessage']))
			{
				if (!empty($this->articles['listEmptyMessage']))
					echo '<p>'.$this->articles['listEmptyMessage'].'</p>';
			}
			else echo '<p>'.RheiaMainClass::$text['article-list-empty'].'</p>';
		}
	}


	public function showArticle($link, $articleLinkFormat = null)
	{
		global $style, $javaScript1, $javaScript2, $javaScript3, $linkStyles,
			$linkJavaScripts, $langPostfix, $permalink, $articleID;

		if ('title' != $link && 'templates' != $link &&
			'warnings' != $link && 'listEmptyMessage' != $link &&
			'revalidationDate' != $link && isSet($this->articles[$link]) &&
			!isSet($this->articles[$link]['staticHTML']))
		{
			$articleID = $link;
			$article = $this->articles[$link];

			if ($article['options']['článok:odstránený'])
			{
				if (isSet($article['link']))
				{
					global $newName;
					$newName = $article['link'];
					header('Location: '.$newName);
					include '301.php'; // obsahuje header 301
					echo EOL.'<!-- Požadovaný článok bol presunutý. ('.
						$link.': '.$article['link'].') -->'.EOL;
				}
				else
				{
					include '410.php';
					echo EOL.'<!-- Požadovaný článok bol odstránený. ('.
						$link.') -->'.EOL;
				}

				return;
			}

			if (!empty($article['source']))
			{
				// echo '<pre>'; var_dump($article['source']); echo '</pre>';
				$articleSource = RheiaMainClass::getCached($article['source'][0]);
				$articleSource->loadArticles();
				$articleSource = $articleSource->getArticlesData();

				if (is_array($articleSource) &&
					isSet($articleSource[$article['source'][1]]))
				{
					// $article = $articleSource[$article['source'][1]];
					$articleSource = $articleSource[$article['source'][1]];

					foreach ($article as $attribute => $value)
					{
						if (empty($article[$attribute]) &&
							isSet($articleSource[$attribute]))
							$article[$attribute] = $articleSource[$attribute];
					}

					if (isSet($articleSource['attachment']))
					{
						$article['attachment'] =
							$articleSource['attachment'];
						if (isSet($articleSource['description']))
							$article['description'] =
								$articleSource['description'];
					}

					foreach (array('style', 'javaScript', 'onShow', 'onExit',
						'linkStyles', 'linkJavaScripts') as $attribute)
					{
						if (empty($article[$attribute]) &&
							isSet($articleSource[$attribute]))
							$article[$attribute] = $articleSource[$attribute];
					}

					// echo '<pre>'; var_dump($article); echo '</pre>';
				}

				$articleSource = null;
			}

			if (isSet($article['lang']) && is_array($article['lang']))
				$langPostfix = $article['lang'];

			$expired = isSet($article['expires']) &&
				($article['expires'] < RheiaMainClass::$currentTime);
			if ($article['options']['detail:skryVarovanieNeplatnosti'])
				$expired = false;

			echo '<div class="article'.($expired ? ' expired' : '').
				($article['options']['detail:dôležité'] ? ' important' : '').
				($article['options']['detail:parte'] ? ' parte' : '').
				($article['options']['detail:pripnuté'] ? ' pinned' : '').
				'">';

			if (!empty($article['header']))
			{
				// echo '<pre>'; var_dump($article['header']); echo '</pre>';
				echo '<div class="article-header">';
				echo RheiaMainClass::replaceObjects($article['header']);
				echo '</div>';
			}

			if (!empty($article['root']) || !empty($article['parent']))
			{
				$separator = false;
				echo '<div class="history"><p>';

				if (!empty($article['root']))
				{
					foreach ($article['root'] as $root)
					{
						if ($separator) echo ' » ';
						else $separator = true;

						echo RheiaMainClass::solveInternalRedirects(
							'<a href="'.
							//(empty($root) ? '' :
								$root[0]
							//	)
							.'">').
							RheiaMainClass::filterHTML(RheiaMainClass::
								replaceObjects($root[1])).'</a>';
					}
				}

				if (!empty($article['parent']))
				{
					$parentLink = $article['parent'];

					if ('title' != $parentLink &&
						'templates' != $parentLink &&
						'warnings' != $parentLink &&
						'listEmptyMessage' != $parentLink &&
						'revalidationDate' != $parentLink &&
						isSet($this->articles[$parentLink]) &&
						!isSet($this->articles[$parentLink]['staticHTML']))
					{
						if ($separator) echo ' » ';
						$parent = $this->articles[$parentLink];

						echo RheiaMainClass::solveInternalRedirects(
							'<a href="'.$articleLinkFormat.$parentLink.'">');
						if (!empty($parent['title']))
							echo RheiaMainClass::replaceObjects(
								$parent['title']);
						else
							echo RheiaMainClass::$text['article-untitled'];
						echo '</a>';
					}
				}

				echo '</p></div>';
			}

			if (!$article['options']['detail:skryDátum'] &&
				!empty($article['date']))
			{
				echo '<div class="date"><p>'.RheiaMainClass::$text['article-date'].
					': '.date(date_format, $article['date']).', '.RheiaMainClass::
					getWhen($article['date']);

				if (!empty($article['updated'])) echo ', '.RheiaMainClass::$text
					['article-updated'].': '.date(date_format, $article
					['updated']).', '.RheiaMainClass::getWhen($article
					['updated']);

				if (!$article['options']['detail:skryPoznámku'] &&
					!empty($article['note'])) echo ', '.$article['note'];
				echo '</p></div>';
			}
			elseif (!$article['options']['detail:skryPoznámku'] &&
				!empty($article['note'])) echo '<div class="date"><p>'.
					$article['note'].'</p></div>';

			if (!$article['options']['detail:skryAutora'] &&
				(!empty($article['author']) ||
					!empty($article['publisher'])))
			{
				echo '<div class="author">';
				if (!empty($article['author']))
					echo '<p>'.($article['options']['článok:viacAutorov'] ?
						RheiaMainClass::$text['article-authors'] :
						RheiaMainClass::$text['article-author']).
						': '.$article['author'].'</p>';
				if (!empty($article['publisher']))
					echo '<p>'.($article['options']
							['článok:viacZverejňovateľov'] ?
						RheiaMainClass::$text['article-publishers'] :
						RheiaMainClass::$text['article-publisher']).
						': '.$article['publisher'].'</p>';
				echo '</div>';
			}

			if (!empty($article['root']) || !empty($article['parent']) ||
				(!$article['options']['detail:skryPoznámku'] &&
					!empty($article['note'])) ||
				(!$article['options']['detail:skryDátum'] &&
					!empty($article['date'])) ||
				(!$article['options']['detail:skryAutora'] &&
					(!empty($article['author']) ||
						!empty($article['publisher']))))
				echo '<div class="clear"></div>';

			if ($expired)
			{
				echo '<p class="error"> <br /><b>'.RheiaMainClass::$text
					['article-expired-warning'].':</b> '.RheiaMainClass::$text
					['article-expired-expired'].
					'!</p><p class="expiredDesc">'.
					RheiaMainClass::$text['article-expired-description'].
					'…<br /> <br /> </p>';
			}

			if (!empty($article['title']))
			{
				if (!$article['options']['detail:skryNadpis'])
				{
					if ($expired)
					{
						echo '<div class="title"><h1'.$article['titleCodes'].
							'>'.RheiaMainClass::replaceObjects(
								$article['options']['detail:nahraďZlomyNadpisu']
								?
								str_replace(' <br />', ' ', $article['title'])
								:
								$article['title']).'</h1></div>';
					}
					else
					{
						echo '<div class="title"><h1'.
							$article['titleCodes'].'>';

						// http://www.tamurajones.net/MarkingPermalinks.xhtml
						if (empty($permalink))
						{
							if (isSet($article['link']))
							{
								if (!preg_match('#^https?://.*#i',
									$article['link']))
									$permalink = $article['link'];
							}
							else
								$permalink = $articleLinkFormat.$link;

							if (!empty($permalink))
							{
								$permalink = RheiaMainClass::
									solveInternalRedirectURL(RheiaMainClass::$protocol.'://'.
										rtrim(RheiaMainClass::$domain.'/'.rtrim(
											RheiaMainClass::$siteSectionPath, '/'),
											'/').'/'.ltrim($permalink, '/'));
							}
						}

						if (empty($permalink))
							echo RheiaMainClass::solveInternalRedirects('<a href="'.
								(isSet($article['link']) ? $article['link'] :
									$articleLinkFormat.$link).'">');
						else
							echo '<a class="permalink" href="'.$permalink.
								'" rel="bookmark" title="'.RheiaMainClass::
								filterHTML(RheiaMainClass::replaceObjects(
									$article['options']['detail:nahraďZlomyNadpisu']
									? str_replace(' <br />', ' ',
										$article['title'])
									: $article['title'])).'">';

						echo '<?>';

						echo // RheiaMainClass::handleNBSP(
							RheiaMainClass::replaceObjects(
								$article['options']['detail:nahraďZlomyNadpisu']
								?
								str_replace(' <br />', ' ', $article['title'])
								:
								$article['title']).'</a></h1></div>';
					}
				}

				if (empty($this->title))
					$this->title = RheiaMainClass::filterHTML(
						RheiaMainClass::replaceObjects(
							str_replace(' <br />', ' ', $article['title'])));
			}

			if (!$article['options']['detail:skryAbstrakt'] &&
				!empty($article['abstract']))
			{
				echo '<div class="abstract"><p>'.
					RheiaMainClass::handleNBSP(
						$article['abstract']).'</p></div>';
			}

			if (!$article['options']['detail:skryObsah'] &&
				!empty($article['content']))
			{
				echo '<div class="article-content">'.
					RheiaMainClass::replaceObjects(
						$article['content']).'</div>';
			}

			if (!empty($article['style']))
				$style .= $article['style'];
			if (!empty($article['javaScript']))
				$javaScript1 .= $article['javaScript'];
			if (!empty($article['onShow']))
				$javaScript2 .= $article['onShow'];
			if (!empty($article['onExit']))
				$javaScript3 .= $article['onExit'];
			if (!empty($article['linkStyles']))
			{
				$linkStyles = array_merge($linkStyles,
					$article['linkStyles']);
				foreach ($article['linkStyles'] as $key => $val)
					if ($val && function_exists('loadStyleVersion'))
						loadStyleVersion($key);
			}
			if (!empty($article['linkJavaScripts']))
			{
				$linkJavaScripts = array_merge($linkJavaScripts,
					$article['linkJavaScripts']);
				foreach ($article['linkJavaScripts'] as $key => $val)
					if ($val && function_exists('loadScriptVersion'))
						loadScriptVersion($key);
			}

			if (isSet($article['attachment']))
			{
				if (!$article['options']['detail:skryOddeľovačPríloh'])
					echo '<hr class="attachments-separator" />'.EOL;

				echo '<ul class="downloads-list">';

				foreach ($article['attachment'] as $i => $attachment)
				{
					echo '<li>';

					if (isSet($article['description']) &&
						isSet($article['description'][$i]))
						$description = $article['description'][$i];
					else
						$description = $attachment;

					$exists = false; $fileSize = null; $fileDate = null;
					RheiaMainClass::getFileInfo($attachment,
						$exists, $fileSize, $fileDate);

					echo RheiaMainClass::solveInternalRedirects(
						'<a href="/'.RheiaMainClass::$downloadScript.
						'?'.$attachment. // rawurlencode().
						'" class="download">');
					echo '<img src="'.RheiaMainClass::
						getFileIconName($attachment, $exists).'" alt="'.
						RheiaMainClass::$text['icon-alt'].'" /><span>'.
						$description.'</span></a>';

					if ($exists) echo RheiaMainClass::
						formatFileInfo($fileSize, $fileDate);

					echo '</li>';
				}
				echo '</ul>';
			}

			if (isSet($article['targetBlank']))
				echo '<p class="note"><b>'.RheiaMainClass::$text['common-note'].
					':</b> '.RheiaMainClass::$text['common-target-blank'].'</p>';

			if (!empty($article['footer']))
			{
				echo '<div class="article-footer">';
				echo RheiaMainClass::replaceObjects($article['footer']);
				echo '</div>';
			}

			echo '</div>';
		}
		else
		{
			include '404.php';
			echo EOL.'<!-- Požadovaný článok nebol nájdený. ('.
				$link.', '.RheiaMainClass::$contentPath.') -->'.EOL;
			// RheiaMainClass::logError('Article “'.$link.'” does not exist.');
		}
	}


	public function getSourceDate()
	{
		return $this->sourceDate;
	}

	public function getBaseName()
	{
		return $this->baseName;
	}

	public function getArticlesData()
	{
		return $this->articles;
	}

	public function getHTMLData()
	{
		return $this->html;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public static function getObjectsData()
	{
		return RheiaMainClass::$objects;
	}

	public static function setObjectsData($objectsData)
	{
		RheiaMainClass::$objects = $objectsData;
	}


	public static function noTabLink($link)
	{
		$strpos = strpos($link, '#');
		if (false === $strpos) return $link;
		return substr($link, 0, $strpos);
	}


	public function search($location, $link, $articleSeparator = null)
	{
		if (null !== $this->html)
		{
			if (null === $location && isSet($this->html['title']))
				$location = RheiaMainClass::filterHTML(
					RheiaMainClass::replaceObjects($this->html['title']));

			if (isSet($this->html['searchInTabs']))
			{
				$noTabLink = RheiaMainClass::noTabLink($link);

				foreach ($this->html['searchInTabs'] as $tabID => $tab)
				{
					RheiaMainClass::$searchIn = $tab['search'];
					$rate = RheiaMainClass::getSearchRate();

					if ($rate)
					{
						// echo '<p>Rate Tab: '.$rate.' '.$noTabLink.'#'.$tabID.'</p>';
						RheiaMainClass::$searchResults[] =
							array($location.' » '.$tab['name'],
								RheiaMainClass::handleNBSP($tab['preview']),
								$noTabLink.'#'.$tabID, $rate + 1,
								$this->sourceDate);
					}
				}
			}

			// echo '<p>Search HTML: '.$link.'</p>';
			RheiaMainClass::$searchIn = $this->html['search'];
			$rate = RheiaMainClass::getSearchRate();

			if (isSet($this->html['searchInObjects']))
			{
				RheiaMainClass::$searchIn = RheiaMainClass::transliterate(
					RheiaMainClass::filterHTML(RheiaMainClass::replaceObjects(
						$this->html['searchInObjects'])));
				$rateSiO = RheiaMainClass::getSearchRate();
				if ($rateSiO > $rate) $rate = $rateSiO;
			}

			if ($rate)
			{
				// echo '<p>Rate: '.$rate.' '.$link.'</p>';
				// Title, Preview, Link, Rate, Date
				RheiaMainClass::$searchResults[] =
					array($location, RheiaMainClass::handleNBSP(
						$this->html['preview']),
						$link, $rate, $this->sourceDate);
			}
		}

		if (null !== $this->articles)
		{
			if (null === $location && isSet($this->articles['title']))
				$location = RheiaMainClass::filterHTML(
					RheiaMainClass::replaceObjects($this->articles['title']));

			// echo '<p>Search Articles: '.$link.' ('.$articleSeparator.')</p>';
			RheiaMainClass::$searchIn = null; $searchInObjects = null;
			$searchInTabs = array();

			foreach ($this->articles as $link2 => $article)
			{
				if ('title' === $link2 || 'templates' === $link2 ||
					'warnings' === $link2 || 'listEmptyMessage' === $link2 ||
					'revalidationDate' === $link2)
					continue;
				if (isSet($article['staticHTML']))
				{
					RheiaMainClass::$searchIn .= ' '.$article['search'];
					if (isSet($article['searchInObjects']))
						$searchInObjects .= ' '.$article['searchInObjects'];
					if (isSet($article['searchInTabs']))
						$searchInTabs = array_merge($searchInTabs,
							$article['searchInTabs']);
				}
			}

			if (!empty($searchInTabs))
			{
				$noTabLink = RheiaMainClass::noTabLink($link);

				foreach ($searchInTabs as $tabID => $tab)
				{
					RheiaMainClass::$searchIn = $tab['search'];
					$rate = RheiaMainClass::getSearchRate();

					if ($rate)
					{
						// echo '<p>Rate Tab: '.$rate.' '.$noTabLink.'#'.$tabID.'</p>';
						RheiaMainClass::$searchResults[] =
							array($location.' » '.$tab['name'],
								RheiaMainClass::handleNBSP($tab['preview']),
								$noTabLink.'#'.$tabID, $rate + 1,
								$this->sourceDate);
					}
				}
			}

			// echo '<p>Static: '.htmlspecialchars(RheiaMainClass::$searchIn).'</p>';
			$rate = RheiaMainClass::getSearchRate();

			if (!empty($searchInObjects))
			{
				RheiaMainClass::$searchIn = RheiaMainClass::transliterate(
					RheiaMainClass::filterHTML(RheiaMainClass::replaceObjects(
						$searchInObjects)));
				$rateSiO = RheiaMainClass::getSearchRate();
				if ($rateSiO > $rate) $rate = $rateSiO;
			}

			if ($rate)
			{
				// echo '<p>Rate: '.$rate.' '.$link.'</p>';
				// Title, Preview, Link, Rate, Date
				RheiaMainClass::$searchResults[] =
					array($location, null, $link, $rate,
						$this->sourceDate);
			}

			foreach ($this->articles as $link2 => $article)
			{
				if ('title' === $link2 || 'templates' === $link2 ||
					'warnings' === $link2 || 'listEmptyMessage' === $link2 ||
					'revalidationDate' === $link2)
					continue;

				if (!isSet($article['staticHTML']) &&
					(!isSet($article['expires']) ||
						($article['expires'] >= RheiaMainClass::$currentTime)) &&
					(!isSet($article['date']) ||
						($article['date'] <= RheiaMainClass::$currentTime)) &&
					(!$article['options']['zoznam:skryČlánok'] ||
						!empty($article['title'])) &&
					!$article['options']['článok:odstránený'])
				{
					if (isSet($article['searchInTabs']))
					{
						$noTabLink = RheiaMainClass::noTabLink($link);

						foreach ($article['searchInTabs'] as $tabID => $tab)
						{
							RheiaMainClass::$searchIn = $tab['search'];
							$rate = RheiaMainClass::getSearchRate();

							if ($rate)
							{
								// Title, Preview, Link, Rate, Date
								RheiaMainClass::$searchResults[] =
									array($location.' » '.
										(empty($article['title']) ?
											('«'.RheiaMainClass::$text
											['article-untitled'].'»') :
												RheiaMainClass::
											filterHTML(RheiaMainClass::
												replaceObjects($article
													['title']))).
										' » '.$tab['name'],
										RheiaMainClass::handleNBSP(
											$tab['preview']),
										$noTabLink.$articleSeparator.
											$link2.'#'.$tabID,
										$rate + 1, $article['date']);
							}
						}
					}

					RheiaMainClass::$searchIn = $article['search'];
					$rate = RheiaMainClass::getSearchRate();

					if (isSet($article['searchInObjects']))
					{
						RheiaMainClass::$searchIn =
							RheiaMainClass::transliterate(
							RheiaMainClass::filterHTML(RheiaMainClass::
								replaceObjects($article['searchInObjects'])));
						$rateSiO = RheiaMainClass::getSearchRate();
						if ($rateSiO > $rate) $rate = $rateSiO;
					}

					if ($rate)
					{
						// Title, Preview, Link, Rate, Date
						RheiaMainClass::$searchResults[] = array($location.
							' » '.(empty($article['caption']) ? (
							(empty($article['title']) ?
								('«'.RheiaMainClass::
								$text['article-untitled'].'»') :
								RheiaMainClass::filterHTML(RheiaMainClass::
									replaceObjects($article['title'])))) :
								RheiaMainClass::filterHTML(RheiaMainClass::
									replaceObjects($article['caption']))),
							RheiaMainClass::handleNBSP($article['preview']),
							$link.$articleSeparator.$link2,
							$rate, $article['date']);
					}
				}
			}
		}
	}


	public static function searchResults()
	{
		function usort_cmp($a, $b)
		{
			if ($b[3] == $a[3]) return $b[4] - $a[4];
			return $b[3] - $a[3];
		}

		usort(RheiaMainClass::$searchResults, 'usort_cmp');

		if ($count = count(RheiaMainClass::$searchResults))
		{
			echo '<p><b>'.RheiaMainClass::$text['search-number-pages-found'].
				':</b> '.$count.'</p><ul class="search-results">';

			foreach (RheiaMainClass::$searchResults as $item)
			{
				// O: Title, Preview, Link, Rate, 4: Date
				echo '<li>'.RheiaMainClass::solveInternalRedirects('<a href="'.
					$item[2].'" target="_blank">').$item[0].
					' <em title="'.RheiaMainClass::$text['search-rate-title'].
					'"> <small>('.$item[3].'b.)</small> </em><em title="'.
					RheiaMainClass::$text['search-target-title'].'">»</em></a>';
				if (!empty($item[1])) echo '<br /><small>'.$item[1].
					'</small>';
				echo '</li>';
			}

			echo '</ul>';
		}
		else
		{
			echo '<p><b>'.RheiaMainClass::$text['search-pages-result'].
				':</b></p>'.EOL.'<p class="error">'.RheiaMainClass::$text
				['search-not-found'].'.</p>';
		}
	}


	public function rssItem($location, $link, $articleSeparator = null)
	{
		if (null !== $this->html)
		{
			// Open item element
			echo TAB.'<item>'.EOL;

			// Add title element
			echo TAB2.'<title>'.htmlspecialchars(str_replace(' ', ' ',
				RheiaMainClass::filterHTML(str_replace('<br />', ' ',
					RheiaMainClass::replaceObjects($location))))).
					'</title>'.EOL;

			// Add link element
			echo TAB2.'<link>'.htmlspecialchars(
				RheiaMainClass::$rssCore.$link).'</link>'.EOL;

			// Add description element
			echo TAB2.'<description>'.htmlspecialchars($this->html
				['preview']).'</description>'.EOL;

			// Add category element
			echo TAB2.'<category>Webové stránky</category>'.EOL;

			// Add pubDate element
			echo TAB2.'<pubDate>'.date(gmtdate_format,
				$this->sourceDate - 3600).'</pubDate>'.EOL;

			// Close item element
			echo TAB.'</item>'.EOL;
		}

		if (null !== $this->articles)
		{
			foreach ($this->articles as $link2 => $article)
			{
				if ('title' === $link2 || 'templates' === $link2 ||
					'warnings' === $link2 || 'listEmptyMessage' === $link2 ||
					'revalidationDate' === $link2)
					continue;

				if (!isSet($article['staticHTML']))
				{
					if (!empty($article['source']) ||
						$article['options']['článok:odstránený']) continue;
					/*{
						$articleSource = new RheiaMainClass(
							$article['source'][0], false);
						$articleSource->loadArticles();
						$articleSource = $articleSource->getArticlesData();

						if (is_array($articleSource) &&
							isSet($articleSource[$article['source'][1]]))
						{
							$articleSource =
								$articleSource[$article['source'][1]];

							foreach ($article as $attribute => $value)
							{
								if (empty($article[$attribute]) &&
									isSet($articleSource[$attribute]))
									$article[$attribute] =
										$articleSource[$attribute];
							}

							if (isSet($articleSource['targetBlank']))
								$article['link'] = $articleSource['link'];
						}

						$articleSource = null;
					}*/

					if ((!$article['options']['zoznam:skryČlánok']
							// ??? – o čom toto bolo??? || !empty($article['title'])
							)
						&&
						(!isSet($article['expires']) ||
							($article['expires'] >=
								RheiaMainClass::$currentTime) ||
							$article['options']['zoznam:ponechajNeplatné'])
						&&
						(!isSet($article['date']) ||
							($article['date'] <= RheiaMainClass::$currentTime)
							||
							$article['options']['zoznam:zobrazVopred']))
					{
						// Open item element
						echo TAB.'<item>'.EOL;

						// Add title element
						if (isSet($article['title']))
							echo TAB2.'<title>'.htmlspecialchars(
								str_replace(' ', ' ',
									RheiaMainClass::filterHTML(
									RheiaMainClass::replaceObjects(
										$article['title'])))).
							'</title>'.EOL;
						else
							echo TAB2.'<title>«'.RheiaMainClass::$text
								['article-untitled'].'»</title>'.EOL;

						// Add link element
						echo TAB2.'<link>'.htmlspecialchars(
							RheiaMainClass::$rssCore.$link).'</link>'.EOL;

						// Add description element
						if (isSet($article['preview']))
							echo TAB2.'<description>'.htmlspecialchars(
								$article['preview']).'</description>'.EOL;

						// Add author element
						if (isSet($article['author']) ||
							isSet($article['publisher']))
							echo TAB2.'<author>'.htmlspecialchars(
								$article['author'].((!empty(
									$article['author']) &&
									!empty($article['publisher'])) ?
										', ' : '').
								$article['publisher']).'</author>'.EOL;

						// Add category element
						echo TAB2.'<category>'.htmlspecialchars(
							$location).'</category>'.EOL;

						// Add guid element
						echo TAB2.'<guid isPermaLink="true">'.
							htmlspecialchars(RheiaMainClass::$rssCore.$link.
								$articleSeparator.$link2).
							'</guid>'.EOL;

						// Add pubDate element
						if (isSet($article['date']))
							echo TAB2.'<pubDate>'.date(gmtdate_format,
								$article['date'] - 3600).'</pubDate>'.EOL;

						// Close item element
						echo TAB.'</item>'.EOL;
					}
				}
			}
		}
	}


	private function setupIcal($data)
	{
		// $data = str_replace('code()', '', $data);

		if (preg_match('/^ *start *$/i', $data))
			$this->icalProcessing['capture'] = true;
		elseif (preg_match('/^ *end *$/i', $data))
			$this->icalProcessing['capture'] = false;
		elseif (preg_match('/^ *reset *$/i', $data))
		{
			if (!empty($this->icalProcessing['capture']))
				$this->icalProcessing = array('capture' => true);
			else
				$this->icalProcessing = array('capture' => false);
		}
		elseif (preg_match('/^ *data: *(.*)$/i', $data, $matches))
		{
			$this->processIcal($matches[1]);
		}
		elseif (preg_match('/^ *default: *(.*)$/i', $data, $matches))
		{
			if (!isSet($this->icalProcessing['default']) ||
				!is_array($this->icalProcessing['default']))
				$this->icalProcessing['default'] = array();

			$matches = explode('	', $matches[1]);
			foreach ($matches as $key => $val)
			{
				$explode = explode(':', $val);
				$this->icalProcessing['default'][$explode[0]] = $explode[1];
				$this->icalData['debug']['default'][] =
					$explode[0].':'.$explode[1];
			}
		}
		elseif (preg_match('/^ *uidparts: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['uidparts'] = explode('	', $matches[1]);
			$this->icalData['debug']['uidparts'][] =
				$this->icalProcessing['uidparts'];
		}
		elseif (preg_match('/^ *contentarray: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['contentarray'] = explode('	', $matches[1]);
			$this->icalData['debug']['contentarray'][] =
				$this->icalProcessing['contentarray'];
		}
		elseif (preg_match('/^ *activefilters: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['activefilters'] = explode('	', $matches[1]);
			$this->icalData['debug']['activefilters'][] =
				$this->icalProcessing['activefilters'];
		}
		elseif (preg_match('/^ *ifcontsetdef: *(.*)$/i', $data, $matches))
		{
			$matches = explode('	', $matches[1]);
			foreach ($matches as $key => $val)
			{
				if (preg_match('/^([^:]+):(.*)/', $matches[$key], $matchtemp))
				{
					$matches[$key] = array($matchtemp[1], $matchtemp[2]);
				}
				else $matches[$key] = explode(':', $matches[$key]);
			}
			$this->icalProcessing['ifcontsetdef'] = $matches;
			$this->icalData['debug']['ifcontsetdef'][] =
				$this->icalProcessing['ifcontsetdef'];
		}
		elseif (preg_match('/^ *prefixcontent: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['prefixcontent'] = explode('	', $matches[1]);
			$this->icalData['debug']['prefixcontent'][] =
				$this->icalProcessing['prefixcontent'];
		}
		elseif (preg_match('/^ *contentto: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['contentto'] = explode('	', $matches[1]);
			$this->icalData['debug']['contentto'][] =
				$this->icalProcessing['contentto'];
		}
		elseif (preg_match('/^ *updateempty: *(.*)$/i', $data, $matches))
		{
			$this->icalProcessing['updateempty'] = explode('	', $matches[1]);
			$this->icalData['debug']['updateempty'][] =
				$this->icalProcessing['updateempty'];
		}
		elseif (preg_match('/^ *(joinarray): *(.*) *	(.*)$/i',
			$data, $matches))
		{
			$this->icalProcessing['finalize'][] = $matches;
			$this->icalData['debug']['finalize'][] = $matches;
		}
		elseif (preg_match('/^ *(removerepeatingstring): *(.*) *	(.*)$/i',
			$data, $matches))
		{
			$this->icalProcessing['finalize'][] = $matches;
			$this->icalData['debug']['finalize'][] = $matches;
		}
		elseif (preg_match('/^ *(moveto): *(.*) *	 *(.*) *	(.*)$/i',
			$data, $matches))
		{
			$this->icalProcessing['finalize'][] = $matches;
			$this->icalData['debug']['finalize'][] = $matches;
		}
	}

	private function setIcalEventData2($key, $val)
	{
		if (!isSet($this->icalProcessing['default']) ||
			empty($this->icalProcessing['default']) ||
			!is_array($this->icalProcessing['default']))
		{
			$this->icalData['error'][] = 'cannot identify target for: '.$val;
			return;
		}

		if (!isSet($this->icalProcessing['uidparts']) ||
			empty($this->icalProcessing['uidparts']) ||
			!is_array($this->icalProcessing['uidparts']))
			$this->icalProcessing['uidparts'] = array('dates');

		$uid = '';
		foreach ($this->icalProcessing['uidparts'] as $part)
		{
			if (isSet($this->icalProcessing['default'][$part]))
				$uid .= $this->icalProcessing['default'][$part];
			else
				$uid .= 'unknown';
			$uid .= '-';
		}
		$uid = RheiaMainClass::transliterate($uid, '-').'-'.$this->baseName.
			'-event-'.RheiaMainClass::$icalUIDPostfix;

		if (is_array($val))
		{
			$data = '';
			foreach ($val as $part)
				$data .= $part.' ';
			$data = rtrim($data);
		}
		else $data = $val;

		/* // This is not possible here!
		$data = RheiaMainClass::filterHTML(
			RheiaMainClass::replaceObjects(
				$this->generateHTML($data)));
		*/
		$key = strtolower($key);

		if (isSet($this->icalProcessing['contentarray']) &&
			is_array($this->icalProcessing['contentarray']) &&
			in_array($key, $this->icalProcessing['contentarray']))
		{
			$this->icalData['events'][$uid][$key][] = $data;
		}
		else
		{
			if (isSet($this->icalData['events'][$uid]))
			{
				if (isSet($this->icalData['events'][$uid][$key]) &&
					'sequence' != $key)
					$this->icalData['events'][$uid][$key] .= ' '.$data;
				else
					$this->icalData['events'][$uid][$key] = $data;
			}
			else
			{
				$this->icalData['events'][$uid][$key] = $data;
			}
		}

		if (isSet($this->icalProcessing['updateempty']) &&
			is_array($this->icalProcessing['updateempty']))
		{
			foreach ($this->icalProcessing['updateempty'] as $what)
			{
				if (!isSet($this->icalData['events'][$uid][$what]) &&
					isSet($this->icalProcessing['default'][$what]))
					$this->icalData['events'][$uid][$what] =
						$this->icalProcessing['default'][$what];
			}
		}

		if (isSet($this->icalProcessing['contentarray']) &&
			is_array($this->icalProcessing['contentarray']))
		{
			foreach ($this->icalProcessing['contentarray'] as $what)
			{
				if (isSet($this->icalProcessing['default'][$what]))
					$this->icalData['events'][$uid][$what][] =
						$this->icalProcessing['default'][$what];
			}
		}
	}

	private function icalActiveFilterCallback($matches)
	{
		// $debug = 'XEX ';
		// foreach ($this->icalProcessing['default'] as $data => $value)
		//	$debug .= 'data('.$data.') value('.$value.')'.EOL;

		if (!empty($this->icalProcessing['activefilterset']))
		{
			$result = $this->icalProcessing['activefilterval'];
			// $debug .= $result.EOL;

			foreach ($matches as $i => $match)
				$result = str_replace('$'.$i, $match, $result);

			foreach ($this->icalProcessing['default'] as $data => $value)
				if (!empty($data))
					$result = str_replace('$'.$data, $value, $result);

			$this->icalProcessing['default']
				[$this->icalProcessing['activefilterset']] =
					$result;

			// $debug .= $result.EOL;
		}

		// $this->icalData['debug']['callback'][] =
		// 	$this->icalProcessing['activefilterset'].' = '.$result;

		$result = $this->icalProcessing['activefilterres'];
		// $debug .= $result.EOL;

		if (!empty($result))
		{
			foreach ($matches as $i => $match)
				$result = str_replace('$'.$i, $match, $result);

			foreach ($this->icalProcessing['default'] as $data => $value)
				if (!empty($data))
					$result = str_replace('$'.$data, $value, $result);

			// foreach ($this->icalProcessing['default'] as $data => $value)
			// 	if (!empty($data))
			// 		$debug .= '  '.$data.' = '.$value.EOL;
		}

		// $debug .= $result.EOL;
		// $this->icalData['debug']['callback'][] = $debug;

		return $result;
	}

	private function setIcalEventData($key, $val)
	{
		if (isSet($this->icalProcessing['activefilters']))
		{
			$activefilters = $this->icalProcessing['activefilters'];

			if (is_array($activefilters))
			{
				$count = count($activefilters);
				$backup = array();

				for ($i = 0; $i + 3 < $count; $i += 4)
				{
					$this->icalProcessing['activefilterset'] =
						$activefilters[$i + 1];
					$this->icalProcessing['activefilterval'] =
						$activefilters[$i + 2];
					$this->icalProcessing['activefilterres'] =
						$activefilters[$i + 3];

					if (isSet($this->icalProcessing['default']
						[$activefilters[$i + 1]]) &&
						!isSet($backup[$activefilters[$i + 1]]))
							$backup[$activefilters[$i + 1]] =
								$this->icalProcessing['default']
									[$activefilters[$i + 1]];

					$val = preg_replace_callback($activefilters[$i],
						array(&$this, 'icalActiveFilterCallback'), $val);
				}

				$this->setIcalEventData2($key, $val);

				for ($i = 0; $i + 2 < $count; $i += 3)
					unset($this->icalProcessing['default']
						[$activefilters[$i + 1]]);

				foreach ($backup as $res1 => $res2)
					$this->icalProcessing['default'][$res1] = $res2;
			}
		}
		else
		{
			$this->setIcalEventData2($key, $val);
		}
	}

	private function processIcalData(&$data)
	{
		if (false !== strpos($data, '	'))
		{
			if (preg_match('/^\*.*\*$/', $data))
				$data = trim($data, '*');

			$data = explode('	', $data);

			if (isSet($this->icalProcessing['contentto']) &&
				is_array($this->icalProcessing['contentto']))
			{
				foreach ($data as $key => $val)
				{
					if (!empty($val) &&
						isSet($this->icalProcessing['contentto'][$key]) &&
						!empty($this->icalProcessing['contentto'][$key]))
					{
						if (is_array($this->icalProcessing['prefixcontent']) &&
							isSet($this->icalProcessing['prefixcontent'][$key])
							&& !empty($this->icalProcessing['prefixcontent']
								[$key]))
						{
							$val = $this->icalProcessing
								['prefixcontent'][$key].$val;
						}

						if (is_array($this->icalProcessing['ifcontsetdef']) &&
							isSet($this->icalProcessing['ifcontsetdef'][$key]))
						{
							$ifcontsetdef = $this->
								icalProcessing['ifcontsetdef'][$key];
							if (is_array($ifcontsetdef))
							{
								$count = count($ifcontsetdef);
								$backup = array();

								for ($i = 0; $i + 1 < $count; $i += 2)
								{
									if (isSet($this->icalProcessing['default']
										[$ifcontsetdef[$i]]) &&
										!isSet($backup[$ifcontsetdef[$i]]))
											$backup[$ifcontsetdef[$i]] =
												$this->icalProcessing['default']
													[$ifcontsetdef[$i]];

									$this->icalProcessing
										['default'][$ifcontsetdef[$i]] =
											$ifcontsetdef[$i + 1];
								}

								$this->setIcalEventData($this->
									icalProcessing['contentto'][$key], $val);

								for ($i = 0; $i + 1 < $count; $i += 2)
									unset($this->icalProcessing['default']
										[$ifcontsetdef[$i]]);

								foreach ($backup as $res1 => $res2)
									$this->icalProcessing['default'][$res1] =
										$res2;
								continue;
							}
						}

						$this->setIcalEventData($this->
							icalProcessing['contentto'][$key], $val);
					}
				}
				return;
			}
		}

		if (isSet($this->icalProcessing['contentto']) &&
			is_array($this->icalProcessing['contentto']) &&
			isSet($this->icalProcessing['contentto'][0]))
		{
			$this->setIcalEventData($this->
				icalProcessing['contentto'][0], $data);
		}
		else
		{
			$this->setIcalEventData('description', $data);
		}
	}

	private function processIcal($data)
	{
		// $data = str_replace('code()', '', $data);
		if (!isSet($this->icalProcessing['updateempty']) ||
			!is_array($this->icalProcessing['updateempty']))
			$this->icalProcessing['updateempty'] = array('dtstart', 'dtend');
		if (!in_array('dtstart', $this->icalProcessing['updateempty']))
			$this->icalProcessing['updateempty'][] = 'dtstart';
		if (!in_array('dtend', $this->icalProcessing['updateempty']))
			$this->icalProcessing['updateempty'][] = 'dtend';

		if (preg_match('/0?([0-9]{1,2})\.[  ]*'.
			'0?([0-9]{1,2})\.[  ]*([0-9]{2,4})'.
			'[  ]*[-–‒—―−­‑]+[  ]*0?([0-9]{1,2})\.[  ]*'.
			'0?([0-9]{1,2})\.[  ]*([0-9]{2,4})/u',
			$data, $matches))
		{
			$this->icalProcessing['default']['dtstart'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]));
			$this->icalProcessing['default']['dtend'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[5], $matches[4] + 1, $matches[6]));
			$this->icalProcessing['default']['dates'] = date(icaldate_format,
				mktime(0, 0, 0, $matches[2], $matches[1], $matches[3])).'-'.
				date(icaldate_format,
					mktime(0, 0, 0, $matches[5], $matches[4], $matches[6]));
			$this->processIcalData($data);
		}
		elseif (preg_match('/0?([0-9]{1,2})\.[  ]*'.
			'0?([0-9]{1,2})\.'.
			'[  ]*[-–‒—―−­‑]+[  ]*0?([0-9]{1,2})\.[  ]*'.
			'0?([0-9]{1,2})\.[  ]*([0-9]{2,4})/u',
			$data, $matches))
		{
			$this->icalProcessing['default']['dtstart'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[2], $matches[1], $matches[5]));
			$this->icalProcessing['default']['dtend'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[4], $matches[3] + 1, $matches[5]));
			$this->icalProcessing['default']['dates'] = date(icaldate_format,
				mktime(0, 0, 0, $matches[2], $matches[1], $matches[5])).'-'.
				date(icaldate_format,
					mktime(0, 0, 0, $matches[4], $matches[3], $matches[5]));
			$this->processIcalData($data);
		}
		elseif (preg_match('/0?([0-9]{1,2})\.[  ]*'.
			'0?([0-9]{1,2})\.[  ]*([0-9]{2,4})/',
			$data, $matches))
		{
			$this->icalProcessing['default']['dtstart'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]));
			$this->icalProcessing['default']['dtend'] = // ;value=date
				date(icaldate_format,
					mktime(0, 0, 0, $matches[2], $matches[1] + 1, $matches[3]));
			$this->icalProcessing['default']['dates'] = date(icaldate_format,
				mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]));
			$this->processIcalData($data);
		}
		else
		{
			$this->icalData['raw'][] = $data;
		}
	}

	private function finalizeIcal()
	{
		if (isSet($this->icalProcessing['finalize']))
		foreach ($this->icalProcessing['finalize'] as $finalize)
		{
			if (preg_match('/^joinarray$/i', $finalize[1]))
			{
				foreach ($this->icalData['events'] as $uid => $event)
				{
					if (isSet($event[$finalize[2]]) &&
						is_array($event[$finalize[2]]))
					{
						$join = '';
						foreach ($event[$finalize[2]] as $value)
						{
							if (empty($join)) $join = $value;
							else $join .= $finalize[3].$value;
						}
						$this->icalData['events'][$uid][$finalize[2]] = $join;
					}
				}
			}
			elseif (preg_match('/^removerepeatingstring$/i', $finalize[1]))
			{
				foreach ($this->icalData['events'] as $uid => $event)
				{
					if (isSet($event[$finalize[2]]) &&
						is_array($event[$finalize[2]]))
					{
						for ($i = count($event[$finalize[2]]) - 1; $i > 0; --$i)
						{
							if (preg_match($finalize[3],
								$this->icalData['events']
									[$uid][$finalize[2]][$i - 1]) &&
								preg_match($finalize[3],
									$this->icalData['events'][$uid]
									[$finalize[2]][$i]))
								$this->icalData['events'][$uid]
									[$finalize[2]][$i] = preg_replace(
										$finalize[3], '', $this->
										icalData['events'][$uid]
										[$finalize[2]][$i]);
						}
					}
				}
			}
			elseif (preg_match('/^moveto$/i', $finalize[1]))
			{
				foreach ($this->icalData['events'] as $uid => $event)
				{
					if (isSet($event[$finalize[2]]))
					{
						if (isSet($event[$finalize[3]]) &&
							!empty($event[$finalize[3]]))
							$this->icalData['events'][$uid][$finalize[3]] .=
								$finalize[4].$this->icalData['events'][$uid]
								[$finalize[2]];
						else
							$this->icalData['events'][$uid][$finalize[3]] =
								$this->icalData['events'][$uid][$finalize[2]];
						unset($this->icalData['events'][$uid][$finalize[2]]);
					}
				}
			}
		}

		$finalizeRheiaClass = new RheiaMainClass();

		foreach ($this->icalData['events'] as $uid => $event)
		{
			foreach ($event as $data => $array)
			{
				if (is_array($array))
				{
					$join = '';
					foreach ($array as $value)
					{
						if (empty($join)) $join = $value;
						else $join .= ' '.$value;
					}
					$event[$data] = $this->icalData['events'][$uid][$data] =
						$join;
				}
			}

			foreach (array('summary', 'location', 'categories', 'description')
				as $data) if (isSet($event[$data]))
				{
					$event[$data] = $this->icalData['events'][$uid][$data] =
						trim(RheiaMainClass::filterHTML(RheiaMainClass::replaceObjects(
							$finalizeRheiaClass->generateHTML($event[$data]))));
				}

			foreach (array('summary', 'location', 'description') as $data)
				if (isSet($event[$data]))
				{
					$line = preg_replace('/\n/u', '\\n',
						preg_replace('/([\\\\;,])/u', '\\\\$1',
							preg_replace('/([  ]+)/u', ' ',
								preg_replace('/([  \r\t]*\n[  \r\t]*)/u', '\n',
									$event[$data]))));
					$lines = '';

					if (preg_match('/^(\X{0,45}\pL{0,9})/u',
						$line, $matches))
					{
						$lines = $matches[1];
						$line = substr($line, strlen($matches[1]));

						while (strlen($line) > 0)
						{
							if (preg_match('/^(\X{0,65}\pL{0,9})/u',
								$line, $matches))
							{
								$lines .= EOL.' '.$matches[1];
								$line = substr($line, strlen($matches[1]));
							}
							else
							{
								$lines .= EOL.' «error»';
								$this->icalData['error'][] =
									'cannot find enough text for '.$uid.
										' (2): '.$line;
								break;
							}
						}
					}
					else
					{
						$lines .= EOL.' «error»';
						$this->icalData['error'][] =
							'cannot find enough text for '.$uid.' (1): '.$line;
					}

					$event[$data] = $this->icalData['events'][$uid][$data] =
						$lines;
				}

			if (!isSet($event['summary']) && isSet($event['description']))
			{
				if (preg_match('/^(\X{0,36})(\pL*)/u',
					$event['description'], $matches))
				{
					if (strlen($matches[2]))
						$summary = trim(preg_replace(
							'/\pL*$/u', '', $matches[1]));
					else
						$summary = trim($matches[1]);

					if (strlen($summary) != strlen(
						trim($event['description'])))
						$summary .= '…';
					$this->icalData['events'][$uid]['summary'] = $summary;
				}
			}

			if (!isSet($event['dtstamp']))
			{
				$this->icalData['events'][$uid]['dtstamp'] =
					date(icalstamp_format);
			}
		}
	}
}

RheiaMainClass::$currentTime = time();

function updateRheiaStaticFields()
{
	global // $externalLinkIcon,
		$downloadPath, $downloadLevel, $downloadSubfolder, $iconsPath,
		$designFilesPath, $siteSectionPath, $protocol, $domain, $contentPath,
		$parsedPath, $downloadScript, $imageScript, $icalScript,
		$mailScript, $externDownParsedPath, $internalRedirects,
		$translation;

	/**
	 * Do NOT change any default values here. To modify the values,
	 * use global variables in your configuration PHP file.
	 */

	// if (isSet($externalLinkIcon))
	// 	RheiaMainClass::$externalLinkIcon = $externalLinkIcon;

	if (isSet($downloadPath))
		RheiaMainClass::$downloadPath = explode(';', $downloadPath);
	if (isSet($downloadLevel))
		RheiaMainClass::$downloadLevel = $downloadLevel;
	if (isSet($downloadSubfolder))
		RheiaMainClass::$downloadSubfolder = $downloadSubfolder;
	if (isSet($iconsPath))
		RheiaMainClass::$iconsPath = $iconsPath;
	if (isSet($designFilesPath))
		RheiaMainClass::$designFilesPath = $designFilesPath;
	if (isSet($siteSectionPath))
		RheiaMainClass::$siteSectionPath = $siteSectionPath;
	if (isSet($protocol))
		RheiaMainClass::$protocol = $protocol;
	if (isSet($domain))
		RheiaMainClass::$domain = $domain;
	if (isSet($contentPath))
		RheiaMainClass::$contentPath = $contentPath;
	if (isSet($parsedPath))
		RheiaMainClass::$parsedPath = $parsedPath;
	if (isSet($downloadScript))
		RheiaMainClass::$downloadScript = $downloadScript;
	if (isSet($imageScript))
		RheiaMainClass::$imageScript = $imageScript;
	if (isSet($icalScript))
		RheiaMainClass::$icalScript = $icalScript;
	if (isSet($mailScript))
		RheiaMainClass::$mailScript = $mailScript;
	if (isSet($externDownParsedPath))
		RheiaMainClass::$externDownParsedPath = $externDownParsedPath;
	if (isSet($internalRedirects))
		RheiaMainClass::$internalRedirects = $internalRedirects;

	if (isSet($translation))
		RheiaMainClass::localize($translation);
}

updateRheiaStaticFields();

?>