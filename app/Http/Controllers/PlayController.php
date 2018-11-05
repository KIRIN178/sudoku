<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Lib\Sudoku;

class PlayController extends Controller
{
    public function index($id, Request $request)
    {
        $cookie_id = $request->cookie('id');
        if (is_null($cookie_id)) {
            return redirect('/menu');
        }
        //\DB::enableQueryLog();
        $question = new Question();
        $query = $question->where('cookie_id', $cookie_id)->where('id', (int)$id)->firstOrFail();
        $data["puzzle"] = json_decode($query->puzzle, true);
        $data["id"] = $id;
        return view('play', $data);
    }
    public function correction($id, Request $request)
    {
        $cookie_id = $request->cookie('id');
        if (is_null($cookie_id)) {
            return redirect('/menu');
        }
        $question = new Question();
        $query = $question->where('cookie_id', $cookie_id)->where('id', (int)$id)->firstOrFail();
        $puz = json_decode($query->puzzle, true);
        $puzzle = new Sudoku();
        $puzzle->setPuzzle($puz);
        if ($puzzle->isSolvable() && $puzzle->isSolved() !== true) {
            $puzzle->solve();
            $solution = $puzzle->getSolution();
            $ans = $request->get('ans');
            $c = 0;
            $correction = array();
            foreach ($puz as $x => $row) {
                foreach ($row as $y => $col) {
                    if ($col == 0) {
                        if ($ans[$c] == '') {
                            $correction[] = 2;
                        } elseif ((int)$solution[$x][$y] == (int)$ans[$c]) {
                            $correction[] = 1;
                        } else {
                            $correction[] = 0;
                        }
                        $c++;
                    }
                }
            }
            return response()->json(['status'=>'ok','correction'=>$correction,'ans'=>$solution]);
        } else {
            return response()->json(['status'=>'error','aaa'=>$puzzle->isSolvable()]);
        }
    }
    public function surrender($id, Request $request)
    {
        $cookie_id = $request->cookie('id');
        if (is_null($cookie_id)) {
            return redirect('/menu');
        }
        $question = new Question();
        $query = $question->where('cookie_id', $cookie_id)->where('id', (int)$id)->firstOrFail();
        $puz = json_decode($query->puzzle, true);
        $puzzle = new Sudoku();
        $puzzle->setPuzzle($puz);
        if ($puzzle->isSolvable() && $puzzle->isSolved() !== true) {
            $puzzle->solve();
            $solution = $puzzle->getSolution();
            $ans = $request->get('ans');
            $c = 0;
            $correction = array();
            foreach ($puz as $x => $row) {
                foreach ($row as $y => $col) {
                    if ($col == 0) {
                        $correction[] = $solution[$x][$y];
                    }
                }
            }
            return response()->json(['status'=>'ok','correction'=>$correction,'ans'=>$solution]);
        } else {
            return response()->json(['status'=>'error']);
        }
    }
}
