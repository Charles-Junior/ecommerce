<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190608005325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE panier_produit_produit (panier_produit_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_F148216D6BE1476E (panier_produit_id), INDEX IDX_F148216DF347EFB (produit_id), PRIMARY KEY(panier_produit_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier_produit_produit ADD CONSTRAINT FK_F148216D6BE1476E FOREIGN KEY (panier_produit_id) REFERENCES panier_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit_produit ADD CONSTRAINT FK_F148216DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE panier_produit_produit');
    }
}
