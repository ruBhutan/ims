<?php

/**
 * @author eDruk Private Limited <edruk@edruk.com.bt>
 * @link http://web.edruk.com.bt
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Doctroine Entity Class
 * @ORM\Entity
 * @ORM\Table(name="employee_details")
 */
class EmployeeDetails {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_id;
    /**
     * @ORM\Column(length=255)
     */
    protected $first_name;
    /**
     * @ORM\Column(length=255)
     */
    protected $middle_name;
    /**
     * @ORM\Column(length=255)
     */
    protected $last_name;
    /**
     * @ORM\Column(length=255)
     */
    protected $cid;
    /**
     * @ORM\Column(length=255)
     */
    protected $nationality;
    /**
     * @ORM\Column(length=255)
     */
    protected $date_of_birth;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_house_no;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_thram_no;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_dzongkhag;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_gewog;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_village;
    /**
     * @ORM\Column(length=255)
     */
    protected $country;
    /**
     * @ORM\Column(length=255)
     */
    protected $recruitment_date;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_type;
    /**
     * @ORM\Column(length=255)
     */
    protected $gender;
    /**
     * @ORM\Column(length=255)
     */
    protected $marital_status;
    /**
     * @ORM\Column(length=255)
     */
    protected $phone_no;
    /**
     * @ORM\Column(length=255)
     */
    protected $email;
    /**
     * @ORM\Column(length=255)
     */
    protected $blood_group;
    /**
     * @ORM\Column(length=255)
     */
    protected $religion;
    /**
     * @ORM\Column(length=255)
     */
    protected $profile_picture;
    /**
     * @ORM\Column(length=255)
     */
    protected $organisation_id;
    /**
     * @ORM\Column(length=255)
     */
    protected $departments_id;
    /**
     * @ORM\Column(length=255)
     */
    protected $departments_units_id;
    /**
     * @ORM\Column(length=255)
     */
    protected $emp_resignation_id;

    /**
     * Initializes the roles variable.
     */
    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    /**
     * Magic getter to expose protected properties.
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }

    /**
     * Populate from an array.
     * @param array $data
     */
    public function populate($data = array()) {
        foreach ( $data as $key => $value ) {
            if ( property_exists(__class__, $key) )
                $this->$key = $value;
        }
    }

    public function toArray() {
        return get_object_vars($this);
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole(Role $role) {
        $this->roles[] = $role;
    }

    /**
     * Add roles to the user.
     *
     * @param unknown $roles
     */
    public function addRoles($roles) {
        foreach ( $roles as $curRole ) {
            $this->roles->add($curRole);
        }
    }

    /**
     * Remove roles from user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function removeRoles($roles) {
        foreach ( $roles as $curRole ) {
            $this->roles->removeElement($curRole);
        }
    }

    /**
     * Sets the role to the one specified. All other roles are cleared.
     *
     * @param Role $role
     */
    public function updateRole(Role $role) {
        $this->roles->clear();
        $this->roles[] = $role;
    }

    /**
     * Returns the first role of this user.
     * @return \BloomtubeUser\Entity\Role | null
     */
    public function getRole() {
        $roles = $this->getRoles();

        if ( $roles && (is_array($roles)) && count($roles) > 0 ) {
            return $roles[0];
        } else {
            return null;
        }
    }

}
