function drawhistogram (incomingdata) {

var values = [];

if (incomingdata.length != 2) {
  values=incomingdata.value;
} else {

 function reducevalue(f) {
    values.push(parseFloat(f.value));
 };
  incomingdata.forEach(reducevalue);
  
}

// A formatter for counts.
var formatCount = d3.format(",.2f");

var margin = {top: 10, right: 30, bottom: 30, left: 30},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var x = d3.scale.linear()
    .domain([0, d3.max(values)])
    .range([0, width]);

var num_bins = (values.length < 10 ) ? values.length : 10 ;

var data = d3.layout.histogram()
    .bins(num_bins)
    (values);

var y = d3.scale.linear()
    .domain([0, d3.max(data, function(d) { return d.y; })])
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

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

//.attr("width", x(data[0].dx) - 1)
bar.append("rect")
    .attr("x", 1)
    .attr("width", ((width-margin.left-margin.right)/20))
    .attr("height", function(d) { return height - y(d.y); });

bar.append("text")
    .attr("dy", ".75em")
    .attr("y", 6)
    .attr("x", x(data[0].dx) / 2)
    .attr("text-anchor", "middle")
    .text(function(d) { return "£" + formatCount(d.y); });

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
}
