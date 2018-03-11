<?php

require 'class-linkshorter.php';

$a = new linkshorter("adfly", "");

echo $a->getLink();
