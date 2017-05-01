<?php
  namespace App\Http;
  
  class PiroxsiResponse {
    private $command = '';
    private $arguments = [];
        
    public function setCommand(string $command) {
      $this->command = $command;
    }
    public function getCommand() : string {
      return $this->command;
    }
    
    public function getArguments() : array {
      return $this->arguments;
    }
    public function addArgument($key, $value) {
      $this->arguments[$key] = $value;
    }
    
    public function toAssoc() {
      return [ 'command' => $this->command,
               'args' => $this->arguments ];
    }
  }