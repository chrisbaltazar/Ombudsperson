<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wildside\Userstamps\Userstamps;
    
    protected $guarded = ['id', 'file', 'url', 'source'];
}
