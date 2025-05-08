<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "Le nom d'un article doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le nom d'un article doit comporter au plus {{ limit }} caractères"
    )]
    #[Assert\NotBlank(message: "Le nom de l'article ne peut pas être vide")]
    private ?string $nom = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotEqualTo(
        value: 0,
        message: "Le prix d'un article ne doit pas être égal à 0"
    )]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide")]
    #[Assert\Type(
        type: 'numeric',
        message: 'Le prix doit être un nombre'
    )]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix !== null ? (float) $this->prix : null;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix !== null ? number_format($prix, 2, '.', '') : null;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}