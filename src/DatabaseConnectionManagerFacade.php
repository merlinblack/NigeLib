<?php namespace NigeLib;

class DatabaseConnectionManagerFacade extends StaticDelegate
{
    public static function getInstance() {
        return DatabaseConnectionManager::getSingleton();
    }
}