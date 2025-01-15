<?php
// require_once(DIR_SYSTEM . '../RecommApi/autoload.php');
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;
class RecombeeHelper {
    private $client;
    
    public function __construct() {
      error_log('RecombeeHelper constructor');
        $this->client = new Client(
            'flaviu-dev', 
            'yO02H30kHYTnkNFgXNslwpANvptPXWPp8oUo6XDKeQGAaEMQk7utEdMnx84wCnyJ',
            ['region' => 'eu-west']
        );
    }
    
    public function addDetailView($user_id, $product_id) {
        file_put_contents(DIR_LOGS . 'recombee.log', date('Y-m-d G:i:s') . ' - User ID: ' . $user_id . "\n", FILE_APPEND);
        try {
            $this->client->send(new Reqs\AddDetailView(
                $user_id,
                $product_id,
                ['cascadeCreate' => true]
            ));
        } catch (Ex\ApiException $e) {
            error_log($e->getMessage());
        }
    }
    
    public function addCartAddition($user_id, $product_id) {
        file_put_contents(DIR_LOGS . 'recombee.log', date('Y-m-d G:i:s') . ' - User ID: ' . $user_id . "\n", FILE_APPEND);
        try {
            $this->client->send(new Reqs\AddCartAddition(
                $user_id,
                $product_id,
                ['cascadeCreate' => true]
            ));
        } catch (Ex\ApiException $e) {
            error_log($e->getMessage());
        }
    }
    
    public function getRecommendations($user_id, $count = 10) {
        file_put_contents(DIR_LOGS . 'recombee.log', date('Y-m-d G:i:s') . ' - User ID: ' . $user_id . "\n", FILE_APPEND);
        try {
            $recommended = $this->client->send(new Reqs\RecommendItemsToUser(
                $user_id,
                $count,
                ['cascadeCreate' => true, 'scenario' => 'colaborativ']
            ));
            return $recommended['recomms'];
        } catch (Ex\ApiException $e) {
            return [];
        }
    }
} 