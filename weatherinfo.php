<?php
use GeoIp2\Database\Reader;

if (!defined('_PS_VERSION_')) {
    exit;
}

class WeatherInfo extends Module{
    public function __construct(){
        $this->name = 'weatherinfo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Axel Libori Roch';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Weather Info');
        $this->description = $this->l('Module to show basic weather info on header');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('WEATHERINFO_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install(){
        return parent::install() &&
            $this->registerHook('displayNav1') &&
            Configuration::updateValue('WEATHERINFO_NAME', 'weeatherinfo');
    }

    public function uninstall(){
        if (!parent::uninstall() ||
            !Configuration::deleteByName('WEATHERINFO_NAME')
        ) {
            return false;
        }

        return true;
    }

    public function hookDisplayNav1(){
        $apiKEY = 'WRITE HERE YOUR API KEY';

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // In production use Tools:getRemoteAddr();
        
        if ($ip != '::1') {
            // City DB
            $reader = new Reader('./app/Resources/geoip/GeoLite2-City.mmdb');
            $record = $reader->city($ip);
                    
            $weatherInfo = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=".$record->city->name."&units=metric&appid=".$apiKEY."");

            $weather = json_decode($weatherInfo, true);

            $this->context->smarty->assign('weather', $weather);

            return $this->display(__FILE__, 'weatherinfo.tpl');
        }
    }
}

