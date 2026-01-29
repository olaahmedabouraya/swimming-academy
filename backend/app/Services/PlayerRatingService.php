<?php

namespace App\Services;

use App\Repositories\PlayerRatingRepository;
use Illuminate\Database\Eloquent\Collection;

class PlayerRatingService
{
    public function __construct(
        private PlayerRatingRepository $ratingRepository
    ) {}

    public function getAllRatings(array $filters = []): Collection
    {
        return $this->ratingRepository->getAll($filters);
    }

    public function getRatingById(int $id): ?array
    {
        $rating = $this->ratingRepository->findById($id);
        return $rating ? $rating->toArray() : null;
    }

    public function createRating(array $data, int $ratedBy): array
    {
        $data['rated_by'] = $ratedBy;
        $data['overall_score'] = $this->calculateOverallScore($data);
        
        $rating = $this->ratingRepository->create($data);
        $rating->load(['player.user', 'ratedBy']);
        
        return $rating->toArray();
    }

    public function updateRating(int $id, array $data): ?array
    {
        $rating = $this->ratingRepository->findById($id);
        
        if (!$rating) {
            return null;
        }

        if (isset($data['technique_score']) || isset($data['endurance_score']) || 
            isset($data['speed_score']) || isset($data['attitude_score'])) {
            $data['overall_score'] = $this->calculateOverallScore($data, $rating);
        }

        $this->ratingRepository->update($rating, $data);
        $rating->refresh();
        $rating->load(['player.user', 'ratedBy']);
        
        return $rating->toArray();
    }

    public function deleteRating(int $id): bool
    {
        $rating = $this->ratingRepository->findById($id);
        
        if (!$rating) {
            return false;
        }

        return $this->ratingRepository->delete($rating);
    }

    private function calculateOverallScore(array $data, $existingRating = null): float
    {
        $technique = $data['technique_score'] ?? $existingRating->technique_score ?? 0;
        $endurance = $data['endurance_score'] ?? $existingRating->endurance_score ?? 0;
        $speed = $data['speed_score'] ?? $existingRating->speed_score ?? 0;
        $attitude = $data['attitude_score'] ?? $existingRating->attitude_score ?? 0;

        return ($technique + $endurance + $speed + $attitude) / 4;
    }
}


