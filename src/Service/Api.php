<?php 
// src/Service/Api.php
namespace App\Service;

use App\Security\ApiUser;
use Doctrine\Common\Collections\ArrayCollection;

class Api
{
    public function getUserFullName(int $id): string
    {
        //query the api for the user fullname (json)
        //convert it to string
        // return user fullname 

        //return null if not found
    }

    public function getUserByMail($mail): ApiUser
    {
    //query api for user full row from mail (json)
    //turn cesirole into symfony role
    //decode json into ApiUser object 
    //return ApiUser object

    //return null if not found
    }

    public function register(ApiUser $user){
        //apiuser => json
        //crer nouvel entre d'après le json
    }
}