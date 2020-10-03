<?php

if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'protected_to_private' => false,
    ))
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')

    )
;
