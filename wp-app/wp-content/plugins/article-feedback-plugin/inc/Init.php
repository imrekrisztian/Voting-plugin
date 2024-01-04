<?php

namespace Inc;

class Init
{

    public function init(): void
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