<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use App\Entity\Product;
use JMS\Serializer\SerializerBuilder;

class ProductController extends AbstractController
{
    /**
    * @Route("/api/product/index", methods={"GET"}, defaults={"page": 1})
    */
    public function index(Request $request, int $page, ProductRepository $products, LoggerInterface $logger): Response
    {
        $latestProducts = $products->findLatest($page, 3);
        $serializer = SerializerBuilder::create()->build();
        return new Response(
            $serializer->serialize($latestProducts, 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
    * @Route("/api/product/filter", methods={"GET"}, defaults={"page": 1})
    */
    public function filter(Request $request, int $page, ProductRepository $products, LoggerInterface $logger): Response
    {
        $data = $request->toArray();
        if(array_key_exists('filters', $data)){
            $filters = $data['filters'];
        }
        $filterdProducts =  $products->findByFilter($page, 3, $filters);
        $serializer = SerializerBuilder::create()->build();
        return new Response(
            $serializer->serialize($filterdProducts, 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}

