<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * MailList
 *
 * @ORM\Table(name="mail_list", indexes={@ORM\Index(name="idx_project", columns={"id_project"})})
 * @ORM\Entity
 */
class MailList
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
     * @ORM\Column(name="url", type="string", length=100, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="from_name", type="string", length=100, nullable=false)
     */
    private $fromName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_add", type="datetime", nullable=false)
     */
    private $dtAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_upd", type="datetime", nullable=true)
     */
    private $dtUpd;

    /**
     * @var \Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_project", referencedColumnName="id")
     * })
     */
    private $idProject;

    public function getId()
    {
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getFromName()
    {
        return $this->fromName;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getProjectDomain()
    {
        return $this->idProject->getDomain();
    }
}
