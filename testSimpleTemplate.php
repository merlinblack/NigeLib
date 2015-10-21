<?php
include( 'autoloader.php' );

use NigeLib\SimpleTemplate;

$main = new SimpleTemplate( 'main.tpl' );

$main->title = 'Motorbike clothing for sale';

$main->items = new SimpleTemplate( null, '
<ul>
 <?php foreach( $itemlist as $item=>$price ): ?>
  <?=$itemlist_tpl->render( compact( "item", "price" ) ) ?>
 <?php endforeach; ?>
</ul>' );

$main->items->itemlist = array( 'Boots' => 230, 'Jackets' => 100, 'Gloves' => 50, 'Helmets' => 250 );

$main->items->itemlist_tpl = new SimpleTemplate( null, '<li><?=$item?> - from $<?=number_format($price,2)?></li>' );

echo $main;
