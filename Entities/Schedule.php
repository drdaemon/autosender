<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule
 *
 * @ORM\Table(name="schedule", uniqueConstraints={@ORM\UniqueConstraint(name="id_list", columns={"id_list", "id_email"})}, indexes={@ORM\Index(name="id_email", columns={"id_email"}), @ORM\Index(name="IDX_5A3811FBCFE8E41A", columns={"id_list"})})
 * @ORM\Entity
 */
class Schedule
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
     * @var integer
     *
     * @ORM\Column(name="delay_days", type="integer", nullable=true)
     */
    private $delayDays;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_email_prev", type="integer", nullable=true)
     */
    private $idEmailPrev;

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
     * @var \MailList
     *
     * @ORM\ManyToOne(targetEntity="MailList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_list", referencedColumnName="id")
     * })
     */
    private $idList;

    public function getId(){
        return $this->id;
    }
    public function getEmailIdPrev()
    {
        return $this->idEmailPrev;
    }
    public function getEmailIdCurr()
    {
        return $this->idEmail;
    }
    public function getDelay()
    {
        return $this->delayDays;
    }
}
