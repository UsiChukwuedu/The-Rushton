<?php echo $THEME_URL; ?>
<footer>
    <div class="row container">
        <div class="small-6 columns left">
            <div class="row images">
                <div class="columns logo">
                    <a class="sierra" href="https://www.sierra.ca/">
                        <img src="<?php echo THEME_URL; ?>/img/sierra_logo.svg;" alt="Sierra logo" />
                    </a>
                </div>
                 <div class="socials">
                    <?php $social = get_field('social_media'); ?>
                    <ul class="socials-list">
                        <?php foreach($social as $item): ?>
                            <li class="social-item">
                                <a href="<?php echo esc_url($item['url']); ?>">
                                    <?php 
                                        $src = THEME_URL . '/img/socials/';
                                        switch($item['icon']){
                                            case 'facebook':
                                                $src .= 'facebook.svg';
                                            break;
                                            case 'instagram':
                                                $src .= 'instagram.svg';
                                            break;
                                            case 'pinterest':
                                                $src .= 'pinterest.svg';
                                            break;
                                        }
                                    ?>
                                    <img class="icon" src="<?php echo $src; ?>" alt="<?php echo $item['icon']; ?> icon" />
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="small-6 columns by-agency">
            <p class="text">&copy; Sierra Corporation All Rights Reserved.</p>
            <div>
                <a class="privacy" href="<?php echo get_permalink(10); ?>" target="_blank">Privacy Policy</a> <a href="https://www.52pick-up.com/" target="_blank">Site by 52 Pick-up Inc.</a>
            </div>
        </div>
    </div>
</footer>
