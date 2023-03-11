<?php namespace App\Classes;

use App\Models\Setting;
use DOMDocument;
use Mail;

class SiteMapGenerator
{

    public function  generate($url_route)
    {
        $xmldoc = new DomDocument('1.0');
        $url = $xmldoc->createElement('url');

//         $date = new DateTime(date('Y-m-d H:i:s'));
        $date = date('Y-m-d  H:i:s');
        $priority_value = 0.8;


        if ($xml = file_get_contents(public_path('sitemap.xml'))) {
            $xmldoc->loadXML($xml, LIBXML_NOBLANKS);

            // find the headercontent tag
            $root = $xmldoc->getElementsByTagName('urlset')->item(0);

            $url = $xmldoc->createElement('url');

            // add the product tag before the first element in the <headercontent> tag
            $root->insertBefore($url, $root->firstChild);

            // create other elements and add it to the <product> tag.
            $locElement = $xmldoc->createElement('loc');
            $url->appendChild($locElement);
            $url_route = $xmldoc->createTextNode($url_route);
            $locElement->appendChild($url_route);

            $lastmodElement = $xmldoc->createElement('lastmod');
            $url->appendChild($lastmodElement);
            $lastmodText = $xmldoc->createTextNode($date);
            $lastmodElement->appendChild($lastmodText);


            $changefreqElement = $xmldoc->createElement('changefreq');
            $url->appendChild($changefreqElement);
            $changefreqText = $xmldoc->createTextNode($date);
            $changefreqElement->appendChild($changefreqText);

            $priorityElement = $xmldoc->createElement('priority');
            $url->appendChild($priorityElement);
            $priorityText = $xmldoc->createTextNode($priority_value);
            $priorityElement->appendChild($priorityText);

            $xmldoc->save(public_path('sitemap.xml'));

        }

    }


    public function  update($url_route)
    {
        $xmldoc = new DomDocument('1.0');
//         $date = new DateTime(date('Y-m-d H:i:s'));
        $date = date('Y-m-d  H:i:s');
        $priority_value = 0.8;


        if ($xml = file_get_contents(public_path('sitemap.xml'))) {
            $xmldoc->loadXML($xml, LIBXML_NOBLANKS);

        }

    }




}
