<?php


namespace App\Controller\Api;

use App\Service\RequestValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class AbstractApiController extends AbstractController
{
    protected $serializer;
    protected $em;
    protected $requestValidatorService;

    public function __construct(SerializerInterface $serializer,
                                EntityManagerInterface $entityManager,
                                RequestValidatorService $requestValidatorService
    )
    {
        $this->serializer = $serializer;
        $this->em = $entityManager;
        $this->requestValidatorService = $requestValidatorService;
    }

    /**
     * @param $data
     * @param int $code
     * @param array $headers
     * @return Response
     */
    protected function createResponse($data, $code = 200, $headers = [])
    {
        $response = new Response($data, $code, $headers);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}