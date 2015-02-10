<?php

class trustedshopsrich extends trustedshopsrich_parent
{
	public function getTrustedShopsRich($tsId, $type = 1) {

		$oShop = $this->getConfig()->getActiveShop();
		$sShopTitle = $oShop->oxshops__oxname->getRawValue();

		$cacheFile = getShopBasePath().'tmp/trustedshops.cache';

		if(file_exists($cacheFile)) {
			$now = time();
			$then = filemtime($cacheFile);
			if($now-$then > 60*60) {
				unlink($cacheFile);
			} else {
				$xml = simplexml_load_string(file_get_contents($cacheFile));
			}
		}

		$sURL = "http://api.trustedshops.com/rest/public/v2/shops/".$tsId."/quality/reviews.json";

		if(!is_object($xml)) {
			if(ini_get('allow_url_fopen')) {
				file_put_contents($cacheFile, file_get_contents($sURL));
			} else {
				$rCurl = curl_init();
				curl_setopt($rCurl, CURLOPT_URL, $sURL);
				curl_setopt($rCurl, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
				$sData = curl_exec($rCurl);

				if(!$sData) {
					die('Error: "' . curl_error($rCurl) . '" - Code: ' . curl_errno($rCurl));
				} else {
					file_put_contents($cacheFile, $sData);
				}
				curl_close($rCurl);
			}
		}

		$aJSON = json_decode(file_get_contents($cacheFile), true);

		if (is_array($aJSON)) {
			$fMax = "5.00";
			$fAverage = (float)$aJSON['response']['data']['shop']['qualityIndicators']['reviewIndicator']['overallMark'];
			$iCount = (int)$aJSON['response']['data']['shop']['qualityIndicators']['reviewIndicator']['activeReviewCount'];

			$htmlOutput = '
			<div class="trustedshops">
				<div class="hreview-aggregate"><div class="item"><span class="fn">' . $sShopTitle . '</span></div><span class="rating">Durchschnittliche Bewertung: <span class="average">' . $fAverage . '</span> (von <span class="best">' . $fMax . '</span>). Ermittelt aus  <span class="votes">' . $iCount . '</span> Bewertungen. </span></div>
			</div>';

			$htmlOutput2 = '
			<div class="trustedshops">Kundenbewertungen von ' . $sShopTitle . '<br>
				<span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
					Durchschnittliche Bewertung: <span rel="v:rating"><span property="v:value">' . $fAverage . '</span></span> / <span property="v:best">' . $fMax . '</span>
					Ermittelt aus <span property="v:count">' . $iCount . '</span> <a href="https://www.trustedshops.de/bewertung/info_' . $tsId . '.html" rel="nofollow">Bewertungen</a>.
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