<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table("account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 * @UniqueEntity(fields={"email"}, message="This email is already used.")
 */
class Account implements UserInterface, EncoderAwareInterface ,\Serializable
{
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"account","account.id"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"account","account.name"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     *
     * @Serializer\Groups({"account","account.surname"})
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Serializer\Groups({"account","account.email"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     *
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="session_token", type="string", length=255, nullable=true)
     * @Serializer\Groups({"account"})
     */
    private $sessionToken;

    /**
     * Require for interface
     * @var array
     *
     * @ORM\Column(name="roles", type="simple_array")
     *
     * @Serializer\Groups({"account"})
     */
    private $roles;

    /**
     * @var DateTime $lastLogin
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"account"})
     */
    private $lastLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     *
     * @Serializer\Groups({"account"})
     */
    private $active = true;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="account")
     * @Serializer\Groups({"account.order"})
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="account", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"ordering.customer"})
     */
    private $customer;

    /**
     * @return string|null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function getEncoderName()
    {
        return 'hasher';
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->name .' '. $this->surname;
    }

    /**
     * Require for interface
     */
    public function eraseCredentials(){}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Account
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Account
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set sessionToken
     *
     * @param string $sessionToken
     *
     * @return Account
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    /**
     * Get sessionToken
     *
     * @return string
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Account
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return Account
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Account
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Account
     */
    public function addOrder(\AppBundle\Entity\Order $order)
    {
        $this->order[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\Order $order
     */
    public function removeOrder(\AppBundle\Entity\Order $order)
    {
        $this->order->removeElement($order);
    }

    /**
     * Get order
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Account
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
