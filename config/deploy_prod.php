<?php
use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;


return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('www-aoe@aoe.global:9922')
            ->deployDir('/var/www/test.aoe.global')
            ->repositoryUrl('git@gitlab.com:aoe_global/AOEadmin.git')
            ->repositoryBranch('master')
            ;
    }
};