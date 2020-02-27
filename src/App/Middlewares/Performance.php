<?php

namespace Raftx24\Helper\App\Middlewares;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Performance
{
    private $start;
    private $queryCount;

    public function handle($request, Closure $next, $guard = null)
    {
        return env('MIDDLEWARE_PERFORMANCE', false)
            ? $this->performance($request, $next)
            : $next($request);
    }

    private function performance($request, Closure $next)
    {
        $this->init();

        $result = $next($request);

        $this->log($request);

        return $result;
    }


    private function memoryUsage(): string
    {
        $memory = (int) (memory_get_peak_usage(false) / 1024 / 1024);

        return $memory.' MiB';
    }

    private function log($request): void
    {
        Log::debug('performance: /'.$request->path().' at '.$this->time().' with '.$this->memoryUsage(). ' queries: '. $this->queryCount.PHP_EOL);
    }

    private function time()
    {
        $time = (int) ((microtime(true) - $this->start) * 1000);

        return $time.' ms ';
    }

    private function init(): void
    {
        $this->start = microtime(true);
        $this->listenQueries();
    }

    private function listenQueries()
    {
        DB::listen(function ($query) {
            ++$this->queryCount;
        });
    }
}
