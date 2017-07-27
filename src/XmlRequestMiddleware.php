<?php

namespace XmlMiddleware;

use Closure;

class XmlRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $xml = $request->xml();
        if($xml) {
            $request->merge($request->xml());
        }
        
        return $next($request);
    }
}
