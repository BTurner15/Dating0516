<?php
/**
 * Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating III Assignment: use classes
 * file: Member.php
 * date: Friday, May 17 2019
 * class Member
 *
 * Here I want to conform to the required PEAR coding standards from the git go
 * " Apply PEAR Standards to your class files, including a class-level docblock
 *   above each class, and a docblock above each function. "
 *
 * indent 4 spaces
 * line length max 80 characters
 * class names begin with a upper case
 * private members (variables & functions) are preceded with an underscore
 * constants are all Uppercase
 * add PHPDoc to each class & function
 */
//3456789_123456789_123456789_123456789_123456789_123456789_123456789_1234567890
// the above is 80 characters
/**
 *  Class Member: the basic class, everyone is at least a Member. If they are
 *  a premium member, they started as a Member.
 *  private methods and properties are NOT inherited.
 */
class Member
{
    private $_fname;    //first name
    private $_lname;    //last name
    private $_age;      //age in years minimum is 18, max is 68
    private $_gender;   //I was told this is optional
    private $_phone;    //ten digits
    private $_email;    //must qualify as a syntactic email
    private $_state;    //of residence
    private $_seeking;  //this is required
    private $_bio;      //a text box string including the thoughts on yourself

    /**
     * Member constructor.
     * @param $fname
     * @param $lname
     * @param $age
     * @param $gender
     * @param $phone
     * @param $email
     * @param $state
     * @param $seeking
     * @param $bio
     */
    public function __construct($fname, $lname, $age, $gender, $phone,
                                $email = "", $state = "", $seeking = "", $bio = "")
    {
        $this->_fname = $fname;
        $this->_lname = $lname;
        $this->_age = $age;
        $this->_gender = $gender;
        $this->_phone = $phone;
        $this->_email = $email;
        $this->_state = $state;
        $this->_seeking = $seeking;
        $this->_bio = $bio;
    }

//we are instructed to write our own setters and getters, thus the above nine
//data (attributes) will be logical extensions of their respective names. Here will
//impose some alphabetical order to aid in locating the methods (mutator)
//thus presentation of the methods will be ordered:
//
// getAge()
// getBio()
// getEmail()
// getFname()
// getGender()
// getLname()
// getPhone()
// getSeeking()
// getState()
//
// consistent with the powerpoint specification, each method has a short description,
// a long description, and tags
    /**
     * returns the "age" of the member
     * @return int
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * returns the biography of the member, from a textbox string
     * @return String
     */
    public function getBio()
    {
        return $this->_bio;
    }

    /**
     * returns the alleged email of the member, if it has been stored, it has passed
     * the HTML sanity check imposed by type="email" tag in profile.html
     * @return String $email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * returns the first name entered by the member
     * @return String
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @return String
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * return the last name entered by the member
     * @return String
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * return the phone number entered, NOTE as a string turn into a int when
     * and if needed
     * @return String
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * returns the designator of "Male" or "Female" depending of choice of member
     * @return String
     */
    public function getSeeking()
    {
        return $this->_seeking;
    }

    /**
     * returns the state the member resides in. this will be a two character abbreviated
     * designator
     * @return String
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * will "set" the member age. this will be in the range 18...68
     * @param int $age
     */
    public function setAge($age)
    {
        $this->_age = $age;
    }

    /**
     * stores the biography text box of the member
     * @param String $bio
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }

    /**
     * stores the email entered. the HTML sanity check imposed by type="email"
     * tag in profile.html has been imposed
     * @param $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * stores the first name in the class member (the member of the Member, if you will)
     * @param String $fname
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * stores the string in gender, is either "Male" or "Female"
     * @param String $gender
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * the last name entered into the personal information Last name field
     * @param String $lname
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * stores the 10 digit phone number entered, as a string
     * @param String $phone
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * will store the sex of the desired partner
     * @param String $seeking
     */
    public function setSeeking($seeking)
    {
        $this->_seeking = $seeking;
    }

    /**
     * stores a two character state designator
     * @param String $state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

}