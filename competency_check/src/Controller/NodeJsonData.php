<?php

namespace Drupal\competency_check\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NodeJsonData extends ControllerBase {

  /**
   * Return json data of the node
   */
  public static function load_node_jsondata($site_api_key,$nid) {
   $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
   $config = \Drupal::configFactory()->getEditable('siteapikey.configuration');
   $get_site_api_key = $config->get('siteapikey');
    
   /* If site api key is not correct and the node id not available, redirect to Access Denied page */
  if ($get_site_api_key != $site_api_key ||  empty($node)) {
     $url = Url::fromRoute('system.403');
     $response = new RedirectResponse($url->toString());
     return $response;
  }

   /* If site api key is  correct and the node id is available, show the json representation of the node */
   $serializer = \Drupal::service('serializer');
   $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
   $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
   return JsonResponse::fromJsonString($data);
  }

}
