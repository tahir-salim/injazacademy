<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class CustomBooleanFilter extends BooleanFilter
{

    protected $dbField;
    protected $optionName1;
    protected $optionName2 = null;
    protected $optionName3 = null;
    protected $optionValue1 = true;
    protected $opionValue2 = false;
    protected $opionValue3 = null;
    public function __construct($name, $dbField, $optionName1, $optionName2 = null, $optionValue1 = true, $opionValue2 = false, $optionName3 = null, $opionValue3 = null)
    {
        $this->name = $name;
        $this->dbField = $dbField;
        $this->optionName1 = $optionName1;
        $this->optionName2 = $optionName2;
        $this->optionName3 = $optionName3;
        $this->optionValue1 = $optionValue1;
        $this->opionValue2 = $opionValue2;
        $this->opionValue3 = $opionValue3;
    }

    // public $name = 'Is Blocked';
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
        if($value['key1'] && $value['key2']){
            return $query;
        }
        if ($value['key1']) {
            return $query->where($this->dbField, $this->optionValue1);
        }
        if ($value['key2']) {
            return $query->where($this->dbField, $this->opionValue2);
        }
        if (isset($value['key3']) && $value['key3']) {
            return $query->where($this->dbField, $this->opionValue3);
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $options = [
            $this->optionName1 => 'key1',
        ];
        if ($this->optionName2) {
            $options[$this->optionName2] = 'key2';
        }
        if ($this->optionName3) {
            $options[$this->optionName3] = 'key3';
        }
        return $options;
    }

    public function key()
    {
        return 'custom_boolean_' . $this->dbField;
    }
}
