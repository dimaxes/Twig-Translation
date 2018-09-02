# Coign - Twig Translation Extension

Esse repositório usa Twig & Illuminate/Translation para fazer a tradução de toda sua aplicação
fornecendo uma classe de extensão dee ramificação para analizar. Então a classe usa uma função auxiliar para o uso de modelos em ramificação
 A função de tradutor tenta chamar a função trans() ou translate() de um objeto Illuminate\Translation\
## Como instalar

#### Usando o [Composer](http://getcomposer.org/)

Abra o prompt de comando na pasta da aplicação e cole:
```
composer require coign/twig-translation-extension
```
    
```json
{
    "require": {
        "dkesberg/slim-twig-translation-extension": "dev-master"
    }
}
```

Then run the following composer command:

```bash
$ php composer.phar install
```

## How to use

### Slim

Set up your twig views as described in the [SlimViews Repository](https://github.com/codeguy/Slim-Views).
Add the extension to your parser extensions.

```php
$view->parserExtensions = array(
    new \Dkesberg\Slim\Twig\Extension\TranslationExtension(),
);
```

### Twig template

In your twig template you would write:

```
  {{ translate('male') }}
```
  
You can also use the shorthand:

```
  {{ _('male') }}
```

### Adding Illuminate/Translation/Translator to slim

Simple injection:

```php
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

$translator = new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), 'en');
$translator->setFallback('en');
$app->translator = $translator;
```

Using slim hooks:

```php
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

// detect language and set translator
$app->hook('slim.before', function () use ($app) {
  $env = $app->environment();
  
  $locale = Locale::acceptFromHttp($env['HTTP_ACCEPT_LANGUAGE']);
  $locale = substr($locale,0,2);

  // Set translator instance
  $translator = new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), 'en');
  $translator->setFallback('en');
  $app->translator = $translator;
});
```
