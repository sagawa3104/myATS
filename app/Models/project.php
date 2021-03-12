<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
    ];

    public static function selectList()
    {
        $projects = Project::orderBy('id', 'desc')->get();
        $list = array();
        $list += array("" => "選択してください");
        foreach ($projects as $project) {
            $list += array($project->id => $project->code . ":" . $project->name);
        }
        return $list;
    }
}
