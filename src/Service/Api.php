<?php
// src/Service/Api.php
namespace App\Service;

use App\Security\ApiUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Api
{
    private $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }


    public function getUserFullName(int $id): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/user/getName/:' . $id);
        $content = $response->getArray();
        //A verifier
        if ($content[user]) {
            return $content[user][nom] . " " . $content[user][prenom];
        }
        return null;

        //query the api for the user fullname (json)
        //convert it to string
        // return user fullname 

        //return null if not found
    }

    public function getUserByMail($mail): ApiUser
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/user/getName/:' . $mail);
        $content = $response->getContent();
        print_r($content);
        die();

        //A verifier
        if ($content[user]) {
            return $this->serializer->deserialize($content, ApiUser::class, 'json');
        }
        return null;

        //query api for user full row from mail (json)
        //turn cesirole into symfony role
        //decode json into ApiUser object 
        //return ApiUser object

        //return null if not found
    }

    public function register(ApiUser $user)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/user/newUser/:', [
            'body' => $this->serializer->serialize($user, 'json')
        ]);

        $content = $response->getContent();
    }
}
