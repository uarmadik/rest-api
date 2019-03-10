<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use FOS\RestBundle\Controller\Annotations as Rest;
use GuzzleHttp\Client;



class IndexController extends AbstractController
{
//    /**
//     * @Route("/test", name="web_test", methods={"GET"})
//     */
    /**
     * @Rest\Get("/test")
     */
    public function testAction()
    {
        exit('Test Action');
    }
}