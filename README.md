This bundle allows you to integrate Vite into your Symfony application by using [WebpackEncoreBundle](https://symfony.com/doc/current/frontend.html) and [ViteFait](https://www.npmjs.com/package/vite-fait)

Installation
============

Make sure [Node](https://nodejs.org/en/download/) and a package manager ([Yarn](https://yarnpkg.com/getting-started/install)) are installed.

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bechir/vite-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Bechir\ViteBundle\BechirViteBundle::class => ['dev' => true],
];
```
Add the bundle configuration in `config/packages/bechir_vite.yaml`:
```yaml
bechir_vite:
  output_path: '%kernel.project_dir%/public/dist'
```

### Step 3: Install ViteFait
```sh
# using yarn
yarn add --dev vite-fait
# using npm
npm install vite-fait --save-dev
```

Usage
=====
Create `vite.config.js` in the root folder and add the following code inside the file:

```javascript
const ViteFait = require('vite-fait');

ViteFait
  .setRoot('assets')
  .setOutputPath('../public/dist')
  .addEntry('app', './assets/app.js')
  .addEntry('admin', './assets/admin/app.js');

module.exports = ViteFait.getViteConfig()

```
Add vite-fait scripts in your `package.json`
```json
{
  "scripts": {
    "build": "vite-fait build",
    "dev": "vite-fait dev",
  }
}
```

Then run your first build with `yarn build` or `npm run build`
It generate `entrypoints.json` file in `public/dist`:
```json
{
  "entrypoints": {
    "app": {
      "js": [
        "/dist/app.7f38ab96.js"
      ],
      "css": [
        "/dist/app.c385b6b3.css"
      ]
    },
    "admin": {
      "js": [
        "/dist/admin.a88436ae.js"
      ],
      "css": [
        "/dist/admin.0e68df5b.css"
      ]
    }
  }
}
```

Add the twig functions in `templates/base.html.twig`:

```html
<html>
<head>
    {{ vite_entry_link_tags('app') }}
</head>
<body>
    <!-- html code -->
    {{ vite_entry_script_tags('app') }}
</body>
</html>
```

[Read the vite docs](https://vitejs.dev) for more information

## Using Plugins
> Read [how to use vite plugins](https://vitejs.dev/guide/using-plugins.html) first before reading this section

Put your plugins inside the `usePlugins` method:
```js

const fooPlugin = function() {
  return {
    name: 'vite-plugin-foo',
    configureServer() {
      console.log('foo');
    }
  }
}

ViteFait
  .usePlugins(fooPlugin()) // use array for multiple plugins: [fooPlugin(), barPlugin()]
```

TODO
=====
- [ ] Add tests
- [ ] React support
- [ ] Vue support
- [ ] Documentation
