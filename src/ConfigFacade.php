<?php namespace NigeLib;

class ConfigFacade extends StaticDelegate
{
    public static function getInstance() {
        return Config::getSingleton();
    }
}
