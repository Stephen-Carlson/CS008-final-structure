
<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');
// break url into an array- get filename
$pathparts = pathinfo($phpSelf);

?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        
        <title>The Shtick</title>
        
        <meta charset ='utf-8'>
        <meta name ="author" content ="Stephen Carlson, Austin Lee, Henry Von Hagke">
        <meta name ="description" content="Offical Website for The Shtick">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="css/band.css" type="text/css" media="screen">       
    <?php
        $debug = false;

        if (isset($_GET["debug"])) {
            $debug = True;
        }


        //PATH SETUP
$domain = '//';

$server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

$domain .= $server;

        if ($debug) {
            print'<p> php Self: ' . $phpself;
            print'<pdomain: ' . $domain;
            print'<p>Path Parts<pre>';
            print_r($path_parts);
            print'</pre></p>';
           }

        print PHP_EOL . '<!-- Include libraries-->' . PHP_EOL;

        require_once 'lib/security.php';
        
        include_once 'lib/validation-functions.php';

        include_once 'lib/mail-message.php';
        print PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
        ?>
    </head>
    
    <!-- ######################## BODY SECTION ##################/# -->
    <?php
    print '<body id="' . $pathparts['filename'] . '">' . PHP_EOL;
    include('head.php');
    print PHP_EOL;
    include('nav.php');
    print PHP_EOL;
    
    if ($debug) {
        print'<p>DEBUG MODE IS ON</p>';
    }
    
    print'<!-- End of top.php-->';
    ?>
    <!-- ######################## MAIN SECTION ##################/# -->

