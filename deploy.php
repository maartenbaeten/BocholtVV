<?php

// All Deployer recipes are based on `recipe/common.php`.
require 'vendor/deployer/deployer/recipe/symfony.php';

// Define a server for deployment.
// Let's name it "prod" and use port 22.
server('prod', '165.227.161.32', 22)
    ->user('bocholtvv')
    ->password('l01sd8kk03jnl4tv10klmdqf55uk1') // You can use identity key, ssh config, or username/password to auth on the server.
    ->stage('production')
    ->env('deploy_path', 'apps/bocholt-vv'); // Define the base path to deploy your project to.

task('database:migrate-db', function () {

    run('php {{deploy_path}}/current/app/console doctrine:migrations:migrate --env={{env}} --no-debug --no-interaction');

})->desc('Migrate database');

// Specify the repository from which to download your project's code.
// The server needs to have git installed for this to work.
// If you're not using a forward agent, then the server has to be able to clone
// your project from this repository.
set('repository', 'git@github.com:maartenbaeten/BocholtVV.git');
set('shared_dirs', ['app/logs', 'web/uploads', 'web/bundles/images']);
