<?php

class Web {

    const WEB_ERRNO = 0x1;
    const WEB_STATUS = 0x2;
    const WEB_CONTENT = 0x4;

    public static function info($url, $element = null, $flags = null) {
        if ($flags === null)
            $flags = self::WEB_ERRNO | self::WEB_STATUS | self::WEB_CONTENT;

        $result = array();

        $handle = curl_init($url);
   
        // stawiamy timeout na 5 sekund
        curl_setopt($handle, CURLOPT_TIMEOUT, 5);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($handle, CURLOPT_MAXREDIRS, 20);  
        curl_setopt($handle, CURLOPT_USERAGENT, "Web Notifier 0.1a");          

        if ($flags & self::WEB_CONTENT) 
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);

        $error = curl_errno($handle);
        if ($flags & self::WEB_ERRNO)
            $result['errno'] = $error;

        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($flags & self::WEB_STATUS)
            $result['status'] = $status;

        if ($status === 200 && $flags & self::WEB_CONTENT) {
            if (strlen($element) > 0) {
                //na wypadek bledow
                libxml_use_internal_errors(true);
                
                $dom = new DOMDocument();
                $dom->loadHTML($response);

                $xpath = new DOMXpath($dom);
                $elements = $xpath->query($element);
                $content = '';
                $separator = '';

                foreach ($elements as $element) {
                    $domTmp = new DOMDocument();
                    $domTmp->appendChild($domTmp->importNode($element->cloneNode(TRUE), TRUE));
                    $content .= $domTmp->saveHTML().$separator;
                }
                $result['content'] = $content;
            } else
                $result['content'] = $response;
        }
        curl_close($handle);
        return $result;
    }

    public static function hash($content) {
        return md5($content);
    }

}