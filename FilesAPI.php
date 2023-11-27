<?php

class FilesAPI
{
    private $token="y0_AgAAAABlopFmAAiHsQAAAADSUpMItaAQj2TyRySNIxrVFKlc-0pg9kU";
    private $diskUrl="https://disk.yandex.ru/d/H400n4Oh1ykQRg";
    private $dirUrl="";
    private $files;
    private $links;

    public function __construct()
    {

    }

    public function getDirectoryUrl($dir_num)
    {
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_URL,"https://cloud-api.yandex.net/v1/disk/public/resources/?public_key=https://disk.yandex.ru/d/H400n4Oh1ykQRg");
        curl_setopt($curl,CURLOPT_POST,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        $result=curl_exec($curl);
        $obj=json_decode($result);
        $dir_url=$obj->{'_embedded'}->{'items'}[$dir_num]->{'public_url'};
        curl_close($curl);
        $this->dirUrl=$dir_url;
    }

    public function getDirectoryFiles()
    {
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_URL,"https://cloud-api.yandex.net/v1/disk/public/resources/?public_key=$this->dirUrl&sort=-created&limit=50000");
        curl_setopt($curl,CURLOPT_POST,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $result=curl_exec($curl);
        $obj=json_decode($result);

        $files=$obj->{'_embedded'}->{'items'};
        $this->files=$files;
        curl_close($curl);
    }

    public function getFilesLinks($left_boarder_date)
    {
        $links=array();
        $size=0;
        foreach ($this->files as $file)
        {
            if ($file->{'type'} != 'dir')
            {
                $links[] = $file->{'file'};
            }
        }
        $this->links=$links;
    }

    public function loadFiles()
    {
        set_time_limit(0);
        foreach($this->links as $link)
        {
            $start_index = stripos($link,"filename=");
            $end_index = stripos($link,"&disposition");
            $filename = substr($link,$start_index+9,$end_index-$start_index-9);

            file_put_contents("files/" . $filename,file_get_contents($link));
        }
    }

    public function getLoadedFilesCount()
    {
        $count = count(scandir("files"));
        echo $count-2 . " files have been downloaded";
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}