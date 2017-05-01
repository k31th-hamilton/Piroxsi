<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  
  class ResourceTypes extends Model {
    protected $table = 'resourcetypes';
    
    protected $fillable = [      
      'resourceType',
      'updated_at',
      'created_at'
    ];
    
    protected $hidden = [
    
    ];
  }