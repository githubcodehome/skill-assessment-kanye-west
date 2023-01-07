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
use OpenApi\Annotations as OA;

/**
 * @OAS\SecurityScheme(
 *          securityScheme="sanctum",
 *          type="http",
 *          scheme="bearer"
 *      ),
 */
class QuoteController extends Controller
{

    public function __construct(private readonly QuoteService $quoteService)
    {
    }

    /**
     * Create token for API.
     *
     * @OA\Post(
     *     path="/api/sanctum/token",
     *     tags={"Token"},
     *     operationId="token",
     *     @OA\Response(
     *         response=422,
     *         description="Failed validation"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *           @OA\JsonContent(
     *           type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="device_name",
     *                     description="device name",
     *                     type="string"
     *                 )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="token response",
     *         @OA\JsonContent(ref="#/components/schemas/Token")
     *     )
     * )
     */
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

    /**
     * Get quotes.
     *
     * @OA\Get(
     *     path="/api/get",
     *     tags={"Quotes"},
     *     operationId="get",
     *     @OA\Parameter(
     *         name="count",
     *         in="query",
     *         description="Count of quotes",
     *         required=false,
     *         example="5"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Failed validation"
     *     ),
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Quotes response",
     *         @OA\JsonContent(ref="#/components/schemas/Quotes")
     *     )
     * )
     */
    public function get(QuoteRequest $request): JsonResponse
    {
        $validated = $request->all();
        $quotes = $this->quoteService->get(...$validated);

        return response()->json(['data' => $quotes, 'count' => count($quotes), 'status' => 'OK']);
    }

    /**
     * Add favorite.
     *
     * @OA\Post(
     *     path="/api/add-favorite",
     *     tags={"Quotes"},
     *     operationId="addFavorite",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *           @OA\JsonContent(
     *           type="object",
     *                 @OA\Property(
     *                     property="text",
     *                     description="text of quote",
     *                     type="string",
     *                 ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Failed validation"
     *     ),
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Status response",
     *         @OA\JsonContent(ref="#/components/schemas/Status")
     *     )
     * )
     */
    public function addFavorite(AddFavoriteRequest $request): JsonResponse
    {
        $this->quoteService->addFavorite($request->text);

        return response()->json(['status' => 'OK']);
    }

    /**
     * Get favorite quotes.
     *
     * @OA\Get(
     *     path="/api/favorites",
     *     tags={"Quotes"},
     *     operationId="getFavorites",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Favorite quotes response",
     *         @OA\JsonContent(ref="#/components/schemas/Favorites")
     *     )
     * )
     */
    public function getFavorites(): JsonResponse
    {
        $favorites = $this->quoteService->getFavorites();

        return response()->json(['data' => $favorites, 'status' => 'OK']);
    }


    /**
     * Delete favorite quote.
     *
     * @OA\Delete(
     *     path="/api/favorites/{favorite}",
     *     tags={"Quotes"},
     *     operationId="deleteFavorite",
     *     @OA\Parameter(
     *         name="favorite",
     *         in="path",
     *         description="Favorite ID",
     *         required=true,
     *         example="5"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Status response",
     *         @OA\JsonContent(ref="#/components/schemas/Status")
     *     )
     * )
     */
    public function deleteFavorite(Favorite $favorite): JsonResponse
    {
        $this->quoteService->deleteFavorite($favorite);

        return response()->json(['status' => 'OK']);
    }
}
