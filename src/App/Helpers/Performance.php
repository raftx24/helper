<?php

namespace Raftx24\Helper\App\Helpers;

class Performance
{
    private $last;

    /**
     * Performance constructor.
     */
    public function __construct()
    {
        $this->last = $this->getCurrentMilisecond();
    }

    public function showTime($place, $return = false)
    {
        $result = $place . "\t\t\t" . number_format(($this->getCurrentMilisecond() - $this->last)) . PHP_EOL;
        $this->last = $this->getCurrentMilisecond();
        if ($return) return $result;
        echo $result;
    }

    private function getCurrentMilisecond()
    {
        return round(microtime(true) * 1000);
        return time();

    }

}
