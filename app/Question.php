<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	protected $table = 'question';
	public function create_question($id,$cell_count,$puz)
	{
		$question = new Question();
		$question->cookie_id = $id;
		$question->cell_count = $cell_count;
		$question->puzzle = $puz;
		$question->save();
		return true;
	}
    public function get_question_list($id)
	{
		$question = new Question();
		$query = $question->where('cookie_id',$id)->get();
		$ret = array();
		foreach($query as $row)
		{
			$temp = array();
			$temp["id"] = $row->id;
			$temp["cell_count"] = $row->cell_count;
			//$temp["puzzle"] = json_decode($row->puzzle, true);
			$ret[] = $temp;
		}
		return $ret;
	}
	public function inherit_cookie($id, $cookie)
	{
		$question = new Question();
		$query = $question->where('cookie_id', $cookie)->update(['cookie_id'=>$id]);
		return true;
	}
}
