<?php
namespace App\Entity\Main;

use App\Entity\Enum\DossierStatusEnum;
use App\Entity\Trait\CreatedDateTrait;
use App\Entity\Trait\LastUpdateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: \App\Repository\Main\DossierRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Dossier
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('Dossier')]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Groups('Dossier')]
    private string $title;

    #[ORM\Column(enumType: DossierStatusEnum::class, options: ['default' => DossierStatusEnum::PREPARATION->value])]
    #[Groups('Dossier')]
    private DossierStatusEnum $status = DossierStatusEnum::PREPARATION;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('DossierDetails')]
    private string $content;

    #[ORM\ManyToOne(inversedBy: 'dossier'), ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Client $client;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'dossier')]
    #[Groups('Category')]
    /** @var Collection<int, Category> */
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'dossiers'), ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups('DossierDetails')]
    private User $author;

    use CreatedDateTrait, LastUpdateTrait;

    public function __construct()
    {
        $this->categories = new ArrayCollection;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): DossierStatusEnum
    {
        return $this->status;
    }

    public function setStatus(DossierStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        if (null === $client) {
            unset($this->client);
        } else {
            $this->client = $client;
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addDossier($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeDossier($this);
        }

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        if (null === $author) {
            unset($this->author);
        } else {
            $this->author = $author;
        }

        return $this;
    }
}
