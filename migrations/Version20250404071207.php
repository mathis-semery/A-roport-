<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404071207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avion ADD ref_modele_id INT NOT NULL');
        $this->addSql('ALTER TABLE avion ADD CONSTRAINT FK_234D9D38DF4EB64F FOREIGN KEY (ref_modele_id) REFERENCES modele (id)');
        $this->addSql('CREATE INDEX IDX_234D9D38DF4EB64F ON avion (ref_modele_id)');
        $this->addSql('ALTER TABLE vol ADD ref_avion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EB6AC7EC6 FOREIGN KEY (ref_avion_id) REFERENCES avion (id)');
        $this->addSql('CREATE INDEX IDX_95C97EB6AC7EC6 ON vol (ref_avion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avion DROP FOREIGN KEY FK_234D9D38DF4EB64F');
        $this->addSql('DROP INDEX IDX_234D9D38DF4EB64F ON avion');
        $this->addSql('ALTER TABLE avion DROP ref_modele_id');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB6AC7EC6');
        $this->addSql('DROP INDEX IDX_95C97EB6AC7EC6 ON vol');
        $this->addSql('ALTER TABLE vol DROP ref_avion_id');
    }
}
