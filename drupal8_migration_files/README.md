# Drupal 8 migration files

Module to migrate Drupal 8 files to Drupal 8

# Instructions:

## Install Drupal Console
If you don't have it yet, follow the installation instructions [here](https://docs.drupalconsole.com/en/getting/project.html)

## Enable custom module.

This command also will install the module dependencies:

  - migrate_plus
  - migrate_tools
  - migrate_drupal

`$ drupal module:install drupal8_migration_files`

## Setup migration.

Provide credentials to legacy Drupal 8 database, you will be prompted to specify these information:

 - Database type.
 - Database host.
 - database name.
 - Database user.
 - Database password.
 - Database prefix.
 - Database port.
 - Local file directory(*).

(*) Path to the legacy Drupal 8.

`$ drupal migrate:setup`

![alt text][setup]

[setup]: ./images/drupal-migrate-setup.png "Drupal Console migrate setup prompt"


## Execute migration.

You will be prompted to specify the Source site URL and the same information as above in the Setup command.

`$ drupal migrate:execute drupal8_files`

![alt text][execute]

[execute]: ./images/drupal-migrate-execute.png "Drupal Console migrate execute prompt"

You will see confirmation messages like:

![alt text][result]

[result]: ./images/drupal-migrate-execute-result.png "Drupal Console migrate execute prompt"
