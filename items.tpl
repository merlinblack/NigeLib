<ul>
<?php foreach( $itemlist as $item=>$price ): ?>
 <li><?=$item?> - from $<?=number_format($price,2)?></li>
<?php endforeach; ?>
</ul>
