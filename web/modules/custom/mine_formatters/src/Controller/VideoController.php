<?php

namespace Drupal\mine_formatters\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends ControllerBase {

  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $static = new static();
    $static->httpClient = $container->get('http_client');
    return $static;
  }

  public function vimeoHls(Request $request) {
    if (!$video_id = $request->query->get('video')) {
      return new JsonResponse([]);
    }

    $video_url = 'https://player.vimeo.com/video/' . $video_id . '/config';

    $response = $this->httpClient->get($video_url)->getBody()->getContents();
    $response = json_decode($response, TRUE);

    return new JsonResponse([
      'status' => '200',
      'url' => $response['request']['files']['hls']['cdns']['fastly_skyfire']['avc_url'],
      'width' => $response['video']['width'],
      'height' => $response['video']['height'],
    ]);
  }

}
