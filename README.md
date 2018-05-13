# Zend Expressive Swagger Codegen

With this package you can easily generate Handlers, Models and Routes according to your OpenAPI Specification (OAS) 3.0 config.

## Requirements

* Zend Expressive +3.0

## Installation

`composer require bradcrumb/ze-swagger-codegen`

## Usage

Simply place your `openapi.json` inside of your project root and run the following command:

`vendor/bin/swagger codegen`

By default the choosen namespace will be `App`. You can overwrite the namespace with the --namespace option:

`vendor/bin/swagger codegen --namespace MyOwnNamespace`

The CLI will search for the correct path inside of the composer autoloading. When a namespace is not registered for autoloading it will ask if the namespace folder have to be created and registered in `composer.json`.

## Files

The Codegen will generate the following files:

| File | Description |
|:---|:---|
| `config/autoload/swagger.dependencies.global.php` | Generated dependency and hydrators config |
| `config/swagger.routes.php` | Swagger routing config |
| `*/Handler/*` | Namespace to put all generated Handlers |
| `*/Model/*` | Namespace to put all generated Models |
| `*/Hydrator/*` | Namespace to put all generated Hydrators |
