<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"customer"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Serializer\Groups({"customer"})
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="customer", cascade={"persist"})
     * @Serializer\Groups({"customer.order"})
     */
    private $order;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="customer", cascade={"persist"})
     * @Serializer\Groups({"customer.account"})
     */
    private $account;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Customer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Customer
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
     * Add account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return Customer
     */
    public function addAccount(\AppBundle\Entity\Account $account)
    {
        $this->account[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param \AppBundle\Entity\Account $account
     */
    public function removeAccount(\AppBundle\Entity\Account $account)
    {
        $this->account->removeElement($account);
    }

    /**
     * Get account
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccount()
    {
        return $this->account;
    }
}
