<?php

namespace app\core\form;

use app\core\Model;

class Video
{

    public function getYoutubeVideoId($url)
    {
        $video_id = '';
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $match)) {
            $video_id = $match[1];
        }
        return $video_id;
    }

    public function displayYoutubeLink($url)
    {
        $video_id = $this->getYoutubeVideoId($url);
        $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/0.jpg";
        return "
                <div class='col-sm-6 col-md-4 col-lg-3 mb-4'>
                    <div class='card h-100'>
                        <a href='{$url}' target='_blank' class='text-decoration-none'>
                            <img src='{$thumbnail_url}' alt='YouTube Thumbnail' class='card-img-top'>
                            <div class='card-body'>
                                <p class='card-text small text-muted'>" . $this->truncateUrl($url) . "</p>
                            </div>
                        </a>
                    </div>
                </div>
            ";

    }

    private function truncateUrl($url, $length = 50)
    {
        return (strlen($url) > $length) ? substr($url, 0, $length - 3) . '...' : $url;
    }
}
