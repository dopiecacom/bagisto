<?php

namespace ACME\Contact\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TestListener
{
    public $token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2NzcwMTM0MjIsInVzZXJfbmFtZSI6IjEwNTkwMzYyNSIsImp0aSI6IjZiMTIxNmM0LWNkOTYtNDY0ZS05OWVmLWMzZTQ2MmUwZGIxNCIsImNsaWVudF9pZCI6IjNjNzE1NzBhYjE5OTQ3NTNhYWZlMTdlODY5NTFjZTMzIiwic2NvcGUiOlsiYWxsZWdybzphcGk6b3JkZXJzOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOnNldHRpbmdzOndyaXRlIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6d3JpdGUiLCJhbGxlZ3JvOmFwaTpwcm9maWxlOnJlYWQiLCJhbGxlZ3JvOmFwaTpiaWxsaW5nOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOnNldHRpbmdzOnJlYWQiLCJhbGxlZ3JvOmFwaTpwYXltZW50czpyZWFkIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6cmVhZCJdLCJhbGxlZ3JvX2FwaSI6dHJ1ZX0.bHlaAkC2oIl79qfkET68b5TzEk7OCMqao-0uR7tpg1KfNHYd_vbUz4CfiBsax-1cXXmFK9XNVBa0kzMF8aFRwnuWudNx9WRyKrmdR0nTGIC9WQE-1qAY6l51vHMUCQcbXOgo3QAUd5AJiffzYhADyELeB0TqF_ljRs2t5m0df-DP3uZtXoV1wXkbnpnZXh_GsAWv9wwWV5zXDTIxp-DRmedNK-KF1lOrL30WwBdQ2QPp4pOn4GPkfzJBWSdNDry-X7XmqLlIjpUfLsEvS_4zf6L2AsfG2Vcwst8gCrgeYi8FizvhI5a4qen_C14yQVFlGjXJRt9PbeD_8UhYBeElQ_rLZUZRaldc4HB7gsy7EkiFcxoRM_Pc5xwe_6iVTtSgUv6sztltVFOUp8mdtB7R1eTREp7hWtYCESgoZVkGKlBxyiG2IXHn2QhivagbxEzwMdONXW6drtKYlX91ubk-s3uJoENsT0QHURT8Ok1WvRlbnb3BP6T0G4jnfJGHaqV2ydrwXEbxsuAoUFz1VqIDaf5ES-Q5AydkbHKGkwgN8YhFdCD8dLddlLnormvR5hFkCNQORj9XhK4I39YfdndpxQb7bIrY5awIRPY64fUbHpTlcO3BxeyVxK4geKVMuzjBL5Cv7ZDHqEaxNMNexgdL8A9tP1Cq-EYV9SJeVyPFphU';
    public $refreshToken = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiIxMDU5MDM2MjUiLCJzY29wZSI6WyJhbGxlZ3JvOmFwaTpvcmRlcnM6cmVhZCIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6d3JpdGUiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczp3cml0ZSIsImFsbGVncm86YXBpOnByb2ZpbGU6cmVhZCIsImFsbGVncm86YXBpOmJpbGxpbmc6cmVhZCIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6cmVhZCIsImFsbGVncm86YXBpOnBheW1lbnRzOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczpyZWFkIl0sImFsbGVncm9fYXBpIjp0cnVlLCJhdGkiOiI2YjEyMTZjNC1jZDk2LTQ2NGUtOTllZi1jM2U0NjJlMGRiMTQiLCJleHAiOjE2ODQ3NDYyMjIsImp0aSI6ImIzOGE4Mzg5LTI3ZDMtNGQwZS05YjlmLTBkMWZmYzAyMDQ5ZSIsImNsaWVudF9pZCI6IjNjNzE1NzBhYjE5OTQ3NTNhYWZlMTdlODY5NTFjZTMzIn0.ryzPqP_fS2d8t29DgW41-Q7JWokKwStzOypoIi4yuysPZeQy2rKHjsGbaqlnaCCSfuAU53WZWtcqSNGQuz3LQCbgqyDB70ZyPyNZWgBMVQ-FDEFlz-l05VE6UvkX4lnGDE0208sBnajWlhDmAwUvQPMHa_RZYet4vZY3SdSFfcHOdX1bBOI8Dpif3GdQHGQVjHkdFoo4OpfoRQCGg5kyY_SNoFpEexEdf6ib9EDTttyVfWrOKqobhF-m1YojiuK1nc6OBXk1eivZDolOYDx8s7m05d-9ukVrWVlsqYwFgkFfWVsZq84Pif3uhXrPR07fNG7L42aGZAx8qghncV45w75PTdcyoEsS-5ZSEXjQoogfbFC3yralvnCXEl7OI46gJf7PudUfeYHqq6PPlkrZsDHmHzaMzFridkiwV2JcvMrufdHtAtMsVf5aME4mdoxhdAdXDcKr8Anyivgv4Jy7utPElKdeFJvG_JwycouPKhq58LlnPg6WtbSQisJ4ExjMKMlbXwMEPc630AAGvmjeD2WrLgW3VkeGNsoNxBuxe7ki2Gx_BI87ugT8a7ufur5ItwN7M-MhCIzaHQquYvrlvau9h7GYrZ3UZf-lnBtJggDJXwSUXTtn3i1nDlW9PMo2rHXUHCwLitxuAue_3bFsDiWS-UbUUHKgJjwllq0NwOo';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        define('CLIENT_ID', '3c71570ab1994753aafe17e86951ce33');
        define('CLIENT_SECRET', 'JBOlbnJqOVEy5LVn2Lico2oueE0kyA2NCcFmebLp1kVsUFsYxQNefFNdtmsostpA');

