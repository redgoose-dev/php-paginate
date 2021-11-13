<?php
require '../src/Paginate.php';

use redgoose\Paginate;

//header('Content-Type: text/plain');
error_reporting(E_ALL & ~E_NOTICE);


// create instance
$paginate = new Paginate((object)[
  'total' => 500,
  'page' => isset($_GET['page']) ? (int)$_GET['page'] : 1,
  'params' => [ 'foo' => 'bar' ]
]);
print_r($paginate);


// create elements
$elements = $paginate->createElements(['apple', 'banana']);
//echo "<nav>";
//echo $elements;
//echo "</nav>";


// create object
//$object = $paginate->createObject();
//print_r($object);


// update paginate
//print_r($paginate);
$paginate->update((object)[
  'total' => 100,
  'size' => 3,
  'scale' => 5,
  'startPage' => 1,
  'params' => [
    'apple' => 'red',
    'banana' => 'yellow'
  ],
]);
print_r($paginate);

$elements = $paginate->createElements(['after-class']);
echo "<nav>";
echo $elements;
echo "</nav>";