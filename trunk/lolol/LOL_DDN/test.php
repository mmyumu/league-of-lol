<?php
	require_once 'Log.php';
	require_once 'Fight.php';
	require_once 'Team.php';

	#DEBUT_LISTE_DES_CHAMPIONS#
	require_once 'GarenCrownguard.php';
	require_once 'Udyr.php';
	require_once 'JannaWindforce.php';
	require_once 'AnnieHastur.php';
	#FIN_LISTE_DES_CHAMPIONS#

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

	$LOGGER->debug('Ajout du premier champion GarenCrownguard � l\'�quipe A');
	$teamA->addChampion(new GarenCrownguard());
	$LOGGER->debug('Ajout du second champion AnnieHastur � l\'�quipe A');
	$teamA->addChampion(new AnnieHastur());

	$LOGGER->debug('Ajout du premier champion Udyr � l\'�quipe B');
	$teamB->addChampion(new Udyr());
	$LOGGER->debug('Ajout du second champion JannaWindforce � l\'�quipe B');
	$teamB->addChampion(new JannaWindforce());

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