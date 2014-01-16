<?php
	require_once 'Log.php';
	require_once 'Fight.php';
	require_once 'Team.php';
	require_once 'Tanky.php';
	require_once 'Mage.php';

	// test
	$LOGGER = new Log();
	$LOGGER->purge();

	$LOGGER->debug('D�but du test');

	$LOGGER->debug('Cr�ation de l\'�quipe A');
	$teamA = new Team('A');
	$teamA->setLogger($LOGGER);

	$LOGGER->debug('Cr�ation de l\'�quipe B');
	$teamB = new Team('B');
	$teamB->setLogger($LOGGER);

	$LOGGER->debug('Ajout du premier champion Tanky � l\'�quipe A');
	$teamA->addChampion(new Tanky());
	$LOGGER->debug('Ajout du second champion Mage � l\'�quipe A');
	$teamA->addChampion(new Mage());

	$LOGGER->debug('Ajout du premier champion Mage � l\'�quipe B');
	$teamB->addChampion(new Mage());
	$LOGGER->debug('Ajout du second champion Tanky � l\'�quipe B');
	$teamB->addChampion(new Tanky());

	$LOGGER->debug('Initialisation de l\'ar�ne');
	$f = new Fight($teamA, $teamB);
	$f->setLogger($LOGGER);

	$LOGGER->debug('D�but du combat');
	$f->computeFight();

	$LOGGER->debug('R�sultat du combat');
	$f->displayResult();

	$LOGGER->debug('Fin du test');

	$LOGGER->toScreen();
?>