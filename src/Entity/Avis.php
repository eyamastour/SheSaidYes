<?php
 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Services;

/**
 * Avis
 * @ORM\Table(name="avis")
 * @ORM\Entity(repositoryClass="App\Repository\AvisRepository")
 */
class Avis
{

    /**
     * @var int
     *
     * @ORM\Column(name="idAvis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idavis;

  

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

  /**
     * @var Utilisateur
     *  
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="champ vide")
     */
    private $idUser;

    /**
     *  @var int
     * @ORM\Column(name="service", type="integer", nullable=false)
     */
    private $service;

    
    /**
     * @var string
     *
     * @ORM\Column(name="descriptionAvis", type="string", nullable=false)
     */
    private $descriptionAvis;


    public function getIdavis(): ?int
    {
        return $this->idavis;
    }

  

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

  



    /**
     * Get the value of idUser
     *
     * @return  Utilisateur
     */ 
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @param  Utilisateur  $idUser
     *
     * @return  self
     */ 
    public function setIdUser(Utilisateur $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

   

  

    /**
     * Get the value of descriptionAvis
     *
     * @return  string
     */ 
    public function getDescriptionAvis()
    {
        return $this->descriptionAvis;
    }

    /**
     * Set the value of descriptionAvis
     *
     * @param  string  $descriptionAvis
     *
     * @return  self
     */ 
    public function setDescriptionAvis(string $descriptionAvis)
    {
        $this->descriptionAvis = $descriptionAvis;

        return $this;
    }

   

    /**
     * Get the value of service
     *
     * @return  int
     */ 
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set the value of service
     *
     * @param  int  $service
     *
     * @return  self
     */ 
    public function setService(int $service)
    {
        $this->service = $service;

        return $this;
    }
}
