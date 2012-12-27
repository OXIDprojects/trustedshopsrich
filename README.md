trustedshopsrich
================

renders the xml output of trusted shops as an rich snippet for google to show the rating stars
within the google search results

Installation: 

1. Copy complete trustedshopsrich folder into modules folder of your shop
2. Activate module "Trustes Shops Rich Snippet" in OXID admin area
3. Be Happy :)
	
Using in Template: 

	[{if $oViewConf->getTsId()}]
    	    [{$oViewConf->getTrustedShopsRich($oViewConf->getTsId())}]
 	[{/if}]

Parameters for using different output formats:

	[{$oViewConf->getTrustedShopsRich($oViewConf->getTsId(), 2)}]


	no parameter: Dein Shop Name
	Durchschnittliche Bewertung: 4.89 (von 5). Ermittelt aus 151 Bewertungen.
	2: Kundenbewertungen von Trusted Shops: 4.89 / 5.00 bei 151 Bewertungen
	3: debug xml output
	
Changelog: 

	v1.0.3: Initial;
	v1.0.4: Added Caching / Thanks to Dave Holloway;
	v1.0.5: Added DIV's arount module output

Licensing: 

	Dreamride / Christian Bernhard
	Author: Christian Bernhard

	Copyright 2012 

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.