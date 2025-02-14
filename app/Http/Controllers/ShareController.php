<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function index()
    {
        $setting = Setting::surveyLink()->first();
        return redirect()->away(optional($setting)->value ?? 'https://www.southern.gov.bh/en/');
    }
}
