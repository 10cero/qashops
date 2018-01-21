"# qashops"

TEST 2.  (20 minutos)

El tiempo dedicado al primer test ha sido aproximadamente de 20 minutos. No estoy familiarizado con la forma de tratar la caché Yii2 y he necesitado buscar la documentación para no afectar negativamente en mis acciones.

Se han desarrollado dos funciones auxiliares que incorporan el concepto de cache, para liberar a la función principal de condiciones que quedan excluida de su ámbito.


TEST 2. (3 horas y 15 minutos)

En principio la prueba no incluía gran complejidad, dado que se trataba de la descarga y tratamiento de datos.

A la hora de integrar la librería pdfparser, he tenido algunos problemas de requerimientos en mi sistema por versión de PHP.

Tras resolver el problema y resolver las dependencias he construido la clase BormeDownloader y su método downloadBorme().

Se ha creado adicionalmente una clase denominada Borme donde se han incluido los métodos de descarga, y transformación del pdf a txt.

Se ha incluido además una interfaz con el usuario desde la que se pueden solicitar la descarga de ficheros pdf, con una área de registro de notificaciones, una zona de ficheros procesados y otras con la lista de ficheros pdfs que no han podido ser procesados.

Se ha incluido una constante de sistema desde la que se puede controlar el número de reintentos de descarga de un fichero a través de su URL.


En este ejercició se ha empleado aproximadamente 3 horas. Una hora practicamente se ha llevado la resolución del problema de incompatibilidad de versión y configuración de PHP.

En la inclusión del interfaz se ha empleado otra hora. Se han incluido llamadas ajax asincronas para permitir solicitar varias urls, sin necesidad de esperar a su procesamiento.





