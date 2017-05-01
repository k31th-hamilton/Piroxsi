<?php
  namespace App\API;

  interface IResourceInfo {
    public function getCountry() : string;
    public function setCountry(string $country);
    
    public function getCountryFlag() : string;
    public function setCountryFlag(string $countryFlag);
    
    public function getDownloadUrl() : string;
    public function setDownloadUrl(string $downloadUrl);
    
    public function getSessions() : string;
    public function setSessions(string $sessions);
    
    public function getUptime() : string;
    public function setUptime(string $uptime);
  }