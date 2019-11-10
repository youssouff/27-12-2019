<?php

namespace App\Controller\Event;

use App\Entity\Evenement;
use App\Entity\Upload;
use App\Entity\Photo;
use App\Entity\Comment;
use App\Repository\EvenementRepository;
use App\Form\UploadType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function event_show(Evenement $event, Request $request, $id ){

        $upload = new Upload();
        $comment = new Comment();
        $formUpload = $this->createForm(UploadType::class, $upload);
        $formComment = $this->createForm(CommentType::class, $comment);

        $formUpload->handleRequest($request);
        $formComment->handleRequest($request);
        
        if ($formUpload->isSubmitted() && $formUpload->isValid()) {
            
            $file = $upload->getName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $upload->setName($fileName);

            $photo = new Photo;
            $photo->setUrl("../appdata/photos/".$fileName);
            $photo->setauthor( $user = $this->getUser() );
            $photo->setEvenement( $event );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
            
            return $this->redirectToRoute('event_show', [
                'id' => $id,
            ]);
        }
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            
            $comment->setauthor( $user = $this->getUser() );
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->render('event/show_event.html.twig', [
            'event' => $event,
            'formUpload' => $formUpload->createView(),
            //'formComment' => $formComment->createView(),
        ]);
    }
}
