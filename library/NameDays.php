<?php

class NameDays
{
	private static $nameDays = array(
		array(	// Jan
			null, array('Alexandra', 'Karina', 'Karin'), array('Daniela', 'Radmila'), array('Drahoslav', 'Titus'), 'Andrea', 'Antónia', 'Bohuslava', 'Severín', array('Alex', 'Alexej'), array('Dáša', 'Dalimil'), 'Malvína', array('Ernest', 'Ernestína'), 'Rastislav', array('Radovan', 'Radovana'), 'Dobroslav', 'Kristína', 'Nataša', 'Bohdana', array('Drahomíra', 'Mário', 'Sára'), array('Dalibor', 'Sebastián'), 'Vincent', array('Zora', 'Zoran', 'Zorana'), 'Miloš', array('Timotea', 'Timotej'), 'Gejza', 'Tamara', 'Bohuš', array('Alfonz', 'Alfonzia'), 'Gašpar', array('Ema', 'Emma'), 'Emil'),
		array(	// Feb
			array('Táňa', 'Tatiana'), array('Erik', 'Erika'), 'Blažej', array('Nika', 'Verona', 'Veronika'), 'Agáta', array('Dorota', 'Dorisa'), 'Vanda', 'Zoja', array('Zdeno', 'Zdenko'), 'Gabriela', array('Dezider', 'Dezidera'), array('Perla', 'Zoro'), 'Arpád', 'Valentín', array('Pravoslav', 'Pravoslava'), array('Ida', 'Liana'), 'Miloslava', array('Jaromír', 'Jaromíra'), 'Vlasta', array('Lívia', 'Alma', 'Aladár'), 'Eleonóra', 'Etela', array('Roman', 'Romana'), array('Matej', 'Matias'), array('Frederik', 'Frederika'), 'Viktor', 'Alexander', array('Zlata', 'Zlatica'), 'Radomír'),
		array(	// Mar
			'Albín', 'Anežka', array('Bohumil', 'Bohumila'), 'Kazimír', 'Fridrich', array('Radoslav', 'Radoslava'), 'Tomáš', array('Alan', 'Alana'), 'Františka', array('Branislav', 'Bronislav', 'Bruno'), array('Angela', 'Angelika'), 'Gregor', 'Vlastimil', 'Matilda', 'Svetlana', 'Boleslav', 'Ľubica', array('Eduard', 'Eduarda'), 'Jozef', array('Víťazoslav', 'Víťazoslava'), 'Blahoslav', array('Beňadik', 'Beňadikt'), 'Adrián', 'Gabriel', 'Marián', array('Eman', 'Emanuel'), array('Alena', 'Dita'), 'Soňa', 'Miroslav', array('Vieroslava', 'Vieroslav'), 'Benjamín'),
		array(	// Apr
			'Hugo', 'Zita', 'Richard', array('Izidor', 'Izidora'), 'Miroslava', 'Irena', 'Zoltán', 'Albert', 'Milena', 'Igor', array('Július', 'Leo'), 'Estera', 'Aleš', array('Justín', 'Justína'), 'Fedor', array('Dana', 'Danka', 'Danica'), 'Rudolf', array('Valér', 'Erich'), 'Jela', 'Marcel', 'Ervín', 'Slavomír', 'Vojtech', 'Juraj', array('Marek', 'Marko'), 'Jaroslava', 'Jaroslav', 'Jarmila', 'Lea', 'Anastázia'),
		array(	// May
			null, 'Žigmund', array('Galina', 'Timea'), 'Florián', array('Lesana', 'Lesia'), 'Hermína', 'Monika', 'Ingrida', 'Roland', 'Viktória', 'Blažena', 'Pankrác', 'Servác', 'Bonifác', array('Žofia', 'Sofia'), 'Svetozár', array('Aneta', 'Gizela'), 'Viola', 'Gertrúda', array('Bernard', 'Bernarda'), 'Zina', array('Júlia', 'Juliana'), 'Želmíra', 'Ela', array('Urban', 'Vanesa'), 'Dušan', 'Iveta', 'Viliam', array('Vilma', 'Maxim'), 'Ferdinand', array('Petronela', 'Petrana', 'Nela')),
		array(	// Jun
			'Žaneta', array('Xénia', 'Oxana'), array('Karolína', 'Kevin'), 'Lenka', 'Laura', 'Norbert', 'Róbert', 'Medard', 'Stanislava', array('Gréta', 'Margaréta'), 'Dobroslava', 'Zlatko', 'Anton', 'Vasil', 'Vít', array('Blanka', 'Bianka'), 'Adolf', 'Vratislav', 'Alfréd', 'Valéria', array('Alojz', 'Lejla'), array('Paulína', 'Zaira'), 'Sidónia', array('Ján', 'Nino'), array('Tadeáš', 'Olívia'), 'Adriána', array('Ladislav', 'Ladislava'), 'Beáta', array('Peter', 'Pavol', 'Petra'), 'Melánia'),
		array(	// Jul
			'Diana', 'Berta', 'Miloslav', 'Prokop', array('Cyril', 'Metod'), array('Patrik', 'Patrícia'), 'Oliver', 'Ivan', array('Lujza', 'Lukrécia'), 'Amália', 'Milota', 'Nina', 'Margita', 'Kamil', array('Henrich', 'Egon', 'Šarlota'), 'Drahomír', 'Bohuslav', 'Kamila', 'Dušana', array('Iľja', 'Eliáš'), 'Daniel', 'Magdaléna', array('Oľga', 'Lilien'), 'Vladimír', 'Jakub', array('Anna', 'Hana'), 'Božena', 'Krištof', 'Marta', 'Libuša', 'Ignác'),
		array(	// Aug
			'Božidara', 'Gustáv', 'Jerguš', array('Dominik', 'Dominika'), 'Hortenzia', 'Jozefína', 'Štefánia', 'Oskar', 'Ľubomíra', array('Vavrinec', 'Vavro'), 'Zuzana', array('Darina', 'Dárius'), 'Ľubomír', 'Mojmír', 'Marcela', 'Leonard', 'Milica', array('Elena', 'Helena'), 'Lýdia', 'Anabela', array('Jana', 'Janka'), 'Tichomír', 'Filip', 'Bartolomej', 'Ľudovít', 'Samuel', 'Silvia', 'Augustín', array('Nikola', 'Nicole', 'Nikolaj'), 'Ružena', 'Nora'),
		array(	// Sep
			'Drahoslava', array('Linda', 'Rebeka'), 'Belo', 'Rozália', 'Regína', array('Alica', 'Larisa'), 'Marianna', 'Miriama', 'Martina', 'Oleg', 'Bystrík', array('Mária', 'Marika'), array('Ctibor', 'Tobias'), array('Ľudomil', 'Ľudomila'), array('Jolana', 'Melisa'), 'Ľudmila', 'Olympia', 'Eugénia', 'Konštantín', array('Ľuboslav', 'Ľuboslava'), 'Matúš', 'Móric', 'Zdenka', array('Ľuboš', 'Ľubor'), array('Vladislav', 'Vladislava'), 'Edita', array('Cyprián', 'Damián'), 'Václav', array('Michal', 'Michala', 'Michaela'), 'Jarolím'),
		array(	// Oct
			'Arnold', 'Levoslav', 'Stela', 'František', 'Viera', 'Natália', 'Eliška', 'Brigita', 'Dionýz', 'Slavomíra', 'Valentína', array('Maximilián', 'Max'), 'Koloman', 'Boris', 'Terézia', 'Vladimíra', 'Hedviga', 'Lukáš', 'Kristián', 'Vendelín', 'Uršuľa', 'Sergej', 'Alojzia', 'Kvetoslava', array('Dária', 'Aurel'), 'Demeter', 'Sabína', 'Dobromila', array('Klára', 'Klarisa'), array('Šimon', 'Simona'), 'Aurélia'),
		array(	// Nov
			array('Denis', 'Denisa'), null, 'Hubert', array('Karol', 'Jesika'), 'Imrich', 'Renáta', 'René', array('Bohumír', 'Bohumíra'), array('Tea', 'Teodor'), 'Tibor', array('Martin', 'Maroš'), 'Svätopluk', 'Stanislav', 'Irma', 'Leopold', 'Agnesa', 'Klaudia', 'Eugen', array('Alžbeta', 'Liliana'), 'Félix', 'Elvíra', 'Cecília', 'Klement', 'Emília', 'Katarína', 'Kornel', 'Milan', 'Henrieta', 'Vratko', array('Ondrej', 'Andrej')),
		array(	// Dec
			'Edmund', 'Bibiána', 'Oldrich', array('Barbora', 'Barbara'), 'Oto', array('Mikuláš', 'Nikolas'), 'Ambróz', 'Marína', 'Izabela', 'Radúz', 'Hilda', 'Otília', 'Lucia', array('Branislava', 'Bronislava'), 'Ivica', 'Albína', 'Kornélia', array('Sláva', 'Slávka'), 'Judita', 'Dagmara', 'Bohdan', array('Ada', 'Adela'), array('Naďa', 'Nadežda'), array('Adam', 'Eva'), null, 'Štefan', 'Filoména', array('Ivana', 'Ivona'), 'Milada', 'Dávid', 'Silvester')
		);

	public static function getNames($timestamp = null)
	{
		global $designTexts;

		if (null === $timestamp) $timestamp = mktime();

		$day = date('j', $timestamp) - 1;
		$month = date('n', $timestamp) - 1;

		if (!empty(NameDays::$nameDays[$month][$day]))
		{
			$todaysNameDay = NameDays::$nameDays[$month][$day];

			if (is_array($todaysNameDay))
			{
				$more = '';
				$i = false;

				foreach ($todaysNameDay as $todaysName)
				{
					if ($i) $more .= ', '; else $i = true;
					$more .= $todaysName;
				}

				return $designTexts['design-namedays-plural'].
					' <a href="javascript:searchNameDay(\''.
					$more.'\');">'.$more.'</a>';
			}
			else return $designTexts['design-namedays-singular'].
				' <a href="javascript:searchNameDay(\''.
				$todaysNameDay.'\');">'.$todaysNameDay.'</a>';
		}

		return null;
	}
}

?>