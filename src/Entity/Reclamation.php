<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Reclamation
 * @ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @Groups("post:read")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreclamation;


    /**
     * @var string A "Y-m-d" formatted value
     *@Assert\Date 
     * @Assert\NotBlank(message="veuillez choisir une date ")
     * @Groups("post:read")
     * @ORM\Column(name="dateReclamtion", type="string", length=255, nullable=false)
     */
    private $datereclamtion;

    /**
     * @var string
     *@Assert\NotBlank(message="champ vide")
     *@Groups("post:read")
     * @ORM\Column(name="descriptionReclamtion", type="string", length=255, nullable=false)
     */
    private $descriptionreclamtion;


    /**
     * @var string
     * @ORM\Column(name="imageReclamtion", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $imagereclamtion;

    /**
     * @var string
     * @ORM\Column(name="etatreclamtion", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $etatreclamtion;

    /**
     * @var Utilisateur
     *  @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="champ vide")
     * @Groups("post:read")
     */
    private $idUser;

    /**
     * @ORM\OneToOne(targetEntity=Reponse::class,mappedBy="reclamation", cascade={"persist", "remove"})
     */
    private $reponse;

    public function getIdreclamation(): ?int
    {
        return $this->idreclamation;
    }

    public function getDatereclamtion(): ?string
    {
        return $this->datereclamtion;
    }

    public function setDatereclamtion(string $datereclamtion): self
    {
        $this->datereclamtion = $datereclamtion;

        return $this;
    }

    public function getDescriptionreclamtion(): ?string
    {
        return $this->descriptionreclamtion;
    }

    public function setDescriptionreclamtion(string $descriptionreclamtion): self
    {
        $this->descriptionreclamtion = $descriptionreclamtion;

        return $this;
    }

    public function getImagereclamtion(): ?string
    {
        return $this->imagereclamtion;
    }

    public function setImagereclamtion(string $imagereclamtion): self
    {
        $this->imagereclamtion = $imagereclamtion;

        return $this;
    }

    public function getEtatreclamtion(): ?string
    {
        return $this->etatreclamtion;
    }

    public function setEtatreclamtion(string $etatreclamtion): self
    {
        $this->etatreclamtion = $etatreclamtion;

        return $this;
    }

  

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get })
     *
     * @return  Utilisateur
     */ 
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set })
     *
     * @param  Utilisateur  $idUser  })
     *
     * @return  self
     */ 
    public function setIdUser(Utilisateur $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}
