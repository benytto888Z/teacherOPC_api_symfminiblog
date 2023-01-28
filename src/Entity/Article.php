<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ArticleUpdatedAt;
use App\Repository\ArticleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
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
            'controller' => ArticleUpdatedAt::class,
        ],
    ]
)]
class Article
{

    use ResourceId;
    use Timestampable;
    #[ORM\Column(length: 255)]
    #[Groups(['article_read'],['user_details_read'],['article_details_read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['article_read'],['user_details_read'],['article_details_read'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article_details_read'])]

    private ?User $author = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}