<?php
// Note: The assets directory is not included in the git repo to keep the size
// down. However this serves to demonstrate the Headlinks class.
require('autoloader.php');
$headlinks = new NigeLib\Headlinks(
    array(
        '$jquery-ui' => array( 'assets/jquery/ui/jquery-ui.js' ),
        'assets/jquery/ui/jquery-ui.js' => array( 'assets/jquery/jquery.js', 'assets/jquery/themes/base/jquery-ui.css' ),
        'assets/jquery/themes/base/jquery-ui.css' => array( 'assets/jquery/ui/jquery-ui.js' ),
        'assets/test.1.2.3.js' => array( 'assets/jquery/jquery.js', 'assets/jquery/ui/jquery-ui.js' ),
    )
);
$headlinks->addFile( 'assets/moment.js' );
$headlinks->addFile( '$jquery-ui' );
$headlinks->addFile( 'assets/style.css' );
$headlinks->addFile( 'assets/test.1.2.3.js' );
?>
<!doctype html>
<html>
 <head>
  <?php echo $headlinks->getLinks(); ?>
 </head>
 <body>
  <h1>This is a test</h1>
  <a href='test_headlinks.php'>Load again</a>
 </body>
</html>
