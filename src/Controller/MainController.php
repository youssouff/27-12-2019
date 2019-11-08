<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shop()
    {
        return $this->render('shop/index_shop.html.twig');
    }

    /**
     * @Route("/event", name="event")
     */
    public function event(EvenementRepository $eventrepo)
    {
        $events = $eventrepo->findAll();
        return $this->render('event/index_event.html.twig', [
            'events' => $events
        ]);
    }

    /**
    * @Route("/event/{id}", name="event_show")
    */
    public function event_show(Evenement $event){
        
        
        return $this->render('event/show_event.html.twig', [
            'event' => $event
        ]);
    }

    

}
