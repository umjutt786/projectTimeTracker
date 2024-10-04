<?php

namespace App\Http\Controllers;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

use App\Models\Screenshot;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="WorkLog",
 *     required={"id", "user_id", "project_id", "start_time", "hours_logged"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="project_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2023-01-01T10:00:00Z"),
 *     @OA\Property(property="hours_logged", type="number", format="float", example=2.5),
 *     @OA\Property(property="keyboard_activity", type="integer", format="int32", example=120),
 *     @OA\Property(property="mouse_activity", type="integer", format="int32", example=150),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 * )
 *
 * @OA\Schema(
 *     schema="Screenshot",
 *     required={"id", "work_log_id", "screenshot_path", "captured_at"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="work_log_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="screenshot_path", type="string", example="path/to/screenshot.jpg"),
 *     @OA\Property(property="captured_at", type="string", format="date-time", example="2023-01-01T10:00:00Z"),
 * )
 */
class WorkLogController extends Controller
{
    /**
     * @OA\Post(
     *     path="/worklog/start",
     *     summary="Start a new work log",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"project_id", "start_time"},
     *             @OA\Property(property="project_id", type="integer", example=1),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2023-01-01T10:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Work log started",
     *         @OA\JsonContent(ref="#/components/schemas/WorkLog")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function start(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_time' => 'required|date',
        ]);
        $workLog = WorkLog::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'start_time' => $request->start_time,
            'hours_logged' => 0,
            'keyboard_activity' => 0,
            'mouse_activity' => 0
        ]);
        return response()->json($workLog, 201);
    }

    /**
     * @OA\Post(
     *     path="/worklog/{id}/pause",
     *     summary="Pause an existing work log",
     * *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pause_time", "keyboard_activity", "mouse_activity"},
     *             @OA\Property(property="pause_time", type="string", format="date-time", example="2023-01-01T11:00:00Z"),
     *             @OA\Property(property="keyboard_activity", type="integer", example=50),
     *             @OA\Property(property="mouse_activity", type="integer", example=60)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Work log paused",
     *         @OA\JsonContent(ref="#/components/schemas/WorkLog")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Work log not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Work log not found.")
     *         )
     *     )
     * )
     */
    public function pause(Request $request, $id)
    {
        $request->validate([
            'pause_time' => 'required|date|after:start_time',
            'keyboard_activity' => 'required|integer',
            'mouse_activity' => 'required|integer',
        ]);

        $workLog = WorkLog::findOrFail($id);
        $pauseTime = strtotime($request->pause_time);
        $startTime = strtotime($workLog->start_time);
        $hoursLogged = ($pauseTime - $startTime) / 3600;

        $workLog->update([
            'hours_logged' => $hoursLogged,
            'keyboard_activity' => $workLog->keyboard_activity + $request->keyboard_activity,
            'mouse_activity' => $workLog->mouse_activity + $request->mouse_activity,
            'end_time' => $request->pause_time,
        ]);

        return response()->json($workLog, 200);
    }

    /**
     * @OA\Post(
     *     path="/worklog/{id}/resume",
     *     summary="Resume a paused work log",
     * *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"resume_time"},
     *             @OA\Property(property="resume_time", type="string", format="date-time", example="2023-01-01T11:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Work log resumed",
     *         @OA\JsonContent(ref="#/components/schemas/WorkLog")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Work log not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Work log not found.")
     *         )
     *     )
     * )
     */
    public function resume(Request $request, $id)
    {
        $request->validate([
            'resume_time' => 'required|date|after:end_time',
        ]);

        $workLog = WorkLog::findOrFail($id);
        $workLog->update([
            'start_time' => $request->resume_time,
        ]);

        return response()->json($workLog, 200);
    }

    /**
     * @OA\Post(
     *     path="/worklog/{id}/stop",
     *     summary="Stop a work log",
     * *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"end_time", "keyboard_activity", "mouse_activity"},
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *             @OA\Property(property="keyboard_activity", type="integer", example=70),
     *             @OA\Property(property="mouse_activity", type="integer", example=80)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Time tracking stopped",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Time tracking stopped"),
     *             @OA\Property(property="log", ref="#/components/schemas/WorkLog")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Work log not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Work log not found.")
     *         )
     *     )
     * )
     */
    public function stop(Request $request, $id)
    {
        $request->validate([
            'end_time' => 'required|date|after:start_time',
            'keyboard_activity' => 'required|integer',
            'mouse_activity' => 'required|integer',
        ]);

        $workLog = WorkLog::findOrFail($id);
        $endTime = strtotime($request->end_time);
        $startTime = strtotime($workLog->start_time);
        $hoursLogged = $workLog->hours_logged + (($endTime - $startTime) / 3600);

        $workLog->update([
            'hours_logged' => $hoursLogged,
            'keyboard_activity' => $workLog->keyboard_activity + $request->keyboard_activity,
            'mouse_activity' => $workLog->mouse_activity + $request->mouse_activity,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['message' => 'Time tracking stopped', 'log' => $workLog], 200);
    }

    /**
     * @OA\Post(
     *     path="/worklog/screenshot",
     *     summary="Upload a screenshot for a work log",
     * *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"worklog_id", "screenshot"},
     *             @OA\Property(property="worklog_id", type="integer", example=1),
     *             @OA\Property(property="screenshot", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Screenshot uploaded successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Screenshot")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function uploadScreenshot(Request $request)
    {
        $request->validate([
            'worklog_id' => 'required|exists:work_logs,id',
            'screenshot' => 'required|image|max:2048', // Validate that it is an image and size limit of 2MB
        ]);

        $workLog = WorkLog::findOrFail($request->worklog_id);

        // Store the uploaded screenshot
        $path = $request->file('screenshot')->store('screenshots', 'public');

        // Save screenshot record in the database
        $screenshot = Screenshot::create([
            'work_log_id' => $workLog->id,
            'screenshot_path' => $path,
            'captured_at' => now(),
        ]);

        return response()->json(['message' => 'Screenshot uploaded successfully', 'screenshot' => $screenshot], 201);
    }
}
