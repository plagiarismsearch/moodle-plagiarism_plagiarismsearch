<?php

class plagiarismsearch_api
{
    public $api_url = 'https://plagiarismsearch.com/api/v3';
    public $api_user;
    public $api_key;
    /**/
    public $api_success;
    public $api_data;
    public $api_error;
    public $api_info;
    /**/
    public $cmid;
    public $userid;
    public $filehash;
    public $filename;

    public function __construct($config = array())
    {
        $this->api_url = plagiarismsearch_config::get_settings('api_url');
        $this->api_user = plagiarismsearch_config::get_settings('api_user');
        $this->api_key = plagiarismsearch_config::get_settings('api_key');

        $this->configure($config);
    }

    protected function configure($config = array())
    {
        if (!empty($config)) {
            if (is_array($config)) {
                foreach ($config as $key => $value) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function post($url, $post = array(), $files = array())
    {
        $curl = curl_init($url);

        if ($postFields = $this->build_post_files($post, $files) or $postFields = $this->build_post_to_string($post)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        }

        // HTTP basic authentication
        curl_setopt($curl, CURLOPT_USERPWD, $this->api_user . ':' . $this->api_key);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);

        $this->api_data = curl_exec($curl);
        $this->api_info = curl_getinfo($curl);
        $this->api_error = curl_error($curl);
        $this->api_success = $this->api_info >= 200 and $this->api_info < 300 and ! $this->api_error;
        curl_close($curl);

        if ($this->api_data) {
            return $this->unpack($this->api_data);
        }
    }

    public function get_response()
    {
        if ($this->api_data) {
            return $this->unpack($this->api_data);
        }
    }

    private function unpack($data)
    {
        return json_decode($data, false);
    }

    private function build_post_to_string($post)
    {
        if (!empty($post)) {
            if (is_array($post)) {
                return http_build_query($post, '', '&');
            } else {
                return $post;
            }
        }
        return false;
    }

    private function build_post_files($post, $files)
    {
        $result = array();
        if (!empty($post) and is_array($post)) {
            foreach ($post as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = http_build_query($value, '', '&');
                } else {
                    $result[$key] = $value;
                }
            }
        }
        if (!empty($files) and is_array($files)) {
            $result = array_merge($result, $this->build_files($files));
        }

        return $result;
    }

    private function build_files($files)
    {
        $result = array();
        if (!empty($files)) {
            foreach ($files as $key => $value) {
                if (is_string($value)) {
                    $result[$key] = new \CURLFile(realpath($value));
                } elseif (isset($value['tmp_name'])) {
                    $file = $value['tmp_name'];
                    $name = isset($value['name']) ? $value['name'] : null;
                    $type = isset($value['type']) ? $value['type'] : null;

                    $result[$key] = new \CURLFile($file, $type, $name);
                }
            }
        }
        return $result;
    }

    public function ping()
    {
        $url = $this->api_url . '/ping';

        return $this->post($url);
    }

}
