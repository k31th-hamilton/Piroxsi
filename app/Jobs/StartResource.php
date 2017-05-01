<?php
  namespace App\Jobs;  
  
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Queue\InteractsWithQueue;
  use Illuminate\Queue\SerializesModels;

  class StartResource extends Job implements ShouldQueue {
    use InteractsWithQueue,
        SerializesModels;
    
    private $resourceId = -1;
    private $config = '';
    
    public function __construct(int $resourceId, string $config) {
      $this->resourceId = $resourceId;
      $this->config = $config;
    }
    
    public function handle() {
      exec('sudo ' . base_path('resources/scripts/stopResource'));      
      
      file_put_contents(base_path('storage/app/' . $this->resourceId . '.pir'), $this->config);
      $cmd = 'sudo '. base_path('resources/scripts/startResource') . ' ' 
                    . base_path('storage/app/' . $this->resourceId . '.pir');
    
      exec($cmd);
    }
  }