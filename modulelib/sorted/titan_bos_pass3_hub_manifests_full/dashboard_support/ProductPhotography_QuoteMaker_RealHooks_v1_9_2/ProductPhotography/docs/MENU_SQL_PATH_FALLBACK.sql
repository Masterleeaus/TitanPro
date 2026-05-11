START TRANSACTION;

INSERT INTO `extensions` (`version`,`slug`,`installed`,`created_at`,`updated_at`,`is_theme`)
SELECT '1.7.0','productphotography',1,NOW(),NOW(),0
WHERE NOT EXISTS (SELECT 1 FROM `extensions` WHERE `slug`='productphotography');

UPDATE `extensions`
SET `version`='1.7.0',`installed`=1,`updated_at`=NOW(),`is_theme`=0
WHERE `slug`='productphotography';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT NULL,'ext_quotemaker','dashboard.user.quotemaker.index',NULL,'QuoteMaker','tabler-file-invoice',NULL,25,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
WHERE NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.index',`label`='QuoteMaker',`updated_at`=NOW()
WHERE `key`='ext_quotemaker';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT m.id,'ext_quotemaker_gallery','dashboard.user.quotemaker.gallery',NULL,'Quote Gallery','tabler-photo',NULL,1,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
FROM `menus` m
WHERE m.`key`='ext_quotemaker'
AND NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker_gallery');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.gallery',`label`='Quote Gallery',`updated_at`=NOW()
WHERE `key`='ext_quotemaker_gallery';

CREATE TABLE IF NOT EXISTS `ext_quotemaker_visuals` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_type` VARCHAR(255) NULL,
  `site_type` VARCHAR(255) NULL,
  `work_area` VARCHAR(255) NULL,
  `scope_notes` TEXT NULL,
  `visual_mode` VARCHAR(255) DEFAULT 'quote_preview',
  `package_tier` VARCHAR(255) NULL,
  `quote_reference` VARCHAR(255) NULL,
  `customer_context` TEXT NULL,
  `generated_prompt` LONGTEXT NULL,
  `render_payload_json` LONGTEXT NULL,
  `result_title` VARCHAR(255) NULL,
  `result_summary` TEXT NULL,
  `usage_tag` VARCHAR(255) NULL,
  `image_path` VARCHAR(255) NULL,
  `status` VARCHAR(50) DEFAULT 'draft',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
