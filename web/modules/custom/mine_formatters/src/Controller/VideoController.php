<?php

namespace Drupal\mine_formatters\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends ControllerBase
{

  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $static = new static();
    $static->httpClient = $container->get('http_client');
    return $static;
  }

  public function vimeoHls(Request $request)
  {
    if (!$video_id = $request->query->get('video')) {
      return new JsonResponse([]);
    }

    $video_url = 'https://api.vimeo.com/videos/' . $video_id;

    $response = $this->httpClient->get($video_url, [
      RequestOptions::HEADERS => [
        'Authorization' => 'Bearer ' . $this->config('dms_field_formatters')->get('vimeo.access_token'),
      ],
    ])->getBody()->getContents();
    $response = json_decode($response, TRUE);

    return new JsonResponse([
      'status' => '200',
      'url' => $response['play']['hls']['link'] ?? null,
      'width' => $response['width'],
      'height' => $response['height'],
    ]);
  }
}
