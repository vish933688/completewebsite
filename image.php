<div class="width-100 margin-top-50">
    <div class="container">
        <h3 class="gallery-head-style">Gallery</h3>
        <?php
        $galleryImages = glob("uploads/gallery/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($galleryImages as $image) {
            echo '<div class="width-25"><img src="' . $image . '" alt="Gallery Image" class="gallery-img"></div>';
        }
        ?>
    </div>
</div>
<div class="event-list">
    <div class="heading-sect">
        <h3 class="head-title">Upcoming Events</h3>
    </div>
    <ul class="upcoming-event-list">
        <?php
        $eventImages = glob("uploads/events/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($eventImages as $image) {
            echo '<li><img src="' . $image . '" alt="Event Image"></li>';
        }
        ?>
    </ul>
</div>
