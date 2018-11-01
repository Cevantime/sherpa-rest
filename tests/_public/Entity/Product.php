<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 01/11/18
 * Time: 16:40
 */

/**
 * Class Program
 * @package Entity
 * @Entity()
 */
class Product
{
    /**
     * @var int $id
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @var string $fullCode
     * @Column(type="string")
     */
    protected $name;

    /**
     * @var User
     * @ManyToOne(targetEntity="User", inversedBy="products")
     */
    protected $owner;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return Product
     */
    public function setOwner(User $owner): Product
    {
        $this->owner = $owner;
        return $this;
    }
}