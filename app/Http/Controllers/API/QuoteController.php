<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFavoriteRequest;
use App\Http\Requests\QuoteRequest;
use App\Models\Favorite;
use App\Models\User;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{

    public function __construct(private readonly QuoteService $quoteService)
    {
    }

    public function token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' =>
                ['email' =>
                    ['The provided credentials are incorrect.']
                ]
            ], 422);
        }
        $tokenData = $user->createToken($request->device_name);
        return response()->json([
            'data' => $tokenData,
            'token' => $tokenData->plainTextToken,
            'status' => 'OK']);
    }

    public function get(QuoteRequest $request): JsonResponse
    {
        $validated = $request->all();
        $quotes = $this->quoteService->get(...$validated);

        return response()->json(['data' => $quotes, 'count' => count($quotes), 'status' => 'OK']);
    }

    public function addFavorite(AddFavoriteRequest $request): JsonResponse
    {
        $this->quoteService->addFavorite($request->text);

        return response()->json(['status' => 'OK']);
    }

    public function getFavorites(): JsonResponse
    {
        $favorites = $this->quoteService->getFavorites();

        return response()->json(['data' => $favorites, 'status' => 'OK']);
    }

    public function deleteFavorite(Favorite $favorite): JsonResponse
    {
        $this->quoteService->deleteFavorite($favorite);

        return response()->json(['status' => 'OK']);
    }
}
