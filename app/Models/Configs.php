<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  
  class Configs extends Model {
    protected $table = 'configs';
    
    protected $fillable = [
      'currentResource',
      'connected',        
      'updated_at',
      'created_at'
    ];
    
    protected $hidden = [
    
    ];
  }