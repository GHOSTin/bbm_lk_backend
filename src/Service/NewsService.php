<?php


namespace App\Service;



use App\Helper\Mapped\News;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class NewsService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(
        ContainerInterface $container,
        SerializerInterface $serializer
    )
    {
        $this->container = $container;
        $this->serializer = $serializer;
    }

    public function parseRSS() {
        $urlRSS = $this->container->getParameter('url_rss');

        $feed = implode(file($urlRSS));
        $xml = simplexml_load_string($feed);
        $json = json_encode($xml);

        $news = $this->serializer->deserialize(
            $json,
            News::class,
            'json',
            [
                'group' => 'denormalizeIndex'
            ]
        );
        return $news;
    }

}