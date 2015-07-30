function drawhistogram (incomingdata) {

var values=[];

function reducevalue(f) {
   values.push(parseFloat(f.value));
};

incomingdata.forEach(reducevalue);

//var values = d3.range(1000).map(d3.random.logNormal(Math.log(30), .4));
// A formatter for counts.
var formatCount = d3.format(",.2f");

var margin = {top: 10, right: 30, bottom: 30, left: 30},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var x = d3.scale.linear()
    .domain([0, 120])
    .range([0, width]);

var data = d3.layout.histogram()
    .bins(x.ticks(10))
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

bar.append("rect")
    .attr("x", 1)
    .attr("width", x(data[0].dx) - 1)
    .attr("height", function(d) { return height - y(d.y); });
var max = d3.max(d3.values(data));
console.log(max);
console.log(data);
bar.append("text")
    .attr("dy", ".75em")
    .attr("y", 6)
    .attr("x", x(data[0].dx) / 2)
    .attr("text-anchor", "middle")
    .text(function(d) { return "£" + formatCount(d[(d.length -1)]); });

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
}