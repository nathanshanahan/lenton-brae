# Bone WordPress base project

Built on Bedrock, with Bone 'skeleton' starter theme. Plugins and other PHP dependencies managed by _Composer_, theme assets built with _Vite_.

```
PHP >= 8.3
Nodejs >= 18
WordPress >= 6.5.0
```

## Quick Start

* Pull base project repository
* Rename root and theme folders
* Install composer dependencies in root folder
* Install node dependencies (_Yarn_) in theme folder
* Set up environment variables
* Set up your local/dev server
* Install WordPress
* Run `yarn dev` from the theme folder
* Open the local website (not localhost!) in your browser and start codin'!




## Getting started - full steps

### Set up the repo
* Pull the repository
* Delete the `.git` folder
* Rename the root folder from `wp-base-project` to your project name. Typically this should be the full client's name, in kebab case.
* Update the `style.css` file in the skeleton theme with the project name (usually the client's name)
```sh
# style.css
<project-name>/web/app/themes/skeleton/style.css
```
* (Optional) rename the theme folder from 'skeleton' to match the same name as the root folder (i.e client's name in kebab case)
* Initialise a new git repo with `git init` and connect it to the origin repo in Bitbucket
* Commit your current project state as the initial commit and push it to origin.

### Install dependencies

* In the root folder, install composer dependencies with `composer install`
* In the theme folder, install dependencies with `yarn install`
	* Note: if you use _nvm_ to manage your node versions you can use the command `nvm use` to automatically set the correct nodejs version.
* Commit and push your updates

### Get going with WordPress 
* Set up you local WP dev environment. Typically your webroot should be:
```sh
# webroot
<project-name>/web
```

* Start your server and open the site in your browser to complete WP installation.


## Development

Build and dev tools provided by _Vite_.


### Hot reloading
This theme supports _hot reloading_ of CSS, as well as automatic page refreshing on change to JS and PHP files. 

The following environment variables are key to having this work:
```
WP_HOME='http://project-name.test'
DEV_HOST='http://localhost'
DEV_URL="${DEV_HOST}:3000"
```

To start developing with hot reloading, start the vite dev server with `yarn dev` in the theme folder, and in your browser navigate to the domain set by `WP_HOME` - _NOT_ localhost! In other words, with the config above you will be working on `http://project-name.test` rather than localhost:3000


#### localhost doesn't do what you think...
The `DEV_HOST` and `DEV_URL` environment variables set where your _dev assets_ are served from - it is _not_ a proxy for your local WP install. With this setup WordPress checks for the dev server and if found enqueues the _Vite_ client and dev assets, thus enabling dev assets with hot reloading but on a real domain.

#### Why do it this way?
While this approach is a little unconventional it enables some powerful usecases:
* WP admin also supports hot reloading, so styling is as easy as it is on the frontend
* It enables _**remote development of CSS and JS directly on staging and production**_.
	
This second point is especially powerful. Not only does it provide an avenue for working on features that are locked to a specific domain (e.g. 3rd party widgets), but on an existing site it enables CSS- and JS-only updates to be done without needing to set up a local WP environment - just pull the repo, `yarn install` and `yarn dev`.

#### Developing on remote environments
_Docs TODO_


### Managing assets
Asset generation can be managed in `vite.config.js`.

JS and SASS/CSS bundles to be generated are defined in an `entries` array that looks something like this:
```js
// example entries
const entries = [
	{ name: 'main', source: './src/js/main.js'},
	{ name: 'admin', source: './src/js/admin.js'},
	{ name: 'main-css', source: './src/scss/main-css.js'},
	{ name: 'admin-css', source: './src/scss/admin-css.js'},
];
```

On build, Vite generates a manifest file (`<theme-name>/dist/manifest.json`) which WP uses to find the correct file including hashing etc. The `name` prop corresponds to the handle WP will use to identify the desired asset.

```json
// example manifest.json
{
	"main": {
		"handle": "main",
		"fileName": "/dist/assets/main-ffcee205.js",
		"source": "main.js"
	},
	"admin": {
		"handle": "admin",
		"fileName": "/dist/assets/admin-cbb7f6f8.js",
		"source": "admin.js"
	},
	"main-css": {
		"handle": "main-css",
		"fileName": "/dist/assets/main-css-1e744f20.css",
		"source": "main.scss"
	},
	... etc
}

```

In WordPress, during theme setup we create a global variable `$assets`, which is an instance of the `ViteAssets` class. This provides helpers so we can enqueue assets like so:
```php
// enqueueing assets

global $assets;

wp_enqueue_script('app-main', $assets->uri('main'));
wp_enqueue_style('app-main-css', $assets->uri('main-css'));
```
Note that the handle passed to `$assets->uri()` matches the `name` prop in the `entries` array.


#### EXAMPLE - Adding a new bundle
```js
// define in vite.config.js

const entries = [
	{ name: 'main', source: './src/js/main.js'},
	{ name: 'admin', source: './src/js/admin.js'},
	{ name: 'main-css', source: './src/scss/main-css.js'},
	{ name: 'admin-css', source: './src/scss/admin-css.js'},
	// new bundle
	{ name: 'embed-forms', source: './src/js/embed-forms.js'},
];
```

```php
// enqueue in functions.php (or similar)

if (request_is_iframe()) {
	global $assets;
	wp_enqueue_script('app-embed-forms', $assets->uri('embed-forms'));
}
```





