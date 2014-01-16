<?php

	// Define the request
	$sReq = '
SELECT
	c.name,
	c.attackDamage,
	c.attackSpeed,
	c.armor,
	c.health,
	c.description,
	a.name,
	a.manaCost,
	a.healthCost,
	a.cooldown,
	a.damage,
	a.cure,
	a.description
FROM
	champion c
	LEFT JOIN ability a ON
		a.champion_idr = c.id
	';

	// Open a connection to the database
	$dblink = mssql_connect(
		'FR-31-02-13-079\SWAT',
		base64_decode("c2E="),
		base64_decode("aXRpZGl0aWQ=")
	);
	$aResults = array();
	if ($dblink !== FALSE) {
		// Connected to base, check base selection
		if (mssql_select_db('h', $dblink) !== FALSE) {
			// Execute the request
			$cur = mssql_query($sReq, $dblink);
			if ($cur !== FALSE) {
				if ($cur !== TRUE) {
					// Loop on the results to fill the array
					$row = mssql_fetch_assoc($cur);
					$iRows = 0;
					while ($row !== FALSE) {
						$aResults[$iRows] = array();
						foreach ($row as $sKey => $sValue) {
							$aResults[$iRows][$sKey] = $sValue;
						}
						$iRows ++;
						$row = mssql_fetch_assoc($cur);
					}
				}
			}
			// Free the recordset
			mssql_free_result($cur);
		}
		mssql_close($dblink);
	}

	// Read the template file
	$templateFile = fopen('Champion_template.php', 'r');
	$sTemplate = '';
	if ($templateFile !== FALSE) {
		while (!feof($templateFile)) {
		  $sTemplate .= fread($templateFile, 8192);
		}
		fclose($templateFile);
	}

	// Read the test file
	$testFile = fopen('test.php', 'r');
	$sTest = '';
	if ($testFile !== FALSE) {
		while (!feof($testFile)) {
		  $sTest .= fread($testFile, 8192);
		}
		fclose($testFile);
	}

/*
	$sReq = '
SELECT
	name,
	attackDamage,
	attackSpeed,
	armor,
	health,
	description
FROM
	champion
	';
*/

	$sRequiresTest = "#DEBUT_LISTE_DES_CHAMPIONS#";

	// Generate each class
	$sPreviousChampion = '';
	foreach ($aResults as $i => $row) {
		$sFileContent = $sTemplate;
		$sClass = preg_replace('[ ]', '', ucwords(preg_replace('[^a-zA-Z]', ' ', $row['name'])));
		if (strcmp($sPreviousChampion, $sClass) == 0) {
			// Same Champion, add ability
		}
		else {
			// Store the abilities for previous champion

			// Manage New Champion
			$sPreviousChampion = $sClass;
			$sFileContent = str_ireplace('#CLASS#', $sClass, $sFileContent);
			$sFileContent = str_ireplace('#DESCRIPTION#', $row['description'], $sFileContent);
			$sFileContent = str_ireplace('#HEALTH#', $row['health'], $sFileContent);
			$sFileContent = str_ireplace('#ATTACK_DAMAGE#', $row['attackDamage'], $sFileContent);
			$sFileContent = str_ireplace('#ARMOR#', $row['armor'], $sFileContent);
			$sFileContent = str_ireplace('#ATTACK_SPEED#', $row['attackSpeed'], $sFileContent);
			$sFileContent = str_ireplace('#NAME#', $row['name'], $sFileContent);
			$sFileName = $sClass . '.php';
			$sRequiresTest .= "\n	require_once '$sClass.php';";
			$rFile = fopen($sFileName, 'w');
			if ($rFile !== FALSE) {
				fwrite($rFile, $sFileContent);
				fclose($rFile);
			}
		}
	}

	// Reprise du fichier de test
	$sDebutTest = substr($sTest, 0, strpos($sTest, '#DEBUT_LISTE_DES_CHAMPIONS#'));
	$sDebutTest .= $sRequiresTest . "\n	";
	$sDebutTest .= substr($sTest, strpos($sTest, '#FIN_LISTE_DES_CHAMPIONS#'), strlen($sTest));

	$sNouveauTestFileName = 'nouveauTest.php';
	$rTestNouveauFile = fopen($sNouveauTestFileName, 'w');
	if ($rTestNouveauFile !== FALSE) {
		fwrite($rTestNouveauFile, $sDebutTest);
		fclose($rTestNouveauFile);
	}

?>