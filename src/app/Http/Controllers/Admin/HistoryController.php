<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConst;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request){
        $historiesQuery = History::query();

        if($request->user_id){
            $historiesQuery->where('user_id', $request->user_id);
        }
        else{
            $historiesQuery->with('user');
        }

        if($request->date){
            $historiesQuery->whereDate('created_at', $request->date);
        }
        $histories = $historiesQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);

        return $histories;
    }

}
