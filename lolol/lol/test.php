<?php
	require_once 'Log.php';
	require_once 'Fight.php';
	require_once 'Team.php';
	require_once 'Tanky.php';
	require_once 'Mage.php';

	// test
	$LOGGER = new Log();
	$LOGGER->purge();

	$LOGGER->debug('Début du test');

	$LOGGER->debug('Création de l\'équipe A');
	$teamA = new Team('A');
	$teamA->setLogger($LOGGER);

	$LOGGER->debug('Création de l\'équipe B');
	$teamB = new Team('B');
	$teamB->setLogger($LOGGER);

	$LOGGER->debug('Ajout du premier champion Tanky à l\'équipe A');
	$teamA->addChampion(new Tanky());
	$LOGGER->debug('Ajout du second champion Mage à l\'équipe A');
	$teamA->addChampion(new Mage());

	$LOGGER->debug('Ajout du premier champion Mage à l\'équipe B');
	$teamB->addChampion(new Mage());
	$LOGGER->debug('Ajout du second champion Tanky à l\'équipe B');
	$teamB->addChampion(new Tanky());

	$LOGGER->debug('Initialisation de l\'arène');
	$f = new Fight($teamA, $teamB);
	$f->setLogger($LOGGER);

	$LOGGER->debug('Début du combat');
	$f->computeFight();

	$LOGGER->debug('Résultat du combat');
	$f->displayResult();

	$LOGGER->debug('Fin du test');

	$LOGGER->toScreen();
?>