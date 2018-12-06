<?php
include 'top.php';

print PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
$states = array('Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinios','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachesetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','Wisconsin','West Virginia','Wyoming', 'Outside US');


$contact='All Emails';
$findGoogle = true;
$findSpotify = false;
$findSoundcloud = false;
$findOther = false;
$writeMessage = '';
$firstName - '';
$email = '';
$stateName = 'Alabama';

print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;

$emailERROR = false;
$contactERROR = false;
$findERROR = false;
$totalChecked = 0;
$writeMessageERROR = false;
$firstNameERROR = false;
$stateNameERROR = false;

print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;

$errorMsg = array();


$mailed = false;

print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;

if (isset($_POST["btnSubmit"])) {
    
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;

    // the url for this form
    $thisURL = $domain . $phpSelf;

    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg .= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data  -->' . PHP_EOL;
    
    $contact = htmlentities($_POST['radAge'], ENT_QUOTES, "UTF-8");
    $writeMessage = htmlentities($_POST["txtwriteMessage"], ENT_QUOTES, "UTF-8");
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $stateName = htmlentities($_POST["lstState"], ENT_QUOTES, "UTF-8");



  
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    
    if($contact!= "All Emails" AND $contact != "Limited Emails" AND $contact != "No Emails"){
        $errormsg[] = "Please select your communication prefrences";
        $contactERROR = true;
    }
    if (isset($_POST["chkfindGoogle"])) {
        $findGoogle = true;
        $totalChecked ++;
    } else {
        $findGoogle = false;
    }
    if (isset($_POST["chkfindSpotify"])) {
        $findSpotify = true;
        $totalChecked ++;
    } else {
        $findSpotify = false;
    }
    if (isset($_POST["chkfindSoundcloud"])) {
        $findSoundcloud = true;
        $totalChecked ++;
    } else {
        $findSoundcloud = false;
    }
    if (isset($_POST["chkfindOther"])) {
        $findOther = true;
        $totalChecked ++;
    } else {
        $findOther = false;
    }
    if ($totalChecked < 1) {
        $errorMsg[] = "Please Select how you found us!";
        $contactERROR = true;
    }
    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphanum($firstName)) {
        $errorMsg[] = "Your First Name appears to have extra characters.";
        $firstNameERROR = true;
    }
    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }
    if($stateName == ""){
        $errorMsg[] = "Please select your state";
        $stateNameERROR = true;
    }
    if($writeMessage != ''){
        if(!verifyAlphaNum($writeMessage)){
            $errorMsg[] = "your comments seem to have extra characters";
            $writeMessageERROR = true;
        }
    }
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
       
    if (!$errorMsg) {
        if ($debug)
            print '<p>Form is valid</p>';

        
        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
        
        $dataRecord = [];

       
        $dataRecord[] = $contact;
        $dataRecord[] = $findGoogle;
        $dataRecord[] = $findSpotify;
        $dataRecord[] = $findSoundcloud;
        $dataRecord[] = $findOther;
        $dataRecord[] = $firstName;
        $dataRecord[] = $writeMessage;
        $dataRecord[]= $stateName;
        $dataRecord[] = $email;

        // Setup CSV File
        $myFolder = 'data/';
        $myFileName = 'join';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;
        if ($debug) print PHP_EOL . '<p>Filename is ' . $filename;

        $file = fopen($filename, 'a');
        fputcsv($file, $dataRecord);
        fclose($file);

        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
       
        $message = '<h2>Your Information.</h2><p>Thanks for keeping in touch! look forward to a show near you soon!</p><p>Love,</p><p>The Schtick</p>';

        foreach ($_POST as $htmlName => $value) {
            $message .= '<p>';
            // breaks up the form names in words
            $camelCase = preg_split('/(?=[A-Z]/', substr($htmlName, 3));

            foreach ($camelCase as $oneword) {
                $message .= $oneword . '';
            }

            $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
        }


        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;
        
        $to = $email;
        $cc = '';
        $bcc = '';
        $from = 'The Schtick<outreach@theschtick.com>'
                . '>';
        // subject of mail should make sense for your form
        $subject = 'Registration';
        $mailed = sendmail($to, $cc, $bcc, $from, $subject, $message);
    }
    // end form is valid     
}   // ends if form was submitted.

print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;

