<?php

require './util/gutenbergCrawler.php';

$gut = new guterbergCrawler();
$paragrafos = $gut->getParagrafos();

print_r($paragrafos);