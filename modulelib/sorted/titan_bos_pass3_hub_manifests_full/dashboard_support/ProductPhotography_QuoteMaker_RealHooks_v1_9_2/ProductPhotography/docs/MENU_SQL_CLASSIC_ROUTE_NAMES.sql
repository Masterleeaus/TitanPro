START TRANSACTION;

INSERT INTO `extensions` (`version`,`slug`,`installed`,`created_at`,`updated_at`,`is_theme`)
SELECT '1.8.5','productphotography',1,NOW(),NOW(),0
WHERE NOT EXISTS (SELECT 1 FROM `extensions` WHERE `slug`='productphotography');

UPDATE `extensions`
SET `version`='1.8.5', `installed`=1, `updated_at`=NOW(), `is_theme`=0
WHERE `slug`='productphotography';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT NULL,'ext_quotemaker','dashboard.user.quotemaker.index',NULL,'QuoteMaker','tabler-file-invoice',NULL,25,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
WHERE NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.index',`label`='QuoteMaker',`icon`='tabler-file-invoice',`updated_at`=NOW(),`is_active`=1
WHERE `key`='ext_quotemaker';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT m.id,'ext_quotemaker_builder','dashboard.user.quotemaker.builder',NULL,'Quote Builder','tabler-sparkles',NULL,0,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
FROM `menus` m WHERE m.`key`='ext_quotemaker'
AND NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker_builder');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.builder',`label`='Quote Builder',`icon`='tabler-sparkles',`updated_at`=NOW(),`is_active`=1
WHERE `key`='ext_quotemaker_builder';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT m.id,'ext_quotemaker_templates','dashboard.user.quotemaker.templates',NULL,'Templates','tabler-layout-grid',NULL,1,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
FROM `menus` m WHERE m.`key`='ext_quotemaker'
AND NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker_templates');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.templates',`label`='Templates',`icon`='tabler-layout-grid',`updated_at`=NOW(),`is_active`=1
WHERE `key`='ext_quotemaker_templates';

INSERT INTO `menus`
(`parent_id`,`key`,`route`,`route_slug`,`label`,`icon`,`svg`,`order`,`is_active`,`params`,`type`,`extension`,`bolt_menu`,`bolt_background`,`bolt_foreground`,`letter_icon`,`letter_icon_bg`,`created_at`,`updated_at`,`custom_menu`)
SELECT m.id,'ext_quotemaker_gallery','dashboard.user.quotemaker.gallery',NULL,'Drafts','tabler-photo',NULL,2,1,'[]','item','1',0,NULL,NULL,NULL,NULL,NOW(),NOW(),0
FROM `menus` m WHERE m.`key`='ext_quotemaker'
AND NOT EXISTS (SELECT 1 FROM `menus` WHERE `key`='ext_quotemaker_gallery');

UPDATE `menus`
SET `route`='dashboard.user.quotemaker.gallery',`label`='Drafts',`icon`='tabler-photo',`updated_at`=NOW(),`is_active`=1
WHERE `key`='ext_quotemaker_gallery';

COMMIT;
