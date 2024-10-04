<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="Project",
 *     required={"id", "title", "description", "freelancer_id", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="Project Title"),
 *     @OA\Property(property="description", type="string", example="Project description goes here"),
 *     @OA\Property(property="freelancer_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="status", type="string", example="open"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/projects",
     * *     security={{"bearerAuth": {}}},
     *     summary="Get all open projects for the authenticated freelancer",
     *     description="Returns a list of open projects for the authenticated freelancer",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Project")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $projects = Project::where('freelancer_id', $user->id)
                            ->where('status', 'open')->get();

        return response()->json($projects, 200);
    }
}
