<?php

namespace App\Controller\Event;

use Spipu\Html2Pdf\Html2Pdf;
use \ZipAchive;
use App\Service\Api;
use App\Entity\Photo;
use App\Entity\Upload;
use App\Form\UploadType;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventController extends AbstractController
{
    
       /**
     * @Route("/event/later", name="event_later")
     */
    public function event_later(EvenementRepository $eventrepo)
    {   
        //lists the event incoming
        $events = $eventrepo->eventsLater();
        //returns the redering of the incoming events page
        return $this->render('event/index_event_later.html.twig', [
            'events' => $events,
            
        ]);
    }
    /**
     * @Route("/event/before", name="event_before")
     */
    public function event_before(EvenementRepository $eventrepo)
    {
        //lists the past events
        $events = $eventrepo->eventsBefore();
        //returns the rendering of the past events page
        return $this->render('event/index_event_before.html.twig', [
            'events' => $events
        ]);
    }

    /**
    * @Route("/event/{id}", name="show_event")
    */
    public function show_event(Evenement $event, Request $request, $id ){
        //construct upload entity for any upload
        $upload = new Upload();
        //creates the upload form
        $formUpload = $this->createForm(UploadType::class, $upload);
        //get the actual user
        $user = $this->getUser();
        //handles the upload request
        $formUpload->handleRequest($request);
        
        //if the upload is okay and exploitable
        if ($formUpload->isSubmitted() && $formUpload->isValid()) {
            
            //gets the file
            $file = $upload->getName();
            //moves it and sets it a new uniq name
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory_photos'), $fileName);
            $upload->setName($fileName);

            //creates the photo in the database
            $photo = new Photo;
            $photo->setUrl("../appdata/photos/".$fileName);
            $photo->setauthor( $user = $this->getUser() );
            $photo->setEvenement( $event );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
            //redirect to show event route
            return $this->redirectToRoute('show_event', [
                'id' => $id,
            ]);
        }
        //determine if the event is incoming or past
        $photos = $event->getPhotos();
        $now = new \DateTime('now');
        if($event->getDate() >= $now){
            $eventincoming = true;
        } else {
            $eventincoming = false;
        }
        //returns the rendering of the event page
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
        //get the actual user
        $user = $this->getUser();
        $now = new \DateTime('now');
        if($event->getDate() >= $now){
            if($event->getParticipants()){
                if (!in_array($user->getUsername(), $event->getParticipants())) 
                { 
                    $event->addParticipant( $user );
                }else{
                    $event->removeParticipant($user);
                }
            }else{
                $event->addParticipant( $user );
            }
        } 

        
            
        
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();
        //redirect to show event route
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
            //sends the mail
            $mailer->send($message);
        //redirect to the show event route   
        return $this->redirectToRoute('show_event', [
            'id' => $id
        ]);
    }
    /**
     * @Route("/event/{id}/download/participants", name="csv_participants")
     */
    public function download_participants(Evenement $event,$id){

        

        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML('<h1>Participants : </h1>'.implode("<br>",$event->getParticipants()));
        $html2pdf->output();

        return $this->redirectToRoute('show_event', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/event/{id}/download/photos", name="zip_photos")
     */
    public function download_photos($id){
        
        //not working at this moment

        return $this->redirectToRoute('show_event', [
            'id' => $id,
        ]);
    }
    
        

}
