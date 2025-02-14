<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CompletedByGender extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $data = DB::table('enrollments')->leftJoin('users', 'enrollments.user_id', '=', 'users.id')->select('users.gender as user_gender', DB::raw('count(*) as total'))
            ->groupBy('users.gender')->get();

        return $this->result([
            'Male' => optional($data->where('user_gender','male')->first())->total,
            'Female' => optional($data->where('user_gender','female')->first())->total
        ]);
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
        return 'completed-by-gender';
    }
}
