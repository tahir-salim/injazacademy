<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Illuminate\Support\Str;

class DateFrom extends DateFilter
{
    protected $dbField;

    public function __construct($name, $dbField)
    {
        $this->name = $name;
        $this->dbField = $dbField;
    }
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $value = Carbon::parse($value);

        return $query->where($this->dbField, '>=', Carbon::parse($value));
    }

    public function key()
    {
        return 'custom_' . Str::replace(' ', '_', $this->name) . $this->dbField;
    }
}
