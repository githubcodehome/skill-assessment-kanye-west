<?php

namespace App\Models\Swagger;

use OpenApi\Annotations as OA;

/**    @OA\Schema(
 *     schema="Favorites",
 *     type="object",
 *      @OA\Property(
 *              type="array",
 *              property="data",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(
 *                          property="id",
 *                          type="integer",
 *                          example="3"
 *                      ),
 *                       @OA\Property(
 *                          property="text",
 *                          type="string",
 *                          example="The world is our office"
 *                      ),
 *                      @OA\Property(
 *                          property="user_id",
 *                          type="integer",
 *                          example="7"
 *                      ),
 *                  )
 *         ),
 *       ),
 *      @OA\Property(
 *        property="status",
 *        type="string",
 *        example="OK"
 *     ),
 *    )
 */
class Favorites
{

}
