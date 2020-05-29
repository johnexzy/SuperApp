<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Src\Logic;

/**
 * Description of CheckDate
 *
 * @author hp
 */
class CheckDate
{   
    private  $timestamp = null;
    public function __construct($timestamp) {
        $this->timestamp = $timestamp;
    }
    public function getDay()
    {
        return intval(date('d', $this->timestamp));
    }
    public function getMonth()
    {
        return intval(date('m', $this->timestamp));
    }
    public function getYear()
    {
        return intval(date('Y', $this->timestamp));
    }
    public function getHour()
    {
        return intval(date('G', $this->timestamp));
    }
    public function getMin()
    {
        return intval(date('i', $this->timestamp));
    }
    public function getSecs()
    {
        return intval(date('s', $this->timestamp));
    }
    public function checkDay()
    {
        $nowd = intval(date('d')); //Day
        $nowm = intval(date('m')); //Month
        $nowy = intval(date('Y')); //Year
        $nowg = intval(date('G')); //Hour
        $nowi = intval(date('i')); //Minutes
        $nows = intval(date('s')); //Seconds

        if ($nowy == $this->getYear()) {
            if ($nowm > $this->getMonth()) {
                $monthsAgo = ($nowm - $this->getMonth() == 1) ? "A month Ago" : ($nowm - $this->getMonth()) . " months Ago";
                return $monthsAgo;
            } else {
                if ($nowd > $this->getDay()) {
                    $daysAgo = ($nowd - $this->getDay() == 1) ? "Yesterday" : ($nowd - $this->getDay()) . " Days Ago";
                    return $daysAgo;
                } else {
                    if ($nowg > $this->getHour()) {
                        $hourAgo = ($nowg - $this->getHour() == 1) ? "An hour Ago" : ($nowg - $this->getHour()) . " Hours Ago";
                        return $hourAgo;
                    }
                    else {
                        if ($nowi > $this->getMin()) {
                            $minAgo = ($nowi - $this->getMin() == 1) ? "Min Ago" : ($nowi - $this->getMin()) . " mins Ago";
                            return $minAgo;
                        }else {
                            return "<b>Just Now</b>";
                        }
                    }
                }
            }
        }
        else {
            if ($nowy - $this->getYear() > 1) {
                $yearsAgo = ($nowy - $this->getYear() == 1) ? "Last Year" : ($nowy - $this->getYear()) . " Years Ago";
                return $yearsAgo;
            }else{
                $diff = $nowm+12 -  $this->getMonth();
                $monthAgo = ($diff == 1) ? "Last month" : $diff. " Months Ago";
                return $monthAgo;
            }
        }

    }

}
