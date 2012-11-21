<?php

class trustedshopsrich extends trustedshopsrich_parent
{
	public function getTrustedShopsRich($tsId, $type = 1)
	{
		
  $oShop = $this->getConfig()->getActiveShop();
	$sShopTitle = $oShop->oxshops__oxname->getRawValue();
		
	if ($xml = simplexml_load_file("https://www.trustedshops.com/bewertung/show_xml.php?tsid=".$tsId)) {
		$max = $xml->ratings->result[0];
		$average = $xml->ratings->result[1];
		$count = $xml->ratings["amount"];
		
		$htmlOutput = '<div class="hreview-aggregate"><div class="item"><span class="fn">' . $sShopTitle . '</span></div><span class="rating">Durchschnittliche Bewertung: <span class="average">' . $average . '</span> (von 5). Ermittelt aus  <span class="votes">' . $count . '</span> <a class="links_link" href="https://www.trustedshops.de/bewertung/info_' . $tsId . '.html">Bewertungen</a>. </span></div>';
		
		$htmlOutput2 = '<a href="http://www.trustedshops.de/shop-info/trusted-shops-kundenbewertungen/">Kundenbewertungen von Trusted Shops</a>:
		<span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
		    <span rel="v:rating"><span property="v:value">' . $average . '</span>
		    </span> / <span property="v:best">' . $max . '
		    </span> bei <span property="v:count">' . $count . '</span> <a href="https://www.trustedshops.de/bewertung/info_' . $tsId . '.html">Bewertungen</a>
		</span>';			
		
		} else {
			$htmlOutput = "No connection to Trusted Shops.";
		}
	
		switch($type) {
			case 1:
				$htmlOutput = $htmlOutput;
			break;
			case 2:
				$htmlOutput = $htmlOutput2;
			break;
			case 3:
				$htmlOutput = "<pre>" . print_r($xml, true) . "</pre>";
			break;
			default:
				$htmlOutput = $htmlOutput;
		}
			
	return $htmlOutput;
	}
}