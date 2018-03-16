<?php
/**
*Copyright (C) 2018  Gabboxl
*This file is part of LinkShorter.
*
*This program (LinkShorter) is free software: you can redistribute it and/or modify
*    it under the terms of the GNU Affero General Public License as published
*    by the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program (LinkShorter) is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU Affero General Public License for more details.
*
*    You should have received a copy of the GNU Affero General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/



require 'class-linkshorter.php';

$a = new linkshorter("adfly", "https://google.com");

echo "Link: ".$a->getLink()."<br>";
echo "Errors: ".$a->getError()."<br>";
echo "Warnings: ".$a->getWarning();
