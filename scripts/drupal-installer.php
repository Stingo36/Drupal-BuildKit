#!/usr/bin/env php
<?php

echo "🎉 Welcome to the Stingo BuildKit Installer\n\n";

// DB Info
echo "Enter DB name: ";
$db_name = trim(fgets(STDIN));

echo "Enter DB user: ";
$db_user = trim(fgets(STDIN));

echo "Enter DB password: ";
$db_pass = trim(fgets(STDIN));

echo "Enter DB host [127.0.0.1]: ";
$db_host = trim(fgets(STDIN)) ?: '127.0.0.1';

// Admin info
echo "Enter admin username [admin]: ";
$admin_user = trim(fgets(STDIN)) ?: 'admin';

echo "Enter admin password [admin]: ";
$admin_pass = trim(fgets(STDIN)) ?: 'admin';

echo "Enter site name [Stingo Site]: ";
$site_name = trim(fgets(STDIN)) ?: 'Stingo Site';

echo "\n🔧 Creating database '$db_name'...\n";
exec("mysql -u$db_user -p$db_pass -e 'CREATE DATABASE IF NOT EXISTS `$db_name`;'", $output, $status);

if ($status !== 0) {
    echo "❌ Failed to create database. Please check your MySQL credentials.\n";
    exit(1);
}

echo "\n🚀 Installing Drupal using profile stingo_buildkit...\n";
$cmd = "vendor/bin/drush site:install stingo_buildkit --db-url=mysql://$db_user:$db_pass@$db_host/$db_name --site-name=\"$site_name\" --account-name=$admin_user --account-pass=$admin_pass -y";
passthru($cmd);

echo "\n✅ Done! Log in at /user/login with: $admin_user / $admin_pass\n";
