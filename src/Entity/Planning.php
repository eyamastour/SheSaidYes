<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Planning
 *
 * @ORM\Table(name="planning")
 * @ORM\Entity(repositoryClass="App\Repository\planningRepository")
 */
class Planning
{
    /**
     * @var int
     *
     * @ORM\Column(name="idplan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplan;

    /**
     * @var string A "Y-m-d" formatted value
     * @ORM\Column(name="dateplan", type="string", length=20, nullable=false)
     * @Assert\Date
     */
    private $dateplan;

    /**
     * @var string
     *
     * @ORM\Column(name="etatplan", type="string", length=11, nullable=false)
     */
    private $etatplan;

    /**
     * @var \Services
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idservice", referencedColumnName="id")
     * })
     */

    private $idservice;

    /**
     * @var \Espaceprestatairee
     * @ORM\ManyToOne(targetEntity="Espaceprestatairee")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idprest", referencedColumnName="idprest")
     * })
     */
    private $idprest;

    public function getIdplan(): ?int
    {
        return $this->idplan;
    }

    public function getDateplan(): ?string
    {
        return $this->dateplan;
    }

    public function setDateplan(string $dateplan): self
    {
        $this->dateplan = $dateplan;

        return $this;
    }

    public function getEtatplan(): ?string
    {
        return $this->etatplan;
    }

    public function setEtatplan(string $etatplan): self
    {
        $this->etatplan = $etatplan;

        return $this;
    }

    public function getIdservice()
    {
        return $this->idservice;
    }

    public function setIdservice(Services $idservice): self
    {
        $this->idservice = $idservice;

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
}
