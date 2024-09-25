<?php

namespace App\EventListener;

use App\Entity\Relic;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Relic::class)]
final class RelicListener
{
    public function __construct(private readonly Security $security) {}

    public function prePersist(Relic $relic, PrePersistEventArgs $event): void
    {
        $user = $this->security->getUser();
        $relic->setCreator($user);
    }
}
