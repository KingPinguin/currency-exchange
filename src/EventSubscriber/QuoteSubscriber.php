<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Quote;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class QuoteSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(GetResponseForControllerResultEvent $event)
    {
        $quote = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $content = json_decode($event->getRequest()->getContent(), true);
        if (!$quote instanceof Quote || Request::METHOD_POST !== $method) {
            return;
        }
        if($content['code'] == 'USDGBP') {
            $message = (new \Swift_Message('A new quote has been added'))
                ->setFrom('system@example.com')
                ->setTo('contact@les-tilleuls.coop')
                ->setBody(sprintf('New quote has been added.'));

            $this->mailer->send($message);
        }
    }
}