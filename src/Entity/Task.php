<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Behavior\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Task
 *
 * @ORM\Table(name="task", uniqueConstraints={@ORM\UniqueConstraint(name="task_id_key", columns={"id"})}) })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Task
{
    use Timestamps;

    public static $STATUS = [
        'new' => 'new',
        'progress' => 'progress',
        'close' => 'close',
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
     * @ORM\ManyToOne(targetEntity="Administrator")
     * @ORM\JoinColumn(name="administrator_id",  nullable=true, referencedColumnName="id")
     **/
    private $administrator;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;


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


    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($val)
    {
        $this->description = $val;
        return $this;
    }

    public function setAdministrator(Administrator $val)
    {
        $this->administrator = $val;
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
            throw new \Exception('Task status is not valid');
        }
        $this->status = $status;

        return $this;
    }

}
