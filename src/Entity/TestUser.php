<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TestUserRepository;

#[ORM\Entity(repositoryClass: TestUserRepository::class)]
#[ORM\Table(name: 'test_users')]
class TestUser
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private ?int $id = null;

	#[ORM\Column(name: 'username', type: 'string', length: 20)]
	private string $userName;

	#[ORM\Column(name: 'email', type: 'string', length: 75)]
	private string $emailAddress;

	#[ORM\Column(name: 'password', type: 'string', length: 255)]
	private string $passwordHash;

	#[ORM\Column(name: 'is_member', type: 'boolean')]
	private bool $isMember;

	#[ORM\Column(name: 'is_active', type: 'boolean', nullable: true)]
	private ?bool $isActive = null;

	#[ORM\Column(name: 'user_type', type: 'integer')]
	private int $userType;

	#[ORM\Column(name: 'last_login_at', type: 'datetime', nullable: true)]
	private ?\DateTimeInterface $lastLoginAt = null;

	#[ORM\Column(name: 'created_at', type: 'datetime')]
	private \DateTimeInterface $createdAt;

	#[ORM\Column(name: 'updated_at', type: 'datetime')]
	private \DateTimeInterface $updatedAt;
}
