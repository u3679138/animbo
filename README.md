Animbo V1.0.0 (Ramen)
====================
Instalación
------------
*Debido a que aún no logramos compatibilizar al 100% el php, se debe correr en php 5.6, no superior. Pronto sanaremos esto.

*Por cuestiones de seguridad, hemos desactivado la visualización de errores, es decir que php no mostrará al publico los erores.

*Para ver estos errores, borrar la linea "error_reporting(0);" al comienzo del archivo tablon.php

Cosas a tener en cuenta
-----------------------
Se debe meter los archivos de animbo en una carpeta, y a esa carpeta ponerle en nombre del tablon que deseas.

Por ej. si se meten los archivos en una carpeta llamada "ramen", la URL de tu tablon seria:

http://example.org/ramen

Luego tan solo se debe subir a tu web. Puedes usar un software FTP como Filezilla para esto.

Una vez subido, se debe acceder a:

http://example.org/ramen/tablon.php

Este archivo generará el tablon. Luego tan solo entran a http://example.org/ramen

Para cambiar el nombre del tablon, se debe acceder al archivo tablon.php con un editor de texto web como Brackets, o Notepad++

Se debe acceder a la linea 12 y modificar el valor "TITLE".

Para acceder al panel de administración, se debe acceder mediante:

http://example.org/tutablon/tablon.php?mode=admin

Para cambiar la contraseña de administrador, se debe acceder a tablon.php mediante un editor de texto web y modificar en la linea 19, el valor
"ADMIN_PASS".

Para editar los banners del tablon, se debe ir a la carpeta "editables".

ATENCIÓN:

Se debe guardar con los mismos nombres, FORMATOS, Y GUARDAR EN LA CARPETA DE LOS BANNERS!!!

Para usar un solo banner estatico, en vez de varios rotativos, se debe cambiar el nombre de "banner_null.php" por "banner.php"
en la carpeta "genesis/tablon".

Los tags del < head > deben ser cambiados por tus valores.

Errores encontrados:
-------------------
Hay un problema con el ereg del php.

Los comentarios no generan saltos de linea.


