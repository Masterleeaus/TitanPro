ALTER TABLE `tz_zero_proposals`
  ADD COLUMN IF NOT EXISTS `signal_stage` VARCHAR(50) NULL AFTER `status`,
  ADD COLUMN IF NOT EXISTS `process_status` VARCHAR(50) NULL AFTER `signal_stage`,
  ADD COLUMN IF NOT EXISTS `node_origin` VARCHAR(50) NULL AFTER `process_status`,
  ADD COLUMN IF NOT EXISTS `node_id` VARCHAR(150) NULL AFTER `node_origin`;

CREATE TABLE IF NOT EXISTS `tz_zero_node_trust` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` BIGINT UNSIGNED NULL,
  `company_id` BIGINT UNSIGNED NULL,
  `node_id` VARCHAR(150) NOT NULL,
  `node_origin` VARCHAR(50) NULL,
  `trust_level` VARCHAR(50) NOT NULL DEFAULT 'standard',
  `meta_json` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tz_zero_node_trust_team_idx` (`team_id`),
  KEY `tz_zero_node_trust_node_idx` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tz_zero_site_memory` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` BIGINT UNSIGNED NULL,
  `company_id` BIGINT UNSIGNED NULL,
  `site_id` BIGINT UNSIGNED NULL,
  `memory_type` VARCHAR(100) NOT NULL,
  `memory_value` LONGTEXT NULL,
  `is_sensitive` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tz_zero_site_memory_team_idx` (`team_id`),
  KEY `tz_zero_site_memory_site_idx` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tz_zero_job_memory` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` BIGINT UNSIGNED NULL,
  `company_id` BIGINT UNSIGNED NULL,
  `job_id` BIGINT UNSIGNED NULL,
  `memory_type` VARCHAR(100) NOT NULL,
  `memory_value` LONGTEXT NULL,
  `is_sensitive` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tz_zero_job_memory_team_idx` (`team_id`),
  KEY `tz_zero_job_memory_job_idx` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `tz_zero_signal_registry` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `signal_key` VARCHAR(150) NOT NULL,
  `signal_stage` VARCHAR(50) NOT NULL DEFAULT 'signal',
  `direction` VARCHAR(50) NOT NULL DEFAULT 'internal',
  `meta_json` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tz_zero_signal_registry_key_unique` (`signal_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tz_zero_federation_handshakes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team_id` BIGINT UNSIGNED NULL,
  `company_id` BIGINT UNSIGNED NULL,
  `node_id` VARCHAR(150) NOT NULL,
  `node_origin` VARCHAR(50) NULL,
  `trust_level` VARCHAR(50) NOT NULL DEFAULT 'standard',
  `handshake_payload` LONGTEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tz_zero_federation_handshakes_team_idx` (`team_id`),
  KEY `tz_zero_federation_handshakes_node_idx` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
