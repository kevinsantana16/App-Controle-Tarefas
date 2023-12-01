<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['tarefa', 'data_limite_conclusão', 'user_id'];
 
 public  function user(){
        return $this->belongsTo('App\Models\User');
    }
}
