<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    public function createQuestion($id, $cell_count, $puz)
    {
        $question = new Question();
        $question->cookie_id = $id;
        $question->cell_count = $cell_count;
        $question->puzzle = $puz;
        $question->save();
        return true;
    }
    public function getQuestionList($id)
    {
        $question = new Question();
        $query = $question->where('cookie_id', $id)->get();
        $ret = array();
        foreach ($query as $row) {
            $temp = array();
            $temp["id"] = $row->id;
            $temp["cell_count"] = $row->cell_count;
            //$temp["puzzle"] = json_decode($row->puzzle, true);
            $ret[] = $temp;
        }
        return $ret;
    }
    public function inheritCookie($id, $cookie)
    {
        $question = new Question();
        $query = $question->where('cookie_id', $cookie)->update(['cookie_id'=>$id]);
        return true;
    }
}
