<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = User::whereApiKey($request->api_key)->first();
            $device = Device::whereBody($request->sender)->first();

            if ($device->user_id != $user->id) {
                return response()->json(
                    [
                        'status' => false,
                        'msg' => 'Invalid data!',
                        'errors' =>
                            'Invali api_key or sender,please check again',
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $next($request);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'msg' => 'Invalid data!',
                    'errors' => 'Invali api_key or sender,please check again',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
