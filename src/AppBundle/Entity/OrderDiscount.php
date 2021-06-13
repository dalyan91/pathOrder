<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * OrderDiscount
 *
 * @ORM\Table(name="order_discount")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderDiscountRepository")
 */
class OrderDiscount
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="discountAmount", type="float")
     * @Serializer\Groups({"orderDiscount"})
     */
    private $discountAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="subtotal", type="float")
     * @Serializer\Groups({"orderDiscount"})
     */
    private $subtotal;

    /**
     * @ORM\ManyToOne(targetEntity="Ordering", inversedBy="orderDiscount")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="SET NULL")
     * @Serializer\Groups({"orderDiscount.order"})
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Discount", inversedBy="orderDiscount")
     * @ORM\JoinColumn(name="discount_id", referencedColumnName="id", onDelete="SET NULL")
     * @Serializer\Groups({"orderDiscount.discount"})
     */
    private $discount;

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
     * Set discountAmount
     *
     * @param float $discountAmount
     *
     * @return OrderDiscount
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    /**
     * Get discountAmount
     *
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return OrderDiscount
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Ordering $order
     *
     * @return OrderDiscount
     */
    public function setOrder(\AppBundle\Entity\Ordering $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Ordering
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set discount
     *
     * @param \AppBundle\Entity\Discount $discount
     *
     * @return OrderDiscount
     */
    public function setDiscount(\AppBundle\Entity\Discount $discount = null)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return \AppBundle\Entity\Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
