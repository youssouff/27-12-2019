<?php

namespace App\Controller\Shop;

use App\Entity\Goodies;
use App\Entity\OrderHistory;
use App\Entity\GoodiesSearch;
use App\Form\GoodiesSearchType;
use App\Repository\GoodiesRepository;
use App\Repository\OrderHistoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
    public function shop(PaginatorInterface $paginator, Request $request, OrderHistoryRepository $repo)
    {
        //Best-sellers
       $bestSeller = $repo->findAll();
        
        //Search
        $search = new GoodiesSearch(); //creating search and handling form
        $form = $this->createForm(GoodiesSearchType::class, $search);
        $form->handleRequest($request);


        //Paginator
        $goodies = $paginator->paginate(//paginate search results
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        
        return $this->render('shop/index_shop.html.twig',[
            'goodies' => $goodies,
            'form' => $form->createView(),
            'bestSeller' => $bestSeller
        ]);
    }


    
    /**
     * @Route("/checkout", name="shop_checkout")
     */
    public function checkout(Request $request, \Swift_Mailer $mailer)
    {
        /*generate cart from cookie*/
        $total = 0;//init cart total
        $cart = []; // init cart

        if($request->cookies->get('cart')){
            $rawCart = json_decode($request->cookies->get('cart'));//getting cookie value
            
        
            foreach ($rawCart as $id => $quantity) { //cart init from cookie
                $cart[] = [
                    'product' => $this->repository->find($id),
                    'quantity' => $quantity
                ];
            }

            
            foreach ($cart as $item) { //calcul cart total
                $totalItem = $item['product']->getPrice() * $item['quantity'];
                $total += $totalItem;
            }
        }

        $defaultData = ['message' => 'messagebuilder'];
        $form = $this->createFormBuilder($defaultData)
                    ->add('salle', IntegerType::class)
                    ->add('envoyer', SubmitType::class)
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            /**
             *  @var \App\Entity\User $user 
             */
            $user = $this->getUser();

            
            $message = (new \Swift_Message('Commande'))
            ->setFrom($user->getUsername())
            ->setTo('montemonttheophile@gmail.com')//the bde's mail
            ->setBody(
                $this->renderView(
                    // templates/emails/order.html.twig
                    'emails/order.html.twig',
                    ['cart' => $cart,
                    'total' => $total,
                    'user' => $user,
                    'room' => $data['salle']]
                ),'text/html');
    
            $mailer->send($message);


            //fillin order objet to prepare insert into database
            $order = new OrderHistory();
            $order  ->setAuthor($user)
                    ->setCart(json_decode($request->cookies->get('cart'), true))
                    ->setCreatedAt(new \DateTime());
            $this->manager->persist($order);
            $this->manager->flush();

            return $this->redirectToRoute('cart_clear'); //will clear cart then redirect to shop
        }

        return $this->render('shop/checkout.html.twig', [
            'cart' => $cart,
            'total' => $total,
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
