<?php

namespace XmlMiddleware;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class XmlRequestServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Request::macro('isXml', function () {
            return strtolower($this->getContentType()) === 'xml';
        });

        Request::macro('xml', function ($assoc = true) {
            if (!$this->isXml()) {
                return [];
            }
            // Returns the xml input from a request
            return XmlRequestServiceProvider::parse($this->getContent()); 
        });
    }

    public static function parse($xml)
    {
        return self::normalize(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS));
    }

    protected static function normalize($obj)
    {
        $result = null;

        if (is_object($obj)) {
            $obj = (array) $obj;
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $res = self::normalize($value);
                if (($key === '@attributes') && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $obj;
        }

        return $result;
    }
}
