<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Project extends Model
{
    //
    use SoftDeletes;
    use Validatable;

    protected $fillable = [
        'code',
        'name',
    ];

    private function rules()
    {
        $unique = Rule::unique('users', 'email');
        $unique = is_null($this->id) ? $unique : $unique->ignore($this->id);
        return [
            'code' => ['required', 'max:255', $unique],
            'name' => ['required', 'max:255'],
        ];
    }

    public function workRecordDetails()
    {
        return $this->hasMany('App\Models\WorkRecordDetail');
    }

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

    public function selectedItem()
    {
        return array($this->id => $this->code . ":" . $this->name);
    }
}
