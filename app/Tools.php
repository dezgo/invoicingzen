<?php

namespace App;

use Carbon\Carbon;

class Tools
{
    public static function getAccruals($start_date, $amount, $every_n_days, $periods)
    {
        $data = [];
        if ($periods === "") $periods = 26;
        for ($n=0; $n<$periods; $n++) {
            $end_date = $start_date->copy()->addDays($every_n_days-1);
            $data[] = ['start_date' => $start_date, 'end_date' => $end_date, 'amount' => $amount];
            if ($end_date->month !== $start_date->month) {
                $end_of_month = $start_date->copy()->endOfMonth();
                $start_of_month = $end_date->copy()->startOfMonth();
                $days_to_end_of_month = $start_date->diffInDays($start_of_month);
                if ($days_to_end_of_month > 0 and $days_to_end_of_month < $every_n_days) {
                    $accrual = round(($every_n_days - $days_to_end_of_month) / $every_n_days * $amount,2);
                    $data[] = ['start_date' => $end_of_month, 'end_date' => $end_of_month, 'amount' => -$accrual];
                    $data[] = ['start_date' => $start_of_month, 'end_date' => $start_of_month, 'amount' => $accrual];
                }
            }
            $start_date = $end_date->copy()->addDays(1);
        }
        return $data;
    }

    // allocate the given amount over the given period
    public static function allocatePrepayment($start_date, $end_date, $amount)
    {
        $data = [];
        $start_of_month = $start_date->copy();
        $total_days = $start_date->diffInDays($end_date)+1;
        $running_total = 0;
        do {
            $end_of_month = $start_of_month->copy()->endOfMonth();
            if ($end_date < $end_of_month) {
                $end_of_month = $end_date;
                $days = $start_of_month->diffInDays($end_of_month)+1;
                $allocated = round($amount - $running_total,2);
            }
            else {
                $days = $start_of_month->diffInDays($end_of_month)+1;
                $allocated = round($amount * $days / $total_days,2);
            }
            $data[] = ['start_of_month' => $start_of_month,
                       'end_of_month' => $end_of_month,
                       'days' => $days,
                       'allocated' => $allocated];
            $start_of_month = $end_of_month->copy()->addDay()->startOfDay();
            $running_total += $allocated;
        } while ($end_of_month !== $end_date);

        return $data;

    }
}
