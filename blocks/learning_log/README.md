# Learning log
## Purpose
Allow users to capture hours spent on learning activities, e.g. for reporting Continuing Professional Development (CPD) hours.

## Features
The plugin allows users to:

* Record hours spent 
* Edit events
* Delete events

This block plugin is only suitable for use on the user's Dashboard.

## Structure
The plugin is based on the ToDo plugin by David Mudrák <david@moodle.com> and David Woloszyn <david.woloszyn@moodle.com>. 

It uses the following coding techniques:

* HTML output rendered using Mustache templates
* AJAX workflow for the elementary CRUD operations
* JavaScript organised into AMD modules
* External functions organised into traits
* Low-level access to the database via persistent models
* Exporters for handling the data structures
