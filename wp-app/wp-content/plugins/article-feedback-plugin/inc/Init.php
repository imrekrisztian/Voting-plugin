<?php

namespace Inc;

class Init
{

    public function init()
    {
        $assetsManager = new AssetsManager();
        $votingHandler = new VotingHandler();
        $metaBoxManager = new MetaBoxManager();

        // Initialize components
        $assetsManager->init();
        $votingHandler->init();
        $metaBoxManager->init();

    }
}