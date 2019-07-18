<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wildside\Userstamps\Userstamps;
    
    protected $table = "questions";
    
    protected $guarded = ['id'];
}
