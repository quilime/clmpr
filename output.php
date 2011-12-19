<?php

/**
* @param    int     $seconds    Number of seconds to convert into a human-readable timestamp
* @return   tring   Human-readable approximate timestamp like "2 hours"
*/
function approximate_time($seconds)
{
    switch(true)
    {
        case abs($seconds) <= 90:
            return 'moments';

        case abs($seconds) <= 90 * 60:
            return round(abs($seconds) / 60).' minutes';

        case abs($seconds) <= 36 * 60 * 60:
            return round(abs($seconds) / (60 * 60)).' hours';

        default:
            return round(abs($seconds) / (24 * 60 * 60)).' days';
    }
}
