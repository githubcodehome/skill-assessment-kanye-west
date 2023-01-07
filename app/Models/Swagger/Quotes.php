<?php

namespace App\Models\Swagger;

use OpenApi\Annotations as OA;

/**    @OA\Schema(
 *     schema="Quotes",
 *     type="object",
 *      @OA\Property(
 *              type="array",
 *              property="data",
 *              example={
 *                      "I don't expect to be understood at all.",
 *                      "I really love my Tesla. I'm in the future. Thank you Elon.",
 *                      "Life is the ultimate gift"},
 *                  @OA\Items(
 *                     type="string"
 *                  )
 *         ),
 *       ),
 *      @OA\Property(
 *        property="count",
 *        type="integer",
 *        example="3"
 *      ),
 *      @OA\Property(
 *        property="status",
 *        type="string",
 *        example="OK"
 *     ),
 *    )
 */
class Quotes
{

}
