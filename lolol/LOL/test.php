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

	$LOGGER->debug('Début du test');

	$LOGGER->debug('Création de l\'équipe A');
	$teamA = new Team('A');
	$teamA->setLogger($LOGGER);

	$LOGGER->debug('Création de l\'équipe B');
	$teamB = new Team('B');
	$teamB->setLogger($LOGGER);

	$LOGGER->debug('Ajout du premier champion GarenCrownguard à l\'équipe A');
	$teamA->addChampion(new GarenCrownguard());
	$LOGGER->debug('Ajout du second champion AnnieHastur à l\'équipe A');
	$teamA->addChampion(new AnnieHastur());

	$LOGGER->debug('Ajout du premier champion Udyr à l\'équipe B');
	$teamB->addChampion(new Udyr());
	$LOGGER->debug('Ajout du second champion JannaWindforce à l\'équipe B');
	$teamB->addChampion(new JannaWindforce());

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