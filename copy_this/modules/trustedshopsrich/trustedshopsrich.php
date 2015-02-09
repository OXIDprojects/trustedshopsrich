<?php

class trustedshopsrich extends trustedshopsrich_parent
{
	public function getTrustedShopsRich($tsId, $type = 1)
	{

	$oShop = $this->getConfig()->getActiveShop();
  $sShopTitle = $oShop->oxshops__oxname->getRawValue();

  $cacheFile = getShopBasePath().'tmp/trustedshops.cache';

  if (file_exists($cacheFile)) {
    $now = time();
    $then = filemtime($cacheFile);
    if ($now-$then > 60*60) {
      unlink($cacheFile);
    } else {
      $xml = simplexml_load_string(file_get_contents($cacheFile));
    }
  }

  if(!is_object($xml)) {
    if(ini_get('allow_url_fopen') && $xml = simplexml_load_file("https://www.trustedshops.com/bewertung/show_xml.php?tsid=".$tsId)) {
        file_put_contents($cacheFile, $xml->asXML());
    } else {
        $rCurl = curl_init();
        curl_setopt($rCurl, CURLOPT_URL, 'https://www.trustedshops.com/bewertung/show_xml.php?tsid='.$tsId);
        curl_setopt($rCurl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
        $xml = simplexml_load_string(curl_exec($rCurl));

        if(!$xml) {
            die('Error: "' . curl_error($rCurl) . '" - Code: ' . curl_errno($rCurl));
        } else {
            file_put_contents($cacheFile, $xml->asXML());
        }
        curl_close($rCurl);
    }
  }

	if (is_object($xml)) {

		$max = $xml->ratings->result[0];
		$average = $xml->ratings->result[1];
		$count = $xml->ratings["amount"];

		$htmlOutput = '
    <div class="trustedshops">
      <div class="hreview-aggregate"><div class="item"><span class="fn">' . $sShopTitle . '</span></div><span class="rating">Durchschnittliche Bewertung: <span class="average">' . $average . '</span> (von 5). Ermittelt aus  <span class="votes">' . $count . '</span> Bewertungen. </span></div>
    </div>';

		$htmlOutput2 = '
    <div class="trustedshops">Kundenbewertungen von ' . $sShopTitle . '<br>
  		<span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
  		    Durchschnittliche Bewertung: <span rel="v:rating"><span property="v:value">' . $average . '</span>
  		    </span> / <span property="v:best">' . $max . '
  		    </span> Ermittelt aus <span property="v:count">' . $count . '</span> <a href="https://www.trustedshops.de/bewertung/info_' . $tsId . '.html" rel="nofollow">Bewertungen</a>.
  		</span>
    </div>';

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