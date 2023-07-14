<?php
namespace App\Entity\Main;

use App\Entity\Trait\LastUpdateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\Main\UserRepository::class),
    UniqueEntity('username', errorPath: 'username'),
    UniqueEntity('email'),
    ORM\HasLifecycleCallbacks,
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_COMMENTATOR = 'ROLE_COMMENTATOR';
    public const ROLE_WRITER = 'ROLE_WRITER';
    public const ROLE_EDITOR = 'ROLE_EDITOR';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const DEFAULT_ROLES = [self::ROLE_COMMENTATOR];

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 6, max: 255), Assert\NotBlank]
    private string $username;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '#(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}#',
        message: 'Password must be 7 characters long and contain at least one digit, one uppercase letter and one lower case letter.'
    )]
    private string $password;

    // Plain password. Used for model validation. Must not be persisted.
    #[Assert\Regex(
        pattern: '#(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}#',
        message: 'Password must be 7 characters long and contain at least one digit, one uppercase letter and one lower case letter.'
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 6, max: 255), Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\Email, Assert\Length(min: 6, max: 255), Assert\NotBlank]
    private string $email;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, options: ['default' => self::ROLE_COMMENTATOR])]
    private array $roles = self::DEFAULT_ROLES;

    #[ORM\Column(options: ['default' => true])]
    private bool $enabled = true;

    #[ORM\OneToMany(targetEntity: Dossier::class, mappedBy: 'author', orphanRemoval: true)]
    /** @var Collection<int, Dossier> */
    private Collection $dossiers;

    use LastUpdateTrait;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setAuthor($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getAuthor() === $this) {
                $dossier->setAuthor(null);
            }
        }

        return $this;
    }
}
