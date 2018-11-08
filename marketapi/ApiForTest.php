<?php

class GettingApiReturn
{
    protected $accessKey = "",
               $secretKey = "",
               $url = "",
               $otherParams = [],
               $httpParams = [];

    public function __construct($url,$otherParams = [],$accessKey = '',$secretKey = ''){
        $this->url = $url;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->otherParams = $otherParams;
    }

    /**
     * @return string
     * 作用：获取签名参数值字符串。
     */
    public function  generate_sign_string(){
        $this->httpParams = array(
            'access_key' => $this->accessKey,
            'date' => time()
        );
        if ($this->otherParams){
            $this->httpParams = array_merge($this->httpParams,$this->otherParams);
        }
        $signParams = array_merge($this->httpParams, array('secret_key' => $this->secretKey));
        ksort($signParams);
        $signString = http_build_query($signParams);
        return strtolower(md5($signString));
    }

    /**
     * @param bool $need_sign
     * @return array
     * 作用：获取url参数部分数组；
     */
    public function generate_httpParams_string($need_sign = false){
        if(!$need_sign){
            $this->httpParams = $this->otherParams;
        }else{
            $this->httpParams['sign'] = $this->generate_sign_string();
        }
        return $this->httpParams;
    }

    /**
     * @param bool $need_sign
     * @return string
     * 作用：获取请求地址
     */
    public function generate_api_request_url($need_sign = false){
        $queryString = http_build_query($this->generate_httpParams_string($need_sign));
        $requestURL = ($need_sign || $this->otherParams) ? "$this->url?".$queryString : $this->url;
        return $requestURL;
    }

    public function  store_output_to_assocArray($need_sign = false,$header = []){
        $ch = curl_init();
        $requestURL = $this->generate_api_request_url($need_sign);
        //var_dump($requestURL);
        curl_setopt($ch, CURLOPT_URL, $requestURL);
	//curl_setopt($ch, CURLOPT_HEADER, 1);
        if($header){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		$ret = curl_exec($ch);

       try{
            if(!$ret){
                $error = curl_errno($ch);
                curl_close($ch);
                throw new Exception("curl出错，错误码:$error");
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        curl_close($ch);
        return json_decode($ret, true);
	}
}