        define('REDIRECT_URI', 'http://localhost:8080');
        define('AUTH_URL', 'https://allegro.pl.allegrosandbox.pl/auth/oauth/authorize');
        define('TOKEN_URL', 'https://allegro.pl.allegrosandbox.pl/auth/oauth/token');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $productId = $event->sku;
        $this->createOffer($this->token, $productId);
    }


    // Mess below


    protected function getAuthorizationCode() {
        $authorization_redirect_url = AUTH_URL . "?response_type=code&client_id="
            . CLIENT_ID . "&redirect_uri=" . REDIRECT_URI;
        ?>
        <html>
        <body>
        <a href="<?php echo $authorization_redirect_url; ?>">Zaloguj do Allegro</a>
        </body>
        </html>
        <?php
    }

    public function getCurlAccess($headers, $content) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => TOKEN_URL,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ));
        return $ch;
    }

    public function getCurl($headers, $url, $content = null) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true
        ));
        if ($content !== null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        return $ch;
    }


    public function getAccessToken($authorization_code) {
        $authorization = base64_encode(CLIENT_ID.':'.CLIENT_SECRET);
        $authorization_code = urlencode($authorization_code);
        $headers = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");
        $content = "grant_type=authorization_code&code=${authorization_code}&redirect_uri=" . REDIRECT_URI;
        $ch = $this->getCurlAccess($headers, $content);
        $tokenResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($tokenResult === false || $resultCode !== 200) {
            exit ("Something went wrong $resultCode $tokenResult");
        }
        return json_decode($tokenResult);
    }


    public function main(){
        if (1>0) {
            $access_token = $this->getAccessToken('oCRvHG1yo3q3MBRWszQoF0cMhPj5mXFE');
            var_dump($access_token);
        } else {
            $this->getAuthorizationCode();
        }
    }

    function createOffer($token, $productId)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json", "Content-Type: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/product-offers";

        $content = array(
            "name" => "Ajfon",
            "productSet" => [
                [
                    "product" => [
                        "id" => "$productId"
                    ]
                ]
            ],
            "sellingMode" => [
                "price" => [
                    "amount" => 1000,
                    "currency" => "PLN"
                ]
            ],
            "location" => [
                "city" => "Warszawa",
                "countryCode" => "PL",
                "postCode" => "00-121",
                "province" => "WIELKOPOLSKIE"
            ],
            "stock" => [
                "available" => 10
            ],
        );

        $ch = $this->getCurl($headers, $url, json_encode($content));
        $result = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        var_dump($result);
    }

    public function updateOffer($token, $offerId)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json", "Content-Type: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/product-offers/$offerId";

        $content = '{"sellingMode": {"price": {"amount": "220.85","currency": "PLN"}}}';

        $ch = $this->getCurl($headers, $url, $content);
        $result = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        var_dump($result);
    }

    public function getProducts($token)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/offers";
        $ch = $this->getCurl($headers, $url);
        $mainCategoriesResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($mainCategoriesResult === false || $resultCode !== 200) {
            var_dump($mainCategoriesResult);
            exit ("Something went wrong");
        }
        $categoriesList = json_decode($mainCategoriesResult);
        return $categoriesList;
    }

    public function getMainCategories($token) {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/categories";
        $ch = $this->getCurl($headers, $url);
        $mainCategoriesResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($mainCategoriesResult === false || $resultCode !== 200) {
            var_dump($mainCategoriesResult);
            exit ("Something went wrong");
        }
        $categoriesList = json_decode($mainCategoriesResult);
        return $categoriesList;
    }

    public function findFirstLeaf($parentId, $token) {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/categories";
        $query = ['parent.id' => $parentId];
        $getChildrenUrl = $url . '?' . http_build_query($query);
        $ch = $this->getCurl($headers, $getChildrenUrl);
        $categoriesResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($categoriesResult  === false || $resultCode !== 200) {
            var_dump($categoriesResult);
            exit ("Something went wrong");
        }
        $categoriesList = json_decode($categoriesResult);
        $category = $categoriesList->categories[0];

        if ($category->leaf === true) {
            return $category;
        } else {
            return $this->findFirstLeaf($category->id, $token);
        }
    }


}
