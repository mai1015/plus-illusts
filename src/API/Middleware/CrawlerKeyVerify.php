<?php

namespace Mai1015\PlusIllusts\API\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class CrawlerKeyVerify
{
    /**
     * ThinkSNS+ config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create the middleware.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->query('key') != $this->config->get('plus-illusts.crawler_key')) {
            return response()->json([
                'code' => 0,
                'messages' => 'verify failed'
            ]);
        }
        return $next($request);
    }
}
