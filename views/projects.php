<div class="col-md-auto d-flex justify-content-end mb-4">
    <a href="/wcp/add-models" class="btn btn-primary btn-sm">Add Model <i class="fas fa-plus ml-2"></i></a>
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