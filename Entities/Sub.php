<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Sub
 *
 * @ORM\Table(name="sub", uniqueConstraints={@ORM\UniqueConstraint(name="hash", columns={"hash"})})
 * @ORM\Entity
 */
class Sub
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
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_disabled", type="boolean", nullable=false)
     */
    private $isDisabled = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=36, nullable=false)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_parent", type="string", length=36, nullable=true)
     */
    private $hashParent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=true)
     */
    private $ip;

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

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getEmail(){
        return $this->email;
    }
}
