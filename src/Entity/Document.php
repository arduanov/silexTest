<?php

namespace App\Entity;

use Codeception\Module\Cli;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Behavior\Timestamps;


/**
 * Document
 *
 * @ORM\Table(name="document", uniqueConstraints={@ORM\UniqueConstraint(name="document_id_key", columns={"id"})}) })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Document
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
     * @ORM\ManyToOne(targetEntity="Administrator", inversedBy="documents")
     * @ORM\JoinColumn(name="administrator_id",  nullable=false, referencedColumnName="id")
     **/
    private $administrator;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="documents")
     * @ORM\JoinColumn(name="client_id",  nullable=true, referencedColumnName="id")
     **/
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="documents")
     * @ORM\JoinColumn(name="task_id",  nullable=true, referencedColumnName="id")
     **/
    private $task;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
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

    public function setAdministrator(Administrator $val = null)
    {
        $this->administrator = $val;
        return $this;
    }

    public function setClient(Client $val = null)
    {
        $this->client = $val;
        return $this;
    }

    public function setTask(Task $val = null)
    {
        $this->task = $val;
        return $this;
    }

}
