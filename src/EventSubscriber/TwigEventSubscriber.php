<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\CarRepository;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $carRepository;

    public function __construct(Environment $twig, CarRepository $carRepository) {
        $this->twig = $twig;
        $this->carRepository = $carRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $this->twig->addGlobal('cars',$this->carRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}