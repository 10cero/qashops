"# qashops"

TEST 2.  (20 minutos)

El tiempo dedicado al primer test ha sido aproximadamente de 20 minutos. No estoy familiarizado con la forma de tratar la cach� Yii2 y he necesitado buscar la documentaci�n para no afectar negativamente en mis acciones.

Se han desarrollado dos funciones auxiliares que incorporan el concepto de cache, para liberar a la funci�n principal de condiciones que quedan excluida de su �mbito.


TEST 2. (3 horas y 15 minutos)

En principio la prueba no inclu�a gran complejidad, dado que se trataba de la descarga y tratamiento de datos.

A la hora de integrar la librer�a pdfparser, he tenido algunos problemas de requerimientos en mi sistema por versi�n de PHP.

Tras resolver el problema y resolver las dependencias he construido la clase BormeDownloader y su m�todo downloadBorme().

Se ha creado adicionalmente una clase denominada Borme donde se han incluido los m�todos de descarga, y transformaci�n del pdf a txt.

Se ha incluido adem�s una interfaz con el usuario desde la que se pueden solicitar la descarga de ficheros pdf, con una �rea de registro de notificaciones, una zona de ficheros procesados y otras con la lista de ficheros pdfs que no han podido ser procesados.

Se ha incluido una constante de sistema desde la que se puede controlar el n�mero de reintentos de descarga de un fichero a trav�s de su URL.


En este ejercici� se ha empleado aproximadamente 3 horas. Una hora practicamente se ha llevado la resoluci�n del problema de incompatibilidad de versi�n y configuraci�n de PHP.

En la inclusi�n del interfaz se ha empleado otra hora. Se han incluido llamadas ajax asincronas para permitir solicitar varias urls, sin necesidad de esperar a su procesamiento.





