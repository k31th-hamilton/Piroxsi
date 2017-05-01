<?php
  namespace App\Jobs;  
  
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Queue\InteractsWithQueue;
  use Illuminate\Queue\SerializesModels;

  class StopResource extends Job implements ShouldQueue {
    use InteractsWithQueue,
        SerializesModels;

    public function __construct() {
      
    }
    
    public function handle() {      
      $cmd = 'sudo ' . base_path('resources/scripts/stopResource');
      
      exec($cmd);      
    }
  }
