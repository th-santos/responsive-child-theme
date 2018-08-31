<?php
    // exit if accessed directly
    if (!defined('ABSPATH')) { exit; }
    
    /**
     * Template Name: A01034486 Retrieve Data
     */
    
    get_header();
    
    $prefix = 'A01034486';
?>

<script>
    var page = "retrieve";
</script>

<?php
    # set some variables
    $animals = [];  // e.g. ["Dog" => 2, "Cat" => 4, "Rat" => 1]
    $animals_qty = 0;
    $colours = [];  // e.g. ["#ff000", "#00ff00", "#0000ff"]
    $angles = [];   // e.g. [180, 90, 90]
    $labels = [];   // e.g. ["Dog", "Cat", "Rat"]
    $percents = []; // e.g. ["50%", "30%", "20%"]
    $votes_qty = 0;

    // $wpdb as global
    global $wpdb;
    
    # create an SQL query
    $sql = "SELECT option_value FROM $wpdb->options WHERE option_name LIKE \"$prefix%\" ORDER BY option_name";
    $options = $wpdb->get_results($sql, ARRAY_N);   // retrieve an entire SQL result set from the database   
    // $wpdb: class / get_results: method
    # loop throw each record
    foreach ($options as $option) {
        $record = json_decode($option[0], true);    // json-decode it / true - associative array
        
        if (is_array($record)) {                        // verify if "$record" is an array
            foreach ($record as $key => $value) {       // iterate over each array's element
                if ($key == $prefix.'_animal') {        // if the key of an element is "..._animal"
                    $kind = htmlspecialchars($value);   // escape html (security)
                    $animals_qty++;                     // increment quantity of animals
                    # verify if the kind of animal exists as key in the "animal" array
                    if (array_key_exists($kind, $animals)) {
                        # if the key already exists, increment its value
                        $animals[$kind] += 1;
                    } else {
                        # otherwise create a new element in the "animal" array
                        $animals[$kind] = 1;
                    }
                }
            }
        } else { ?>
            <script>
                alert("Error: \"record\" is not an array.\nPlease contact the system administrator");
            </script>
        <?php    
        }
    }
    
    # sort associative "animal" array in descending order, according to the value
    arsort($animals); // most voted come first

    # color generator for PHP - https://github.com/mistic100/RandomColor.php
    require_once('random-color.php');
    use \Colors\RandomColor;

    # generate an array of unique random dark HEX colors
    $colours = RandomColor::many(count($animals), array(
        'luminosity' => 'dark'
    ));

    # create 2 arrays: angles and labels
    foreach ($animals as $key => $value) {
        array_push($angles, 360 / $animals_qty * $value);
        array_push($labels, $key);
        array_push($percents, number_format($value / $animals_qty * 100, 2) . "%");
        $votes_qty += $value; // sum the total of votes
    }
?>

<script>
    // pass variables from the server
    var colours = <?php echo json_encode($colours); ?>;
    var angles = <?php echo json_encode($angles); ?>;
    var labels = <?php echo json_encode($labels); ?>;
    var percents = <?php echo json_encode($percents); ?>;
</script>

