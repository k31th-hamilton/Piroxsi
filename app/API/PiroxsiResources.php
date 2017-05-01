<?php
  namespace App\API;

  class PiroxsiResources {
    public static function getAllResources() {
      return [
        \App\API\Resources\VPNGate\VPNGate::class,  
      ];
    }
  }