<?php

final class DocumentServer
{
    private $apiUrl;
    private $partnerId;
    private $clientSecret;
    private $partner;

    private static $me = null;

    /**
     * @param $apiUrl
     * @param $partnerId
     * @param $partner
     * @param $clientSecret
     *
     * @return DocumentServer
     */
    public static function me($apiUrl, $partnerId, $partner, $clientSecret)
    {
        if (static::$me == null) {
            static::$me = new DocumentServer($apiUrl, $partnerId, $partner, $clientSecret);
        }

        return static::$me;
    }

    /**
     * Ds_Communicator constructor.
     * @param $apiUrl
     * @param $partnerId
     * @param $partner
     * @param $clientSecret
     */
    public function __construct($apiUrl, $partnerId, $partner, $clientSecret)
    {
        $this->apiUrl = $apiUrl;
        $this->partnerId = $partnerId;
        $this->clientSecret = $clientSecret;
        $this->partner = $partner;
    }

    /**
     * @param $module
     * @param $title
     * @param $url
     * @return mixed
     */
    public function createProject($module, $title, $url)
    {
        $function = 'partners/' . $this->partner .'/shops';
        $data['shop'] = array(
            'module' => $module,
            'title' => $title,
            'url' => $url
        );
        $response = $this->apiRequest('POST', $function, $data);

        return json_decode($response, 1);
    }

    /**
     * @param $partner
     * @param $projectId
     * @return mixed
     */
    public function getQuestionary($partner, $projectId)
    {
        $function = "partners/" . $partner . "/shops/" . $projectId . "/questionary";

        return $this->apiRequest('GET', $function);
    }

    /**
     * @param $partner
     * @param $projectId
     * @param $answers
     * @return mixed
     */
    public function answerQuestion($partner, $projectId, $answers)
    {
        $function = "partners/" . $partner . "/shops/" . $projectId . "/answers";

        return $this->apiRequest('GET', $function, array('answers' => $answers));
    }

    /**
     * @param $partner
     * @param $projectId
     * @return mixed
     */
    public function getDocuments($partner, $projectId)
    {
        $function = "partners/" . $partner . "/shops/" . $projectId . "/documents";

        return $this->apiRequest('GET', $function);
    }

    /**
     * @param $partner
     * @param $projectId
     * @param $docType
     * @param $formatType
     * @return mixed
     */
    public function downloadDocument($partner, $projectId, $docType, $formatType)
    {
        $function = "partners/" . $partner . "/shops/" . $projectId . "/documents/" . $docType . "/contentformat/" . $formatType;
        $response = $this->apiRequest('GET', $function);

        return $response;
    }

    public function getProjects($partner)
    {
        $function = "partners/" . $partner . "/shops";
        $response = $this->apiRequest('GET', $function);

        return $response;
    }

    /**
     * @return mixed
     */
    private function getAccessToken()
    {
        $data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->partnerId,
            'client_secret' => $this->clientSecret
        );

        $dsUrl = "$this->apiUrl/oauth/v2/token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dsUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), 1);

        //close connection
        curl_close($ch);

        return $response['access_token'];
    }

    /**
     * @param $httpMethod
     * @param $apiFunction
     * @param null $data
     * @return mixed
     */
    private function apiRequest($httpMethod, $apiFunction, $data = null)
    {
        $dsUrl = "$this->apiUrl/v2.0/de/$apiFunction/format/json";

        // Open connection
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $dsUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Bearer ' .  $this->getAccessToken()));

        switch ($httpMethod)
        {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_PUT, 1);
                break;
            case "GET":
            default:
                break;
        }

        //set post data
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $response;
    }
}

