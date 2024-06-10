<div align="center">
  <div><strong>⚡ wp-starter-plugin</strong></div>
  <p>A starting point for your next plugin project</p>
	
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/syntatis/wp-helpers/php?color=%238892be) ![Dynamic JSON Badge](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2Fsyntatis%2Fwp-starter-plugin%2Fmain%2Fpackage.json&query=engines.node&label=node&color=%2368a063) ![Dynamic JSON Badge](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2Fsyntatis%2Fwp-starter-plugin%2Fmain%2Fpackage.json&query=engines.npm&label=npm&color=%23cb3837)

</div>

---

> [!CAUTION]
> This project is still in active development and the code is changing a lot, which might make it unstable. We don’t recommend using it to develop a real plugin for your users yet. Please wait until the project is more stable.

You can start creating a WordPress plugin with just one PHP file, but as your plugin grows and adds more features, you'll need to organize your code and use the right tools to make it easier to manage and maintain.

This WordPress plugin boilerplate gives you a basic structure for your plugin and includes popular tools pre-configured to help you get started quickly and develop your plugin more efficiently. Here are some of the tools included in this boilerplate:

* **[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer)**: Checks and fixes code styles according to common PHP practices.
* **[PHPScoper](https://github.com/humbug/php-scoper)**: Prevents class name collisions with other plugins.
* **[Webpack](https://webpack.js.org/) (via [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/))**: Bundles and optimizes assets.
* **[ESLint](https://eslint.org/)**: Checks and fixes code styles according to common JavaScript practices.

### Prerequisites

Make sure you have the following installed to use these tools:

* **[PHP](https://www.php.net/) (7.4 or later)**: Required to run Composer.
* **[Composer](https://getcomposer.org/)**: Manages PHP dependencies.
* **[Node.js](https://nodejs.org/) (18 or later)**: Required to run NPM.
* **[NPM](https://www.npmjs.com/)**: Manages Node.js and JavaScript dependencies.

## Getting Started

There are two ways to start using this boilerplate:

### With Composer (Recommended) 🎉

If you have Composer installed, you can create a new plugin project using this boilerplate by running this command:

```bash
composer create-project syntatis/wp-starter-plugin -s dev
```

This command creates a new directory named `wp-starter-plugin` and downloads the boilerplate files into it. Once the download is complete, it will run a command to scope the plugin dependencies to prevent class name collisions with other plugins. You can find the scoped dependencies in the `dist-autoload` directory.

For more information, see [Composer CLI documentation on creating a project](https://getcomposer.org/doc/03-cli.md#create-project).

### With GitHub

You can also use this repository as a template to create a new repository with the same boilerplate files. Click the <kbd>Use this template</kbd> button at the top of this page to create a new repository with the same files. However, unlike the Composer method, which immediately pulls and sets up the boilerplate, you will need to clone the repository and run the following commands to scope the plugin dependencies:

```bash
composer scope && composer install
```

For more information, see [Creating a repository from a template](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/creating-a-repository-from-a-template).

## Available Commands

This boilerplate comes with a few Composer and NPM commands to help you manage your plugin during development:

### Composer

| Command | Description |
| --- | --- |
| `composer scope`     | Scope the dependency namespace to prevent conflicts with other plugins. |
| `composer phpcs`     | Check the codebase for coding standards violations |
| `composer phpcs:fix` | Fix coding standards violations |

### NPM

| Command | Description |
| --- | --- |
| `npm run build`    | Build the assets for production. |
| `npm run start`    | Build the assets for development and watch for changes. |
| `npm run lint:js`  | Lint the JavaScript files. |
| `npm run lint:css` | Lint the Stylesheet files. |
| `npm run format`   | Format the JavaScript and Stylesheet files. |

## Test and Preview 🚀

Typically, you will need to zip the plugin files and upload them to your WordPress site to test it. However, if you're using Visual Studio Code, the easiest way to test and preview your plugin is to use the [WordPress Playground](https://marketplace.visualstudio.com/items?itemName=WordPressPlayground.wordpress-playground) extension. This extension allows you to run a local WordPress server and preview your plugin directly from Visual Studio Code.
