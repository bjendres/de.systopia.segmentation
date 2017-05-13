<?php
/*-------------------------------------------------------+
| SYSTOPIA Contact Segmentation Extension                |
| Copyright (C) 2017 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/


/**
 * Basic configuration hub
 */
class CRM_Segmentation_Configuration {

  protected static $custom_group_id = NULL;
  protected static $option_group_id = NULL;
  protected static $custom_fields = NULL;

  /**
   * Get the ID of the segmentation custom group
   */
  public static function groupID() {
    if (self::$custom_group_id === NULL) {
      $query = civicrm_api3('CustomGroup', 'getvalue', array(
        'return'     => 'id',
        'table_name' => 'civicrm_segmentation'));
      self::$custom_group_id = (int) $query;
    }
    return self::$custom_group_id;
  }


  /**
   * get all custom fileds in the segmentation custom group
   */
  public static function segmentationFields() {
    if (self::$custom_fields === NULL) {
      $query = civicrm_api3('CustomField', 'get', array('custom_group_id' => self::groupID()));
      self::$custom_fields = $query['values'];
    }
    return self::$custom_fields;
  }

  /**
   * get the ID of the segmentation custom field with the given column name
   */
  public static function getFieldID($column_name) {
    $fields = self::segmentationFields();
    foreach ($fields as $field) {
      if ($field['column_name'] == $column_name) {
        return $field['id'];
      }
    }
    // not found
    return NULL;
  }

  /**
   * Get the ID of the segmentation custom group
   */
  public static function segmentsGroupID() {
    if (self::$option_group_id === NULL) {
      $query = civicrm_api3('OptionGroup', 'getvalue', array(
        'return' => 'id',
        'name'   => 'segments'));
      self::$option_group_id = (int) $query;
    }
    return self::$option_group_id;
  }

  /**
   * get a SQL query to list all the segment lines for the given
   * campaign (and a list of selected segments if given)
   *
   * @param $campaign_id  id of campaign
   * @param $segment_list  array of segment IDs
   */
  public static function getSegmentQuery($campaign_id, $segment_list = NULL) {
    $_campaign_id = (int) $campaign_id;
    if (!$_campaign_id) {
      throw new Exception("Illegal campaign_id '{$campaign_id}'", 1);
    }

    if (empty($segment_list)) {
      $segment_condition = '';
    } else {
      $segment_list_string = implode(',', $segment_list);
      $segment_condition = "AND segment_id IN ({$segment_list_string})";
    }

    return "
     SELECT
      entity_id     AS contact_id,
      datetime      AS datetime,
      campaign_id   AS campaign_id,
      segment_id    AS segment_id,
      name          AS segment_name,
      test_group    AS test_group,
      membership_id AS membership_id
     FROM civicrm_segmentation
     LEFT JOIN civicrm_segmentation_index ON civicrm_segmentation_index.id = civicrm_segmentation.segment_id
     WHERE campaign_id = {$_campaign_id}
     {$segment_condition}";
  }
}
