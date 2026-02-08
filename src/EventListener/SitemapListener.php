<?php

namespace App\EventListener;

use App\Entity\Relic;
use App\Entity\Saint;
use App\Enum\RelicStatus;
use App\Repository\RelicRepository;
use App\Repository\SaintRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RelicRepository $relicRepository,
        private readonly SaintRepository $saintRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerRelicsUrls($event->getUrlContainer());
        $this->registerSaintsUrls($event->getUrlContainer());
    }

    private function registerRelicsUrls(UrlContainerInterface $urls): void
    {
        $relics = $this->relicRepository->findBy(['status' => RelicStatus::APPROVED]);

        foreach ($relics as $relic) {
            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'app_relic_show',
                        ['id' => $relic->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'relics'
            );
        }
    }

    private function registerSaintsUrls(UrlContainerInterface $urls): void
    {
        $saints = $this->saintRepository->findBy(['is_incomplete' => false]);

        foreach ($saints as $saint) {
            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'app_saint_show',
                        ['id' => $saint->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'saints'
            );
        }
    }
}
