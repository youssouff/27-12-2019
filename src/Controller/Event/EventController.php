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
    * @Route("/event/photo/{id}", name="photo_show")
    */
    public function photo_show(Photo $photo, Request $request, $id ){
        $liked = false;
        $comment = new Comment();
        $user = $this->getUser();
        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            
            $comment->setauthor( $user = $this->getUser() );
            $comment->setPhoto( $photo );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('photo_show', [
                'id' => $id,
            ]);


        }

        return $this->render('event/photo/photo_show.html.twig', [
            'photo' => $photo,
            'formComment' => $formComment->createView(),
        ]);
    }
    
    /**
    * @Route("/event/photo/{id}/like", name="like_photo")
    */
    public function like_photo(Photo $photo, $id){

        $user = $this->getUser();
        if(!$photo->getUsers() || !$photo->getUsers()->contains($user)){
            $photo->addUser( $user );
            
        } else {
            $photo->removeUser($user);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->flush();
        

        return $this->redirectToRoute('photo_show', [
            'photo' => $photo,
            'id' => $id
        ]);
    }
    /**
    * @Route("/comment/delete/{id}", name="delete_comment")
    */
    public function delete_comment(Comment $comment, Request $request){

        
    if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
        $id = $comment->getPhoto()->getId();
        $photo = $comment->getPhoto();
        $photo->removeComment($comment);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->remove($comment);
        $entityManager->flush();

    }
        return $this->redirectToRoute('photo_show', [
        'photo' => $photo,
        'id' => $id
        ]);
        

    }
    /**
    * @Route("/photo/delete/{id}", name="delete_photo")
    */
    public function delete_photo(Photo $photo,$id, Request $request){

        
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $id = $photo->getEvenement()->getId();
            $photo->clearComments();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
    
        }
            return $this->redirectToRoute('show_event', [
            'photo' => $photo,
            'id' => $id
            ]);
            
    
        }
}
