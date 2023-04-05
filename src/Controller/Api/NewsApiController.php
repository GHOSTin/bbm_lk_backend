<?php

namespace App\Controller\Api;

use App\Helper\Mapped\News;
use App\Service\NewsService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewsApiController
 * @package  App\Controller\Api
 */
class NewsApiController extends AbstractApiController
{
    /**
     *
     * @SWG\Tag(name="News")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get News",
     *      @SWG\Schema(
     *          @SWG\Property(property="data", type="object",
     *              @SWG\Property(property="news", type="array",
     *                  @SWG\Items(
     *                      ref=@Model(type=News::class, groups={"show"})
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @SWG\Parameter(
     *   name="apikey",
     *   type="string",
     *   required=true,
     *   in="header",
     *   description="auth user's apikey"
     * )
     *
     * @Route("/news", name="api_news", methods={"GET"})
     * @param Request $request
     * @param NewsService $newsService
     * @return Response
     */
    public function getNews(Request $request, NewsService $newsService)
    {
        $news = $newsService->parseRSS();
        if (!empty($news)) {
            $data['data']['news'] = $news;
        } else {
            $data['data']['news'] = [];
        }
        $json = $this->serializer->serialize($data, 'json', ['groups' => ['show']]);
        return $this->createResponse($json, Response::HTTP_OK);
    }
}
