<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Behavior\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Client
 *
 * @ORM\Table(name="client", uniqueConstraints={@ORM\UniqueConstraint(name="client_id_key", columns={"id"})}) })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Client
{
    use Timestamps;

    public static $STATUS = [
        'active' => 'active',
        'possible' => 'possible',
        'old' => 'old',
    ];
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="client")
     */
    private $documents;

    public function __construct()
    {
        $this->cdate = new \DateTime();
        $this->mdate = new \DateTime();
        $this->documents = new ArrayCollection();

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

    public function getName()
    {
        return $this->name;
    }

    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($val)
    {
        $this->email = $val;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($val)
    {
        $this->phone = $val;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$STATUS, true)) {
            throw new \Exception('User status is not valid');
        }
        $this->status = $status;

        return $this;
    }

}
