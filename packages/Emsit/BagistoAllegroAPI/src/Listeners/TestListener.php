<?php

namespace Emsit\BagistoAllegroAPI\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Emsit\BagistoAllegroApi\Http\Controllers\Admin\AllegroAPIAuthenticationController as APIAuthentication;
use Emsit\BagistoAllegroAPI\Models\AllegroProductData;

class TestListener
{
    public $token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2NzcxOTY1MjUsInVzZXJfbmFtZSI6IjEwNTkwMzYyNSIsImp0aSI6ImQ0NWIyYmM4LTM3OTgtNDNlNC04MWU1LTY2MGQ0MDBhZDk0MiIsImNsaWVudF9pZCI6IjNjNzE1NzBhYjE5OTQ3NTNhYWZlMTdlODY5NTFjZTMzIiwic2NvcGUiOlsiYWxsZWdybzphcGk6b3JkZXJzOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOnNldHRpbmdzOndyaXRlIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6d3JpdGUiLCJhbGxlZ3JvOmFwaTpwcm9maWxlOnJlYWQiLCJhbGxlZ3JvOmFwaTpiaWxsaW5nOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOnNldHRpbmdzOnJlYWQiLCJhbGxlZ3JvOmFwaTpwYXltZW50czpyZWFkIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6cmVhZCJdLCJhbGxlZ3JvX2FwaSI6dHJ1ZX0.rjrepMtdpCMiNocLlAp_PJrk6bH3IXAzy2aQLDbts37qH3B9cIpfYzXUQjlVxUGG_a5yFRPsVirS70Wg7_3TSwJA4NyV5N-fLj4d2IUclcjNmVvdCl2sYBfYgEz5Whz0endOFKgQjEvBFKR4tfKUv_y7vee24Vb9sm231x851dDzJ8oXad2Gfo8pCmRAHhoV7PJJLGPOgQJkiwbu2pAi52quJnJzq8t-fPXnQ0HeSWiTbOaTEEZ-h-ANEmEUHLsn7ovczeYoavFKDTm8Cuwyg5c2fhpfEf8M0W60JW9G_bvgkQnFDmlkeX8lsULI66fktLyKtXTk4athoUrXYCacAf2YIGTeM3RGOlJ-uZbC4cMFgEFlk6tz7WHT_ttWWpIJzkSb7Q2RmNjK6THJn-12fODxqKWgWoTLG4lnJEJJriRpUxVXqcS0YK5fGZcYOANx3ysnNQE_KhOLY4lgCWPn4moOEF7ZK1BEsyhjamfa8FV9jX08sBRjITgqN5Oe2ym-zcCxqg9hkgd96UHQgQpNg9uMsJxk7cq_e_o2j1YCKwNl2wo7VrG3LkRw2BzaemlM-DotGd1JChxoeTJnzjXJ8EAcvzr9e3GUwuZv--_9SlylRb7GdeP9qPDnAqpojC5fJFJUSL9aISMAqDN8Q-WaoUydasT830pnHZFx-wr7_Dk';
    public $refresh_token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiIxMDU5MDM2MjUiLCJzY29wZSI6WyJhbGxlZ3JvOmFwaTpvcmRlcnM6cmVhZCIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6d3JpdGUiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczp3cml0ZSIsImFsbGVncm86YXBpOnByb2ZpbGU6cmVhZCIsImFsbGVncm86YXBpOmJpbGxpbmc6cmVhZCIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6cmVhZCIsImFsbGVncm86YXBpOnBheW1lbnRzOnJlYWQiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczpyZWFkIl0sImFsbGVncm9fYXBpIjp0cnVlLCJhdGkiOiJkNDViMmJjOC0zNzk4LTQzZTQtODFlNS02NjBkNDAwYWQ5NDIiLCJleHAiOjE2ODQ5MjkzMjUsImp0aSI6IjhmNTg2Y2ZjLTZhNDQtNDk1ZS05MmRlLTcxN2UyNGViYzcwYSIsImNsaWVudF9pZCI6IjNjNzE1NzBhYjE5OTQ3NTNhYWZlMTdlODY5NTFjZTMzIn0.B9jf5pF_87mYlY3fPrFTVi6mgc7giZO9LHMWNmrZR-Z7GAPUT_LME7_Z0glGbNxNkgKYaW3QH2sPIkAp-FRs0PmR6ZmvIrIOR1rk4X7R3Bhh1tveyAaO8OgtVF9qbhLDbfRVmuind4Oe_f5Gnj-9XpS571_OAeZtLUcIqQLkhT8fu0oMZXLPsZRbrF5eEayOEHxqLxev4Pz6vl5Yz9xgO377PlTMGNv43Hv8QFrkvKSAEtIaqqnQ8a-LNNTwGC2BkdyWR7jU3gQNMlJEnLxK_b_HE8gsgKo-wD2ZcvW4pAX1DsRff9J1Prw3YIFNfm_eaLRfCPSdBs5g4H48Ch3CMl2Yw2XOrdpWOtVfFTklmy11FDldvsYKcssiGjqTO8BxBNz3B0gltkDMjafFhgNEvTNk1NhuUBd1vA9qFB-U55sOKcfNB16GUkX3PS0cIVLdG-IqYIdjmPRHQ6MB7zOaWViHtLgarGVieGqMsUDds1qCcoRicuDOU0Z3rWPg3fOXH0k4wjVQ8ZiGEUFQlohDBxT-fgdejiTZGjoMdV0YWeZH26GGbuh_lD81zVbEBKbDDYTvhd6yN3yKk7AZaNB7iTNIPC7XaZgJbsVE5olam41dn7ILDINkNYIAcc_zu_fq4LXwLnnwoaStxmlCq6tkP_AJD2zO6m1qYVBsfaZ4QuU';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(APIAuthentication $apiAuthentication)
    {
        $this->token = $apiAuthentication->getToken();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleCreate($event)
    {


        $response = $this->createOffer($this->token, $event->sku);

        AllegroProductData::create([
            'allegro_product_id' => $response->id,
            'shop_product_id'    => $event->id
        ]);
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleUpdate($event)
    {
        $product = $event->product_flats->first();

        $offerId = AllegroProductData::where('shop_product_id', $product->product_id)
            ->first()
            ->getAttribute('allegro_product_id');

        $values = collect([
            'price' => round($product->price, 2)
        ]);

        $this->updateOffer($this->token, $offerId, $values);
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

    public function getCurl($headers, $url, $content = null, $method = 'POST') {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true
        ));
        if ($content !== null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
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

    protected function createOffer($token, $productId)
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

        return json_decode($result);
    }

    public function updateOffer(string $token, int|string $offerId, Collection $values)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json", "Content-Type: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/product-offers/$offerId";

        $content = array(
            "sellingMode" => [
                "price" => [
                    "amount" => $values->get('price'),
                    "currency" => "PLN"
                ]
            ],
            "stock" => [
                "available" => 10
            ],
        );
dd($content);
        $ch = $this->getCurl($headers, $url, json_encode($content), 'PATCH');
        $result = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($result);
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
}
