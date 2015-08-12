<html>
<head>

</head>
<body>
<svg width="600" height="600">

</svg>
<script type="text/javascript">
    /**
     * SVG Fun, a small test of the posibilities with SVG and JavaScript
     * @constructor
     */
    function SvgFun() {
        this.svg = document.querySelector('svg');
    };

    /**
     * Helper function to set multiple attributes at once
     */
    SvgFun.prototype.setAttributes = function(el, obj) {
        for(var key in obj) {
            el.setAttribute(key, obj[key]);
        }
    };

    /**
     * Create a SVG node
     */
    SvgFun.prototype.el = function(name, attr) {
        var el = document.createElementNS('http://www.w3.org/2000/svg', name);
        if(attr) {
            this.setAttributes(el, attr);
        }
        return el;
    };

    /**
     * Draw a debug circle
     */
    SvgFun.prototype.debugCircle = function(x, y, attr) {
        var circle = this.el(
            'circle',
            {
                cx : x,
                cy : y,
                r : 3,
                stroke : 'black',
                fill : 'none',
                "stroke-width" : 1
            }
        );
        if(attr) {
            this.setAttributes(circle, attr);
        }
        this.svg.appendChild(circle);
    };

    /**
     * Demo 1, draw a line from XY1 to XY2:
     */
    SvgFun.prototype.demo1 = function() {
        var x1 = 100, y1 = 500, x2 = 500, y2 = 500;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
    };

    /**
     * Demo 2, find the center of the line:
     */
    SvgFun.prototype.demo2 = function() {
        var x1 = 100, y1 = 500, x2 = 500, y2 = 500;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
        // The center:
        var cX = x1 + (x2 - x1) / 2;
        var cY = y1 + (y2 - y1) / 2;
        this.debugCircle(cX, cY, {stroke : 'red'});
    };

    /**
     * Demo 3, find the center of the line:
     */
    SvgFun.prototype.demo3 = function() {
        var x1 = 100, y1 = 500, x2 = 500, y2 = 400;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
        // The center:
        var cX = x1 + (x2 - x1) / 2;
        var cY = y1 + (y2 - y1) / 2;
        this.debugCircle(cX, cY, {stroke : 'red'});
        // Find the perpendicular line:
        // To get this, we must now the direction of the line and calculate it's normal:
        var dX = x2 - x1;
        var dY = y2 - y1;
        // Create unit vectors:
        var uV = Math.sqrt(Math.pow(dX, 2) + Math.pow(dY, 2));
        var uX = dX / uV;
        var uY = dY / uV;
        // Get the normal:
        var n1 = {x: -uY, y: uX};
        var n2 = {x: uY, y: -uX};
        this.debugCircle(cX + (30 * n1.x), cY + (30 * n1.y), {stroke: 'green'});
        this.debugCircle(cX + (30 * n2.x), cY + (30 * n2.y), {stroke: 'blue'});
        // Draw a line for dramatic effect:
        var line2 = this.el('line', {x1:n1.x, y1:n1.y, x2:n2.x, y2:n2.y, stroke:'magenta'});
        this.svg.appendChild(line2);
    };

    /**
     * Demo 4, segmentation:
     */
    SvgFun.prototype.demo4 = function() {
        var x1 = 200, y1 = 500, x2 = 400, y2 = 500;
        var height = 400;
        var segments = 4;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
        // The center:
        var cX = x1 + (x2 - x1) / 2;
        var cY = y1 + (y2 - y1) / 2;
        this.debugCircle(cX, cY, {stroke : 'red'});
        // Find the perpendicular line:
        // To get this, we must now the direction of the line and calculate it's normal:
        var dX = x2 - x1;
        var dY = y2 - y1;
        // Create unit vectors:
        var uV = Math.sqrt(Math.pow(dX, 2) + Math.pow(dY, 2));
        var uX = dX / uV;
        var uY = dY / uV;
        // Get the normal:
        var n1 = {x: -uY, y: uX};
        var n2 = {x: uY, y: -uX};
        this.debugCircle(cX + (30 * n1.x), cY + (30 * n1.y), {stroke: 'green'});
        this.debugCircle(cX + (30 * n2.x), cY + (30 * n2.y), {stroke: 'blue'});
        // Create segments:
        var segmentHeight = height / segments;
        var sX = cX;
        var sY = cY;
        for(var i=0; i<segments; i++) {
            // We move up from the center to the next vector:
            // Get the next X,Y coordinate:
            var nextX = sX + n2.x * (segmentHeight);
            var nextY = sY + n2.y * (segmentHeight);
            this.debugCircle(nextX, nextY);
            var sLine = this.el('line', {x1:sX, y1:sY, x2:nextX, y2:nextY, stroke:'#cccccc'});
            this.svg.appendChild(sLine);
            sX = nextX;
            sY = nextY;
        }
    };

    /**
     * Demo 5, segmentation:
     */
    SvgFun.prototype.demo5 = function() {
        var x1 = 200, y1 = 500, x2 = 400, y2 = 400;
        var height = 400;
        var segments = 4;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
        // The center:
        var cX = x1 + (x2 - x1) / 2;
        var cY = y1 + (y2 - y1) / 2;
        this.debugCircle(cX, cY, {stroke : 'red'});
        // Find the perpendicular line:
        // To get this, we must now the direction of the line and calculate it's normal:
        var dX = x2 - x1;
        var dY = y2 - y1;
        // Create unit vectors:
        var uV = Math.sqrt(Math.pow(dX, 2) + Math.pow(dY, 2));
        var uX = dX / uV;
        var uY = dY / uV;
        // Get the normal:
        var n1 = {x: -uY, y: uX};
        var n2 = {x: uY, y: -uX};
        this.debugCircle(cX + (30 * n1.x), cY + (30 * n1.y), {stroke: 'green'});
        this.debugCircle(cX + (30 * n2.x), cY + (30 * n2.y), {stroke: 'blue'});
        // Create segments:
        var segmentHeight = height / segments;
        var sX = cX;
        var sY = cY;
        // Give a little bit offset in radians:
        var directionOffset = 0.3;
        var cosOffset = Math.cos(directionOffset);
        var sinOffset = Math.sin(directionOffset);
        for(var i=0; i<segments; i++) {
            // We move up from the center to the next vector:
            // Get the next X,Y coordinate:
            var nextX = sX + n2.x * (segmentHeight);
            var nextY = sY + n2.y * (segmentHeight);
            this.debugCircle(nextX, nextY);
            var sLine = this.el('line', {x1:sX, y1:sY, x2:nextX, y2:nextY, stroke:'#cccccc'});
            this.svg.appendChild(sLine);
            // 'rotate' the normal:
            var pX = n2.x * cosOffset - n2.y * sinOffset;
            var pY = n2.x * sinOffset + n2.y * cosOffset;
            n2.x = pX;
            n2.y = pY;
            sX = nextX;
            sY = nextY;
        }
    };

    /**
     * Create unit normal according to direction
     */
    SvgFun.prototype.unitNormal = function(x1, y1, x2, y2) {
        var dX = x2 - x1;
        var dY = y2 - y1;
        // Create unit vectors:
        var uV = Math.sqrt(Math.pow(dX, 2) + Math.pow(dY, 2));
        var uX = dX / uV;
        var uY = dY / uV;
        var n1 = {x: -uY, y: uX};
        var n2 = {x: uY, y: -uX};
        return {n1:n1, n2:n2};
    };

    /**
     * Demo 6, segmentation:
     */
    SvgFun.prototype.demo6 = function() {
        var x1 = 200, y1 = 500, x2 = 400, y2 = 400;
        var height = 400;
        var segments = 4;
        this.debugCircle(x1, y1);
        this.debugCircle(x2, y2);
        var line = this.el('line', {x1:x1, y1:y1, x2:x2, y2:y2, stroke:'black'});
        this.svg.appendChild(line);
        // The center:
        var cX = x1 + (x2 - x1) / 2;
        var cY = y1 + (y2 - y1) / 2;
        this.debugCircle(cX, cY, {stroke : 'red'});
        // Find the perpendicular line:
        // To get this, we must now the direction of the line and calculate it's normal:
        var dX = x2 - x1;
        var dY = y2 - y1;
        // Create unit vectors:
        var uV = Math.sqrt(Math.pow(dX, 2) + Math.pow(dY, 2));
        var uX = dX / uV;
        var uY = dY / uV;
        // Get the normals:
        var n1 = {x: -uY, y: uX};
        var n2 = {x: uY, y: -uX};
        this.debugCircle(cX + (30 * n1.x), cY + (30 * n1.y), {stroke: 'green'});
        this.debugCircle(cX + (30 * n2.x), cY + (30 * n2.y), {stroke: 'blue'});
        // Create segments:
        var segmentHeight = height / segments;
        var sX = cX;
        var sY = cY;
        // Give a little bit offset in radians:
        var directionOffset = 0.3;
        var cosOffset = Math.cos(directionOffset);
        var sinOffset = Math.sin(directionOffset);
        // Get the distance to the center (thanks to Pythagoras):
        var distanceToCenter = Math.sqrt(Math.pow(cX - x1, 2) + Math.pow(cY - y1, 2));
        var segmentWidth = distanceToCenter / segments;
        for(var i=0; i<segments; i++) {
            // We move up from the center to the next vector:
            // Get the next X,Y coordinate:
            var nextX = sX + n2.x * (segmentHeight);
            var nextY = sY + n2.y * (segmentHeight);
            this.debugCircle(nextX, nextY);
            var sLine = this.el('line', {x1:sX, y1:sY, x2:nextX, y2:nextY, stroke:'#cccccc'});
            this.svg.appendChild(sLine);
            // 'rotate' the normal:
            var pX = n2.x * cosOffset - n2.y * sinOffset;
            var pY = n2.x * sinOffset + n2.y * cosOffset;
            n2.x = pX;
            n2.y = pY;
            // Create normals for each node:
            var sNormal = this.unitNormal(sX, sY, nextX, nextY);
            // Debug circles:
            var distance = segmentWidth * (segments - i - 1);
            var lX = nextX + (distance * sNormal.n2.x);
            var lY = nextY + (distance * sNormal.n2.y);
            var rX = nextX + (distance * sNormal.n1.x);
            var rY = nextY + (distance * sNormal.n1.y);
            this.debugCircle(rX, rY, {stroke: 'green'});
            this.debugCircle(lX, lY, {stroke: 'blue'});
//            var sLine2 = this.el('line', {x1:sX, y1:sY, x2:nextX, y2:nextY, stroke:'#cccccc'});
//            this.svg.appendChild(sLine);
            sX = nextX;
            sY = nextY;
        }
    };

    var svgFun = new SvgFun();
    svgFun.demo6();
</script>
</body>
</html>