?>       
<main>     
    <article>
        <?php
        
    print PHP_EOL . '<!-- SECTION 3a  -->' . PHP_EOL;
        

        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
            print '<h2>Thank you for providing your information.</h2>';

            print '<p>For your records a copy of this data has ';
            if (!$mailed) {
                print "not";
            }



            print 'been sent:</p>';
            print '<p>To: ' . $email . '</p>';
            print($message);
        } else {
            print '<h2>Let Us Know!</h2>';
            print '<p class="form-heading">Your information will greatly help us with our research.</p>';

            //####################################
            //
        print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;
           

            if ($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
                print '<ol>' . PHP_EOL;
                foreach ($errorMsg as $err) {
                    print '<li>' . $err . '</li>' . PHP_EOL;
                }
                print '</ol>' . PHP_EOL;
                print '</div>' . PHP_EOL;
            }
            
        print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
            
            ?>    
            <form action = "<?php print $phpSelf; ?>"
                  id = "frmRegister"
                  method = "post">

                <fieldset class = "contact">
                    <legend>Contact Information</legend>
                    <p>
                    <label class = "required" for = "txtEmail">Email</label>
                    <input 
                    <?php if ($emailERROR) print 'class="mistake"'; ?>
                        id = "txtEmail"     
                        maxlength = "45"
                        name = "txtEmail"
                        onfocus = "this.select()"
                        placeholder = "Enter your email address"
                        tabindex = "10"
                        type = "text"
                        value = "<?php print $email; ?>"
                        >
                    </p>
                    <p>
                    </fieldset>
                    <label class = "required" for="txtFirstName">First Name</label>
                    <input autofocus
                    <?php if ($firstNameERROR) print 'class = "mistake"'; ?>
                           id = "txtFirstName"
                           maxlength = "45"
                           name = "txtFirstName"
                           onfocus= "this.select()"
                           placeholder ="Enter your First Name"
                           tabindex="20"
                           type="text"
                           value="<?php print $firstName; ?>"
                           >
                   <p>
                   <label class = "required" for="txtWriteMessage">Write us a Message!</label>
                   <textarea <?php if($writeMessageERROR) print 'class="mistake"'; ?>
                       id="txtWriteMessage"
                       name="txtWriteMessage"
                       onfocus="this.select()"
                       placeholder="Got Comments? Write 'em here!"
                       tabindex="30"><?php print $writeMessage; ?></textarea>
                       
                    </p>
                    <fieldset class = "checkbox <?php if ($findERROR) print 'mistake';?>">
                                <legend>How did you find us?</legend>
                                <p>
                                    <label class="check-field">
                                        <input <?php if($findGoogle) print "checked"; ?>
                                            id="chkfindGoogle"
                                            name="chkfindGoogle"
                                            tabindex="40"
                                            type="checkbox"
                                            value="Google">Google</label>
                            </p>
                            <p>
                                    <label class="check-field">
                                        <input <?php if($findSpotify) print "checked"; ?>
                                            id="chkfindSpotify"
                                            name="chkfindSpotify"
                                            tabindex="50"
                                            type="checkbox"
                                            value="Spotify">Spotify</label>
                            </p>
                            <p>
                                    <label class="check-field">
                                        <input <?php if($findSoundcloud) print "checked"; ?>
                                            id="chkfindSoundcloud"
                                            name="chkfindSoundcloud"
                                            tabindex="60"
                                            type="checkbox"
                                            value="Soundcloud">Soundcloud</label>
                            </p>
                            <p>
                                    <label class="check-field">
                                        <input <?php if($findOther) print "checked"; ?>
                                            id="chkfindOther"
                                            name="chkfindOther"
                                            tabindex="70"
                                            type="checkbox"
                                            value="Other">Other</label>
                            </p>
                    </fieldset>
                   <fieldset class="listbox <?php if($stateNameERROR) print'mistake';?>">
           
                       <legend>State</legend>
                       <select id="lstState"
                               name="lstState"
                               tabindex="80">
                                   <?php
                                   foreach($states as $state){ ?>
                           <option value="<?php print $state ?>"><?= $state ?></option>
                           <?php
                                   } ?>
                                   
                       </select>
                   
                   </fieldset>
                      
                    <fieldset class="radio <?php if($contactERROR) print 'mistake';?>">
                        <legend>How would you like to be contacted?</legend>
                        <p>
                            <label class="radio-field">
                                <input type="radio"
                                       id="radContactAll"
                                       name="radContact"
                                       value="All Emails"
                                       tabindex="90"
                                       <?php if($contact == "All Emails") echo 'checked= "checked"'; ?>>All Emails</label>
                                       </p>
                        <p>
                            <label class="radio-field">
                                <input type="radio"
                                       id="radContactLimit"
                                       name="radContact"
                                       value="Limited Emails"
                                       tabindex="100"
                                       <?php if($contact =="Limited Emails") echo 'checked= "checked"'; ?>>Limited Emails</label>
                                       </p>
                        <p>
                            <label class="radio-field">
                                <input type="radio"
                                       id="radContactNone"
                                       name="radContact"
                                       value="No Emails"
                                       tabindex="110"
                                       <?php if($contact == "No Emails") echo 'checked= "checked"'; ?>>No Emails</label>
                                       </p>    
                                       
                    
                    
                    











                    
                    
               
                </fieldset> <!-- ends contact -->

                <fieldset class="buttons">
                    <legend></legend>
                    <input class = "button" id = "btnSubmit" name = "btnSubmit" tabindex = "900" type = "submit" value = "Submit" >

                </fieldset> <!-- ends buttons -->
            </form>     
            <?php
        } // ends body submit
        ?>
    </article>     
</main>

<?php include 'footer.php'; ?>

</body>     
</html>