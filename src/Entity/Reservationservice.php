<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * Reservationservice
 * @ORM\Table(name="reservationservice")
 * @ORM\Entity(repositoryClass="App\Repository\reservationserviceRepository")
 */
class Reservationservice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /** 
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="veuillez choisir un client")
     * @Groups("post:read")
     */
    private $iduser;

    /** 
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idservice", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="veuillez choisir au moins un service")
     * @Groups("post:read")
     */
    private $idservice;

    /**
     * @ORM\Column(name="date", type="string", length=10, nullable=true)
     * @Assert\NotBlank(message="date de reservation doit etre non vide")
     * @var string A "Y-m-d" formatted value
     * @Assert\Date
     * @Groups("post:read")
     */
    private $date;

    /**
     * @ORM\Column(name="heuredeb", type="string", length=10, nullable=true)
     * @Assert\NotBlank(message="heure doit etre non vide")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     * @Groups("post:read")
     */
    private $heuredeb;

    /**
     * @ORM\Column(name="heurefin", type="string", length=10, nullable=true)
     * @Assert\NotBlank(message="heure doit etre non vide")
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     * @Groups("post:read")
     */
    private $heurefin;

    /**
     * @var float|null
     *@Assert\NotBlank(message="le prix doit etre non vide")
     *@Assert\Positive
     * @ORM\Column(name="prixresserv", type="float", precision=10, scale=0, nullable=true)
     * @Groups("post:read")
     */
    private $prixresserv;

    /**
     * @var bool
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     * @Groups("post:read")
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser()
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdservice()
    {
        return $this->idservice;
    }

    public function setIdservice(?Services $idservice): self
    {
        $this->idservice = $idservice;

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

    public function getPrixresserv(): ?float
    {
        return $this->prixresserv;
    }

    public function setPrixresserv(?float $prixresserv): self
    {
        $this->prixresserv = $prixresserv;

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
}
