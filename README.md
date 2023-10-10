<p align="center">
  <a href="" rel="noopener"><b style="color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
</p>

<h3 align="center">SteamCopy</h3>

<div align="center">

  [![Status](https://img.shields.io/badge/status-active-success.svg)]() 
  [![GitHub Issues](https://img.shields.io/github/issues/safkom/SteamCopy.svg)](https://github.com/safkom/SteamCopy/issues)
  [![GitHub Pull Requests](https://img.shields.io/github/issues-pr/safkom/SteamCopy.svg)](https://github.com/safkom/SteamCopy/pulls)
  [![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)

</div>

---

<p align="center">Projekt vsebuje funkcije spletnega trgovca z igrami, prijavo, registracijo, dostop za administratorje, prijavo z Google računom in dodajanje ljudi kot prijatelje.
    <br> 
</p>

## 📝 Kazalo vsebine
- [O projektu](#o-projektu)
- [Začetek](#začetek)
- [Namestitev](#namestitev)
- [Zagon testov](#zagon-testov)
- [Uporaba](#uporaba)
- [Implementacija](#implementacija)
- [Naloge za izboljšavo](../TODO.md)
- [Sodelovanje](../CONTRIBUTING.md)
- [Avtorji](#avtorji)
- [Zahvale](#zahvale)

## 🧐 O projektu <a name = "o-projektu"></a>
Projekt, izdelan za šolsko nalogo. Večina kode je napisana v jeziku PHP.

## 🏁 Začetek <a name = "začetek"></a>
Navodila vam bodo pomagala, da dobite kopijo projekta, ki bo delovala na vašem lokalnem računalniku za razvoj in testiranje. Za informacije o namestitvi na živem sistemu glejte [namestitev](#namestitev).

### Predpogoji
Zahtevane komponente:
MySQL baza
Apache za prikaz strani



### Installing
Potrebna je samo kopija projekta. Lahko kot .zip datoteka, lahko pa kot .git projekt.

Za .zip file:

```
https://github.com/safkom/SteamCopy/archive/refs/heads/master.zip
```

in za .git projekt:

```
https://github.com/safkom/SteamCopy.git
```
Nato samo namestite lahko bazo, v vaš strežnik. Priložena .sql koda, vsebuje celotno strukturo. Treba je samo dodati Default profile picture. Za uporabnike, ki se registrirajo na novo.
```
https://github.com/safkom/SteamCopy.git
```

Nato samo prenesite datoteke za spletno stran v vaš strežnik.
Treba pa je še popraviti podatke za povezavo na strežnik!
To uredite v datoteki [connect.php](https://github.com/safkom/SteamCopy/blob/master/connect.php)

### CSS
CSS je razvrščen v 4 datoteke:

index.css - za vse strani na strani. Splošna struktura strani.
login.css - CSS za forms dele html strani.
navbar.css - za navbar na vrhu strani.
profile.css - za postavitev in izgled profilov na strani.

Vse datoteke tudi vsebujejo prilagoditve za mobilne naprave.

## 🎈 Usage <a name="usage"></a>
Se lahko uporabi kot spletna trgovina za igre in komuniciranje z prijatelji in podajanje mnenj za igre na strani.


## ⛏️ Built Using <a name = "built_using"></a>
- [MySQL](https://www.mysql.com) - Database
- [PHP](https://www.php.net) - Web Framework
- [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) - Filtering tool

## ✍️ Authors <a name = "authors"></a>
- [@safkom](https://github.com/safkom) - Delo in ideja projekta
- [@sm1ncH](https://github.com/sm1ncH) - Testiranje strani
- [@aljazorlicnik](https://github.com/aljazorlicnik) - Predlogi za css in testiranje

Tukaj lahko vidiš tudi vse [contributors](https://github.com/safkom/SteamCopy/contributors), ki so pomagali na projektu.
