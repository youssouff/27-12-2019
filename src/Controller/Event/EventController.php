<?php

namespace App\Controller\Event;

use App\Entity\Evenement;
use App\Entity\Upload;
use App\Entity\Photo;
use App\Repository\EvenementRepository;
use App\Form\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    
       /**
     * @Route("/event/later", name="event_later")
     */
    public function event_later(EvenementRepository $eventrepo)
    {
       

        $events = $eventrepo->eventsLater();
        
        return $this->render('event/index_event_later.html.twig', [
            'events' => $events,
            
        ]);
    }
    /**
     * @Route("/event/before", name="event_before")
     */
    public function event_before(EvenementRepository $eventrepo)
    {
        
    $events = $eventrepo->eventsBefore();
        
        return $this->render('event/index_event_before.html.twig', [
            'events' => $events
        ]);
    }

    /**
    * @Route("/event/{id}", name="show_event")
    */
    public function show_event(Evenement $event, Request $request, $id ){

        $upload = new Upload();
        $formUpload = $this->createForm(UploadType::class, $upload);
        $user = $this->getUser();
        $formUpload->handleRequest($request);
        
        
        if ($formUpload->isSubmitted() && $formUpload->isValid()) {
            
            $file = $upload->getName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory_photos'), $fileName);
            $upload->setName($fileName);

            $photo = new Photo;
            $photo->setUrl("../appdata/photos/".$fileName);
            $photo->setauthor( $user = $this->getUser() );
            $photo->setEvenement( $event );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
            
            return $this->redirectToRoute('show_event', [
                'id' => $id,
            ]);
        }
        $photos = $event->getPhotos();
        $now = new \DateTime('now');
        if($event->getDate() >= $now){
            $eventincoming = true;
        } else {
            $eventincoming = false;
        }
        
        return $this->render('event/show_event.html.twig', [
            'id' => $id,
            'event' => $event,
            'formUpload' => $formUpload->createView(),
            'incoming' => $eventincoming
        ]);
    }
    /**
    * @Route("/event/{id}/participate", name="participate")
    */
    public function participate(Evenement $event, $id){
        $user = $this->getUser();
        if(!$event->getParticipants() || !$event->getParticipants()->contains($user)){
            $event->addParticipant( $user );
            
        } else {
            $event->removeParticipant($user);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();


    
        return $this->redirectToRoute('show_event', [
            'event' => $event,
            'id' => $id,
        ]);
    }

    /**
    * @Route("/event/{id}/report", name="report_event")
    */
    public function report(Evenement $event, \Swift_Mailer $mailer){

        $user = $this->getUser();
        $message = (new \Swift_Message('Report'))
            ->setFrom($user->getUsername())
            ->setTo('montemonttheophile@gmail.com')//the bde's mail
            ->setBody(
                $this->renderView(
                    // templates/emails/order.html.twig
                    'emails/report.html.twig',
                    [
                    'item' => $event,
                    'user' => $user
                    ]
                ),'text/html');
    
            $mailer->send($message);

        return $this->redirectToRoute('event_before');
    }
}
