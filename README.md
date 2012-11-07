trustedshopsrich
================

Test (meine Shop URL eingeben):
http://www.google.de/webmasters/tools/richsnippets

Im Template, also da, wo die Bewertungen ausgegeben werden sollen, muss folgendes eingetragen werden:

PHP-Code:
[{if $oViewConf->getTsId()}]
    [{$oViewConf->getTrustedShopsRich($oViewConf->getTsId())}]
[{/if}] 
                  
Es wird noch ein zweiter Konfigurationsparameter mit übernommen, 
so dass verschiedene Formate ausgegeben werden können. Also dann so:

[{$oViewConf->getTrustedShopsRich($oViewConf->getTsId(), 2)}]

Kein Parameter zeigt an:

Dein Shop Name
Durchschnittliche Bewertung: 4.89 (von 5). Ermittelt aus 151 Bewertungen.

Paramter 2:

Kundenbewertungen von Trusted Shops: 4.89 / 5.00 bei 151 Bewertungen

Paramter 3: Debugausgabe.

Die Verwendung des Moduls geschieht auf eigene Gefahr. 
Es wird keinerlei Support angeboten. Eine Demo sieht man in meinem Shop ganz unten.