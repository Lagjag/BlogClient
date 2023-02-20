# BlogApi
## Proyecto realizado en:
 - [Symfony 6.2](https://symfony.com/releases/6.2)
 - [PHP 8.1](https://www.php.net/releases/8.1/en.php)

## Tecnologías usadas:
 - [symfony/maker-bundle](https://github.com/symfony/maker-bundle)
 - [symfony/orm-pack](https://symfony.com/components/ORM%20Pack)
 - [symfony/http-client](https://github.com/symfony/http-client)
 - [symfony/test-pack](https://github.com/symfony/test-pack)
 - [symfony/flex](https://github.com/symfony/flex)
 - [symfony/twig-bundle](https://github.com/symfony/twig-bundle)
 - [friendsofphp/php-cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

Estas son algunas de las tecnologías usadas para desarrollar el proyecto.

## Descripción del proyecto
Este proyecto es un cliente completamente desacoplado de una BBDD. Lo único que hace es consumir la api REST enlaza a dicho proyecto. Lleva esos json al front, hecho en twig y bootstrap (lo más secillo posible). Cuenta con un registro, login, logout, visualizar post, crear post, ver categorías, crear categorías.

## Endpoints de la api
 - /(GET) -> Llama a la api para devolver los post en orden cronológico
 - /post/new(POST) -> Manda a la api el json del formulario y devuelve correcto si se inserta bien
 - /post/{id}(GET) -> Manda el id del post a la api para su visualización
 - /category/categories(GET) -> Pide a la api la lista de categorías creadas
 - /category/new(POST) -> Manda el form de categoría a la api a la espera de la respuesta
 - /login(ANY) -> Sirve para renderizar el formulario de login por primera vez
 - /login_api(ANY) -> Sirve para mandar la información de login a la api o volver a renderizar el form si no ha salido bien
 - /logout(ANY) -> Sirve para salir de la sesión (se mata la sesión)
 - /register(ANY) -> Sirve para renderizar por primera vez el form de registro.
 - /register_api(ANY) -> Sirve para mandar la información a  la api o volver a renderizar el form en caso de respuesta errónea

## Preparando el proyecto
Al ser un proyecto de cliente que sólo consume de una api REST no requiere BBDD real; por lo que ese paso nos lo podemos saltar.

## Test Unitarios
Los test unitarios se han hecho a nivel del proyecto API debido que los datos de allí se tienen que devolver correctamente.

## PHP-CS-FIXER
Se ha utilizado para corregir el código php utilizado en el proyecto. Se han colocado 2 scrips a nivel de composer para hacerlo más manejable:
 - composer sniff (compara el código php con la plantilla de fixer)
 ```sh
composer sniff
```
 - composer format (formatea el código acorde a la plantilla fixer)
  ```sh
composer format
```

## Arrancando el proyecto
Para arrancar el poryecto se ha probado con lo mínimo indispensable. Para ello se ha utilizado el propio symfony cli.
```sh
symfony server:start --port=8081
```
Se podría poner cualquier puerto salvo el 8080 que es dónde correrá nuestra api para ser consumida

## Curiosidades del proyecto
 - Este proyecto contiene todas las plantillas y form necesarios.
 - Los forms de este proyecto carecen de entidades, ya que dichas entidades se encuentran en la api.
 - Todas las anotaciones de rutas están inline para que mientras se codifique se pueda hacer cualquier cambio.
 - Se han capturado las excepciones desde el propio framework, personalizando las plantillas.
 - Se ha creado un servicio para hacer las llamadas rápidas a la api llamado GenericRequest.
 - El username del usuario registrado coincide con el de autor.

## Mejoras
Las posibles mejoras pasan por los siguientes puntos:
 - Modo de edición de post filtrado por el autor (solo el mismo autor creador puede tocarlo)
 - Edición de usuarios (ahora mismo solamente los usuarios creados mediante fixtures tienen dirección)
 - Realización de un servicio de excepciones (falta de tiempo)

## Excepciones y errores
Estos está controlados desde el propio symfony. En modo dev o test sólo se verán aplicando la ruta /_error/{código de error}. Actualmente se tienen plantillas de error de 401,405 y 500 que están ubicadas en:
```sh
templates/bundles/TwigBundle/Exception/error{codigo}.html.twig
```