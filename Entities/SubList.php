<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * SubList
 *
 * @ORM\Table(name="sub_list", uniqueConstraints={@ORM\UniqueConstraint(name="id_sub", columns={"id_sub", "id_list"})}, indexes={@ORM\Index(name="id_list", columns={"id_list"}), @ORM\Index(name="IDX_635E67D9C6A66A86", columns={"id_sub"})})
 * @ORM\Entity
 */
class SubList
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
     * @ORM\Column(name="dt_add", type="datetime", nullable=false)
     */
    private $dtAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_uns", type="datetime", nullable=true)
     */
    private $dtUns;

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
}
