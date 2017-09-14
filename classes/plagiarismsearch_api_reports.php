<?php

class plagiarismsearch_api_reports extends plagiarismsearch_api
{

    public function action_create_file($filename, $post = array())
    {
        $url = $this->api_url . '/reports/create';

        $post['fields'] = array('id', 'status', 'plagiat', 'file');

        $post['title'] = basename($filename);
        $post['filter_chars'] = $this->get_config('filter_chars', 0);
        $post['filter_references'] = $this->get_config('filter_references', 0);
        $post['filter_quotes'] = $this->get_config('filter_quotes', 0);

        $files = array('file' => realpath($filename));

        return $this->post($url, $post, $files);
    }

    public function action_send_file($file, $post = array())
    {
        /* @var $file \stored_file */

        if ($tmpFile = $this->tmp_file($file->get_filename(), $file->get_content())) {
            $post['remote_id'] = $this->generate_remote_id($file);

            $result = $this->action_create_file($tmpFile, $post);

            unlink($tmpFile);

            return $result;
        }
    }

    public function action_status($ids = array())
    {
        $url = $this->api_url . '/reports';

        $post['fields'] = array('id', 'status', 'plagiat', 'file');
        $post['ids'] = $ids;

        return $this->post($url, $post);
    }

    protected function get_config($name, $default = null)
    {
        return plagiarismsearch_config::get_config_or_settings($this->cmid, $name, $default);
    }

    protected function tmp_dir()
    {
        global $CFG;
        if ($tmpDir = $CFG->dataroot . '/temp' and is_writable($tmpDir)) {
            return $tmpDir;
        } else {
            return sys_get_temp_dir();
        }
    }

    protected function tmp_file($filename, $content)
    {
        if ($tmpDir = $this->tmp_dir() and $filename and $filename = $tmpDir . DIRECTORY_SEPARATOR . $filename and $f = fopen($filename, 'w')) {
            fwrite($f, $content);
            fclose($f);

            return $filename;
        }
    }

    protected function generate_remote_id($file = null)
    {
        /* @var $file \stored_file */
        if ($file) {
            return 'c:' . $this->cmid . 'u:' . $file->get_userid() . 'h:' . $file->get_pathnamehash() . 'id:' . $file->get_id();
        } else {
            return 'c:' . $this->cmid . 'u:' . $file->userid . 'h:' . $this->filehash;
        }
    }

}
