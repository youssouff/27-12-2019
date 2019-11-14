<?php
// src/Service/Api.php
namespace App\Service;

use App\Security\ApiUser;
use Symfony\Component\HttpClient\HttpClient;
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
        $response = $client->request('GET', 'http://localhost:8080/users/getName/:' . $id);
        $content = $response->getArray();
        //A verifier
        if ($content[0]) {
            return $content[user][name] . " " . $content[user][firstName];
        }
        return null;

        //query the api for the user fullname (json)
        //convert it to string
        // return user fullname 

        //return null if not found
    }

    public function getUserByMail($mail): ApiUser
    {
        // $encoders = [new JsonEncoder()];
        // $normalizers = [new ObjectNormalizer()];
        // $serializer = new Serializer($normalizers, $encoders);

        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/users/getFull/' . $mail, [
            'headers' => ['Content-type' => 'applicaton/json']
        ]);

        $content = $response->toArray();
        
        if ($content[0]) {
            $user = new ApiUser();
            $content[0]['roles'] = json_decode($content[0]['roles'],true);
            $user->initialize($content[0]['id'], $content[0]['email'], $content[0]['name'], $content[0]['firstName'], $content[0]['telephone'], $content[0]['campus'], $content[0]['promotion'], $content[0]['age'], $content[0]['roles'], $content[0]['password']);
            return $user;
        } else return null;

        //query api for user full row from mail (json)
        //turn cesirole into symfony role
        //decode json into ApiUser object 
        //return ApiUser object

        //return null if not found
    }

    public function register(ApiUser $user)
    {
        $client = HttpClient::create();

        $client->request('POST', 'http://localhost:8080/users/newUser/', [
            'headers' => ['Content-type' => 'application/json'],
            'body' => $this->serializer->serialize($user, 'json')
        ]);
    }
}
