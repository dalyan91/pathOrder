<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Ordering
 *
 * @ORM\Table(name="ordering")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderingRepository")
 */
class Ordering
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"ordering"})
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Serializer\Groups({"ordering"})
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="discountedTotal", type="float", nullable=true)
     * @Serializer\Groups({"ordering"})
     */
    private $discountedTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="totalDiscount", type="float", nullable=true)
     * @Serializer\Groups({"ordering"})
     */
    private $totalDiscount;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"ordering.customer"})
     */
    private $customerId;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="order")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @Serializer\Groups({"ordering.account"})
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity="OrderDiscount", mappedBy="order", cascade={"persist"})
     * @Serializer\Groups({"ordering.orderDiscount"})
     */
    private $orderDiscount;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
     * @Serializer\Groups({"ordering.orderItem"})
     */
    private $orderItem;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderDiscount = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orderItem = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set total
     *
     * @param float $total
     *
     * @return Ordering
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set discountedTotal
     *
     * @param float $discountedTotal
     *
     * @return Ordering
     */
    public function setDiscountedTotal($discountedTotal)
    {
        $this->discountedTotal = $discountedTotal;

        return $this;
    }

    /**
     * Get discountedTotal
     *
     * @return float
     */
    public function getDiscountedTotal()
    {
        return $this->discountedTotal;
    }

    /**
     * Set totalDiscount
     *
     * @param float $totalDiscount
     *
     * @return Ordering
     */
    public function setTotalDiscount($totalDiscount)
    {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    /**
     * Get totalDiscount
     *
     * @return float
     */
    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }

    /**
     * Set customerId
     *
     * @param \AppBundle\Entity\Customer $customerId
     *
     * @return Ordering
     */
    public function setCustomerId(\AppBundle\Entity\Customer $customerId = null)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return Ordering
     */
    public function setAccount(\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add orderDiscount
     *
     * @param \AppBundle\Entity\OrderDiscount $orderDiscount
     *
     * @return Ordering
     */
    public function addOrderDiscount(\AppBundle\Entity\OrderDiscount $orderDiscount)
    {
        $this->orderDiscount[] = $orderDiscount;

        return $this;
    }

    /**
     * Remove orderDiscount
     *
     * @param \AppBundle\Entity\OrderDiscount $orderDiscount
     */
    public function removeOrderDiscount(\AppBundle\Entity\OrderDiscount $orderDiscount)
    {
        $this->orderDiscount->removeElement($orderDiscount);
    }

    /**
     * Get orderDiscount
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDiscount()
    {
        return $this->orderDiscount;
    }

    /**
     * Add orderItem
     *
     * @param \AppBundle\Entity\OrderItem $orderItem
     *
     * @return Ordering
     */
    public function addOrderItem(\AppBundle\Entity\OrderItem $orderItem)
    {
        $this->orderItem[] = $orderItem;

        return $this;
    }

    /**
     * Remove orderItem
     *
     * @param \AppBundle\Entity\OrderItem $orderItem
     */
    public function removeOrderItem(\AppBundle\Entity\OrderItem $orderItem)
    {
        $this->orderItem->removeElement($orderItem);
    }

    /**
     * Get orderItem
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderItem()
    {
        return $this->orderItem;
    }
}
