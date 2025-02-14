<?php

namespace App\Nova\Metrics;

use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class EnrollmentPerCourse extends Partition
{
    protected $isWorkshop;

    public function __construct($name, $isWorkshop = false)
    {
        $this->name = $name;
        $this->isWorkshop = $isWorkshop;
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $data = DB::table('enrollments')
            ->leftJoin('programs', 'enrollments.program_id', '=', 'programs.id')
            ->where('programs.is_workshop','=',$this->isWorkshop)
            ->select('programs.title as programTitle', DB::raw('count(*) as total'))
            ->groupBy('programs.id')->get()->toArray();

        return $this->result(array_combine(array_column($data, 'programTitle'), array_column($data, 'total')));
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return ($this->isWorkshop ? 'workshops-' : 'programs-') . 'student-per-course';
    }
}
