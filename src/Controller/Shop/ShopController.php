<?php

namespace App\Controller\Shop;

use App\Entity\Goodies;
use App\Repository\GoodiesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/shop")
 * */
class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop")
     */
    public function shop(GoodiesRepository $repository)
    {
        $goodies = $repository->findAll();
        return $this->render('shop/index_shop.html.twig',[
            'goodies' => $goodies
        ]);
    }

    /**
     * @Route("/{id}", name="shop_show", methods={"GET"})
     */
    public function show(Goodies $goody): Response
    {
        return $this->render('shop/show.html.twig', [
            'goody' => $goody,
        ]);
    }
}
