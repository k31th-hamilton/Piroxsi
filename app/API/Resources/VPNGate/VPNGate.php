<?php
  namespace App\API\Resources\VPNGate;
  
  use App\API\IResource;  
  
  class VPNGate implements IResource {
    private $vpnGateUrl = 'http://www.vpngate.net/en/';
    
    private function parseLink($downloadUrl) {    
      $justVarsStart = strpos($downloadUrl, '?') + 1;
      $justVars = substr($downloadUrl, $justVarsStart);
      
      $vars = explode('&', $justVars);
      $allVars = [];
      
      foreach ($vars as $single) {
        $keyval = explode('=', $single);
        
        if (count($keyval) > 1) {
          $allVars[$keyval[0]] = $keyval[1];      
        }
      }
      
      return $allVars;
    }
    
    public function GetResourcePIDCommand() {
      return 'ps -A | grep openvpn';
    }
    
    public function DownloadResourceInfo(string $downloadUrl) : string {
      include 'simple_html_dom.php';
      
      try {
        $html = str_get_html(file_get_contents($this->vpnGateUrl . $downloadUrl));
        $vpnGateUrlDownload = str_replace('en/', '', $this->vpnGateUrl);

        $allLinks = $html->find('a[href$=".ovpn"]');

        if (count($allLinks) <= 0) {
          return [ 'success' => false,
                   'error' => 'Could not find proxy.  Try refreshing the list.' ];
        } else {
          foreach ($allLinks as $proxyLink) {
            $href = html_entity_decode($proxyLink->getAttribute('href'));

            if (substr($href, 0, 1) === '/') {
              $href = substr($href, 1);
            }

            $vars = $this->parseLink($href);        
            $localVars = $this->parseLink($downloadUrl);

            if (($localVars['ip'] === $vars['host']) && array_key_exists('udp', $vars)) {
              $remoteFileUrl = $vpnGateUrlDownload . $href;                
              
              return file_get_contents($remoteFileUrl);              
            }
          }
        }
      } catch (Exception $ex) {
        return '';
      }      
    }
    
    public function GetResources() {
      include 'simple_html_dom.php';
      
      $retResources = [];
      $flagDirectory = base_path('public/assets/images/flags');      
      
      $html = str_get_html(file_get_contents($this->vpnGateUrl));
      $allLinks = $html->find('a[href^="do_openvpn.aspx?"]');                  

      foreach ($allLinks as $link) {
        $tableRow = $link->parent->parent;
        $downloadUrl = $link->getAttribute('href');
        $firstColumn = $tableRow->find('td', 0);
        $countryContents = $firstColumn->innertext;
        $country = substr($countryContents, strpos($countryContents, '<br>') + 4);

        $countryFlag = $firstColumn->find('img', 0);    

        $sessions = $tableRow->find('td', 2)->find('span', 0)->innertext;
        $uptime = $tableRow->find('td', 2)->find('span', 1)->innertext;

        $countryFlagImageFileName = $countryFlag->getAttribute('src');
        $countryFlagImageLocalFileName = $flagDirectory . basename($countryFlagImageFileName);
        $countryFlagImageWebFileName = 'assets/images/flags/' . basename($countryFlagImageFileName);

        if (!file_exists($countryFlagImageLocalFileName)) {
          file_put_contents($countryFlagImageLocalFileName, file_get_contents($this->vpnGateUrl . $countryFlagImageFileName));
        }

        $newResourceInfo = new VPNGateResource();
        $newResourceInfo->setCountry($country);
        $newResourceInfo->setCountryFlag($countryFlagImageWebFileName);
        $newResourceInfo->setDownloadUrl($downloadUrl);
        $newResourceInfo->setSessions($sessions);
        $newResourceInfo->setUptime($uptime);

        array_push($retResources, $newResourceInfo);

      }      
      
      return $retResources;
    }
  }