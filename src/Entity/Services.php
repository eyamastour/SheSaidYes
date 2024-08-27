<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Services
 *
 * @ORM\Table(name="services", indexes={@ORM\Index(name="fk_p", columns={"idPack"})})
 * @ORM\Entity(repositoryClass="App\Repository\ServicesRepository")
 */
class Services
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" Nom doit etre non vide")
     *  * @Assert\Length(
     *      min = 4,
     *      minMessage=" Entrer un Nom de longueur minimal de 5 caracteres"
     *
     *     )
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message=" Description doit etre non vide")
     *  * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer une Description de longueur minimal de 5 caracteres"
     *
     *     )
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var float
     * @Assert\NotBlank(message=" prix doit etre non vide")
     * @Assert\Positive(message=" prix doit etre positive")
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     * @Assert\Image(
     *     maxSize = "10M",
     *     minWidth = 200,
     *     maxWidth = 5000,
     *     minHeight = 200,
     *     maxHeight = 5000,
     *     mimeTypes = {
     *      "image/jpeg",
     *      "image/jpg",
     *      "image/png"
     *    
     *     }
     * )
     * @Assert\NotBlank(message=" Nom doit etre non vide")
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     * @Assert\NotBlank(message=" Categorie doit etre non vide")
 
     * @ORM\Column(name="categorie", type="string", length=100, nullable=false)
     */
    private $categorie;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iduser", type="integer", nullable=true)
     */
    private $iduser;

    /**
     * @var \Packs|null
     *
     * @ORM\ManyToOne(targetEntity="Packs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPack", referencedColumnName="id")
     * })
     */
    private $idpack;

    /**
     * @var int
     * @Assert\PositiveOrZero(message=" prix doit etre positive")
     * @ORM\Column(name="promo", type="integer", nullable=false)
     */
    private $promo = '0';


    /**
     * @var float
     * @Assert\NotBlank(message=" choisir location")
     * @ORM\Column(name="pos1", type="float", precision=10, scale=0, nullable=false)
     */
    private $pos1;

    /**
     * @var float
     * @Assert\NotBlank(message="choisir location ")
     * @ORM\Column(name="pos2", type="float", precision=10, scale=0, nullable=false)
     */
    private $pos2;

   /**
     * @var \Espaceprestatairee
     * @ORM\ManyToOne(targetEntity="Espaceprestatairee")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idprest", referencedColumnName="idprest")
     * })
     */
    private $idprest;

    


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getIdprest()
    {
        return $this->idprest;
    }

    public function setIdprest(Espaceprestatairee $idprest): self
    {
        $this->idprest = $idprest;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdpack(): ?Packs
    {
        return $this->idpack;
    }

    public function setIdpack(?Packs $idpack): self
    {
        $this->idpack = $idpack;

        return $this;
    }

    public function getPromo(): ?int
    {
        return $this->promo;
    }

    public function setPromo(int $promo): self
    {
        $this->promo = $promo;

        return $this;
    }
    public function getPos1(): ?float
    {
        return $this->pos1;
    }

    public function setPos1(float $pos1): self
    {
        $this->pos1 = $pos1;

        return $this;
    }

    public function getPos2(): ?float
    {
        return $this->pos2;
    }

    public function setPos2(float $pos2): self
    {
        $this->pos2 = $pos2;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->nom;
    }
}