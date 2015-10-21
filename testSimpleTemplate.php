<?php
include( 'autoloader.php' );

use NigeLib\SimpleTemplate;

$main = new SimpleTemplate( 'main.tpl' );

$main->title = 'Motorbike clothing for sale';
$main->items = new SimpleTemplate( 'items.tpl' );
$main->items->itemlist = array( 'Boots' => 230, 'Jackets' => 100, 'Gloves' => 50, 'Helmets' => 250 );

echo $main;
