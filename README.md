# Parse tags

This project is in tag directory.

## Requirements

 - PHP 8
 - php composer
 - php unit tests

## Running/Installation

- Get project and move files on server.
- Install and/or run composer (for getting dependencies).
- Change .htaccess if needed.
- Run project in browser from project folder on server.

# Last versions

```
SELECT v.id, v2.version, v.content 
FROM `versions` AS v 
JOIN (SELECT id, max(version) AS version FROM versions GROUP BY id) AS v2 ON (v2.id = v.id AND v2.version = v.version) 
```

# Counter web application

This project has login form and profile section with counter.

## Implementation

Project has api part and web (front-end) part.\
Api part contains App, Core, Model, Entity and Helper directories.\
App directory contains App.php for running project, Request.php, Response.php and all controllers.\
Core directory contains database connector.\
Model directory has models for database requests.\
Entity directory has table objects.\
Helper directory helps project (for example, Locale).\
Web part contains html, css, js files.

## Requirements

 - PHP 8
 - sqlite
 - SSL (HTTPS)

## Running/Installation

- Move api part on server.
- Move web part on your place.
- Change some data in config.php in api part if needed.
- Change .htaccess in api part if needed.
- Change in web part (js/client.js) path to API.
- Run web part (index.html) from your place.

## Settings

Change some variables in App/Request.php (if needed). For example, firstUriItem variable - by default 0, it means that project is in root of domain (https://domain.com), 1 means that project in folder of domain (https://domain.com/project_folder/).