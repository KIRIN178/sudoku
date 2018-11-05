<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use App\Lib\Sudoku;
use App\Question;

class MenuController extends Controller
{
	public function index(CookieJar $cookieJar, Request $request)
	{
		$id = $request->cookie('id');
		$data["questions"] = array();
		if(is_null($id))
		{
			$id = uniqid('', true);
			$cookieJar->queue(cookie('id', $id, 2628000));
		}
		else
			$cookieJar->queue(cookie('id', $id, 2628000));
		$data["id"] = $id;
		$question = new Question();
		$data["list"] = $question->get_question_list($id);
    	return view('menu',$data);
	}
	public function create(Request $request)
	{
		$id = $request->cookie('id');
		if(is_null($id))
			return response()->json(['status'=>'error']);
		$cell_count = $request->get('cell_count');
		if((int)$cell_count != 27 && (int)$cell_count != 30 && (int)$cell_count != 38 && (int)$cell_count != 48)
			return response()->json(['status'=>'error']);
		$puzzle = new Sudoku();
		$puzzle->generatePuzzle((int)$cell_count);
		$puz = json_encode($puzzle->getPuzzle());
		$question = new Question();
		if($question->create_question($id,$cell_count,$puz) !== true)
		{
			$request->session()->flash('message_type', 'dangers');
			$request->session()->flash('message', '問題の作成は失敗しました。');
			return response()->json(['status'=>'error']);
		}
		$request->session()->flash('message_type', 'success');
		$request->session()->flash('message', '問題の作成は完成しました。');
		return response()->json(['status'=>'ok']);
	}
	public function delete(Request $request)
	{
		$cookie_id = $request->cookie('id');
		if(is_null($cookie_id))
			return response()->json(['status'=>'error']);
		$id = $request->get('id');
		$question = new Question();
		$puz = $question->where('cookie_id', $cookie_id)->findOrFail($id);
		$puz->delete();
		$request->session()->flash('message_type', 'success');
		$request->session()->flash('message', '問題の削除は完成しました。');
		return response()->json(['status'=>'ok']);
	}
	public function inherit(Request $request)
	{
		$id = $request->cookie('id');
		if(is_null($id))
			return response()->json(['status'=>'error']);
		$cookie_id = $request->get('cookie_id');
		$question = new Question();
		if($question->inherit_cookie($id,$cookie_id) !== true)
		{
			$request->session()->flash('message_type', 'dangers');
			$request->session()->flash('message', '引継ぎは失敗しました。');
			return response()->json(['status'=>'error']);
		}
		$request->session()->flash('message_type', 'success');
		$request->session()->flash('message', '引継ぎは完成しました。');
		return response()->json(['status'=>'ok']);
	}
}
