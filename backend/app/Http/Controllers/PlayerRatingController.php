<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rating\StoreRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Services\PlayerRatingService;
use Illuminate\Http\Request;

class PlayerRatingController extends Controller
{
    public function __construct(
        private PlayerRatingService $ratingService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['player_id']);
        $ratings = $this->ratingService->getAllRatings($filters);

        return response()->json($ratings);
    }

    public function store(StoreRatingRequest $request)
    {
        $rating = $this->ratingService->createRating($request->validated(), $request->user()->id);

        return response()->json($rating, 201);
    }

    public function update(UpdateRatingRequest $request, $id)
    {
        $rating = $this->ratingService->updateRating($id, $request->validated());

        if (!$rating) {
            return response()->json(['message' => 'Rating not found'], 404);
        }

        return response()->json($rating);
    }

    public function destroy($id)
    {
        $deleted = $this->ratingService->deleteRating($id);

        if (!$deleted) {
            return response()->json(['message' => 'Rating not found'], 404);
        }

        return response()->json(['message' => 'Rating deleted successfully']);
    }
}

