<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Behavior\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Administrator
 *
 * @ORM\Table(name="administrator", uniqueConstraints={@ORM\UniqueConstraint(name="administrator_id_key", columns={"id"})}) })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Administrator
{
    use Timestamps;

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
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="administrator")
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

    /**
     * Get owner
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }
}
