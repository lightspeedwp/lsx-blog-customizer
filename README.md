# LSX Blog Customizer

This extension gives you complete control over the appearance of your LSX-powered WordPress blog.

## Setup

### 1: Install NPM
https://nodejs.org/en/

### 2: Install Gulp
`npm install`

This will run the package.json file and download the list of modules to a "node_modules" folder in the plugin.

### 3: Install Composer

Run the following two commands, this will install all the Composer Modules. 

This you need to do while inside the plugin directory.
`curl -sS https://getcomposer.org/installer | php`
 
Wait for the terminal to finish and test by running
`php composer.phar install`

### 4: Gulp Commands
`gulp watch`
`gulp compile-sass`
`gulp compile-js`
`gulp wordpress-lang`

## Changelog

### 1.0.0 - 26/01/17
* First Version
