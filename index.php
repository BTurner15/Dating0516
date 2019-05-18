<?php
/* Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating III Assignment: incorporate classes
 * file: index.php  is the default landing page, defines various routes
 * date: Friday, May 17 2019
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
    //If form has been submitted, validate. The wrinkle with Dating III is to
    //"
    //- instantiate the appropriate class -- Member or PremiumMember -- depending on whether or not the checkbox was selected
    //- save the form data to the appropriate member object
    //- store the member object in a session variable
    //"
    if(!empty($_POST)) {
        //Get data from form
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $age= $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $premium = $_POST['premium'];

        //Add data to hive
        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);
        $f3->set('premium', $premium);
        if (validPerinfoForm()) {

            //Write data to Session
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['gender'] = $_POST['gender'];
            $_SESSION['phone'] = $_POST['phone'];
            $_SESSION['premium'] = $_POST['premium'];
            //now fold in the classes...parse on PremiumMember checkbox, if we are in a  mode with
            //an "ordinary" Member then !premium will be true
            $isPremium = $f3->get('premium');
            if(!$isPremium)
            {
                $member = new Member($_SESSION['fname'],$_SESSION['lname'],$_SESSION['age'],
                                     $_SESSION['gender'],$_SESSION['phone']);
            }
            else
            {
                $member = new PremiumMember($_SESSION['fname'],$_SESSION['lname'],$_SESSION['age'],
                                            $_SESSION['gender'],$_SESSION['phone']);
            }
            //store the individual either way
            $_SESSION['member'] = $member;
            //we are only going to store in the $_SESSION[] NOT $f3->set('member', $member);

            $f3->reroute('/profile');
        }
    }
    //Display personal information, until REROUTED to  profile in above
    $view = new Template();
    echo $view->render('views/perinfo.html');
});

//Define a profile route
$f3->route('GET|POST /profile', function($f3) {
    //Display profile information, upon completion REROUTES to interests
    //if and only if (IFF - I always liked this acronym!) the individual is a PremiumMember
    //make sure that se have a clean slate here
    $_SESSION['email'] = null;
    $_SESSION['resState'] = null;
    $_SESSION['seekSex'] = null;
    $_SESSION['bio'] = null;

    if(!empty($_POST)) {
        //Add data to hive
        $email = $_POST['email'];
        $resState = $_POST['resState'];
        $seekSex = $_POST['seekSex'];
        $bio = $_POST['bio'];

        $f3->set('email', $email);
        $f3->set('resState', $resState);
        $f3->set('seekSex', $seekSex);
        $f3->set('bio', $bio);
         if (validProfileForm()) {

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['resState'] = $_POST['resState'];
            $_SESSION['seekSex'] = $_POST['seekSex'];
            $_SESSION['bio'] = $_POST['bio'];

            //we must remember Dating III is all about objects.
            //USED to store individual data items in the $_SESSION[] data structure by name.
            //continuing to do so is redundant
            $_SESSION['member']->setEmail($_POST['email']);
            $_SESSION['member']->setState($_POST['state']);
            $_SESSION['member']->setSeeking($_POST['seekSex']);
            $_SESSION['member']->setBio($_POST['bio']);

            //Ok, the time has come to decide whether to display the interest to the Member (or not)
            if(!isset($_SESSION['premium']))
            {
                $f3->reroute('/summary');
            }
            else
            {
                $f3->reroute('/interests');
            }
        }
    }
    //Display profile, until REROUTED to  interests in above
    $view = new Template();
    echo $view->render('views/profile.html');
});

//Define a interests route. We will only get here via reroute from profile.html
$f3->route('GET|POST /interests', function($f3) {
    $_SESSION['indoor'] = array();
    $_SESSION['outdoor'] = array();

    if(!empty($_POST)) {
        //Display interests, until REROUTED to summary
        //form valid?
        if (validInterestsForm()) {
            //Write data to Session "member" object
            $_SESSION['member']->setIndoor($_POST['indoor']);
            $_SESSION['member']->setOutdoor = $_POST['outdoor'];

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
    //save the data gathered in interests IF A PREMIUM MEMBER
    print_r($_SESSION['member']);

    if(isset($_SESSION['premium'])) {
        $indoor = $_POST['indoor'];
        $freshIndoor = array();
        $numElements = count($indoor);
        $ctr = -1;
        for ($i = 0; $i < $numElements; $i++) {
            if (validIndoor($indoor[$i])) {
                $ctr++;
                $freshIndoor[$ctr] = $indoor[$i];
            } else {
                //skip it
            }
        }
        $_SESSION['indoor'] = $freshIndoor;

        $outdoor = $_POST['outdoor'];
        $freshOutdoor = array();
        $numElements = count($outdoor);
        $ctr = -1;
        for ($i = 0; $i < $numElements; $i++) {
            if (validOutdoor($outdoor[$i])) {
                $ctr++;
                $freshOutdoor[$ctr] = $outdoor[$i];
            } else {
                //skip it
            }
        }
        $_SESSION['outdoor'] = $freshOutdoor;
        //we have the two sets of allowable interests available in the two arrays loaded at
        //the beginning, our buddies indoorInterests[] and outdoorInterests[]
        //just waiting for us! Yeah!

        if (isset($_SESSION['indoor'])) {
            $_SESSION['member']->setIndoor(implode(", ", $_SESSION['indoor']));
        }

        if (isset($_SESSION['outdoor'])) {
            $_SESSION['member']->setOutdoor(implode(", ", $_SESSION['outdoor']));
        }
    }
    //Display summary, which concludes Dating III
    $view = new Template();
    echo $view->render('views/summary.html');
});
//Run fat free
$f3->run();