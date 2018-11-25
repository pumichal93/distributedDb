## Inštalácia

Stiahnutie repozitára do nami zvoleného adresára.
```
git clone https://github.com/pumichal93/distributedDb
```
stiahnutie pomocných knižnic pomoocu composera
```
require thingengineer/mysqli-database-class:dev-master
```
Naimportovanie potrebných tabuliek do našej databázy.
```
mysql =u[USER] -h[HOST] -p < sql_dump.sql
```
Nastavenie databázových prihlasovacích údajov do súboru config.php

### Potrebné nastavenia pre uméžnenie distribuovaného spracovania dát

Do súboru nodes.php nastavíme do premennéj pola nodes. informáciach o uzloch, ktoré chceme využiť na distribuované spracovanie dát.
Príklad konfigurácie uzlov:
```php
$nodes = [
	'25.58.60.58' => [
		'user' => 'root',
		'password' => '',
		'database_name' => 'zaklad'
	]
];
```

## Konfigurácia umožnǔjúca vykonávanie prikazov do vzdialených serverov.

Pridanie práv vzdialeným uzlom na to, aby mohli v našej lokálnej databáze vykonávať potrebné príkazy.
Po prihlásenim sa do našej mysql ako root týmto príkazom umožníme prístup pre adresu, ktorú zadáme namiesto X.X.X.X.
```
GRANT ALL PRIVILEGES ON *.* TO 'root'@'X.X.X.X' IDENTIFIED BY PASSWORD '*root_user_password' WITH GRANT OPTION 
```
A máme v konfiguračnom súbori našej databázy nastavený parameter bind-adress  127.0.0.1, tak tento riadok zakomentujeme
```
#bind-adress  127.0.0.1
```
Týmto krokom neobmedzíme adresy, ktoré môžu vzkonávať nad našou lokálnou databázou príkazy.

## Odporúčané nastavenia

Pred vykonaním ríkazu sa skontroluje či je daný uzol dosiahnuteľný. Kvôli tomuto kroku sa pri situácií, že v danom momente nebude uzol dosiahnutý,
tak sa bude snažiť nadviazať spojenie a pre to je kvôli používateľskému zážitku odporúčané nastaviť parametre v mysql na dostatočné minimum.
```
[mysqld]
wait_timeout = 600
interactive_timeout = 600
```