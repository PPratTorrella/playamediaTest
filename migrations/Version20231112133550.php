<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112133550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

	public function up(Schema $schema): void
	{
		$this->addSql('DROP TABLE IF EXISTS test_users');

		$this->addSql('CREATE TABLE test_users (
			id INT AUTO_INCREMENT NOT NULL,
			username VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL DEFAULT \'\',
			email VARCHAR(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\',
			password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
			is_member TINYINT(1) NOT NULL DEFAULT \'0\',
			is_active TINYINT(1) DEFAULT NULL,
			user_type INT NOT NULL,
			last_login_at TIMESTAMP DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY(id),
			KEY is_active (is_active)
		) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
	}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
