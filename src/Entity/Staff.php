<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Role;

/**
 * Class Staff.
 *
 * @author MacFJA
 *
 * @ORM\Entity(repositoryClass="App\Repository\StaffRepository")
 * @ORM\Table(name="sys_aoe_staff")
 */
class Staff
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = null;

    /**
     * @var \DateTime
     * @ORM\Column(type="boolean", name="active")
     */
    private $active = true;

    /**
     * @var string
     * @ORM\Column(type="string", name="last_name", length=20)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", name="middle_name", length=20, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     * @ORM\Column(type="string", name="first_name", length=20)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", name="gender", nullable=true)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="string", name="priv_email", length=50, nullable=true)
     */
    private $emailPrivate;

    /**
     * @var string
     * @ORM\Column(type="string", name="priv_phone", length=20, nullable=true)
     */
    private $phonePrivate;

    /**
     * @var string
     * @ORM\Column(type="string", name="address", length=50, nullable=true)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="string", name="email_work", length=50, nullable=true)
     */
    private $emailWork;

    /**
     * @var string
     * @ORM\Column(type="string", name="phone_work_private", length=20, nullable=true)
     */
    private $phoneWorkPrivate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $image;


    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        if (empty($gender)) $gender = 'unspecified';

        $enum = ['male', 'female', 'unspecified'];

        if (in_array($gender, $enum, true)) {
            $this->gender = $gender;
        } else {
            throw new \InvalidArgumentException("Invalid gender");
        }
    }

    /**
     * @return string
     */
    public function getEmailPrivate()
    {
        return $this->emailPrivate;
    }

    /**
     * @param string $emailPrivate
     */
    public function setEmailPrivate($emailPrivate)
    {
        $this->emailPrivate = $emailPrivate;
    }

    /**
     * @return string
     */
    public function getPhonePrivate()
    {
        return $this->phonePrivate;
    }

    /**
     * @param string $phonePrivate
     */
    public function setPhonePrivate($phonePrivate)
    {
        $this->phonePrivate = $phonePrivate;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getEmailWork()
    {
        return $this->emailWork;
    }

    /**
     * @param string $emailWork
     */
    public function setEmailWork($emailWork)
    {
        $this->emailWork = $emailWork;
    }

    /**
     * @return string
     */
    public function getPhoneWorkPrivate()
    {
        return $this->phoneWorkPrivate;
    }

    /**
     * @param string $phoneWorkPrivate
     */
    public function setPhoneWorkPrivate($phoneWorkPrivate)
    {
        $this->phoneWorkPrivate = $phoneWorkPrivate;
    }

    /**
     * @return \DateTime
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param \DateTime $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}
