<?php

require 'class-linkshorter.php';

$a = new linkshorter("adfly", "http://google.com");

echo $a->getLink();
