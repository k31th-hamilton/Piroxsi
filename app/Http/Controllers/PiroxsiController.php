<?php
  namespace App\Http\Controllers;

  use App\API\PiroxsiResources;
  use App\Http\PiroxsiResponse;
  use App\Jobs\StartResource;
  use App\Jobs\StopResource;
  use App\Models\Configs;
  use App\Models\Resources;
  use App\Models\ResourceTypes;
  use Laravel\Lumen\Routing\Controller;  
  
  class PiroxsiController extends Controller {
    public function ResourceList() {
      return view('resourcelist')->with([
        'resources' => Resources::all(),
        'config' => Configs::where('id', 1)->first()
      ]);
    }
    
    public function ResourceStatus($id) {
      return view('resourcestatus')->with([
        'resource' => Resources::where('id', $id)->first(),
        'config' => Configs::where('id', 1)->first()
      ]);
    }
    
    public function DownloadResourceInfo($id) {
      $currentResource = Resources::where('id', $id)->first();
      $currentResourceType = ResourceTypes::where('id', $currentResource->resourceType)->first();
      $currentResponse = new PiroxsiResponse();
      
      try {        
        $resource = new $currentResourceType->resourceType();
        
        $downloadedConfig = $resource->DownloadResourceInfo($currentResource->downloadUrl);
        $currentResource->rsettings = $downloadedConfig;
        $currentResource->save();

        $currentConfig = Configs::where('id', 1)->first();
        $currentConfig->currentResource = $currentResource->id;
        $currentConfig->save();

        $currentResponse->setCommand('true');    
      } catch (Exception $ex) {
         $currentResponse->setCommand('false');
         $currentResponse->addArgument('error', $ex->getMessage());
      }
      
      return $currentResponse->toAssoc();
    }
    
    public function RefreshResourceList() {
      Resources::truncate();
      ResourceTypes::truncate();
      
      $response = new PiroxsiResponse();
            
      try {
        $resourceList = PiroxsiResources::getAllResources();
        
        foreach ($resourceList as $resourceClass) {
          $newResourceType = ResourceTypes::create([
                               'resourceType' => $resourceClass,
                               'updated_at' => time(),
                               'created_at' => time()
                             ]);
          
          $resource = new $resourceClass();
          
          foreach ($resource->GetResources() as $currentResource) {
            Resources::create([
              'country' => $currentResource->getCountry(),
              'countryFlag' => $currentResource->getCountryFlag(),
              'sessions' => $currentResource->getSessions(),
              'uptime' => $currentResource->getUptime(),
              'downloadUrl' => $currentResource->getDownloadUrl(),
              'resourceType' => $newResourceType->id,
              'updated_at' => time(),
              'created_at' => time()                
            ]);
          }
        }
        
        $response->setCommand('true');        
      } catch (Exception $ex) {
        $response->setCommand('false');
        $response->addArgument('error', $ex->getMessage());
      }      
      
      return $response->toAssoc();
    }
    
    public function ResourceRefresh($command) {
      $response = new PiroxsiResponse();
      
      try {
        $currentConfig = Configs::where('id', 1)->first();
        
        if (isset($currentConfig)) {
          $currentResource = Resources::where('id', $currentConfig->currentResource)->first();
          $currentResourceType = ResourceTypes::where('id', $currentResource->resourceType)->first();

          if (isset($currentResource)) {
            switch ($command) {            
              case 'connect':
                if (isset($currentConfig)) {                 
                  $currentConfig->connected = true;                        
                  $startJob = new StartResource($currentResource->id,
                                                $currentResource->rsettings);
                  $this->dispatch($startJob);

                  $response->setCommand('setmessage');
                  $response->addArgument('message', 'Starting Resource');
                  $response->addArgument('cssclass', 'label-success');
                } else {
                  $response->setCommand('false');
                  $response->addArgument('error', 'There is no configuration for this resource');
                }            
                break;
                
              case 'disconnect':
                $currentConfig->currentResource = null;
                $currentConfig->connected = false;

                $stopJob = new StopResource();
                $this->dispatch($stopJob);
                
                $response->setCommand('true');               
                break;
              
              case 'update':
                if ($currentConfig->connected) {
                  $oRe = new $currentResourceType->resourceType();           
                  $pids = [];
                  
                  exec($oRe->GetResourcePIDCommand(), $pids);

                  if (empty($pids)) {
                    $response->setCommand('pidnotfoundmessage');
                    $response->addArgument('message', 'pid not found for connection');
                    $response->addArgument('cssclass', 'label-danger'); 
                    
                    $currentConfig->connected = false;
                  } else {
                    $response->setCommand('pidfoundmessage');
                    $response->addArgument('message', count($pids) . ' pid(s) found and active');
                    $response->addArgument('cssclass', 'label-success');
                    
                    $tunIpCheck = 'ifconfig tun0 | awk \'/t addr:/{gsub(/.*:/,"",$2);print$2}\'';
                    $ipOutput = [];
                    exec($tunIpCheck, $ipOutput);
                    
                    if (count($ipOutput) > 0) {
                      if (filter_var($ipOutput[0], FILTER_VALIDATE_IP)) {
                        $response->addArgument('ipaddress', $ipOutput[0]);
                        
                        $currentConfig->connected = true;
                      } else {
                        $response->addArgument('ipaddress', 'none');
                      }
                    }
                  }
                } else {
                  $response->setCommand('setmessage');
                  $response->addArgument('message', 'Disconnected');
                  $response->addArgument('cssclass', 'label-danger');
                }
                break;              
            } 
          } else {
            $response->setCommand('false');
            $response->addArgument('error', 'There is no selected resource');
          }
           
          $currentConfig->save();                
        } else {

        }
      } catch (Exception $ex) {
          $response->setCommand('error');
          $response->addArgument('error', $ex->getMessage());
      }
      
      return $response->toAssoc();
    }
  }
