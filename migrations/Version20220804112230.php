<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804112230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest_orders CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guest_orders ADD CONSTRAINT FK_FFE0D7044584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_FFE0D7044584665A ON guest_orders (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest_orders DROP FOREIGN KEY FK_FFE0D7044584665A');
        $this->addSql('DROP INDEX IDX_FFE0D7044584665A ON guest_orders');
        $this->addSql('ALTER TABLE guest_orders CHANGE product_id product_id INT NOT NULL');
    }
}
