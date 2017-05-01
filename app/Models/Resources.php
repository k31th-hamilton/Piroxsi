<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  
  class Resources extends Model {
    protected $table = 'resources';
    
    protected $fillable = [
      'country',
      'countryFlag',
      'sessions',
      'uptime',        
      'downloadUrl',
      'rsettings',
      'resourceType',
      'updated_at',      
      'created_at'
    ];
    
    protected $hidden = [
    
    ];
    
    
    public function resourceType() {
      return $this->hasOne('App\Models\ResourceTypes');
    }
    
  }