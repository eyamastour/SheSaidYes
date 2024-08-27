<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 */
class Coupon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pourcentageReduction;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateur::class, mappedBy="coupon")
     */
    private $utilisateur;

    public function __construct()
    {
        $this->utilisateur = new ArrayCollection();
    }
    /*
        /**
     * @var \Utilisateur
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="iduser", referencedColumnName="iduser")
     * })
     */
    /* private $iduser;


    public function getIduser()
    {
        return $this->iduser ;
    }

    public function setIduser(Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPourcentageReduction(): ?string
    {
        return $this->pourcentageReduction;
    }

    public function setPourcentageReduction(?string $pourcentageReduction): self
    {
        $this->pourcentageReduction = $pourcentageReduction;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
            $utilisateur->setCoupon($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateur->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getCoupon() === $this) {
                $utilisateur->setCoupon(null);
            }
        }

        return $this;
    }
}
