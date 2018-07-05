<?php

namespace App\Controller;


use App\Entity\Chanel;
use App\Service\MessageService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends FOSRestController
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * MessageController constructor.
     * @param MessageService $messageService
     */
    public function __construct( MessageService $messageService)
    {
        $this->messageService = $messageService;
    }


    /**
     * @Rest\Post("/channels/{chanel}/send-message")
     * @ParamConverter("chanel", class="App\Entity\Chanel")
     * @param Request $request
     * @param Chanel $chanel
     * @return View
     */
    public function post( Request $request, Chanel $chanel )
    {
        return $this->messageService->createMessage( $request, $chanel, $this->getUser() );
    }

    /**
     * @Rest\Get("/channels/{chanel}/messages")
     * @ParamConverter("chanel", class="App\Entity\Chanel")
     * @Rest\View( serializerGroups={"list_messages"})
     * @param Chanel $chanel
     * @return View
     */
    public function cget( Chanel $chanel )
    {
        return $this->messageService->getMessages( $chanel, $this->getUser() );
    }
}
