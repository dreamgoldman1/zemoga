# Drupal 8 - Zemoga test

# Prueba Zemoga - Gilberto Orozco Linero

## Acceso al repositorio

### GIT
```
URL: https://github.com/dreamgoldman1/zemoga.git 
```

## Generalidades del Módulo

### Versión de Drupal
```
El desarrollo del módulo se hizo sobre la versión de Drupal 8.5.4, la más reciente a la fecha de la prueba, sobre Apache y MySQL.
```

### Estructura de archivos
	
### Instalación del módulo
```
El módulo se debe alojar en la carpeta modules del proyecto, preferiblemente en la carpeta custom, al momento de activarlo se crea una URL para poder acceder a la funcionalidad del módulo, /zemoga/step-one, recuerde que se debe limpiar la caché de Drupal una vez se haga la activación del módulo.
```

## Explicación de la funcionalidad
```
Se crea un formulario abstracto abstract class FormWizard extends FormBase que funciona como el formulario padre de cada uno de los steps del wizard, cada uno de los formularios steps son extensiones de esta clase abstracta.

Los formularios class StepOneForm extends FormWizard, class StepTwoForm extends FormWizard y class StepThreeForm extends FormWizard tienen la definición de cada formulario y la validación de ellos mismos, una vez se hace click en el botón siguiente se encarga de guardar los datos ingresados en una sesión de usuario de Drupal para conservarlos durante su flujo, estos datos de sesión se limpian una vez se finaliza la transacción.

Cuando se hace el click en el submit final de los pasos, se hace un llamado de la clase padre saveData(), en este se hace el llamado de tres funciones: setZemogaUser(), createUser() y deleteStore(), que se encarga de guardar los datos en una tabla custom del módulo, la creación de un usuario en el sistema de Drupal y finalmente eliminar los datos de la sesión respectivamente.
```
## Base de datos
```
En el módulo está definido un schema que crea una tabla custom para el módulo llamada zemoga_user. El campo id guarda el consecutivo propio de la tabla, y en el campo id_user se guarda el id de usuario generado del registro de usuario de Drupal.
```