<?php
  namespace App\API\Resources\VPNGate;

  use App\API\IResourceInfo;

  class VPNGateResource implements IResourceInfo {
    private $country = '';
    private $countryFlag = '';
    private $downloadUrl = '';
    private $sessions = '';
    private $uptime = '';

    public function getCountry(): string {
      return $this->country;
    }
    public function setCountry(string $country){
      $this->country = $country;
    }

    public function getCountryFlag(): string {
      return $this->countryFlag;
    }
    public function setCountryFlag(string $countryFlag) {
      $this->countryFlag = $countryFlag;
    }

    public function getDownloadUrl(): string {
      return $this->downloadUrl;
    }
    public function setDownloadUrl(string $downloadUrl) {
      $this->downloadUrl = $downloadUrl;
    }

    public function getSessions(): string {
      return $this->sessions;
    }
    public function setSessions(string $sessions) {
      $this->sessions = $sessions;
    }

    public function getUptime(): string {
      return $this->uptime;
    }
    public function setUptime(string $uptime) {
      $this->uptime = $uptime;
    }
  }