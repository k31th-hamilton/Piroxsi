<?php
  namespace App\API;
  
  interface IResource {
    public function GetResources();
    public function GetResourcePIDCommand();
    public function DownloadResourceInfo(string $downloadUrl) : string;
  }
