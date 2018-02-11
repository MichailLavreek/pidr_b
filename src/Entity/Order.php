<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="order_montage")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $status = 'new';

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $productText;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productPrice;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $quadrature;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="iso2", nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $clientName;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $clientPhone;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    private $clientAddress;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $orderQueue;

    /** @ORM\PrePersist */
    public function onPersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $statuses = ['new', 'accepted', 'rejected'];

        if (!in_array($status, $statuses)) {
            throw new \InvalidArgumentException('Invalid argument - status');
        }

        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getProductText()
    {
        return $this->productText;
    }

    /**
     * @param mixed $productText
     */
    public function setProductText($productText)
    {
        $this->productText = $productText;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * @param mixed $productPrice
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;
    }

    /**
     * @return mixed
     */
    public function getQuadrature()
    {
        return $this->quadrature;
    }

    /**
     * @param mixed $quadrature
     */
    public function setQuadrature($quadrature)
    {
        $this->quadrature = $quadrature;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param mixed $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @return mixed
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * @param mixed $clientPhone
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;
    }

    /**
     * @return mixed
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * @param mixed $clientAddress
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @param mixed $orderDate
     */
    public function setOrderDate($orderDate)
    {
        $date = null;

        if ($orderDate instanceof DateTime) {
            $date = $orderDate;
        } elseif (is_string($orderDate)) {
            $date = new \DateTime($orderDate);
        }

        $this->orderDate = $date;
    }

    /**
     * @return mixed
     */
    public function getOrderQueue()
    {
        return $this->orderQueue;
    }

    /**
     * @param mixed $orderQueue
     */
    public function setOrderQueue($orderQueue)
    {
        $this->orderQueue = $orderQueue;
    }
}
