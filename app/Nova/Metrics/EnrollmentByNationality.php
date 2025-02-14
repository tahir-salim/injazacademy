<?php

namespace App\Nova\Metrics;

use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class EnrollmentByNationality extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $data = DB::table('enrollments')->leftJoin('users', 'enrollments.user_id', '=', 'users.id')->leftJoin('countries', 'users.country_id', '=', 'countries.id')->select('countries.name as country_name', DB::raw('count(*) as total'))
            ->groupBy('users.country_id')
            ->get()
            ->map(function($item){
                if(!$item->country_name){
                    $item->country_name = 'Unknown';
                }
                return $item;
            })
            ->toArray();

        return $this->result(array_combine(array_column($data, 'country_name'), array_column($data, 'total')));
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'enrollment-by-nationality';
    }
}
