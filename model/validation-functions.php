<?php
/* Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating IIb Assignment: with form validation & sticky forms
 * file: validation-functions.php
 * date: Saturday, May 11 2019
 * "
 * validName() checks to see that a string is all alphabetic
 * validAge() checks to see that an age is numeric and between 18 and 118
 * validPhone() checks to see that a phone number is valid
 * (you can decide what constitutes a “valid” phone number)
 * validEmail() checks to see that an email address is valid
 * validOutdoor() checks each selected outdoor interest against a list of valid options
 * validIndoor() checks each selected indoor interest against a list of valid options
 * "
*/

/* Validate the personal information form
 * @return boolean
 */
function validPerinfoForm()
{
    global $f3;
    global $_SESSION;
    $isValid = true;

    if (!validName($f3->get('fname'))) {

        $isValid = false;
        $f3->set("errors['fname']", "Please enter an alphabetic first name");
    }
    else{
        $_SESSION['fname'] = $_POST['fname'];
    }
    if (!validName($f3->get('lname'))) {

        $isValid = false;
        $f3->set("errors['lname']", "Please enter an alphabetic last name");
    }
    else{
        $_SESSION['lname'] = $_POST['lname'];
    }
    if (!validAge($f3->get('age'))) {

        $isValid = false;
        $f3->set("errors['age']", "Please enter a numeric age in the range 18 to 118");
    }
    else{
        $_SESSION['age'] = $_POST['age'];
    }
    if (!validGender($f3->get('gender'))) {

        $isValid = false;
        $f3->set("errors['gender']", "Please select a gender");
    }
    else{
        $_SESSION['gender'] = $_POST['gender'];
    }
    if (!validPhoneNumber($f3->get('phone'))) {
        //require a number totaling 10 digits
        $isValid = false;
        $f3->set("errors['gender']", "Please select a gender");
    }
    else{
        $_SESSION['phone'] = $_POST['phone'];
    }

    return $isValid;
}

    /* validate an age value
     * @param String age
     * @return boolean
     *
     */
    function validAge($age)
    {
        return !empty($age) && ctype_digit($age) && ((int)$age >= 18 && (int)$age <= 118);
    }

    /* Validate a name string
     * @param String $name
     * @return boolean if name is not empty, and all alphabetic
     */
    function validName($name)
    {
        return ctype_alpha($name) AND ($name != "");
    }

    /* Validate a gender value
     * @param gender $gender
     * @return boolean if gender is not empty, and all alphabetic
     */
    function validGender($gender)
    {
        return ctype_alpha($gender) AND ($gender != "");
    }

    /* validate an phone number value input as a string
     * @param String phone
     * @return boolean
     *
     */
    function validPhoneNumber($phone)
    {
        return !empty($phone) && ctype_digit($phone);
    }

    function validProfileForm()
    {
        global $f3;
        global $_SESSION;
        $isValid = true;

        if (!validEmail($f3->get('email'))) {

            $isValid = false;
            $f3->set("errors['email']", "Please enter an correct email address");
        }
        else{
            $_SESSION['email'] = $_POST['email'];
        }

        if (!validSeekSex($f3->get('seekSex')))
        {
            $isValid = false;
            $f3->set("errors['seekSex']", "Please select the gender of your desired partner");
        }
        else {
            $_SESSION['seekSex'] = $_POST['seekSex'];
        }

        if (!validState($f3->get(resState)))
        {
            $isValid = false;
            $f3->set("errors['resState']", "Full spelling or state, or two letter abbreviation please");
        }
        else{
            $_SESSION['resState'] = $_POST['resState'];
        }
        return $isValid;
    }

    function validEmail($email)
    {
        // https://stackoverflow.com/questions/19261987/how-to-check-if-an-email-
        //address-is-real-or-valid-using-php/19262381
        // StackOverflow comment #33 Author: Machavity
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //Email is valid
            return true;
        }
        else {
            return false;
        }
    }

    function validSeekSex($seekSex)
    {
        return ctype_alpha($seekSex) AND ($seekSex != "");
    }

    function validState($resState)
    {
        global $f3;
        // insist on either the 2 letter abbreviation, or full state spelling
        if (in_array($resState,$f3->get('states')))
        {
            return true;
        }
        else if (in_array($resState,$f3->get('states_ABBR')))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function validInterestsForm()
    {
        return true;
    }
    function validOutdoor($interest)
    {
        global $f3;
        if(!in_array($interest, $f3->get('outdoorInterests')))
        {
            //spoof attempt. skip it
            return false;
        }
        else{
            return true;
        }
    }

    function validIndoor($interest)
    {
        global $f3;
        if (!in_array($interest, $f3->get('indoorInterests'))) {
            //spoof attempt. skip it
            return false;
        } else {
            return true;
        }

    }