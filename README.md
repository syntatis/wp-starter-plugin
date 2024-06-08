> [!CAUTION]
> This project is still in active development and the code is changing a lot, which might make it unstable. We don’t recommend using it to develop a real plugin for your users yet. Please wait until the project is more stable.

<div align="center">
  <div><strong>⚡ wp-starter-plugin</strong></div>
  <p>A starting point for your next plugin project</p>
	
![Packagist Dependency Version](https://img.shields.io/packagist/dependency-v/syntatis/wp-helpers/php)
</div>

---

Creating a WordPress plugin can begin with just a single PHP file, but as your plugin grows and adds more features, you'll need to organize your code and use the right tools to make it easier to manage and maintain.

This WordPress plugin boilerplate provides a basic minimal opininated structure for your plugin and includes some popular tools pre-configured to help you get started quickly and develop your plugin more efficiently. Here are some of the tools included in this boilerplate:

## Tools

* **[Composer](https://getcomposer.org/)**: Manages PHP dependencies.
* **[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer)**: Checks and fixes code styles according to common PHP practices.
* **[PHPScoper](https://github.com/humbug/php-scoper)**: Prevents class name collisions with other plugins.
* **[NPM](https://www.npmjs.com/)**: Manages JavaScript dependencies.
* **[Webpack](https://webpack.js.org/) (via [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/))**: Bundles and optimizes assets.
* **[ESLint](https://eslint.org/)**: Checks and fixes code styles according to common JavaScript practices.

## Getting Started

There are a couple of ways you can start using this boilerplate:

### With Composer (Recommended)

If you have Composer installed, you can create a new plugin project using this boilerplate by running the following command:

```bash
composer create-project syntatis/wp-starter-plugin -s dev
```

This command will create a new directory named `wp-starter-plugin` and download the boilerplate files into it. Once the download is complete, it will run a command to scope the plugin dependencies to prevent class name collisions with other plugins. You can find the scoped dependencies in the `dist-autoload` directory.

### With GitHub

You can also use this repository as a template to create a new repository with the same boilerplate files. Click the <kbd>Use this template</kbd> button at the top of this page to create a new repository with the same files. However, unlike the Composer method, which immediately pulls and set up the boilerplate, you will need to clone the repository and run the following commands to scope the plugin dependencies:

```bash
composer scope && composer install
```

For more information, see [Creating a repository from a template](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/creating-a-repository-from-a-template). 
