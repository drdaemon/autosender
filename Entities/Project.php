<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project
{
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=100, nullable=false)
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mnd_key", type="string", length=100, nullable=false)
     */
    private $mndKey;

    /**
     * @var string
     *
     * @ORM\Column(name="mnd_cb", type="string", length=100, nullable=false)
     */
    private $mndCb;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_disabled", type="boolean", nullable=false)
     */
    private $isDisabled = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_add", type="datetime", nullable=false)
     */
    private $dtAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_mod", type="datetime", nullable=true)
     */
    private $dtMod;

    public function getName()
    {
        return $this->name;
    }
    public function getDomain()
    {
        return $this->domain;
    }
}
