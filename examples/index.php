<?php
$time = microtime(true);

require '../vendor/autoload.php';

$armory = new Armory('EU', 'fr_FR');
$armory->cacheDirectory = '../cache';

// Get guild object, now we can call all function implemented for a guild
$guild = $armory->refreshNext()->getGuild('Trinity', 'Archimonde');
var_dump($guild->members[0]['character']->race);
//var_dump($guild->isFromCache());
//var_dump($guild);


$time =  microtime(true) - $time;
echo $time;