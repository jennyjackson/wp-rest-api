<?php

class Qiniu_RS_PutPolicy_WP
{
    public $Scope;                  //必填
    public $Expires;                //默认为3600s
    public $CallbackUrl;
    public $CallbackBody;
    public $ReturnUrl;
    public $ReturnBody;
    public $AsyncOps;
    public $EndUser;
    public $InsertOnly;             //若非0，则任何情况下无法覆盖上传
    public $DetectMime;             //若非0，则服务端根据内容自动确定MimeType
    public $FsizeLimit;
    public $SaveKey;
    public $PersistentOps;
    public $PersistentPipeline;
    public $PersistentNotifyUrl;
    public $FopTimeout;
    public $MimeLimit;

    public function __construct($scope)
    {
        $this->Scope = $scope;
    }

    public function Token($mac) // => $token
    {
        $deadline = $this->Expires;
        if ($deadline == 0) {
            $deadline = 3600;
        }
        $deadline += time();

        $policy = array('scope' => $this->Scope, 'deadline' => $deadline);
        if (!empty($this->CallbackUrl)) {
            $policy['callbackUrl'] = $this->CallbackUrl;
        }
        if (!empty($this->CallbackBody)) {
            $policy['callbackBody'] = $this->CallbackBody;
        }
        if (!empty($this->ReturnUrl)) {
            $policy['returnUrl'] = $this->ReturnUrl;
        }
        if (!empty($this->ReturnBody)) {
            $policy['returnBody'] = $this->ReturnBody;
        }
        if (!empty($this->AsyncOps)) {
            $policy['asyncOps'] = $this->AsyncOps;
        }
        if (!empty($this->EndUser)) {
            $policy['endUser'] = $this->EndUser;
        }
        if (isset($this->InsertOnly)) {
            $policy['insertOnly'] = $this->InsertOnly;
        }
        if (!empty($this->DetectMime)) {
            $policy['detectMime'] = $this->DetectMime;
        }
        if (!empty($this->FsizeLimit)) {
            $policy['fsizeLimit'] = $this->FsizeLimit;
        }
        if (!empty($this->SaveKey)) {
            $policy['saveKey'] = $this->SaveKey;
        }
        if (!empty($this->PersistentOps)) {
            $policy['persistentOps'] = $this->PersistentOps;
        }
        if (!empty($this->PersistentPipeline)) {
            $policy['persistentPipeline'] = $this->PersistentPipeline;
        }
        if (!empty($this->PersistentNotifyUrl)) {
            $policy['persistentNotifyUrl'] = $this->PersistentNotifyUrl;
        }
        if (!empty($this->FopTimeout)) {
            $policy['fopTimeout'] = $this->FopTimeout;
        }
        if (!empty($this->MimeLimit)) {
            $policy['mimeLimit'] = $this->MimeLimit;
        }


        $b = json_encode($policy);
        return Qiniu_SignWithData_WP($mac, $b);
    }
}

class Qiniu_Mac_WP {

    public $AccessKey;
    public $SecretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->AccessKey = $accessKey;
        $this->SecretKey = $secretKey;
    }

    public function Sign($data) // => $token
    {
        $sign = hash_hmac('sha1', $data, $this->SecretKey, true);
        return $this->AccessKey . ':' . Qiniu_Encode_WP($sign);
    }

    public function SignWithData($data) // => $token
    {
        $data = Qiniu_Encode_WP($data);
        return $this->Sign($data) . ':' . $data;
    }
}

function Qiniu_SignWithData_WP($mac, $data) // => $token
{
    return Qiniu_RequireMac_WP($mac)->SignWithData($data);
}

function Qiniu_RequireMac_WP($mac) // => $mac
{
    if (isset($mac)) {
        return $mac;
    }

    global $QINIU_ACCESS_KEY;
    global $QINIU_SECRET_KEY;

    return new Qiniu_Mac_WP($QINIU_ACCESS_KEY, $QINIU_SECRET_KEY);
}

function Qiniu_Encode_WP($str) // URLSafeBase64Encode
{
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($str));
}

