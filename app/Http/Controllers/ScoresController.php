<?php

namespace App\Http\Controllers;

use App\Models\Scores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoresController extends Controller
{
    public function index() {
        // show all scores
        $user = Auth::guard('sanctum')->user();
        return Scores::query()->with('user')->with('file')->where('user_id','=',$user->id)->get();
    }

    public function show($id) {
        $user = Auth::guard('sanctum')->user();
        return Scores::query()->with('user')->with('file')->where('user_id','=',$user->id)->where('file_id','=',$id)->get();
    }

    public function store() {
        return "hello";
    }
}
