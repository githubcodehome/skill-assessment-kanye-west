<?php
namespace App\Models\Swagger;

use OpenApi\Annotations as OA;

/**    @OA\Schema(
 *     schema="Token",
 *     type="object",
 *      @OA\Property(
 *              type="object",
 *              property="data",
 *              @OA\Property(
 *              type="object",
 *              property="accessToken",
 *               @OA\Property(
 *                  property="name",
 *                  type="srting",
 *                  example="John"
 *              ),
 *              @OA\Property(
 *                  property="abilities",
 *                  type="array",
 *                  example={"*"},
 *                  @OA\Items(
 *                     type="string"
 *                  )
 *              ),
 *              @OA\Property(
 *                  property="tokenable_id",
 *                  type="integer",
 *                  example="7"
 *              ),
 *              @OA\Property(
 *                  property="tokenable_type",
 *                  type="string",
 *                  example="App\Models\User"
 *              ),
 *              @OA\Property(
 *                  property="updated_at",
 *                  type="timestamp",
 *                  example="2023-01-07T16:17:33.000000Z"
 *              ),
 *              @OA\Property(
 *                  property="created_at",
 *                  type="timestamp",
 *                  example="2023-01-07T16:17:33.000000Z"
 *              ),
 *              @OA\Property(
 *                  property="id",
 *                  type="integer",
 *                  example="25"
 *              ),
 *         ),
 *              @OA\Property(
 *                  property="plainTextToken",
 *                  type="string",
 *                  example="27|T9Jkmxn5mKKFRsdFfupbZZSJFv8QRlyKT04obNQC"
 *              ),
 *       ),
 *      @OA\Property(
 *        property="token",
 *        type="string",
 *        example="27|T9Jkmxn5mKKFRsdFfupbZZSJFv8QRlyKT04obNQC"
 *      ),
 *      @OA\Property(
 *        property="status",
 *        type="string",
 *        example="OK"
 *     ),
 *    ),
 */
class Token
{

}
