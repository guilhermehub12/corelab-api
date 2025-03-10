<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="CoreLab API Documentação",
 *      description="Documentação para a CoreLab REST API",
 *      @OA\Contact(
 *          email="guilhermedelmiro11@gmail.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 * 
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="CoreLab API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer"
 * )
 * 
 * @OA\Schema(
 *      schema="Task",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="title", type="string", example="Complete project documentation"),
 *      @OA\Property(property="description", type="string", example="Write comprehensive documentation for the API project"),
 *      @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="pending"),
 *      @OA\Property(property="status_label", type="string", example="Pending"),
 *      @OA\Property(property="due_date", type="string", format="date", example="2023-12-31"),
 *      @OA\Property(property="is_overdue", type="boolean", example=false),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-02 13:00:00")
 * )
 * 
 * @OA\Schema(
 *      schema="Error",
 *      @OA\Property(property="message", type="string", example="Error message"),
 *      @OA\Property(
 *          property="errors",
 *          type="object",
 *          @OA\Property(
 *              property="field",
 *              type="array",
 *              @OA\Items(type="string", example="The field is required")
 *          )
 *      )
 * )
 */
class SwaggerController extends Controller
{
    //
}
