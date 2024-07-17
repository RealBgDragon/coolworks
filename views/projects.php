<div class="col-md-auto d-flex justify-content-end mb-4">
</div>

<div class="row">
    <?php
    use app\core\form\Video;

    $video = new Video();
    foreach ($videourls as $videourl) {
        echo $video->displayYoutubeLink($videourl);
    }
    ?>
</div>