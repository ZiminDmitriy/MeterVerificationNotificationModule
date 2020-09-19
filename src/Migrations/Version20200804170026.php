<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804170026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sifted_prepared_notification_data (id VARCHAR(255) NOT NULL, contract VARCHAR(255) NOT NULL, meter_id VARCHAR(255) NOT NULL, meter_next_check_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', abonent_email VARCHAR(255) DEFAULT NULL, abonent_phone_number VARCHAR(255) DEFAULT NULL, meter_factory_number VARCHAR(255) DEFAULT NULL, meter_model VARCHAR(255) DEFAULT NULL, sending_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX sending_date_index (sending_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prepared_notification_data (id VARCHAR(255) NOT NULL, contract VARCHAR(255) NOT NULL, meter_id VARCHAR(255) NOT NULL, meter_next_check_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', abonent_email VARCHAR(255) DEFAULT NULL, abonent_phone_number VARCHAR(255) DEFAULT NULL, meter_factory_number VARCHAR(255) DEFAULT NULL, meter_model VARCHAR(255) DEFAULT NULL, UNIQUE INDEX meter_id_unique (meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_jobid (id INT NOT NULL, notification_retransmission_guard_id VARCHAR(255) NOT NULL, sending_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', execution_status VARCHAR(255) NOT NULL, common_messages_quantity INT NOT NULL, UNIQUE INDEX UNIQ_5E2F0BC2FDD556A (notification_retransmission_guard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_retransmission_guard (id VARCHAR(255) NOT NULL, sending_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX sending_date_unique (sending_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE executed_notification_data (id VARCHAR(255) NOT NULL, notification_jobid_id INT NOT NULL, contract VARCHAR(255) NOT NULL, meter_id VARCHAR(255) NOT NULL, meter_next_check_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_B516EFE4F0BDA0A8 (notification_jobid_id), INDEX contract_index (contract), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_jobid ADD CONSTRAINT FK_5E2F0BC2FDD556A FOREIGN KEY (notification_retransmission_guard_id) REFERENCES notification_retransmission_guard (id)');
        $this->addSql('ALTER TABLE executed_notification_data ADD CONSTRAINT FK_B516EFE4F0BDA0A8 FOREIGN KEY (notification_jobid_id) REFERENCES notification_jobid (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE executed_notification_data DROP FOREIGN KEY FK_B516EFE4F0BDA0A8');
        $this->addSql('ALTER TABLE notification_jobid DROP FOREIGN KEY FK_5E2F0BC2FDD556A');
        $this->addSql('DROP TABLE sifted_prepared_notification_data');
        $this->addSql('DROP TABLE prepared_notification_data');
        $this->addSql('DROP TABLE notification_jobid');
        $this->addSql('DROP TABLE notification_retransmission_guard');
        $this->addSql('DROP TABLE executed_notification_data');
    }
}
