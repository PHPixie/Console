<?php 

class Implementation
{
    public function __construct($configBuilder, $defaultCommand)
    {
        $this->configBuilder = $configBuilder;
    }
    
    protected function config()
    {
        return $this->configBuilder->config();
    }
    
    public function command($name)
    {
        
    }
    
    protected function nestedRegistries()
    {
        if($this->nestedRegistries === null) {
            $this->nestedRegistries = $this->buildNestedRegistries();
        }
    }
    
    protected function buildNestedRegistries()
    {
        return array();
    }
}
