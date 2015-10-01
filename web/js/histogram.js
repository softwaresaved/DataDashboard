function drawhistogram (incomingdata) {

var values = [];

/**
*   Method to cast to float and extract
*   Temporary rule in this to ignore anything that looks like an hourly
*   rate, a conversion from another currency or a typo in the data.
*/
var reduce = function (v) {
    k = parseFloat(v);
    if ( k < 120000.0) {
        values.push(k);
    }
};

// Get the values from the incoming data 
var _tmp_value = incomingdata.value;
_tmp_value.forEach(reduce);

// A formatter for counts.
var formatCount = d3.format(",.2f");

// Set the margins and side 
var margin = {top: 10, right: 30, bottom: 30, left: 30},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

/**
*  Create the X variables and scale to the max value in permitted values in domain
*  Range is kept within the chart width
*/
var x = d3.scale.linear()
    .domain([0, d3.max(values)])
    .range([0, width]);

//  Calculate the number of bins in the chart
var num_bins = (values.length < 12 ) ? values.length : 12 ;

//  Create the data var with D3
var data = d3.layout.histogram()
    .bins(num_bins)
    (values);

/**
*  Create the Y variables and scale to the max value in permitted values in domain
*  Range is kept within the chart width
*/
var y = d3.scale.linear()
    .domain([0, d3.max(data, function(d) { return d.y; })])
    .range([height,0]);

var bar_width = width/num_bins;

//  Create the X axis
var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");
/**
*   Create the graph elements.
*   First create and attach the SVG element
*   The create the bar and complete the bar
*/
var svg = d3.select("#chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var bar = svg.selectAll(".bar")
    .data(data)
    .enter().append("g")
    .attr("class", "bar")
    .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

bar.append("rect")
    .attr("x", 1)
    .attr("width",(bar_width - 2))
    .attr("height", function(d) { if (height > 0) { return height -  y(d.y); } else {return 1; }});

/*bar.append("text")
    .attr("dy", ".75em")
    .attr("y", 6)
    .attr("x", bar_width)
    .attr("text-anchor", "left")
    .text(function(d) { console.log(d[(d.y-11)]);  return "£" + formatCount(d[(d.y-11)]); });
*/
//  Add the X axis to the SVG image
svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
}
