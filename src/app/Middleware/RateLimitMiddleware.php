<?php

namespace App\Middleware;

class RateLimitMiddleware
{
    private $maxRequests = 5;
    private $timeWindow = 60; // 1 minute

    public function handle()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = "rate_limit_" . str_replace('.', '_', $ip);

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'requests' => 1,
                'first_request' => time()
            ];
            return;
        }

        $data = &$_SESSION[$key];
        $currentTime = time();

        if ($currentTime - $data['first_request'] > $this->timeWindow) {
            $data['requests'] = 1;
            $data['first_request'] = $currentTime;
        } else {
            $data['requests']++;
        }

        if ($data['requests'] > $this->maxRequests) {
            http_response_code(429);
            die("Trop de tentatives. Veuillez patienter une minute.");
        }
    }
}
