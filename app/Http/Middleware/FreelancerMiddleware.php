<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class FreelancerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Attempt to authenticate the user with email and password (passed in request)
        // This is not common in middleware, but if needed, you can attempt this:
        $credentials = $request->only('email', 'password');
            // dd($credentials);
        // Check if the user is authenticated
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->isFreelancer()) {
                return $next($request);
            }
            
            return response()->json(['error' => 'Unauthorized. Freelancers only.'], 403);  // Non-freelancers are forbidden
        }

        // If email and password don't match or no credentials are provided
        return response()->json(['error' => 'Invalid credentials or unauthorized access.'], 401);
    }
}
