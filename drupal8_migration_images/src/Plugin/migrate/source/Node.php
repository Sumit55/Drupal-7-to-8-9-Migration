<?php

namespace Drupal\drupal8_migration_images\Plugin\migrate\source;

use Drupal\migrate\Row;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Drupal 8 node source from database.
 *
 * @MigrateSource(
 *   id = "migrate_d8_node",
 *   source_provider = "node"
 * )
 */
class Node extends SqlBase {
  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The join options between the node and the node_revisions table.
   */
  const JOIN = 'n.vid = nr.vid';

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Select node in its last revision.
    $query = $this->select('node_revision', 'nr')
      ->fields('n', [
        'nid',
        'type',
        'status',
        'created',
        'changed',
        'promote',
        'sticky',
      ])
      ->fields('nr', [
        'vid',
        'title',
      ])
      ->fields('nb', [
        'body_value',
      ]);
    $query->innerJoin('node', 'n', static::JOIN);
    $query->innerJoin('field_revision_body', 'nb', 'n.vid = nb.revision_id');

    if (isset($this->configuration['node_type'])) {
      $query->condition('n.type', $this->configuration['node_type']);
    }
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    //$nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');
    $query = $this->select('field_revision_field_image', 'i')
      ->fields('i', [
        'field_image_fid',
      ])
      ->condition('revision_id', $vid)
      ->execute()
      ->fetchAll();
    $row->setSourceProperty('field_image', $query);
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'nid' => $this->t('Node ID'),
      'type' => $this->t('Type'),
      'title' => $this->t('Title'),
      'node_uid' => $this->t('Node authored by (uid)'),
      'revision_uid' => $this->t('Revision authored by (uid)'),
      'created' => $this->t('Created timestamp'),
      'changed' => $this->t('Modified timestamp'),
      'status' => $this->t('Published'),
      'promote' => $this->t('Promoted to front page'),
      'sticky' => $this->t('Sticky at top of lists'),
      'revision' => $this->t('Create new revision'),
      'language' => $this->t('Language (fr, en, ...)'),
      'tnid' => $this->t('The translation set id for this node'),
      'timestamp' => $this->t('The timestamp the latest revision of this node was created.'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['nid']['type'] = 'integer';
    $ids['nid']['alias'] = 'n';
    return $ids;
  }

}
