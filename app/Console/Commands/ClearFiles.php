<?php
  namespace App\Console\Commands;
  
  use Illuminate\Console\Command;

  class ClearFiles extends Command {
    protected $name = 'clearfiles';    
    protected $description = 'Clears out the logs and other things and refreshes the database';
    
    public function fire() {      
      array_map('unlink', glob(base_path('storage/app/*.pir')));
      array_map('unlink', glob(base_path('storage/logs/*.log')));
      
      unlink(base_path('storage/database/piroxsi.sqlite'));
      touch(base_path('storage/database/piroxsi.sqlite'));
      
      $this->call('migrate');
      $this->call('db:seed');
      
      $this->call('cache:clear');
    }
  }