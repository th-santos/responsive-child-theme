/* CANVAS 2D DOUGHNUT CHART
 * Args:
 * [1] chartId: 'yourCanvasElementId'
 * [2] colours: ['#f00', '#0f0', '#00f'] 
 *     permissible colour specs:
 *     - red
 *     - #ff0000
 *     - #f00
 *     - rgb(255,0,0)
 *     - rgba(255,0,0,0.5) (alpha range: 0.0-1.0)
 * [3] angles: [60, 90, 210] (360 deg overall) 
 * [4] labels (optional): ["Label 1", "Label 2", "Label 3"]
 * [5] shadow (optional): true / false
 * Note: colours, angles, and labels arrays must have identical length.
 */

function drawDoughnutChart(chartId, colours, angles, labels, shadow) {
    // get DOM elements
	var canvas = document.getElementById(chartId);
    var area = document.getElementById("canvas-area");

    // set variables to resize the canvas
    var maxWidth = 440;     // can be change
    var maxHeight = 400;    // can be change
    canvas.width = maxWidth;
    canvas.height = maxHeight;

    // set variables to draw
    var context = canvas.getContext("2d");
    var startAngle, endAngle;
    var x, y, r;
    var holeSize = .4; // 0 < holeSize < 1
    var numElements = angles.length;

    // set coordinates and radius
    function setCoordinates() {
        x = Math.floor(canvas.width / 2);
        y = Math.floor(canvas.height / 2);
        r = x > y ? y : x; // set radius

        // set new radius if no labels set
        labels ? r *= .7 : r *= .9;
    }

    // resize canvas
    function resizeCanvas() {
        let resizeFactor = area.clientWidth / canvas.width;

        canvas.width = canvas.width * resizeFactor;
        canvas.height = canvas.height * resizeFactor;

        if (canvas.width > maxWidth) canvas.width = maxWidth;
        if (canvas.height > maxHeight) canvas.height = maxHeight;

        setCoordinates();
    }

    // draw sectors
    function drawSectors(numElem) {
        startAngle = 0;

        for (var i = 0; i < numElem; i++) {
            drawSector(colours[i], angles[i]);
        }
    }

    // draw sector
	function drawSector(colour, angle) {
        // set endAngle
        endAngle = startAngle + parseFloat(angle) * Math.PI / 180;
        
        // draw sector - beginPath
		context.beginPath();
        context.arc(x, y, r, startAngle, endAngle, false);

        // cut a hole
        context.arc(x, y, r * holeSize, endAngle, startAngle, true);

        // add colour
		context.fillStyle = colour;
        context.fill();

        // draw sector - closePath
        context.closePath();
        
        // set new startAngle
		startAngle = endAngle;
    }

    // draw labels
    function drawLabels(numElem) {
        startAngle = 0;

        for (var i = 0; i < numElem; i++) {
            drawLabel(angles[i], labels[i]);
        }
    }

    // draw label
    function drawLabel(angle, label) {
        // set endAngle
        endAngle = startAngle + parseFloat(angle) * Math.PI / 180;

        // set variables
        let middleAngle = startAngle + (endAngle - startAngle) / 2;
        let rLabel = r / .7;
        let xLabel = x + rLabel * .9 * Math.cos(middleAngle); // .85: text proximity
        let yLabel = y + rLabel * .9 * Math.sin(middleAngle); // if bigger, more far
        
        // set text options
        context.font = rLabel * .07 + "pt sans-serif"; // .07: text size
        context.fillStyle = "#555";
        context.textAlign = "center";
        context.textBaseline = "middle";
        
        // draw text
        context.fillText(label, xLabel, yLabel);

        // set new startAngle
		startAngle = endAngle;
    }

    function showShadow() {
        context.save();

        // shadow options
        context.shadowColor = "#999";
        context.shadowBlur = r * .1;
        context.shadowOffsetX = r * .05;
        context.shadowOffsetY = r * .05;

        // draw circle and hole - beginPath
        context.beginPath();
        context.arc(x, y, r, 0, 2 * Math.PI, false);
        context.arc(x, y, r * holeSize, 0, 2 * Math.PI, true);
        
        // add colour
        context.fillStyle = "#fff";
        context.fill();
        
        // draw circle and hole - closePath
        context.closePath();

        context.restore();
    }

    // draw daughnut chart
    function drawDoughnut() {
        if (shadow) showShadow();
        drawSectors(numElements);
        if (labels) drawLabels(numElements);
    }

    // draw chart again on window resize
    window.onresize = function() {
        context.clearRect(0, 0, canvas.width, canvas.height);
        resizeCanvas();
        drawDoughnut();
    };
    
    // draw chart now
    resizeCanvas();
    drawDoughnut();
}
