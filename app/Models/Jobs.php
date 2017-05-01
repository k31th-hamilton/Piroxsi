<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Jobs extends Model {
    protected $table = 'jobs';
    
    protected $fillable = [
      'id',
      'connection',
      'queue',
      'payload',
      'exception',
      'failed_at'      
    ];
    
    protected $hidden = [
        
    ];
  }