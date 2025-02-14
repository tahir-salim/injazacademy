<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FavouriteProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavouriteProgramController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'program_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $alreadyFavourite = FavouriteProgram::where([
            ['user_id', '=', Auth::id()],
            ['program_id', '=', $request->program_id],
        ])->first();

        if ($alreadyFavourite) {
            $alreadyFavourite->delete();
            return $this->formatResponse('success', 'favourite-program-deleted');
        }

        $favourite = new FavouriteProgram();
        $favourite->user_id = Auth::id();
        $favourite->program_id = $request->program_id;
        $favourite->save();
        return $this->formatResponse('success', 'favourite-program-inserted', $favourite, 200);

    }

}
