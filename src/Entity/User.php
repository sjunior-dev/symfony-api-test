<?php

namespace App\Entity;

use App\CoreAPI\Entity\Interface\EntityInterface;
use App\Entity\Trait\SoftDeletable;
use App\Entity\Trait\Timestampable;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users'), HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_REFERENCE', fields: ['reference'])]
#[UniqueEntity('email')]
class User implements EntityInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;
    use SoftDeletable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'reference', type: 'uuid', nullable: false)]
    private Uuid $reference;

    // #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(name: 'email', type: 'string', length: 180, nullable: false)]
    private string $email;

    /** @var array<string> $roles */
    #[ORM\Column(name: 'roles', type: 'json', nullable: false)]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(name: 'password', type: 'string', length: 255, nullable: false)]
    private string $password;

    public function __construct()
    {
        $this->initializeTimestamps();
        $this->reference = Uuid::v4();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** @param list<string> $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getReference(): Uuid
    {
        return $this->reference;
    }

    public function setReference(Uuid $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
