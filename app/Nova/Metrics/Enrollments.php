<?php

namespace App\Nova\Metrics;

use App\Models\Enrollment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class Enrollments extends Value
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
        if ($this->isWorkshop) {
            $model = Enrollment::workshops();
        } else {
            $model = Enrollment::programs();
        }

        return $this->count($request, $model);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Current Year To Date'),
        ];
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
        return ($this->isWorkshop ? 'workshops-' : 'programs-') . 'enrollments';
    }
}
