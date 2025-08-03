<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Track visitor data
        try {
            $userAgent = $request->header('User-Agent');
            $ipAddress = $request->ip();
            $pageVisited = $request->path();
            $referrer = $request->header('Referer');

            // Parse user agent to get browser and OS info
            $browser = $this->getBrowser($userAgent);
            $os = $this->getOS($userAgent);
            $deviceType = $this->getDeviceType($userAgent);

            $visitor = Visitor::create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'page_visited' => $pageVisited,
                'referrer' => $referrer,
                'visit_date' => now()->toDateString(),
                'visit_time' => now()->toTimeString(),
                'country' => null, // Could be enhanced with GeoIP
                'city' => null, // Could be enhanced with GeoIP
                'device_type' => $deviceType,
                'browser' => $browser,
                'os' => $os,
            ]);

            // Send real-time update to Socket.IO server
            $this->sendRealTimeUpdate($visitor);
        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Visitor tracking error: ' . $e->getMessage());
        }

        return $next($request);
    }

    private function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Unknown';
    }

    private function getOS($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    private function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) return 'Mobile';
        if (strpos($userAgent, 'Tablet') !== false) return 'Tablet';
        return 'Desktop';
    }

    private function sendRealTimeUpdate($visitor)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://localhost:3001/api/analytics/update', [
                'json' => [
                    'visitor' => [
                        'ip_address' => $visitor->ip_address,
                        'page_visited' => $visitor->page_visited,
                        'device_type' => $visitor->device_type,
                        'browser' => $visitor->browser,
                        'os' => $visitor->os,
                        'visit_date' => $visitor->visit_date,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            // Silently fail for real-time updates
            Log::error('Real-time update failed: ' . $e->getMessage());
        }
    }
}
