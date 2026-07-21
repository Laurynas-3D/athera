<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260721175929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle_number_plates (id INT AUTO_INCREMENT NOT NULL, device_id VARCHAR(128) NOT NULL, vehicle_registration_number_part1 VARCHAR(12) DEFAULT NULL, vehicle_registration_number_part2 VARCHAR(12) DEFAULT NULL, UNIQUE INDEX UNIQ_2513876B94A4C7D4 (device_id), INDEX IDX_2513876B94A4C7D4 (device_id), INDEX IDX_2513876B437CB4D4 (vehicle_registration_number_part1), INDEX IDX_2513876BDA75E56E (vehicle_registration_number_part2), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vehicle_record (id INT AUTO_INCREMENT NOT NULL, device_id VARCHAR(128) NOT NULL, created_at DATETIME DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, altitude INT DEFAULT NULL, speed INT DEFAULT NULL, ignition INT DEFAULT NULL, movement INT DEFAULT NULL, gsm_signal INT UNSIGNED, total_odometer BIGINT DEFAULT NULL, engine_total_fuel_used BIGINT DEFAULT NULL, recorded_at DATETIME NOT NULL, INDEX IDX_32EA9E42D74808F5 (recorded_at), INDEX idx_device_time (device_id, recorded_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle_number_plates');
        $this->addSql('DROP TABLE vehicle_record');
    }
}
