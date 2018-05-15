<?php
/**
 * Created by PhpStorm.
 * User: mai1015
 * Date: 2018-04-11
 * Time: 21:28
 */

namespace Mai1015\PlusIllusts\API\Middleware;

use Closure;

class FileUploadVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        // check ext
        if (isset($input['ext'])) {
            $input['ext']  = strpos($input['ext'],'.') === 0 ? $input['ext'] : '.' . $input['ext'];
        }
        $request->replace($input);
        return $next($request);
    }
}