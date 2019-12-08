<?php
require 'src/Paginate.php';

use redgoose\Paginate;

// create instance
$paginate = new Paginate(500, 12);

// create elements
$elements = $paginate->createElements();
//echo $elements;

// create object
$object = $paginate->createObject();
