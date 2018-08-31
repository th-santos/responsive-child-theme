<?php
    // exit if accessed directly
    if (!defined('ABSPATH')) { exit; }

    /**
     * Template Name: A01034486 Store Data
     */

    get_header();

    $prefix = 'A01034486';
?>

<script>
    var page = "store";
</script>

<div id="content" class="<?php echo esc_attr(implode(' ', responsive_get_content_classes())); ?>">
    <h1><?php single_post_title(); ?></h1>

    <form method="POST" id="surv-form" novalidate>
        <input type="hidden" name="prefix" value="<?php echo $prefix; ?>" required>

        <div class="form-group row">
            <label for="surv-name" class="col-form-label col-sm-4">What's your name?</label>
            <div class="col-sm-8">
                <input type="text" id="surv-name" name="<?php echo $prefix; ?>_name" class="form-control" placeholder="Type your name here" value="" autocomplete="off" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-4">What's your favourite animal?</label>
            <div class="col-sm-8">
                <div class="custom-control custom-radio">
                    <input type="radio" id="anm-dog" name="<?php echo $prefix; ?>_animal" class="custom-control-input" value="Dog" required>
                    <label for="anm-dog" class="custom-control-label">Dog</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="anm-cat" name="<?php echo $prefix; ?>_animal" class="custom-control-input" value="Cat" required>
                    <label for="anm-cat" class="custom-control-label">Cat</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="anm-rat" name="<?php echo $prefix; ?>_animal" class="custom-control-input" value="Rat" required>
                    <label for="anm-rat" class="custom-control-label">Rat</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="anm-other" name="<?php echo $prefix; ?>_animal" class="custom-control-input" value="" required>
                    <label for="anm-other" class="custom-control-label">
                        <input type="text" id="anm-input" placeholder="Other animal"  class="form-control form-control-sm" autocomplete="off">
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-6 offset-sm-4 col-sm-4">
                <button type="submit" class="btn btn-primary btn-block" value="Submit">Submit</button>
            </div>
            <div class="col-6 col-sm-4">
                <button type="reset" class="btn btn-secondary btn-block" value="Reset">Reset</button>
            </div>
        </div>
    </form>
</div>

<?php
    get_sidebar();
    get_footer();
?>