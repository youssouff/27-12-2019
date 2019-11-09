<?php

namespace App\Controller\Event;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
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
