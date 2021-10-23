<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Ordering
 *
 * @ORM\Table(name="ordering")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"order"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="order_code", type="string", length=255)
     * @Serializer\Groups({"order"})
     */
    private $orderCode;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $total;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Serializer\Groups({"order"})
     */
    private $quantity;

    /**
     * @var string
     * @ORM\Column(name="address", type="text")
     * @Serializer\Groups({"order"})
     */
    private $address;

    /**
     * @var \DateTime
     * @ORM\Column(name="shipping_date", type="datetime")
     * @Serializer\Groups({"order"})
     */
    private $shippingDate;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"order.customer"})
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="order")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @Serializer\Groups({"order.account"})
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="order")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @Serializer\Groups({"order.product"})
     */
    private $product;

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
     * Set orderCode
     *
     * @param string $orderCode
     *
     * @return Order
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * Get orderCode
     *
     * @return string
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Order
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Order
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set shippingDate
     *
     * @param \DateTime $shippingDate
     *
     * @return Order
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    /**
     * Get shippingDate
     *
     * @return \DateTime
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Order
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

    /**
     * Set account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return Order
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Order
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
