<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *  fields={"email"},
 *  message= "L'email que vous avez tapez est déjà utilisé!"
 * )

 */
class Utilisateur implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)

     */
    private $nom;



    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;



    /**
     * @var int|null
     *
     * @ORM\Column(name="tel", type="integer", nullable=true)

     */
    private $tel;






    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=false)
     */
    private $role;


    /**
     * @var int
     *
     * @ORM\Column(name="Bloquer", type="integer", nullable=false)
     */
    private $bloquer = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="remember", type="integer", nullable=false)
     */
    private $remember = '0';





    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     */
    private $reset_token;



    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     */
    private $activation_token;


    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }




    //     /**
    // * @var string
    // *
    // * @ORM\Column(name="repeatPassword", type="string", length=255, nullable=false)
    // */
    // private $repeatpassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)  $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * 
     * @return string|null
     */
    function getNom()
    {
        return $this->nom;
    }

    /**
     * 
     * @param string|null $nom 
     * @return Utilisateur
     */
    function setNom($nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    /**
     * 
     * @return string|null
     */
    function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * 
     * @param string|null $prenom 
     * @return Utilisateur
     */
    function setPrenom($prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }
    /**
     * 
     * @return int|null
     */
    function getTel()
    {
        return $this->tel;
    }

    /**
     * 
     * @param int|null $tel 
     * @return Utilisateur
     */
    function setTel($tel): self
    {
        $this->tel = $tel;
        return $this;
    }


    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }


    /**
     * 
     * @return string|null
     */
    function getImage()
    {
        return $this->image;
    }


    function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }
    /**
     * 
     * @return int
     */
    function getBloquer()
    {
        return $this->bloquer;
    }

    /**
     * 
     * @param int $bloquer 
     * @return Utilisateur
     */
    function setBloquer($bloquer): self
    {
        $this->bloquer = $bloquer;
        return $this;
    }
    /**
     * 
     * @return int
     */
    function getRemember()
    {
        return $this->remember;
    }

    /**
     * 
     * @param int $remember 
     * @return Utilisateur
     */
    function setRemember($remember): self
    {
        $this->remember = $remember;
        return $this;
    }
    // /**
    //  * 
    //  * @return string
    //  */
    // function getRepeatpassword() {
    // 	return $this->repeatpassword;
    // }

    // /**
    //  * 
    //  * @param string $repeatpassword 
    //  * @return Utilisateur
    //  */
    // function setRepeatpassword($repeatpassword): self {
    // 	$this->repeatpassword = $repeatpassword;
    // 	return $this;
    // }
    /**
     * 
     * @return string|null
     */
    function getRole()
    {
        return $this->role;
    }

    /**
     * 
     * @param string|null $role 
     * @return Utilisateur
     */
    function setRole($role): self
    {
        $this->role = $role;
        return $this;
    }
    /**
     * 
     * @return mixed
     */
    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }


    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }
    public function __toString()
    {
        return (string)$this->nom;
    }
}
