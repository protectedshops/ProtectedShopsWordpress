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
     * @param $templateId
     * @param int $bundleId
     * @return mixed
     */
    public function createProject($module, $title, $templateId = null, $bundleId = null)
    {
        $function = 'partners/' . $this->partner . '/shops';
        $data['shop'] = array(
            'module' => $module,
            'title' => $title
        );
        if ($templateId) {
            $data['shop']['templateId'] = $templateId;
        }
        if ($bundleId) {
            $data['shop']['bundleId'] = $bundleId;
        }

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

        return $this->apiRequest('POST', $function, array('answers' => $answers));
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
     * @param $moduleKey
     * @return mixed
     */
    public function getTemplates($partner, $moduleKey)
    {
        $function = "partners/" . $partner . "/templates/module/" . $moduleKey;

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

    /**
     * @param $partner
     * @return mixed
     */
    public function getProjects($partner, $shopIds = array())
    {
        $function = "partners/" . $partner . "/shops";
        $data = null;
        if (!empty($shopIds)) {
            $data = array('shopIds' => $shopIds);
        }
        $response = $this->apiRequest('GET', $function, $data);

        return $response;
    }

    /**
     * @param $partner
     * @param $projectId
     * @return mixed
     */
    public function getProject($partner, $projectId)
    {
        $function = "partners/" . $partner . "/shops/" . $projectId;
        $response = $this->apiRequest('GET', $function);

        return $response;
    }

    /**
     * @param $apiUrl
     * @param $partnerId
     * @param $clientSecret
     * @return bool
     */
    public static function test($apiUrl, $partnerId, $clientSecret)
    {
        $data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $partnerId,
            'client_secret' => $clientSecret
        );

        $dsUrl = '';
        if (strpos($apiUrl, 'http') === false) {
            $dsUrl .= 'https://';
        }
        $dsUrl .= "$apiUrl/oauth/v2/token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dsUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), 1);

        //close connection
        curl_close($ch);

        return is_array($response) && array_key_exists('access_token', $response) ? true : false;
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
        $dsUrl = '';
        if (strpos($this->apiUrl, 'http') === false) {
            $dsUrl .= 'https://';
        }
        $dsUrl .= "$this->apiUrl/v2.0/de/$apiFunction/format/json";

        // Open connection
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $dsUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getAccessToken()));

        switch ($httpMethod) {
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
            if ($httpMethod == 'GET') {
                curl_setopt($ch, CURLOPT_URL, $dsUrl . '?' . http_build_query($data));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $response;
    }
}
