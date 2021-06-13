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
 * @ORM\Table("users")
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
     * @ORM\Column(name="raw_password", type="string", length=64, nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $rawPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_password_token", type="string", length=500, nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $resetPasswordToken;

    /**
     * @var DateTime $resetPasswordTokenExpire
     *
     * @ORM\Column(name="reset_password_token_expire", type="datetime", nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $resetPasswordTokenExpire;

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
     * @ORM\OneToMany(targetEntity="Ordering", mappedBy="account")
     */
    private $order;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = [];

    }

    /**
     * Get username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->name .' '. $this->surname;
    }

    /**
     * Get Salt
     * Require for interface
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get password
     * Require for interface
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Require for interface
     */
    public function eraseCredentials(){}

    /**
     * Require for interface
     *
     * @return string
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
     * Require for interface
     *
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
     * Return dynamically encoder name
     *
     * @return string
     */
    public function getEncoderName()
    {
        return 'hasher';
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
     * Get roles
     * Require for interface
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Does user have a role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Add new role
     *
     * @param string $role
     * @return Account
     */
    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Account
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set resetPasswordToken
     *
     * @param string $resetPasswordToken
     *
     * @return Account
     */
    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * Get resetPasswordToken
     *
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    /**
     * Set resetPasswordTokenExpire
     *
     * @param DateTime $resetPasswordTokenExpire
     *
     * @return Account
     */
    public function setResetPasswordTokenExpire($resetPasswordTokenExpire)
    {
        $this->resetPasswordTokenExpire = $resetPasswordTokenExpire;

        return $this;
    }

    /**
     * Get resetPasswordTokenExpire
     *
     * @return DateTime
     */
    public function getResetPasswordTokenExpire()
    {
        return $this->resetPasswordTokenExpire;
    }

    /**
     * Set lastLogin
     *
     * @param DateTime $lastLogin
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
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
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
     * Get raw password
     *
     * @return mixed
     */
    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    /**
     * Set raw password
     *
     * @param string $rawPassword
     * @return Account
     */
    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;

        return $this;
    }

    /**
     * Set session token
     *
     * @param string $token
     *
     * @return Account
     */
    public function setSessionToken($token)
    {
        $this->sessionToken = $token;

        return $this;
    }

    /**
     * Get session token
     *
     * @return string
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Ordering $order
     *
     * @return Account
     */
    public function addOrder(\AppBundle\Entity\Ordering $order)
    {
        $this->order[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\Ordering $order
     */
    public function removeOrder(\AppBundle\Entity\Ordering $order)
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
}
