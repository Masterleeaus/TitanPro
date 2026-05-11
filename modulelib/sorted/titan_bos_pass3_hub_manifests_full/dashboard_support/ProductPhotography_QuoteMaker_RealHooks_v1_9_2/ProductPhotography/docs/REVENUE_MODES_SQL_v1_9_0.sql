START TRANSACTION;

CREATE TABLE IF NOT EXISTS `ext_quotemaker_builders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `mode` VARCHAR(50) NOT NULL DEFAULT 'quote',
  `vertical` VARCHAR(255) NULL,
  `service_type` VARCHAR(255) NULL,
  `variation` VARCHAR(255) NULL,
  `theme` VARCHAR(255) NULL,
  `visual_mode` VARCHAR(255) NULL,
  `package_tier` VARCHAR(255) NULL,
  `summary` TEXT NULL,
  `pricing_model` VARCHAR(255) NULL,
  `follow_up_delay_hours` INT NULL,
  `follow_up_channel` VARCHAR(255) NULL,
  `auto_create_booking` TINYINT(1) NOT NULL DEFAULT 0,
  `auto_create_job` TINYINT(1) NOT NULL DEFAULT 0,
  `auto_send_invoice` TINYINT(1) NOT NULL DEFAULT 0,
  `auto_negotiate` TINYINT(1) NOT NULL DEFAULT 0,
  `metadata_json` LONGTEXT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ext_quotemaker_templates`
  ADD COLUMN IF NOT EXISTS `vertical` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `variation` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `theme` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `pricing_model` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `metadata_json` LONGTEXT NULL;

COMMIT;
