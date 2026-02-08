<?php

namespace App\Service;

use App\Enum\RelicStatus;
use App\Repository\RelicRepository;
use App\Repository\SaintRepository;

class StatisticsService
{
    public function __construct(
        private readonly RelicRepository $relicRepository,
        private readonly SaintRepository $saintRepository
    ) {
    }

    public function getLandingStatistics(): array
    {
        return [
            'total_relics' => $this->relicRepository->countByStatus(RelicStatus::APPROVED),
            'total_saints' => $this->countSaintsWithRelics(),
            'total_locations' => $this->countUniqueLocations(),
        ];
    }

    private function countSaintsWithRelics(): int
    {
        return (int) $this->relicRepository->createQueryBuilder('r')
            ->select('COUNT(DISTINCT s.id)')
            ->join('r.saint', 's')
            ->where('r.status = :status')
            ->setParameter('status', RelicStatus::APPROVED)
            ->andWhere('s.is_incomplete = :incomplete')
            ->setParameter('incomplete', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function countUniqueLocations(): int
    {
        return (int) $this->relicRepository->createQueryBuilder('r')
            ->select('COUNT(DISTINCT r.location)')
            ->where('r.status = :status')
            ->setParameter('status', RelicStatus::APPROVED)
            ->andWhere('r.location IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
