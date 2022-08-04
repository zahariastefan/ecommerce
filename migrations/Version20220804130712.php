<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804130712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD url_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5FC54BE4 FOREIGN KEY (url_image_id) REFERENCES url_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5FC54BE4 ON product (url_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5FC54BE4');
        $this->addSql('DROP INDEX UNIQ_D34A04AD5FC54BE4 ON product');
        $this->addSql('ALTER TABLE product DROP url_image_id');
    }
}