<div id="content" class="<?php echo esc_attr(implode(' ', responsive_get_content_classes())); ?>">
    <h1><?php single_post_title(); ?></h1>

    <div id="canvas-area">
        <canvas id="doughnut-chart"></canvas>
        <div>
            <b>Responsive Doughnut</b><br>
            (resize viewport to redraw the canvas - <b>no</b> pixelate image)
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-borderless">
            <thead>
                <tr class="font-weight-bold">
                    <th scope="col"><a href="#pos-explanation" class="font-weight-bold">Pos.</a> *</th>
                    <th scope="col">Animal</th>
                    <th scope="col">Votes</th>
                    <th scope="col">Percentage</th>
                    <th scope="col">Colour</th>
                </tr>
            </thead>
            <tfoot>
                <tr class="font-weight-bold">
                    <th>-</th>
                    <td>-</td>
                    <td><?php echo $votes_qty; ?></td>
                    <td>100%</td>
                    <td>-</td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                    $position = 0;

                    foreach ($animals as $key => $value) {
                        $arrId = $position;
                        ++$position;
                        $inputId = 'animColor' . $position;

                        echo '<tr>';
                        echo    '<th scope="row" class="font-weight-bold">' . $position . '</th>';
                        echo    '<td>' . $key . '</td>';
                        echo    '<td>' . $value . '</td>';
                        echo    '<td>' . $percents[$arrId] . '</td>';
                        echo    '<td>
                                    <input type="color" id="' . $inputId . '" 
                                        name="' . $inputId . '" class="form-control" value="' . $colours[$arrId] . '" autocomplete="off">
                                    <a href="" id="link-' . $inputId . '" class="change-color">change</a>
                                </td>';
                        echo '</tr>';
                        ?>

                        <script>
                            // get DOM elements
                            var inputColor<?php echo $position; ?> = document.getElementById("<?php echo $inputId; ?>");
                            var linkColor<?php echo $position; ?> = document.getElementById("link-<?php echo $inputId; ?>");

                            // add event listeners - mouseenter / mouseleave
                            inputColor<?php echo $position; ?>.addEventListener("mouseenter", enterChangeColor);
                            inputColor<?php echo $position; ?>.addEventListener("mouseleave", leaveChangeColor);
                            linkColor<?php echo $position; ?>.addEventListener("mouseenter", enterChangeColor);
                            linkColor<?php echo $position; ?>.addEventListener("mouseleave", leaveChangeColor);
                            
                            // functions - mouseenter / mouseleave
                            function enterChangeColor() {
                                inputColor<?php echo $position; ?>.classList.add("input-hovered");
                                linkColor<?php echo $position; ?>.classList.add("link-hovered");
                            };
                            
                            function leaveChangeColor() {
                                inputColor<?php echo $position; ?>.classList.remove("input-hovered");
                                linkColor<?php echo $position; ?>.classList.remove("link-hovered");
                            };
                            
                            // add event listeners - click on link
                            linkColor<?php echo $position; ?>.addEventListener("click", clickChangeColor);
                            
                            // function - click on link
                            function clickChangeColor(event) {
                                event.preventDefault();
                                inputColor<?php echo $position; ?>.click(); 
                            };
                            
                            // add event listeners - change color
                            inputColor<?php echo $position; ?>.addEventListener("change", doChangeColor);
                            
                            // function - change color
                            function doChangeColor(event) {
                                colours[<?php echo $arrId; ?>] = this.value;
                                drawDoughnutChart("doughnut-chart", colours, angles, percents, true);
                            };
                        </script>
                    <?php
                    }
                ?>
            </tbody>
        </table>
    </div>

    <form id="clear-form">
        <input type="hidden" name="prefix" value="<?php echo $prefix; ?>" required>
        <button type="submit" id="clear-btn" class="btn btn-danger">Clear All Data</button>
    </form>

    <p id="pos-explanation"><span class="font-weight-bold">* Position</span><br>
    The position is defined by the number of votes. Most voted animals come first.<br>
    If two or more animals has the same number of votes, the position is defined by the time. The animal that receives the first vote early comes first.</p>

</div>


<?php
    get_sidebar();
    get_footer();
?>

<script>
    // check if has some data to show
    if (<?php echo $votes_qty; ?> < 1) {
        let content = document.getElementById("content");
        
        // display a friendly error message
        content.innerHTML = `
        <h1> No results to show</h1>
        <p>Please take a moment to <a href="questionnaire">answer our survey</a>!</p>`;
    }
</script>

<?php
    if ($votes_qty > 0) { ?>
        <script src="<?php echo get_stylesheet_directory_uri().'/js/doughnut-chart.js'; ?>"></script>
        <script>
            // call drawDoughnutChart function
            drawDoughnutChart("doughnut-chart", colours, angles, percents, true);
        </script>
    <?php
    }
?>


