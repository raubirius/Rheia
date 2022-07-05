<?php

if (!isSet($searchTexts))
{
	$searchTexts = array(
		'search-title' => 'Vyhľadávanie',
		'search-search-for' => 'Hľadanie',
		'search-target-title' => 'symbol „otvárané v novom okne“',
		'search-employees-found' => 'Počet výsledkov nájdených v centrálnom zozname zamestnancov',
		'search-employees-not-found' => 'Zamestnanec nebol nájdený',
		// 'search-emplist-last-update' => 'Posledná aktualizácia zoznamu zamestnancov',
		'search-main-page-title' => 'Hlavná stránka',
		'search-invalid-string' => 'Zadaný reťazec je príliš krátky alebo neplatný.',

		'search-emplist-empl'   => 'zamestnanec',
		'search-emplist-worker' => 'Pracovník',
		'search-emplist-pos'    => 'Pracovná pozícia',
		'search-emplist-room'   => 'Miestnosť',
		'search-emplist-ext'    => 'Klapka',
		'search-emplist-email'  => 'E-mail',

		'search-not-satisfied-try-google' => 'Ak nie ste s výsledkami vyhľadávania spokojní, skúste prehľadať naše webové sídlo prostredníctvom vyhľadávača Google',
		'search-note-global-search' => 'vyhľadávanie bude vykonané v rámci celého webového sídla fakulty…',
		'search-use-google' => 'Hľadať prostredníctvom Google',
	);

	if (isSet($translation))
		foreach ($translation as $key => $value)
			if (isSet($searchTexts[$key]))
				$searchTexts[$key] = $value;
}

?>