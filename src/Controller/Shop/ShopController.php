<?php

namespace App\Controller\Shop;

use App\Entity\Goodies;
use App\Entity\GoodiesSearch;
use App\Form\GoodiesSearchType;
use App\Repository\GoodiesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/shop")
 * */
class ShopController extends AbstractController
{

    private $repository;
    private $manager;
    public function __construct(GoodiesRepository $repository, ObjectManager $manager){
        $this->repository = $repository;
        $this->manager = $manager;
    }

    
    /**
     * @Route("/", name="shop")
     */
    public function shop(PaginatorInterface $paginator, Request $request, GoodiesRepository $repository)
    {
        $search = new GoodiesSearch();
        $form = $this->createForm(GoodiesSearchType::class, $search);
        $form->handleRequest($request);

        $goodies = $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        
        return $this->render('shop/index_shop.html.twig',[
            'goodies' => $goodies,
            'form' => $form->createView()
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
