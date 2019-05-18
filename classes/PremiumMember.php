<?php
/**
 * Class PremiumMember
 * Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating III Assignment: use classes
 * file: PremiumMember.php
 * date: Friday, May 17 2019
 * class PremiumMember
 *
 * will extend the Member class, and add the interests (indoor & outdoor) shown only to PremiumMembers
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

Class PremiumMember extends Member
{
    private $_indoor;
    private $_outdoor;

    /**
     * Here we will leverage PHP inheritance, because a PremiumMember really is (and starts as) a Member,
     * then has additional fields (variables) the two arrays of selected interests (indoor[] & outdoor[])
     * Thus there is an embedded invoking of the Member class
     *
     * @param $_indoor
     * @param $_outdoor
     */
    public function __construct($fname, $lname, $age, $gender, $phone,
                                $email = "", $state = "", $seeking = "", $bio = "",
                                $indoor = "", $outdoor = "")
    {
        //set parent constructor values
        parent::__construct($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio);

        $this->_indoor = $indoor;
        $this->_outdoor = $outdoor;
    }
//we are instructed to write our own setters and getters, thus the above four
//data (attributes) will be logical extensions of their respective names. Here will
//impose some alphabetical order to aid in locating the methods (mutator)
//thus presentation of the methods will be ordered:
//
// getIndoor()
// getOutdoor()
// setIndoor()
// setOutdoor()
    /**
     * retrieve the list of indoor interests selected by the PremiumMember
     * @return string pointer to something like "sewing reading..."
     */
    public function getIndoor()
    {
        return $this->_indoor;
    }

    /**
     * retrieve the list of outdoor interests selected by the PremiumMember
     * @return string pointer to something like "skydiving archery..."
     */
    public function getOutdoor()
    {
        return $this->_outdoor;
    }

    /**
     * store the list of indoor interests selected by the PremiumMember
     * @param string $indoor
     */
    public function setIndoor($indoor)
    {
        $this->_indoor = $indoor;
    }

    /**
     * store the list of outdoor interests selected by the PremiumMember
     * @param string $outdoor
     */
    public function setOutdoor($outdoor)
    {
        $this->_outdoor = $outdoor;
    }
}