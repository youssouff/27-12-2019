<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{


    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function add($id, Request $request)
    {
        
        $value =  $request->cookies->get('cart');
        
        if($value !== null){
            $cart = json_decode($value, true);
        }
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        
        $cookie = new Cookie('cart', json_encode($cart), time() + (365 * 24 * 60 * 60));
        
        //create response with updated cookie
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();

        return $this->redirectToRoute('shop');
    }


    /**
     * @Route("/remove/{id}", name="cart_remove")
     */
    public function remove($id, Request $request)
    {
        
        $value =  $request->cookies->get('cart');
        
        if($value !== null){
            $cart = json_decode($value, true);
        }
        if(!empty($cart[$id])){
            $cart[$id]--;
            if($cart[$id] == 0){
                unset($cart[$id]);
            }
        }
        
        $cookie = new Cookie('cart', json_encode($cart), time() + (365 * 24 * 60 * 60));
        
        //create response with updated cookie
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();

        return $this->redirectToRoute('shop');
    }


    /**
     * @Route("/view")
     */
    public function view(Request $request){
        var_dump($request->cookies->get('cart',[]));
       return new Response('= value for cookie named cart');
    }

    /**
     * @Route("/clear", name="cart_clear")
     */
    public function clear(Request $request){
        $res = new Response();
        $res->headers->clearCookie('cart');
        $res->send();

        return $this->redirectToRoute('shop');
    }
}
