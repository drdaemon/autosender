<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email", uniqueConstraints={@ORM\UniqueConstraint(name="hash", columns={"hash"})})
 * @ORM\Entity
 */
class Email
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="filename_text", type="string", length=100, nullable=false)
     */
    private $filenameText;

    /**
     * @var string
     *
     * @ORM\Column(name="filename_html", type="string", length=100, nullable=true)
     */
    private $filenameHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=36, nullable=false)
     */
    private $hash;

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

    public function getId(){
        return $this->id;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getFilenameText(){
        return $this->filenameText;
    }
}
