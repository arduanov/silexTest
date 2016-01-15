<?php

namespace App\Entity\Behavior;


trait Timestamps
{
    /**
     * @ORM\HasLifecycleCallbacks
     */

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cdate", type="datetime", nullable=false)
     */
    private $cdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mdate", type="datetime", nullable=false)
     */
    private $mdate;

    /**
     * Get cdate
     *
     * @return \DateTime
     */
    public function getCdate()
    {
        if ($this->cdate === null) {
            $this->cdate = new \DateTime();
        }
        return $this->cdate;
    }

    /**
     * Get mdate
     *
     * @return \DateTime
     */
    public function getMdate()
    {
        if ($this->mdate === null) {
            $this->mdate = new \DateTime();
        }
        return $this->mdate;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function doPreUpdate()
    {
        if (!$this->cdate) {
            $this->cdate = new \DateTime();
        }
        $this->mdate = new \DateTime();
    }
}
