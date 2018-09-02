# Coign - Twig Translation Extension

Esse repositório usa Twig & Illuminate/Translation para fazer a tradução de toda sua aplicação
fornecendo uma classe de extensão dee ramificação para analizar. Então a classe usa uma função auxiliar para o uso de modelos em ramificação
 A função de tradutor tenta chamar a função trans() ou translate() de um objeto  [Illuminate/translation.](https://github.com/illuminate/translation)
## Como instalar

#### Usando o [Composer](http://getcomposer.org/)

Abra o prompt de comando na pasta da aplicação e cole:
```
composer require coign/twig-translation
```
  

## Como Usar

### Slim

Vamos mostrar o exemplo que pode ser usado com twig, para instalar o twig segue o link [Twig View Repository](https://github.com/slimphp/Twig-View).

Recomendo chamar essas funções pelo Middleware.
Adicione as extenções do Illuminate/translation.
```php
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
```

Em seguida..
```php
$container = $app->getContainer();

$app->add(function (\Slim\Http\Request $request, $response, $next) use ($app,$container)
{
	   //pegar linguagem do navegador
    $lang = $request->getHeader('Accept-Language');
    
    //mostrar apenas os dois primeiros caractéries
    $language = substr($lang[0],0,2);
    
    //pegar as configurações (caminho) da renderização
    $settings = $container->get('settings')['renderer'];

    //invocar container view para adicionar a extenssão
    $view = $container->get('view');
    $view->addExtension(new \Dkesberg\Slim\Twig\Extension\TranslationExtension());

    //$settings['template_path_lang']  esse é o diretório da pasta de tradução
    //Aonde pode ser alterada em settings.php
    $translator = new Translator(new FileLoader(new Filesystem(), $settings['template_path_lang']), $language);
    
    //Idioma padrão do site
	   $translator->setFallback('en');
    
	   //Execultar tradução
	   $app->translator = $translator;

	   //Gravar a tradução do usuario em uma _SESSION para usar em formularios navegador etc..
	   $_SESSION['lang'] = $language;

    // executar um outro middleware com o atual route
    return $next($request, $response);
});
```
Temos o diretório de tradução na pasta: ```templates/lang``` aonde teremos os idiomas.
Lembre-se se que cada pasta é um idioma..
```
/templates
          /lang
               /pt
                  /home.php
                  /about.php
               /en
                  /home.php
                  /about.php
```


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
