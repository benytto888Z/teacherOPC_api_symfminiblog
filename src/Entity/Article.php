<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ArticleUpdatedAtController;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['article_read']],
        ],
        'post',
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['article_details_read']],
        ],
        'put',
        'patch',
        'delete',
        'put_updated_at' => [
            'method' => 'PUT',
            'path' => '/articles/{id}/updated-at',
            'controller' => ArticleUpdatedAtController::class,
        ],
    ]
)]
class Article
{
    use ResourceId;
    use Timestampable;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['article_read', 'article_details_read', 'user_details_read'])]
    private string $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['article_read', 'article_details_read', 'user_details_read'])]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article_details_read'])]
    private ?UserInterface $author;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getAuthor(): ?UserInterface
    {
        return $this->author;
    }

    public function setAuthor(?UserInterface $author): self
    {
        $this->author = $author;

        return $this;
    }
}