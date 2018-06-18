-- CREATE SEGMENT INDEX TABLE
CREATE TABLE IF NOT EXISTS `civicrm_segmentation_index`(
  `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Segment ID',
  `name` varchar(255)                        COMMENT 'Segment Name',
  PRIMARY KEY ( `id` ),
  INDEX `index_name` (name)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;


-- CREATE SEGMENT INDEX TABLE
CREATE TABLE IF NOT EXISTS `civicrm_segmentation_order`(
  `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Order Entry ID',
  `campaign_id`  int unsigned  COMMENT 'Orders are defined per campaign',
  `segment_id`   int unsigned  COMMENT 'Segement ID',
  `order_number` int unsigned  COMMENT 'Order index from 1..n (highest to lowest)',
  `bundle` varchar(255) NULL,
  `text_block` varchar(255) NULL,
  PRIMARY KEY ( `id` ),
  UNIQUE INDEX `segment_order` (campaign_id,segment_id),
  INDEX `order_number` (order_number),
  INDEX `campaign_id` (campaign_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- CREATE SEGMENT TO ACTIVITY TABLE
CREATE TABLE IF NOT EXISTS `civicrm_activity_contact_segmentation` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ActivityContact Segment Entry ID',
  `activity_contact_id` INT UNSIGNED NOT NULL COMMENT 'ActivityContact ID',
  `segment_id` INT UNSIGNED NOT NULL COMMENT 'Segment ID',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `activity_contact_id_UNIQUE` (`activity_contact_id` ASC),
  CONSTRAINT `FK_civicrm_activity_contact_segmentation_activity_contact_id` FOREIGN KEY (`activity_contact_id`) REFERENCES `civicrm_activity_contact` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_civicrm_activity_contact_segmentation_segment_id` FOREIGN KEY (`segment_id`) REFERENCES `civicrm_segmentation_index` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;