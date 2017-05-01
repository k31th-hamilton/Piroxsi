<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class FailedJobs extends Model {
    protected $table = 'failed_jobs';
    
    protected $fillable = [
      'id',
      'queue',
      'payload',
      'attempts',
      'reserved',
      'reserved_at',
      'available_at',
      'created_at'        
    ];
    
    protected $hidden = [
        
    ];
  }
