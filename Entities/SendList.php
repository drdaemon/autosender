<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * SendList
 *
 * @ORM\Table(name="send_list", uniqueConstraints={@ORM\UniqueConstraint(name="id_sub", columns={"id_sub", "id_email"})}, indexes={@ORM\Index(name="id_email", columns={"id_email"}), @ORM\Index(name="IDX_200F408BC6A66A86", columns={"id_sub"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class SendList
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
     * @var \DateTime
     *
     * @ORM\Column(name="dt_sent", type="datetime", nullable=true)
     */
    private $dtSent;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="send_count", type="integer", nullable=false)
     */
    private $sendCount = '0';

    /**
     * @var \Email
     *
     * @ORM\ManyToOne(targetEntity="Email")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_email", referencedColumnName="id")
     * })
     */
    private $idEmail;

    /**
     * @var \Sub
     *
     * @ORM\ManyToOne(targetEntity="Sub")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sub", referencedColumnName="id")
     * })
     */
    private $idSub;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mnd_id", type="string", length=32, nullable=true)
     */
    private $mndId;

    /**
     * @var integer
     *
     * @ORM\Column(name="mnd_ts", type="integer", nullable=true)
     */
    private $mndTs = '0';

    /** 
     *  @ORM\PrePersist
     */
    public function InitializeSentDate()
    {
        $this->dtSent = new \DateTime("now");
    }

    public function getId(){
        return $this->id;
    }
    public function setSub($Sub){
        return $this->idSub = $Sub;
    }
    public function setStatus($status){
        return $this->status = $status;
    }
    public function setEmail($Email){
        return $this->idEmail = $Email;
    }
    public function setMndId($MndId){
        return $this->mndId = $MndId;
    }
}
