<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class isVerifiedLicense
{

    private  $verified;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

   
    public function handle(Request $request, Closure $next)
    {

        //next if route is settings.install_app ,,get or post
       
        // $allowedRoute = ['setting.install_app','activateLicense','connectDB' ,'settings.install_app'];
        // if(!in_array($request->route()->getName(),$allowedRoute) && !env('APP_INSTALLED'))
        // {
        //   return redirect()->route('setting.install_app');
        // }
        // if ($request->route()->getName() == 'settings.install_app' || $request->route()->getName() == 'setting.install_app' || $request->route()->getName() == 'activateLicense' || $request->route()->getName() == 'connectDB') {
        //     return $next($request);
        // }
        // $key = env('LICENSE_KEY');
        // if(!Session::has('verifiedLicense')){
            
        //     $cek = Http::withOptions(['verify' => false])->get('https://license-management.m-pedia.my.id/api/license/check?licensekey='.$key.'&host='.$_SERVER['HTTP_HOST'])->object();
              
        //     if($cek->status === 200){
        //         Session::put('verifiedLicense',true);
        //         return $next($request);
        //       }else {
        //         return Redirect::intended('https://license-management.m-pedia.my.id/invalids');
        //       }
        // } else if(Session::get('verifiedLicense')) {
        //     return $next($request);
        // }
        return $next($request);

       
    }
}
