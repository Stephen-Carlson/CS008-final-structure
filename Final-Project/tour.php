<?php
include 'top.php';
/* Open Weather data info */
$debug = false;
if(isset($_GET["debug"])){
    $debug = true;
}
$folder = 'data/';
$filetitle = 'tourdates';
$ext = '.csv';
$filename = $folder . $filetitle . $ext;
        
if ($debug){print '<p?filename is ' . $filename;}
$file=fopen($filename, 'r');

/*if(debug){
    if($file){
        print '<p> File Opened Success </p>';
    }else{
        print '<p> File Open Fail';
    }
}*/
if($file){
    if($debug){ print '<p> Begin reading data into an array </p>';}
    /* read the header row, copy the line for each header row you have */
    $headers[] = fgetcsv($file);
    if($debug){
        print '<p> Finished reading headers </p>';
        print '<p> My header Array</p><pre>';
        print_r($headers);
        print '</pre>';
    }
    /* Read all the Data*/
    while(!feof($file)){
        $tours[] = fgetcsv($file);
    }
    if ($debug){
        print '<p> Finished Reading Headers </p>';
        print '<p> My Data Array</p><pre>';
        print_r($tours);
        print '</pre></p>'. PHP_EOL;
    }
} /* ends if file was opened */
fclose($file);
?>
        <article id = "content">
            <h1 id = tour><img src='images/tourTitle.png'></h1>
            <table id = tour>
            <?php
            foreach($headers as $header){
                print '<tr>';
                print '<th>' . $header[0] . '</th>';
                print '<th>' . $header[1] . '</th>';
                print '<th>' . $header[2] . '</th>';
                print '<th>' . $header[3] . '</th>';
                print '</tr>';
            };
            foreach($tours as $tour){
                print '<tr>';
                print '<td>' . $tour[0] . '</td>';
                print '<td>' . $tour[1] . '</td>';
                print '<td>' . $tour[2] . '</td>';
                print '<td>' . $tour[3] . '</td>'; PHP_EOL;
                print '</tr>';
                
            }
            ?>
             </table>
        </article>
        <?php
        include 'footer.php';
        ?>
</body>
</html>