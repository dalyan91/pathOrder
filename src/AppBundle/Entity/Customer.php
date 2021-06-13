<?php

namespace AppBundle\Entity;

use DateTime;
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"customer"})
     */
    private $name;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="since", type="datetime", nullable=true)
     * @Serializer\Groups({"customer"})
     *
     */
    private $since;

    /**
     * @ORM\Column(name="revenue", type="float")
     * @Serializer\Groups({"customer"})
     */
    private $revenue;

    /**
     * @ORM\OneToMany(targetEntity="Ordering", mappedBy="customer", cascade={"persist"})
     * @Serializer\Groups({"customer.order"})
     */
    private $order;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->since = new \DateTime("now");
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
     * @return Customer
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
     * Set since
     *
     * @param \DateTime $since
     *
     * @return Customer
     */
    public function setSince($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * Get since
     *
     * @return \DateTime
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * Set revenue
     *
     * @param float $revenue
     *
     * @return Customer
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get revenue
     *
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Ordering $order
     *
     * @return Customer
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
