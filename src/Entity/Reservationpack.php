<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/*@ORM\ManyToOne(targetEntity="Packs",inversedBy="Reservationpack") @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="Reservationpack")*/

/**
 * Reservationpack
 * @ORM\Table(name="reservationpack")
 * @ORM\Entity(repositoryClass="App\Repository\reservationpackRepository")
 */
class Reservationpack
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @Groups("post:read")
     */
    private $id;

    /** 
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     *  @Groups("post:read")
     */
    private $iduser;

    /** 
     * @var \Packs
     *
     * @ORM\ManyToOne(targetEntity="Packs")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idpack", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $idpack;

    /**
     * @var string A "Y-m-d" formatted value
     * @ORM\Column(name="date", type="string", length=10, nullable=true)
     * @Assert\NotBlank(message="veuillez choisir une date pour passer la réservation")
     * @Assert\Date
     *  @Groups("post:read")
     */
    private $date;

    /**
    
     * @Assert\NotBlank(message="l'heure doit etre non vide")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     * @ORM\Column(name="heuredeb", type="string", length=10, nullable=true)
     *  @Groups("post:read")
     */


    private $heuredeb;

    /**
     * @Assert\NotBlank(message="l'heure doit etre non vide")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     * @ORM\Column(name="heurefin", type="string", length=10, nullable=true)
     *  @Groups("post:read")
     */
    private $heurefin;

    /**
     * @var float
     * @Assert\NotBlank(message="le prix doit etre non vide")
     * @Assert\Positive
     * @ORM\Column(name="prixrespack", type="float", precision=10, scale=0, nullable=false)
     *  @Groups("post:read")
     */
    private $prixrespack;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     *  @Groups("post:read")
     */
    private $etat = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser()
    {
        return $this->iduser;
    }

    public function setIduser(Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdpack()
    {
        return $this->idpack;
    }

    public function setIdpack(Packs $idpack): self
    {
        $this->idpack = $idpack;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeuredeb(): ?string
    {
        return $this->heuredeb;
    }

    public function setHeuredeb(?string $heuredeb): self
    {
        $this->heuredeb = $heuredeb;

        return $this;
    }

    public function getHeurefin(): ?string
    {
        return $this->heurefin;
    }

    public function setHeurefin(?string $heurefin): self
    {
        $this->heurefin = $heurefin;

        return $this;
    }

    public function getPrixrespack(): ?float
    {
        return $this->prixrespack;
    }

    public function setPrixrespack(float $prixrespack): self
    {
        $this->prixrespack = $prixrespack;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function __toString()
    {
        return $this->getDate();
    }
}
