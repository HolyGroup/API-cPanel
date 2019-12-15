# API cPanel (Classe)
- Pour commencer, initialisez la classe PHP
```php
require_once("path/to/cPanel.class.php");
$cPanel = new cPanel("cpanel.votredomaine.fr", "USERNAME", "PASSWORD");
```
- Pour créer un compte cPanel faite ceci
```php
$cPanel->create("VOTRE_PLAN");
```
Un problème avec un compte ou le problème est maintenant régler, la suspension et unsuspension sont là
```php
$cPanel->unsuspend("WEB1234");
$cPanel->suspend("WEB1234");
```
- Besoins de supprimer un compte, Super simple, faite ça
```php
$cPanel->delete("WEB1234");
```
- Pour réinisialiser un mot de passe, rien de plus simple
```php
$cPanel->resetPassword("WEB1234", "UN_MOT_DE_PASSE_SECURISE");
```
- Pour sauvegarder un site, basique (Attention: la sauvegarde va dans **/public_ftp/**)
```php
$cPanel->backup("WEB1234", "UN_MOT_DE_PASSE_SECURISE","WEB1234.votredomaine.fr");
```
> Pour toute informations complémentaire, la docs cPanel | WHM est diponible à tous : https://documentation.cpanel.net/

Classe faite par MartinDev <martin@martindev.fr>, Merci de garder les crédits du haut et vous pouvez modifier le code à votre guise.

2019 Martindev.fr
