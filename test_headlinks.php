<?php
// Note: The access directory is not included in the git repo to keep the size
// down. However this serves to demonstrate the Headlinks class.
require('autoloader.php');
$headlinks = new Headlinks(
    array(
        'assets/jquery/ui/jquery-ui.js' => array( 'assets/jquery/jquery.js' ),
        'assets/test.1.2.3.js' => array( 'assets/jquery/jquery.js', 'assets/jquery/ui/jquery-ui.js' ),
    )
);
$headlinks->addFile( 'assets/moment.js' );
$headlinks->addFile( 'assets/jquery/ui/jquery-ui.js' );
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
