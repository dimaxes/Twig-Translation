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
Aonde em cada arquivo de tradução por sua vez terá uma array com as traduções
/en/home.php
```php
return array(
'Welcome' => 'Hello'
); 
```
/pt/home.php
```php
return array(
'Welcome' => 'Olá'
); 
```

# Usando no template
Para usar no seu template, você téria que invocar primeiro o nome do arquivo de diretório no nosso caso ```home.php``` e depois a chave do array que desejamos chamar..
invocariamos da seguinte forma ```home.welcome
```
{{ translate('home.Welcome') }}
```
Você pode usar com abreviação..
```
{{ _('home.welcome') }}
```
Ou como no exemplo abaixo, temos uma array para dar as boas vindas e mostrar o nome do usuário..
```/pt/messages.php```
```
return array(
	'hello' => 'Olá :name!'
);
```
Nesse exemplo acima , teriamos que usar a invocação no template assim..
```
{{ trans('messages.hello', {'name': 'João Doe'}) }}
```

Adicione novas pastas para diferentes idiomas.
