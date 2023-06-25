# Receptes_weboldal
## Feladat leírása
A weboldal célja, hogy a felhasználók egy receptes gyűjteményből tudjanak kiválasztani receptet, majd összeegyeztetni a hozzávalókat az otthonukban megtalálható összetevőkkel. Amennyiben valamilyen hiánycikkük van létre tudnak hozni egy bevásárló listát, amin a kiválasztott receptehez szükséges további alapanyagokat találják meg. Továbbá 3 felhasználói jogkör van megkülönböztetve. Az egyszerű felhasználókon túl a "séfek" új recepteket tudnak hozzáírni a recept gyűjteményhez, a "koordinátorok" a receptek esetleges törléséről tudnak gondoskodni.

## Megvalósitandó funkciók
* Receptek kezelése
  * új recept írása (az erre jogosult felhasználóknak)
  * receptek módosítása és törlése (koordinátorok számára)
  * receptek kiválasztása, majd amennyiben szükséges bevásárló lista készítése
  * receptek listázása
  
* Hozzávalók
  * adatbázisban tárolt hozzávalók bővítése (az erre jogosult felhasználóknak)
  * adatbázisban tárolt hozzávalók törlése (az erre jogosult felhasználóknak)
  * adatbázisban tárolt hozzávalók listázása (az erre jogosult felhasználóknak)
  
* Felhasználók kezelése
  * új felhasználók létrehozása
  * meglévő felhasználók adatainak módosítása
  * felhasználók törlése (koordinátorok számára)
  * felhasználók státuszát jelszóval állíthatjuk
  * felhasználók listázása
  
* Otthon kezelése
  * otthon elérhető hozzávalók bővítése a hozzávalók lista alapján
  * otthon elérhető hozzávalók törlése 
  * otthon elérhető hozzávalók lekérdezése (kilistázása)
  * recept ajánlása, amenniyben a felhasználó ezzel a lehetőséggel élni szeretne
  
* Recepteskönyv
  * lehet új recepteket felvenni, amennyiben az megtalálható a receptek között 
  * lehet recepteket törölni
  
* Összetevők listája
  * modosítható (törölhető/bővíthető), amennyiben egy receptet szeretnénk modosítani (ez a funkció is csak az arra jogosult felhasználók számára elérhető)
  * tárolja a hozzávalók mennyiségeit egy adott receptben
  
## Főoldalról élérhető oldalak
 * Bejelentkezés 
 * Recepteskönyv
 * Otthon
 * Státusz beállítás 
 
## Adatbázis séma
![image](https://user-images.githubusercontent.com/104391381/236557211-9bbf0293-9146-4daa-af9a-02ad5b507440.png)
