<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function index(){
        dump(1);
        return new Response('<h1>EEEEEE!!!!</h1>');
    }

}