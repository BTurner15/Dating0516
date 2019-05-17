<?php
/* Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating III Assignment: incorporate classes
 * file: index.php  is the default landing page, defines various routes
 * date: Thursday, May 15 2019
*/
ini_set('display_errors', 1);
error_reporting(E_ALL);


//Require auto-loads
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

//Start a session
session_start();

$f3 = Base::instance();
/*
//Turn on Fat-Free error reporting
set_exception_handler(function($obj) use($f3){
    $f3->error(500,$obj->getmessage(),$obj->gettrace());
});
set_error_handler(function($code,$text) use($f3)
{
    if (error_reporting())
    {
        $f3->error(500,$text);
    }
});
*/
$f3->set('DEBUG', 3);


$f3->set('genders', array('Male', 'Female'));
$f3->set('seekSexs', array('Male', 'Female'));
/* Establish our data structures */
$f3->set('states', array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
    'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho',
    'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine',
    'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri',
    'Montana', 'Nebraska', 'Nevada', 'New Hampshire','New Jersey', 'New Mexico',
    'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon',
    'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas',
    'Utah', 'Vermont', 'Virgina', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming' ));

$f3->set('states_ABBR', array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
    'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS',
    'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA',
    'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY' ));

$f3->set('outdoorInterests', array('hiking', 'walking', 'biking', 'climbing', 'swimming', 'collecting'));
$f3->set('indoorInterests', array('tv', 'puzzles', 'movies', 'reading', 'cooking', 'playing cards', 'board games', 'video games'));

//Define a default route
$f3->route('GET /', function(){
    //don't want any lingering session information
    $_SESSION = array();

    //display landing page Template, which POSTS to personal information
    $view = new Template();
    echo $view->render('views/home.html');

});
//Define a personal information route
$f3->route('GET|POST /perinfo', function($f3) {
    //Display personal information, upon completion REROUTES to profile
    //If form has been submitted, validate
    print_r($_POST);
    if(!empty($_POST)) {
        //Get data from form
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $age= $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];

        //Add data to hive
        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);

        if (validPerinfoForm()) {

            //Write data to Session
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['gender'] = $_POST['gender'];
            $_SESSION['phone'] = $_POST['phone'];
            $f3->reroute('/profile');
        }
    }
    //Display personal information, until REROUTED to  profile in above
    print_r($_POST);
    $view = new Template();
    echo $view->render('views/perinfo.html');
});

//Define a profile route
$f3->route('GET|POST /profile', function($f3) {
    //Display profile information, upon completion REROUTES to interests
    //If form has been submitted, validate

    if(!empty($_POST)) {
        //Get data from form
        $email = $_POST['email'];
        $resState = $_POST['resState'];
        $seekSex = $_POST['seekSex'];
        $bio = $_POST['bio'];

        //Add data to hive
        $f3->set('email', $email);
        $f3->set('resState', $resState);
        $f3->set('seekSex', $seekSex);
        $f3->set('bio', $bio);

        //echo "<br>".validEmail($f3->get('email')."<br>");

        if (validProfileForm()) {
            //Write data to Session
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['resState'] = $_POST['resState'];
            $_SESSION['seekSex'] = $_POST['seekSex'];
            $_SESSION['bio'] = $_POST['bio'];

            $f3->reroute('/interests');
        }
    }
    //Display profile, until REROUTED to  interests in above
    $view = new Template();
    echo $view->render('views/profile.html');
});

//Define a interests route
$f3->route('GET|POST /interests', function($f3) {
    $_SESSION['indoor'] = array();
    $_SESSION['outdoor'] = array();

    if(!empty($_POST)) {
        //Display interests, until REROUTED to summary
        //Get data from form
        $indoor = $_POST['indoor'];
        $outdoor = $_POST['outdoor'];
        //Add data to hive
        $f3->set('indoor', $indoor);
        $f3->set('outdoor', $outdoor);

        if (validInterestsForm()) {
            //Write data to Session
            $_SESSION['indoor'] = $_POST['indoor'];
            $_SESSION['outdoor'] = $_POST['outdoor'];
            $f3->reroute('/summary');
        }
    }
    $view = new Template();
    echo $view->render('views/interests.html');
});

//Define a summary route
$f3->route('GET|POST /summary', function($f3) {
    /*
     * want to pause here and ensure that we have not been spoofed with indoor & outdoor interests arrays
     */
    //save the data gathered in interests
    $indoor = $_POST['indoor'];
    $freshIndoor = array();
    $numElements = count($indoor);
    $ctr = -1;
    for ($i = 0; $i < $numElements; $i++) {
        if(validIndoor($indoor[$i]))
        {
            $ctr++;
            $freshIndoor[$ctr] = $indoor[$i];
        }
        else{
            //skip it
        }
    }
    $f3->set('indoor', $freshIndoor);
    $_SESSION['indoor'] = $freshIndoor;

    $outdoor = $_POST['outdoor'];
    $freshOutdoor = array();
    $numElements = count($outdoor);
    $ctr = -1;
    for ($i = 0; $i < $numElements; $i++) {
        if(validOutdoor($outdoor[$i]))
        {
            $ctr++;
            $freshOutdoor[$ctr] = $outdoor[$i];
        }
        else{
            //skip it
        }
    }
    $f3->set('outdoor', $freshOutdoor);
    $_SESSION['outdoor'] = $freshOutdoor;
    //we have the two sets of allowable indoor and outdoor interests available in
    //indoorInterests and outdoorInterests.

    if(isset($_SESSION['indoor']))
    {
        $_SESSION['indoor'] = implode(", ", $freshIndoor);
    }

    if(isset($_SESSION['outdoor']))
    {
        $_SESSION['outdoor'] = implode(", ", $freshOutdoor);
    }

    //Display summary, which concludes Dating II
    $view = new Template();
    echo $view->render('views/summary.html');
});
//Run fat free
$f3->run();