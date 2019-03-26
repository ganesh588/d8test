<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Plugin\Block\RSVPBlock
 */

 namespace Drupal\rsvplist\Plugin\Block;

 use Drupal\Core\Block\BlockBase;
 use Drupal\Core\Session\AccountInterface;
 use Drupal\Core\Access\AccessResult;

 /**
  * Provides an 'RSVP' list Block
  * @Block(
  *   id = "rsvp_block",
  *   admin_label = @Translation("RSVP Block"),
  * )
  */

  class RSVPBlock extends BlockBase {
      /**
       * {@inheritDoc}
       */
      public function build(){
          return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
      }
      public function blockAccess(AccountInterface $account) {
          $node = \Drupal::routeMatch()->getParameter('node');          
          if (!empty($node)) {
            $nid = $node->nid->value;
            $current_node_type = $node->getType();
          }
          $enabler = \Drupal::service('rsvplist.enabler');      
          $config = \Drupal::config('rsvplist.settings');
          $types = $config->get('allowed_types', array());
          if (is_numeric($nid)) {
            if (in_array($current_node_type, $types)){
              if ($enabler->isEnabled($node)) {
                return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
              }
            }
          }
          return AccessResult::forbidden();
      }
  }