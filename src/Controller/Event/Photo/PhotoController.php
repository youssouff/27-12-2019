<?php

namespace App\Controller\Event\Photo;

use App\Service\Api;
use App\Entity\Photo;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    //display photo view with it's comments
    /**
    * @Route("/photo/{id}", name="photo_show")
    */
    public function photo_show(Photo $photo, Request $request, $id ){
        //construct a comment and create it when user comments
        $comment = new Comment();
        //get the actual user
        $user = $this->getUser();
        //create the comment form
        $formComment = $this->createForm(CommentType::class, $comment);
        //handle the request of the form whenever the form is submitted
        $formComment->handleRequest($request);

        //if the form is okay and exploitable
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            //set the comment 
            $comment->setauthor( $user = $this->getUser() );
            $comment->setPhoto( $photo );
            //entering it in the Database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            //redirecting to actual route
            return $this->redirectToRoute('photo_show', [
                'id' => $id,
            ]);

        }
        //rendering photo view 
        return $this->render('event/photo/photo_show.html.twig', [
            'photo' => $photo,
            'formComment' => $formComment->createView(),
        ]);
    }
    
    /**
    * @Route("/photo/{id}/like/{route}", name="like_photo")
    */
    public function like_photo(Photo $photo, $id, Request $request, $route){
        //gets the actual user
        $user = $this->getUser();
        //likes or unlike it
        if($photo->getUsers()){
            if (!in_array($user->getUsername(), $photo->getUsers())) 
            { 
                $photo->addUser( $user );
            }else{
                $photo->removeUser($user);
            }
        }else{
            $photo->addUser( $user );
        }
        //saves the photo's state in database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->flush();
        
        //redirect to photo show route
        return $this->redirectToRoute("$route", [
            'photo' => $photo,
            'id' => $id
        ]);
    }
    /**
    * @Route("/comment/delete/{id}", name="delete_comment")
    */
    public function delete_comment(Comment $comment, Request $request){

        //if the token of the comment deletion is valid
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            //set the id of the photo on which the commment is posted
            $id = $comment->getPhoto()->getId();
            //removes the comment in the photo entity
            $photo = $comment->getPhoto();
            $photo->removeComment($comment);
            //remove the comment from the databae
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->remove($comment);
            $entityManager->flush();

        }
        //redirect to photo view route
        return $this->redirectToRoute('photo_show', [
        'photo' => $photo,
        'id' => $id
        ]);
        

    }

    /**
    * @Route("/comment/{id}/report", name="report_comment")
    */
    public function report_comment(Comment $comment, \Swift_Mailer $mailer, Api $api){
        //gets the actual user
        $user = $this->getUser();
        //gets the comment's author
        $author = $comment->getAuthor();
        //get the id of the comment's photo
        $id = $comment->getPhoto()->getId();
        //prepare the mail to report the comment
        $message = (new \Swift_Message('Report'))
            ->setFrom($user->getUsername())
            ->setTo('montemonttheophile@gmail.com')//the bde's mail
            ->setBody(
                $this->renderView(
                    // templates/emails/order.html.twig
                    'emails/report.html.twig',
                    [
                    'author' => $author,
                    'item' => $comment,
                    'user' => $user
                    ]
                ),'text/html');
            //sends the mail
            $mailer->send($message);
        //return to photo view route
        return $this->redirectToRoute('photo_show', [
            'id' => $id
        ]);
    }

    /**
    * @Route("/photo/delete/{id}", name="delete_photo")
    */
    public function delete_photo(Photo $photo,$id, Request $request){

        //if the token of the photo deletion is valid
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $id = $photo->getEvenement()->getId();
            //deletes all of the photo's comments
            $photo->clearComments();
            //removes the photo in the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
    
        }
        //redirect to event view route
        return $this->redirectToRoute('show_event', [
        'photo' => $photo,
        'id' => $id
        ]);
    }

    /**
    * @Route("/photo/{id}/report", name="report_photo")
    */
    public function report_photo(Photo $photo, \Swift_Mailer $mailer, Api $api){
        //get the id of the photo's event
        $id = $photo->getEvenement()->getId();
        //gets the actual user
        $user = $this->getUser();
        //gets the photo's author
        $author = $photo->getAuthor();
        //prepare the mail to report the photo
        $message = (new \Swift_Message('Report'))
            ->setFrom($user->getUsername())
            ->setTo('montemonttheophile@gmail.com')//the bde's mail
            ->setBody(
                $this->renderView(
                    // templates/emails/report.html.twig
                    'emails/report.html.twig',
                    [
                        'author' => $author,
                        'item' => $photo,
                        'user' => $user
                    ]
                ),'text/html');
            //sends the mail
            $mailer->send($message);
        //redirect to event show view
        return $this->redirectToRoute('show_event', [
            'id' => $id
            ]);
    }

}
